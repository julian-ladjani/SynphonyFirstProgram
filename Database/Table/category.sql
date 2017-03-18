/*
Navicat MariaDB Data Transfer

Source Server         : ENTRETIEN
Source Server Version : 100121
Source Host           : localhost:3306
Source Database       : symfony

Target Server Type    : MariaDB
Target Server Version : 100121
File Encoding         : 65001

Date: 2017-03-18 01:55:24
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
SET FOREIGN_KEY_CHECKS=1;
