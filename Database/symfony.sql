/*
Navicat MariaDB Data Transfer

Source Server         : ENTRETIEN
Source Server Version : 100121
Source Host           : localhost:3306
Source Database       : symfony

Target Server Type    : MariaDB
Target Server Version : 100121
File Encoding         : 65001

Date: 2017-03-18 01:45:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', 'Développement web');
INSERT INTO `category` VALUES ('2', 'Développement mobile');
INSERT INTO `category` VALUES ('3', 'Graphisme');
INSERT INTO `category` VALUES ('4', 'Intégration');
INSERT INTO `category` VALUES ('5', 'Réseau');

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `Author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CD07ECCB6` (`advert_id`),
  CONSTRAINT `FK_9474526CD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `Entretien_advert` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('7', '26', 'test', 'test', '2017-03-17 18:16:54');

-- ----------------------------
-- Table structure for Entretien_advert
-- ----------------------------
DROP TABLE IF EXISTS `Entretien_advert`;
CREATE TABLE `Entretien_advert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nb_applications` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E7967F9989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_E7967F93DA5256D` (`image_id`),
  CONSTRAINT `FK_E7967F93DA5256D` FOREIGN KEY (`image_id`) REFERENCES `Entretien_image` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of Entretien_advert
-- ----------------------------
INSERT INTO `Entretien_advert` VALUES ('26', '10', '2017-03-17 18:16:00', 'Test2', 'Test', 'Test', '1', '2017-03-18 00:39:32', '0', 'test2');

-- ----------------------------
-- Table structure for Entretien_advert_category
-- ----------------------------
DROP TABLE IF EXISTS `Entretien_advert_category`;
CREATE TABLE `Entretien_advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`advert_id`,`category_id`),
  KEY `IDX_8B0D744AD07ECCB6` (`advert_id`),
  KEY `IDX_8B0D744A12469DE2` (`category_id`),
  CONSTRAINT `FK_8B0D744A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8B0D744AD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `Entretien_advert` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of Entretien_advert_category
-- ----------------------------
INSERT INTO `Entretien_advert_category` VALUES ('26', '2');
INSERT INTO `Entretien_advert_category` VALUES ('26', '3');
INSERT INTO `Entretien_advert_category` VALUES ('26', '4');

-- ----------------------------
-- Table structure for Entretien_advert_skill
-- ----------------------------
DROP TABLE IF EXISTS `Entretien_advert_skill`;
CREATE TABLE `Entretien_advert_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D123297BD07ECCB6` (`advert_id`),
  KEY `IDX_D123297B5585C142` (`skill_id`),
  CONSTRAINT `FK_D123297B5585C142` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`),
  CONSTRAINT `FK_D123297BD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `Entretien_advert` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of Entretien_advert_skill
-- ----------------------------

-- ----------------------------
-- Table structure for Entretien_application
-- ----------------------------
DROP TABLE IF EXISTS `Entretien_application`;
CREATE TABLE `Entretien_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_96286F50D07ECCB6` (`advert_id`),
  CONSTRAINT `FK_96286F50D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `Entretien_advert` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of Entretien_application
-- ----------------------------

-- ----------------------------
-- Table structure for Entretien_image
-- ----------------------------
DROP TABLE IF EXISTS `Entretien_image`;
CREATE TABLE `Entretien_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of Entretien_image
-- ----------------------------
INSERT INTO `Entretien_image` VALUES ('10', 'https://lh3.googleusercontent.com/Iip-8Yn3PLAzecCMb4ZaHTvFObl3ETUWZmd5zLflhbB6BXKyNc5aM4hrGAA9NXSs7i0=w100', 'WHO IS THE DOCTOR s Name ?');

-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of image
-- ----------------------------

-- ----------------------------
-- Table structure for skill
-- ----------------------------
DROP TABLE IF EXISTS `skill`;
CREATE TABLE `skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of skill
-- ----------------------------
INSERT INTO `skill` VALUES ('1', 'php');
INSERT INTO `skill` VALUES ('2', 'synphony');
SET FOREIGN_KEY_CHECKS=1;
