-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  sam. 03 nov. 2018 à 11:49
-- Version du serveur :  5.7.17
-- Version de PHP :  7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ybernier_blog`
--
CREATE DATABASE IF NOT EXISTS `ybernier_blog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ybernier_blog`;

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_comments`
--

CREATE TABLE `yb_blog_comments` (
  `id_com` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `id_post` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_com_parent` int(10) UNSIGNED DEFAULT NULL,
  `id_state` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_comments`
--

INSERT INTO `yb_blog_comments` (`id_com`, `text`, `date`, `id_post`, `id_user`, `id_com_parent`, `id_state`) VALUES
(1, 'TEST Commentaire N°1', '2018-10-24 10:37:00', 3, 2, NULL, 1),
(2, 'Commentaire N°2', '2018-10-24 11:00:00', 3, 2, NULL, 1),
(3, 'Response Commentaire N°3', '2018-10-24 12:00:00', 3, 2, 1, 1),
(4, 'Response 2 Commentaire N°4', '2018-10-31 12:00:00', 3, 2, 1, 1),
(5, 'Response 3 Commentaire N°5', '2018-10-31 16:00:00', 3, 2, 2, 1),
(6, 'Commentaire pôür Episode 2', '2018-10-31 17:05:46', 2, 6, NULL, 1),
(7, 'Commentaire test avec balise bizare et NULL en param', '2018-10-31 17:19:59', 4, 6, NULL, 1),
(8, 'test nouveau commentaire', '2018-11-02 12:35:21', 3, 6, NULL, 1),
(9, 'reponse au nouveau commentaire', '2018-11-02 12:35:47', 3, 6, 8, 1),
(10, 'repondre ancien commentaire', '2018-11-02 12:36:07', 3, 6, 2, 1),
(11, 'nouveau nouveau commentaire aprés reponses', '2018-11-02 12:36:42', 3, 6, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_comment_state`
--

CREATE TABLE `yb_blog_comment_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_comment_state`
--

INSERT INTO `yb_blog_comment_state` (`id_state`, `text`) VALUES
(1, 'Validé'),
(2, 'Bloqué');

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_posts`
--

CREATE TABLE `yb_blog_posts` (
  `id_post` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text,
  `image_top` varchar(250) DEFAULT NULL,
  `id_state` int(10) UNSIGNED NOT NULL,
  `id_cat` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_posts`
--

INSERT INTO `yb_blog_posts` (`id_post`, `date`, `title`, `content`, `image_top`, `id_state`, `id_cat`, `id_user`) VALUES
(1, '2018-10-09 14:36:00', 'Hello world', 'et bien voilà le premier post\r\nHELLO WORLD', 'avatar.png', 1, 1, 2),
(2, '2018-10-09 14:40:00', 'Episode 2', 'POST 2', 'episode_2.jpg', 1, 2, 2),
(3, '2018-10-09 14:45:00', 'Episode 3', 'The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will, shepherds the weak through the valley of darkness, for he is truly his brother\'s keeper and the finder of lost children. And I will strike down upon thee with great vengeance and furious anger those who would attempt to poison and destroy My brothers. And you will know My name is the Lord when I lay My vengeance upon thee.', 'episode_3.jpg', 1, 2, 2),
(4, '2018-10-09 14:37:00', 'Episode 1', 'Post 4 test categories et menus', 'episode_1.jpg', 1, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_post_category`
--

CREATE TABLE `yb_blog_post_category` (
  `id_cat` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_post_category`
--

INSERT INTO `yb_blog_post_category` (`id_cat`, `text`) VALUES
(1, 'Génerale'),
(2, 'Star Wars');

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_post_state`
--

CREATE TABLE `yb_blog_post_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_post_state`
--

INSERT INTO `yb_blog_post_state` (`id_state`, `text`) VALUES
(1, 'Validé'),
(2, 'Bloqué');

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_users`
--

CREATE TABLE `yb_blog_users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cookie_id` varchar(250) NOT NULL,
  `id_role` int(10) UNSIGNED NOT NULL,
  `id_state` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_users`
--

INSERT INTO `yb_blog_users` (`id_user`, `first_name`, `last_name`, `email`, `password`, `cookie_id`, `id_role`, `id_state`) VALUES
(2, 'Yves', 'BERNIER', 'webyves@hotmail.com', '$2y$10$afl440xdqsFic4WLrc/0aOkPFwsvb9woLAfa3wY/kGR8gtVs/2Kw.', '1', 1, 1),
(4, 'Prenom', 'Nom', 'adresse@email.com', '$2y$10$afl440xdqsFic4WLrc/0aOkPFwsvb9woLAfa3wY/kGR8gtVs/2Kw.', '2', 4, 2),
(5, 'Crypted', 'Boy', 'crypted@boy.fr', '$2y$10$afl440xdqsFic4WLrc/0aOkPFwsvb9woLAfa3wY/kGR8gtVs/2Kw.', '3', 4, 2),
(6, 'Tom', 'Sawyer', 'tom@sawyer.fr', '$2y$10$FN.QB1O5yeG.49RnW8YGMuBxlAbKoqdKRjc5ZdV7QBw4y1yMLhh52', '0.90458200 1540549097', 4, 2),
(7, 'bob', 'pouet', 'bob@pouet.com', '$2y$10$mYPIopy4iXER8NBPq91d4.oWmvJpCyBh87QSxWYwHRQ6oylOs6iYi', '$2y$10$C9Ymt1x2gbGc2qvZuCKIB.49jOInbekKTSUyhn/tqNzW8xvACNlv.', 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_user_role`
--

CREATE TABLE `yb_blog_user_role` (
  `id_role` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_user_role`
--

INSERT INTO `yb_blog_user_role` (`id_role`, `text`) VALUES
(1, 'Administrateur'),
(2, 'Redacteur'),
(3, 'Lecteur'),
(4, 'Visiteur');

-- --------------------------------------------------------

--
-- Structure de la table `yb_blog_user_state`
--

CREATE TABLE `yb_blog_user_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `yb_blog_user_state`
--

INSERT INTO `yb_blog_user_state` (`id_state`, `text`) VALUES
(1, 'Validé'),
(2, 'Bloqué');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `yb_blog_comments`
--
ALTER TABLE `yb_blog_comments`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `post` (`id_post`),
  ADD KEY `user` (`id_user`),
  ADD KEY `com_parent` (`id_com_parent`),
  ADD KEY `state` (`id_state`);

--
-- Index pour la table `yb_blog_comment_state`
--
ALTER TABLE `yb_blog_comment_state`
  ADD PRIMARY KEY (`id_state`);

--
-- Index pour la table `yb_blog_posts`
--
ALTER TABLE `yb_blog_posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `date` (`date`),
  ADD KEY `state` (`id_state`),
  ADD KEY `cat` (`id_cat`),
  ADD KEY `user` (`id_user`);

--
-- Index pour la table `yb_blog_post_category`
--
ALTER TABLE `yb_blog_post_category`
  ADD PRIMARY KEY (`id_cat`);

--
-- Index pour la table `yb_blog_post_state`
--
ALTER TABLE `yb_blog_post_state`
  ADD PRIMARY KEY (`id_state`);

--
-- Index pour la table `yb_blog_users`
--
ALTER TABLE `yb_blog_users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `state` (`id_state`),
  ADD KEY `role` (`id_role`) USING BTREE;

--
-- Index pour la table `yb_blog_user_role`
--
ALTER TABLE `yb_blog_user_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `yb_blog_user_state`
--
ALTER TABLE `yb_blog_user_state`
  ADD PRIMARY KEY (`id_state`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `yb_blog_comments`
--
ALTER TABLE `yb_blog_comments`
  MODIFY `id_com` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `yb_blog_comment_state`
--
ALTER TABLE `yb_blog_comment_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `yb_blog_posts`
--
ALTER TABLE `yb_blog_posts`
  MODIFY `id_post` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `yb_blog_post_category`
--
ALTER TABLE `yb_blog_post_category`
  MODIFY `id_cat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `yb_blog_post_state`
--
ALTER TABLE `yb_blog_post_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `yb_blog_users`
--
ALTER TABLE `yb_blog_users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `yb_blog_user_role`
--
ALTER TABLE `yb_blog_user_role`
  MODIFY `id_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `yb_blog_user_state`
--
ALTER TABLE `yb_blog_user_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `yb_blog_comments`
--
ALTER TABLE `yb_blog_comments`
  ADD CONSTRAINT `C_id_com_parent` FOREIGN KEY (`id_com_parent`) REFERENCES `yb_blog_comments` (`id_com`),
  ADD CONSTRAINT `C_id_post` FOREIGN KEY (`id_post`) REFERENCES `yb_blog_posts` (`id_post`),
  ADD CONSTRAINT `C_id_state` FOREIGN KEY (`id_state`) REFERENCES `yb_blog_comment_state` (`id_state`),
  ADD CONSTRAINT `C_id_user` FOREIGN KEY (`id_user`) REFERENCES `yb_blog_users` (`id_user`);

--
-- Contraintes pour la table `yb_blog_posts`
--
ALTER TABLE `yb_blog_posts`
  ADD CONSTRAINT `P_id_cat` FOREIGN KEY (`id_cat`) REFERENCES `yb_blog_post_category` (`id_cat`),
  ADD CONSTRAINT `P_id_state` FOREIGN KEY (`id_state`) REFERENCES `yb_blog_post_state` (`id_state`),
  ADD CONSTRAINT `P_id_user` FOREIGN KEY (`id_user`) REFERENCES `yb_blog_users` (`id_user`);

--
-- Contraintes pour la table `yb_blog_users`
--
ALTER TABLE `yb_blog_users`
  ADD CONSTRAINT `U_id_role` FOREIGN KEY (`id_role`) REFERENCES `yb_blog_user_role` (`id_role`),
  ADD CONSTRAINT `U_id_state` FOREIGN KEY (`id_state`) REFERENCES `yb_blog_user_state` (`id_state`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
