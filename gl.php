<?php

/**
 * @param mixed ...$args
 *
 * @throws Exception
 */
function du(...$args): void {
    \Phpd\Phpd::dump($args);
}
