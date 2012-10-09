<?php

/**

* @package   copix

* @subpackage copixtools

* @version   $Id: CopixPager.config.php,v 1.4 2006-10-04 16:21:18 fmossmann Exp $

* @author <o.veujoz@miasmatik.net>, Bertrand Yan

* @copyright 2001-2005 CopixTeam

* @link      http://copix.org

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/



/**

 *

 * Fichier de configuration de la classe CopixPager pour un projet entier

 * Ce fichier sert de valeurs par d�faut pour un projet entier, mais rien n'emp�che de les param�trer directement

 * lors de la cr�ation d'un objet Multipage

 *

 */









/**************************************

 * Param�trage global du multipage

 **************************************/



$options['PAGER'] = Array(

    'perPage'        => 10,                 // Nombre de r�sultats par page

    'delta'          => 5,                  // Le nombre de liens maximum souhait� dans le multipage (0 = tous les liens)

    'alwaysShow'     => true,               // Que faire si le multipage n'est pas n�cessaire? L'afficher ou non?

    'toHtmlEntities' => false,              // Positionn� � true, les caract�res sp�ciaux des libell�s seront traduits en leur entit� HTML

    'encodeVarUrl'   => false,              // Positionn� � true, les param�tres pass�s par Url seront encod�s

    'display'        => 'sliding'           // 'sliding' || 'jumping'

);







/****************************

 * Param�trage divers

 ****************************/



$options['PARAMS'] = Array(

    'nextPage'        => '<img src="'._resource('images/icon-16/pager-next.png').'" alt="next"/>&nbsp;',         // libell� lien vers la page suivante

    'previousPage'    => '<img src="'._resource('images/icon-16/pager-previous.png').'" alt="previous"/>&nbsp;',       // libell� lien vers la page pr�c�dente

    'lastPage'        => '<img src="'._resource('images/icon-16/pager-last.png').'" alt="last"/>',              // libell� lien vers la derni�re page

    'firstPage'       => '<img src="'._resource('images/icon-16/pager-first.png').'" alt="first"/>&nbsp;',              // libell� lien vers la premi�re page

    'separator'       => '&nbsp;-&nbsp;',   // S�parateur de page

    'curPageSpanPre'  => '<strong>',          // Chaine pr�fixant la page courante

    'curPageSpanPost' => '</strong>',        // Chaine suffixant la page courante

    'linkClass'       => 'multipage'    // Classe CSS � ajouter aux liens

);





/**************************************************************************************************

 * Configuration avanc�e (optionnel, vous pouvez laisser les param�tres par d�faut.

 * C'est uniquement pour ceux qui aiment bidouiller.)

 **************************************************************************************************/



$options['ADVANCED'] = Array(

    'varUrl'             => 'p',            // Nom de la variable dans l'url servant � indiquer la page en cours

);

