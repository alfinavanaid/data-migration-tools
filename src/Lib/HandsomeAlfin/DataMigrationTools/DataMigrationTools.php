<?php

namespace Lib\HandsomeAlfin\DataMigrationTools;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\DataSource;

class DataMigrationTools
{

    public $data_source;
    public $custom_relations_fields;
    public $data_source_json;
    public $table_not_found;

    function __construct()
    {

    }

    public function analyze()
    {
        $DataSource = new DataSource($this->data_source, $this->custom_relations_fields);
        $DataSource->scanTable();
        $data_source = $DataSource->get();
        $this->data_source_json = $data_source['data_source_json'];
        $this->table_not_found = $data_source['table_not_found'];
    }

    public function extract()
    {
        $DataSource = new DataSource(null, null, $this->data_source_json);
        $DataSource->extractDDL();

    }

    public function get()
    {
        unset($this->data_source);
        unset($this->custom_relations_fields);
        return $this;
    }
}
