<?php

namespace Phpd;

use Phpd\Renderer\Html;
use Phpd\Renderer\RendererInterface;

/**
 * Class Builder
 *
 * @package Phpd
 */
class Builder
{
    const TYPE_BOOLEAN = 'bool';
    const TYPE_INTEGER = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_RESOURCE = 'resource';
    const TYPE_NULL = 'null';
    const TYPE_CALLABLE_CALLBACK = 'callable';
    const TYPE_UNKNOWN = 'unknown';

    /**
     * @var RendererInterface
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
            $this->renderer = $renderer;
        }
    }

    /**
     * @param $input
     *
     * @return Html|string
     * @throws Config\ConfigLoaderException
     * @throws \ReflectionException
     */
    public function build($input)
    {
        $this->renderer->start();

        $type = $this->getType($input);

        switch ($type) {
            case self::TYPE_BOOLEAN:
                return $this->wrapGeneric($input ? 'true' : 'false', $type);
            case self::TYPE_INTEGER:
                return $this->wrapGeneric($input, $type)->end();
            case self::TYPE_FLOAT:
                return $this->wrapGeneric($input, $type)->end();
            case self::TYPE_STRING:
                return $this->wrapGeneric($input, $type)->end();
            case self::TYPE_ARRAY:
                return $this->wrapArray($input);
            case self::TYPE_OBJECT:
                return $this->wrapObject($input);
            case self::TYPE_RESOURCE:
                return self::TYPE_RESOURCE;
            case self::TYPE_NULL:
                return $this->wrapGeneric($input, $type)->end();
            case self::TYPE_CALLABLE_CALLBACK:
                return self::TYPE_CALLABLE_CALLBACK;
            default:
                return self::TYPE_UNKNOWN;
        }
    }

    /**
     * @param $input
     *
     * @return string
     * @throws Config\ConfigLoaderException
     */
    private function wrapArray($input)
    {
        $this
            ->renderer
            ->start()
            ->startUl()
            ->startLi()
            ->append(self::TYPE_ARRAY.' ('.count($input).')', $this->renderer->getConfig()
                ->get($this->getType($input)));

        return $this->wrapArrayRecursive($input)
            ->endUl()
            ->endLi()
            ->end();
    }

    /**
     * @param $input
     *
     * @return string
     * @throws Config\ConfigLoaderException
     * @throws \ReflectionException
     */
    private function wrapObject($input)
    {
        $reflection = new \ReflectionClass($input);

        $properties = $reflection->getDefaultProperties();

        $this
            ->renderer
            ->start()
            ->startUl()
            ->startLi()
            ->append('Properties', 'c-null')
            ->endLi()
            ->startUl()
            ->startLi()
            ->append(self::TYPE_ARRAY.' ('.count($properties).')', $this->renderer->getConfig()
                ->get($this->getType($properties)))
            ->endLi()
            ->startLi();

        return $this->wrapArrayRecursive($properties)
            ->endLi()
            ->endUl()
            ->endUl()
            ->end();
    }

    /**
     * @param $array
     *
     * @return Html|RendererInterface
     * @throws Config\ConfigLoaderException
     */
    private function wrapArrayRecursive($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this
                    ->renderer
                    ->startUl()
                    ->startLi()
                    ->append(self::TYPE_ARRAY.' ('.count($value).') => ',
                        $this->renderer->getConfig()
                            ->get($this->getType($value)))
                    ->endLi();

                $this->wrapArrayRecursive($value);

                $this
                    ->renderer
                    ->endUl()
                    ->endLi();
            } else {
                $type = sprintf('(%s) %s => ', $this->getType($value), $key);
                $this
                    ->renderer
                    ->startUl()
                    ->startLi();

                if (null === $value) {
                        $this->renderer
                        ->append($type,
                            $this->renderer->getConfig()
                                ->get($this->getType($value)))
                        ->endLi()
                        ->startLi()
                        ->append('null', $this->renderer->getConfig()
                            ->get($this->getType($value)));
                }
                elseif (is_bool($value)) {
                    $this->renderer
                        ->append($type,
                            $this->renderer->getConfig()
                                ->get($this->getType($value)))
                        ->endLi()
                        ->startLi()
                        ->append(false === $value ? 'false' : 'true', $this->renderer->getConfig()
                            ->get($this->getType($value)));

                }
                else {
                    $this->renderer
                        ->append($type,
                            $this->renderer->getConfig()
                                ->get($this->getType($value)))
                        ->endLi()
                        ->startLi()
                        ->append($value, $this->renderer->getConfig()
                            ->get($this->getType($value)));
                }

                $this->renderer
                    ->endLi()
                    ->endUl();
            }
        }

        return $this->renderer;
    }

    /**
     * @param $variable
     *
     * @return null|string
     */
    protected function getType($variable)
    {
        // I have no idea why but
        // case is_bool($variable)
        // doesn't work with switch
        if (is_bool($variable)) {
            return self::TYPE_BOOLEAN;
        }

        // Same thing here, no idea why...yet
        if (is_null($variable)) {
            return self::TYPE_NULL;
        }

        if(is_array($variable)) {
            return self::TYPE_ARRAY;
        }

        switch ($variable) {
            case is_integer($variable):
                return self::TYPE_INTEGER;
            case is_float($variable):
                return self::TYPE_FLOAT;
            case is_string($variable):
                return self::TYPE_STRING;
            case is_object($variable):
                return self::TYPE_OBJECT;
            case is_resource($variable):
                return self::TYPE_RESOURCE;
            case is_callable($variable):
                return self::TYPE_CALLABLE_CALLBACK;
            default:
                // This should never happen
                return self::TYPE_UNKNOWN;
        }
    }

    /**
     * @param $input
     * @param $type
     *
     * @return Html
     * @throws Config\ConfigLoaderException
     */
    protected function wrapGeneric($input, $type)
    {
        return $this
            ->renderer
            ->startUl()
            ->startLi()
            ->append('('.$type.')', $this->renderer->getConfig()->get($type))
            ->endLi()
            ->startLi()
            ->append($input, $this->renderer->getConfig()->get($type))
            ->endLi()
            ->endUl();
    }

}