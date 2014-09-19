<?php

define('CONFIG_FILE', './includes/config.ini');

if (!file_exists(CONFIG_FILE)) {
  die('You should specify a configuration file');
}
$config = parse_ini_file(CONFIG_FILE, TRUE);

//Retrieve useful informations
//basepath to call
$basepath = isset($config['service']['remote_address']) ? trim($config['service']['remote_address']) : FALSE;
if (FALSE === $basepath || '' === $basepath) {
  die('You should specify a remote address');
}

//qs parameter
$qs_param = isset($config['core']['qstring_parameter']) ? trim($config['core']['qstring_parameter']) : FALSE;
if (FALSE === $qs_param || '' === $qs_param) {
  die('You should specify a qs parameter');
}

$content_type = isset($config['core']['content_type']) ? trim($config['core']['content_type']) : FALSE;
if (FALSE === $content_type || '' === $content_type) {
  die('You should specify a content type');
}


if (isset($_GET[$qs_param])) {
  $e_sub = $_GET[$qs_param];
  unset($_GET[$qs_param]);
}

$call_base = $basepath . rtrim($e_sub, "/") . "/";
$qstring = "?" . http_build_query($_GET);
$qstring = trim($qstring) === "?" ? "" : $qstring;

$url_to_call = $call_base . $qstring;


$ch = curl_init();
try {
  curl_setopt_array($ch, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url_to_call
  ));
  $res = curl_exec($ch);
} catch (Exception $e) {
  die("An error occurred -> " . $e->getMessage());
}

header('Content-Type: ' . $content_type);
die($res);


