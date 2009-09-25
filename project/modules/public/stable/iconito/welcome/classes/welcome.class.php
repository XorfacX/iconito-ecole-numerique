<?php
/**
* @package  Iconito
* @subpackage Welcome
* @version   $Id: welcome.class.php,v 1.4 2007-07-20 16:08:54 cbeyer Exp $
* @author   Fr�d�ric Mossmann
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class Welcome {

	/**
	 * simplifyUrl
	 *
	 * Fonction de simplification d'URL (retire les protocoles et les '/' finaux)
	 * pour faciliter la recherche.
	 * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function simplifyUrl( $url ) {
		// Retrait du type de connexion
		if( ereg( '^([a-zA-Z]+://)(.*)$', $url, $regs ) ) $url=$regs[2];
		
		// Retrait du slash final
		if( ereg( '^(.*)/$', $url, $regs ) ) $url=$regs[1];
		
		return $url;
	}

	/**
	 * findNodeByUrl
	 *
	 * Recherche une URL identique ou similaire dans la base,
	 * et retourne le noeud correspondant.
	 * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function findNodeByUrl( $url ) {
		// Simplification de l'URL � tester.
		$url_site = Welcome::simplifyUrl( $url );
		
		// Initialisation des tests de similitude.
		$last_similar_text = 0;
		$last_levenshtein  = 9999;
		$best_node = null;
		
		$best_similar_text_node = null;
		$best_levenshtein_node = null;
		
		// Recherche de toutes les URLs.
		//$url_dao = _dao("welcome|url");
		$url_dao = _dao("kernel|kernel_limits_urls");
		$url_list = $url_dao->findAll();
		
		// Pour chaque URL...
		foreach( $url_list as $url_val ) {
			//var_dump($url_val);
			// Simplifier l'URL
			$url_test = Welcome::simplifyUrl( $url_val->url );
			
			// Tester l'exactitude.
			if ($url_site == $url_test)
				return( $url_val );
			
			// Mesurer les similitudes.
			$test_similar_text = similar_text( $url_site, $url_test );
			$test_levenshtein  = levenshtein ( $url_site, $url_test );
			
			// M�morisation si la similitude par "similar_text" est la meilleure.
			if( $test_similar_text>=$last_similar_text ) {
				$last_similar_text=$test_similar_text;
				$best_similar_text_node=$url_val;
			}
			
			// M�morisation si la similitude par "levenshtein" est la meilleure.
			if( $test_levenshtein<=$last_levenshtein ) {
				$last_levenshtein=$test_levenshtein;
				$best_levenshtein_node=$url_val;
			}

		}
		
		// Si aucune URL n'est identique, et si il y a une URL
		// similaire par les deux algorithmes, on peut la retourner.
		if( isset($best_similar_text_node->url) && isset($best_levenshtein_node->url) && ($best_similar_text_node->url == $best_levenshtein_node->url ))
			return $best_similar_text_node;

		// Si vraiment rien ne colle, on ne retourne rien.
		return null;
	}
	
	function getWelcome( $id ) {
		if( ! ereg('^[0-9]+$',$id) ) return false;
		
		$node = Kernel::getModParent( 'MOD_WELCOME', $id );
		if( sizeof($node)==0 ) return false;
		
		$droit = Kernel::getModRight( 'MOD_WELCOME', $id );
		
		$toReturn = array(
			'node'  => $node[0],
			'droit' => $droit
		);
		
		return $toReturn;
	}
	
	function canAdmin( $welcome ) {
		if( isset($welcome) && $welcome!=false && isset( $welcome['droit'] ) && $welcome['droit']>=70 )
			return true;
		else
			return false;
	}

	function commonCheck( $vars ) {
		if( ! isset($vars["homepage"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ( 'message'=>CopixI18N::get ('welcome.error.noid'),
				        'back'=>CopixUrl::get ('||') ));
		}

		$welcome = Welcome::getWelcome( $vars["homepage"] );
		if( ! $welcome ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ( 'message'=>CopixI18N::get ('welcome.error.nonode'),
				        'back'=>CopixUrl::get ('||') ));
		}
		
		if( ! Welcome::canAdmin( $welcome ) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ( 'message'=>CopixI18N::get ('welcome.error.noright'),
				        'back'=>CopixUrl::get ('||') ));
		}
		
		return $welcome;
	}

}

?>
