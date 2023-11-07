<?php

namespace Lernkarten\JsonApi;

trait Schemas
{
    public function registerSchemas(): array
    {
        return [
            \Lernkarten\Models\Deck::class => Schemas\Deck::class,
            \Lernkarten\Models\Folder::class => Schemas\Folder::class,
        ];
    }
}
