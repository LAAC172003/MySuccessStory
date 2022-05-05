-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 05 mai 2022 à 16:36
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
  `isFake` tinyint(1) NOT NULL DEFAULT 0,
  `idUser` int(10) UNSIGNED NOT NULL,
  `idSubject` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
  `idSubject` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `isCIE` tinyint(1) UNSIGNED NOT NULL,
  `category` enum('CG','CFC','MATU') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`idSubject`, `name`, `description`, `isCIE`, `category`) VALUES
(1, 'M100', 'bgrfswb', 0, 'CFC'),
(2, 'M114', 'Mettre en oeuvre des systèmes de codification, de compression et d\'encryptage', 0, 'CFC'),
(3, 'M304', 'Installer et configurer un ordinateur mono-poste', 1, 'CFC'),
(4, 'M305', 'Installer, configurer et administrer un système d’exploitation', 1, 'CFC'),
(5, 'M403', 'Implémenter de manière procédurale des déroulements de programme', 0, 'CFC'),
(6, 'M404', 'Programmer orienté objets selon directives', 0, 'CFC'),
(7, 'M101', 'Réaliser et publier un site Web', 1, 'CFC'),
(8, 'M104', 'Implémenter un modèle de données', 0, 'CFC'),
(9, 'M117', 'Mettre en place l’infrastructure informatique d\'une petite entreprise', 0, 'CFC'),
(10, 'M123', 'Activer les services d’un serveur', 0, 'CFC'),
(11, 'M307', 'Réaliser des pages Web interactives', 1, 'CFC'),
(12, 'M431', 'Exécuter des mandats de manière autonome dans un environnement informatique', 0, 'CFC'),
(13, 'M105', 'Traiter une base de données avec SQL', 1, 'CFC'),
(14, 'M122', 'Automatiser des procédures à l’aide de scripts', 0, 'CFC'),
(15, 'M426', 'Développer un logiciel avec des méthodes agiles', 0, 'CFC'),
(16, 'M411', 'Développer et appliquer des structures de données et algorithmes', 0, 'CFC'),
(17, 'M318', 'Analyser et programmer orienté objet avec des composants', 1, 'CFC'),
(18, 'M133', 'Réaliser des applications Web en Session-Handling', 0, 'CFC'),
(19, 'M151', 'Intégrer des bases de données dans des applications Web', 0, 'CFC'),
(20, 'M153', 'Développer les modèles de données', 0, 'CFC'),
(21, 'M213', 'Développer l\'esprit d\'équipe', 0, 'CFC'),
(22, 'M335', 'Réaliser une application pour mobile', 1, 'CFC'),
(23, 'EE', 'Ecole-Entreprise', 0, 'CFC'),
(24, 'M121', 'Elaborer des tâches de pilotage', 0, 'CFC'),
(25, 'M183', 'Implémenter la sécurité d’une application', 0, 'CFC'),
(26, 'M226-A', 'Implémenter orienté objets', 0, 'CFC'),
(27, 'M242', 'Réaliser des applications pour microprocesseurs', 0, 'CFC'),
(28, 'M150', 'Adapter une application de commerce électronique', 0, 'CFC'),
(29, 'M151', 'Intégrer des bases de données dans des applications Web', 0, 'CFC'),
(30, 'M306', 'Réaliser un petit projet informatique', 0, 'CFC'),
(31, 'M326', 'Développer et implémenter orienté objets', 0, 'CFC'),
(32, 'M152', 'Intégrer des contenus multimédia dans des applications Web', 0, 'CFC'),
(33, 'LangueCommunication', '', 0, 'CG'),
(34, 'Societe', '', 0, 'CG'),
(35, 'Anglais', '', 0, 'CG'),
(36, 'Economie', '', 0, 'CG'),
(37, 'Mathematique', '', 0, 'CG'),
(38, 'Physique', '', 0, 'CG'),
(39, 'EducationPhysique', 'desc', 0, 'CG'),
(62, 'M226-B', 'Implémenter orienté objets', 0, 'CFC');

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE `token` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiration` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `idNote` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `idSubject` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`idSubject`) REFERENCES `subjects` (`idSubject`) ON DELETE CASCADE ON UPDATE CASCADE,
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
