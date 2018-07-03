<?php

namespace Phpd;

use Phpd\Renderer\Cli;
use Phpd\Renderer\Html;
use Phpd\Renderer\RendererInterface;

/**
 * Class Builder
 *
 * @package Phpd
 */
class Builder
{
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
     * @var Cli
     */
    private $renderer;

    /**
     * Builder constructor.
     *
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        if ($renderer instanceof Html) {
            $this->renderer = new Html();
        }
    }

    /**
     * @param $input
     *
     * @return string
     * @throws Config\ConfigLoaderException
     */
    public function build($input)
    {
        if(is_bool($input)) {
            return $this->wrapGeneric($input ? 'true' : 'false', self::TYPE_BOOLEAN);
        }

        if(is_integer($input)) {
            return $this->wrapGeneric($input, self::TYPE_INTEGER);
        }

        if(is_float($input)) {
            return $this->wrapGeneric($input, self::TYPE_FLOAT);
        }

        if(is_string($input)) {
            return $this->wrapGeneric($input, self::TYPE_STRING);
        }
    }

    /**
     * @param $input
     * @param $type
     *
     * @return string
     * @throws Config\ConfigLoaderException
     */
    private function wrapGeneric($input, $type) {
        return $this
            ->renderer
            ->start()
            ->startUl()
            ->startLi()
            ->append($type)
            ->startUl()
            ->wrapToLi($input)
            ->endUl()
            ->endLi()
            ->endUl()
            ->end();
    }

}