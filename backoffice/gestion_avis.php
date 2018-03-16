<?php
require_once('../inc/init.inc.php');

$resultat = $pdo -> query("SELECT * FROM avis");
$salle = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$tab['resultat'] = '';

$tab['resultat'] = '<div class="table-responsive">';
$tab['resultat'] .= '<table class="table text-center">';
$tab['resultat'] .= '<tr>';

for($i=0; $i<$resultat -> columnCount(); $i ++){// la boucle va tourner autant de fois qu'on a de champs dans la table
	$champs = $resultat -> getColumnMeta($i);
	$tab['resultat'] .= '<th class="text-center">' . $champs['name'] . '</th>';
}

$tab['resultat'] .= '</tr>';

foreach($salle as $value){
	$tab['resultat'] .= '<tr>';

	foreach($value as $value2){
		$tab['resultat'] .= '<td>' . $value2 . '</td>';
	}

	$tab['resultat'] .= '</tr>';
}

$tab['resultat'] .= '</table>';
$tab['resultat'] .= '</div>';

$page = 'Gestion des avis';
require_once('../inc/header.inc.php');
?>

<h1><?= $page ?></h1>

<?= $tab['resultat']; ?><br>


<?php  			
require_once('../inc/footer.inc.php');			
?>	