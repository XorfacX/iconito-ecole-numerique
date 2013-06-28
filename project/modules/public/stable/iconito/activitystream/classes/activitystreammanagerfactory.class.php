<?php

/**
 * Classe de constr
 */
class ActivityStreamManagerFactory
{
    public static function create()
    {
        _classInclude('activitystream|ActivityStreamAdapterFactory');
        return new ActivityStream\Client\Manager\ActivityStreamManager(ActivityStreamAdapterFactory::create());
    }
}
