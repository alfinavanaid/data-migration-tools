<?php

namespace Lib\Avanaone\DataMigrationTools\CustomRelationField;

use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableAlias;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableIgnored;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableColumnAlias;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableColumnIgnored;

class CustomRelationField
{


    private $value;
    // private $custom_relations_fields;
    // private $table_alias;

    private static $file_url = 'public/storage/custom_relations_field.json';

    function __construct($value)
    {
        $this->value = $value;
    }

    function checkTableNotFound($reference_table_name)
    {
        if (array_key_exists('table_not_found', $this->custom_relations_fields)) {
            if (array_key_exists($reference_table_name, $this->custom_relations_fields->table_not_found)) {
                $custom_table = ((array) $this->custom_relations_fields->table_not_found)[$reference_table_name];
                if ($custom_table->type == 'alias' && $custom_table->table_name != '' && $custom_table->table_name != null) {
                    $this->table_alias = $custom_table->table_name;
                    return true;
                }
            }
        }
    }

    function getAlias()
    {
        return $this->table_alias;
    }

    public function checkTableColumnAlias(String $main_table_name, String $column_name): bool
    {
        return (new TableColumnAlias($this->value))
            ->check($main_table_name, $column_name);
    }

    public function checkTableColumnIgnored(String $main_table_name, String $column_name): bool
    {
        return (new TableColumnIgnored($this->value))
            ->check($main_table_name, $column_name);
    }

    public function checkTableIgnored(String $reference_table_name): bool
    {
        return (new TableIgnored($this->value))
            ->check($reference_table_name);
    }

    // function checkTableIgnoreds($reference_table_name, $column_name, $table_name = '')
    // {
    //     if (array_key_exists('table_not_found', $this->custom_relations_fields)) {
    //         if (array_key_exists($reference_table_name, $this->custom_relations_fields->table_not_found)) {
    //             $custom_table = ((array) $this->custom_relations_fields->table_not_found)[$reference_table_name];
    //             if ($custom_table->type == 'ignored' && $custom_table->table_name == $reference_table_name && $custom_table->column_name == $column_name) {
    //                 if (array_key_exists('table_name_called_from', $custom_table)) {
    //                     if ($custom_table->table_name_called_from == $table_name) {
    //                         return true;
    //                     } else {
    //                         return false;
    //                     }
    //                 } else {
    //                     return true;
    //                 }
    //             }
    //         }
    //     }
    // }

    public static function getFileUrl(): string
    {
        return SELF::$file_url;
    }

    /**
     * Validate the all component of custom relations field
     * @throws \Exception if the key doesn't exists
     * @return boolean validate()
     */

    public function validate(): bool
    {

        $custom_relations_fields = [
            (new TableAlias($this->value)),
            (new TableIgnored($this->value)),
            (new TableColumnAlias($this->value)),
            (new TableColumnIgnored($this->value))
        ];

        foreach ($custom_relations_fields as $val) {
            if (!$val->validate()) {
                throw new \Exception('`' . $val->getKeyName() . '` key doesn\'t exists. Please check at `' . CustomRelationField::getFileUrl() . '`');
            }
        }

        return true;
    }
}
