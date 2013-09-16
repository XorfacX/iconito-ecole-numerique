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

    /**
     * Listener sur la crétion d'événements
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateEvent($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();
        $eventObject = $event->getParam('event');
        $contexts = Kernel::getModContexts('MOD_AGENDA', $eventObject->id_agenda);

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $eventObject->toResource(),
            reset($contexts),
            $contexts
        );
    }

    /**
     * Listener sur l'envoi de minimails
     *
     * @param $event
     * @param $eventResponse
     */
    public function processSendMinimail($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('minimail')->toResource()
        );
    }

    /**
     * Listener sur la création de fichiers
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateFile($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();
        $file = $event->getParam('file');
        $contexts = Kernel::getModContexts('MOD_CLASSEUR', $file->classeur_id);

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $file->toResource(),
            $event->getParam('folder')->toResource(),
            $contexts
        );
    }

    /**
     * Listener sur la création d'articles
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateArticle($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();

        $article = $event->getParam('article');
        $contexts = Kernel::getModContexts('MOD_BLOG', $article->id_blog);

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $article->toResource(),
            $event->getParam('blog')->toResource(),
            $contexts
        );
    }

    /**
     * Listener sur la création de commentaires
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateComment($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();
        $article = $event->getParam('article');
        $contexts = Kernel::getModContexts('MOD_BLOG', $article->id_blog);

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('comment')->toResource(),
            $article->toResource(),
            $contexts
        );
    }

    /**
     * Listener sur la création de quiz
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateQuiz($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();
        $quiz = $event->getParam('quiz');
        $contexts = Kernel::getModContexts('MOD_QUIZ', $quiz->gr_id);


        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $quiz->toResource(),
            null,
            $contexts
        );
    }

    /**
     * Listener sur la création de question de quiz
     *
     * @param $event
     * @param $eventResponse
     */
    public function processCreateQuestion($event, $eventResponse)
    {
        $activityStreamService = new ActivityStreamService();
        $quiz = $event->getParam('quiz');
        $contexts = Kernel::getModContexts('MOD_QUIZ', $quiz->gr_id);

        $activityStreamService->logActivity(
            'create',
            $activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('question')->toResource(),
            $quiz,
            $contexts
        );
    }
}
