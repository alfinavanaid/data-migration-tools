<?php

namespace Lib\Avanaone\DataMigrationTools\Utility;

class JsonStructure
{

    /**
     * @var array $object
     * @var int $type
     * @var string $error
     */

    private $object;
    private $type;
    private $errors;

    /**
     * @var const int TYPE_DATA_SOURCE defined as data source
     * @var const int TYPE_CUSTOM_RELATIONS_FIELD defined as custom relations fields
     */

    public const TYPE_DATA_SOURCE = 1;
    public const TYPE_CUSTOM_RELATIONS_FIELD = 2;

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
     * Check if self was the `data source` object type
     *
     * @return boolean isCustomRelationsField()
     */
    public function isDataSource(): bool
    {
        return $this->type === SELF::TYPE_DATA_SOURCE;
    }

    /**
     * Check if self was the `custom relations field` object type
     *
     * @return boolean isCustomRelationsField()
     */
    public function isCustomRelationsField(): bool
    {
        return $this->type === SELF::TYPE_CUSTOM_RELATIONS_FIELD;
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
    public function get(): object
    {
        return (object)$this->object;
    }
}
