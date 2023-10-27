<?php

namespace Lernkarten\Models;

use SimpleORMap;

class Card extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'lernkarten_cards';

        $config['belongs_to']['deck'] = [
            'class_name' => Deck::class,
            'foreign_key' => 'deck_id',
        ];
        $config['belongs_to']['note'] = [
            'class_name' => Note::class,
            'foreign_key' => 'note_id',
        ];

        parent::configure($config);
    }
}
