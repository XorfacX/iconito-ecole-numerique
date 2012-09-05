<?php

/**
 * Fonctions relatives au kernel et au module Liste
 *
 * @package Iconito
 * @subpackage	Liste
 */
class KernelListe
{
    /* Cr�e une liste de diffusion
         Renvoie son ID ou NULL si erreur
         * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
    */
    public function create ($infos=array())
    {
        $dao = _dao("liste|liste_listes");
        $new = _record("liste|liste_listes");
        $new->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
        $new->date_creation = date("Y-m-d H:i:s");
        $dao->insert ($new);
        return ($new->id!==NULL) ? $new->id : NULL;
    }

    /**
     * Suppression d�finitive d'une liste
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/09
     * @param integer $id Id de la liste
     * @return boolean true si la suppression s'est bien pass�e, false sinon
     */
    public function delete ($id)
    {
        $daoListes = _dao("liste|liste_listes");
        $rListe = $daoListes->get($id);
        $res = false;
        if ($rListe) {
            $criteres = _daoSp ()->addCondition ('liste', '=', $id);
            _dao ('module_liste_messages')->deleteBy($criteres);
            $daoListes->delete ($id);
            $res = true;
        }
    Kernel::unregisterModule("MOD_LISTE", $id);
        return $res;
    }

    /*
        Renvoie diff�rentes infos chiffr�es d'une liste
    */
    public function getStats ($id_liste)
    {
        $res = array();
        $daoListe = _dao("liste|liste_listes");
        $infos = $daoListe->getNbMessagesInListe($id_liste);
        $res['nbMessages'] = array ('name'=>CopixI18N::get ('liste|liste.stats.nbMessages', array($infos[0]->nb)));
        return $res;

    }


    /**
     * Statistiques du module liste
     *
     * Renvoie des �l�ments chiffr�s relatifs aux listes de diffusion et d�di�s � un utilisateur syst�me : nombre de listes, messages envoy�s...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/20
     * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbMalles"] ["nbFolders"] ["nbFiles"] ["size"]
     */
    public function getStatsRoot ()
    {
        $res = array();
        $sql = 'SELECT COUNT(id) AS nb FROM module_liste_listes';
        $a = _doQuery($sql);
        $res['nbListes'] = array ('name'=>CopixI18N::get ('liste|liste.stats.nbListes', array($a[0]->nb)));
        $sql = 'SELECT COUNT(id) AS nb FROM module_liste_messages';
        $a = _doQuery($sql);
        $res['nbMessages'] = array ('name'=>CopixI18N::get ('liste|liste.stats.nbMessages', array($a[0]->nb)));
        return $res;
    }



}

