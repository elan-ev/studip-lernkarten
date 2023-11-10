<?php

namespace Lernkarten\Models;

use SimpleORMap;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
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

        $config['registered_callbacks']['after_delete'][] = function () {
            Note::prune();
        };

        parent::configure($config);
    }

    public function updateFields(array $fields): void
    {
        $this->note = $this->note->cloneWithFields($fields);
        $this->store();
        Note::prune();
    }
}
