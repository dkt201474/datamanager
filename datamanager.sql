-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mer 09 Septembre 2015 à 19:45
-- Version du serveur: 5.5.20
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `datamanager`
--

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expediteur` varchar(255) NOT NULL,
  `recepteur` varchar(255) NOT NULL,
  `objet` text NOT NULL,
  `description` text NOT NULL,
  `dateEnv` text NOT NULL,
  `dateRecep` text NOT NULL,
  `etat` int(11) NOT NULL,
  `adrPieceJointe` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `mail`
--

INSERT INTO `mail` (`id`, `expediteur`, `recepteur`, `objet`, `description`, `dateEnv`, `dateRecep`, `etat`, `adrPieceJointe`) VALUES
(13, 'jimo@gmail.com', 'tut@tut', 'cool', 'xa marhce', '2015-08-28 00:34:51', '', 0, ''),
(14, 'jimo@gmail.com', 'tut@tut', 'cool', 'xa marhce', '2015-08-28 00:35:18', '', 0, ''),
(15, 'dkt201474@gmail.com', 'tut@tut', '', 'ca marcheeeeeeeeeeeeeee', '2015-09-02 12:06:35', '', 0, ''),
(16, 'dkt201474@gmail.com', 'tut@tut', '', 'sssssssssss', '2015-09-02 12:07:26', '', 0, ''),
(17, 'tut@tut', 'dkt201474@gmail.com', '', 'ça marche ici aussi', '2015-09-06 12:45:01', '', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `memoires`
--

CREATE TABLE IF NOT EXISTS `memoires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(255) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` text NOT NULL,
  `adrMem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `memoires`
--

INSERT INTO `memoires` (`id`, `theme`, `auteur`, `description`, `email`, `adrMem`) VALUES
(1, 'dataManager', 'dkt201474@gmail.com', 'Conception et création d''une application web', 'dkt201474@gmail.com', 'memoires/9lfn_pdf-book-internet-et-intranet-sous-linux.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objet` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `motif` text NOT NULL,
  `dateDepart` date NOT NULL,
  `dateRetour` date NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `permissions`
--

INSERT INTO `permissions` (`id`, `objet`, `email`, `motif`, `dateDepart`, `dateRetour`, `statut`) VALUES
(13, 'Salutation de la famille', 'dkt201474@gmail.com', 'Ils le font rire trop cool. \r\nLa mif c''est tout ce qui compte', '2015-09-01', '2015-09-08', 2);

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire`
--

CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `universite` varchar(255) NOT NULL,
  `classe` int(11) NOT NULL,
  `filiaire` varchar(255) NOT NULL,
  `debutStage` date NOT NULL,
  `finStage` date NOT NULL,
  `statut` int(11) NOT NULL,
  `srcImgProfile` text NOT NULL,
  `mdp` text NOT NULL,
  `email` text NOT NULL,
  `noteStage` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `stagiaire`
--

INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `age`, `universite`, `classe`, `filiaire`, `debutStage`, `finStage`, `statut`, `srcImgProfile`, `mdp`, `email`, `noteStage`) VALUES
(18, 'Hovehissi', 'Jean-Max', 20, 'ESTB', 2, 'TI', '2015-08-26', '2015-09-05', 0, 'images_064.jpeg', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'jimo@gmail.com', 0),
(17, 'Yarou', 'Sadeck', 19, 'ESTB', 2, 'TI', '2015-07-27', '2015-09-28', 1, 'images_040.jpeg', '5383dfcb70a88a94a24454d2b4c19bcc6d3128c6', 'dkt201474@gmail.com', 0),
(24, 'Hazoulè', 'Yann', 21, 'Moncton', 3, 'RT', '2015-10-01', '2015-10-31', 1, 'images_016.png', 'bb8062e9d794e1f1288680e4039299cab78c737d', 'y@y', 0);

-- --------------------------------------------------------

--
-- Structure de la table `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `dateEnv` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `suggestions`
--

INSERT INTO `suggestions` (`id`, `nom`, `prenom`, `dateEnv`, `description`) VALUES
(12, 'Yarou', 'Sadeck', '2015-08-27 07:04:47', 'OUFFFFFFFFF pas trop tôt. Merde toi tu m''as fait galérer grâve.'),
(11, 'Yarou', 'Sadeck', '2015-08-27 06:50:03', 'ça s''améliore'),
(10, 'Yarou', 'Sadeck', '2015-08-27 06:06:24', 'OUFFFFFFFFF. Yéééhhh\r\nEnfin. J''ai fini avec suggestions.'),
(9, 'Hovehissi', 'Jean-Max', '2015-08-26 16:05:44', 'cool ça marche'),
(8, 'Hovehissi', 'Jean-Max', '2015-08-26 16:02:13', '"éééééé'),
(13, 'Hovehissi', 'Jean-Max', '2015-08-27 07:05:25', 'Félicitation Sadeck'),
(14, 'Hovehissi', 'Jean-Max', '2015-08-27 07:07:59', 'cool'),
(16, 'Yarou', 'Sadeck', '2015-09-02 11:24:31', 'colll'),
(17, 'Yarou', 'Sadeck', '2015-09-02 12:00:27', 'ma nouvelle sugestion');

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE IF NOT EXISTS `taches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `etat` int(11) NOT NULL,
  `heureDebut` tinytext NOT NULL,
  `heureFin` tinytext NOT NULL,
  `dateDebut` tinytext NOT NULL,
  `dateFin` tinytext NOT NULL,
  `auteur` text NOT NULL,
  `sujet` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `taches`
--

INSERT INTO `taches` (`id`, `titre`, `etat`, `heureDebut`, `heureFin`, `dateDebut`, `dateFin`, `auteur`, `sujet`, `description`) VALUES
(13, 'Sertissage de câble', 1, '3:49:31', '14:31:20', '09/09/2015', '09-09-2015', 'tut@tut', 'dkt201474@gmail.com', 'Des câbles à sertir pour le dslam');

-- --------------------------------------------------------

--
-- Structure de la table `tuteur`
--

CREATE TABLE IF NOT EXISTS `tuteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `mdp` text NOT NULL,
  `email` text NOT NULL,
  `srcImgProfile` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `tuteur`
--

INSERT INTO `tuteur` (`id`, `nom`, `prenom`, `age`, `profession`, `mdp`, `email`, `srcImgProfile`) VALUES
(2, 'Komlanvi', 'Jean', 37, 'Informatien', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'tut@tut', 'images_033.jpeg'),
(13, 'Super', 'Sadeck', 19, 'Maitre de conférence', '5383dfcb70a88a94a24454d2b4c19bcc6d3128c6', 'admin@admin', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
