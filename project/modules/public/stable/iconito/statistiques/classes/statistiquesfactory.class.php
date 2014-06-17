<?php

_classInclude('statistiques|StatistiquesFormFilter');

/**
 * Class StatistiquesFactory
 *
 * Construction des classe de statistiques afin d'injecter certains paramètres au constructeur
 */
class StatistiquesFactory
{
    public static function get(\Closure $classFactory, ConsolidatedStatisticFilter $formFilter)
    {
        return $classFactory($formFilter);
    }
}