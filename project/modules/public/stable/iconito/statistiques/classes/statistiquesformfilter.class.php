<?php

_classInclude('statistiques|StatistiquesFilter');

class StatistiquesFormFilter extends StatistiquesFilter
{
    protected static $allowedKeys = array(
        'context',
        'published_from',
        'published_to'
    );

    public function __construct(array $filterData = array())
    {
        parent::__construct(static::$allowedKeys, $filterData);
    }
}