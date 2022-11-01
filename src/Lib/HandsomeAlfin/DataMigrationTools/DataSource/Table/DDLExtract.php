<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table\Relations;

class DDLExtract
{

    private $data_source_json;

    function __construct($data_source_json)
    {
        $this->data_source_json = $data_source_json;
    }

    public function generateSql()
    {
        $this->sortObjectAndFilterExtractLayer();
        $this->createSqlCodePerObject();
    }

    private function sortObjectAndFilterExtractLayer()
    {
        $data_source_json = (array)$this->data_source_json;
        usort($data_source_json, function ($a, $b) {
            if ($a->extract_layer != null) {
                return $a->extract_layer <=> $b->extract_layer;
            }
        });

        $data_source_json_new = [];
        foreach ($data_source_json as $key => $val) {
            if ($val->extract_layer != null) {
                $data_source_json_new[$val->table_name] = $val;
            }
        }

        $this->data_source_json = $data_source_json_new;
    }

    private function createSqlCodePerObject()
    {

        foreach ($this->data_source_json as $table_name => $val) {

            $table = $this->data_source_json[$table_name];
            $query = '';
            if (count($table->relations_parent) > 0) {
                $query .= ' WHERE ';
                foreach ($table->relations_parent as $key => $parent_table_name) {

                    if($key > 0)
                        $query .= ' AND ';

                    // where column name in
                    $query .= Relations::getColumnnameOfString($parent_table_name) . ' IN ';

                    // (select )
                    $query .= '(' . $this->data_source_json[Relations::getReferenceTablenameOfString($parent_table_name)]->data_row['sql_query_get_primary'] . ')';


                    // dd($query);
                    // dd($this->data_source_json[Relations::getReferenceTablenameOfString($parent_table_name)]->data_row['sql_query_get_primary']);


                    // echo Relations::getReferenceTablenameOfString($parent_table_name);
                    // echo '<br/>';
                    // echo Relations::getReferenceColumnnameOfString($parent_table_name);
                    // echo '<br/>';
                    // echo Relations::getColumnnameOfString($parent_table_name);
                    // echo '<br/>';
                }
            }

            $this->data_source_json[$table_name]->data_row = [
                'row' => [],
                'sql_query_get_primary' => 'SELECT ' . $table->primary_key . ' FROM ' . $table_name . ' ' . $query,
                'sql_query_get_all' => 'SELECT * FROM ' . $table_name . ' ' . $query,
            ];
        }
        if (isset($_GET['table'])) {
            dd($this->data_source_json[$_GET['table']]->data_row);
        }
        dd($this->data_source_json);
    }
}
