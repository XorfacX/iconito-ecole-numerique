<?php

use ActivityStream\Client\Model\Activity;
use ActivityStream\Client\Model\Statistic;

_classInclude('activityStream|ActivityStreamManagerFactory');
_classInclude('activityStream|ActivityEvent');
_classInclude('activityStream|StatisticEvent');

class ActivityStreamListener
{
    /**
     * @var ActivityStream\Client\Manager\ActivityStreamManager
     */
    protected $activityStreamManager;

    /**
     * Construction de la classe
     */
    public function __construct()
    {
        $this->manager = ActivityStreamManagerFactory::create();
    }

    /**
     * Envoi une ligne d'activité dans l'adapter
     *
     * @param ActivityEvent $event
     */
    public function pushActivity(ActivityEvent $event)
    {
        $activity = $event->getActivity();

        $this->manager->pushStreamObject($activity);
    }

    /**
     * Envoi une ligne de statistic dans l'adapter
     *
     * @param StatisticEvent $event
     */
    public function pushStatistic(StatisticEvent $event)
    {
        $statistic = $event->getStatistic();

        $this->manager->pushStreamObject($statistic);
    }
}
