<?php

namespace Lib\Avanaone\DataMigrationTools\DataSource;

use Lib\Avanaone\DataMigrationTools\DataSource\Table;
use Lib\Avanaone\DataMigrationTools\DataSource\ResultAnalysis;
use Lib\Avanaone\DataMigrationTools\DataSource\Table\DDLSequenceLayer;
use Lib\Avanaone\DataMigrationTools\DataSource\Table\DDLExtract;

class DataSource
{


    private $data_source;
    private $data_source_json;
    private $table_list;
    private $table_not_found;

    private $custom_relations_fields;

    private $active_table = '';
    private $result_analysis;

    private static $file_url = 'public/storage/data_source.json';

    function __construct(array $data_source)
    {
        $this->data_source = $data_source;
    }

    function setCustomRelationsFields(array $custom_relations_fields): void
    {
        $this->custom_relations_fields = $custom_relations_fields;
    }

    function setDataSourceJson(array $data_source_json): void
    {
        $this->data_source_json = $data_source_json;
    }

    function scanTable()
    {
        // $new = [
        //     'alias' => [],
        //     'ignore' => []
        // ];
        // foreach($this->custom_relations_fields->table_not_found as $key => $val) {
        //     if($val->type == 'alias') {
        //         array_push($new['alias'], [
        //             'existing_table_name' => $key,
        //             'new_table_name' => $val->table_name
        //         ]);
        //     } else {
        //         $new['ignore'][$val->table_name.'.'.$val->column_name] = $val;
        //     }
        //     // dd($val);
        // }
        // dd($new);
        // dd($this->custom_relations_fields);
        $this->table_list = array_values(array_unique(array_column($this->data_source, 0)));
        if (count($this->data_source) > 0 && $this->data_source <> null && !empty($this->data_source)) {
            foreach ($this->data_source as $data_source) {
                $this->setTableAndColumn($data_source[0], $data_source[1]);
            }
        }
        $this->data_source_json = (new DDLSequenceLayer($this->data_source_json))->execute('shop');
        $this->result_analysis = (new ResultAnalysis($this->data_source_json))->getReports();
    }

    private function setTableAndColumn($table_name, $column_name)
    {
        if (!$this->findTable($table_name)) {
            $Table = new Table();
            $Table->setTableName($table_name);
        } else {
            $Table = new Table($this->data_source_json[$table_name]);
        }

        $Table->setNewColumn($column_name);
        $Table->analyzeRelationship($column_name, $this->table_list, $this->custom_relations_fields);

        if ($Table->getNewParentTable() != null) {

            $table_name_parent = $Table->getNewParentTable();
            if (!$this->findTable($table_name_parent)) {
                $ParentTable = new Table();
                $ParentTable->setTableName($table_name_parent);
            } else {
                $ParentTable = new Table($this->data_source_json[$table_name_parent]);
            }

            $ParentTable->appendChild($table_name, $column_name);
            $this->data_source_json[$table_name_parent] = $ParentTable->get();
        }


        if ($Table->getTableNotFound()) {
            array_push($this->table_not_found, $Table->getTableNotFound());
        }
        $this->data_source_json[$table_name] = $Table->get();
    }

    private function findTable($table_name)
    {
        if (array_key_exists($table_name, $this->data_source_json)) {
            return true;
        }
        return false;
    }

    public function get()
    {
        return [
            'data_source_json' => $this->data_source_json,
            'table_not_found' => $this->table_not_found,
            'result_analysis' => $this->result_analysis
        ];
    }

    public function extractDDL()
    {
        $main_data = [
            'table_name' => 'shop',
            'column_name' => 'shop_id',
            'column_value' => '77786'
        ];

        $DDLExtract = new DDLExtract($this->data_source_json, $main_data);
        $DDLExtract->generateSql();
    }

    public static function getFileUrl(): string
    {
        return SELF::$file_url;
    }

    public function validate(): void
    {

        if (empty($this->data_source)) {
            throw new \Exception('Empty `data_source.json` file. Please check at `' . DataSource::getFileUrl() . '`');
        }

    }
}
