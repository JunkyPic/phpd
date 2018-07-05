<?php

/**
 * @param mixed ...$args
 *
 * @throws Exception
 */
function dump(...$args): void {
    \Phpd\Phpd::dump($args);
}
