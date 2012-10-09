<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: modifier.substrpos.php,v 1.1 2007-03-23 16:22:01 cbeyer Exp $

* @author   Christophe Beyer

* @copyright 2007 CAP-TIC

* @link      http://www.cap-tic.fr

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/


/**

 * Plugin smarty type modifier
 * Purpose: A partir d'une chaine de caract�res (typiquement une URL), en extrait la fin, en commen�ant � la derni�re occurence d'un caract�re (par d�faut le slash).
 * Input: Chaine de caract�res (URL ou autre)
 * Output: Chaine de caract�res.
 * Example:  {$text|substrpos} {$text|substrpos:/}
 * @return string
 */

function smarty_modifier_substrpos ($string, $char="/")
{
    $txt = $string;
    $pos = strrpos($string, $char);
    if ($pos !== false) {
      $txt = substr($string,$pos+1);
    }
    return $txt;
}

