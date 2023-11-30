<?php

namespace Lernkarten\Models;

use SimpleORMap;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Card extends SimpleORMap
{
    use HasPolicy;

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
        $config['belongs_to']['original_note'] = [
            'class_name' => Note::class,
            'foreign_key' => 'original_note_id',
        ];

        $config['registered_callbacks']['before_create'][] = function ($card) {
            $now = time();
            $card->due = $now;
            $card->last_review = $now;
        };

        $config['registered_callbacks']['after_delete'][] = function () {
            Note::prune();
        };

        parent::configure($config);
    }

    public function isPristine(): bool
    {
        return $this->original_note_id === null || $this->original_note_id === $this->note_id;
    }

    public function updateFields(array $fields): void
    {
        $this->note = $this->note->cloneWithFields($fields);
        $this->store();
        Note::prune();
    }
}
