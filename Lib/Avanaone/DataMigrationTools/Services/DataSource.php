<?php

namespace Lib\Avanaone\DataMigrationTools\Services;

interface DataSource {

    public function isEnable(): bool;

    public function validate(): bool;
    
    public function getKeyName(): string;

}
