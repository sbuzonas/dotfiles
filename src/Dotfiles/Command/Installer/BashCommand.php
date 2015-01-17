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

class BashCommand extends AbstractInstallerCommand
{

    protected function getSymlinks() {
        return array(
            'bashrc'  => '.bashrc',
            'profile' => '.bash_profile',
			'logout'  => '.bash_logout'
        );
    }

}
