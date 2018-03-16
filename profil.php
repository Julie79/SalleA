<?php

// site/profil.php

require_once('inc/init.inc.php');

// Accessibilité : Redirection si user déjà pas connecté
if(!userConnect()) {
	header('location:connexion.php');
}


debug($_SESSION);
extract($_SESSION['membre']);

$page = 'Profil';
$meta_description = 'Bienvenue sur la page profil de ' . $pseudo;

require_once('inc/header.inc.php');
?>


<!-- Contenu HTML -->

<h1>Profil de <?= $pseudo ?></h1>

<div class="profil">
	<p>Bonjour <?= $pseudo ?> !!</p><br/>

	<div class="profil_img">
		<img src="img/default.png" />
	</div>
	
	<div class="profil_infos">
		<ul>
			<li>Pseudo :<b><?= $pseudo ?></b></li>
			<li>Prénom :<b><?= $prenom ?></b></li>
			<li>Nom :<b><?= $nom ?></b></li>
		</ul>
	</div>
	
	<div class="profil_adresse">
		<ul>
			<li>Adresse :<b><?= $adresse ?></b></li>
			<li>Code Postal :<b><?= $code_postal ?></b></li>
			<li>Ville :<b><?= $ville ?></b></li>
			<li>Email :<b><?= $email ?></b></li>
		</ul>
	</div>
	
</div>

<div class="profil">
	<h2>Détails des commandes</h2>

</div>





<?php
require_once('inc/footer.inc.php');
?>