CREATE TABLE `access` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `user_id` INT NOT NULL ,
   `type` VARCHAR( 10 ) NOT NULL ,
   `action` VARCHAR( 30 ) NOT NULL ,
   `ip` VARCHAR( 15 ) NOT NULL ,
   `date` INT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `banners` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `UID` INT NOT NULL ,
   `date` INT NOT NULL ,
   `size` INT NOT NULL ,
   `web` VARCHAR( 70 ) NOT NULL ,
   `ip` VARCHAR( 15 ) NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `servers` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `UID` INT NOT NULL ,
   `type` VARCHAR( 14 ) NOT NULL ,
   `MID` INT NOT NULL ,
   `port` INT NOT NULL ,
   `slots` INT NOT NULL ,
   `lock` INT NOT NULL,
   `stoped` VARCHAR( 30 ) NOT NULL,
   `autorun` BOOL NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `codecredits` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `1` VARCHAR( 4 ) NOT NULL ,
   `2` VARCHAR( 8 ) NOT NULL ,
   `3` VARCHAR( 4 ) NOT NULL ,
   `lock` INT NOT NULL ,
   `cost` INT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `messages` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `UID` INT NOT NULL ,
   `date` INT NOT NULL ,
   `title` TEXT NOT NULL ,
   `message` TEXT NOT NULL,
   `type` INT NOT NULL ,
   `value` VARCHAR( 14 ) NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `chat` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `UID` INT NOT NULL ,
   `date` INT NOT NULL ,
   `message` TEXT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `tickets` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `UID` INT NOT NULL ,
   `type` INT NOT NULL,
   `category` VARCHAR( 10 ) NOT NULL ,
   `message` TEXT NOT NULL ,
   `lock` INT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `machines` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `name` VARCHAR( 30 ) NOT NULL ,
   `hostname` VARCHAR( 5 ) NOT NULL ,
   `ip` VARCHAR( 15 ) NOT NULL ,
   `ssh_login` VARCHAR( 20 ) NOT NULL ,
   `ssh_password` VARCHAR( 20 ) NOT NULL ,
   `SAMP` INT NOT NULL ,
   `VNT` INT NOT NULL ,
   `rank` INT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `banned` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `ip` VARCHAR( 15 ) NOT NULL ,
   `date` INT NOT NULL ,
   `message` TEXT NOT NULL
) ENGINE = innodb COLLATE utf8_bin;



CREATE TABLE `users` (
   `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `username` VARCHAR( 14 ) NOT NULL ,
   `password` VARCHAR( 50 ) NOT NULL ,

   `firstname` VARCHAR( 30 ) NOT NULL ,
   `lastname` VARCHAR( 30 ) NOT NULL ,
   `address` VARCHAR( 30 ) NOT NULL ,
   `city` VARCHAR( 30 ) NOT NULL ,
   `postcode` VARCHAR( 30 ) NOT NULL ,
   `telephone` VARCHAR( 30 ) NOT NULL ,

   `credit` FLOAT NOT NULL ,
   `language` VARCHAR( 2 ) NOT NULL ,
   `style` VARCHAR( 30 ) NOT NULL ,
   `avatar` INT NOT NULL ,
   `rank` INT NOT NULL ,
   `rules` INT NOT NULL ,
   `email` VARCHAR( 30 ) NOT NULL ,
   `website` VARCHAR( 30 ) NOT NULL ,
   `ip` VARCHAR( 15 ) NOT NULL ,
   `last_login` INT NOT NULL ,
   `last_login2` INT NOT NULL ,
   `chat` BOOL NOT NULL ,
   `lock` INT NOT NULL,
    UNIQUE (`username`)
) ENGINE = innodb COLLATE utf8_bin;


