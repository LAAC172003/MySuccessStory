-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 nov. 2021 à 12:17
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `idCategory` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `idClass` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`idClass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `idNote` int(10) NOT NULL AUTO_INCREMENT,
  `note` int(50) NOT NULL,
  `idUser` int(10) NOT NULL,
  `idSubject` int(10) NOT NULL,
  `idPeriod` int(10) NOT NULL,
  PRIMARY KEY (`idNote`),
  KEY `idUser` (`idUser`),
  KEY `idSubject` (`idSubject`),
  KEY `idPeriod` (`idPeriod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `period`
--

DROP TABLE IF EXISTS `period`;
CREATE TABLE IF NOT EXISTS `period` (
  `idPeriod` int(10) NOT NULL AUTO_INCREMENT,
  `year` int(50) NOT NULL,
  `semester` varchar(100) NOT NULL,
  PRIMARY KEY (`idPeriod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `idSubject` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `idCategory` int(10) NOT NULL,
  PRIMARY KEY (`idSubject`),
  KEY `idCategory` (`idCategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `year` int(50) NOT NULL,
  `type` varchar(100) NOT NULL,
  `idClass` int(10) NOT NULL,
  PRIMARY KEY (`iduser`),
  KEY `idClass` (`idClass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`idSubject`) REFERENCES `subject` (`idSubject`),
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`idPeriod`) REFERENCES `period` (`idPeriod`);

--
-- Contraintes pour la table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `category` (`idCategory`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`idClass`) REFERENCES `class` (`idClass`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
