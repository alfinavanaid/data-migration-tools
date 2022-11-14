<?php

namespace Lib\Avanaone\DataMigrationTools\Resources;

use Lib\Avanaone\DataMigrationTools\Resources\DetailDataSourceObjectResources;

class ListDataSourceObjectDetailResources
{

    public $table_list;

    function __construct(array $table_list)
    {
        foreach($table_list as $val) {
            $this->table_list[$val['table_name']] = (new DetailDataSourceObjectResources($val));
        }
        return $this;
    }
}
