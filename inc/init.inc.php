<?php

// session :
session_start();

// connexion :
$pdo = new PDO('mysql:host=localhost;dbname=sallea', 'root', '', array(
	PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
));

// variables :
$msg = "";
$page = "";
$contenu = "";

// chemins :
define('RACINE_SITE', '/SALLEA/'); // création du chemin du site à partir de htdocs
define('RACINE_SERVEUR', $_SERVER['DOCUMENT_ROOT']); // création du chemin du serveur grâce à la superglobale $_SERVER

// autres inclusions :
require_once('fonctions.inc.php');
?>