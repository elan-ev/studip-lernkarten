<?php

class IndexController extends PluginController
{
    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $cid = Context::getId();
        Navigation::activateItem($cid ? '/course/lernkarten/index' : '/contents/lernkarten/index');
        PageLayout::setHelpKeyword('Lernkarten.Introduction');
        PageLayout::setTitle(_('Lernkarten'));
    }

    public function index_action()
    {
        $this->isTeacher = Context::isCourse()
            ? $GLOBALS['perm']->have_studip_perm('tutor', Context::getId())
            : $GLOBALS['perm']->have_perm('tutor');
    }
}