<?php
/**
* @package	copix
* @version	$Id: blogauth.class.php,v 1.11 2007-06-15 15:05:48 cbeyer Exp $
* @author	C�dric VALLAT, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Class de gestion des droits utilisateur
 */

_classInclude ('blog|blogoptions');

class user {
	var $name;
	var $userId;
	var $email;
	var $web;
	var $isConnected;
	
	function user() {
		if (Kernel::is_connected()) {
			$session = Kernel::getSessionBU ();
			$this->userId = $session['user_id'];
			$this->name = trim($session['prenom'].' '.$session['nom']);
			$this->email = '';
			$this->web = '';
			$this->isConnected = true;
		} else {
			$this->name = '';
			$this->userId = 0;
			$this->email = '';
			$this->web = '';
			$this->isConnected = false;
		}
	}
	
	function isConnected() {
		/* ... */
		return $this->isConnected;
	}
	
	/*...*/
}


class BlogAuth {
	
	/**
	* fonction getUserInfos
	* param : 
	* return : Le pr�nom et le nom de l'utilisateur connect�
	*/
	function getUserInfos($id_blog=NULL) {
		/* ... */
		//print_r($_SESSION);
		$user = new user();
		if ($id_blog) {
			if (!isset($_SESSION['cache']['right']['MOD_BLOG'][$id_blog])) {
				$_SESSION['cache']['right']['MOD_BLOG'][$id_blog] = Kernel::getLevel("MOD_BLOG", $id_blog);
				//Kernel::deb ("getUserInfos($id_blog)");
				//print_r($_SESSION);
			}
			$user->right = $_SESSION['cache']['right']['MOD_BLOG'][$id_blog];
		}
		return $user;
	}
	
	/**
	* fonction canComment
	* param : $id_blog = Id du blog
	* return : vrai si l'utilisateur a les droits de commenter les articles de ce blog, faut sinon
	*/
	function canComment($id_blog) {
		/* ... */
		return true;		
	}
	
	
	

	/**
	 * Gestion des droits dans un blog
	 *
	 * Teste si l'usager peut effectuer une certaine op�ration par rapport � son droit. Le droit sur le blog est calcul� ou r�cup�r� de la session dans la fonction
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/05/31
	 * @param string $action Action pour laquelle on veut tester le droit
	 * @param object $r L'objet sur lequel on teste le droit
	 * @return bool true s'il a le droit d'effectuer l'action, false sinon
	 */
	function canMakeInBlog ($action, $r) {
		$can = false;
		if (!$r)
			return false;
		$userInfos = BlogAuth::getUserInfos ($r->id_blog);
		//print_r($userInfos);
		$droit = $userInfos->right;
		//Kernel::deb("action=$action / droit=$droit");
		switch ($action) {
			case "READ" :
				$can = ($droit >= PROFILE_CCV_NONE);
				break;
			case "ACCESS_ADMIN" :
			case "ADMIN_ARTICLES" :
			case "ADMIN_PHOTOS" :
			case "ADMIN_DOCUMENTS" :
				$can = ($droit >= PROFILE_CCV_VALID);
				break;
			case "ADMIN_CATEGORIES" :
			case "ADMIN_COMMENTS" :
			case "ADMIN_LIENS" :
			case "ADMIN_PAGES" :
			case "ADMIN_RSS" :
			case "ADMIN_ARTICLE_MAKE_ONLINE" :
			case "ADMIN_ARTICLE_DELETE" :
				$can = ($droit >= PROFILE_CCV_MODERATE);
				//$can = false;
				break;
			case "ADMIN_OPTIONS" :
			case "ADMIN_DROITS" :
			case "ADMIN_STATS" :
				$can = ($droit >= PROFILE_CCV_ADMIN);
				break;
		}
		return $can;
	}

}
?>
