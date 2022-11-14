<?php

namespace Lib\Avanaone\DataMigrationTools\Services;

interface ConfigLoader
{

    public function getArray(): array;

    public function checkArray(): bool;

}
