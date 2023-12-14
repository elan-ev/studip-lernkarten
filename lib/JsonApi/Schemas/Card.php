<?php

namespace Lernkarten\JsonApi\Schemas;

use Lernkarten\Models\Card as CardModel;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Schema\Link;

class Card extends SchemaProvider
{
    public const TYPE = 'lernkarten-cards';
    public const REL_DECK = 'deck';

    /**
     * {@inheritdoc}
     * @param CardModel $resource
     */
    public function getId($resource): ?string
    {
        return (string) $resource->id;
    }

    /**
     * {@inheritdoc}
     * @param CardModel $resource
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        $note = $resource->note;
        return [
            'guid' => (string) $note->guid,
            'model' => (string) $note->model,
            'fields' => json_decode($note->fields, true),
            'due' => date('c', $resource->due),
            'stability' => (float) $resource->stability,
            'difficulty' => (float) $resource->difficulty,
            'elapsed-days' => (int) $resource->elapsed_days,
            'scheduled-days' => (int) $resource->scheduled_days,
            'reps' => (int) $resource->reps,
            'lapses' => (int) $resource->lapses,
            'state' => (int) $resource->state,
            'last-review' => date('c', $resource->last_review),
            'again-count' => (int) $resource->again_count,
            'hard-count' => (int) $resource->hard_count,
            'good-count' => (int) $resource->good_count,
            'easy-count' => (int) $resource->easy_count,
            'mkdate' => date('c', $resource->mkdate),
            'chdate' => date('c', $resource->chdate),
        ];
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        $relationships = [];

        $deck = $resource->deck;
        $relationships[self::REL_DECK] = [
            self::RELATIONSHIP_LINKS => [
                Link::RELATED => $this->createLinkToResource($deck),
            ],
            self::RELATIONSHIP_DATA => $deck,
        ];

        return $relationships;
    }
}
