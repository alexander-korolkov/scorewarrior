-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.3.0 - MySQL Community Server - GPL
-- Server OS:                    Linux
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db
CREATE DATABASE IF NOT EXISTS `db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db`;

-- Dumping structure for table db.doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table db.doctrine_migration_versions: ~9 rows (approximately)
INSERT IGNORE INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20240406134144', '2024-04-06 16:49:30', 72),
	('DoctrineMigrations\\Version20240406153116', '2024-04-06 18:31:32', 38),
	('DoctrineMigrations\\Version20240406153404', '2024-04-06 18:34:11', 55),
	('DoctrineMigrations\\Version20240407135950', '2024-04-07 14:00:51', 2660),
	('DoctrineMigrations\\Version20240407145750', '2024-04-07 14:58:04', 225),
	('DoctrineMigrations\\Version20240407171444', '2024-04-07 17:15:04', 275),
	('DoctrineMigrations\\Version20240408082949', '2024-04-08 08:30:02', 202),
	('DoctrineMigrations\\Version20240408083508', '2024-04-08 08:35:24', 90),
	('DoctrineMigrations\\Version20240408120420', '2024-04-08 12:04:41', 182),
	('DoctrineMigrations\\Version20240408120940', '2024-04-08 12:10:01', 184);

-- Dumping structure for table db.item
CREATE TABLE IF NOT EXISTS `item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.item: ~4 rows (approximately)
INSERT IGNORE INTO `item` (`id`, `name`, `description`) VALUES
	(1, 'Sword of Fire', 'A flaming sword that can burn enemies with just a touch.'),
	(2, 'Ice Shield', 'A shield covered with eternal ice, can protect against any fire.'),
	(3, 'Elixir of health', 'Restores the character\'s health by 50 units.'),
	(4, 'Cloak of Invisibility', 'Makes the wearer invisible for a short time.');

-- Dumping structure for table db.item_grant
CREATE TABLE IF NOT EXISTS `item_grant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `initiator_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E3A27D9C7DB3B714` (`initiator_id`),
  CONSTRAINT `FK_E3A27D9C7DB3B714` FOREIGN KEY (`initiator_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.item_grant: ~5 rows (approximately)
INSERT IGNORE INTO `item_grant` (`id`, `player_id`, `status`, `created_at`, `updated_at`, `initiator_id`) VALUES
	(4, 1, 'Pending', '2024-04-11 12:15:05', NULL, 1),
	(6, 1, 'Pending', '2024-04-11 13:14:16', NULL, 1),
	(7, 77, 'Pending', '2024-04-17 19:23:16', NULL, 1),
	(8, 888, 'Pending', '2024-04-17 19:26:45', NULL, 1),
	(9, 333, 'Pending', '2024-04-17 19:28:03', NULL, 1),
	(10, 888, 'Pending', '2024-04-22 08:53:34', NULL, 4);

-- Dumping structure for table db.item_grant_item
CREATE TABLE IF NOT EXISTS `item_grant_item` (
  `item_grant_id` int NOT NULL,
  `item_id` int NOT NULL,
  PRIMARY KEY (`item_grant_id`,`item_id`),
  KEY `IDX_6032CE7430014138` (`item_grant_id`),
  KEY `IDX_6032CE74126F525E` (`item_id`),
  CONSTRAINT `FK_6032CE74126F525E` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6032CE7430014138` FOREIGN KEY (`item_grant_id`) REFERENCES `item_grant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.item_grant_item: ~5 rows (approximately)
INSERT IGNORE INTO `item_grant_item` (`item_grant_id`, `item_id`) VALUES
	(4, 1),
	(6, 1),
	(6, 2),
	(7, 1),
	(8, 3),
	(9, 3),
	(10, 4);

-- Dumping structure for table db.messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.messenger_messages: ~0 rows (approximately)

-- Dumping structure for table db.player_message
CREATE TABLE IF NOT EXISTS `player_message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` int NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `initiator_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D749CF3A7DB3B714` (`initiator_id`),
  CONSTRAINT `FK_D749CF3A7DB3B714` FOREIGN KEY (`initiator_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.player_message: ~7 rows (approximately)
INSERT IGNORE INTO `player_message` (`id`, `player_id`, `message`, `status`, `created_at`, `updated_at`, `initiator_id`) VALUES
	(1, 1, 'Test message', 'Pending', '2024-04-11 08:23:47', '2024-04-19 06:24:32', 1),
	(2, 1, 'Test message', 'Approved', '2024-04-11 08:52:59', '2024-04-19 06:31:40', 1),
	(4, 1, 'Test message', 'Approved', '2024-04-11 09:45:40', '2024-04-19 06:31:36', 1),
	(5, 1, 'Test message', 'Rejected', '2024-04-11 09:46:03', '2024-04-19 06:31:45', 1),
	(6, 123, 'Hello My Friend', 'Approved', '2024-04-17 13:06:07', '2024-04-19 06:22:58', 1),
	(7, 777, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Rejected', '2024-04-18 15:27:58', '2024-04-20 17:17:15', 1),
	(8, 999, 'Hello, fellow kids', 'Approved', '2024-04-22 08:53:07', '2024-04-23 12:53:53', 4),
	(9, 1234556, 'How are you doing?', 'Approved', '2024-04-23 11:22:08', NULL, 1);

-- Dumping structure for table db.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db.user: ~3 rows (approximately)
INSERT IGNORE INTO `user` (`id`, `email`, `roles`, `password`, `username`, `avatar`) VALUES
	(1, 'alexkorolkov1@gmail.com', '["ROLE_EDITOR"]', '$2y$13$zIcz/gOsT48AM4MtkGxEoO0kFhmKFQW3vw2yT6s2HQpUdZms3hWMy', 'editor', NULL),
	(3, 'neweditor@test.com', '["ROLE_EDITOR"]', '$2y$13$4yryE1dYKUjbBAEKEjMo1eR6jDJVOvlw50vRdZwBlnTEtS4XWcei.', 'NewEditor', NULL),
	(4, 'commonuser@test.com', '["ROLE_USER"]', '$2y$13$Hfy8VBLNy5ZNlTKP.FiGhOEhQNrqh/6ie0gJ6AxvUXh67gLc01lxS', 'CommonUser', 'https://variety.com/wp-content/uploads/2021/04/Avatar.jpg?w=800');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
