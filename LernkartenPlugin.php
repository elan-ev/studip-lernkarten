<?php

use DI\Container;
use DI\ContainerBuilder;
use Lernkarten\Http\Controllers\ExportPDF;
use Lernkarten\Http\Controllers\Wildcard;
use Lernkarten\JsonApi\Routes;
use Lernkarten\JsonApi\Schemas;
use Lernkarten\StudIP\Datenschutz;
use JsonApi\Contracts\JsonApiPlugin;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @SuppressWarnings(StaticAccess)
 */
class LernkartenPlugin extends StudIPPlugin implements SystemPlugin, StandardPlugin, JsonApiPlugin
{
    use Routes;
    use Schemas;
    use Datenschutz;

    public function __construct()
    {
        parent::__construct();

        PageLayout::addStylesheet($this->getPluginUrl() . '/dist/lernkarten.css');

        PageLayout::addScript($this->getPluginUrl() . '/dist/lernkarten.js', [
            'type' => 'module',
            'rel' => 'preload',
        ]);
        PageLayout::addScript($this->getPluginUrl() . '/dist/register.js', [
            'type' => 'module',
            'rel' => 'preload',
        ]);

        $this->addContentsNavigation();
    }

    /**
     * {@inheritdoc}
     */
    public function getTabNavigation($courseId)
    {
        return ['lernkarten' => $this->createNavigation($courseId)];
    }

    /**
     * {@inheritdoc}
     */
    public function getIconNavigation($courseId, $lastVisit, $userId)
    {
        $icon = new AutoNavigation(
            $this->_('Lernkarten'),
            PluginEngine::getURL($this, ['cid' => $courseId, 'iconnav' => 'true'], '', true)
        );
        $icon->setImage(Icon::create('dialog-cards', 'inactive', ['title' => 'Lernkarten']));

        return $icon;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $courseId
     * @return null|object
     */
    public function getInfoTemplate($courseId)
    {
        return null;
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(UnusedFormalParameter)
     * @param string $unconsumedPath
     */
    public function perform($unconsumedPath)
    {
        parent::perform($unconsumedPath);
    }

    private function addContentsNavigation(): void
    {
        Navigation::addItem('/contents/lernkarten', $this->createNavigation());
    }

    private function createNavigation(string $cid = null): Navigation
    {
        $params = $cid ? ['cid' => $cid] : [];
        $navigation = new Navigation($this->_('Lernkarten'));
        $navigation->setDescription(
            $this->_('Eigene Kartensätze erstellen, teilen und in Kurse einbinden')
        );
        $navigation->setImage(Icon::create('dialog-cards', 'navigation'));
        $navigation->setURL(PluginEngine::getURL($this, $params, '', true));

        // subnavigation
        $navigation->addSubnavigation('index', clone $navigation);

        return $navigation;
    }
}
