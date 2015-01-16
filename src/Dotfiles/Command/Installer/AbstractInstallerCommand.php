<?php

/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles\Command\Installer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

abstract class AbstractInstallerCommand extends Command
{

    protected $helpText = <<<EOT
The <info>%s</info> command installs the %s configuration
files included within this dotfiles repository.
EOT;

    public function __construct() {
        parent::__construct($this->getCommandName());
    }

    protected function getCommandName() {
        return sprintf('install:%s', $this->getInstallerName());
    }

    protected function getInstallerName() {
        return strtolower(substr(basename(str_replace('\\', DIRECTORY_SEPARATOR, get_class($this))), 0, -7));
    }

    protected function getInstallerResourcePath() {
        return $this->getDotfilesRoot() . $this->getInstallerName();
    }

    protected function getInstallerTargetPath() {
        return $this->getHomePath();
    }

    // TODO: if a windows host is a potential target figure out how to do that
    protected function getHomePath() {
        $info = posix_getpwuid(posix_getuid());
        return $info['dir'];
    }

    protected function getDotfilesRoot() {
        return strstr(__DIR__, dirname(str_replace('\\', DIRECTORY_SEPARATOR, 'src\\' . get_class($this))), true);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->installSymlinks($input);
    }
    
    protected function configure()
    {
        $this
            ->setDescription(sprintf('Installs the %s module of this dotfiles repository', $this->getInstallerName()))
            ->setHelp(sprintf($this->helpText, $this->getCommandName(), $this->getInstallerName()))
        ;
    }
    
    protected function installSymlinks(InputInterface $input) {
        $this->getLogger()->notice(sprintf('Installing symlinks'));
        $fs = new Filesystem();
        try {
            foreach ($this->getSymlinks() as $source => $target) {
                $source = sprintf('%s/%s', $this->getInstallerResourcePath(), $source);
                $target = sprintf('%s/%s', $this->getInstallerTargetPath(), $target);
                $this->getLogger()->info(sprintf('Linking <comment>%s</comment> to <comment>%s</comment>', $source, $target));
                $fs->symlink($source, $target, true);
            }
            $this->getLogger()->notice('Done linking files');
        } catch (IOExceptionInterface $ioe) {
            $this->getLogger()->error(sprintf('An error occurred while creating your symlink at %s.', $ioe->getPath()));
        }
    }

    protected function getLogger() {
        return $this->getApplication()->getLogger();
    }

    abstract protected function getSymlinks();

}
