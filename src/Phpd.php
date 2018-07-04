<?php

namespace Phpd;

use Phpd\Config\Config;
use Phpd\Renderer\Cli;
use Phpd\Renderer\Html;

/**
 * Class Phpd
 * @package phpd
 */
class Phpd
{
    /**
     * @param array ...$args
     * @throws \Exception
     */
    public static function dump(...$args)
    {
        if(php_sapi_name() === 'cli') {
            $builder = new Builder(new Cli());
        } else {
            $html = new Html();
            $html->setConfig(new Config());
            $builder = new Builder($html);
        }

        foreach($args[0] as $arg) {
            echo $builder->build($arg);
        }
    }
}