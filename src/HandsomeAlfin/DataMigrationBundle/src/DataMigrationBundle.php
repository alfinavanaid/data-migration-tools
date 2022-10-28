<?php

namespace HandsomeAlfin\DataMigrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use HandsomeAlfin\DataMigrationBundle\DataSource\DataSource;

class DataMigrationBundle extends Bundle
{


    private $data_source;
    private $custom_relations_fields;

    function __construct($data_source, $custom_relations_fields)
    {
        $this->data_source = $data_source;
        $this->custom_relations_fields = $custom_relations_fields;
    }

    public function analyze()
    {
        $DataSource = new DataSource($this->data_source, $this->custom_relations_fields);
        $DataSource->scanTable();
        return $DataSource->get();
    }
}
