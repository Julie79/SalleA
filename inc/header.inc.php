<!DOCTYPE html>
<html>
	<head>
		<title>SalleA - <?= $page ?></title>
		<meta charset="utf-8">
		
		<!-- BOOTSTRAP CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- CSS -->
		<link href="<?= RACINE_SITE ?>css/style.css" rel="stylesheet">
		<link href="css/fiche-produit.css" rel="stylesheet">
		<!-- FONTAWSOME -->
		<link rel="stylesheet" href="../css/css/font-awesome.min.css">
	</head>
	
	<body>
		<!-- Navigation -->
    	<nav class="navbar navbar-default">
  			<div class="container">

		      	<!-- brand -->
		        <div class="navbar-header">
					<a class="navbar-brand" href="<?= RACINE_SITE ?>accueil.php">SalleA</a>
				</div>

		        <!-- Links -->
		        <div class="collapse navbar-collapse" id="navbar-collapse">
						
	        		<!-- links gauche -->
					<ul class="nav navbar-nav">
        				<li class="active"><a href="<?= RACINE_SITE ?>presentation.php">Qui sommes-nous ?</a></li>
        				<li><a href="<?= RACINE_SITE ?>contact.php">Contact</a></li> 
					</ul>

					<!-- dropdown admin -->
					<!-- On vérifie si l'user est l'admin -->
					<?php if(userAdmin()) : ?>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Espace Admin <span class="caret"></span></a>
							<ul class="dropdown-menu">
							
								<li><a href="<?= RACINE_SITE ?>/backoffice/gestion_avis.php">Gestion Avis</a></li>
								<li><a href="<?= RACINE_SITE ?>/backoffice/gestion_commandes.php">Gestion Commandes</a></li>
								<li><a href="<?= RACINE_SITE ?>/backoffice/gestion_membres.php">Gestion membres</a></li>
								<li><a href="<?= RACINE_SITE ?>/backoffice/gestion_produits.php">Gestion produits</a></li>
								<li><a href="<?= RACINE_SITE ?>/backoffice/gestion_salles.php">Gestion salles</a></li>
								<li><a href="<?= RACINE_SITE ?>/backoffice/statistiques.php">Statistiques</a></li>
							
							</ul>
						</li>
					</ul>
					<?php endif; ?>

		          	<!-- dropdown membre -->
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Espace Membres <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<!-- On vérifie si l'utilisateur est connecté -->
								<?php if(userConnect()) : ?>
									<!-- oui il l'est -->						
									<li><a href="<?= RACINE_SITE ?>profil.php">Profil</a></li>
									<li><a href="<?= RACINE_SITE ?>deconnexion.php">Déconnexion</a></li>

								<?php else : ?>
									<!-- non il ne l'est pas -->
									<li><a href="<?= RACINE_SITE ?>connexion.php">Connexion</a></li>
									<li><a href="<?= RACINE_SITE ?>inscription.php">Inscription</a></li>

								<?php endif; ?>
							</ul>
						</li>
					</ul>

	        	</div> <!-- fin de navbar collapse -->
    		</div> <!-- fin de container -->
    	</nav> <!-- fin de la nav -->

		<!-- Page Content -->
		<section>
	    	<div class="container">