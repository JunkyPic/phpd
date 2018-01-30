<?php

namespace Phpd;

use Phpd\Renderer\Cli;
use Phpd\Renderer\Html;

/**
 * Class Builder
 * @package Phpd
 */
class Builder {
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_RESOURCE = 'resource';
    const TYPE_NULL = 'null';
    const TYPE_CALLABLE_CALLBACK = 'callable';

    private $html = '';

    /**
     * @var
     */
    private $input;

    /**
     * @param $input
     * @return $this
     */
    public function setInput($input) {
        $this->input = $input;
        return $this;
    }

    /**
     * @param $arg
     *
     * @return string
     */
    private function getType($arg)
    {
        switch ($arg) {
            case is_callable($arg):
                return self::TYPE_CALLABLE_CALLBACK;
            case is_array($arg):
                return self::TYPE_ARRAY;
            case is_object($arg):
                return self::TYPE_OBJECT;
            case is_string($arg):
                return self::TYPE_STRING;
            case is_resource($arg):
                return self::TYPE_RESOURCE;
            case is_null($arg):
                return self::TYPE_NULL;
            case is_bool($arg):
                return self::TYPE_BOOLEAN;
            case (int)$arg === $arg:
                return self::TYPE_INTEGER;
            case is_float($arg):
                return self::TYPE_FLOAT;
        }
    }

    public function build() {

        if (php_sapi_name() === 'cli') {
            $renderer = new Cli();
        } else {
            $renderer = new Html();
        }
//
//        $this->html .= $renderer->getHeader();
//        $this->html .= '<dd>Coffee</dd><dd>Coffee2</dd>';
//        $this->html .= $renderer->getFooter();

        return '       
    <dt>Name: </dt>
    <dt>John Don</dt>
';
    }

}