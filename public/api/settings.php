<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MockSaml2Idp\Settings;

$settings = new Settings();

header('Content-Type: application/json');
echo json_encode($settings->to_array()) . PHP_EOL;
