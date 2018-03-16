<?php
require_once('inc/init.inc.php');

// accessibilité : redirection si user déjà connecté
if(userConnect()){
	header('location:profil.php');
}

if($_POST){
	debug($_POST);


	// vérification si le pseudo existe en BDD (donnée sensible)
	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$resultat -> execute();

	// Si on ne trouve pas le pseudo :
	if($resultat -> rowCount() == 0){ 
			$msg .= '<div class="bg-danger">Le pseudo est erroné</div>';
	}
	// si on trouve le pseudo
	else{
		$membre = $resultat -> fetch();
		debug($membre);
		

		// on vérifie si le mdp entré est le même qu'en BDD
		if($_POST['mdp'] === $membre['mdp']){
			// oui c'est le même -> on enregistre les données de user dans le fichier de session
			foreach($membre as $indice => $valeur){
				$_SESSION['membre'][$indice] = $valeur;
			}
			debug($_SESSION);
			header('location:profil.php');
		}
		else{ // ce n'est pas le bon mdp
			$msg .= '<div class="bg-danger">Le mot de passe est erroné</div>';
		}
	}
}

$page = 'Connexion';
require_once('inc/header.inc.php');
?>

<?= $msg; ?>

<form method="post" action="#" enctype="multipart/form-data">

	<div class="form-group">
		<input class="form-control" type="text" name="pseudo" id="pseudonyme" placeholder="Votre pseudo" required />
	</div>

	<div class="form-group">
		<input class="form-control" type="text" name="mdp" id="mdp" placeholder="Votre mot de passe" required />
	</div>

	<input type="submit" name="submit" id="submit" value="Connexion">

</form>

<?php
require('inc/footer.inc.php');
?>
