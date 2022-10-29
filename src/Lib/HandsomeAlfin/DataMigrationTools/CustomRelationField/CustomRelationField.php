<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\CustomRelationField;

class CustomRelationField {


    private $custom_relations_fields;
    private $table_alias;

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

    function checkTableIgnored($reference_table_name, $column_name)
    {
        if (array_key_exists('table_not_found', $this->custom_relations_fields)) 
        {
            if (array_key_exists($reference_table_name, $this->custom_relations_fields->table_not_found)) {
                $custom_table = ((array) $this->custom_relations_fields->table_not_found)[$reference_table_name];
                if ($custom_table->type == 'ignored' && $custom_table->table_name == $reference_table_name && $custom_table->column_name == $column_name) {
                    return true;
                }
            }
        }
    }

    
}
