-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 24 jan. 2018 à 13:11
-- Version du serveur :  10.1.22-MariaDB
-- Version de PHP :  7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sallea`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(3) NOT NULL,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` varchar(20) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'admin', 'mdp1', 'Thoyer', 'Marie', 'marie.thoyer@gmail.com', '', 'admin', '2016-06-06 14:45:00'),
(2, 'joker', 'mdp2', 'Cottet', 'Julien', 'juju70@gmail.com', '', 'membre', '2016-06-06 20:30:00'),
(3, 'camelus', 'mdp3', 'Miller', 'Guillaume', 'guillaume-miller@gmail.com', 'm', 'membre', '2016-09-06 18:30:00'),
(4, 'chouchou', 'mdp4', 'Dupont', 'Thomas', 'thomas28@gmail.com', 'm', 'membre', '2017-01-10 16:30:00'),
(5, 'moon', 'mdp5', 'Santos', 'Carlota', 'carlota-santos@gmail.com', 'f', 'membre', '2017-04-10 10:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(1, 1, '2017-11-22 09:00:00', '2017-11-27 19:00:00', 2000, 'libre'),
(2, 2, '2017-11-29 00:00:00', '2017-12-03 00:00:00', 755, 'libre'),
(3, 3, '2017-12-05 00:00:00', '2018-03-10 00:00:00', 3600, 'libre'),
(4, 4, '2018-03-22 00:00:00', '2018-03-29 00:00:00', 990, 'libre'),
(5, 5, '2018-04-02 00:00:00', '2018-04-14 00:00:00', 4990, 'libre'),
(6, 1, '2018-01-25 00:00:00', '2018-01-27 00:00:00', 650, 'libre'),
(7, 2, '2018-01-28 00:00:00', '2018-01-31 00:00:00', 1400, 'libre'),
(8, 3, '2018-01-25 00:00:00', '2018-01-29 00:00:00', 800, 'libre'),
(9, 4, '2018-02-05 00:00:00', '2018-02-09 00:00:00', 2100, 'libre'),
(10, 5, '2018-02-15 00:00:00', '2018-01-20 00:00:00', 2500, 'libre'),
(11, 6, '2018-01-30 00:00:00', '2018-02-02 00:00:00', 940, 'libre'),
(12, 7, '2018-02-01 00:00:00', '2018-02-04 00:00:00', 1500, 'libre'),
(13, 8, '2018-02-25 00:00:00', '2018-02-28 00:00:00', 2600, 'libre'),
(14, 9, '2018-03-14 00:00:00', '2018-03-17 00:00:00', 1800, 'libre'),
(15, 10, '2018-03-12 00:00:00', '2018-03-14 00:00:00', 450, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(3) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('réunion','bureau','formation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(1, 'Cézanne', 'Cette salle sera parfaite pour vos réunions d\'entreprise.', 'salle-cezanne.jpg', 'Tahiti', 'Papeete', '25 rue du paradis', 98714, 25, 'réunion'),
(2, 'backer', 'Cette salle vous permettra de recevoir vos collaborateurs en petit comité dans une ambiance décontractée.', 'salle-backer.jpg', 'France', 'Lyon', '17 rue mademoiselle', 69006, 11, 'bureau'),
(3, 'Picasso', 'Cette salle spacieuse et calme, parfaite pour les formations diverses.\r\n', 'salle-picasso.jpg', 'France', 'Paris', '17 rue de turbigo', 75002, 14, 'formation'),
(4, 'salle New York', 'Salle moderne élégante, épurée, parfaite\r\npour travailler dans le calme.', 'salle-new-york.jpg', 'France', 'Marseille', '34 rue de la gare', 13005, 5, 'bureau'),
(5, 'Salle Moderne', 'salle de réunion parfaite pour les grandes entreprises.', 'salle-moderne.jpg', 'Portugal', 'Lisbonne', '2 rue de lisbonne', 11001, 32, 'réunion'),
(6, 'David', 'Petite salle cosy et confortable', 'salle-david.jpg', 'France', 'Paris', '110 bd de charonne', 75011, 11, 'bureau'),
(7, 'Donatello', 'Salle stylée, idéale pour des réunion de travail', 'salle-donatello.jpg', 'France', 'Lyon', '25 Rue Joliot Curie', 69000, 26, 'réunion'),
(8, 'Michelange', 'Salle de grande capacité pour des réunion au sommet', 'salle-michelange.jpg', 'France', 'Marseille', '36 Avenue de Saint-Julien', 13000, 36, 'bureau'),
(9, 'Monet', 'Petite salle de cours axée sur l\'écoute et le partage', 'salle-monet.jpg', 'France', 'Paris', '135 rue du faubourg saint antoine', 75011, 28, 'formation'),
(10, 'Rembrandt', 'Salle de bonne taille qui vous permettra de l\'agencer à votre guise', 'salle-rembrandt.jpg', 'France', 'Lyon', '18 Quai Arloing', 69000, 35, 'formation');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_membre` (`id_membre`,`id_salle`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_membre` (`id_membre`,`id_produit`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
