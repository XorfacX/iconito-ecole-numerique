<?php

use ActivityStream\Client\Model\Resource;
use ActivityStream\Client\Model\ResourceInterface;

_classInclude('eventDispatcher|EventDispatcherFactory');
_classInclude('activityStream|ActivityStreamManagerFactory');
_classInclude('activityStream|StreamObjectEvent');
_classInclude('activityStream|ActivityEvent');
_classInclude('activityStream|StatisticEvent');
_classInclude('activityStream|ActivityStreamPerson');

/**
 * Classe de service de l'activyStream
 */
class ActivityStreamService
{
    /**
     * @var ActivityStreamService L'instance courante
     */
    protected static $instance;

    const
        EVENT_ACTIVITY = 'activity_stream.push_activity',
        EVENT_STATISTIC = 'activity_stream.push_statistic';

    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->eventDispatcher = EventDispatcherFactory::getInstance();
    }

    /**
     * Récupération de l'instance courante (singleton) avec création au besoin
     *
     * @return ActivityStreamService
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Log une activité (lance l'évènement)
     *
     * @param string            $verb        Le verbe
     * @param ResourceInterface $actor       L'acteur
     * @param ResourceInterface $object      L'objet
     * @param ResourceInterface $target      La cible
     * @param array             $context     Le périmètre de la cible
     */
    public function logActivity($verb, ResourceInterface $actor = null, ResourceInterface $object = null,
                                ResourceInterface $target = null, array $context = null)
    {
        $this->eventDispatcher->dispatch(self::EVENT_ACTIVITY, new ActivityEvent(
            $verb,
            $actor,
            $object,
            $target,
            $context
        ));
    }

    /**
     * Log une statistique (lance l'évènement)
     *
     * @param int               $counter     Le compteur
     * @param string            $period      La période de calcul
     * @param ResourceInterface $actor       L'acteur
     * @param null              $verb        Le verbe
     * @param ResourceInterface $object      L'objet
     * @param ResourceInterface $target      La cible
     * @param array             $context     Le périmètre de la cible
     */
    public function logStatistic($counter, $period, ResourceInterface $actor = null, $verb = null,
                                 ResourceInterface $object = null, ResourceInterface $target = null,
                                 array $context = null)
    {
        $this->eventDispatcher->dispatch(self::EVENT_STATISTIC, new StatisticEvent(
            $counter,
            $period,
            $actor,
            $verb,
            $object,
            $target,
            $context
        ));
    }

    /**
     * Retourne une Resource à partir des userInfo
     *
     * @param array $userInfo
     *
     * @return Resource
     */
    public function getPersonFromUserInfo(array $userInfo)
    {
        return new ActivityStreamPerson($userInfo['type'], $userInfo['id'], $userInfo['prenom'], $userInfo['nom']);
    }
}
