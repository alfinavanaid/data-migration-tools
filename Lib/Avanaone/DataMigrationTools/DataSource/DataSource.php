<?php

namespace Lib\Avanaone\DataMigrationTools\DataSource;


class DataSource
{

    private $value;
    private static $file_url = 'public/storage/data_source.json';

    private const index_table_name = 0;
    private const index_column_name = 1;

    function __construct(object $value)
    {
        $this->value = $value;
    }

    public function validate(): bool
    {

        if (empty($this->value)) {
            throw new \Exception('Empty `data_source.json` file. Please check at `' . $this->file_url . '`');
        }

        return true;
    }

    public function getAllValue() {
        return $this->value;
    }

    public function getTableNameValueByIndex($index): string
    {
        return $this->value->{$index}[SELF::index_table_name];
    }

    public function getColumnNameValueByIndex($index): string
    {
        return $this->value->{$index}[SELF::index_column_name];
    }
    
}
