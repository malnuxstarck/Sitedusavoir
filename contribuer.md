# Sitedusavoir SDS

- Comment faire fonctionner SDS en Local ?

D'abord il faut verifier que vous avez un serveur installer correctement comme **WAMP**, **MAMP** ou **XAMPP**.
Voici quelques liens vous permetant d'en telecharger

1. [Wampserver](http://www.wampserver.com/fr/) . Si vous rencontrez des problemes aller voir cet tuto [Tuto installation wamp](https://zestedesavoir.com/tutoriels/612/wamp-developper-avec-php-ajax-html-sous-windows/381_utiliser-son-serveur/2836_ou-pour-des-besoins-specifiques/#2-9001_wamp-ses-modules-et-microsoft-visual-c)
2. [XAMPP](https://www.apachefriends.org/index.html)
3. [MAMP](https://www.apachefriends.org/index.html)

Avant de lancer SDS , il vous faut aussi une base de donnée operationnel et des tables dessus.
Voici les requetes a executer :)

- Creation de la base de donnée , Desolé pour le nom degueux c'est a cause de mon hebergeur.

```sql

CREATE DATABASE IF NOT EXISTS `7438988jpzn` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `7438988jpzn`;

```

- Les tables

```sql

CREATE TABLE `amis` (
  `ami_from` int(11) NOT NULL,
  `ami_to` int(11) NOT NULL,
  `ami_confirm` enum('0','1') NOT NULL,
  `ami_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `categorie` (
  `cat_id` int(11) NOT NULL,
  `cat_nom` varchar(30) NOT NULL,
  `cat_ordre` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`cat_id`, `cat_nom`, `cat_ordre`) VALUES
(1, 'Général', 50),
(2, 'Informatique', 40),
(3, 'Autres savoirs', 30),
(4, 'Bugs et Ameliorations', 20),
(5, 'Jeux videos', 10),
(6, 'Communauté SDS', 5);

-- --------------------------------------------------------




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

--
-- Contenu de la table `forum`
--

INSERT INTO `forum` (`forum_id`, `forum_cat_id`, `forum_name`, `forum_desc`, `forum_ordre`, `forum_last_post_id`, `forum_topic`, `forum_post`, `auth_view`, `auth_post`, `auth_topic`, `auth_annonce`, `auth_modo`) VALUES
(1, 1, 'Présentation', 'Nouveau sur le forum? Venez vous\\r\\nprésenter ici !', 60, 46, 2, 2, 1, 2, 2, 2, 3),
(2, 1, 'Les News', 'Les news du site sont ici', 50, 2, 1, 1, 1, 2, 2, 3, 3),
(3, 1, 'Discussions générales', 'Ici on peut parler de tout\\r\\nsur tous les sujets', 40, 30, 1, 4, 1, 2, 2, 3, 4),
(4, 2, 'HTML/CSS', 'Il y\'a des erreurs dans le code Html/css c\'est ici', 60, 31, 1, 6, 1, 1, 1, 1, 1),
(5, 5, 'Les jeux Clash (clans ,Royal)', 'Pour parler des jeux clash of clans ! C\'est ici', 50, 39, 2, 6, 1, 2, 2, 2, 4),
(6, 5, 'Commands and conquer ', 'Pour parler de combat, de strategie sur commands and conquer', 40, 16, 1, 1, 1, 2, 2, 3, 4),
(7, 5, 'Autres jeux', 'Parler des autres jeux ! Par la', 30, 17, 1, 1, 1, 2, 2, 3, 4),
(8, 2, 'Langage C', 'Vos erreurs dans le C c\'est ici', 20, 29, 1, 3, 1, 2, 2, 3, 3),
(9, 2, 'PHP', 'Pour le php c\'est ici', 0, 27, 1, 3, 1, 2, 2, 3, 4),
(10, 4, 'Suggestions', 'Pour les suggestions d(ameliorations !', 0, 49, 3, 8, 1, 2, 2, 3, 4),
(11, 4, 'Bugs', 'Pour les bugs ou erreurs !', 0, 51, 2, 2, 1, 2, 2, 3, 4),
(12, 3, 'Maths', 'Ah les maths !', 0, 28, 1, 6, 1, 2, 2, 3, 3),
(13, 3, 'Physique', 'La physique  !', 0, 44, 2, 2, 1, 2, 2, 3, 3),
(14, 3, 'La litterature', 'Juste pour parler de la litterature', 10, 0, 0, 0, 1, 2, 2, 3, 3),
(15, 6, 'Vos projets', 'Presentez vos petits projets', 50, 52, 1, 3, 1, 2, 2, 3, 3),
(16, 6, 'Rencontres SDS', 'Les samedis de SDS', 40, 53, 1, 1, 1, 2, 2, 3, 3),
(17, 2, 'Javascript', 'Pour parler de script en javascript .', 30, 0, 0, 0, 1, 2, 2, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `forum_automess`
--

CREATE TABLE `forum_automess` (
  `automess_id` tinyint(3) NOT NULL,
  `automess_mess` text NOT NULL,
  `automess_titre` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_automess`
--

INSERT INTO `forum_automess` (`automess_id`, `automess_mess`, `automess_titre`) VALUES
(1, 'Vous n\'avez pas respecter les regles pour poster un sujet,celui est fermer :)', 'Non respect de post');

-- --------------------------------------------------------

--
-- Structure de la table `forum_config`
--

CREATE TABLE `forum_config` (
  `config_nom` varchar(200) NOT NULL,
  `config_valeur` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_config`
--

INSERT INTO `forum_config` (`config_nom`, `config_valeur`) VALUES
('avatar_maxsize', ''),
('avatar_maxh', ''),
('avatar_maxl', ''),
('sign_maxl', ''),
('auth_bbcode_sign', ''),
('pseudo_maxsize', ''),
('pseudo_minsize', '3'),
('topic_par_page', '20'),
('post_par_page', '15');

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

--

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

--


-- --------------------------------------------------------

CREATE TABLE `forum_whosonline` (
  `online_id` int(11) NOT NULL,
  `online_time` datetime NOT NULL,
  `online_ip` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `forum_whosonline`
--
ALTER TABLE `forum_whosonline`
  ADD PRIMARY KEY (`online_ip`)

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
  `cookie` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--

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

--

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
  `tutos_validation` enum('0','1') CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tutos`
--

INSERT INTO `tutos` (`tutos_id`, `tutos_titre`, `tutos_banniere`, `tutos_intro`, `tutos_conc`, `tutos_cat`, `tutos_date`, `tutos_validation`) VALUES
(1, 'Apprendre a se demerder seul', '350x150.png', 'Ceci est on introduction pas tres longue', 'ici , j\'ai conclu le travaille accomplir dessus', 2, '2017-01-30 18:49:42', NULL)

-- cle primaire 

ALTER TABLE `tutos`
  ADD PRIMARY KEY (`tutos_id`);
  
  
  
  
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
-- Contenu de la table `tutos_parties`
--

INSERT INTO `tutos_parties` (`parties_id`, `parties_titre`, `parties_contenu`, `tutos_id`) VALUES
(1, 'La recherche sur Google ', 'La recherche sur google est un excellent exercice pour parfaire sa connaissance dans l\'informatique , notament lorsqu\'on programme on peut amener a faire des recherches', 1),
(2, 'La recherche sur la doc', 'La recherche sur la doc est un excellent exercice pour parfaire sa connaissance dans l\'informatique , notament lorsqu\'on programme on peut amener a faire des recherches', 1);

--
-- Index pour les tables exportées
--

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
-- AUTO_INCREMENT pour la table `tutos_parties`
--
ALTER TABLE `tutos_parties`
  MODIFY `parties_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
  


--
-- Structure de la table `tutos_par`
--

CREATE TABLE `tutos_par` (
  `membre_id` int(11) NOT NULL,
  `tutos_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tutos_par`
--

INSERT INTO `tutos_par` (`membre_id`, `tutos_id`) VALUES
(1, 1),
(2, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `tutos_par`
--
ALTER TABLE `tutos_par`
  ADD PRIMARY KEY (`membre_id`,`tutos_id`),
  ADD KEY `fk_tutos_par` (`tutos_id`);



-- Index pour les tables exportées
--

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`ami_to`,`ami_from`);

--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_ordre` (`cat_ordre`);

--


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
-- AUTO_INCREMENT pour les tables exportées
--

--
-
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--


-- AUTO_INCREMENT pour la table `forum`
--
ALTER TABLE `forum`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `forum_automess`
--
ALTER TABLE `forum_automess`
  MODIFY `automess_id` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `forum_post`
--
ALTER TABLE `forum_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `membre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `mp`
--
ALTER TABLE `mp`
  MODIFY `mp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


```
