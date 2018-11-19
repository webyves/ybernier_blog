-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 19 nov. 2018 à 12:56
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
(1, 'TEST Commentaire N°1', '2018-10-24 10:37:00', 3, 7, NULL, 1),
(2, 'Commentaire N°2', '2018-10-24 11:00:00', 3, 7, NULL, 2),
(3, 'Response Commentaire N°3', '2018-10-24 12:00:00', 3, 7, 1, 1),
(4, 'Response 2 Commentaire N°4', '2018-10-31 12:00:00', 3, 7, 1, 1),
(5, 'Response 3 Commentaire N°5', '2018-10-31 16:00:00', 3, 7, 2, 2),
(6, 'Commentaire pôür Episode 2', '2018-10-31 17:05:46', 2, 6, NULL, 1),
(7, 'Commentaire test avec balise bizare', '2018-10-31 17:19:59', 6, 6, NULL, 1),
(8, 'test nouveau commentaire avec script', '2018-11-02 12:35:21', 6, 6, 7, 1);

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
(1, '2018-10-09 14:36:00', 'Hello world', 'et bien voilà le premier post\r\n<br>HELLO WORLD <br>42<br>', 'imageTop1.png', 1, 1, 7),
(2, '2018-10-09 14:40:00', 'Episode 2', '<p>L\'attaque des clone,<br>un Excellent episode de la serie <span style=\"background-color: rgb(255, 0, 0);\"><span style=\"color: rgb(255, 156, 0);\">StarWars</span></span></p><p>l\'histoire d\'amour entre <b>padmé</b> et <b>anakin</b> peu paraitre un peu rebutante mais le fond de l\'histoire est vraiment interressante.</p><p>voir les autres post sur les autres episodes<br><a href=\"http://index.php?p=post&i=3\" target=\"_blank\">Episode 1</a><br>Episode 3<br></p>', 'imageTop2.jpg', 1, 4, 7),
(3, '2018-10-09 14:45:00', 'Episode 3', 'The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will, shepherds the weak through the valley of darkness, for he is truly his brother\'s keeper and the finder of lost children. And I will strike down upon thee with great vengeance and furious anger those who would attempt to poison and destroy My brothers. And you will know My name is the Lord when I lay My vengeance upon thee.', 'imageTop3.jpg', 1, 4, 7),
(4, '2018-10-09 14:37:00', 'Episode 1', 'La menace fantome<br>QUI QUI C\'EST LE VRAI VILAIN ???<br>', 'imageTop4.jpg', 1, 4, 7),
(5, '2018-11-09 11:23:04', 'Episode 4', '<p>A NEW HOPE</p><p>or not<br></p>', 'default.jpg', 2, 1, 8),
(6, '2018-11-09 11:25:21', 'Episode 4', '<p>last insert jedID<br></p>', 'imageTop6.jpg', 1, 1, 8),
(7, '2018-11-09 11:32:30', 'Episode 5', '<p>pour beaucoup le meilleur<br></p>', 'imageTop7.jpg', 1, 1, 8),
(10, '2018-11-09 17:25:34', 'test script', '<p>&lt;script&gt; alert(\'toto 2\')&lt;/script&gt;<br></p>', 'default.jpg', 2, 1, 8);

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
(4, 'Star Wars'),
(5, 'Batman');

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
(6, 'Tom', 'Sawyer', 'tom@sawyer.fr', '$2y$10$FN.QB1O5yeG.49RnW8YGMuBxlAbKoqdKRjc5ZdV7QBw4y1yMLhh52', '$2y$10$2z5.9sCvHgJekDR5g2ugZeSK6sSviQGxa/wWNrazdmLHiaLyD6gBD', 2, 1),
(7, 'Yves', 'BERNIER', 'webyves@hotmail.com', '$2y$10$mYPIopy4iXER8NBPq91d4.oWmvJpCyBh87QSxWYwHRQ6oylOs6iYi', '$2y$10$C9Ymt1x2gbGc2qvZuCKIB.49jOInbekKTSUyhn/tqNzW8xvACNlv.', 1, 1),
(8, 'Default', 'Admin', 'admin@admin.com', '$2y$10$HZMlNkm3GhoagCO70SkZi.1cWrbPIPd2jSVqqSf.o3xscD26mPAl.', '$2y$10$GHCaJFrlAZtNWpPZGz0d8O.AueLUqXYWJYhCZlDG5TMFDGPEV/ZrK', 1, 1),
(9, 'spam', 'boy', 'spam.boy@gmail.com', '$2y$10$WxkOifQs8ib2iQL3otlxG.UepKLm1gzeBB6dpyOge9FGjIbiH1k4y', '$2y$10$1x3.9sCvHgJekDR5g2ugZeSK6sSviQGxa/wWNrazdmLHiaLyD6gAC', 4, 2);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cookie_id` (`cookie_id`),
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
  MODIFY `id_com` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `yb_blog_comment_state`
--
ALTER TABLE `yb_blog_comment_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `yb_blog_posts`
--
ALTER TABLE `yb_blog_posts`
  MODIFY `id_post` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `yb_blog_post_category`
--
ALTER TABLE `yb_blog_post_category`
  MODIFY `id_cat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `yb_blog_post_state`
--
ALTER TABLE `yb_blog_post_state`
  MODIFY `id_state` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `yb_blog_users`
--
ALTER TABLE `yb_blog_users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
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
  ADD CONSTRAINT `C_id_com_parent` FOREIGN KEY (`id_com_parent`) REFERENCES `yb_blog_comments` (`id_com`) ON DELETE CASCADE,
  ADD CONSTRAINT `C_id_post` FOREIGN KEY (`id_post`) REFERENCES `yb_blog_posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `C_id_state` FOREIGN KEY (`id_state`) REFERENCES `yb_blog_comment_state` (`id_state`),
  ADD CONSTRAINT `C_id_user` FOREIGN KEY (`id_user`) REFERENCES `yb_blog_users` (`id_user`);

--
-- Contraintes pour la table `yb_blog_posts`
--
ALTER TABLE `yb_blog_posts`
  ADD CONSTRAINT `P_id_cat` FOREIGN KEY (`id_cat`) REFERENCES `yb_blog_post_category` (`id_cat`) ON DELETE CASCADE,
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
