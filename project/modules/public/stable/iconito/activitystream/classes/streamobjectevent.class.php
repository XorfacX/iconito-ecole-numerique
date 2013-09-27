<?php

use Symfony\Component\EventDispatcher\Event;

class StreamObjectEvent extends Event
{
    /**
     * Retourne l'identifiant de l'application
     *
     * @return string
     */
    public function getApplicationId()
    {
        return CopixConfig::get('activitystream|activity_stream_application_id');
    }
}
