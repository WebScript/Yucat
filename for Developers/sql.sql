
-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'access'
-- 
-- ---

DROP TABLE IF EXISTS `access`;
		
CREATE TABLE `access` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `type` INTEGER NOT NULL,
  `action` VARCHAR(256) NOT NULL,
  `ip` VARCHAR(256) NOT NULL,
  `time` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'banners'
-- 
-- ---

DROP TABLE IF EXISTS `banners`;
		
CREATE TABLE `banners` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `time` INTEGER NOT NULL,
  `size` INTEGER NOT NULL,
  `website` VARCHAR(256) NOT NULL,
  `ip` VARCHAR(256) NOT NULL,
  `lock` INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'users'
-- 
-- ---

DROP TABLE IF EXISTS `users`;
		
CREATE TABLE `users` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user` VARCHAR(256) NOT NULL,
  `passwd` VARCHAR(256) NOT NULL,
  `firstname` VARCHAR(256) NOT NULL,
  `lastname` VARCHAR(256) NOT NULL,
  `street` VARCHAR(256) NOT NULL,
  `city` VARCHAR(256) NOT NULL,
  `postcode` VARCHAR(64) NOT NULL,
  `telephone` VARCHAR(64) NOT NULL,
  `credit` DECIMAL(10,7) NOT NULL DEFAULT 0,
  `language` VARCHAR(256) NOT NULL DEFAULT 'cz',
  `style` VARCHAR(256) NOT NULL DEFAULT 'Turbo',
  `avatar` INTEGER NOT NULL,
  `permissions` INTEGER NOT NULL,
  `rank` INTEGER NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `website` VARCHAR(256) NOT NULL,
  `ip` VARCHAR(256) NOT NULL,
  `ll1` INTEGER NOT NULL,
  `ll2` INTEGER NOT NULL,
  `activate_id` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'premium_code'
-- 
-- ---

DROP TABLE IF EXISTS `premium_code`;
		
CREATE TABLE `premium_code` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `used_by` INTEGER NOT NULL,
  `c1` VARCHAR(256) NOT NULL,
  `c2` VARCHAR(256) NOT NULL,
  `c3` VARCHAR(256) NOT NULL,
  `lock` INTEGER NOT NULL DEFAULT 0,
  `cost` DECIMAL(10,7) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'news'
-- 
-- ---

DROP TABLE IF EXISTS `news`;
		
CREATE TABLE `news` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `position` INTEGER NOT NULL DEFAULT 0,
  `type` INTEGER NOT NULL DEFAULT 0,
  `time` INTEGER NOT NULL,
  `title` VARCHAR(256) NOT NULL,
  `text` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'tickets'
-- 
-- ---

DROP TABLE IF EXISTS `tickets`;
		
CREATE TABLE `tickets` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `category` INTEGER NOT NULL,
  `text` VARCHAR(256) NOT NULL,
  `phase` INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'tickets_response'
-- 
-- ---

DROP TABLE IF EXISTS `tickets_response`;
		
CREATE TABLE `tickets_response` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `TID` INTEGER NOT NULL,
  `text` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'servers'
-- 
-- ---

DROP TABLE IF EXISTS `servers`;
		
CREATE TABLE `servers` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `type` INTEGER NOT NULL,
  `MID` INTEGER NOT NULL,
  `port` INTEGER NOT NULL,
  `slots` INTEGER NOT NULL DEFAULT 1,
  `permissions` INTEGER NOT NULL DEFAULT 0,
  `stopped` INTEGER NOT NULL,
  `autorun` INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'server_params'
-- 
-- ---

DROP TABLE IF EXISTS `server_params`;
		
CREATE TABLE `server_params` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `SID` INTEGER NOT NULL,
  `param` VARCHAR(256) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'machines'
-- 
-- ---

DROP TABLE IF EXISTS `machines`;
		
CREATE TABLE `machines` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `hostname` VARCHAR(256) NOT NULL,
  `ssh_ip` VARCHAR(256) NOT NULL,
  `ssh_port` INTEGER NOT NULL DEFAULT 22,
  `ssh_login` VARCHAR(256) NOT NULL DEFAULT 'root',
  `ssh_password` VARCHAR(256) NOT NULL,
  `ftp_port` INTEGER NOT NULL DEFAULT 21,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'machine_servers'
-- 
-- ---

DROP TABLE IF EXISTS `machine_servers`;
		
CREATE TABLE `machine_servers` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `MID` INTEGER NOT NULL,
  `type` INTEGER NOT NULL,
  `count` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'banned'
-- 
-- ---

DROP TABLE IF EXISTS `banned`;
		
CREATE TABLE `banned` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(256) NOT NULL,
  `time` INTEGER NOT NULL,
  `reason` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'config'
-- 
-- ---

DROP TABLE IF EXISTS `config`;
		
CREATE TABLE `config` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `param` VARCHAR(256) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'cookie'
-- 
-- ---

DROP TABLE IF EXISTS `cookie`;
		
CREATE TABLE `cookie` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `hash` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'cookie_params'
-- 
-- ---

DROP TABLE IF EXISTS `cookie_params`;
		
CREATE TABLE `cookie_params` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `CID` INTEGER NOT NULL,
  `param` VARCHAR(256) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'forum_reply'
-- 
-- ---

DROP TABLE IF EXISTS `forum_reply`;
		
CREATE TABLE `forum_reply` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `UID` INTEGER NOT NULL,
  `FTID` INTEGER NOT NULL,
  `text` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'forum_thread'
-- 
-- ---

DROP TABLE IF EXISTS `forum_thread`;
		
CREATE TABLE `forum_thread` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `FCID` INTEGER NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'forum_category'
-- 
-- ---

DROP TABLE IF EXISTS `forum_category`;
		
CREATE TABLE `forum_category` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'server_types'
-- 
-- ---

DROP TABLE IF EXISTS `server_types`;
		
CREATE TABLE `server_types` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `cost` DECIMAL(10,4) NOT NULL,
  `min_slots` INTEGER NOT NULL,
  `max_slots` INTEGER NOT NULL,
  `min_port` INTEGER NOT NULL,
  `max_port` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'lost_passwords'
-- 
-- ---

DROP TABLE IF EXISTS `lost_passwords`;
		
CREATE TABLE `lost_passwords` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `UID` INTEGER NOT NULL,
  `hash` VARCHAR(256) NOT NULL,
  `passwd` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'server_ftp'
-- 
-- ---

DROP TABLE IF EXISTS `server_ftp`;
		
CREATE TABLE `server_ftp` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user` VARCHAR(256) NOT NULL,
  `passwd` VARCHAR(256) NOT NULL,
  `SID` INTEGER NOT NULL,
  `MID` INTEGER NOT NULL,
  `ftp_uid` INTEGER NOT NULL DEFAULT 6000,
  `ftp_gid` INTEGER NOT NULL DEFAULT 6000,
  `dir` VARCHAR(256) NOT NULL DEFAULT '/dev/null',
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `access` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `access` ADD FOREIGN KEY (type) REFERENCES `server_types` (`id`);
ALTER TABLE `banners` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `premium_code` ADD FOREIGN KEY (used_by) REFERENCES `users` (`id`);
ALTER TABLE `news` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `tickets` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `tickets_response` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `tickets_response` ADD FOREIGN KEY (TID) REFERENCES `tickets` (`id`);
ALTER TABLE `servers` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `servers` ADD FOREIGN KEY (type) REFERENCES `server_types` (`id`);
ALTER TABLE `servers` ADD FOREIGN KEY (MID) REFERENCES `machines` (`id`);
ALTER TABLE `server_params` ADD FOREIGN KEY (SID) REFERENCES `servers` (`id`);
ALTER TABLE `machine_servers` ADD FOREIGN KEY (MID) REFERENCES `machines` (`id`);
ALTER TABLE `machine_servers` ADD FOREIGN KEY (type) REFERENCES `server_types` (`id`);
ALTER TABLE `cookie_params` ADD FOREIGN KEY (CID) REFERENCES `cookie` (`id`);
ALTER TABLE `forum_reply` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `forum_reply` ADD FOREIGN KEY (FTID) REFERENCES `forum_thread` (`id`);
ALTER TABLE `forum_thread` ADD FOREIGN KEY (FCID) REFERENCES `forum_category` (`id`);
ALTER TABLE `lost_passwords` ADD FOREIGN KEY (UID) REFERENCES `users` (`id`);
ALTER TABLE `server_ftp` ADD FOREIGN KEY (SID) REFERENCES `servers` (`id`);
ALTER TABLE `server_ftp` ADD FOREIGN KEY (MID) REFERENCES `machines` (`id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `access` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `banners` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `premium_code` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `news` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `tickets` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `tickets_response` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `servers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `server_params` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `machines` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `machine_servers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `banned` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `config` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `cookie` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `cookie_params` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `forum_reply` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `forum_thread` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `forum_category` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `server_types` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `lost_passwords` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `server_ftp` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `access` (`id`,`UID`,`type`,`action`,`ip`,`time`) VALUES
-- ('','','','','','');
-- INSERT INTO `banners` (`id`,`UID`,`time`,`size`,`website`,`ip`,`lock`) VALUES
-- ('','','','','','','');
-- INSERT INTO `users` (`id`,`user`,`passwd`,`firstname`,`lastname`,`street`,`city`,`postcode`,`telephone`,`credit`,`language`,`style`,`avatar`,`permissions`,`rank`,`email`,`website`,`ip`,`ll1`,`ll2`,`activate_id`) VALUES
-- ('','','','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `premium_code` (`id`,`used_by`,`c1`,`c2`,`c3`,`lock`,`cost`) VALUES
-- ('','','','','','','');
-- INSERT INTO `news` (`id`,`UID`,`position`,`type`,`time`,`title`,`text`) VALUES
-- ('','','','','','','');
-- INSERT INTO `tickets` (`id`,`UID`,`category`,`text`,`phase`) VALUES
-- ('','','','','');
-- INSERT INTO `tickets_response` (`id`,`UID`,`TID`,`text`) VALUES
-- ('','','','');
-- INSERT INTO `servers` (`id`,`UID`,`type`,`MID`,`port`,`slots`,`permissions`,`stopped`,`autorun`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `server_params` (`id`,`SID`,`param`,`value`) VALUES
-- ('','','','');
-- INSERT INTO `machines` (`id`,`name`,`hostname`,`ssh_ip`,`ssh_port`,`ssh_login`,`ssh_password`,`ftp_port`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `machine_servers` (`id`,`MID`,`type`,`count`) VALUES
-- ('','','','');
-- INSERT INTO `banned` (`id`,`ip`,`time`,`reason`) VALUES
-- ('','','','');
-- INSERT INTO `config` (`id`,`param`,`value`) VALUES
-- ('','','');
-- INSERT INTO `cookie` (`id`,`hash`) VALUES
-- ('','');
-- INSERT INTO `cookie_params` (`id`,`CID`,`param`,`value`) VALUES
-- ('','','','');
-- INSERT INTO `forum_reply` (`id`,`UID`,`FTID`,`text`) VALUES
-- ('','','','');
-- INSERT INTO `forum_thread` (`id`,`FCID`,`name`) VALUES
-- ('','','');
-- INSERT INTO `forum_category` (`id`,`name`) VALUES
-- ('','');
-- INSERT INTO `server_types` (`id`,`name`,`cost`,`min_slots`,`max_slots`,`min_port`,`max_port`) VALUES
-- ('','','','','','','');
-- INSERT INTO `lost_passwords` (`id`,`UID`,`hash`,`passwd`) VALUES
-- ('','','','');
-- INSERT INTO `server_ftp` (`id`,`user`,`passwd`,`SID`,`MID`,`ftp_uid`,`ftp_gid`,`dir`) VALUES
-- ('','','','','','','','');
