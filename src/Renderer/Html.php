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
     * @return Config
     * @throws ConfigLoaderException
     */
    public function getConfig() {
        if(null === $this->config) {
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
    public function append($string, $class = null): self
    {
        $this->html .= sprintf('<span class="%s %s">%s</span>',
            $this->config->get('li-span-class'),
            null !== $class ? $class : '',
            $string
        );

        return $this;
    }

    /**
     * @param null $class
     *
     * @return Html
     */
    public function startUl($class = null): self
    {
        $this->html .= sprintf('<ul class="%s %s">',
            $this->config->get('ul-class'),
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
     * @param null $class
     *
     * @return Html
     */
    public function startLi($class = null): self
    {
        $this->html .= sprintf('<li class="%s %s">',
            $this->config->get('li-class'),
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
        $this->html .= '<hr>';
        $return = $this->html;
        $this->html = '';

        return $return;
    }

}

