<?php

namespace Lib\Avanaone\DataMigrationTools\CustomRelationField;

use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableAlias;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableIgnored;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableColumnAlias;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\TableColumnIgnored;

class CustomRelationField {


    private $custom_relations_fields;
    private $table_alias;

    private static $file_url = 'public/storage/custom_relations_field.json';

    function __construct($custom_relations_fields)
    {
        $this->custom_relations_fields = $custom_relations_fields; 
    }
    
    function checkTableNotFound($reference_table_name)
    {
        if (array_key_exists('table_not_found', $this->custom_relations_fields)) 
        {
            if (array_key_exists($reference_table_name, $this->custom_relations_fields->table_not_found)) {
                $custom_table = ((array) $this->custom_relations_fields->table_not_found)[$reference_table_name];
                if ($custom_table->type == 'alias' && $custom_table->table_name != '' && $custom_table->table_name != null) {
                    $this->table_alias = $custom_table->table_name;
                    return true;
                }
            }
        }
    }

    function getAlias() {
        return $this->table_alias;
    }

    function checkTableIgnored($reference_table_name, $column_name, $table_name = '')
    {
        if (array_key_exists('table_not_found', $this->custom_relations_fields)) 
        {
            if (array_key_exists($reference_table_name, $this->custom_relations_fields->table_not_found)) {
                $custom_table = ((array) $this->custom_relations_fields->table_not_found)[$reference_table_name];
                if ($custom_table->type == 'ignored' && $custom_table->table_name == $reference_table_name && $custom_table->column_name == $column_name) {
                    if(array_key_exists('table_name_called_from', $custom_table)) {
                        if($custom_table->table_name_called_from == $table_name) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }
    }

    public static function getFileUrl(): string
    {
        return SELF::$file_url;
    }

    public function validate() : void 
    {

        $custom_relations_fields = [
            (new TableAlias($this->custom_relations_fields)),
            (new TableIgnored($this->custom_relations_fields)),
            (new TableColumnAlias($this->custom_relations_fields)),
            (new TableColumnIgnored($this->custom_relations_fields))
        ];

        foreach ($custom_relations_fields as $val) {
            if (!$val->validate()) {
                throw new \Exception('`' . $val->getKeyName() . '` key doesn\'t exists. Please check at `' . CustomRelationField::getFileUrl() . '`');
            }
        }

    }
    
}
