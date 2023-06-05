<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
define('ROOT_PATH', realpath(dirname(__FILE__)) . '../../');
require_once ROOT_PATH . 'security/config.php';
require_once ROOT_PATH . 'core/functions/functions.php';
secureSession();
$db = null;

if (isset($config)) {
    $db = getDBConnection($config);
}
$settings = getSettings($db);

// Check if the keys exist in the array before using them
$site_title = isset($settings['site_title']) ? $settings['site_title'] : '';
$meta_description = isset($settings['meta_description']) ? $settings['meta_description'] : '';
$footer_text = isset($settings['footer_text']) ? $settings['footer_text'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $site_title;?></title>
    <meta http-equiv="Content-Security-Policy: script-src 'self' 'unsafe-inline' https://ssl.gstatic.com 'unsafe-eval'">
    <link href="assets/bootstrap-5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet"> -->
    <link href="assets/fonts/fonts-quicksand.css" rel="stylesheet">
    <link href="assets/main/style.css" rel="stylesheet"> <!-- -->
   <style>
    .navbar-shrink {
        height: 80px;
        transition: height 0.5s ease;
    }
  </style>
</head>
<body>