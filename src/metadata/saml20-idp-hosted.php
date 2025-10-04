<?php

use MockSaml2Idp\Settings;

$settings = new Settings();

$metadata[$settings->idp_entity_id] = [
    'host' => '__DEFAULT__',
    'certificate' => $settings->idp_cert_path,
    'privatekey' => $settings->idp_key_path,
    'auth' => $settings->idp_auth_mode,
    'NameIDFormat' => [$settings->idp_nameid_format],
    'authproc' => [
        3 => [
            'class' => 'saml:AttributeNameID',
            'identifyingAttribute' => $settings->idp_nameid_attribute,
            'Format' => $settings->idp_nameid_format,
        ],
    ],
];
