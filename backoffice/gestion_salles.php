<?php
require_once('../inc/init.inc.php');

// accessibilité : si user non admin ===> redirection
if (!userAdmin()) { // Si la fonction userAdmin() nous retourne false, cela signifie que l'user n'est pas admin
	header('location:' . RACINE_SITE . 'accueil.php');
}

// Traitement pour récupérer les infos de la table 'salle' et les afficher dans un tableau
$resultat = $pdo -> query("SELECT * FROM salle");
$salle = $resultat -> fetchAll(PDO::FETCH_ASSOC);


$contenu = '<div class="table-responsive">';
$contenu .= '<table class="table">';
$contenu .= '<tr class="bg-dark text-white">';

for($i=0; $i<$resultat -> columnCount(); $i ++){// la boucle va tourner autant de fois qu'on a de champs dans la table
	$champs = $resultat -> getColumnMeta($i);
	$contenu .= '<th >' . $champs['name'] . '</th>';
}
$contenu .= '<th>actions</th>';

$contenu .= '</tr>';

foreach($salle as $value){
	$contenu .= '<tr>';

	foreach($value as $key => $value2){

		if ($key == 'photo') {
				$contenu .= '<td><img height="50" src="' . RACINE_SITE . 'photos/' . $value['photo'] . '"></td>';
		}
		else{
			$contenu .= '<td>' . $value2 . '</td>';
		}

	}
	$contenu .= '<td><a href="?action=modifier&id=' . $value['id_salle'] . ' "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' . ' ' . '<a href="?action=supprimer&id=' . $value['id_salle'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
	
	$contenu .= '</tr>';
}

$contenu .= '</table>';
$contenu .= '</div>';

// fin des traitements pour afficher le tableau


// AJOUTER UNE SALLE *************************************************************
$nom_photo = '';
if ($_POST) {
	debug($_POST);
	debug($_FILES);
	
	// Vérification du champs photo
	if (!empty($_FILES['photo']['name'])) { // si une photo est uploadée...

		// 1 : on va modifier le nom de la photo pour éviter les doublons potentiels
		$nom_photo = $_POST['titre'] . '_' . time() . '_' . rand(1, 999) . '_' . $_FILES['photo']['name'];

		// 2 : on va créer une variable contenant le chemin absolu et définitif de la photo. Le chemin d'un fichier doit commencer par la racine du serveur et s'arrête à l'extension.
		$chemin_photo = RACINE_SERVEUR . RACINE_SITE . 'photos/' . $nom_photo;
		
		// 3 : on vérifie l'intégrité du fichier uploadé (poids et extension)
		if ($_FILES['photo']['size'] > 2000000) {
			$msg .= '<div class="bg-danger">Veuillez sélectionner un fichier de 2Mo maximum<div>';
		}

		$extension = array('image/jpeg', 'image/png', 'image/gif');
		if (!in_array($_FILES['photo']['type'], $extension)) {
			$msg .= '<div class="bg-danger">Veuillez sélectionner une image de type JPG, JPEG, PNG ou GIF<div>';
		} 

		// 4 : si tout est ok et que le produit est bien enregistré en BDD, il nous reste à copier l'image dans notre dossier photo
		
	} // fin du if(!empty($_FILES))
	elseif (isset($_POST['photo_actuelle'])) { // si je suis en phase modification...
		// Si je suis en train de modifier un produit alors photo_actuelle existe et je prends sa valeur pour la mettre dans nom_photo, afin qu'elle soit enregistrée telle quelle dans la BDD.
		$nom_photo = $_POST['photo_actuelle'];
	}
	else{ // sinon on ajoute une photo par défaut
		$nom_photo ='default.jpg';
	}
	

	// Vérifications sur tous les autres champs : nbr de caractères, preg_match, non vide...
	if(!empty($_POST['titre']) || !empty($_POST['description']) || !empty($_POST['pays']) || !empty($_POST['ville']) || !empty($_POST['adresse']) || !empty($_POST['cp']) || !empty($_POST['capacite']) || !empty($_POST['categorie'])){ // Si les champs sont renseignés...

		// on vérifie la nature du titre
		$verif_titre = preg_match('#^[a-zA-Z0-9-._]{3,200}$#', $_POST['titre']); 
        if(!$verif_titre){ 
            $msg .= '<div class="bg-danger">Le titre doit comporter de 3 à 200 caractères (de A à Z, de 0 à 9, et ".", "-", "_")</div>';
        }

        // on vérifie la nature de la description
        if(strlen($_POST['description']) < 5 ){ 
            $msg .= '<div class="bg-danger">La description doit comporter un minimum de 5 caractères</div>';
        }

        // on vérifie la nature de l'adresse
        if(strlen($_POST['adresse']) < 5 ){ 
            $msg .= '<div class="bg-danger">L\'adresse doit comporter 5 caractères minimum</div>';
        }

        // on vérifie la nature du cp
        if(strlen($_POST['cp']) > 5 || !is_numeric($_POST['cp'])){ 
            $msg .= '<div class="bg-danger">Veuillez renseigner un code postal à 5 chiffres</div>';
        }

	} //if(!empty($_POST[''])

	else{ // Si les champs ne sont pas renseignés...
        $msg .= '<div class="bg-danger">Veuillez renseigner un titre</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner une description</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un pays</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un ville</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner une adresse</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner un code postal</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner une capacité</div><br>';
        $msg .= '<div class="bg-danger">Veuillez renseigner une categorie</div><br>';
    }

	// Si tout est ok dans notre formulaire, on peut enregistrer le produit en BDD et la photo dans son emplacement définitif
	if (empty($msg)) {

		// Si on est dans la phase de modification :
		if (!empty($_POST['id_salle'])) { // Si un id_salle est renseignée c'est qu'on est en phase modification
			$resultat = $pdo -> prepare("REPLACE INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:id_salle, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie) ");

			$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
		}
		else{ // Sinon c'est qu'on est en phase création : on enregistre le produit en BDD
			$resultat = $pdo -> prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie) ");
		}

		$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
		$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);
		$resultat -> bindParam(':photo', $nom_photo, PDO::PARAM_STR);
		$resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
		$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
		$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
		$resultat -> bindParam(':cp', $_POST['cp'], PDO::PARAM_STR);
		$resultat -> bindParam(':capacite', $_POST['capacite'], PDO::PARAM_STR);
		$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);


		if($resultat -> execute()){ // Si la requête s'est bien déroulée
			// on enregistre le fichier photo dans son emplacement définitif
			if (!empty($_FILES['photo']['name'])) {
				copy($_FILES['photo']['tmp_name'], $chemin_photo);
				// copy() permet de copier/coller un fichier. En l'occurence ici, on copie le fichier photo de son emplacement temporaire vers son emplacement définitif
			}	
			header('location:gestion_salles.php');

		} //fin de if($resultat -> execute())
	} // fin de if (empty($msg))
} // Fin du if($_POST)

