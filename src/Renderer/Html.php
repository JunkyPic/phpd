<?php

namespace Phpd\Renderer;

class Html
{
    public function getHeader() {
        return '<pre><dl>';
    }

    public function getFooter() {
        return '</dl></pre>';
    }

    public function buildFromString($string) {

    }
}
