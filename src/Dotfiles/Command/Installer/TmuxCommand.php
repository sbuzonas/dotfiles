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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class TmuxCommand extends AbstractInstallerCommand
{

    protected function getTemplates()
    {
        return array(
            '.tmux.conf' => array('tmux.conf', array('includes' => $this->getIncludes())),
        );
    }

    private function getIncludes()
    {
		try {
			$finder = new Finder();
			$finder->files()->in($this->getInstallerResourcePath().'/include');
		} catch (\InvalidArgumentException $iae) {
			$this->getLogger()->warning($iae->getMessage());
			$finder = new \ArrayIterator();
		}
        return iterator_to_array($finder);
    }
    
}
