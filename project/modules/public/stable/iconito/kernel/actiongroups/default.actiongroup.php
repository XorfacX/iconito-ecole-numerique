<?php
/**
 * Kernel - ActionGroup
 *
 * Fonctions du coeur d'Iconito : Gestion des utilisateurs, des liens avec les entit�s, de l'acc�s � la base �l�ve, des droits.
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: kernel.actiongroup.php,v 1.50 2009-07-10 09:13:20 cbeyer Exp $
 * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
 */

_classInclude ('welcome|welcome');


class ActionGroupDefault extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }


    public function processDefault ()
    {
        // return _arRedirect (_url ('|getHome'));
        return _arRedirect (_url ('kernel|dashboard|'));
    }



    /**
     * getNodes
     *
     * Affiche la liste des entit�s reli�es � l'utilisateur connect� (classe, �cole, ville, etc.)
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processGetNodes ()
    {
        // Patch EN2010
        return _arRedirect (_url ('kernel|dashboard|'));
    }

    /**
     * doSelectHome
     *
     * M�morisation en session du noeud (ville, �cole, classe) actuel de travail de l'utilisateur connect�.
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processDoSelectHome()
    {
    // Patch EN2010
        return _arRedirect (_url ('kernel|dashboard|'));
    }

    /**
     * getHome
     *
     * Affiche les information de la zone de travail active (modules disponibles, etc.)
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processGetHome()
    {
        return _arRedirect (_url ('kernel|dashboard|'));
    }






    /**
     * getTree
     *
     * DEBUG: Affiche l'arbre d'information de l'utilisateur connect�.
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processGetTree ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "getTree");

        $tpl->assign ('MAIN', '<pre>'.print_r(Kernel::getTree(),true).'</pre>' );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * debug
     *
     * DEBUG: Fonction de tests, librement modifiable...
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processDebug ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Debug");
        $return_str = "Debug...";

        /*
        $tmp = Kernel::getModParentInfo( "MOD_RESSOURCE", 2 );
        Kernel::MyDebug( $tmp );
        */

        /*
        $pref_class = & CopixClassesFactory::Create ('prefs|prefs');
        echo $pref_class->get( 'minimail', 'alerte_mail_email' );
        */

        /** Test getUserInfo
        $result = Kernel::getUserInfo( "LOGIN", "blob" );
        $result = Kernel::getUserInfo( "ID", 2 );
        $result = Kernel::getUserInfo( "USER_EXT", 1 );
        die( "<pre>".print_r($result,true)."</pre>" );
        */

        // Kernel::MyDebug( Kernel::getUserInfo( "USER_EXT",1 ) );

        // Kernel::MyDebug( Kernel::getNodeInfo( "BU_CLASSE",1 ) );

        // $infos = Kernel::getMyHomes();
        // $return_str=print_r($infos,true);

        $tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * walk
     *
     * DEBUG: Affichage des parents et enfants d'un noeud, pour valider le
     * fonctionnement des fonctions getNodeParents et getNodeChilds.
     * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @see getNodeParents( $type, $id )
     * @see getNodeChilds( $type, $id )
     */
    public function processWalk ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Walk");

        $return_left   ="";
        $return_center ="";
        $return_right  ="";

        if( _request("type")) {
            $type=_request("type");      $id=_request("id");
        } else {
            if( (_currentUser()->getExtra('type')) && (_currentUser()->getExtra('id')) ) {
                $type=_currentUser()->getExtra('type');      $id=_currentUser()->getExtra('id');
            } else {
                $type="USER_ELE"; $id=3777;
            }
        }

        $result=Kernel::getNodeParents( $type, $id );
        foreach ($result AS $key=>$value) {
            $return_left .= '<a href="'.CopixUrl::get ('kernel||walk', array("type"=>$value["type"], "id"=>$value["id"])).'">';
            $return_left .= $value["type"]."/".$value["id"];
            if( isset($value["droit"]) && $value["droit"] ) $return_left .= ' ('.$value["droit"].')';
            $return_left .= '</a>';
            // $tmp = Kernel::getNodeChilds( $value["type"], $value["id"] );
            // if( sizeof($tmp) ) $return_left .= " (".sizeof($tmp).")";
            $return_left .= '<br />';
        }

        $result=Kernel::getNodeChilds( $type, $id );
        foreach ($result AS $key=>$value) {
            // $tmp = Kernel::getNodeChilds( $value["type"], $value["id"] );
            // if( sizeof($tmp) ) $return_right .= "(".sizeof($tmp).") ";
            $return_right .= '<a href="'.CopixUrl::get ('kernel||walk', array("type"=>$value["type"], "id"=>$value["id"])).'">';
            $return_right .= $value["type"]."/".$value["id"];
            if( isset($value["droit"]) && $value["droit"] ) $return_right .= ' ('.$value["droit"].')';
            $return_right .= '</a>';
            $return_right .= '<br />';
        }

        $return_center .= $type."/".$id;

        if(ereg("^USER_", $type)) {
            $user_infos = Kernel::getUserInfo( $type, $id );
            if( isset( $user_infos["login"] ) ) $return_center .= "<br />Login: ".$user_infos["login"];
            if( isset( $user_infos["nom"] ) && isset( $user_infos["prenom"] ) ) {
                $return_center .= "<br />(";
                $return_center .= $user_infos["prenom"]." ".$user_infos["nom"];
                $return_center .= ")";
            }
        } else {
            $node_infos = Kernel::getNodeInfo( $type, $id, false );
            if( isset( $node_infos["nom"] ) ) $return_center .= "<br />".$node_infos["nom"];
            if( isset( $node_infos["desc"] ) ) {
                $return_center .= "<br />(";
                if( strlen( $node_infos["desc"] ) > 45 ) $return_center .= substr($node_infos["desc"], 0, 40)."...";
                else $return_center .= $node_infos["desc"];
                $return_center .= ")";
            }
        }


        $return_str = '<center><h3>'.$return_center.'</h3></center>';
        $return_str.= '<table width="100%"><tr><td valign="top" align="left"><strong>Parents</strong></td><td valign="top" align="right"><strong>Childs</strong></td></tr>';
        $return_str.= '<tr><td valign="top" align="left">'.$return_left.'</td><td valign="top" align="right">'.$return_right.'</td></tr></table>';

        $tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * getLink
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @todo A faire...
     */
    public function processGetLink ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel|kernel.message.profil'));

        $linkedin = Kernel::getMyParents();
        foreach( $linkedin["direct"] as $key => $val ) {
            $linkeddata[] = $key." (".sizeof($val).")";
        }
        $linkedstr = "[ ".implode(" | ", $linkeddata)." ]";

        $tpl->assign ('MAIN', "<pre>".$linkedstr."</pre>" );
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * doLink
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @todo A faire...
     */
    public function processDoLink ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Walk");

        $tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
     * Choix du theme
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/09/21
     * @param string $theme Nom du theme
     */
    public function processSelectTheme ()
    {
        $pTheme = CopixRequest::getAlpha('theme');
        Kernel::setTheme ($pTheme);
        $from = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : CopixUrl::get ('kernel||getHome');
        return new CopixActionReturn (COPIX_AR_REDIRECT, $from);
    }

    /**
     * Choix de la langue
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/11/03
     * @param string $lang Langue a mettre en place
     */

    public function processSetLang ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        $pLang = CopixRequest::getAlpha('lang');

        //$getLang = CopixI18N::getLang (); echo "getLang=".$getLang;

        if ($pLang) {
            CopixI18N::setLang ($pLang);
        }
        $from = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : CopixUrl::get ('kernel||getHome');
        return new CopixActionReturn (COPIX_AR_REDIRECT, $from);

    }


    /**
     * Cle i18n
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2011/06/17
     * @param string $key Cle demandee
     */
    public function processI18n ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        $iKey = CopixRequest::get('key');
        if (CopixI18N::exists($iKey))
            echo CopixI18N::get($iKey);
        return _arNone ();

    }





    public function processTestApi ()
    {
        $api_class = & CopixClassesFactory::Create ('kernel|kernel_api');

		/*
		$ville = new CopixPPO();
		$ville->nom = "Ville de test";
		$ville->nom_canonique = "ville_de_test";
		$id_ville = $api_class->creerVille( 1, $ville );
		die("ok : $id_ville");
		*/
		
		/*
		$api_class->supprimerVille(4);
		*/
		
		/*
		$ville = new CopixPPO();
		$ville->nom = "Ville de test 2";
		$ville->nom_canonique = "ville_de_test";
		$api_class->modifierVille( 3, $ville );
		*/
		
		/*
		$ecole = new CopixPPO();
		$ecole->nom = "Ecole de test";
		$ecole->type = "Primaire";
		$ecole->rne = "1234567";
		$ecole->adresse->numRue = '123';
		$ecole->adresse->numSeq = 'bis';
		$ecole->adresse->adresse1 = 'avenue de l\'Europe';
		$ecole->adresse->adresse2 = 'adresse 2';
		$ecole->adresse->codePostal = '67300';
		$ecole->adresse->commune = 'Schiltigheim';
		$api_class->creerEcole( 3, $ecole );
		*/
		
		/*
		$ecole = new CopixPPO();
		$ecole->nom = "Ecole de test (modif)";
		$ecole->type = "Primaire (modif)";
		$ecole->rne = "7654321";
		$ecole->adresse->numRue = '456';
		$ecole->adresse->numSeq = 'ter';
		$ecole->adresse->adresse1 = 'avenue de l\'Europe (modif)';
		$ecole->adresse->adresse2 = 'adresse 2 (modif)';
		$ecole->adresse->codePostal = '67301';
		$ecole->adresse->commune = 'Schiltigheim (modif)';
		$api_class->modifierEcole( 9, $ecole );
		*/
		
		/*
		$api_class->supprimerEcole( 8 );
		*/
		
		/*
		$classe = new CopixPPO();
		$classe->nom = "Ma classe";
		$classe->anneeScolaire = "2012";
		$classe->niveaux = array();
		$niveau = new CopixPPO();
		$niveau->niveau = 3;
		$niveau->type = 11;
		$classe->niveaux[] = clone $niveau;
		$niveau->niveau = 6;
		$niveau->type = 12;
		$classe->niveaux[] = clone $niveau;
		$api_class->creerClasse( 9, $classe );
		*/
		
		$classe = new CopixPPO();
		$classe->nom = "Ma classe (modif)";
		$classe->anneeScolaire = "2011";
		$classe->niveaux = array();
		$niveau = new CopixPPO();
		$niveau->niveau = 5;
		$niveau->type = 15;
		$classe->niveaux[] = clone $niveau;
		$niveau->niveau = 4;
		$niveau->type = 14;
		$classe->niveaux[] = clone $niveau;
		$api_class->modifierClasse( 9, $classe );
		
		die('<p>Fin de processTestApi</p>');
    }


}
