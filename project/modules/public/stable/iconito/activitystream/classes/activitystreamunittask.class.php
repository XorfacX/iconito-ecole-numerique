<?php

use ActivityStream\Client\Model\Resource;

_classInclude('activityStream|ActivityStreamService');
_classInclude('activityStream|StatisticEvent');

/**
 * Class ActivityStreamUnitTask
 * @author Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class ActivityStreamUnitTask
{
  /**
   * @var ActivityStreamService
   */
  protected $activityStreamService;

  public function __construct()
  {
    $this->activityStreamService = new ActivityStreamService();
  }

  /**
   * Envoie toutes les statistiques
   */
  public function processStat()
  {
//    $this->sendAgendaStat();
//    $this->sendClasseurStat();
//    $this->sendDossierStat();
    $this->sendBlogOuvertStat();
//    $this->sendBlogPubliqueStat();
//    $this->sendBlogStat();
//    $this->sendMemoStat();
//    $this->sendTravailAFaireStat();
//    $this->sendTravailEnClasseStat();
//    $this->sendUserStat();
  }

  protected function sendAgendaStat()
  {
    $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM kernel_mod_enabled kme WHERE kme.module_type = 'MOD_AGENDA'
      GROUP BY target_node_type, target_node_id
SQL;

    $results = _doQuery ($sql);
    $object = new Resource('Agenda', 'DAORecordAgenda');

    foreach ($results as $result) {
      $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
      $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
    }
  }

  /**
   * Envoie les statistiques sur les connexions des usagers
   */
  protected function sendUserStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM dbuser WHERE enabled_dbuser=1");
    $object = new Resource('Comptes actifs', 'ActivityStreamPerson');

    $this->activityStreamService->logStatistic((int)$count[0]->count, 'unit', null, 'count', $object, null, array());
  }

  /**
   * Envoie les statistiques sur le nombre de classeurs
   */
  protected function sendClasseurStat()
  {
    $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM kernel_mod_enabled kme WHERE kme.module_type = 'MOD_CLASSEUR'
      GROUP BY target_node_type, target_node_id
SQL;

    $results = _doQuery ($sql);
    $object = new Resource('Classeur', 'DAORecordClasseur');

    foreach ($results as $result) {
      $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
      $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
    }
  }

  /**
   * Envoie les statistiques sur le nombre de dossiers
   */
  protected function sendDossierStat()
  {
    $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM module_classeur_dossier mcd
      INNER JOIN kernel_mod_enabled kme ON kme.module_type = 'MOD_CLASSEUR' AND kme.module_id = mcd.module_classeur_id
      GROUP BY target_node_type, target_node_id
SQL;

    $results = _doQuery ($sql);

    $object = new Resource('Dossier de classeur', 'DAORecordClasseurDossier');

    foreach ($results as $result) {
      $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
      $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
    }
  }

  /**
   * Envoie les statistiques sur le nombre de blogs ouverts
   */
  protected function sendBlogOuvertStat()
  {
    $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM kernel_mod_enabled kme WHERE kme.module_type = 'MOD_BLOG'
      GROUP BY target_node_type, target_node_id
SQL;

    $object = new Resource('Dossier de classeur', 'DAORecordClasseurDossier');
    $results = _doQuery ($sql);

    foreach ($results as $result) {
      $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
      $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
    }
  }

  /**
   * Envoie les statistiques sur le nombre de blogs publics et non publics
   */
  protected function sendBlogPubliqueStat()
  {
    $results = _doQuery ("SELECT is_public, COUNT(*) AS count FROM module_blog GROUP BY is_public");

    foreach ($results as $result) {
      $object = new Resource('Blog', 'DAORecordBlog', null, null, array('is_public' => $result->is_public));
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, null, array());
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
      $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, null, array());
    }
  }

  /**
   * Envoie le nombre de travaux marqués comme étant à faire
   */
  protected function sendTravailAFaireStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_travail WHERE a_faire = 1");

    $object = new Resource('Travail', 'DAORecordCahierDeTextesTravail', null, null, array('a_faire' => 1));
    $this->activityStreamService->logStatistic((int)$count[0]->count, 'unit', null, 'count', $object, null, array());
  }

  /**
   * Envoie le nombre de travaux en classe
   */
  protected function sendTravailEnClasseStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_travail WHERE a_faire = 0");

    $object = new Resource('Travail', 'DAORecordCahierDeTextesTravail', null, null, array('a_faire' => 0));
    $this->activityStreamService->logStatistic((int)$count[0]->count, 'unit', null, 'count', $object, null, array());
  }

  /**
   * Envoie le nombre de mémos
   */
  protected function sendMemoStat()
  {
    $count = _doQuery ("SELECT COUNT(*) AS count FROM module_cahierdetextes_memo");

    $object = new Resource('Mémo', 'DAORecordCahierDeTextesMemo', null, null);
    $this->activityStreamService->logStatistic((int)$count[0]->count, 'unit', null, 'count', $object, null, array());
  }
}
