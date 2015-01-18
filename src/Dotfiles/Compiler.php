<?php
/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles;

use Composer\Json\JsonFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class Compiler
{

    private $version;
    private $versionDate;
    private $versionBranch;
    
    public function compile($pharFile = 'dotfiles.phar')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $this->version = $this->getCurrentVersion();
        $this->versionDate = $this->getVersionDate();

        $phar = new \Phar($pharFile, 0, 'dotfiles.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        $finder = new Finder();
        $finder->files()
               ->ignoreVCS(true)
               ->name('*.php')
               ->notName('Compiler.php')
               ->in(__DIR__.'/..')
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $finder = new Finder();
        $finder->files()
               ->ignoreVCS(true)
               ->name('*.php')
        ;

        $vendors = array(
            'composer/composer/src/',
            'justinrainbow/json-schema/src/',
            'psr/',
            'seld/jsonlint/src/',
            'symfony/',
        );

        foreach ($vendors as $vendor) {
            $finder->in(sprintf('%s/../../vendor/%s', __DIR__, $vendor));
        }

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/autoload.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/ClassLoader.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_namespaces.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_psr4.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_classmap.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_real.php'));
        if (file_exists(__DIR__.'/../../vendor/composer/include_paths.php')) {
            $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/include_paths.php'));
        }

        $this->addDotfilesBin($phar);

        $phar->setStub($this->getStub());

        $phar->stopBuffering();

        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../LICENSE'), false);
        
        unset($phar);
    }

    public function getCurrentVersion() {
        if (!$version = $this->probeGit('describe --tags --exact-match', true)) {
            if ($branch = $this->probeGit('symbolic-ref --short')) {
                $composerBranch = sprintf('dev-%s', $branch);
                $composerConfig = __DIR__.'/../../composer.json';
                $composerFile = new JsonFile($composerConfig);
                $composerConfig = $composerFile->read();
                if (isset($composerConfig['extra']['branch-alias'][$composerBranch])) {
                    $this->versionBranch = $composerConfig['extra']['branch-alias'][$composerBranch];
                    $version = sprintf('%s@%s', $this->versionBranch, $this->probeGit('log -n1 --pretty="%h"'));
                }
            }
            $version = $version ?: $this->probeGit('log -n1 --pretty="%H"');
        }
        
        return $version;
    }

    public function getVersionDate()
    {
        $date = new \DateTime($this->probeGit('log -n1 --pretty="%ci"'));
        $date->setTimeZone(new \DateTimeZone('UTC'));

        return $date->format('Y-m-d H:i:s');
    }

    private function addFile(\Phar $phar, $file, $strip = true)
    {
        $fs = new Filesystem();
        $path = rtrim($fs->makePathRelative($file->getRealPath(), dirname(dirname(__DIR__))), DIRECTORY_SEPARATOR);
        $content = file_get_contents($file);

        if ($strip) {
            $this->stripWhitespace($content);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n".$content."\n";
        }

        if ($path == 'src/Dotfiles/Console/Application.php') {
            $content = str_replace(array('@package_version@', '@release_date@'), array($this->version, $this->versionDate), $content);
        }

        $phar->addFromString($path, $content);
    }

    private function addDotfilesBin($phar)
    {
        $content = file_get_contents(__DIR__.'/../../bin/dotfiles');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/dotfiles', $content);
    }

    private function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';

        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }

    private function probeGit($subCommand, $expectedFailure = false) {
        $command = sprintf('git %s HEAD', $subCommand);
        $process = new Process($command,__DIR__);
        if (!$expectedFailure && $process->run() != 0) {
            throw new \RuntimeException(sprintf("Can't run %s. Make sure you are compiling from the repository.", $command));
        }

        if ($process->run() == 0) {
            return trim($process->getOutput());
        }
    }
    
    private function getStub()
    {

        $stub = <<<'EOF'
#!/usr/bin/env php
<?php
/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
// Avoid APC causing random fatal errors per https://github.com/composer/composer/issues/264
if (extension_loaded('apc') && ini_get('apc.enable_cli') && ini_get('apc.cache_by_default')) {
    if (version_compare(phpversion('apc'), '3.0.12', '>=')) {
        ini_set('apc.cache_by_default', 0);
    } else {
        fwrite(STDERR, 'Warning: APC <= 3.0.12 may cause fatal errors when running composer commands.'.PHP_EOL);
        fwrite(STDERR, 'Update APC, or set apc.enable_cli or apc.cache_by_default to 0 in your php.ini.'.PHP_EOL);
    }
}
Phar::mapPhar('dotfiles.phar');
EOF;
        // add warning once the phar is older than 30 days
        if (preg_match('{^[a-f0-9]+$}', $this->version)) {
            $warningTime = time() + 30*86400;
            $stub .= "define('COMPOSER_DEV_WARNING_TIME', $warningTime);\n";
        }
                return $stub . <<<'EOF'
require 'phar://dotfiles.phar/bin/dotfiles';
__HALT_COMPILER();
EOF;
    }
    
}