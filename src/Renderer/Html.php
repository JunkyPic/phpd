<?php

namespace Phpd\Renderer;

use Phpd\Config\Config;
use Phpd\Config\ConfigLoaderException;

/**
 * Class Html
 *
 * @package Phpd\Renderer
 */
final class Html implements RendererInterface
{
    /**
     * @var array 
     */
    private $classes
        = [
            'li-class'      => 'c-li',
            'ul-class'      => 'c-ul',
            'li-span-class' => 'li-sc',
            'bool'          => 'c-bool',
            'int'           => 'c-int',
            'float'         => 'c-float',
            'string'        => 'c-string',
            'array'         => 'c-array',
            'object'        => 'c-object',
            'resource'      => 'c-resource',
            'null'          => 'c-null',
            'callable'      => 'c-callable',
            'unknown'       => 'c-unknown',
        ];


    /**
     * @var string
     */
    private $html = '';

    /**
     * @var null|Config
     */
    private $config = null;

    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    /**
     * @param $class
     *
     * @return mixed|string
     */
    public function getClass(string $class) :string {
        return isset($this->classes[$class]) ? $this->classes[$class] : '';
    }

    /**
     * @return null|Html
     * @throws ConfigLoaderException
     */
    public function getConfig() : ?self
    {
        if (null === $this->config) {
            throw new ConfigLoaderException("Config is not yet loaded");
        }

        return $this->config;
    }

    /**
     * @return Html
     * @throws \Phpd\Config\ConfigLoaderException
     */
    public function start(): self
    {
        if (null === $this->config) {
            $this->config = (new Config())->load();
        } else {
            $this->config->load();
        }

        if ( ! $this->config->simpleMode()) {
            if ( ! $this->config->styleLoaded()) {
                $this->html .= '<style>'.$this->config->loadStyles().'</style>';

            }
        }

        $this->html .= '<pre>';

        return $this;
    }

    /**
     * @param null|string $string
     * @param             $class
     *
     * @return Html
     */
    public function append(string $string, ?string $class = null): self
    {
        $this->html .= sprintf('<span class="%s %s">%s</span>',
            $this->getClass('li-span-class'),
            null !== $class ? $class : '',
            $string
        );

        return $this;
    }

    /**
     * @param string|null $class
     *
     * @return Html
     */
    public function startUl(?string $class = null): self
    {
        $this->html .= sprintf('<ul class="%s %s">',
            $this->getClass('ul-class'),
            null !== $class ? $class : ''
        );

        return $this;
    }

    /**
     * @return Html
     */
    public function endUl(): self
    {
        $this->html .= '</ul>';

        return $this;
    }

    /**
     * @param null|string $class
     *
     * @return Html
     */
    public function startLi(?string $class = null): self
    {
        $this->html .= sprintf('<li class="%s %s">',
            $this->getClass('li-class'),
            null !== $class ? $class : ''
        );

        return $this;
    }

    /**
     * @return Html
     */
    public function endLi(): self
    {
        $this->html .= '</li>';

        return $this;
    }

    /**
     * @return string
     */
    public function end(): string
    {
        $this->html .= '</pre>';
        $return = $this->html;
        $this->html = '';

        return $return;
    }

}

