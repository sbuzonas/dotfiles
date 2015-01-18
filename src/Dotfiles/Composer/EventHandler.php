<?php
/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles\Composer;

use Composer\IO\ConsoleIO;
use Composer\Script\Event;
use Dotfiles\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class EventHandler
{

	public static function postCreateProject(Event $event)
	{
		$io = $event->getIO();
		if ($io instanceof ConsoleIO) {
			$application = new Application();
			$verbosity = '';
			switch (true) {
			case $io->isDebug():
				$verbosity .= 'v';
				// no break
			case $io->isVeryVerbose():
				$verbosity .= 'v';
				// no break
			case $io->isVerbose():
				$verbosity = ' -v' . $verbosity;
			}
			$input = new StringInput('install'.$verbosity);
			$output = new ConsoleOutput();
			$application->run($input, $output);
		}
	}
	
}
