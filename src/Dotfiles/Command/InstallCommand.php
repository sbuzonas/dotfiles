<?php

/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{

    protected $installedModules = array();
    protected $currentModule;
    protected $installMode;
	protected $runnerDepth = 0;
    
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Installs the dotfiles in this repository using the guided wizard')
            ->setHelp(<<<EOT
The <info>install</info> command guides you through installing the different modules
included in this dotfiles distribution.
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$this->runnerDepth++;
        if ($input->isInteractive()) {
            while ('q' !== $this->currentModule && $this->runnerDepth <= 1) {
                $modules = $this->getUninstalledModules();
                $this->runModuleCommand($modules[$this->currentModule], $input, $output);
                if (count($this->getUninstalledModules()) > 0) {
                    $this->run($input, $output);
                } else {
                    $this->getLogger()->notice('Nothing left to install.');
                    break;
                }
            }
        }
		$this->runnerDepth--;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$this->installMode) {
            $this->installMode = $this->selectInstallationMethod($input, $output);
        }

        if ('q' === $this->installMode) {
            $this->currentModule = 'q';
        } else if ('a' === $this->installMode) {
            $this->currentModule = 0;
        } else {
            $this->currentModule = $this->selectModuleToInstall($input, $output);
        }
    }

    protected function selectModuleToInstall(InputInterface $input, OutputInterface $output)
    {
        $choices = $this->getModuleChoices();
        $choices[] = "<comment>q</comment>: Quit the installer\n";
        $choices[] = "<question>Choose a module to install:</question> ";

        return $this->getHelper('dialog')->askAndValidate($output, $choices, function ($selectedModule) {
            $selectedModule = is_numeric($selectedModule) ? $selectedModule - 1 : $selectedModule;
            if (!in_array($selectedModule, array_merge(array_keys($this->getUninstalledModules()), array('q')))) {
                throw new \InvalidArgumentException('Invalid module');
            }
            return $selectedModule;
        }, 10);
    }

    protected function selectInstallationMethod(InputInterface $input, OutputInterface $output)
    {
        $choices = array(
            "<comment>a</comment>: Install all modules\n",
            "<comment>i</comment>: Install interactively\n",
            "<comment>q</comment>: Quit the installer\n",
            "<question>Select installation method:</question> ",
        );
        return $this->getHelper('dialog')->askAndValidate($output, $choices, function ($selectedMethod) {
            if (!in_array($selectedMethod, array('a','i','q'))) {
                throw new \InvalidArgumentException('Invalid installation method');
            }
            return $selectedMethod;
        });
    }

    private function runModuleCommand($module, InputInterface $input, OutputInterface $output) {
        $command = $this->getApplication()->get($module);
        if (0 === $command->run($input, $output)) {
            $this->installedModules[] = $module;
        }
    }

    private function getModuleChoices()
    {
        $modules = $this->getUninstalledModules();
        $choices = array();
        foreach($modules as $k => $module) {
            $choices[] = sprintf("<comment>%d</comment>: %s\n", $k + 1, $module);
        }
        return $choices;
    }

    private function getUninstalledModules()
    {
        return array_values(array_diff($this->getModules(), $this->installedModules));
    }

    private function getModules()
    {
        return array_keys($this->getApplication()->all('install'));
    }
    
    protected function getLogger() {
        return $this->getApplication()->getLogger();
    }
    
}
