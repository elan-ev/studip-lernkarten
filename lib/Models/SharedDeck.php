<?php

namespace Lernkarten\Models;

use Course;
use RuntimeException;
use SimpleORMap;
use User;

class SharedDeck extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'lernkarten_shared_decks';

        $config['belongs_to']['deck'] = [
            'class_name' => Deck::class,
            'foreign_key' => 'deck_id',
        ];

        $config['belongs_to']['sharer'] = [
            'class_name' => User::class,
            'foreign_key' => 'sharer_id',
        ];

        parent::configure($config);
    }

    /**
     * @return User|Course|null
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getRecipient()
    {
        switch ($this->recipient_type) {
            case Course::class:
                /** @var Course|null */
                return Course::find($this->recipient_id);
            case User::class:
                /** @var User|null */
                return User::find($this->recipient_id);
        }

        throw new RuntimeException('Unknown recipient_type.');
    }
}
