<?php

namespace Lib\Avanaone\DataMigrationTools;

use Lib\Avanaone\DataMigrationTools\CustomRelationField\CustomRelationField;
use Lib\Avanaone\DataMigrationTools\DataSource\DataSource;
use Lib\Avanaone\DataMigrationTools\DataSource\DataSourceObject;
use Lib\Avanaone\DataMigrationTools\Utility\JsonStructure;
use Lib\Avanaone\DataMigrationTools\Utility\ValidateJson;

class DataMigrationTools
{

    /*
    *   @var String $data_source
    *   @var String $custom_relations_fields
    *   @var String $data_source_json
    *   @var String $table_not_found
    */

    private $data_source;
    private $custom_relations_fields;
    private $data_source_json;
    private $table_not_found;
    private $config;

    function __construct(string $data_source, string $custom_relations_fields)
    {
        $this->data_source = $data_source;
        $this->custom_relations_fields = $custom_relations_fields;
    }

    /**
     * Do analyze of the data source
     * @return array self
     */

    public function analyze(): array
    {

        $data_source = (new JsonStructure($this->data_source));
        $data_source->setType((int) JsonStructure::TYPE_DATA_SOURCE);

        $custom_relations_fields = (new JsonStructure($this->custom_relations_fields));
        $custom_relations_fields->setType((int) JsonStructure::TYPE_CUSTOM_RELATIONS_FIELD);

        $validate_data_source = ValidateJson::do($data_source);
        $validate_custom_relations_fields = ValidateJson::do($custom_relations_fields);

        if (!$validate_data_source || !$validate_custom_relations_fields) {
            return [];
        }

        $data_source = new DataSource((object) $data_source->get());
        $custom_relations_fields = new CustomRelationField($custom_relations_fields->get());

        $data_source_object = new DataSourceObject($data_source);
        $data_source_object->setDataSource($data_source);
        $data_source_object->setCustomRelationsFields($custom_relations_fields);
        // $data_source_object->scanSchema();

        dd($data_source_object->scanSchema()->getDataSourceObject()->getValueByTableName('avacredit_balance'));

        return (array) $data_source;
        // $data_source->setType(JsonStructure::TYPE_DATA_SOURCE);
        exit;
        // $custom_relations_fields = (new JsonStructure($this->custom_relations_fields))->customRelationsFields();

        // if(!is_array($data_source) && !is_array($custom_relations_fields)) {

        // }

        // $this->data_source;
        // $this->custom_relations_fields;
        // $DataSource = new DataSource($data_source->get(), $custom_relations_fields);
        // $DataSource->scanTable();
        // $data_source = $DataSource->get();
        // $this->data_source_json = $data_source['data_source_json'];
        // $this->table_not_found = $data_source['table_not_found'];
    }

    public function extract()
    {
        // $DataSource = new DataSource(null, null, $this->data_source_json);
        // $DataSource->extractDDL();
    }

    public function get()
    {
        unset($this->data_source);
        unset($this->custom_relations_fields);
        return $this;
    }
}
