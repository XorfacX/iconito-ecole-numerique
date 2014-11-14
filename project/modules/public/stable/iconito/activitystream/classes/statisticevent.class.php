<?php

use ActivityStream\Client\Model\Statistic;
use ActivityStream\Client\Model\ResourceInterface;

_classInclude('activityStream|StreamObjectEvent');
_classInclude('activityStream|ActivityStreamManagerFactory');

class StatisticEvent extends StreamObjectEvent
{
    /**
     * @var ActivityStream\Client\Model\Statistic
     */
    protected $statistic;

    /**
     * Construction de l'évènement
     *
     * @param integer            $counter
     * @param string             $period
     * @param ResourceInterface  $actor
     * @param string             $verb
     * @param ResourceInterface  $object
     * @param ResourceInterface  $target
     * @param array              $targetScope
     */
    public function __construct($counter, $period, ResourceInterface $actor = null, $verb = null, ResourceInterface $object = null, ResourceInterface $target = null, array $targetScope = null)
    {
        $this->statistic = ActivityStreamManagerFactory::create()->createStatistic(
            $this->getApplicationId(),
            $counter,
            $period,
            $actor,
            $verb,
            $object,
            $target,
            $targetScope
        );
    }

    /**
     * Retourne la ligne de statistique attachée à l'évènement
     *
     * @return ActivityStream\Client\Model\Statistic
     */
    public function getStatistic()
    {
        return $this->statistic;
    }
}
