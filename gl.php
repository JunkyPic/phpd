<?php

/**
 * @return bool
 */
function isCli(): bool {
    return php_sapi_name() === 'cli';
}

/**
 * @param mixed ...$args
 *
 * @throws Exception
 */
function dump(...$args): void {
    \Phpd\Phpd::dump($args);
}