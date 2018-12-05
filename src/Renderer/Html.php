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
            'dl-class'   => 'dlc',
            'dt-class'   => 'dtc',
            'dd-class'   => 'ddc',
            'span-class' => 'span-class',
            'bool'       => 'd-bool',
            'int'        => 'd-int',
            'float'      => 'd-float',
            'string'     => 'd-string',
            'array'      => 'd-array',
            'object'     => 'd-object',
            'resource'   => 'd-resource',
            'null'       => 'd-null',
            'callable'   => 'd-callable',
            'unknown'    => 'd-unknown',
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
     *
     * @return Html
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param $class
     *
     * @return mixed|string
     */
    public function getClass(string $class): string
    {
        return isset($this->classes[$class]) ? $this->classes[$class] : '';
    }

    /**
     * @return null|Config
     * @throws ConfigLoaderException
     */
    public function getConfig(): ?Config
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

        $this->html .= '<div class="phpd">';

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
            $this->getClass('span-class'),
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
    public function startDl(?string $class = null): self
    {
        $this->html .= sprintf('<dl class="%s %s">',
            $this->getClass('dl-class'),
            null !== $class ? $class : ''
        );

        return $this;
    }

    /**
     * @return Html
     */
    public function endDl(): self
    {
        $this->html .= '</dl>';

        return $this;
    }

    /**
     * @param null|string $class
     *
     * @return Html
     */
    public function startDt(?string $class = null): self
    {
        $this->html .= sprintf('<dt class="%s %s">',
            $this->getClass('dt-class'),
            null !== $class ? $class : ''
        );

        return $this;
    }

    /**
     * @param null|string $class
     *
     * @return Html
     */
    public function startDd(?string $class = null): self
    {
        $this->html .= sprintf('<dd class="%s %s">',
            $this->getClass('dd-class'),
            null !== $class ? $class : ''
        );

        return $this;
    }

    /**
     * @return Html
     */
    public function endDd(): self
    {
        $this->html .= '</dd>';

        return $this;
    }

    /**
     * @return Html
     */
    public function endDt(): self
    {
        $this->html .= '</dt>';

        return $this;
    }

    /**
     * @return string
     */
    public function end(): string
    {
        $this->html .= '</div>';
        $return = $this->html;
        $this->html = '';

        return $return;
    }

}

