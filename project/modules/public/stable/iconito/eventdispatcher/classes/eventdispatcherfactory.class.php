<?php

class EventDispatcherFactory
{
    // Les instance d'EventDispatcher construites
    static $instances = array();

    /**
     * Retourne une instance d'EventDispatcher (création au besoin)
     *
     * @return Symfony\Component\EventDispatcher\EventDispatcher
     */
    public static function getInstance($instanceId = 'default')
    {
        if (!isset(self::$instances[$instanceId])) {
            self::$instances[$instanceId] = new Symfony\Component\EventDispatcher\EventDispatcher();
        }

        return self::$instances[$instanceId];
    }
}
