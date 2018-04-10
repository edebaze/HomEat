-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 06 avr. 2018 à 19:05
-- Version du serveur :  10.1.30-MariaDB
-- Version de PHP :  7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `homeat`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `street` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` int(11) NOT NULL,
  `city` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(10,8) NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `street`, `zip_code`, `city`, `comment`, `number`, `lat`, `lng`, `ip`) VALUES
(1, 'Boulevard Gambetta', 76000, 'Rouen', 'appartement 604', 10, '50.00000000', '30.00000000', '0'),
(5, 'Boulevard Gambetta', 76000, 'Rouen', 'appartement 305', 11, '48.00000000', '2.00000000', '9063'),
(6, 'Marcel Pagnole', 14000, 'Lisieux', 'Appartement 201', 12, '48.00000000', '2.00000000', '9063');

-- --------------------------------------------------------

--
-- Structure de la table `address_has_user`
--

CREATE TABLE `address_has_user` (
  `id` int(11) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `principale` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `address_has_user`
--

INSERT INTO `address_has_user` (`id`, `address_id`, `user_id`, `name`, `principale`) VALUES
(1, 1, 1, 'Home Etienne', 1),
(3, 5, 3, 'Home aza', 0),
(4, 6, 3, 'Home aza', 1);

-- --------------------------------------------------------

--
-- Structure de la table `categories_ingredients`
--

CREATE TABLE `categories_ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories_recipes`
--

CREATE TABLE `categories_recipes` (
  `id` int(11) NOT NULL,
  `names_categories_recipes` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `categories_recipes`
--

INSERT INTO `categories_recipes` (`id`, `names_categories_recipes`) VALUES
(1, 'Hallal'),
(2, 'Viande');

-- --------------------------------------------------------

--
-- Structure de la table `challenge`
--

CREATE TABLE `challenge` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xp` int(11) NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `max` int(11) NOT NULL,
  `ref` int(11) NOT NULL,
  `sousref` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `challenge`
--

