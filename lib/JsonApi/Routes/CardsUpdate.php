<?php

namespace Lernkarten\JsonApi\Routes;

use JsonApi\Errors\AuthorizationFailedException;
use JsonApi\Errors\RecordNotFoundException;
use JsonApi\JsonApiController;
use JsonApi\Routes\ValidationTrait;
use Lernkarten\JsonApi\Schemas\Card as CardSchema;
use Lernkarten\Models\Card;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Updates one Card.
 */
class CardsUpdate extends JsonApiController
{
    use ValidationTrait;

    /**
     * @param array $args
     * @return Response
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        /** @var ?\Lernkarten\Models\Card $resource */
        $resource = Card::find($args['id']);
        if (!$resource) {
            throw new RecordNotFoundException();
        }
        $json = $this->validate($request, $resource);
        $card = $this->update($resource, $json);

        return $this->getContentResponse($card);
    }

    /**
     * @param array $json
     * @param Card $data
     *
     * @return string|void
     */
    protected function validateResourceDocument($json, $data)
    {
        if (!self::arrayHas($json, 'data')) {
            return 'Missing `data` member at document´s top level.';
        }
        if (CardSchema::TYPE !== self::arrayGet($json, 'data.type')) {
            return 'Invalid `type` of document´s `data`.';
        }
        if (!self::arrayHas($json, 'data.id')) {
            return 'Document must have an `id`.';
        }
        if (self::arrayGet($json, 'data.id') !== $data->id) {
            return 'Different `id`';
        }

        if (self::arrayHas($json, 'data.attributes.guid')) {
            if (self::arrayGet($json, 'data.attributes.guid') !== $data->note->guid) {
                return 'Different `guid` attribute';
            }
        }

        // attr fields
        if (!self::arrayHas($json, 'data.attributes.fields')) {
            return 'Missing attribute `fields`.';
        }
        if (!$this->validFields($json)) {
            return 'Invalid attribute `model`.';
        }
    }

    private function update(Card $resource, array $json): Card
    {
        $fields = self::arrayGet($json, 'data.attributes.fields');
        $resource->updateFields($fields);

        return $resource;
    }

    private function validFields(array $json): bool
    {
        $fields = self::arrayGet($json, 'data.attributes.fields');

        return is_array($fields);
    }
}
