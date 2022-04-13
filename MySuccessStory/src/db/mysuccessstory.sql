-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 13 avr. 2022 à 15:40
-- Version du serveur :  10.3.32-MariaDB
-- Version de PHP : 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mysuccessstory`
--
CREATE DATABASE IF NOT EXISTS `mysuccessstory` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mysuccessstory`;

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`mysuccessstory`@`%` PROCEDURE `updateUser` (IN `idUser` INT, IN `pwd` VARCHAR(128), IN `passwordHash` VARCHAR(128), IN `salt` INT, IN `firstName` VARCHAR(30), IN `lastName` VARCHAR(50), IN `entryYear` INT, IN `exitYear` INT)  NO SQL
BEGIN
			IF (pwd = "") THEN
				UPDATE `user` SET `firstName` = firstName, `lastName` = lastName, `entryYear` = entryYear, `exitYear` = exitYear  WHERE `idUser` = idUser;
			ELSE
				UPDATE `user` SET `password` = pwd, `salt` = salt, `firstName` = firstName, `lastName` = lastName, `entryYear` = entryYear, `exitYear` = exitYear  WHERE `idUser` = idUser;
			END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
	`idNote` int(10) UNSIGNED NOT NULL,
	`note` float UNSIGNED NOT NULL DEFAULT 4,
	`semester` tinyint(4) UNSIGNED NOT NULL DEFAULT 1,
	`year` int(10) UNSIGNED NOT NULL DEFAULT 1,
	`idUser` int(10) UNSIGNED NOT NULL,
	`idSubject` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`idNote`, `note`, `semester`, `year`, `idUser`, `idSubject`) VALUES
(159, 2, 1, 1, 1, 38),
(160, 2, 1, 1, 1, 8),
(161, 1, 1, 2, 1, 7),
(162, 5, 2, 1, 9, 1),
(163, 5, 2, 1, 9, 1),
(164, 5, 2, 1, 9, 1),
(165, 5, 2, 1, 9, 1),
(166, 5, 2, 2, 9, 3),
(167, 4, 1, 1, 1, 37),
(168, 5, 1, 1, 1, 37),
(169, 4, 1, 2, 1, 36),
(170, 1, 1, 1, 1, 35),
(171, 1, 1, 1, 1, 34);

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
	`idSubject` int(10) UNSIGNED NOT NULL,
	`name` varchar(30) NOT NULL,
	`description` varchar(200) NOT NULL,
	`isCIE` tinyint(1) UNSIGNED NOT NULL,
	`category` enum('CG','CFC','MATU') NOT NULL,
	`year` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`idSubject`, `name`, `description`, `isCIE`, `category`, `year`) VALUES
