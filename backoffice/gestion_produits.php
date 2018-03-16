<?php
require_once('../inc/init.inc.php');


// Traitements pour afficher un tableau dynamique basé sur les tables salle et produit
$resultat = $pdo -> query("SELECT p.*, s.* FROM produit p, salle s WHERE p.id_salle = s.id_salle");
$produit = $resultat -> fetchAll(PDO::FETCH_ASSOC);


$contenu = '<div class="table-responsive">';
$contenu .= '<table class="table">';
$contenu .= '<tr>';

$contenu .= '<th>id produit</th>';
$contenu .= '<th>date d\'arrivée</th>';
$contenu .= '<th>date de départ</th>';
$contenu .= '<th>id salle</th>';
$contenu .= '<th>prix</th>';
$contenu .= '<th>état</th>';
$contenu .= '<th>actions</th>';

$contenu .= '</tr>';

for ($i=0; $i < count($produit); $i++) { 
	
	$contenu .= '<tr>';
	
	$contenu .= '<td>' . $produit[$i]['id_produit'] . '</td>';
	$contenu .= '<td>' . $produit[$i]['date_arrivee'] . '</td>';
	$contenu .= '<td>' . $produit[$i]['date_depart'] . '</td>';
	$contenu .= '<td>' . $produit[$i]['id_salle'] . '-' . $produit[$i]['titre'] . '<br><img height="60" src="' . RACINE_SITE . 'photos/' . $produit[$i]['photo'] . '"></td>';
	$contenu .= '<td>' . $produit[$i]['prix'] . '</td>';
	$contenu .= '<td>' . $produit[$i]['etat'] . '</td>';
	
	$contenu .= '<td><a href="?action=modifier&id=' . $produit[$i]['id_produit'] . ' "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' . ' ' . '<a href="?action=supprimer&id=' . $produit[$i]['id_produit'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
	
	
	$contenu .= '</tr>';
}

$contenu .= '</table>';
$contenu .= '</div>';

// fin des traitements pour le tableau

// AJOUTER UN PRODUIT *************************************************************
if ($_POST) {
	debug($_POST);








}



$page = 'gestion produits';
require_once('../inc/header.inc.php');
?>
<h1><?= $page ?></h1>
<?= $contenu; ?><br>

<!-- FORMULAIRE -->
<div class="row">
	<div class="col-lg-6">
		<form method="post" action="#" enctype="multipart/form-data">
			
			<div class="form-group">
				<label for="datearrivee">Date d'arrivée</label>
				<input class="form-control" type="date" name="datearrivee" id="datearrivee">
			</div>
			
			<div class="form-group">
				<label for="datededepart">Date de départ</label>
				<input class="form-control" type="date" name="datededepart" id="datededepart">
			</div>

		
	</div> <!-- fin de la colonne de gauche -->

	<div class="col-lg-6 ">
		

			
			<div class="form-group">
				<label for="salle">Salle</label>
				<select class="form-control" name="salle" id="salle">
					<option disabled selected>Choisissez</option>
					<?php 
					$resultat = $pdo -> query("SELECT DISTINCT titre FROM salle ORDER BY titre ASC");
					$titre = $resultat -> fetchAll(PDO::FETCH_ASSOC);
					?>
					<?php foreach ($titre as $key => $value) : ?>
						<option><?= $value['titre'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			
		<div class="form-group">
			<label for="tarif">Tarif</label>
			<input type="text" class="form-control" id="tarif" name="tarif">
		</div>
			
			<div>
				<input type="submit" value="Enregistrer" />
			</div>

		</form> <!-- fin du formulaire de droite -->
	</div> <!-- fin de la colonne de droite -->
</div> <!-- fin de la row -->


<?php 
require('../inc/footer.inc.php');
?>