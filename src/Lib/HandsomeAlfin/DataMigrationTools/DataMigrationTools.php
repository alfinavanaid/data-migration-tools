<?php

namespace Lib\HandsomeAlfin\DataMigrationTools;

use Lib\HandsomeAlfin\DataMigrationTools\DataSource\DataSource;

class DataMigrationTools
{

    private $data_source;
    private $custom_relations_fields;
    private $data_source_json;
    private $table_not_found;

    function __construct($data_source, $custom_relations_fields)
    {
        $this->data_source = $data_source;
        $this->custom_relations_fields = $custom_relations_fields;
    }

    public function analyze()
    {
        $DataSource = new DataSource($this->data_source, $this->custom_relations_fields);
        $DataSource->scanTable();
        $data_source = $DataSource->get();
        dd($data_source);
        $this->data_source_json = $data_source['data_source_json'];
        $this->table_not_found = $data_source['table_not_found'];
    }

    public function get()
    {
        unset($this->data_source);
        unset($this->custom_relations_fields);
        return $this;
    }
}
