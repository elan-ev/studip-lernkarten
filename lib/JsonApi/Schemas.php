<?php

namespace Lernkarten\JsonApi;

trait Schemas
{
    public function registerSchemas(): array
    {
        return [
            \Lernkarten\Models\Folder::class => Schemas\Folder::class,
        ];
    }
}
