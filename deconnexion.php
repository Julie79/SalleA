<?php
// site/deconnexion.php
require_once('inc/init.inc.php');

unset($_SESSION['membre']); // On supprime la partie membre de la session. Seulement la partie membre, car on souhaiterait récupérer les infos liées au panier.

header('location:accueil.php');

?>