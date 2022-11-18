<?php

namespace Lib\Avanaone\DataMigrationTools\Utility\Schema;

use Lib\Avanaone\DataMigrationTools\DataSource\DataSource;
use Lib\Avanaone\DataMigrationTools\DataSource\DataSourceObject;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Table;
use Lib\Avanaone\DataMigrationTools\DataSource\ResultAnalysis;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\SequenceLayer;

class Scan
{

    private $data_source_object;

    function __construct(DataSourceObject $data_source_object)
    {
        $this->data_source_object = $data_source_object;
    }

    function execute(): void
    {
        $data_source = $this->data_source_object->getDataSource();
        $data_source_value = (array) $data_source->getAllValue();

        $table_list = array_values(array_unique(array_column($data_source_value, 0)));
        $this->data_source_object->setTableList($table_list);

        $data_source = $this->data_source_object->getDataSource();

        if (empty($data_source->getAllValue())) {
            exit;
        }

        for ($i = 0; $i < count((array) $data_source->getAllValue()); $i++) {
            $this->setTableAndColumn($data_source, $i);
        }

        $this->data_source_json = (new SequenceLayer($this->data_source_object))->execute('shop');
        // $this->result_analysis = (new ResultAnalysis($this->data_source_json))->getReports();
    }

    public function getDataSourceObject() : DataSourceObject
    {
        return $this->data_source_object;
    }

    private function setTableAndColumn(DataSource $data_source, int $index)
    {
        $table_name = $data_source->getTableNameValueByIndex($index);
        $column_name = $data_source->getColumnNameValueByIndex($index);

        if (!$this->findTable($table_name)) {
            $Table = new Table();
            $Table->setName($table_name);
        } else {
            $Table = new Table($this->data_source_object->getValueByTableName($table_name));
        }

        $Table->setNewColumn($column_name);
        $Table->analyzeRelationship($column_name, $this->data_source_object);

        if ($Table->getNewParentTable() != null) {

            $table_name_parent = $Table->getNewParentTable();
            if (!$this->findTable($table_name_parent)) {
                $ParentTable = new Table();
                $ParentTable->setName($table_name_parent);
            } else {
                $ParentTable = new Table($this->data_source_object->getValueByTableName($table_name_parent));
            }


            $ParentTable->appendChild($table_name, $column_name);
            $this->data_source_object->addOrUpdateValue($ParentTable);
        }

        if ($Table->getTableNotFound()) {
            $this->data_source_object->appendTableNotFound($Table->getTableNotFound());
        }
        $this->data_source_object->addOrUpdateValue($Table);
    }

    private function findTable($table_name): bool
    {
        if (!array_key_exists($table_name, $this->data_source_object->getValue())) {
            return false;
        }
        return true;
    }
}
