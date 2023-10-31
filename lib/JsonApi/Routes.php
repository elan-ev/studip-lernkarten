<?php

namespace Lernkarten\JsonApi;

trait Routes
{
    public function registerAuthenticatedRoutes(\Slim\Routing\RouteCollectorProxy $group)
    {
        $group->get('/lernkarten-folders', Routes\FoldersIndex::class);
        $group->post('/lernkarten-folders', Routes\FoldersCreate::class);
        $group->get('/lernkarten-folders/{id}', Routes\FoldersShow::class);
        $group->delete('/lernkarten-folders/{id}', Routes\FoldersDelete::class);
    }

    public function registerUnauthenticatedRoutes(\Slim\Routing\RouteCollectorProxy $group)
    {
    }
}
