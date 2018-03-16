<?php
require_once('../inc/init.inc.php');

// accessibilité : si user non admin ===> redirection
if (!userAdmin()) { // Si la fonction userAdmin() nous retourne false, cela signifie que l'user n'est pas admin
	header('location:' . RACINE_SITE . 'accueil.php');
}

// Traitement pour récupérer les infos de la table 'membre' et les afficher dans un tableau
$resultat = $pdo -> query("SELECT * FROM membre");
$membre = $resultat -> fetchAll(PDO::FETCH_ASSOC);


$contenu = '<div class="table-responsive">';
$contenu .= '<table class="table text-center">';
$contenu .= '<tr>';

for($i=0; $i<$resultat -> columnCount(); $i ++){// la boucle va tourner autant de fois qu'on a de champs dans la table
	$champs = $resultat -> getColumnMeta($i);
	$contenu .= '<th class="text-center">' . $champs['name'] . '</th>';
}
$contenu .= '<th>actions</th>';
$contenu .= '</tr>';

foreach($membre as $value){
	$contenu .= '<tr>';
	foreach($value as $value2){
		$contenu .= '<td>' . $value2 . '</td>';
	}
	$contenu .= '<td><a href="?action=modifier&id=' . $value['id_membre'] . ' "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' . ' ' . '<a href="?action=supprimer&id=' . $value['id_membre'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
	$contenu .= '</tr>';
}
$contenu .= '</table>';
$contenu .= '</div>';

// fin des traitements pour afficher le tableau

// AJOUTER UN MEMBRE *************************************************************
if ($_POST) {
	debug($_POST);
	extract($_POST);

	// vérification des champs
	if(!empty($_POST['pseudo']) || !empty($_POST['mdp']) || !empty($_POST['nom']) || !empty($_POST['prenom']) || !empty($_POST['email']) || !empty($_POST['civilite']) || !empty($_POST['statut'])){ // Si les champs sont renseignés...

		// On vérifie la nature du pseudo
        $verif_pseudo = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']); 
        if(!$verif_pseudo){ // si les caractères utilisés dans le pseudo posent problèmes à preg_match qui nous retourne FALSE
            $msg .= '<div class="bg-danger">Votre pseudo doit comporter de 3 à 20 caractères (de A à Z, de 0 à 9, et ".", "-", "_")</div>';
        }

        // On vérifie la nature du mdp
        $verif_mdp = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{4,15})$#', $_POST['mdp']);
        if(!$verif_mdp){
            $msg .= '<div class="bg-danger">Votre mot de passe doit comporter de 4 à 15 caractères (dont au moins une MAJ, un chiffre et un caratère spécial)</div>';
        }

        // On vérifie la nature du nom
        $verif_nom = preg_match('#^[a-zA-Z-]{3,20}$#', $_POST['nom']); 
        if(!$verif_nom){ 
            $msg .= '<div class="bg-danger">Votre nom doit comporter de 3 à 20 caractères</div>';
        }

        // On vérifie la nature du prénom
        $verif_prenom = preg_match('#^[a-zA-Z-]{3,20}$#', $_POST['prenom']); 
        if(!$verif_prenom){ 
            $msg .= '<div class="bg-danger">Votre prénom doit comporter de 3 à 20 caractères</div>';
        }

        // On vérifie la nature de l'email
        $verif_email = filter_var($email, FILTER_VALIDATE_EMAIL); // on vérifie si c'est bien un format mail

    } // Fin du if(!empty($_POST['']))

    else{ // Si les champs ne sont pas renseignés...
        $msg .= '<div class="bg-danger">Veuillez renseigner un pseudo</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un mot de passe</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un nom</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un prénom</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un email</div><br>';
    }

    // Traitements d'enregistrement de l'utilisateur :
	if(empty($msg)){ // Si on n'a aucun message d'erreur : tout est OK on peut enregistrer l'utilisateur

        // Le pseudo est-il disponible?
        $resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $resultat -> bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $resultat -> execute();

        if($resultat -> rowCount() > 0){ // Cela signifie que nous avons trouvé un enregistrement en BDD avec ce pseudo, il n'est donc pas dispo...
            $msg .= '<div class="bg-danger">Le pseudo ' . $pseudo . ' n\'est malheureusement pas disponible, veuillez en choisir un autre.</div>';
        }
	    else{ // le pseudo est disponible, on peut enregistrer l'utilisateur

		    // Si on est dans la phase de modification :
			if (!empty($_POST['id_membre'])) { 
				$resultat = $pdo -> prepare("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut) VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut) ");

				$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
			}
			else{ // Sinon c'est qu'on est en phase création : 
				$resultat = $pdo -> prepare("INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES(:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, curdate()) ");
			}
			// crypter le mot de passe
            
            $mdp_crypte = md5($_POST['mdp']); // protocole de cryptage 

				
    		$resultat -> bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    		$resultat -> bindParam(':mdp', $mdp, PDO::PARAM_STR);
    		$resultat -> bindParam(':nom', $nom, PDO::PARAM_STR);
    		$resultat -> bindParam(':prenom', $prenom, PDO::PARAM_STR);
    		$resultat -> bindParam(':email', $email, PDO::PARAM_STR);
    		$resultat -> bindParam(':civilite', $civilite, PDO::PARAM_STR);
    		$resultat -> bindParam(':statut', $statut, PDO::PARAM_STR);
    		
    		if($resultat -> execute()){
    			header('location:gestion_membres.php');
    		}
        } // fin du else
	} // fin du if(empty($msg))
} // fin du if($_POST)

