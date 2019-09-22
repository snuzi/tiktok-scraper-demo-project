
CREATE DATABASE IF NOT EXISTS tiktok;

DROP TABLE IF EXISTS `tiktok`.`posts`;

CREATE TABLE `tiktok`.`posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `userSocialId` varchar(30) DEFAULT NULL,
  `url` varchar(200) CHARACTER SET utf8mb4 DEFAULT '',
  `duration` int(11) DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4,
  `description` text CHARACTER SET utf8mb4,
  `uploadData` varchar(200) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `nrInteractions` int(11) DEFAULT NULL,
  `nrComments` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `posts_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table social_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tiktok`.`social_users`;

CREATE TABLE `tiktok`.`social_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4,
  `nrHearts` int(11) DEFAULT NULL,
  `nrFans` int(11) DEFAULT NULL,
  `nrFollowing` int(11) DEFAULT NULL,
  `nrVideos` int(11) DEFAULT NULL,
  `isVerified` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `social_users_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Script for TEST database 


CREATE DATABASE IF NOT EXISTS tiktok_test;

DROP TABLE IF EXISTS `tiktok_test`.`posts`;

CREATE TABLE `tiktok_test`.`posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `userSocialId` varchar(30) DEFAULT NULL,
  `url` varchar(200) CHARACTER SET utf8mb4 DEFAULT '',
  `duration` int(11) DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4,
  `description` text CHARACTER SET utf8mb4,
  `uploadData` varchar(200) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `nrInteractions` int(11) DEFAULT NULL,
  `nrComments` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `posts_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table social_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tiktok_test`.`social_users`;

CREATE TABLE `tiktok_test`.`social_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4,
  `nrHearts` int(11) DEFAULT NULL,
  `nrFans` int(11) DEFAULT NULL,
  `nrFollowing` int(11) DEFAULT NULL,
  `nrVideos` int(11) DEFAULT NULL,
  `isVerified` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `social_users_id_IDX` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;