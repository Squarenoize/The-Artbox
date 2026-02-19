--
-- Structure de la table `works`
--

DROP TABLE IF EXISTS `works`;
CREATE TABLE IF NOT EXISTS `works` (
  `work_id` int NOT NULL AUTO_INCREMENT,
  `work_title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_artist` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_photo_path` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`work_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
