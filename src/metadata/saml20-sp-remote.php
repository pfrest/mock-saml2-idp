<?php

use MockSaml2Idp\Settings;

$settings = new Settings();

$metadata[$settings->sp_entity_id] = [
    'AssertionConsumerService' => [
        [
            'Location' => $settings->sp_acs_location,
            'Binding' => $settings->sp_acs_binding,
        ],
    ],
    'SingleLogoutService' => [
        [
            'Location' => $settings->sp_slo_location,
            'Binding' => $settings->sp_slo_binding,
        ],
    ],
];
