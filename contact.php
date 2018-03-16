<?php
require_once('inc/init.inc.php');



$page = 'Contact';
require_once('inc/header.inc.php');
?>

<h1>Contactez-nous !</h1>

<form method="post" action="">
	<div class="form-group">
		<input class="form-control" type="text" name="nom" id="nom" placeholder="Votre Nom">
	</div>

	<div class="form-group">
		<input class="form-control" type="text" name="prenom" id="prenom" placeholder="Votre Prom">
	</div>

	<div class="form-group">
		<textarea class="form-control" name="message" id="message" placeholder="Votre message"></textarea> 
	</div>

	<input type="submit" name="submit" id="submit" value="Envoyer">
</form>



<?php
require('inc/footer.inc.php');
?>