<?php

/**
* @package   copix
* @subpackage plugins
* @version   $Id: stats.plugin.conf.php,v 1.1 2007-06-12 14:33:36 cbeyer Exp $
* @author   Christophe Beyer
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class PluginConfigStats {
	/*
		D�termine si l'on active le cache.
		Si true, un seul passage dans une action pr�cise n'est enregistr�e par session
		Si false, chaque passage est enregistr� (y compris les rafra�chissements successifs)
	*/
	var $cache = true;
}

?>