// SUPPRIMER UNE SALLE ***********************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
	if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {

		// vérifier dans la BDD si ce produit existe bien (SELECT)
		$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		if ($resultat -> rowCount() > 0) { 

			$salle = $resultat -> fetch();

			// si on l'a trouvé dans la BDD, alors on peut faire la requête pour le supprimer
			$resultat = $pdo -> exec("DELETE FROM salle WHERE id_salle = $salle[id_salle]");

			if ($resultat !== FALSE) { // si la requete a fonctionné :
				// supprimer de notre serveur la photo (unlink('chemin absolu du fichier à supprimer')) 
				$chemin_photo_a_supprimer = RACINE_SERVEUR . RACINE_SITE . 'photos/' . $salle['photo'];

				if (file_exists($chemin_photo_a_supprimer) && $salle['photo'] != 'default.jpg') { // si le fichier existe dans notre serveur et ce n'est pas la photo par defaut... on le supprime
					unlink($chemin_photo_a_supprimer);
				}
				header('location:' . RACINE_SITE . 'backoffice/gestion_salles.php');
			}	
		}
		else{
			header('location:' . RACINE_SITE . 'backoffice/gestion_salles.php');
		}
	} //fin du if (isset($_GET['id'])
	header('location:' . RACINE_SITE . 'backoffice/gestion_salles.php');

} // fin du if (isset($_GET['action'])


// MODIFIER UNE SALLE ************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier') {

	// on commence par récupérer les infos du produit à modifier
	if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){ // S'il y a un id_salle dans l'url, non vide et de type numérique...
		$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){
			$salle_actuelle = $resultat -> fetch();

			debug($salle_actuelle);
		}
	}
}
// Si la salle actuelle existe alors on crée des variables pour stocker les infos de cette salle. Si elle n'existe pas, on crée tout de même les variables, vides, afin de les afficher quoi qu'il arrive dans les attributs value de notre formulaire.
$id_salle = (isset($salle_actuelle)) ? $salle_actuelle['id_salle'] : '';

$titre = (isset($salle_actuelle)) ? $salle_actuelle['titre'] : '';
$description = (isset($salle_actuelle)) ? $salle_actuelle['description'] : '';
$photo = (isset($salle_actuelle)) ? $salle_actuelle['photo'] : '';
$pays = (isset($salle_actuelle)) ? $salle_actuelle['pays'] : '';
$ville = (isset($salle_actuelle)) ? $salle_actuelle['ville'] : '';
$adresse = (isset($salle_actuelle)) ? $salle_actuelle['adresse'] : '';
$cp = (isset($salle_actuelle)) ? $salle_actuelle['cp'] : '';
$capacite = (isset($salle_actuelle)) ? $salle_actuelle['capacite'] : '';
$categorie = (isset($salle_actuelle)) ? $salle_actuelle['categorie'] : '';

