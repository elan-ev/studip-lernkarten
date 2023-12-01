<?php

namespace Lernkarten\Policies;

use Lernkarten\Models\Deck;
use User;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class DeckPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // The decks will be filtered by this user.
        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyOfUser(User $user, User $observed): bool
    {
        return $user->id === $observed->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Deck $deck): bool
    {
        // TODO: Stimmt das so? Was ist im Veranstaltungskontext?
        return $deck->owner_id = $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Anyone may create a deck.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Deck $deck): bool
    {
        return $deck->owner_id === $user->id && !$deck->colearning;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Deck $deck): bool
    {
        return $this->update($user, $deck);
    }

    /**
     * Determine whether the user can copy the model.
     */
    public function copy(User $user, Deck $deck): bool
    {
        return $this->update($user, $deck);
    }
}
