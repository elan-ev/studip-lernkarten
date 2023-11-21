<?php

namespace Lernkarten\Models;

use Course;
use RuntimeException;
use SimpleORMap;
use User;

class Deck extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'lernkarten_decks';

        $config['has_many']['cards'] = [
            'class_name' => Card::class,
            'assoc_foreign_key' => 'deck_id',
            'on_delete' => 'delete',
            'on_store' => 'store',
            'order_by' => 'ORDER BY mkdate',
        ];

        $config['has_many']['shared_decks'] = [
            'class_name' => SharedDeck::class,
            'assoc_foreign_key' => 'deck_id',
            'on_delete' => 'delete',
            'on_store' => 'store',
            'order_by' => 'ORDER BY mkdate',
        ];

        $config['belongs_to']['folder'] = [
            'class_name' => Folder::class,
            'foreign_key' => 'folder_id',
        ];

        $config['belongs_to']['owner'] = [
            'class_name' => User::class,
            'foreign_key' => 'owner_id',
        ];

        parent::configure($config);
    }

    /**
     * @return User|Course|null
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getContext()
    {
        switch ($this->context_type) {
            case Course::class:
                /** @var Course|null */
                return Course::find($this->context_id);
            case User::class:
                /** @var User|null */
                return User::find($this->context_id);
        }

        throw new RuntimeException('Unknown context_type.');
    }
}
