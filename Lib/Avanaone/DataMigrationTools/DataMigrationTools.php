<?php

namespace Lib\Avanaone\DataMigrationTools;

use Exception;
use Lib\Avanaone\DataMigrationTools\DataSource\DataSource;
use Lib\Avanaone\DataMigrationTools\Utility\JsonStructure;
use Lib\Avanaone\DataMigrationTools\Utility\Config;

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
        $data_source->setType(JsonStructure::TYPE_DATA_SOURCE);
        $data_source->validate();
        $data_source->get();

        $custom_relations_fields = (new JsonStructure($this->custom_relations_fields));
        $custom_relations_fields->setType(JsonStructure::TYPE_CUSTOM_RELATIONS_FIELDS);
        $custom_relations_fields->validate();
        $custom_relations_fields->get();
        
        return (array) $data_source;
        $data_source->setType(JsonStructure::TYPE_DATA_SOURCE);
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
