-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 22 Juillet 2017 à 19:34
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sitedusavoir`

CREATE DATABASE IF NOT EXISTS `sitedusavoir` DEFAULT CHARACTER SET utf8 COLLATE UTF8_general_ci;
USE `sitedusavoir`;
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `fromt` int(11) NOT NULL,
  `toA` int(11) NOT NULL,
  `confirm` enum('0','1') NOT NULL,
  `dateamitie` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

CREATE TABLE `auteurs` (
  `membre` int(11) NOT NULL,
  `idcontenu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `automessages`
--

CREATE TABLE `automessages` (
  `id` tinyint(3) NOT NULL,
  `message` text NOT NULL,
  `titre` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `ordre` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `nom` varchar(200) NOT NULL,
  `valeur` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `contenus`
--

CREATE TABLE `contenus` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `banniere` varchar(30) DEFAULT NULL,
  `introduction` text NOT NULL,
  `conclusion` text NOT NULL,
  `cat` int(11) NOT NULL,
  `publication` datetime NOT NULL,
  `validation` enum('0','1') DEFAULT NULL,
  `confirmation` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forums`
--

CREATE TABLE `forums` (
  `id` int(11) NOT NULL,
  `cat` mediumint(8) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `ordre` mediumint(8) DEFAULT NULL,
  `last_post_id` int(11) NOT NULL,
  `topics` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `auth_view` tinyint(4) NOT NULL DEFAULT '1',
  `auth_post` tinyint(4) NOT NULL DEFAULT '2',
  `auth_topic` tinyint(4) NOT NULL DEFAULT '2',
  `auth_annonce` tinyint(4) NOT NULL DEFAULT '3',
  `auth_modo` tinyint(4) NOT NULL DEFAULT '3'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `siteweb` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `signature` varchar(200) DEFAULT 'Pas de signature',
  `localisation` varchar(100) DEFAULT 'Non Localisé',
  `inscrit` datetime DEFAULT NULL,
  `visite` datetime DEFAULT NULL,
  `rang` int(11) NOT NULL DEFAULT '2',
  `posts` int(11) NOT NULL DEFAULT '0',
  `token` varchar(250) DEFAULT NULL,
  `reset` varchar(250) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `cookiee` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mp`
--

CREATE TABLE `mp` (
  `id` int(11) NOT NULL,
  `expediteur` int(11) NOT NULL,
  `receveur` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `texte` text NOT NULL,
  `mptime` datetime NOT NULL,
  `lu` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

CREATE TABLE `parties` (
  `id` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `texte` text NOT NULL,
  `idcontenu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `createur` int(11) NOT NULL,
  `texte` text NOT NULL,
  `posttime` datetime NOT NULL,
  `topic` int(11) NOT NULL,
  `forum` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `social_groupes`
--

CREATE TABLE `social_groupes` (
  `groupes_id` int(11) NOT NULL,
  `groupes_nom` varchar(50) NOT NULL,
  `groupes_banniere` varchar(30) DEFAULT NULL,
  `groupes_description` varchar(255) NOT NULL,
  `groupes_createur` int(11) NOT NULL,
  `groupes_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_admins`
--

CREATE TABLE `social_gs_admins` (
  `groupe` int(11) NOT NULL,
  `membre` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_membres`
--

CREATE TABLE `social_gs_membres` (
  `groupe` int(11) NOT NULL,
  `membre` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_statut`
--

CREATE TABLE `social_gs_statut` (
  `id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `photo` varchar(30) DEFAULT NULL,
  `membre` int(11) NOT NULL,
  `groupe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_statut`
--

CREATE TABLE `social_statut` (
  `id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `photo` varchar(20) DEFAULT NULL,
  `membre` int(11) NOT NULL,
  `publication` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_st_comment`
--

CREATE TABLE `social_st_comment` (
  `id` int(11) NOT NULL,
  `texte` text NOT NULL,
  `membre` int(11) NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `forum` int(11) NOT NULL,
  `titre` char(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `createur` int(11) NOT NULL,
  `vus` mediumint(8) NOT NULL DEFAULT '0',
  `topictime` datetime NOT NULL,
  `genre` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'Message',
  `last_post` int(11) NOT NULL DEFAULT '0',
  `first_post` int(11) NOT NULL DEFAULT '0',
  `posts` mediumint(8) NOT NULL DEFAULT '0',
  `locked` enum('0','1') NOT NULL,
  `resolu` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `topic_view`
--

CREATE TABLE `topic_view` (
  `tv_id` int(11) NOT NULL,
  `tv_topic_id` int(11) NOT NULL,
  `tv_forum_id` int(11) NOT NULL,
  `tv_post_id` int(11) NOT NULL,
  `tv_poste` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `whoisonline`
--

CREATE TABLE `whoisonline` (
  `online_id` int(11) NOT NULL,
  `online_time` datetime NOT NULL,
  `online_ip` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`toA`,`fromt`);

--
-- Index pour la table `auteurs`
--
ALTER TABLE `auteurs`
  ADD PRIMARY KEY (`membre`,`idcontenu`),
  ADD KEY `fk_auteurs` (`idcontenu`);

--
-- Index pour la table `automessages`
--
ALTER TABLE `automessages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ordre` (`ordre`);

--
-- Index pour la table `contenus`
--
ALTER TABLE `contenus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mp`
--
ALTER TABLE `mp`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contenus` (`idcontenu`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_groupes`
--
ALTER TABLE `social_groupes`
  ADD PRIMARY KEY (`groupes_id`),
  ADD KEY `fk_grges` (`groupes_createur`);

--
-- Index pour la table `social_gs_membres`
--
ALTER TABLE `social_gs_membres`
  ADD PRIMARY KEY (`groupe`,`membre`),
  ADD KEY `fk_s_gs_m` (`membre`);

--
-- Index pour la table `social_gs_statut`
--
ALTER TABLE `social_gs_statut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gs_m` (`membre`),
  ADD KEY `fk_gs_gs` (`groupe`);

--
-- Index pour la table `social_statut`
--
ALTER TABLE `social_statut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_membre` (`membre`);

--
-- Index pour la table `social_st_comment`
--
ALTER TABLE `social_st_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mb_st` (`membre`),
  ADD KEY `fk_st` (`statut`);

--
-- Index pour la table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `last_post` (`last_post`);

--
-- Index pour la table `topic_view`
--
ALTER TABLE `topic_view`
  ADD PRIMARY KEY (`tv_id`,`tv_topic_id`);

--
-- Index pour la table `whoisonline`
--
ALTER TABLE `whoisonline`
  ADD PRIMARY KEY (`online_ip`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `automessages`
--
ALTER TABLE `automessages`
  MODIFY `id` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `contenus`
--
ALTER TABLE `contenus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `mp`
--
ALTER TABLE `mp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