// Traitement pour récupérer les données saisies et les remettre dans le formulaire
$pseudo =       (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$prenom =       (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$nom =          (isset($_POST['nom'])) ? $_POST['nom'] : '';
$civilite =     (isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$email =        (isset($_POST['email'])) ? $_POST['email'] : '';

// SUPPRIMER UN MEMBRE ***********************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
	if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {

		// vérifier dans la BDD si ce membre existe bien
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		if ($resultat -> rowCount() > 0) { 

			$membre = $resultat -> fetch();

			// si on l'a trouvé dans la BDD, alors on peut faire la requête pour le supprimer
			$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $membre[id_membre]");

			if ($resultat !== FALSE) { // si la requete a fonctionné :
				header('location:' . RACINE_SITE . 'backoffice/gestion_membres.php');
			}	
		}
		else{
			header('location:' . RACINE_SITE . 'backoffice/gestion_membres.php');
		}
	} //fin du if (isset($_GET['id'])
	header('location:' . RACINE_SITE . 'backoffice/gestion_membres.php');

} // fin du if (isset($_GET['action'])

// MODIFIER UN MEMBRE ************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier') {

	// on commence par récupérer les infos du produit à modifier
	if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){ 
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){
			$membre_actuel = $resultat -> fetch();

			debug($membre_actuel);
		}
	}
}

$id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';

$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
$mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
$nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
$statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';

// On définit une variable $action qui va stocker soit 'modifier' soit 'ajouter' afin de rendre dynamique le h1 et le bouton du formulaire.
$action = (isset($membre_actuel)) ? 'Modifier' : 'Ajouter';



$page = 'Gestion des membres';
require_once('../inc/header.inc.php');
?>

<h1><?= $page ?></h1>

<?= $contenu; ?><br>

<!-- FORMULAIRE -->
<div class="row">
	<div class="col-lg-6">
		<form method="post" action="#" enctype="multipart/form-data">
			<?= $msg ?>
			<input type="hidden" name="id_membre" value="<?= $id_membre ?>">
			<!-- Le champs ci-dessus permet de transmettre à notre requête REPLACE l'id de la salle en cours de modification -->

			<div class="form-group">
				<label for="pseudo">Pseudo</label>
				<input class="form-control" type="text" name="pseudo" id="pseudo" placeholder="pseudo" value="<?= $pseudo ?>" />
			</div>

			<div class="form-group">
				<label for="mdp">Mot de passe</label>
				<input class="form-control" type="text" name="mdp" id="mdp" placeholder="mot de passe">
			</div>

			<div class="form-group">
				<label for="nom">Nom</label>
				<input class="form-control" type="text" name="nom" id="nom" value="<?= $nom ?>"/>
			</div>

			<div class="form-group">
				<label for="prenom">Prénom</label>
				<input class="form-control" type="text" name="prenom" id="prenom" value="<?= $prenom ?>"/>
			</div>

		
	</div> <!-- fin de la colonne de gauche -->


	<div class="col-lg-6 ">
		

			<div class="form-group" >
				<div class="form-group">
					<label for="email">Email</label>
					<input class="form-control" type="text" name="email" id="email" value="<?= $email ?>"/>
				</div>

				<label for="civilite">Civilite</label>
				<select class="form-control" id="civilite" name="civilite" value="<?= $civilite ?>">
					<option disabled selected>Choisir</option>
					<option <?= ($civilite == 'm') ? 'selected' : '' ?> value="m">Homme</option>
					<option <?= ($civilite == 'f') ? 'selected' : '' ?> value="f">Femme</option>
				</select>
			</div>
			
			<div class="form-group">
				<label for="statut">Statut</label>
				<select class="form-control" name="statut" id="statut" value="<?= $statut ?>">
					<option disabled selected>Choisir</option>
					<option <?= ($statut == 'admin') ? 'selected' : '' ?> value="admin">Admin</option>
					<option <?= ($statut == 'membre') ? 'selected' : '' ?> value="membre">Membre</option>
				</select>
			</div>
	
			<div>
				<input type="submit" value="<?= $action ?>" />
			</div>

		</form> <!-- fin du formulaire de droite -->
	</div> <!-- fin de la colonne de droite -->
</div> <!-- fin de la row -->

</body>
</html>



<?php  			
require_once('../inc/footer.inc.php');			
?>	