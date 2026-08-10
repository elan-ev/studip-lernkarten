<div id="lernkarten-app"
    data-user-id="<?= htmlready($GLOBALS['user']->id) ?>"
    data-course-id="<?= htmlready(Request::option('cid')) ?>"
    data-is-teacher="<?= $this->isTeacher ? 'true' : 'false' ?>"
    data-api-base="<?= htmlready( PluginEngine::getURL($this->plugin, [], '')) ?>"
></div>