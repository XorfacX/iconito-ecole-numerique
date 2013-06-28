<?php

/**
 * Plugin de l'activityStream
 */
class PluginActivityStream extends CopixPlugin
{
    /**
     * Bind des Event dans l'EventDispatcher avant chaque action
     *
     * @param CopixAction $action
     */
    public function beforeProcess(& $action)
    {
        _classInclude('activitystream|ActivityStreamListener');
        _classInclude('eventdispatcher|EventDispatcherFactory');

        $activityStreamListener = new ActivityStreamListener();

        $dispatcher = EventDispatcherFactory::getInstance();

        $dispatcher->addListener('activity_stream.push_activity', array($activityStreamListener, 'pushActivity'));
        $dispatcher->addListener('activity_stream.push_statistic', array($activityStreamListener, 'pushStatistic'));
    }
}
