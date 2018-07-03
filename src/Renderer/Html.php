<?php

namespace Phpd\Renderer;

use Phpd\Config\Config;

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
    public function setConfig(Config $config) : void
    {
        $this->config = $config;
    }

    /**
     * @return Html
     * @throws \Phpd\Config\ConfigLoaderException
     */
    public function start() : self
    {
        if (null === $this->config) {
            $config = (new Config())->load();
        }else {
            $config = $this->config->load();
        }

        if ( ! $config->simpleMode() && !$config->isStyleLoaded()) {
            $this->html .= '<style>' . $config->loadStyles() . '</style>';
        }

        $this->html .= '<pre>';

        return $this;
    }

    public function wrapToLi($string) : self {
        $this->html .= '<li>' . $string . '</li>';
        return $this;
    }

    public function append($string) : self {
        $this->html .= $string;
        return $this;
    }

    public function startUl() : self {
        $this->html .= '<ul>';
        return $this;
    }

    public function endUl() : self {
        $this->html .= '</ul>';
        return $this;
    }

    public function startLi() : self {
        $this->html .= '<li>';
        return $this;
    }

    public function endLi() : self {
        $this->html .= '</li>';
        return $this;
    }

    /**
     * @return string
     */
    public function end() : string
    {
        $this->html .= '</pre>';

        return $this->html;
    }

}

