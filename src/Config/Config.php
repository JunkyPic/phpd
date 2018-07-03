<?php

namespace Phpd\Config;

/**
 * Class ConfigLoader
 *
 * @package Phpd\Config
 */
class Config
{
    /**
     * @var array
     */
    private $requiredConfigKeys = [
        'theme',
        'simple-mode',
    ];

    /**
     * @var string
     */
    private $path = 'src/Config/config.json';

    /**
     * @var bool
     */
    private $simpleMode = true;

    /**
     * @var bool
     */
    private $styleLoaded = false;

    /**
     * @var
     */
    private $config;

    public function isStyleLoaded() {
        return $this->styleLoaded;
    }

    /**
     * @param null $path
     *
     * @throws ConfigLoaderException
     */
    public function setPath($path = null)
    {
        if (null !== $path) {
            if ( ! file_exists($path)) {
                throw new ConfigLoaderException(
                    "Unable to find config file at path {$path}. Default location should be /src/Config/config.json."
                );
            }

            $this->path = $path;
        }
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return $this
     * @throws ConfigLoaderException
     */
    public function load()
    {
        if ( ! file_exists($this->path)) {
            throw new ConfigLoaderException(
                "Unable to find config file at path {$this->path}. Default location should be /src/Config/config.json."
            );
        }

        $config = json_decode(file_get_contents($this->path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigLoaderException("Config file found at {$this->path} is not a valid json file");
        }

        // Check for keys that MUST exist
        foreach ($this->requiredConfigKeys as $key) {
            if ( ! array_key_exists($key, $config)) {
                throw new ConfigLoaderException("Key {$key} is missing from configuration file");
            }
        }

        $this->config = $config;

        $this->simpleMode = (bool) $config['simple-mode'];

        return $this;
    }

    /**
     * @return bool|string
     */
    public function loadStyles() {
        if($this->styleLoaded === false) {
            $this->styleLoaded = true;
            return file_get_contents($this->get('theme'));
        }
    }

    /**
     * Used to set simple mode to true on the fly
     */
    public function disableCss()
    {
        $this->simpleMode = true;
    }

    /**
     * Used to set simple mode to false on the fly
     */
    public function enableCss()
    {
        $this->simpleMode = false;
    }

    /**
     * @return bool
     */
    public function simpleMode() {
        return $this->simpleMode;
    }

}