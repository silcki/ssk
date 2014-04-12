<?php

session_start();

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../'));

require_once ROOT_PATH . '/include/config.php';
require_once ROOT_PATH . '/include/captcha/SimpleCaptcha.php';

$captcha = new SimpleCaptcha();
$captcha->resourcesPath = ROOT_PATH . '/include/captcha/resources';
//$captcha->width = 200;
//$captcha->height = 70;
// OPTIONAL Change configuration...
//$captcha->wordsFile = 'words/es.php';
//$captcha->session_var = 'secretword';
//$captcha->imageFormat = 'png';
//$captcha->lineWidth = 3;
//$captcha->scale = 3; $captcha->blur = true;
//$captcha->resourcesPath = "/var/cool-php-captcha/resources";

$lang = array('en', 'es');
shuffle($lang);

$captcha->session_var =  SESSION_CAPTCHA_VAR_NAME;
$captcha->wordsFile = "words/{$lang[1]}.php";

// Image generation
ob_clean();
$captcha->CreateImage();