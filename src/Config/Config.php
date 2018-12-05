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
    private $requiredConfigKeys
        = [
            'theme',
        ];

    /**
     * @var string
     */
    private $path = 'src/Config/config.json';

    /**
     * @var
     */
    private $config;

    /**
     * @param null $path
     *
     * @throws ConfigLoaderException
     */
    public function setPath($path = null): void
    {
        if (null === $path && ! file_exists($path)) {
            throw new ConfigLoaderException(
                "Unable to find config file at path {$path}. Default location should be /src/Config/config.json."
            );
        }

        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function all(): ?array
    {
        return $this->config;
    }

    /**
     * @param $key
     *
     * @return null|string
     */
    public function get($key): ?string
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        // Try a recursive search
        return $this->recursiveSearch($key, $this->config);
    }

    /**
     * @param       $search
     * @param array $array
     *
     * @return mixed
     */
    private function recursiveSearch($search, array $array): ?string
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                return $this->recursiveSearch($search, $value);
            }
            if ($search === $key) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return $this
     * @throws ConfigLoaderException
     */
    public function load(): self
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

        return $this;
    }

}
