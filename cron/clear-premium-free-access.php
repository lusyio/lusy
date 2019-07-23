<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных

$clearPremiumFreeAccessQuery = $pdo->prepare('UPDATE company SET premium_free_access = NULL WHERE id > 0');
$clearPremiumFreeAccessQuery->execute();
