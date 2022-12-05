DROP DATABASE IF EXISTS `zschedule_php`;
CREATE DATABASE `zschedule_php`;

USE `zschedule_php`;

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE `user_type` (
    `id` int NOT NULL AUTO_INCREMENT,
    `label` varchar(30) NOT NULL,

    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `name` text NOT NULL,
    `photo` varchar(256) NOT NULL,
    `svvid` varchar(128) NOT NULL,
    `pwd` varchar(60) NOT NULL,
    `pwd_reset_token` varchar(60) DEFAULT NULL,
    `type` int NOT NULL DEFAULT 3,

    PRIMARY KEY (`svvid`),
    FOREIGN KEY (`type`) 
        REFERENCES `user_type` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `ground`;
CREATE TABLE `ground` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,
    `photo` varchar(256) NOT NULL,
    `manager_svvid` varchar(128) NOT NULL,
    `close_time` time DEFAULT NULL,
    `open_time` time DEFAULT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`manager_svvid`)
        REFERENCES `user` (`svvid`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `zone`;
CREATE TABLE `zone` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,
    `is_primary` boolean NOT NULL DEFAULT false,
    `is_multi_zonal` boolean DEFAULT NULL,
    `amenities` text,
    `ground_id` int NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`ground_id`) 
        REFERENCES `ground` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
    `id` int NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `zone_id` int NOT NULL,
    `user_svvid` varchar(128) NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`zone_id`)
        REFERENCES `zone` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (`user_svvid`)
        REFERENCES `user` (`svvid`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);