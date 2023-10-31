<?php

namespace Lernkarten\JsonApi\Routes;

use Course;
use JsonApi\Errors\AuthorizationFailedException;
use JsonApi\Errors\BadRequestException;
use JsonApi\Errors\RecordNotFoundException;
use JsonApi\JsonApiController;
use JsonApi\Routes\ValidationTrait;
use JsonApi\Schemas\Course as CourseSchema;
use JsonApi\Schemas\User as UserSchema;
use Lernkarten\JsonApi\Schemas\Folder as FolderSchema;
use Lernkarten\Models\Folder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use User;

/**
 * Creates a Folder.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class FoldersCreate extends JsonApiController
{
    use ValidationTrait;

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param array $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $json = $this->validate($request);

        // if (!Authority::canCreatePeerReviewProcesses($user, $taskGroup)) {
        //     throw new AuthorizationFailedException();
        // }

        $resource = $this->create($json);

        return $this->getCreatedResponse($resource);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     *
     * @param array $json
     * @param mixed $data
     *
     * @return string|void
     */
    protected function validateResourceDocument($json, $data)
    {
        if (!self::arrayHas($json, 'data')) {
            return 'Missing `data` member at document´s top level.';
        }
        if (FolderSchema::TYPE !== self::arrayGet($json, 'data.type')) {
            return 'Invalid `type` of document´s `data`.';
        }
        if (self::arrayHas($json, 'data.id')) {
            return 'New document must not have an `id`.';
        }

        if (!self::arrayHas($json, 'data.attributes.name')) {
            return 'Missing `name` attribute.';
        }
        if (!mb_strlen(trim(self::arrayGet($json, 'data.attributes.name')))) {
            return 'Empty `name` attribute.';
        }

        // rel context
        if (!self::arrayHas($json, 'data.relationships.context')) {
            return 'Missing `context` relationship.';
        }
        if (!$this->getContextFromJson($json)) {
            return 'Invalid `context` relationship.';
        }

        // rel parent
        if (self::arrayHas($json, 'data.relationships.parent')) {
            if (!$this->getParentFromJson($json)) {
                return 'Invalid `parent` relationship.';
            }
        }
    }

    private function create(array $json): Folder
    {
        $context = $this->getContextFromJson($json);
        $parent = $this->getParentFromJson($json);
        $name = trim(self::arrayGet($json, 'data.attributes.name'));

        $folder = Folder::create([
            'parent_id' => $parent ? $parent->id : null,
            'context_id' => $context->id,
            'context_type' => get_class($context),
            'name' => $name,
        ]);

        return $folder;
    }

    /**
     * @return null|Course|User
     */
    private function getContextFromJson(array $json)
    {
        $relation = 'data.relationships.' . FolderSchema::REL_CONTEXT;
        $resourceId = self::arrayGet($json, $relation . '.data.id');
        if ($this->validateResourceObject($json, $relation, CourseSchema::TYPE)) {
            return Course::find($resourceId);
        }
        if ($this->validateResourceObject($json, $relation, UserSchema::TYPE)) {
            return User::find($resourceId);
        }

        return null;
    }

    /**
     * @return null|Folder
     * @throws RecordNotFoundException
     */
    private function getParentFromJson(array $json)
    {
        $relation = 'data.relationships.' . FolderSchema::REL_PARENT;
        $resourceId = self::arrayGet($json, $relation . '.data.id');
        if ($this->validateResourceObject($json, $relation, FolderSchema::TYPE)) {
            if (!Folder::exists($resourceId)) {
                throw new RecordNotFoundException('Invalid `parent` relationship.');
            }
            return Folder::find($resourceId);
        }

        return null;
    }
}