(1, 'M100', 'Distinguer, préparer et évaluer des données', 0, 'CFC', 1),
(2, 'M114', 'Mettre en oeuvre des systèmes de codification, de compression et d\'encryptage', 0, 'CFC', 1),
(3, 'M304', 'Installer et configurer un ordinateur mono-poste', 1, 'CFC', 1),
(4, 'M305', 'Installer, configurer et administrer un système d’exploitation', 1, 'CFC', 1),
(5, 'M403', 'Implémenter de manière procédurale des déroulements de programme', 0, 'CFC', 1),
(6, 'M404', 'Programmer orienté objets selon directives', 0, 'CFC', 1),
(7, 'M101', 'Réaliser et publier un site Web', 1, 'CFC', 1),
(8, 'M104', 'Implémenter un modèle de données', 0, 'CFC', 1),
(9, 'M117', 'Mettre en place l’infrastructure informatique d\'une petite entreprise', 0, 'CFC', 1),
(10, 'M123', 'Activer les services d’un serveur', 0, 'CFC', 1),
(11, 'M307', 'Réaliser des pages Web interactives', 1, 'CFC', 1),
(12, 'M431', 'Exécuter des mandats de manière autonome dans un environnement informatique', 0, 'CFC', 1),
(13, 'M105', 'Traiter une base de données avec SQL', 1, 'CFC', 2),
(14, 'M122', 'Automatiser des procédures à l’aide de scripts', 0, 'CFC', 2),
(15, 'M426', 'Développer un logiciel avec des méthodes agiles', 0, 'CFC', 2),
(16, 'M411', 'Développer et appliquer des structures de données et algorithmes', 0, 'CFC', 2),
(17, 'M318', 'Analyser et programmer orienté objet avec des composants', 1, 'CFC', 2),
(18, 'M133', 'Réaliser des applications Web en Session-Handling', 0, 'CFC', 2),
(19, 'M151', 'Intégrer des bases de données dans des applications Web', 0, 'CFC', 2),
(20, 'M153', 'Développer les modèles de données', 0, 'CFC', 2),
(21, 'M213', 'Développer l\'esprit d\'équipe', 0, 'CFC', 2),
(22, 'M335', 'Réaliser une application pour mobile', 1, 'CFC', 2),
(23, 'EE', 'Ecole-Entreprise', 0, 'CFC', 3),
(24, 'M121', 'Elaborer des tâches de pilotage', 0, 'CFC', 4),
(25, 'M183', 'Implémenter la sécurité d’une application', 0, 'CFC', 4),
(26, 'M226A-B', 'Implémenter orienté objets', 0, 'CFC', 4),
(27, 'M242', 'Réaliser des applications pour microprocesseurs', 0, 'CFC', 4),
(28, 'M150', 'Adapter une application de commerce électronique', 0, 'CFC', 4),
(29, 'M151', 'Intégrer des bases de données dans des applications Web', 0, 'CFC', 4),
(30, 'M306', 'Réaliser un petit projet informatique', 0, 'CFC', 4),
(31, 'M326', 'Développer et implémenter orienté objets', 0, 'CFC', 4),
(32, 'M152', 'Intégrer des contenus multimédia dans des applications Web', 0, 'CFC', 4),
(33, 'CG-Langue & Communication', '', 0, 'CG', 0),
(34, 'CG-Société', '', 0, 'CG', 0),
(35, 'Anglais', '', 0, 'CG', 0),
(36, 'Economie', '', 0, 'CG', 0),
(37, 'Mathématique', '', 0, 'CG', 0),
(38, 'Physique', '', 0, 'CG', 0),
(39, 'Education Physique', '', 0, 'CG', 0);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE `token` (
	`idUser` int(10) UNSIGNED NOT NULL,
	`token` varchar(100) NOT NULL,
	`expiration` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `token`
--

INSERT INTO `token` (`idUser`, `token`, `expiration`) VALUES
(1, 'cVLYIsQooYtkbHboBcneMw', 1649863719),
(9, 'cGgWva5lVA5338p9SZmyfw', 1649863708),
(16, 'Vak54laE8t3S6Nl2-tDJyA', 1649854656),
(17, 'GX9h9QqoxQcOP8NmRD2eAg', 1649854917);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
	`idUser` int(10) UNSIGNED NOT NULL,
	`email` varchar(50) NOT NULL,
	`password` varchar(100) NOT NULL,
	`firstName` varchar(50) NOT NULL,
	`lastName` varchar(50) NOT NULL DEFAULT '',
	`isTeacher` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `email`, `password`, `firstName`, `lastName`, `isTeacher`) VALUES
(1, 'lucasalexandre.almeidacosta@gmail.com', '$2y$10$R3gcHxfRxqnpPhjS2Da./u188Eevz12UWdjdNgeqklvlxBREHGpL.', 'Lucas', 'Almeida Costa', 0),
(7, 'remy.bd@eduge.ch', '$2y$10$SKBOvFTKum32mUR7aTcoy.gAOL6PtSDCpFulcZ000x4VwHNhF8jEy', 'Rémy', 'Beaud', 0),
(9, 'admin@eduge.ch', '$2y$10$p1DiFs/flFY3O7E4dAaMQeJRS173IWMgdYrEo4iu.qeJ4IV8GkvyS', 'Admin', '', 1),
(14, 'ekoue-jordan.fllsd@eduge.ch', '$2y$10$cVOkV8Tw1dfP6/WKmJkJ2OuLzWUzwn.CDHrDqiMyczMaiZFjKZWDK', 'Jordan', 'Folly', 0),
(16, 'jordan@eduge.ch', '$2y$10$WsS.o7V1DFY6tzkmxgRGxe.TlxF3rWMrnfGpd.fC5XTkmDsOOmadq', 'Jordan', 'Folly', 0),
(17, 'ttt@tt.com', '$2y$10$GixLsCC2tbFoVpI3px9qh.LRR.Uja3OcYxM01QT2tS2xNoml.h8Qm', 'prenom', 'nouveau nom', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
	ADD PRIMARY KEY (`idNote`),
	ADD KEY `idUser` (`idUser`),
	ADD KEY `idSubject` (`idSubject`);

--
-- Index pour la table `subjects`
--
ALTER TABLE `subjects`
	ADD PRIMARY KEY (`idSubject`);

--
-- Index pour la table `token`
--
ALTER TABLE `token`
	ADD PRIMARY KEY (`idUser`),
	ADD UNIQUE KEY `token` (`token`),
	ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
	ADD PRIMARY KEY (`idUser`),
	ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
	MODIFY `idNote` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
	MODIFY `idSubject` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
	MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
	ADD CONSTRAINT `notes_ibfk_3` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
	ADD CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
