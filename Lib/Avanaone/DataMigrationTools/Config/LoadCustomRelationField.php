<?php

namespace Lib\Avanaone\DataMigrationTools\Config;

use Lib\Avanaone\DataMigrationTools\Services\ConfigLoader;
use Lib\Avanaone\DataMigrationTools\Config\LoadConfig;

class LoadCustomRelationField implements ConfigLoader
{

    private $config;
    private const KEYNAME = "CUSTOM_RELATION_FIELD";
    private const VALUE = [
        'table_alias',
        'table_ignored',
        'table_column_alias',
        'table_column_ignored',
    ];

    public function __construct()
    {
        $this->config = (new LoadConfig())->getArray();
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
        if (!array_key_exists(SELF::KEYNAME, $this->config)) {
            return false;
        }

        $this->setConfig();

        foreach (SELF::VALUE as $val) {
            if (!array_key_exists($val, $this->config)) {
                return false;
            }
        }

        return true;
    }

    private function setConfig(): void
    {
        $this->config = $this->config[SELF::KEYNAME];
    }
}
