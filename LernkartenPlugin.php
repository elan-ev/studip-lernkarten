<?php

use Lernkarten\JsonApi\Routes;
use Lernkarten\JsonApi\Schemas;
use Lernkarten\StudIP\Datenschutz;
use JsonApi\Contracts\JsonApiPlugin;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @SuppressWarnings(StaticAccess)
 */
class LernkartenPlugin extends StudIPPlugin implements StandardPlugin, JsonApiPlugin
{
    use Routes;
    use Schemas;
    use Datenschutz;

    /**
     * {@inheritdoc}
     */
    public function getTabNavigation($courseId)
    {
        $navigation = new Navigation(
            'Lernkarten',
            PluginEngine::getURL($this, ['cid' => $courseId], '', true)
        );
        $navigation->addSubnavigation('lernkarten', clone $navigation);

        return ['lernkarten' => $navigation];
    }

    /**
     * {@inheritdoc}
     */
    public function getIconNavigation($courseId, $lastVisit, $userId)
    {
        $icon = new AutoNavigation(
            'Lernkarten',
            PluginEngine::getURL(
                $this,
                ['cid' => $courseId, 'iconnav' => 'true'],
                'lernkarten',
                true
            )
        );
        $icon->setImage(Icon::create('group3', 'inactive', ['title' => 'Lernkarten']));

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
        if (!$this->isActivated(\Context::getId()) && $_SERVER['REQUEST_METHOD'] === 'GET') {
            throw new AccessDeniedException('This plugin not activated for this course.');
        }

        PageLayout::setHelpKeyword('Lernkarten.Introduction');
        \Navigation::activateItem('/course/lernkarten/lernkarten');

        $PLGNURL = $this->getPluginURL();
        PageLayout::addStylesheet($PLGNURL . '/dist/style.css');
        PageLayout::addScript($PLGNURL . '/dist/lernkarten.js', ['type' => 'module']);

        $initialState = [];

        echo $GLOBALS['template_factory']->render('layouts/base', [
            'content_for_layout' => $this->bootstrapHtml($initialState),
        ]);
    }

    /**
     * @param mixed $initialState
     */
    private function bootstrapHtml($initialState): string
    {
        ob_start(); ?>
        <div id="lernkarten-app"></div>
        <script>
            document.addEventListener(
                "DOMContentLoaded",
                function() {
                    if (window.STUDIP && window.STUDIP.mountLernkarten) {
                        STUDIP.domReady(() => {
                            const initialState = <?= json_encode($initialState) ?>;
                            const vm = window.STUDIP.mountLernkarten("#lernkarten-app", initialState);
                        })
                    }
                }
            )
        </script>
        <?php return ob_get_clean() ?: '';
    }
}
