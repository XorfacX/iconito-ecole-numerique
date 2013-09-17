<?php

use ActivityStream\Client\Model\Resource;

_classInclude('eventDispatcher|EventDispatcherFactory');
_classInclude('activityStream|StatisticEvent');

/**
 * Class ActivityStreamUnitTask
 * @author Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class ActivityStreamUnitTask
{
  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcher
   */
  protected $dispatcher;

  /**
   * Initialize the unit task, and the event dispatcher
   */
  public function __construct()
  {
    $this->dispatcher = EventDispatcherFactory::getInstance();
  }

  /**
   * Envoie toutes les statistiques
   */
  public function processStat()
  {
    $this->sendBlogOuvertStat();
    $this->sendBlogPubliqueStat();
    $this->sendBlogStat();
    $this->sendClasseurStat();
    $this->sendDossierStat();
    $this->sendMemoStat();
    $this->sendTravailAFaireStat();
    $this->sendTravailEnClasseStat();
    $this->sendUserStat();
  }

  /**
   * Envoie une statistique
   * 
   * @param int $count
   * @param $actor
   * @param $verb
   * @param $object
   * @param $target
   * @param array $targetScope
   */
  protected function sendStatistic($count = 0, $actor, $verb, $object, $target, array $targetScope = array()) {
    $this->dispatcher->dispatch(
      'activity_stream.push_statistic',
      new StatisticEvent((int)$count, 'unit', $actor, $verb, $object, $target, $targetScope)
    );
  }

  /**
   * Envoie les statistiques sur les connexions des usagers
   */
  protected function sendUserStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM dbuser WHERE enabled_dbuser=1");
    $object = new Resource('Comptes actifs', 'ActivityStreamPerson');

    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie les statistiques sur le nombre de classeurs
   */
  protected function sendClasseurStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_classeur");
    $object = new Resource('Classeurs', 'DAORecordClasseur');

    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie les statistiques sur le nombre de dossiers
   */
  protected function sendDossierStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_classeur_dossier");
    $object = new Resource('Dossiers dans les classeurs', 'DAORecordClasseurDossier');

    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie les statistiques sur le nombre de blogs ouverts
   */
  protected function sendBlogOuvertStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_blog");
    $object = new Resource('Blogs ouverts', 'DAORecordBlog');
    
    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie les statistiques sur le nombre de blogs publics et non publics
   */
  protected function sendBlogPubliqueStat()
  {
    $results = _doQuery ("SELECT is_public, COUNT(*) AS count FROM module_blog GROUP BY is_public");

    foreach ($results as $result) {
      $object = new Resource('Blog', 'DAORecordBlog', null, null, array('is_public' => $result->is_public));
      $this->sendStatistic($result->count, null, 'count', $object, null, array());
    }
  }

  /**
   * Envoie le nombre de blogs pour chaque type de visibilité
   */
  protected function sendBlogStat()
  {
    $results = _doQuery ("SELECT privacy, COUNT(*) AS count FROM module_blog GROUP BY privacy");

    foreach ($results as $result) {
      $object = new Resource('Blog', 'DAORecordBlog', null, null, array('privacy' => $result->privacy));
      $this->sendStatistic($result->count, null, 'count', $object, null, array());
    }
  }

  /**
   * Envoie le nombre de travaux marqués comme étant à faire
   */
  protected function sendTravailAFaireStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_travail WHERE a_faire = 1");

    $object = new Resource('Travail', 'DAORecordCahierDeTextesTravail', null, null, array('a_faire' => 1));
    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie le nombre de travaux en classe
   */
  protected function sendTravailEnClasseStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_travail WHERE a_faire = 0");

    $object = new Resource('Travail', 'DAORecordCahierDeTextesTravail', null, null, array('a_faire' => 0));
    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }

  /**
   * Envoie le nombre de mémos
   */
  protected function sendMemoStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_memo");

    $object = new Resource('Mémo', 'DAORecordCahierDeTextesMemo', null, null);
    $this->sendStatistic($count[0]->count, null, 'count', $object, null, array());
  }
}
