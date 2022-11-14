<?php

namespace Lib\Avanaone\DataMigrationTools\Resources;

class DetailDataSourceObjectResources
{

    public $table_name;
    public $column;
    public $relations_child;
    public $relations_parent;
    public $extract_layer;
    public $primary_key;

    function __construct(array $table)
    {
        $this->table_name = $table['table_name'];
        return $this;
    }
}
