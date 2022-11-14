<?php

namespace Lib\Avanaone\DataMigrationTools\DataSource\Table;

class Relations
{

    static function stringWriteRelationship($table_name, $column_name, $foreign_table_name, $foreign_column_name)
    {
        return ($table_name . '.' . $column_name . ' = ' . $foreign_table_name . '.' . $foreign_column_name);
    }

    static function getReferenceTablenameOfString($string)
    {
        return explode('.', explode(' = ', $string)[1])[0];
    }

    static function getReferenceColumnnameOfString($string)
    {
        return explode('.', explode(' = ', $string)[1])[1];
    }

    static function getColumnnameOfString($string)
    {
        return explode('.', explode(' = ', $string)[0])[1];
    }
    
}
