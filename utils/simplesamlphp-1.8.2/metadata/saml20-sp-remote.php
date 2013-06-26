<?php

$metadata['https://auth.coreprim.fr/saml/metadata'] = array(
    'entityid' => 'https://auth.coreprim.fr/saml/metadata',
    'name' =>
    array(
        'en' => 'CRDP Aix Marseille',
    ),
    'description' =>
    array(
        'en' => 'CRDP',
    ),
    'OrganizationName' =>
    array(
        'en' => 'CRDP',
    ),
    'OrganizationDisplayName' =>
    array(
        'en' => 'CRDP Aix Marseille',
    ),
    'url' =>
    array(
        'en' => 'https://www.coreprim.fr/main-menu.html',
    ),
    'OrganizationURL' =>
    array(
        'en' => 'https://www.coreprim.fr/main-menu.html',
    ),
    'contacts' =>
    array(
    ),
    'metadata-set' => 'saml20-sp-remote',
    'AssertionConsumerService' =>
    array(
        0 =>
        array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
            'Location' => 'https://auth.coreprim.fr/saml/proxySingleSignArtifact',
            'index' => 0,
            'isDefault' => true,
        ),
        1 =>
        array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://auth.coreprim.fr/saml/proxySingleSignOnPost',
            'ResponseLocation' => 'https://auth.coreprim.fr/saml/singleSignOnReturn',
            'index' => 1,
            'isDefault' => false,
        ),
    ),
    'SingleLogoutService' =>
    array(
        0 =>
        array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
            'Location' => 'https://auth.coreprim.fr/saml/proxySingleLogoutSOAP',
        ),
        1 =>
        array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://auth.coreprim.fr/saml/proxySingleLogout',
            'ResponseLocation' => 'https://auth.coreprim.fr/saml/proxySingleLogoutReturn',
        ),
        2 =>
        array(
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://auth.coreprim.fr/saml/proxySingleLogout',
            'ResponseLocation' => 'https://auth.coreprim.fr/saml/proxySingleLogoutReturn',
        ),
    ),
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'simplesaml.nameidattribute' => 'id_dbuser',
    'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:basic',
    'simplesaml.attributes' => true,
    'attributes' => array('id_dbuser', 'mail_dbuser'),
    'redirect.sign' => true,
);
