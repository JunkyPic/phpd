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
     * @param $variable
     *
     * @return string
     */
    private function getType($variable)
    {
        switch ($variable) {
            case is_callable($variable):
                return self::TYPE_CALLABLE_CALLBACK;
            case is_array($variable):
                return self::TYPE_ARRAY;
            case is_object($variable):
                return self::TYPE_OBJECT;
            case is_string($variable):
                return self::TYPE_STRING;
            case is_resource($variable):
                return self::TYPE_RESOURCE;
            case is_null($variable):
                return self::TYPE_NULL;
            case is_bool($variable):
                return self::TYPE_BOOLEAN;
            case (int)$variable === $variable:
                return self::TYPE_INTEGER;
            case is_float($variable):
                return self::TYPE_FLOAT;
        }
    }

    public function build() {

        if (php_sapi_name() === 'cli') {
            $renderer = new Cli();
        } else {
            $renderer = new Html();
        }

        var_dump($this->input);
    }

}