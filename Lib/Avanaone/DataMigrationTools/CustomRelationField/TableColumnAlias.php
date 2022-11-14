<?php

namespace Lib\Avanaone\DataMigrationTools\CustomRelationField;

use Lib\Avanaone\DataMigrationTools\Config\LoadCustomRelationField as CustomRelationFieldConfiguration;
use Lib\Avanaone\DataMigrationTools\Services\CustomTableField;

class TableColumnAlias implements CustomTableField
{

    private $object_json;
    private const KEYNAME = 'table_column_alias';

    public function __construct($object_json)
    {
        $this->object_json = $object_json;
    }

    public function isEnable(): bool
    {
        $config_loader = (new CustomRelationFieldConfiguration())->getArray();
        if (!$config_loader[SELF::KEYNAME]) {
            return false;
        }
        return true;
    }

    public function validate(): bool
    {
        if (!$this->isEnable()) {
            return true;
        }
        if (array_key_exists(SELF::KEYNAME, $this->object_json)) {
            return true;
        }
        return false;
    }

    public function getKeyName() : string 
    {
        return SELF::KEYNAME;
    }
}
