<?php

_classInclude('activityStream|ActivityStreamService');

/**
 * Classe d'écouteur de l'activityStream
 */
class ListenerActivityStream extends CopixListener
{
    /**
     * @var ActivityStreamService
     */
    protected $activityStreamService;

    /**
     * Surcharge du constructeur afin de stocker la classe de service
     */
    public function __construct()
    {
        $this->activityStreamService = new ActivityStreamService();
    }

    /**
     * Listener sur le login
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processLogin(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $this->activityStreamService->logActivity(
            'login',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras())
        );
    }

    /**
     * Listener sur la création d'événements
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateEvent(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $eventObject = $event->getParam('event');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $eventObject->toResource(),
            $this->activityStreamService->getResource('MOD_AGENDA', $eventObject->id_agenda),
            $this->activityStreamService->getContextResources('MOD_AGENDA', $eventObject->id_agenda)
        );
    }

    /**
     * Listener sur l'envoi de minimails
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processSendMinimail(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $this->activityStreamService->logActivity(
            'send',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('minimail')->toResource(),
            $this->activityStreamService->getPersonFromUserInfo($event->getParam('recipient')),
            $this->activityStreamService->getContextResourcesFromArray(Kernel::getGroupsFromUserInfos($event->getParam('sender')))
        );

        $this->activityStreamService->logActivity(
            'receive',
            $this->activityStreamService->getPersonFromUserInfo($event->getParam('recipient')),
            $event->getParam('minimail')->toResource(),
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $this->activityStreamService->getContextResourcesFromArray(Kernel::getGroupsFromUserInfos($event->getParam('recipient')))
        );
    }

    /**
     * Listener sur la création de fichiers
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateFile(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        // TODO check si les metaData du fichier sont envoyées
        $file = $event->getParam('file');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $file->toResource(),
            $event->getParam('folder')->toResource(),
            $this->activityStreamService->getContextResources('MOD_CLASSEUR', $file->classeur_id)
        );
    }

    /**
     * Listener sur la création d'articles
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateArticle(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $article = $event->getParam('article');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $article->toResource(),
            $event->getParam('blog')->toResource(),
            $this->activityStreamService->getContextResources('MOD_BLOG', $article->id_blog)
        );
    }

    /**
     * Listener sur la création d'articles
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processVisitBlog(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $blog = $event->getParam('blog');

        $contexts = $this->activityStreamService->getContextResources('MOD_BLOG', $blog->id_blog);

        $this->activityStreamService->logActivity(
            'watch',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $blog->toResource(),
            array_shift($contexts),
            $contexts
        );
    }

    /**
     * Listener sur la création de commentaires
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateComment(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $article = $event->getParam('article');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('comment')->toResource(),
            $article->toResource(),
            $this->activityStreamService->getContextResources('MOD_BLOG', $article->id_blog)
        );
    }

    /**
     * Listener sur la création de quiz
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateQuiz(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $quiz = $event->getParam('quiz');
        $context = $this->activityStreamService->getContextResources('MOD_QUIZ', $quiz->gr_id);

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $quiz->toResource(),
            count($context) > 0 ? array_shift($context) : null,
            $context
        );
    }

    /**
     * Listener sur la création de question de quiz
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateQuestion(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $quiz = $event->getParam('quiz');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $event->getParam('question')->toResource(),
            $quiz,
            $this->activityStreamService->getContextResources('MOD_QUIZ', $quiz->gr_id)
        );
    }

    /**
     * Listener sur la création de travaux
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateTravail(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $travail = $event->getParam('travail');

        $classe = _doQuery('SELECT kernel_bu_ecole_classe_id as id FROM module_cahierdetextes_domaine WHERE id = ?', array($travail->domaine_id));
        $classe = reset($classe);

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $travail->toResource(),
            null,
            $this->activityStreamService->getContextResources('BU_CLASSE', $classe->id)
        );
    }

    /**
     * Listener sur la création de travaux
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateMemo(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $memo = $event->getParam('memo');

        $this->activityStreamService->logActivity(
            'create',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $memo->toResource(),
            null,
            $this->activityStreamService->getContextResources('BU_CLASSE', $memo->classe_id)
        );
    }

    /**
     * Listener sur la création de travaux
     *
     * @param CopixEvent $event
     * @param CopixEventResponse $eventResponse
     */
    public function processCreateMessageListe(CopixEvent $event, CopixEventResponse $eventResponse)
    {
        $message = $event->getParam('message');

        $this->activityStreamService->logActivity(
            'send',
            $this->activityStreamService->getPersonFromUserInfo(_currentUser()->getExtras()),
            $message->toResource(),
            $this->activityStreamService->getResource('MOD_LISTE', $message->liste),
            $this->activityStreamService->getContextResources('MOD_LISTE', $message->liste)
        );
    }
}
