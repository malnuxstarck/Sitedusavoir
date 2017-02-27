-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Lun 27 Février 2017 à 23:40
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `7438988jpzn`
--
CREATE DATABASE IF NOT EXISTS `7438988jpzn` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `7438988jpzn`;

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `ami_from` int(11) NOT NULL,
  `ami_to` int(11) NOT NULL,
  `ami_confirm` enum('0','1') NOT NULL,
  `ami_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `articles_id` int(11) NOT NULL,
  `articles_titre` varchar(100) NOT NULL,
  `articles_banniere` varchar(30) DEFAULT NULL,
  `articles_intro` text NOT NULL,
  `articles_conc` text NOT NULL,
  `articles_cat` int(11) NOT NULL,
  `articles_date` datetime NOT NULL,
  `articles_validation` enum('0','1') DEFAULT NULL,
  `articles_confirm` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `articles_par`
--

CREATE TABLE `articles_par` (
  `membre_id` int(11) NOT NULL,
  `articles_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `articles_parties`
--

CREATE TABLE `articles_parties` (
  `parties_id` int(11) NOT NULL,
  `parties_titre` varchar(200) NOT NULL,
  `parties_contenu` text NOT NULL,
  `articles_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `cat_id` int(11) NOT NULL,
  `cat_nom` varchar(30) NOT NULL,
  `cat_ordre` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

CREATE TABLE `forum` (
  `forum_id` int(11) NOT NULL,
  `forum_cat_id` mediumint(8) NOT NULL,
  `forum_name` varchar(30) NOT NULL,
  `forum_desc` text NOT NULL,
  `forum_ordre` mediumint(8) DEFAULT NULL,
  `forum_last_post_id` int(11) NOT NULL,
  `forum_topic` int(11) NOT NULL DEFAULT '0',
  `forum_post` int(11) NOT NULL DEFAULT '0',
  `auth_view` tinyint(4) NOT NULL DEFAULT '1',
  `auth_post` tinyint(4) NOT NULL DEFAULT '2',
  `auth_topic` tinyint(4) NOT NULL DEFAULT '2',
  `auth_annonce` tinyint(4) NOT NULL DEFAULT '3',
  `auth_modo` tinyint(4) NOT NULL DEFAULT '3'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_automess`
--

CREATE TABLE `forum_automess` (
  `automess_id` tinyint(3) NOT NULL,
  `automess_mess` text NOT NULL,
  `automess_titre` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_config`
--

CREATE TABLE `forum_config` (
  `config_nom` varchar(200) NOT NULL,
  `config_valeur` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_post`
--

CREATE TABLE `forum_post` (
  `post_id` int(11) NOT NULL,
  `post_createur` int(11) NOT NULL,
  `post_texte` text NOT NULL,
  `post_time` datetime NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_forum_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic`
--

CREATE TABLE `forum_topic` (
  `topic_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `topic_titre` char(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `topic_createur` int(11) NOT NULL,
  `topic_vu` mediumint(8) NOT NULL,
  `topic_time` datetime NOT NULL,
  `topic_genre` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'Message',
  `topic_last_post` int(11) NOT NULL DEFAULT '0',
  `topic_first_post` int(11) NOT NULL DEFAULT '0',
  `topic_post` mediumint(8) NOT NULL DEFAULT '0',
  `topic_locked` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic_view`
--

CREATE TABLE `forum_topic_view` (
  `tv_id` int(11) NOT NULL,
  `tv_topic_id` int(11) NOT NULL,
  `tv_forum_id` int(11) NOT NULL,
  `tv_post_id` int(11) NOT NULL,
  `tv_poste` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `forum_whosonline`
--

CREATE TABLE `forum_whosonline` (
  `online_id` int(11) NOT NULL,
  `online_time` datetime NOT NULL,
  `online_ip` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `membre_id` int(11) NOT NULL,
  `membre_pseudo` varchar(30) NOT NULL,
  `membre_mdp` varchar(255) NOT NULL,
  `membre_email` varchar(250) NOT NULL,
  `membre_siteweb` varchar(100) DEFAULT NULL,
  `membre_avatar` varchar(100) DEFAULT NULL,
  `membre_signature` varchar(200) DEFAULT 'Pas de signature',
  `membre_localisation` varchar(100) DEFAULT 'Non Localisé',
  `membre_inscrit` datetime DEFAULT NULL,
  `membre_derniere_visite` datetime DEFAULT NULL,
  `membre_rang` int(11) NOT NULL DEFAULT '2',
  `membre_post` int(11) NOT NULL DEFAULT '0',
  `token` varchar(250) DEFAULT NULL,
  `reset` varchar(250) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `cookie` varchar(250) DEFAULT NULL,
  `membre_avatar_mini` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mp`
--

CREATE TABLE `mp` (
  `mp_id` int(11) NOT NULL,
  `mp_expediteur` int(11) NOT NULL,
  `mp_receveur` int(11) NOT NULL,
  `mp_titre` varchar(100) NOT NULL,
  `mp_text` text NOT NULL,
  `mp_time` datetime NOT NULL,
  `mp_lu` enum('0','1') NOT NULL
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
  `groupes_banniere_min` varchar(30) DEFAULT NULL,
  `groupes_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_admin`
--

CREATE TABLE `social_gs_admin` (
  `groupes_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_membres`
--

CREATE TABLE `social_gs_membres` (
  `groupes_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_gs_statut`
--

CREATE TABLE `social_gs_statut` (
  `gs_statut_id` int(11) NOT NULL,
  `gs_statut_contenu` text NOT NULL,
  `gs_statut_photo` varchar(30) DEFAULT NULL,
  `membre_id` int(11) NOT NULL,
  `groupes_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_st_comment`
--

CREATE TABLE `social_st_comment` (
  `commentaires_id` int(11) NOT NULL,
  `commentaires_text` text NOT NULL,
  `membre_id` int(11) NOT NULL,
  `statut_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `social_statut`
--

CREATE TABLE `social_statut` (
  `statut_id` int(11) NOT NULL,
  `statut_contenu` text NOT NULL,
  `statut_photo` varchar(20) DEFAULT NULL,
  `membre_id` int(11) NOT NULL,
  `statut_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tutos`
--

CREATE TABLE `tutos` (
  `tutos_id` int(11) NOT NULL,
  `tutos_titre` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tutos_banniere` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `tutos_intro` text CHARACTER SET utf8 NOT NULL,
  `tutos_conc` text CHARACTER SET utf8 NOT NULL,
  `tutos_cat` int(11) NOT NULL,
  `tutos_date` datetime NOT NULL,
  `tutos_validation` enum('0','1') CHARACTER SET utf8 DEFAULT NULL,
  `tutos_confirm` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tutos_par`
--

CREATE TABLE `tutos_par` (
  `membre_id` int(11) NOT NULL,
  `tutos_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tutos_parties`
--

CREATE TABLE `tutos_parties` (
  `parties_id` int(11) NOT NULL,
  `parties_titre` varchar(200) NOT NULL,
  `parties_contenu` text NOT NULL,
  `tutos_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`ami_to`,`ami_from`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`articles_id`);

--
-- Index pour la table `articles_par`
--
ALTER TABLE `articles_par`
  ADD PRIMARY KEY (`membre_id`,`articles_id`),
  ADD KEY `fk_articles_par` (`articles_id`);

--
-- Index pour la table `articles_parties`
--
ALTER TABLE `articles_parties`
  ADD PRIMARY KEY (`parties_id`),
  ADD KEY `fk_articles` (`articles_id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_ordre` (`cat_ordre`);

--
-- Index pour la table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`forum_id`);

--
-- Index pour la table `forum_automess`
--
ALTER TABLE `forum_automess`
  ADD PRIMARY KEY (`automess_id`);

--
-- Index pour la table `forum_post`
--
ALTER TABLE `forum_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Index pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
  ADD PRIMARY KEY (`topic_id`),
  ADD UNIQUE KEY `topic_last_post` (`topic_last_post`);

--
-- Index pour la table `forum_topic_view`
--
ALTER TABLE `forum_topic_view`
  ADD PRIMARY KEY (`tv_id`,`tv_topic_id`);

--
-- Index pour la table `forum_whosonline`
--
ALTER TABLE `forum_whosonline`
  ADD PRIMARY KEY (`online_ip`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`membre_id`);

--
-- Index pour la table `mp`
--
ALTER TABLE `mp`
  ADD PRIMARY KEY (`mp_id`);

--
-- Index pour la table `social_groupes`
--
ALTER TABLE `social_groupes`
  ADD PRIMARY KEY (`groupes_id`),
  ADD KEY `fk_grges` (`groupes_createur`);

--
-- Index pour la table `social_gs_admin`
--
ALTER TABLE `social_gs_admin`
  ADD PRIMARY KEY (`groupes_id`,`membre_id`),
  ADD KEY `fk_gres_m` (`membre_id`);

--
-- Index pour la table `social_gs_membres`
--
ALTER TABLE `social_gs_membres`
  ADD PRIMARY KEY (`groupes_id`,`membre_id`),
  ADD KEY `fk_s_gs_m` (`membre_id`);

--
-- Index pour la table `social_gs_statut`
--
ALTER TABLE `social_gs_statut`
  ADD PRIMARY KEY (`gs_statut_id`),
  ADD KEY `fk_gs_m` (`membre_id`),
  ADD KEY `fk_gs_gs` (`groupes_id`);

--
-- Index pour la table `social_st_comment`
--
ALTER TABLE `social_st_comment`
  ADD PRIMARY KEY (`commentaires_id`),
  ADD KEY `fk_mb_st` (`membre_id`),
  ADD KEY `fk_st` (`statut_id`);

--
-- Index pour la table `social_statut`
--
ALTER TABLE `social_statut`
  ADD PRIMARY KEY (`statut_id`),
  ADD KEY `fk_membre` (`membre_id`);

--
-- Index pour la table `tutos`
--
ALTER TABLE `tutos`
  ADD PRIMARY KEY (`tutos_id`);

--
-- Index pour la table `tutos_par`
--
ALTER TABLE `tutos_par`
  ADD PRIMARY KEY (`membre_id`,`tutos_id`),
  ADD KEY `fk_tutos_par` (`tutos_id`);

--
-- Index pour la table `tutos_parties`
--
ALTER TABLE `tutos_parties`
  ADD PRIMARY KEY (`parties_id`),
  ADD KEY `fk_tutos` (`tutos_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `articles_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `articles_parties`
--
ALTER TABLE `articles_parties`
  MODIFY `parties_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum`
--
ALTER TABLE `forum`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum_automess`
--
ALTER TABLE `forum_automess`
  MODIFY `automess_id` tinyint(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum_post`
--
ALTER TABLE `forum_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `membre_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `mp`
--
ALTER TABLE `mp`
  MODIFY `mp_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `social_groupes`
--
ALTER TABLE `social_groupes`
  MODIFY `groupes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `social_gs_statut`
--
ALTER TABLE `social_gs_statut`
  MODIFY `gs_statut_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `social_st_comment`
--
ALTER TABLE `social_st_comment`
  MODIFY `commentaires_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `social_statut`
--
ALTER TABLE `social_statut`
  MODIFY `statut_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tutos`
--
ALTER TABLE `tutos`
  MODIFY `tutos_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tutos_parties`
--
ALTER TABLE `tutos_parties`
  MODIFY `parties_id` int(11) NOT NULL AUTO_INCREMENT;
