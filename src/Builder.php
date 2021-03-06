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
     * @return string
     * @throws Config\ConfigLoaderException
     */
    public function build($input): string
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
            ->startDl()
            ->startDt()
            ->append(self::TYPE_ARRAY.' ('.count($input).') =>',
                $this->renderer->getClass($this->getType($input)))
            ->endDt()
            ->startDd();

        return $this->wrapArrayRecursive($input)
            ->endDd()
            ->end();
    }

    /**
     * @param $input
     *
     * @return string
     * @throws Config\ConfigLoaderException
     */
    private function wrapObject($input)
    {
        $reflection = new \ReflectionObject($input);

        $reflectionClassMethods = $reflection->getMethods();

        $properties = $reflection->getDefaultProperties();

        $this->renderer->start()->startUl()
            ->startLi()
            ->append('('.self::TYPE_OBJECT.') '.$reflection->getName(),
                'c-null')
            ->endLi()
            ->startUl()
            ->startLi()
            ->append('Internal - ', 'c-bool')->endLi()
            ->startLi()
            ->startLi()
            ->append($reflection->isInternal() ? 'true' : 'false', 'c-bool')
            ->endLi()
            ->endUl()
            ->startUl()
            ->startLi()
            ->append('Instantiable - ', 'c-bool')->endLi()
            ->startLi()
            ->startLi()
            ->append($reflection->isInstantiable() ? 'true' : 'false', 'c-bool')
            ->endLi()
            ->endUl()
            ->startUl()
            ->startLi()
            ->append('Clonable - ', 'c-bool')->endLi()
            ->startLi()
            ->startLi()
            ->append($reflection->isCloneable() ? 'true' : 'false', 'c-bool')
            ->endLi()
            ->endUl()
            ->startUl()
            ->startLi()
            ->append('Properties', 'c-null')
            ->endLi()
            ->startUl()
            ->startLi()
            ->append(self::TYPE_ARRAY.' ('.count($properties).') =>',
                $this->renderer->getClass($this->getType($properties)))
            ->endLi()
            ->startLi();

        foreach ($reflectionClassMethods as $key => $method) {

        }

        return $this->wrapArrayRecursive($properties)
            ->endLi()
            ->endUl()
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
                    ->startDl()
                    ->startDt()
                    ->append($key.' ('.self::TYPE_ARRAY.' '.count($value)
                        .') => ',
                        $this->renderer->getClass($this->getType($value)))
                    ->endDt();

                $this->wrapArrayRecursive($value);

                $this
                    ->renderer
                    ->startDl();
            } else {
                $type = sprintf('%s => ', $key);
                $typeValueClass = $this->getType($value);

                if ($typeValueClass === self::TYPE_STRING) {
                    $typeValue = sprintf('(%s %s) %s', $typeValueClass,
                        strlen($value), $value);
                } else {
                    if (null === $value) {
                        $typeValue = sprintf('%s', $append = '(null) null');
                    } elseif (is_bool($value)) {
                        $typeValue = sprintf('%s', false === $value ? '(bool) false' : '(bool) true');
                    } else {
                        $typeValue = sprintf('(%s) %s', $typeValueClass, $value);
                    }
                }

                $this->renderer
                    ->startDl()
                    ->startDt()
                    ->append($type,
                        $this->renderer->getClass($typeValueClass))
                    ->endDt()
                    ->startDd()
                    ->append($typeValue,
                        $this->renderer->getClass($typeValueClass))
                    ->endDd()
                    ->endDl();
            }
        }

        return $this->renderer;
    }

    protected function wrapGeneric($input, $type)
    {
        return $this
            ->renderer
            ->startDl()
            ->startDt()
            ->append('('.$type.') ', $this->renderer->getClass($type))
            ->append($input, $this->renderer->getClass($type))
            ->endDt()
            ->endDl();
    }

    /**
     * @param $variable
     *
     * @return null|string
     */
    protected function getType($variable): string
    {
        if (is_bool($variable)) {
            return self::TYPE_BOOLEAN;
        }

        if (is_null($variable)) {
            return self::TYPE_NULL;
        }

        if (is_array($variable)) {
            return self::TYPE_ARRAY;
        }

        if (is_integer($variable)) {
            return self::TYPE_INTEGER;
        }

        switch ($variable) {
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
}