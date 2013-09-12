<?php

_classInclude('activityStream|ActivityStreamService');

/**
 * Classe d'écouteur de l'activityStream
 */
class ListenerActivityStream extends CopixListener
{
    /**
     * Listener sur le login
     *
     * @param $event
     * @param $eventResponse
     */
    public function processLogin($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'login',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras())
        );
    }

    public function processCreateEvent($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('event')->toResource()
        );
    }

    public function processSendMinimail($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('minimail')->toResource()
        );
    }

    public function processCreateFile($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('file')->toResource(),
            $event->getParam('folder')->toResource()
        );
    }

    public function processCreateArticle($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('article')->toResource(),
            $event->getParam('blog')->toResource()
        );
    }

    public function processCreateComment($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('comment')->toResource(),
            $event->getParam('article')->toResource()
        );
    }
}
