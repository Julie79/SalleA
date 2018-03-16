<?php
require_once('inc/init.inc.php');

// accessibilité : redirection si user déjà connecté
if(userConnect()){
    header('location:profil.php');
}


if($_POST){
    debug($_POST);
	extract($_POST);
	
    // Traitements de vérification des champs :
    if(!empty($_POST['pseudo']) || !empty($_POST['mdp']) || !empty($_POST['nom']) || !empty($_POST['prenom']) || !empty($_POST['email'])){ // Si les champs sont renseignés...

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

        // Le mail est-il déjà enregistré?
        $resultat = $pdo -> prepare("SELECT * FROM membre WHERE email = :email");
        $resultat -> bindParam(':email', $email, PDO::PARAM_STR);
        $resultat -> execute();

        if($resultat -> rowCount() > 0){ // Cela signifie que nous avons trouvé un enregistrement en BDD avec ce mail, l'utilisateur s'est donc déjà enregistré...
            $msg .= '<div class="bg-danger">Le mail ' . $mail . ' est déjà enregistré, avez-vous oublié votre mot de passe? <br> <button><a href="">mot de passe oublié</a></button> </div>';
        }

        else{ // pseudo et mail sont disponibles, on peut enregistrer l'utilisateur
            // INSERT + crypter le mot de passe
            $resultat = $pdo -> prepare("INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES(:pseudo, :mdp, :nom, :prenom, :email, :civilite, 'membre', curdate()) ");

            $mdp_crypte = md5($_POST['mdp']); // protocole de cryptage le moins élevé

				
    		$resultat -> bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    		$resultat -> bindParam(':mdp', $mdp_crypte, PDO::PARAM_STR);
    		$resultat -> bindParam(':nom', $nom, PDO::PARAM_STR);
    		$resultat -> bindParam(':prenom', $prenom, PDO::PARAM_STR);
    		$resultat -> bindParam(':email', $email, PDO::PARAM_STR);
    		$resultat -> bindParam(':civilite', $civilite, PDO::PARAM_STR);
    		
    		if($resultat -> execute()){
    			header('location:connexion.php');
    		}
        }
	}
} // Fin du if($_POST)

// Traitement pour récupérer les données saisies et les remettre dans le formulaire
$pseudo =       (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$prenom =       (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$nom =          (isset($_POST['nom'])) ? $_POST['nom'] : '';
$civilite =     (isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$email =        (isset($_POST['email'])) ? $_POST['email'] : '';

$page = 'Inscription';
require_once('inc/header.inc.php');
?>

<h1><?= $page ?></h1>
<form method="post" action="#" enctype="multipart/form-data">
    <div class="form-group">
        <label for="pseudo">Votre Pseudo</label>
        <input class="form-control" type="text" name="pseudo" id="pseudo" placeholder="votre pseudo" value="<?= $pseudo ?>"/>
    </div>
           
    <div class="form-group">
        <label for="mdp">Votre Mot de passe</label>
        <input class="form-control" type="password" name="mdp" id="mdp" placeholder="votre mot de passe" />
    </div>

        <div class="form-group">
        <label>Votre Nom</label>
        <input class="form-control" type="text" name="nom" id="nom" placeholder="votre nom" value="<?= $nom ?>"/>
    </div>
            
    <div class="form-group">
        <label>Votre Prénom</label>
        <input class="form-control" type="text" name="prenom" id="prenom" placeholder="votre prénom" value="<?= $prenom ?>"/>
    </div>

    <div class="form-group">
        <label  for="email">Votre Email</label>
        <input  class="form-control" type="email" name="email" id="mail" value="<?= $email ?>"/>
    </div>

    <div class="form-group">
        <select id="civilite" name="civilite">
            <option disabled selected>Choisissez</option>
            <option <?= ($civilite == 'm') ? 'selected' : '' ?> value="m">Homme</option>
            <option <?= ($civilite == 'f') ? 'selected' : '' ?> value="f">Femme</option>
        </select>
    </div>

    <input type="submit" id="submit" name="submit" value="S'enregistrer">
	
	<?= $msg ?>

</form>




<?php
require('inc/footer.inc.php');
?>