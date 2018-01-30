<?php

/**
 * @return bool
 */
function isCli(): bool {
    return php_sapi_name() === 'cli';
}

/**
 * @param array ...$args
 * @throws Exception
 */
function dump(...$args): void {
    \Phpd\Phpd::dump($args);
}