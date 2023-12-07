<?php

namespace Lernkarten\Models;

use Course;
use DBManager;
use RuntimeException;
use SimpleORMap;
use User;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Deck extends SimpleORMap
{
    use HasPolicy;

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

        $config['has_many']['copies'] = [
            'class_name' => Deck::class,
            'assoc_foreign_key' => 'template_id',
            'on_delete' => function ($template) {
                DBManager::get()->execute(
                    'UPDATE lernkarten_decks SET template_id = NULL WHERE template_id = ?',
                    [$template->id]
                );
            },
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

        $config['belongs_to']['template'] = [
            'class_name' => Deck::class,
            'foreign_key' => 'template_id',
        ];

        $config['belongs_to']['shared_deck'] = [
            'class_name' => SharedDeck::class,
            'foreign_key' => 'shared_deck_id',
        ];

        parent::configure($config);
    }

    public function copyToWorkPlace(User $user, SharedDeck $sharedDeck = null): Deck
    {
        $resource = self::create([
            'folder_id' => null,
            'context_id' => $user->id,
            'context_type' => User::class,
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $user->id,
            'template_id' => $this->id,
            'shared_deck_id' => $sharedDeck ? $sharedDeck->id : null,
        ]);

        $resource->importCardsFromDeck($this);

        return $resource;
    }

    /**
     * @return User|Course|null
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

    /**
     * @return array{0:int, 1:int, 2:int, 3:int} an array containing the number of cards per state.
     *                                           The state is encoded as 0-3 and used as the key
     *                                           to the array.
     */
    public function getProgress(): array
    {
        $sql =
            'SELECT IF(state IS NULL, 0, state) as state, COUNT(state) as count FROM `lernkarten_cards` WHERE deck_id = ? GROUP BY state';
        $results = DBManager::get()->fetchPairs($sql, [$this->id], function ($x) {
            return (int) $x;
        });
        return $results + array_fill(0, 4, 0);
    }

    public function getSharedWith(): iterable
    {
        return $this->shared_decks->map(function ($sharedDeck) {
            return $sharedDeck->getRecipient();
        });
    }

    public function importCardsFromDeck(Deck $deck): void
    {
        if ($deck->id === $this->id) {
            return;
        }

        DBManager::get()->execute(
            'INSERT INTO lernkarten_cards (note_id, original_note_id, deck_id) ' .
                'SELECT note_id, note_id as original_note_id, ? as deck_id ' .
                'FROM `lernkarten_cards` ' .
                'WHERE deck_id = ?',
            [$this->id, $deck->id]
        );
    }
}
