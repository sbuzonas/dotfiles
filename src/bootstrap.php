<?php

/**
 * This file is part of Dotfiles.
 *
 * (c) Steve Buzonas <steve@fancyguy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$includeIfExists = function ($file)
{
    return file_exists($file) ? include $file : false;
};

if (!$loader = $includeIfExists(__DIR__.'/../vendor/autoload.php')) {
    echo 'You must set up the project dependencies, run the following commands:'.PHP_EOL.
         'curl -sS https://getcomposer.org/installer | php'.PHP_EOL.
         'php composer.phar install'.PHP_EOL;
    exit(1);
}

unset($includeIfExists);

return $loader;
