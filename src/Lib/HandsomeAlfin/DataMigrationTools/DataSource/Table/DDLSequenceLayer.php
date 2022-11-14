<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table\Relations;

class DDLSequenceLayer
{

    private $data_source_json;
    private $log_number = 0;
    const ENABLE_LOGGING = false;

    function __construct($data_source_json)
    {
        $this->data_source_json = $data_source_json;
    }

    function execute($table_name)
    {

        // check if table has parent relations
        $this->findTheParent($table_name);
        return $this->data_source_json;
    }

    function findTheParent($table_name)
    {

        $this->log($table_name, 'start find the parent <br/>');

        $table = $this->data_source_json[$table_name];

        // this table has parent relations ?

        if (count($table->relations_parent) > 0) {

            // yes
            $this->log($table_name, 'has parent <br/>');

            foreach ($table->relations_parent as $relations_parent) {

                $parent_table = Relations::getReferenceTablenameOfString($relations_parent);
                $this->log($table_name, 'has parent (' . $parent_table . ')<br/>');

                if ($this->checkParentTableWithoutSequence($parent_table)) {
                    $this->findTheParent($this->checkParentTableWithoutSequence($parent_table));
                } else {
                    if ($this->data_source_json[$table_name]->extract_layer == null) {
                        $highest_extract_layer = $this->getHighestParentSequence($table_name);
                        $this->data_source_json[$table_name]->extract_layer = $highest_extract_layer + 1;
                        $this->log($table_name, '<b>set layer to (' . ($highest_extract_layer + 1) . ') </b><br/>');
                    }

                    $this->findTheChild($parent_table);
                }
            }
        } else {

            // no
            $this->log($table_name, 'dont have parent <br/>');

            // check if this table has sequence
            if ($this->data_source_json[$table_name]->extract_layer == null) {
                $this->data_source_json[$table_name]->extract_layer = 1;
                $this->log($table_name, '<b>set layer to (1)</b><br/>');
            }

            // check if this table has child 
            $this->findTheChild($table_name);
        }
    }

    function findTheChild($table_name)
    {

        if ($table_name == null) {
            return;
        }

        $table = $this->data_source_json[$table_name];

        // check if this table has child 

        if (count($table->relations_child) > 0) {

            // yes
            $this->log($table_name, 'has children <br/>');

            // looping the table child
            foreach ($table->relations_child as $relations_child) {

                $child_table_name = Relations::getReferenceTablenameOfString($relations_child);
                $this->log($table_name, 'has children (' . $child_table_name . ') <br/>');
                $this->log($child_table_name, 'find parent without sequence <br/>');

                // check if table child had parent without sequence
                // this table child has parent without sequence ?
                if ($this->checkParentTableWithoutSequence($child_table_name)) {

                    // yes
                    $this->findTheParent($this->checkParentTableWithoutSequence($child_table_name));
                } else {

                    // no 
                    if ($this->data_source_json[$child_table_name]->extract_layer == null) {
                        $highest_extract_layer = $this->getHighestParentSequence($child_table_name);
                        $this->data_source_json[$child_table_name]->extract_layer = $highest_extract_layer + 1;
                        $this->log($child_table_name, '<b>set layer to (' . ($highest_extract_layer + 1) . ') </b><br/>');
                    }

                    $this->findTheChild($child_table_name);
                }
            }
        } else {

            // no
            $this->log($table_name, 'dont have any children <br/>');

            if ($table_name == 'brand_users') {
                $this->findTheChild($this->findAnyTableWithoutSequence());
            }
        }
    }

    function checkParentTableWithoutSequence($table_name)
    {

        foreach ($this->data_source_json[$table_name]->relations_parent as $relations_parent) {
            $extract_layer = $this->data_source_json[Relations::getReferenceTablenameOfString($relations_parent)]->extract_layer;
            if ($extract_layer == null) {
                $this->log($table_name, 'has parent without sequence (' . Relations::getReferenceTablenameOfString($relations_parent) . ')<br/>');
                return Relations::getReferenceTablenameOfString($relations_parent);
            }
        }
        if (count($this->data_source_json[$table_name]->relations_parent) == 0) {
            $this->log($table_name, 'this is master table<br/>');
            return false;
        } else {
            $this->log($table_name, 'all parent has sequence<br/>');
            return false;
        }
    }

    function getHighestParentSequence($table_name)
    {
        $highest_extract_layer = 0;
        foreach ($this->data_source_json[$table_name]->relations_parent as $relations_parent) {
            $extract_layer = $this->data_source_json[Relations::getReferenceTablenameOfString($relations_parent)]->extract_layer;
            $this->log($table_name, 'get highest parent sequence (' . Relations::getReferenceTablenameOfString($relations_parent) . ') = ' . $extract_layer . '<br/>');
            if($extract_layer == null) {
                $this->log($table_name, ' parent (' . Relations::getReferenceTablenameOfString($relations_parent) . ') is null, find the parent<br/>');
                $this->findTheParent(Relations::getReferenceTablenameOfString($relations_parent));
                $extract_layer = $this->data_source_json[Relations::getReferenceTablenameOfString($relations_parent)]->extract_layer;
                $this->log($table_name, 'get highest parent sequence (' . Relations::getReferenceTablenameOfString($relations_parent) . ') = ' . $extract_layer . '<br/>');
            }

            if ($extract_layer >= $highest_extract_layer) {
                $highest_extract_layer = $extract_layer;
            }
        }
        $this->log($table_name, 'result highest parent sequence = ' . $highest_extract_layer . '<br/>');
        return $highest_extract_layer;
    }

    function findAnyTableWithoutSequence()
    {
        $this->log('', '---------- start find sequenced table with unsequenced child ---------<br/>');
        foreach ($this->data_source_json as $data_source_json) {
            if ($data_source_json->extract_layer != null && count($data_source_json->relations_child) > 0) {
                foreach ($data_source_json->relations_child as $relations_child) {
                    if (
                        $this->data_source_json[Relations::getReferenceTablenameOfString($relations_child)]->extract_layer == null
                        && count($this->data_source_json[Relations::getReferenceTablenameOfString($relations_child)]->relations_child) > 0
                    ) {
                        $this->log('', '---------- find sequenced table with unsequenced child (' . Relations::getReferenceTablenameOfString($relations_child) . ') ---------<br/>');
                        return Relations::getReferenceTablenameOfString($relations_child);
                    }
                }
            }
        }
        return null;
    }

    function log($table_name, $message)
    {
        if (self::ENABLE_LOGGING) {
            print_r('[' . $this->log_number . '] ' . $table_name . ' : ' . $message);
        }
        $this->log_number++;
    }
}
