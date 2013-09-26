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
    ini_set('display_errors', true);
    _classInclude('activitystream|ActivityStreamService');
//    ActivityStreamService::getInstance()->onConnection(_currentUser());
    $this->activityStreamService = new ActivityStreamService();

    _classInclude('activityStream|test');
    $user1 = new Test('Jean-Michel', 'User', 1, 'http://www.isics.fr', array('nom' => 'Dupont'));
    $user2 = new Test('Martine', 'User', 2, 'http://www.isics.fr', array('nom' => 'Durand'));
    $classe1 = new Test('CM1/CM2', 'Classe', 3, 'http://www.isics.fr', array('nombre_eleves' => 21));
    $classe2 = new Test('CP/CE1', 'Classe', 1, 'CP/CE1', array('nombre_eleves' => 25));
    $ecole = new Test('Ecole du Bois Fleuri', 'Ecole', 654, 'http://www.isics.fr', array('adresse' => 'rue des lilas'));
    $ville = new Test('Limoges', 'Ville', 321, 'http://www.isics.fr', array('code_postal' => 87000));
    $classeur1 = new Test('Classeur 1', 'Classeur', 789, 'http://www.isics.fr');
    $classeur2 = new Test('Classeur 2', 'Classeur', 456, 'http://www.isics.fr');
    $classeur3 = new Test('Classeur 3', 'Classeur', 123, 'http://www.isics.fr');

    $user1 = $user1->toResource();
    $user2 = $user2->toResource();
    $classe1 = $classe1->toResource();
    $classe2 = $classe2->toResource();
    $ecole = $ecole->toResource();
    $ville = $ville->toResource();
    $classeur1 = $classeur1->toResource();
    $classeur2 = $classeur2->toResource();
    $classeur3 = $classeur3->toResource();

    $context1 = array($classe1, $ecole, $ville);
    $context2 = array($classe2, $ecole, $ville);

    $this->activityStreamService->logActivity('create', $user1, $classeur1, $classe1, $context1);
    $this->activityStreamService->logActivity('create', $user1, $classeur2, $classe2, $context2);
    $this->activityStreamService->logActivity('create', $user2, $classeur3, $classe1, $context1);
    $this->activityStreamService->logActivity('like', $user2, $classeur3, $classe1, $context1);
    $this->activityStreamService->logActivity('like', $user2, $classeur1, $classe1, $context1);
    $this->activityStreamService->logActivity('like', $user1, $classeur2, $classe2, $context2);

    die('plop');
//
//    $this->activityStreamService->logActivity(
//      $object1,
//      'like',
//      $object2,
//      $object3,
//      array(
//        $scope1,
//        $scope2
//      )
//    ));
//    $this->activityStreamService->logActivity('activity_stream.push_statistic', new StatisticEvent(
//      12,
//      'unit',
//      $object1,
//      'like',
//      $object2,
//      $object3,
//      array(
//        $scope1,
//        $scope2
//      )
//    ));
//    die;
//  }
}
}