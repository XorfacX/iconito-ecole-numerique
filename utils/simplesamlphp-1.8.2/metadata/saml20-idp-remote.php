<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://rnd.feide.no/content/idp-remote-metadata-reference
 */

$metadata['http://ecolenumerique.fmossmann.cap/simplesaml/module.php/saml/sp/metadata.php/default-sp'] = // A_MODIFIER
// $metadata['http://ecolenumerique.fmossmann.cap/simplesaml/saml2/idp/metadata.php'] =
 array(
	'name' => array(
		'en' => 'Iconito IdP FullURL',
	),
	'description'          => 'Welcome to my Iconito IdP !',

	'SingleSignOnService'  => 'http://'. $_SERVER['HTTP_HOST'] .'/simplesaml/saml2/idp/SSOService.php', // A_MODIFIER
	'SingleLogoutService'  => 'http://'. $_SERVER['HTTP_HOST'] .'/simplesaml/saml2/idp/SingleLogoutService.php', // A_MODIFIER
	'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb' // A_MODIFIER
);

