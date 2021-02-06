DROP TABLE IF EXISTS `lailu_language`;
CREATE TABLE `lailu_language` (
  `lang` varchar(255) DEFAULT NULL,
  `plat` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `pack` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;