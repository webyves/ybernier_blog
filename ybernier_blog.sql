-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  Dim 07 oct. 2018 à 18:03
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

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
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id_com` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `id_post` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_com_parent` int(10) UNSIGNED DEFAULT NULL,
  `id_state` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `comment_state`
--

CREATE TABLE `comment_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id_post` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text,
  `image_top` varchar(250) DEFAULT NULL,
  `id_state` int(10) UNSIGNED NOT NULL,
  `id_cat` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `post_category`
--

CREATE TABLE `post_category` (
  `id_cat` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `post_state`
--

CREATE TABLE `post_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(10) UNSIGNED NOT NULL,
  `id_state` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `id_role` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_state`
--

CREATE TABLE `user_state` (
  `id_state` int(10) UNSIGNED NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `post` (`id_post`),
  ADD KEY `user` (`id_user`),
  ADD KEY `com_parent` (`id_com_parent`),
  ADD KEY `state` (`id_state`);

--
-- Index pour la table `comment_state`
--
ALTER TABLE `comment_state`
  ADD PRIMARY KEY (`id_state`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `date` (`date`),
  ADD KEY `state` (`id_state`),
  ADD KEY `cat` (`id_cat`),
  ADD KEY `user` (`id_user`);

--
-- Index pour la table `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`id_cat`);

--
-- Index pour la table `post_state`
--
ALTER TABLE `post_state`
  ADD PRIMARY KEY (`id_state`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `state` (`id_state`),
  ADD KEY `role` (`id_role`) USING BTREE;

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `user_state`
--
ALTER TABLE `user_state`
  ADD PRIMARY KEY (`id_state`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_com` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `comment_state`
--
ALTER TABLE `comment_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `post_category`
--
ALTER TABLE `post_category`
  MODIFY `id_cat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `post_state`
--
ALTER TABLE `post_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user_state`
--
ALTER TABLE `user_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `C_id_com_parent` FOREIGN KEY (`id_com_parent`) REFERENCES `comments` (`id_com`),
  ADD CONSTRAINT `C_id_post` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`),
  ADD CONSTRAINT `C_id_state` FOREIGN KEY (`id_state`) REFERENCES `comment_state` (`id_state`),
  ADD CONSTRAINT `C_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `P_id_cat` FOREIGN KEY (`id_cat`) REFERENCES `post_category` (`id_cat`),
  ADD CONSTRAINT `P_id_state` FOREIGN KEY (`id_state`) REFERENCES `post_state` (`id_state`),
  ADD CONSTRAINT `P_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `U_id_role` FOREIGN KEY (`id_role`) REFERENCES `user_role` (`id_role`),
  ADD CONSTRAINT `U_id_state` FOREIGN KEY (`id_state`) REFERENCES `user_state` (`id_state`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
