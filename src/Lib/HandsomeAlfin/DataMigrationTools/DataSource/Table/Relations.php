<?php

namespace Lib\HandsomeAlfin\DataMigrationTools\DataSource\Table;

class Relations
{

    static function stringWriteRelationship($table_name, $column_name, $foreign_table_name, $foreign_column_name)
    {
        return ($table_name . '.' . $column_name . ' = ' . $foreign_table_name . '.' . $foreign_column_name);
    }

    static function getTablenameOfString($string)
    {
        return explode('.', explode(' = ', $string)[1])[0];
    }
}
