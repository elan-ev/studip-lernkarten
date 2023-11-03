<?php

namespace Lernkarten\JsonApi\Schemas;

use JsonApi\Schemas\SchemaProvider;
use Lernkarten\Models\Folder as FolderModel;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Schema\Link;

class Folder extends SchemaProvider
{
    public const TYPE = 'lernkarten-folders';
    public const REL_CONTEXT = 'context';
    public const REL_PARENT = 'parent';

    /**
     * {@inheritdoc}
     * @param FolderModel $resource
     */
    public function getId($resource): ?string
    {
        return (string) $resource->id;
    }

    /**
     * {@inheritdoc}
     * @param FolderModel $resource
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            'name' => (string) $resource->name,
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

        $context = $resource->getContext();
        $relationships[self::REL_CONTEXT] = [
            self::RELATIONSHIP_LINKS => [
                Link::RELATED => $this->createLinkToResource($context),
            ],
            self::RELATIONSHIP_DATA => $context,
        ];

        $parent = $resource->parent;
        $relationships[self::REL_PARENT] = [
            self::RELATIONSHIP_LINKS => $parent
                ? [
                    Link::RELATED => $this->createLinkToResource($parent),
                ]
                : [],
            self::RELATIONSHIP_DATA => $parent,
        ];

        return $relationships;
    }
}
