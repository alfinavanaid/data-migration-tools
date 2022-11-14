<?php

namespace Lib\Avanaone\DataMigrationTools\Config;

use Lib\Avanaone\DataMigrationTools\Services\ConfigLoader;

class LoadConfig implements ConfigLoader
{

    private $config;

    public function __construct()
    {
        $this->config = include(dirname(__FILE__) . '/../Config.php');
    }

    public function getArray(): array
    {
        if (!$this->checkArray()) {
            throw new \Exception('There is something wrong with the configuration file');
        }
        return $this->config;
    }

    public function checkArray(): bool
    {
        if (!is_array($this->config)) {
            return false;
        }
        return true;
    }
}
