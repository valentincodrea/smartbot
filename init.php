<?php
session_start();

define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/load_env.php';
load_env();

$configPath  = BASE_PATH . '/client/config.php';
$answersPath = BASE_PATH . '/client/answers.php';

if (!file_exists($configPath) || !file_exists($answersPath)) {
  http_response_code(500);
  exit("Eroare internă configurare.");
}

$config = require $configPath;

$ora = (int)date('H');
$mesajIntro = ($ora < 12)
  ? ($config['mesaje']['intro_dimineata'] ?? 'Bună dimineața!')
  : ($config['mesaje']['intro_seara'] ?? 'Bună seara!');

if (isset($_GET['intro'])) {
  $mesajIntro = htmlspecialchars($_GET['intro']);
}

$widget_path = BASE_PATH . '/core/widget.php';
if (!file_exists($widget_path)) {
  http_response_code(500);
  exit("Eroare widget.");
}

include $widget_path;
?>