// On définit une variable $action qui va stocker soit 'modifier' soit 'ajouter' afin de rendre dynamique le h1 et le bouton du formulaire.
$action = (isset($salle_actuelle)) ? 'Modifier' : 'Ajouter';

$page = 'Gestion des salles';
require_once('../inc/header.inc.php');
?>

<h1><?= $page ?></h1>
<?= $contenu; ?><br>

<?= $msg ?>

<!-- FORMULAIRE -->
<div class="row">
	<div class="col-lg-6">
		<form method="post" action="#" enctype="multipart/form-data">

			<input type="hidden" name="id_salle" value="<?= $id_salle ?>">
			<!-- Le champs ci-dessus permet de transmettre à notre requête REPLACE l'id de la salle en cours de modification -->

			<div class="form-group">
				<label for="titre">Titre</label>
				<input class="form-control" type="text" name="titre" id="titre" placeholder="titre de la salle" value="<?= $titre ?>" />
			</div>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" name="description" id="description" placeholder="Description de la salle" ><?= $description ?></textarea>
			</div>

			<?php
			if (isset($salle_actuelle)) { // si nous sommes en train de modifier une salle
				echo '<input type="hidden" name="photo_actuelle" value="' . $photo . '">'; 
				echo '<img src="' . RACINE_SITE . 'photos/' . $photo . '" width="75">';
			}
			?>

			<div class="form-group">
				<label for="photo">Photo</label>
				<input type="file" name="photo" id="photo" />
			</div>

			<div class="form-group">
				<label for="capacite">Capacité</label>
				<input class="form-control" id="capacite" name="capacite" value="<?= $capacite ?>" >
			</div>
				
			<div class="form-group">
				<label for="categorie">Catégorie</label>
				<select class="form-control" id="categorie" name="categorie" value="<?= $categorie ?>">
					<option disabled selected>Choisissez</option>
					<option <?php if($categorie == 'reunion') {echo 'selected';} ?> value="reunion">Réunion</option>
					<option <?php if($categorie == 'formation') {echo 'selected';} ?> value="formation">Formation</option>
					<option <?php if($categorie == 'bureau') {echo 'selected';} ?> value="bureau">Bureau</option>
				</select>
			</div>

		
	</div> <!-- fin de la colonne de gauche -->

	<div class="col-lg-6 ">
		

			<div class="form-group" >
				<label for="pays">Pays</label>
				<select class="form-control" id="pays" name="pays" value="<?= $pays ?>">
					<option disabled selected>Choisissez</option>
					<option <?php if($pays == 'fr') {echo 'selected';} ?> value="fr">France</option>
					<option <?php if($pays == 'pt') {echo 'selected';} ?> value="pt">Portugal</option>
					<option <?php if($pays == 'pf') {echo 'selected';} ?> value="pf">Tahiti</option>
				</select>
			</div>
			
			<div class="form-group">
				<label for="ville">Ville</label>
				<select class="form-control" name="ville" id="ville"  value="<?= $ville ?>">
					<option disabled selected>Choisissez</option>
					<option <?php if($ville == 'paris') {echo 'selected';} ?> value="paris">Paris</option>
					<option <?php if($ville == 'lyon') {echo 'selected';} ?> value="lyon">Lyon</option>
					<option <?php if($ville == 'marseille') {echo 'selected';} ?> value="marseille">Marseille</option>
					<option <?php if($ville == 'papeete') {echo 'selected';} ?> value="papeete">Papeete</option>
					<option <?php if($ville == 'lisbonne') {echo 'selected';} ?> value="lisbonne">Lisbonne</option>
				</select>
			</div>
	
			<div class="form-group">
				<label for="adresse">Adresse</label>
				<textarea class="form-control" name="adresse" id="adresse" placeholder="Adresse de la salle" ><?= $adresse ?></textarea>
			</div>

			<div class="form-group">
				<label for="cp">Code postal</label>
				<input class="form-control" type="text" name="cp" id="cp" placeholder="Code postal de la salle" value="<?= $cp ?>">
			</div><br>

			<div>
				<input type="submit" value="<?= $action ?>" />
			</div>

		</form> <!-- fin du formulaire de droite -->
	</div> <!-- fin de la colonne de droite -->
</div> <!-- fin de la row -->


<?php 
require('../inc/footer.inc.php');
?>