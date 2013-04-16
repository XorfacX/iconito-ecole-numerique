<?php

class sspmod_iconito_Auth_Source_IconitoAuth extends sspmod_core_Auth_UserPassBase {
	protected function login($username, $password) {
		assert('is_string($username)');
		assert('is_string($password)');

		if ($username !== 'user' || $password !== 'pass') {
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');
		}

		return array(
			'user' => array('user'),
			'iud'  => array('user_uid'),
			'displayName' => array('member','employee'),
		);
	}

}


?>
