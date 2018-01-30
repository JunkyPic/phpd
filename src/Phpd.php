<?php

namespace Phpd;

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
        if (!isset($args[0])) {
            throw new \Exception('Unable to determine arguments');
        }

        $builder = new Builder();

        echo $builder->setInput($args[0])->build();
    }
}