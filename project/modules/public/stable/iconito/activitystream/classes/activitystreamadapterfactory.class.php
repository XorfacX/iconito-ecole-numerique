<?php

use ActivityStream\Client\Adapter\AdapterInterface;

class ActivityStreamAdapterFactory
{
    /**
     * Création de l'adapter pour le push d'activités dans l'activityStream
     *
     * @return mixed
     * @throws Exception
     */
    public static function create()
    {
        $adapterClassname = CopixConfig::get('activitystream|activity_stream_adapter_classname');


        if (!is_subclass_of($adapterClassname, 'ActivityStream\Client\Adapter\AdapterAbstract')) {
            throw new Exception('invalide');
        }

        $config = array(
            'host'               => CopixConfig::get('activitystream|activity_stream_adapter_host'),
            'port'               => CopixConfig::get('activitystream|activity_stream_adapter_port'),
            'connection_timeout' => CopixConfig::get('activitystream|activity_stream_adapter_connection_timeout'),
            'auth_password'      => CopixConfig::get('activitystream|activity_stream_adapter_auth_password')
        );

        // On supprime les clés dont les valeurs sont nulles
        $config = array_filter($config, function($value){
            return null !== $value && '' !== $value;
        });

        return new $adapterClassname($config);
    }
}
