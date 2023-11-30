<?php

namespace Lernkarten\JsonApi\Routes;

use JsonApi\JsonApiController as StudipJsonApiController;
use Psr\Http\Message\ServerRequestInterface as Request;
use User;

class JsonApiController extends StudipJsonApiController
{
    public function can(Request $request, string $ability, ...$arguments): bool
    {
        $policyObject = current($arguments);
        $class = get_class($policyObject);
        $user = $this->getUser($request);

        return $class::getPolicy()->$ability($user, ...$arguments);
    }

    public function cannot(Request $request, string $ability, ...$arguments): bool
    {
        return !$this->can($request, $ability, ...$arguments);
    }
}
