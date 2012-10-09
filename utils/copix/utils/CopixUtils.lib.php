<?php
/**
* @package   copix
* @subpackage generaltools
* @version   $Id: CopixUtils.lib.php,v 1.4 2006-10-04 16:21:18 fmossmann Exp $
* @author   Croes G�rald, Jouanneau Laurent
*           see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
*  Valide une chaine repr�sentant un email.
* contr�le de la forme uniquement.
* @param chaine - la chaine � valider.
* @return si oui ou non la chaine est un email valide.
*/
function validateEMail($chaine)
{
    return ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
    '@'.
    '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
    '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',
    $chaine);
}
/**
* construction d'une chaine de param�tres URL.
* modif automatique des caract�res sp�ciaux.
* @param array $Params tableau associatif contenant les noms des param�tres et leurs valeurs. de la forme Tab[NomParametre]=valeurParam.
* @return string la chaine de param�tres.
*/
function urlParams ($params, $forhtml=true)
{
    $stringparam = "";
    $first = true;//Premier param�tre ?
    if (!is_array ($params)){
        return "";
    }
    if (count ($params) == 0){
        return "";
    }

    foreach ($params as $key=>$elem) {
        if (!$first) {
            //Si pas le premier, ajoute un et commercial pour s�parer
            $stringparam .= ($forhtml?'&amp;':'&');
        }
        $first = false;
        $stringparam .= $key.'='.urlencode($elem);//Ajout du param�te.
    }
    return $stringparam;
}
/**
* retourne un bool�en de format divers sous forme de chaine de caract�re
* @param  string $param    chaine representant un boolean
* @return boolean
*/
function getTxtBool ($param)
{
    if (is_null ($param) || ($param == '') || ($param == 'false') || ($param == 'f') || ($param == 'n') || ($param == '0') || ($param==false)){
        return 'false';
    }
    if (($param == 'true') || ($param == 'y') || ($param == 'o') || ($param == '1') || ($param==true)){
        return 'true';
    }
    return false;
}
/**
* retour de l'url actuelle sous forme de tableau associatif.
* @return array
*/
function getUrlTab ()
{
    $UrlNew = array ();
    //$params = $_GET;
    $params = CopixRequest::asArray ();
    foreach ($params as $param=>$valeur){
      $UrlNew[$param] = $valeur;
    }
    return $UrlNew;
}
/**
* Retour de la partie get de l'url..... il y a surement moyen de faire plus simple.
* @return   array   parametre url
*/
function getUrlParams ()
{
    return urlParams (getUrlTab ());
}
/**
* Capitalisation d'une chaine de caract�re
* @param string $string  la chaine a capitaliser.
* @return string la chaine transform�e.
*/
function capitalizeString ($string)
{
    if (strlen ($string) < 1 )   return $string;
    return strtoupper ($string{0}) . strtolower (substr ($string, 1));
}
/**
* filtre les donn�es d'un tableau d'objets, retourne le tableau filtr�.
*
* $tabCriteres['champ'] = array ('extact'/'approx'/'inf_eg'/'sup_eg', 'value')
* @param   array   $tab   liste d'objets
* @param   array   $tabCriteres   liste de critere de filtrage
* @param    string    $sortByField le nom du champ parlequel on souhaite trier les infos.
* @return   array   tableau filtr�
*/
function tabOfObjectFilter ($tab, $tabCriteres, $sortByField = null)
{
    reset ($tabCriteres);

    $toReturn  = array ();
    $tmpFilter = array ();

    foreach ($tab as $key=>$obj){
        $include = true;
           foreach ($tabCriteres as $fieldToCheck => $whatToCheck){
                if ($whatToCheck[0] == 'exact'){
                    //test le match exact.
                    if ($obj->$fieldToCheck != $whatToCheck[1]){
                        $include = false;
                    }
                }else if ($whatToCheck[0] == 'approx'){
                    //test le match approximatif.
                    if (strpos ($obj->$fieldToCheck, $whatToCheck[1]) === false){
                        $include = false;
                    }
                }else if ($whatToCheck[0] == 'sup_eg'){
                    //doit �tre sup�rieur..... donc �chec si inf�rieur
                    if ($obj->$fieldToCheck <= $whatToCheck[1]){
                        $include = false;
                    }
                }else if ($whatToCheck[0] == 'inf_eg'){
                    //doit �tre sup�rieur � fournit, donc �chec si sup.
                    if ($obj->$fieldToCheck >= $whatToCheck[1]){
                        $include = false;
                    }
                }
            }
        //si tout est ok au niveau des filtres, on ajoute au tableau de retour.
        if ($include){
            $tmpFilter[$key] = $obj;
        }
    }

    //tri des infos.
    if ($sortByField != null){
        $tmpTabTri = array ();
        foreach ($tmpFilter as $key=>$elem){
            $tmpTabTri[$key] = $elem->$sortByField;
        }
        asort ($tmpTabTri);

        //on a les clefs tri�es dans l'ordre souhait�, on remplit maintenant le
        //tableau de retour
        foreach ($tmpTabTri as $key=>$elem){
            $toReturn[$key] = $tmpFilter[$key];
        }
    } else {
        $toReturn = $tmpFilter;//pas de tri, on copie directement.
    }

    //retour des infos.
    return $toReturn;
}
/**
* kills the french chars �, �, ... with their internationnal �quivalent (e, a, ...)
* @param string $string the string to kill french chars from
* @return   string    la chaine filtr�e
*/
function killFrenchChars ($string)
{
    return strtr($string,'����������������n','aaaeeeeiiyoouucnn');
}
