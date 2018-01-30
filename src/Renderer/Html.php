<?php

namespace Phpd\Renderer;

class Html
{
    public function getHeader() {
        return '<dl>';
    }

    public function getFooter() {
        return '</dl>';
    }

    public function buildFromString($string) {

    }
}
