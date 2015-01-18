<?php
/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotfiles\Command\Installer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GitCommand extends AbstractInstallerCommand
{

	/**
	 * @var string
	 */
	private $authorName;

	/**
	 * @var string
	 */
	private $authorEmail;
	
	protected function getSymlinks()
	{
		return array(
			'ignore' => '.gitignore',
		);
	}

	protected function getTemplates()
	{
		return array(
			'.gitconfig' => array('gitconfig', $this->getTemplateVariables()),
		);
	}

	protected function interact(InputInterface $input, OutputInterface $output)
	{
		$this->authorName  = $this->ask($output, '<question>What is your git author name?</question> ');
		$this->authorEmail = $this->ask($output, '<question> What is your git author email?</question> ');
	}

	private function getTemplateVariables()
	{
		try {
			$finder = new Finder();
			$finder->files()->in($this->getInstallerResourcePath().'/includes');
		} catch (\InvalidArgumentException $iae) {
			$this->getLogger()->warning($iae->getMessage());
			$finder = new \ArrayIterator();
		}

		$gitVersion = `git --version | awk '{print \$NF}'`;
		return array(
			'authorName'       => $this->authorName,
			'authorEmail'      => $this->authorEmail,
			'credentialHelper' => $this->hostIsOSX() ? 'osxkeychain' : 'cache',
			'pushDefault'      => version_compare($gitVersion, '1.7.11', '>=') ? 'simple' : 'current',
			'includes'         => iterator_to_array($finder),
		);
	}
	
}
