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

class WebCommand extends AbstractInstallerCommand
{

    protected function getSymlinks()
	{
        return array(
            'wget'  => '.wgetrc',
            'curl' => '.curlrc',
        );
    }

}
