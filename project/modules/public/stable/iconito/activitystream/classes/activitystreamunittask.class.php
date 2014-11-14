<?php

_classInclude('activitystream|ecolenumeriqueactivitystreamresource');
_classInclude('activityStream|ActivityStreamService');
_classInclude('activityStream|StatisticEvent');

use ActivityStream\Client\Model\Resource;

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
        $this->sendAgendaStat();

        $this->sendClasseurStat();
        $this->sendDossierStat();
        $this->sendFichierStat();

        $this->sendBlogOuvertStat();
        $this->sendBlogPubliqueStat();
        $this->sendBlogRubriqueStat();
        $this->sendBlogPageStat();

        $this->sendBlogStat();
        $this->sendUserStat();

        $this->sendGroupeDeTravailStat();
        $this->sendListeStat();
        $this->sendMessageStat();
    }

    protected function sendAgendaStat()
    {
        $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM kernel_mod_enabled kme WHERE kme.module_type = 'MOD_AGENDA'
      GROUP BY target_node_type, target_node_id
SQL;

        $results = _doQuery($sql);
        $object = new EcoleNumeriqueActivityStreamResource('Agenda', 'DAORecordAgenda');

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
        $sql = <<<SQL
            SELECT COUNT(*) AS count, klbu.bu_type AS target_node_type
            FROM kernel_link_bu2user klbu
            INNER JOIN dbuser dbu ON klbu.user_id = dbu.id_dbuser
            WHERE dbu.enabled_dbuser = 1
            GROUP BY target_node_type
SQL;

        $results = _doQuery($sql);

        // On envoie la statistique par type de compte
        foreach ($results as $result) {
            $object = new Resource($result->target_node_type, $result->target_node_type);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object);
        }

        $sql = <<<SQL
            SELECT COUNT(*) AS count
            FROM kernel_link_bu2user klbu INNER JOIN dbuser dbu ON klbu.user_id = dbu.id_dbuser
            WHERE dbu.enabled_dbuser = 1
