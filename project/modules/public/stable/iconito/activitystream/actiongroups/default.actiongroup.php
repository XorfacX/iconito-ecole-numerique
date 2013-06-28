<?php

/**
 * Actiongroup du module ActivityStream
 *
 * @package Iconito
 * @subpackage ActivityStream
 */
class ActionGroupDefault extends CopixActionGroup
{
    public function processIndex()
    {
        _classInclude('eventDispatcher|EventDispatcherFactory');
        $dispatcher = EventDispatcherFactory::getInstance();

        _classInclude('activityStream|test');
        $object1 = new Test(1, 'Actor', 'http://www.isics.fr');
        $object2 = new Test(2, 'Object', 'http://www.google.fr');
        $object3 = new Test(3, 'Target', 'http://www.test.fr');
        $scope1 = new Test(52, 'Scope1', 'http://www.scope1.fr');
        $scope2 = new Test(102, 'Scope2', 'http://www.scope2.fr');

        _classInclude('activityStream|ActivityEvent');
        _classInclude('activityStream|StatisticEvent');
        $dispatcher->dispatch('activity_stream.push_activity', new ActivityEvent(
            $object1,
            'like',
            $object2,
            $object3,
            array(
                $scope1,
                $scope2
            )
        ));
        $dispatcher->dispatch('activity_stream.push_statistic', new StatisticEvent(
            12,
            'unit',
            $object1,
            'like',
            $object2,
            $object3,
            array(
                $scope1,
                $scope2
            )
        ));
        die;
    }
}