INSERT INTO `challenge` (`id`, `name`, `image`, `promo`, `xp`, `description`, `max`, `ref`, `sousref`) VALUES
(1, '5 stars', 'images/recompenses/01.png', 'azazaz', 200, 'Recevez une critique à 5 étoiles', 1, 1, 0),
(2, 'Cuis-tôt', 'images/recompenses/02.png', 'machine', 200, 'Commander un plat', 1, 2, 0),
(3, 'Decimeur de cochons', 'images/recompenses/03.png', 'sqdsqdqsd', 500, 'Manger 10 plats à base de cochon', 10, 3, 0),
(4, '5 star - expert', 'images/recompenses/04.jpg', 'edqsqsv', 500, 'Recevez 10 commentaires à 5 étoiles', 10, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `names_ingredients` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `allergenes` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredients_categories_ingredients`
--

CREATE TABLE `ingredients_categories_ingredients` (
  `ingredients_id` int(11) NOT NULL,
  `categories_ingredients_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `level`
--

CREATE TABLE `level` (
  `id` int(11) NOT NULL,
  `xp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `level`
--

INSERT INTO `level` (`id`, `xp`) VALUES
(1, 100),
(2, 200),
(3, 400),
(4, 600),
(5, 800);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `recipes_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quantities` int(11) NOT NULL,
  `cancel` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `status_id`, `recipes_id`, `user_id`, `quantities`, `cancel`) VALUES
(1, 1, 1, 1, 2, 1),
(2, 1, 1, 1, 1, 0),
(3, 1, 1, 1, 3, 1),
(4, 1, 1, 1, 2, 0),
(5, 1, 1, 2, 1, 1),
(6, 1, 1, 2, 2, 1),
(7, 1, 1, 2, 1, 1),
(8, 1, 1, 2, 2, 1),
(9, 2, 1, 2, 1, 1),
(10, 1, 1, 2, 1, 1),
(11, 1, 1, 2, 1, 0),
(12, 1, 1, 3, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `cuisto_id` int(11) DEFAULT NULL,
  `titre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `hour` time NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `recipes`
--

INSERT INTO `recipes` (`id`, `categorie_id`, `status_id`, `cuisto_id`, `titre`, `image`, `description`, `price`, `hour`, `quantity`) VALUES
(1, NULL, 2, 1, 'Lasagnes au ketchup', 'images/recettes/28768530.jpeg', 'Petit plat chaud du samedi spécial charclo', '3.00', '11:22:00', 2),
(2, NULL, 2, 3, 'Etienne est un ouf', 'images/recettes/45232314.jpeg', 'Etienne est trop un ouf, vraiment', '150.00', '12:07:00', 12);

-- --------------------------------------------------------

--
-- Structure de la table `recipes_ingredients`
--

CREATE TABLE `recipes_ingredients` (
  `recipes_id` int(11) NOT NULL,
  `ingredients_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `destinataire_id` int(11) DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `review`
--

INSERT INTO `review` (`id`, `user_id`, `destinataire_id`, `comments`, `notes`) VALUES
(1, 1, 1, 'Salut', 0),
(2, 1, 1, 'Salut', 0),
(3, 1, 1, 'Super !', 5),
(4, 2, 2, 'qsds', 5),
(5, 2, 2, 'eeza', 5),
(6, 2, 2, '', 0),
(7, 2, 2, 'Etienne est un ouf', 5),
(8, 2, 2, 'wsh', 5),
(9, 3, 3, '', 5),
(10, 3, 3, '', 5),
(11, 3, 3, '', 5),
(12, 3, 3, '', 0),
(13, 3, 3, '', 0),
(14, 3, 3, '', 0),
(15, 3, 3, '', 5),
(16, 3, 3, 'alulégen', 5);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Administrateur'),
(2, 'User', 'Utilisateur lambda');

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `names` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `descriptions` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `names`, `descriptions`) VALUES
(1, 'Vendu', ''),
(2, 'En Vente', '');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `roles_id` int(11) DEFAULT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_inscription` datetime NOT NULL,
  `last_connexion` datetime NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xp` int(11) NOT NULL,
  `level_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `roles_id`, `firstname`, `name`, `mail`, `pass`, `date_inscription`, `last_connexion`, `avatar`, `xp`, `level_id`) VALUES
(1, 2, '', 'Etienne', 'etienne@gmail.com', 'az', '2018-04-04 11:12:04', '2018-04-04 11:12:04', 'images/recompenses/02.png', 0, 1),
(2, 2, '', 'Marc', 'jesus@yahoo.fr', 'a', '2018-04-04 15:21:18', '2018-04-04 15:21:18', 'images/recompenses/01.png', 200, 2),
(3, 2, '', 'aza', 'aza@gmail.com', 'aza', '2018-04-04 16:22:06', '2018-04-04 16:22:06', 'images/recompenses/02.png', 3100, 5);

-- --------------------------------------------------------

--
-- Structure de la table `user_challenge`
--

CREATE TABLE `user_challenge` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `challenge_id` int(11) DEFAULT NULL,
  `accomplissement` double NOT NULL,
  `used` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user_challenge`
--

INSERT INTO `user_challenge` (`id`, `user_id`, `challenge_id`, `accomplissement`, `used`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 2, 1, 0),
(3, 2, 1, 0, 0),
(4, 2, 2, 1, 0),
(5, 3, 1, 1, 0),
(6, 3, 2, 1, 0),
(7, 3, 3, 0, 0),
(8, 3, 4, 0.2, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `address_has_user`
--
ALTER TABLE `address_has_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2E40F397F5B7AF75` (`address_id`),
  ADD KEY `IDX_2E40F397A76ED395` (`user_id`);

--
-- Index pour la table `categories_ingredients`
--
ALTER TABLE `categories_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories_recipes`
--
ALTER TABLE `categories_recipes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ingredients_categories_ingredients`
--
ALTER TABLE `ingredients_categories_ingredients`
  ADD PRIMARY KEY (`ingredients_id`,`categories_ingredients_id`),
  ADD KEY `IDX_449D79133EC4DCE` (`ingredients_id`),
  ADD KEY `IDX_449D7913B213CE5A` (`categories_ingredients_id`);

--
-- Index pour la table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52FFDEE6BF700BD` (`status_id`),
  ADD KEY `IDX_E52FFDEEFDF2B1FA` (`recipes_id`),
  ADD KEY `IDX_E52FFDEEA76ED395` (`user_id`);

--
-- Index pour la table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A369E2B56BF700BD` (`status_id`),
  ADD KEY `IDX_A369E2B52D8FD1A` (`cuisto_id`),
  ADD KEY `IDX_A369E2B5BCF5E72D` (`categorie_id`);

--
-- Index pour la table `recipes_ingredients`
--
ALTER TABLE `recipes_ingredients`
  ADD PRIMARY KEY (`recipes_id`,`ingredients_id`),
  ADD KEY `IDX_761206B0FDF2B1FA` (`recipes_id`),
  ADD KEY `IDX_761206B03EC4DCE` (`ingredients_id`);

--
-- Index pour la table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_794381C6A76ED395` (`user_id`),
  ADD KEY `IDX_794381C6A4F84F6E` (`destinataire_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D64938C751C4` (`roles_id`),
  ADD KEY `IDX_8D93D6495FB14BA7` (`level_id`);

--
-- Index pour la table `user_challenge`
--
ALTER TABLE `user_challenge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D7E904B5A76ED395` (`user_id`),
  ADD KEY `IDX_D7E904B598A21AC6` (`challenge_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `address_has_user`
--
ALTER TABLE `address_has_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `categories_ingredients`
--
ALTER TABLE `categories_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories_recipes`
--
ALTER TABLE `categories_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `challenge`
--
ALTER TABLE `challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_challenge`
--
ALTER TABLE `user_challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `address_has_user`
--
ALTER TABLE `address_has_user`
  ADD CONSTRAINT `FK_2E40F397A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2E40F397F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Contraintes pour la table `ingredients_categories_ingredients`
--
ALTER TABLE `ingredients_categories_ingredients`
  ADD CONSTRAINT `FK_449D79133EC4DCE` FOREIGN KEY (`ingredients_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_449D7913B213CE5A` FOREIGN KEY (`categories_ingredients_id`) REFERENCES `categories_ingredients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_E52FFDEEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_E52FFDEEFDF2B1FA` FOREIGN KEY (`recipes_id`) REFERENCES `recipes` (`id`);

--
-- Contraintes pour la table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `FK_A369E2B52D8FD1A` FOREIGN KEY (`cuisto_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A369E2B56BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_A369E2B5BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categories_recipes` (`id`);

--
-- Contraintes pour la table `recipes_ingredients`
--
ALTER TABLE `recipes_ingredients`
  ADD CONSTRAINT `FK_761206B03EC4DCE` FOREIGN KEY (`ingredients_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_761206B0FDF2B1FA` FOREIGN KEY (`recipes_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK_794381C6A4F84F6E` FOREIGN KEY (`destinataire_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_794381C6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64938C751C4` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `FK_8D93D6495FB14BA7` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`);

--
-- Contraintes pour la table `user_challenge`
--
ALTER TABLE `user_challenge`
  ADD CONSTRAINT `FK_D7E904B598A21AC6` FOREIGN KEY (`challenge_id`) REFERENCES `challenge` (`id`),
  ADD CONSTRAINT `FK_D7E904B5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
