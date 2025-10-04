<?php

use MockSaml2Idp\Settings;

$settings = new Settings();

$config = [
    'basic' => [
        'exampleauth:UserPass',
        'users' => [
            "$settings->idp_user_name:$settings->idp_user_password" => [
                $settings->idp_user_uid_attribute => $settings->idp_user_uid,
                $settings->idp_user_name_attribute => $settings->idp_user_name,
                $settings->idp_user_first_name_attribute => $settings->idp_user_first_name,
                $settings->idp_user_last_name_attribute => $settings->idp_user_last_name,
                $settings->idp_user_email_attribute => $settings->idp_user_email,
                $settings->idp_user_groups_attribute => $settings->idp_user_groups,
            ],
        ],
    ],
    'auto' => [
        'exampleauth:StaticSource',
        $settings->idp_user_uid_attribute => $settings->idp_user_uid,
        $settings->idp_user_name_attribute => $settings->idp_user_name,
        $settings->idp_user_first_name_attribute => $settings->idp_user_first_name,
        $settings->idp_user_last_name_attribute => $settings->idp_user_last_name,
        $settings->idp_user_email_attribute => $settings->idp_user_email,
        $settings->idp_user_groups_attribute => $settings->idp_user_groups,
    ],
];

# Include any custom attributes from environment variables
foreach ($settings->idp_user_custom_attributes as $attr => $value) {
    $config['basic']['users']["$settings->idp_user_name:$settings->idp_user_password"][$attr] = $value;
    $config['auto'][$attr] = $value;
}
