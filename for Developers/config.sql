/*
Navicat MySQL Data Transfer

Source Server         : Destiny
Source Server Version : 50149
Source Host           : 192.168.0.139:3306
Source Database       : yucat

Target Server Type    : MYSQL
Target Server Version : 50149
File Encoding         : 65001

Date: 2011-11-20 12:56:27
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', 'protocol', 'http://');
INSERT INTO `config` VALUES ('2', 'version', '0.0.1');
INSERT INTO `config` VALUES ('3', 'max_free_servers', '1');
INSERT INTO `config` VALUES ('4', 'free_order', 'TRUE');
INSERT INTO `config` VALUES ('5', 'default_language', 'cz');
INSERT INTO `config` VALUES ('6', 'default_style', 'Turbo');
INSERT INTO `config` VALUES ('7', 'time_zone', 'Europe/Bratislava');
INSERT INTO `config` VALUES ('8', 'template_keywords', 'Hosting, Host, Server Hosting, Server Host, Game S');
INSERT INTO `config` VALUES ('9', 'template_description', 'Game Server Hosting');
INSERT INTO `config` VALUES ('10', 'cost_samp', '1.667');
INSERT INTO `config` VALUES ('11', 'banner_rate', '100');
INSERT INTO `config` VALUES ('12', 'url_userfriendly', '1');
