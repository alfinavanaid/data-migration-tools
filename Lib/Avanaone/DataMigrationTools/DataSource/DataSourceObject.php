<?php

namespace Lib\Avanaone\DataMigrationTools\DataSource;

use Lib\Avanaone\DataMigrationTools\CustomRelationField\CustomRelationField;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Table;
use Lib\Avanaone\DataMigrationTools\DataSource\ResultAnalysis;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Table\DDLSequenceLayer;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Table\DDLExtract;
use Lib\Avanaone\DataMigrationTools\Utility\Schema\Scan as ScanSchema;

class DataSourceObject
{

    private $data_source;
    private $value = [];
    private $table_list;
    private $table_not_found = [];

    private $custom_relations_fields;

    private $result_analysis;

    private static $file_url = 'public/storage/data_source_object.json';

    function __construct()
    {
    }

    function scanSchema()
    {
        $scanSchema = new ScanSchema($this);
        $scanSchema->execute();
        return $scanSchema;
        // $this->
        // dd($scanSchema->getDataSourceObject());
        // return $scanSchema->getDataSourceObject();
    }

    public static function getFileUrl(): string
    {
        return SELF::$file_url;
    }

    /**
     * Set 'data source' private property value
     * @return void
     */

    public function setDataSource(object $data_source): void
    {
        $this->data_source = $data_source;
    }

    /**
     * Set 'table list' private property value
     * @return void
     */

    public function setTableList($table_list): void
    {
        $this->table_list = $table_list;
    }

    /**
     * Set 'custom relations fields' private property value
     * @return void
     */

    public function setCustomRelationsFields(CustomRelationField $custom_relations_fields): void
    {
        $this->custom_relations_fields = $custom_relations_fields;
    }

    /**
     * Set 'data source json' private property value
     * @return void
     */

    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    /**
     * Set 'table not found' private property
     * @return void
     */

    public function setTableNotFound(array $table_not_found): void
    {
        $this->table_not_found = $table_not_found;
    }

    /**
     * Append 'table not found' private property
     * @return void
     */

    public function appendTableNotFound(array $table_not_found): void
    {
        array_push($this->table_not_found, $table_not_found);
    }

    /**
     * Get 'data source' private property value
     * @return object $data_source
     */

    public function getDataSource(): object
    {
        return $this->data_source;
    }

    /**
     * Get 'table list' private property value
     * @return array $table_list
     */

    public function getTableList(): array
    {
        return $this->table_list;
    }

    /**
     * Get 'value' private property value, contain list of Lib\Avanaone\DataMigrationTools\Utility\Schema\Table
     * @return array $this->value
     */

    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * Get 'data source json' private property value
     * @return Table $this->value[$table_name]
     */

    public function getValueByTableName($table_name): Table
    {
        return $this->value[$table_name];
    }

    /**
     * Get 'table not found' private property value
     * @return array $table_not_found
     */

    public function getTableNotFound(): array
    {
        return $this->table_not_found;
    }

    /**
     * Get 'custom relations fields' private property value
     * @return object $custom_relations_fields
     */

    public function getCustomRelationsFields(): object
    {
        return $this->custom_relations_fields;
    }

    /**
     * Update the 'value' key of this class
     * @return bool
     */

    public function addOrUpdateValue(Table $table): bool
    {
        $this->value[$table->table_name] = $table;
        return true;
    }
}
