<?php

use ActivityStream\Client\Model\Activity;
use ActivityStream\Client\Model\ResourceInterface;

_classInclude('activityStream|StreamObjectEvent');
_classInclude('activitystream|ActivityStreamManagerFactory');

class ActivityEvent extends StreamObjectEvent
{
    /**
     * @var ActivityStream\Client\Model\Activity
     */
    protected $activity;

    /**
     * Construction de l'évènement
     *
     * @param ResourceInterface  $actor
     * @param string             $verb
     * @param ResourceInterface  $object
     * @param ResourceInterface  $target
     * @param array              $targetScope
     */
    public function __construct($verb, ResourceInterface $actor = null, ResourceInterface $object = null, ResourceInterface $target = null, array $targetScope = null)
    {
        $this->activity = ActivityStreamManagerFactory::create()->createActivity(
            $this->getApplicationId(),
            $verb,
            $actor,
            $object,
            $target,
            $targetScope
        );
    }

    /**
     * Retourne la ligne d'activité attachée à l'évènement
     *
     * @return ActivityStream\Client\Model\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
