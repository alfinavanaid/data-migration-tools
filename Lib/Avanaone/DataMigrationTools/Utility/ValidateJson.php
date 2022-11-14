<?php

namespace Lib\Avanaone\DataMigrationTools\Utility;

use Lib\Avanaone\DataMigrationTools\DataSource\DataSource;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\CustomRelationField;


class ValidateJson
{

    /**
     * Do validation for the object based on the type
     * @param object $object                Lib\Avanaone\DataMigrationTools\Utility\JsonStructure
     * @return bool
     */

    public static function do(object $object): bool
    {

        if ($object->isDataSource()) {
            return (new DataSource($object->get()))->validate();
        } elseif ($object->isCustomRelationsField()) {
            return (new CustomRelationField($object->get()))->validate();
        } else {
            throw new \Exception('Undefined object type');
        }
        return false;
    }
}