SQL;

        $result = reset(_doQuery($sql));

        $object = new Resource('ALL_USERS', 'ALL_USERS');
        $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object);
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

        $results = _doQuery($sql);
        $object = new EcoleNumeriqueActivityStreamResource('Classeur', 'DAORecordClasseur');

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

        $results = _doQuery($sql);

        $object = new EcoleNumeriqueActivityStreamResource('Dossier de classeur', 'DAORecordClasseurDossier');

        foreach ($results as $result) {
            $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
            $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    /**
     * Envoie les statistiques sur le nombre de fichiers
     */
    protected function sendFichierStat()
    {
        // Fichiers par classeurs
        $sql = <<<SQL
          SELECT COUNT(*) AS count, mcf.module_classeur_id AS target_node_id, SUM(taille) AS taille_totale
          FROM module_classeur_fichier mcf
          GROUP BY mcf.module_classeur_id
SQL;

        $results = _doQuery($sql);


        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Fichier', 'DAORecordClasseurFichier', null, null, array('taille' => $result->taille_totale, 'is_casier' => '0'));
            $target = Kernel::getNode('classeur|classeur', $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, array());
        }

        function flattenArray(array $array)
        {
            $return = array();

            foreach ($array as $key => $value) {
                $return[$key] = reset($value);
            }

            return $return;
        }

        $sql = <<<SQL
          SELECT kme.node_id as classe_id, mcd.id AS dossier_id
          FROM module_classeur_dossier mcd
          INNER JOIN kernel_mod_enabled kme ON kme.module_id = mcd.module_classeur_id AND kme.module_type = 'MOD_CLASSEUR' AND kme.node_type = 'BU_CLASSE'
          WHERE mcd.casier = 1
SQL;

        $casierIdByClasseIds = _doQuery($sql);

        function getChildren($dossierId)
        {
            return flattenArray(_doQuery('SELECT id FROM module_classeur_dossier WHERE parent_id = ?', array($dossierId)));
        }

        function getSubFolders($dossierId)
        {
            $children = array($dossierId);
            foreach (getChildren($dossierId) as $child) {
                $children = array_merge($children, getSubFolders($child));
            }

            return $children;
        }

        foreach ($casierIdByClasseIds as $row) {
            $subFolders = getSubFolders($row->dossier_id);

            // Fichiers par casiers
            $sql = <<<SQL
              SELECT COUNT(*) AS count, SUM(taille) AS taille_totale
              FROM module_classeur_fichier mcf
              WHERE mcf.module_classeur_dossier_id IN (?)
              GROUP BY mcf.module_classeur_id
SQL;
            $results = _doQuery($sql, array(implode(', ', $subFolders)));

            if (count($results)) {
                $result = reset($results);
                $object = new EcoleNumeriqueActivityStreamResource('Fichier', 'DAORecordClasseurFichier', null, null, array('taille' => $result->taille_totale, 'is_casier' => '1'));
                $target = Kernel::getNode('BU_CLASSE', $row->classe_id);
                $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, array());
            }
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

        $object = new EcoleNumeriqueActivityStreamResource('Dossier de classeur', 'DAORecordClasseurDossier');
        $results = _doQuery($sql);

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
        $sql = <<<SQL
          SELECT COUNT(*) AS count, mb.is_public, kme.node_type AS target_node_type, kme.node_id AS target_node_id
          FROM module_blog mb
          INNER JOIN kernel_mod_enabled kme ON kme.module_type = 'MOD_BLOG' AND kme.module_id = mb.id_blog
          GROUP BY target_node_type, target_node_id, is_public
SQL;

        $results = _doQuery($sql);

        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Blog', 'DAORecordBlog', null, null, array('is_public' => $result->is_public));

            $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
            $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    /**
     * Envoie les statistiques sur le nombre de blogs publics et non publics
     */
    protected function sendBlogRubriqueStat()
    {
        $results = _doQuery('SELECT COUNT(*) AS count, id_blog FROM module_blog_articlecategory mba GROUP BY id_blog');


        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Blog', 'DAORecordBlogarticlecategory', null, null, array());
            $target = Kernel::getNode('blog|blog', $result->id_blog);
            $context = $this->activityStreamService->getContextResources('MOD_BLOG', $result->id_blog);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    /**
     * Envoie les statistiques sur le nombre de pages de blogs
     */
    protected function sendBlogPageStat()
    {
        $results = _doQuery('SELECT COUNT(*) AS count, id_blog FROM module_blog_page mbp GROUP BY id_blog');


        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Blog', 'DAORecordblogpage', null, null, array());
            $target = Kernel::getNode('blog|blog', $result->id_blog);
            $context = $this->activityStreamService->getContextResources('MOD_BLOG', $result->id_blog);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    /**
     * Envoie le nombre de blogs pour chaque type de visibilité
     */
    protected function sendBlogStat()
    {
        $sql = <<<SQL
      SELECT COUNT(*) AS count, mb.privacy, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM module_blog mb
      INNER JOIN kernel_mod_enabled kme ON kme.module_type = 'MOD_BLOG' AND kme.module_id = mb.id_blog
      GROUP BY target_node_type, target_node_id, privacy
SQL;

        $results = _doQuery($sql);

        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Blog', 'DAORecordBlog', null, null, array('privacy' => $result->privacy));

            $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
            $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    public function sendGroupeDeTravailStat()
    {
        $sql = <<<SQL
          SELECT COUNT(*) AS count, module_type, klgn.node_type, klgn.node_id
          FROM module_groupe_groupe mgg
          INNER JOIN kernel_mod_enabled kme ON kme.node_type = 'CLUB' AND kme.node_id = mgg.id
          INNER JOIN kernel_link_groupe2node klgn ON klgn.groupe_id = mgg.id
          GROUP BY kme.module_type, klgn.node_type, klgn.node_id
SQL;

        $results = _doQuery($sql);
        foreach ($results as $result) {
            if ($result->node_type == 'ROOT') {
                continue;
            }

            $object = new EcoleNumeriqueActivityStreamResource('Groupe de travail', 'DAORecordGroupe', null, null, array('module' => $result->module_type));
            $context = $this->activityStreamService->getContextResources($result->node_type, $result->node_id);
            $target = Kernel::getNode($result->node_type, $result->node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }

        $sql = <<<SQL
          SELECT COUNT(*) AS count, klgn.node_type, klgn.node_id
          FROM module_groupe_groupe mgg
          INNER JOIN kernel_link_groupe2node klgn ON klgn.groupe_id = mgg.id
          GROUP BY klgn.node_type, klgn.node_id
SQL;
        $results = _doQuery($sql);

        foreach ($results as $result) {
            if ($result->node_type == 'ROOT') {
                continue;
            }

            $object = new EcoleNumeriqueActivityStreamResource('Groupe de travail', 'DAORecordGroupe', null, null, array('module' => 'TOTAL'));
            $context = $this->activityStreamService->getContextResources($result->node_type, $result->node_id);
            $target = Kernel::getNode($result->node_type, $result->node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }

    }


    /**
     * Envoie le nombre de discussions pour un groupe de travail
     */
    protected function sendListeStat()
    {
        $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM module_liste_listes mll
      INNER JOIN kernel_mod_enabled kme ON kme.module_type = 'MOD_LISTE' AND kme.module_id = mll.id
      GROUP BY target_node_type, target_node_id
SQL;

        $results = _doQuery($sql);

        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Discussion', 'DAORecordListe_Listes');

            $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
            $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }

    /**
     * Envoie le nombre de discussions pour un groupe de travail
     */
    protected function sendMessageStat()
    {
        $sql = <<<SQL
      SELECT COUNT(*) AS count, kme.node_type AS target_node_type, kme.node_id AS target_node_id
      FROM module_liste_messages mlm
      INNER JOIN kernel_mod_enabled kme ON kme.module_type = 'MOD_LISTE' AND kme.module_id = mlm.liste
      GROUP BY target_node_type, target_node_id
SQL;

        $results = _doQuery($sql);

        foreach ($results as $result) {
            $object = new EcoleNumeriqueActivityStreamResource('Discussion', 'DAORecordliste_messages');

            $context = $this->activityStreamService->getContextResources($result->target_node_type, $result->target_node_id);
            $target = Kernel::getNode($result->target_node_type, $result->target_node_id);
            $this->activityStreamService->logStatistic((int)$result->count, 'unit', null, 'count', $object, $target, $context);
        }
    }
}
