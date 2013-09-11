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
}
