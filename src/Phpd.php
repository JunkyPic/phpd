<?php

namespace Phpd;

use Phpd\Config\Config;
use Phpd\Config\ConfigLoaderException;
use Phpd\Renderer\Cli;
use Phpd\Renderer\Html;

/**
 * Class Phpd
 *
 * @package phpd
 */
class Phpd
{
    /**
     * @param mixed ...$args
     */
    public static function dump(...$args): void
    {
        try {
            echo self::startDump($args);
        } catch (\Exception $exception) {
            echo $exception->__toString();
        }
    }

    /**
     * @param $args
     *
     * @return Html|string
     * @throws ConfigLoaderException
     * @throws \ReflectionException
     */
    private static function startDump($args): string
    {
        if (php_sapi_name() === 'cli') {
            $builder = new Builder(new Cli());
        } else {
            $html = new Html();
            $html->setConfig(new Config());
            $builder = new Builder($html);
        }

        foreach ($args[0] as $arg) {
            return $builder->build($arg);
        }
    }
}
