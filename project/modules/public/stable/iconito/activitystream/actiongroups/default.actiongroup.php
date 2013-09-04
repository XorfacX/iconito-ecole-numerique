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
//    var_dump(_currentUser());

    _classInclude('activitystream|ActivityStreamService');
    ActivityStreamService::getInstance()->onConnection(_currentUser());

    die;

    _classInclude('eventDispatcher|EventDispatcherFactory');
    $dispatcher = EventDispatcherFactory::getInstance();

    _classInclude('activityStream|test');
    $object1 = new Test('Actor', 'Resource', 1, 'http://www.isics.fr');
    $object2 = new Test('Object', 'Resource', 2, 'http://www.google.fr');
    $object3 = new Test('Target', 'Resource', 3, 'http://www.test.fr');
    $scope1 = new Test('Scope1', 'Resource', 52, 'http://www.scope1.fr');
    $scope2 = new Test('Scope2', 'Resource', 102, 'http://www.scope2.fr');

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
