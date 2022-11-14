<?php

namespace Lib\Avanaone\DataMigrationTools\CustomRelationField;

use Lib\Avanaone\DataMigrationTools\Config\LoadCustomRelationField as CustomRelationFieldConfiguration;
use Lib\Avanaone\DataMigrationTools\Services\CustomTableField;

class TableColumnIgnored implements CustomTableField
{

    /**
     * @var object $object_json
     * @var object $value
     * @var string KEYNAME
     */

    private $object_json;
    private $value;
    private const KEYNAME = 'table_column_ignored';

    /**
     * @param array $object_json       Lib\Avanaone\DataMigrationTools\Utility\JsonStructure::$object
     */

    public function __construct(object $object_json)
    {
        $this->object_json = $object_json;
    }

    /**
     * Check if the `key` custom relations configuration was enabled
     * @return boolean isEnable
     */

    public function isEnable(): bool
    {
        $config_loader = (new CustomRelationFieldConfiguration())->getArray();
        if (!$config_loader[SELF::KEYNAME]) {
            return false;
        }
        return true;
    }

    /**
     * Check if the `key` custom relations value was exists on the file
     * @return boolean validate()
     */

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

    /**
     * Get the name of the key for this custom relations
     * @return string KEYNAME
     */

    public function getKeyName(): string
    {
        return SELF::KEYNAME;
    }

    /**
     * Set the value from the indexed object $object_json
     * @return void
     */

    public function setValue(): void
    {
        if ($this->validate()) {
            $this->value = $this->object_json->{$this->getKeyName()};
        }
    }

    /**
     * Get the value from the indexed object $object_json
     * @return array
     */

    public function getValue(): array
    {
        if ($this->validate()) {
            return $this->object_json->{$this->getKeyName()};
        }
        return null;
    }

    /**
     * Check if table_column exist on this ignoration lists
     * @param String reference_table_name 
     * @param String column_name 
     * @return bool
     */

    public function check(String $reference_table_name, String $column_name): bool
    {
        $this->setValue();
        $table_column = $reference_table_name . '.' . $column_name;

        return in_array($table_column,  $this->getValue());
    }
    
}
