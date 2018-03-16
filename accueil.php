<?php
require_once('inc/init.inc.php');


// Récupérer les catégories pour afficher dans le menu de gauche
$resultat = $pdo -> query("SELECT DISTINCT categorie FROM salle ");
$categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);

// Récupérer les villes pour afficher dans le menu de gauche
$resu
ltat = $pdo -> query("SELECT DISTINCT ville FROM salle ");
$ville = $resultat -> fetchAll(PDO::FETCH_ASSOC);


$nombreSalle = '';


//Traitements pour rendre dynamique le menu de gauche
/* if(isset($_GET['categorie']) && !empty($_GET['categorie']) ){
	$resultat = $pdo -> prepare("SELECT * FROM salle WHERE categorie = :categorie");
	$resultat -> bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
	$resultat -> execute();
	
	if ($resultat -> rowCount() == 0) {	//si qq'un s'amuse à taper categorie=toto dans l'url alors on le redirige vers la page 
		header('location:accueil.php');
	}
} */
if(isset($_GET['ville']) && !empty($_GET['ville']) ){
	$resultat = $pdo -> prepare("SELECT * FROM salle WHERE ville = :ville");
	$resultat -> bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
	$resultat -> execute();
	
	if ($resultat -> rowCount() == 0) {	
		header('location:accueil.php');
	}
}
if($_POST){
	debug($_POST);
}
else{ // faire une requête pour récupérer toutes les infos des salles
	// Récupérer toutes les données des tables produit ET salle combinées, pour afficher dans les vignettes, sachant que l'état doit être 'libre' et qu'on les range par ordre croissant de date d'arrivée.
	$resultat = $pdo -> query("SELECT p.*, s.* FROM produit p, salle s WHERE p.id_salle = s.id_salle AND p.etat = 'libre' ORDER BY p.date_arrivee ASC");
	$salle = $resultat -> fetchAll(PDO::FETCH_ASSOC);
}



$page = 'Accueil'; // affiche l'onglet de manière dynamique, à faire sur toutes les pages
require_once('inc/header.inc.php');
?>

<!-- DEBUT DE l'HTML -->
<section id="accueil">
  <div class="row">

    <div class="col-lg-3 col-sm-3 col-gauche">

      <form method="post" action="">

        <label class="">Categorie</label>
        <div class="list-group">
          <!-- faire une boucle pour afficher les catégories -->
          <?php foreach ($categorie as $key => $value) : ?>
          <a href="?categorie=<?= $value['categorie'] ?>" class="list-group-item"><?= $value['categorie'] ?></a>
          <?php endforeach; ?>
        </div>

        <label class="">Ville</label>
        <div class="list-group">
          <!-- faire une boucle pour afficher les villes -->
          <?php foreach ($ville as $key => $value) : ?>
          <a href="?ville=<?= $value['ville'] ?>" class="list-group-item"><?= $value['ville'] ?></a>
          <?php endforeach; ?>
        </div>
      
        <label>Capacité</label>
        <div class="form-group">
          <select class="form-control" name="capacite" id="capacite">
            <option  disabled selected >Choisissez</option>
            <option value="moinsde10">moins de 10 personnes</option>
            <option value="de10a20">de 10 à 20 personnes</option>
            <option value="de20a30">de 20 à 30 personnes</option>
            <option value="plusde30">plus de 30 personnes</option>
          </select>
        </div>

        <label>Prix</label>
        <div class="form-group">
          <input type="range" name="prix" id="prix">
        </div>

        <label class="">Période</label>
        <h3>Date d'arrivée</h3>
        <div class="form-group">
          <input class="form-control" type="date" name="date_arrivee">
        </div>

        <h3>Date de départ</h3>
        <div class="form-group">
          <input class="form-control" type="date" name="date_depart">
        </div>

      </form> 

    </div> <!-- Fin du col-lg-3 -->
        
    <!-- Debut des fiches salles -->
    <div class="col-lg-9 col-sm-9 col-droite">

      <!-- Début de la fiche d'une salle -->
      <?php 
      if (isset($_GET['p']) && !empty($_GET['p']) && $_GET['p'] == 'plus'){ // Si 'voir plus' a été activé...
        $nombreSalle = count($salle); // on affiche toutes les salles
      } 
      else{ 
        $nombreSalle = 6; // sinon on en affiche 6
      }
      ?> 
        <!-- On fait une boucle pour afficher les résultats -->
        <?php for ($i = 0; $i < $nombreSalle ; $i++) : ?>
        <?php extract($salle[$i]); ?>
        
        <!-- on affiche les salles libres -->
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img class="" src="<?= RACINE_SITE ?>photos/<?= $photo ?>" alt="">
            <div class="caption">
              <h3><?= $titre ?></h3>
              <h5><?= $prix ?> €</h5>
              <p class=""><?= substr($description, 0, 60).'...' ?></p> <!-- uniformise les descriptions à 60 caractères -->
              <p>du <?= substr($date_arrivee, 0, strpos($date_arrivee, ' ')) ?> au <?= substr($date_depart, 0, strpos($date_depart, ' ')) ?></p> <!-- coupe les dates pour enlever les heures -->
            </div>
            <div class="card-footer">
              <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
              <a href="fiche_produit.php?id=<?= $id_salle ?> ">voir</a>
            </div>
          </div> <!-- fin du thumbnail -->
        </div> <!-- fin des cols -->

      <?php endfor; ?>

      <!-- Si le nombre total de salles dépasse 6 on affiche le lien 'voir plus' -->
      <?php if(count($salle) > 6){
        echo '<a href="accueil.php?p=plus"><p class="text-center">voir plus</p></a>';
      } ?>

    </div><!-- /.col-lg-9 -->

  </div><!-- /.row -->

</section>

    
<?php
require('inc/footer.inc.php');
?>