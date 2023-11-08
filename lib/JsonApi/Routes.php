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

        $group->get('/lernkarten-decks', Routes\DecksIndex::class);
        $group->post('/lernkarten-decks', Routes\DecksCreate::class);
        $group->get('/lernkarten-decks/{id}', Routes\DecksShow::class);
        $group->delete('/lernkarten-decks/{id}', Routes\DecksDelete::class);

        $group->post('/lernkarten-cards', Routes\CardsCreate::class);
        $group->get('/lernkarten-cards/{id}', Routes\CardsShow::class);

        $group->get('/lernkarten-folders/{id}/decks', Routes\DecksOfFoldersShow::class);
        $group->get('/lernkarten-decks/{id}/cards', Routes\CardsOfDecksShow::class);

        $group->get('/courses/{id}/lernkarten-decks', Routes\DecksOfCoursesIndex::class);
        $group->get('/users/{id}/lernkarten-decks', Routes\DecksOfUsersIndex::class);
    }

    public function registerUnauthenticatedRoutes(\Slim\Routing\RouteCollectorProxy $group)
    {
    }
}
