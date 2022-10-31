<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table\Relations;

class DDLSequenceLayer
{

    private $data_source_json;
    private $count_scan = 0;

    function __construct($data_source_json)
    {
        $this->data_source_json = $data_source_json;
    }

    function execute($table_name)
    {

        // check if table has parent relations
        $this->findTheParent($table_name);

        // usort($this->data_source_json, function ($a, $b) {
        //     return $a->extract_layer <=> $b->extract_layer;
        // });
        exit;
        echo '<pre>';
        print_r($this->data_source_json);
        exit;
    }

    function findTheParent($table_name)
    {
        $this->count_scan += 1;
        if($this->count_scan == 10)
            exit;
        print_r($table_name . ' : start find the parent <br/>');

        $table = $this->data_source_json[$table_name];

        // this table has parent relations ?

        if (count($table->relations_parent) > 0) {

            // yes

            print_r($table_name . ' : has parent <br/>');

            foreach ($table->relations_parent as $relations_parent) {

                $parent_table = Relations::getTablenameOfString($relations_parent);

                print_r($table_name . ' : has parent (' . $parent_table . ')<br/>');

                // if ($this->data_source_json[$parent_table]->extract_layer != null) {

                //     print_r($table_name . ' : the parent (' . $parent_table . ') has layer ' . $this->data_source_json[$parent_table]->extract_layer . '<br/>');

                // } else {

                // if($parent_table == 'country') {
                //     dd($this->data_source_json[$parent_table]);
                // }


                if ($this->checkParentTableWithoutSequence($parent_table)) {
                    $this->findTheParent($this->checkParentTableWithoutSequence($parent_table));
                }

                // }
            }

            // die($this->getHighestParentSequence($table_name));
        } else {

            // no

            print_r($table_name . ' : dont have parent <br/>');

            // check if this table has sequence

            if ($this->data_source_json[$table_name]->extract_layer == null) {
                $this->data_source_json[$table_name]->extract_layer = 1;
                print_r($table_name . ' : set layer to (1) <br/>');
            }

            // check if this table has child 

            $this->findTheChild($table_name);
        }
    }

    function findTheChild($table_name)
    {

        $table = $this->data_source_json[$table_name];

        // check if this table has child 

        if (count($table->relations_child) > 0) {

            // yes
            print_r($table_name . ' : has children <br/>');

            // looping the table child
            foreach ($table->relations_child as $relations_child) {

                $child_table_name = Relations::getTablenameOfString($relations_child);

                print_r($table_name . ' : has children (' . $child_table_name . ') <br/>');

                print_r($child_table_name . ' : find parent without sequence <br/>');

                // check if table child had parent without sequence
                // this table child has parent without sequence ?
                if ($this->checkParentTableWithoutSequence($child_table_name)) {

                    // yes

                    $this->findTheParent($this->checkParentTableWithoutSequence($child_table_name));
                    // $this->findTheParent($child_table_name);
                } else {

                    // no 

                    die($this->getHighestParentSequence($child_table_name));
                }
                // if ($this->data_source_json[$child_table_name]->extract_layer == null) {
                //     $this->data_source_json[$child_table_name]->extract_layer = $this->checkParentTablewithoutSequence($child_table_name) + 1;
                //     print_r($table_name . ' : set layer to (' . ($this->checkParentTablewithoutSequence($child_table_name) + 1) . ') <br/>');
                // }

            }
        } else {

            // no

        }

        print_r($table_name . ' : start find the childern <br/>');


        if (count($table->relations_child) > 0) {
        }
    }

    function checkParentTableWithoutSequence($table_name)
    {

        foreach ($this->data_source_json[$table_name]->relations_parent as $relations_parent) {
            $extract_layer = $this->data_source_json[Relations::getTablenameOfString($relations_parent)]->extract_layer;
            if ($extract_layer == null) {
                print_r($table_name . ' : has parent without sequence ('.Relations::getTablenameOfString($relations_parent).')<br/>');
                return Relations::getTablenameOfString($relations_parent);
                //     print_r($table_name . ' : parent dont had sequence (' . Relations::getTablenameOfString($relations_parent) . ') <br/>');

                //     $this->findTheParent(Relations::getTablenameOfString($relations_parent));
            }
        }
        if(count($this->data_source_json[$table_name]->relations_parent) == 0) {             
            print_r($table_name . ' : this is master table<br/>');
            return false;
        } else {
            print_r($table_name . ' : all parent has sequence<br/>');
            return false;
        }
        // $this->getHighestParentSequence($table_name);
        // return $highest_extract_layer;

    }

    function getParentWithoutSequence($table_name)
    {

    }

    function getHighestParentSequence($table_name)
    {
        return 4;
    }
}
