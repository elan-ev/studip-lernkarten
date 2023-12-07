<?php

namespace Lernkarten\JsonApi\Routes;

use InvalidArgumentException;
use JsonApi\JsonApiController as StudipJsonApiController;
use Psr\Http\Message\ServerRequestInterface as Request;
use User;

class JsonApiController extends StudipJsonApiController
{
    public function can(Request $request, string $ability, ...$arguments): bool
    {
        $policedArgument = current($arguments);
        if (is_object($policedArgument)) {
            $class = get_class($policedArgument);
        } else if (class_exists($policedArgument)) {
            $class = $policedArgument;
        } else {
            throw new InvalidArgumentException();
        }
        $user = $this->getUser($request);

        return $class::getPolicy()->$ability($user, ...$arguments);
    }

    public function cannot(Request $request, string $ability, ...$arguments): bool
    {
        return !$this->can($request, $ability, ...$arguments);
    }
}
