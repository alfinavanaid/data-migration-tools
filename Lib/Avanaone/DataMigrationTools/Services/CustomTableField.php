<?php

namespace Lib\Avanaone\DataMigrationTools\Services;

interface CustomTableField {

    public function isEnable(): bool;

    public function validate(): bool;
    
    public function getKeyName(): string;

}
