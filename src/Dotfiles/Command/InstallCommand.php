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
        if ($input->isInteractive()) {
            while ('q' !== $this->currentModule) {
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
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $choices = $this->getModuleChoices();
        $choices[] = "<comment>q</comment>: Quit the installer\n";
        $choices[] = "<question>Choose a module to install:</question> ";

        $this->currentModule = $this->getHelper('dialog')->askAndValidate($output, $choices, function ($selectedModule) {
            $selectedModule = is_numeric($selectedModule) ? $selectedModule - 1 : $selectedModule;
            if (!in_array($selectedModule, array_merge(array_keys($this->getUninstalledModules()), array('q')))) {
                throw new \InvalidArgumentException('Invalid module');
            }
            return $selectedModule;
        }, 10);
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
        return array_diff($this->getModules(), $this->installedModules);
    }

    private function getModules()
    {
        return array_keys($this->getApplication()->all('install'));
    }
    
    protected function getLogger() {
        return $this->getApplication()->getLogger();
    }
    
}
