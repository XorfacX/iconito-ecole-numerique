<?php

_classInclude('statistiques|StatistiquesFilter');

class StatistiquesApiFilter extends StatistiquesFilter
{
    protected static $allowedKeys = array(
        'id',
        'period',
        'application_id',
        'published_from',
        'published_to',
        'last_only',
        'actor_object_type',
        'actor_id',
        'actor_display_name',
        'actor_url',
        'actor_attributes',
        'verb',
        'counter',
        'object_object_type',
        'object_id',
        'object_display_name',
        'object_url',
        'object_attributes',
        'target_object_type',
        'target_id',
        'target_display_name',
        'target_url',
        'target_attributes'
    );

    public function __construct(array $filterData = array())
    {
        parent::__construct(static::$allowedKeys, $filterData);
    }

    public function toQueryParameters()
    {

    }

}