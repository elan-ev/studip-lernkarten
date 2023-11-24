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
     * @param User|Course|null $recipient
     */
    public static function isShared(Deck $deck, $recipient): bool
    {
        return !!self::findOneBySql('deck_id = ? AND recipient_id = ? AND recipient_type = ?', [
            $deck->id,
            $recipient->getId(),
            get_class($recipient),
        ]);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function copyToWorkPlace(User $user): Deck
    {
        $resource = Deck::create([
            'folder_id' => null,
            'context_id' => $user->id,
            'context_type' => User::class,
            'name' => $this->deck->name,
            'description' => $this->deck->description,
            'owner_id' => $user->id,
            'template_id' => $this->deck_id,
        ]);

        $resource->importCardsFromDeck($this->deck);

        return $resource;
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
