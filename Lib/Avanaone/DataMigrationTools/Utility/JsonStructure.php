<?php

namespace Lib\Avanaone\DataMigrationTools\Utility;

use Lib\Avanaone\DataMigrationTools\DataSource\DataSource;
use Lib\Avanaone\DataMigrationTools\CustomRelationField\CustomRelationField;


class JsonStructure
{

    private $object;
    private $type;
    private $errors;

    public const TYPE_DATA_SOURCE = 1;
    public const TYPE_CUSTOM_RELATIONS_FIELDS = 2;

    public function __construct(String $object)
    {
        $this->object = json_decode($object);
    }

    /**
    * Set the type of json structure (data source, custom relations field)
    * @param  int type
    * @return void
    */

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
    * Validate is the value of the json object fulfilled 
    * the rule of `data source` or the `custom relations field` type
    *
    * @return void
    */
    public function validate(): void
    {
        if (SELF::isDataSource()) {

            (new DataSource($this->object))->validate();

        } elseif (SELF::isCustomRelationsFields()) {

            (new CustomRelationField($this->object))->validate();

        } else {

            throw new \Exception('Undefined object type');

        }
    }

    /**
    * Check if self was the `data source` object type
    *
    * @return boolean isCustomRelationsFields()
    */
    private function isDataSource() : bool
    {
        return $this->type === SELF::TYPE_DATA_SOURCE;
    }

    /**
    * Check if self was the `custom relations field` object type
    *
    * @return boolean isCustomRelationsFields()
    */
    private function isCustomRelationsFields() : bool
    {
        return $this->type === SELF::TYPE_CUSTOM_RELATIONS_FIELDS;
    }

    /**
    * Return error message
    *
    * @return string errors
    */
    public function getErrors(): string
    {
        return $this->errors;
    }

    /**
    * Return error message
    *
    * @return array self
    */
    public function get() : array
    {
        return $this->object;
    }
}
