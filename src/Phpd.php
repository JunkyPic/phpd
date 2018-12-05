<?php

namespace Phpd;

use Phpd\Config\Config;
use Phpd\Config\ConfigLoaderException;
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
     * @return string
     * @throws ConfigLoaderException
     */
    private static function startDump($args): string
    {
        $html = new Html();
        $html = $html->setConfig((new Config())->load());
        $html->getConfig()->load();
        $builder = new Builder($html);

        foreach ($args[0] as $arg) {
            return $builder->build($arg);
        }
    }
}
