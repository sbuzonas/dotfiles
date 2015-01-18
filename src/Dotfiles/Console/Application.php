<?php

/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles\Console;

use Dotfiles\Command;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{

    const VERSION = '0.1.0';
    
    private static $asciiLogo = <<<EOASCII
________      _________________________
___  __ \_______  /___  ____/__(_)__  /____________
__  / / /  __ \  __/_  /_   __  /__  /_  _ \_  ___/
_  /_/ // /_/ / /_ _  __/   _  / _  / /  __/(__  )
/_____/ \____/\__/ /_/      /_/  /_/  \___//____/


EOASCII;

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        if (function_exists('ini_set') && extension_loaded('xdebug')) {
            ini_set('xdebug.show_exception_trace', false);
            ini_set('xdebug.scream', false);
        }

        if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
            date_default_timezone_set(@date_default_timezone_get());
        }

        parent::__construct('DotFiles', self::VERSION);
    }

    public function getHelp()
    {
        return self::$asciiLogo . parent::getHelp();
    }

    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function getLogger() {
        return $this->logger;
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        
        $defaultCommands[] = new Command\InstallCommand();
        $defaultCommands[] = new Command\Installer\BashCommand();
		$defaultCommands[] = new Command\Installer\GitCommand();
        $defaultCommands[] = new Command\Installer\TmuxCommand();
        $defaultCommands[] = new Command\Installer\WebCommand();
        
        return $defaultCommands;
    }

    public function doRun(InputInterface $input, OutputInterface $output) {
        if (!$this->logger) {
            $this->logger = new ConsoleLogger($output);
        }
        parent::doRun($input, $output);
    }

}
