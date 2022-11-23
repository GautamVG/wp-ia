DROP DATABASE IF EXISTS `zschedule_dev`;
CREATE DATABASE `zschedule_dev`;

USE `zschedule_dev`;

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE `user_type` (
    `id` int NOT NULL AUTO_INCREMENT,
    `label` varchar(30) NOT NULL,

    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `ground`;
CREATE TABLE `ground` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,

    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `name` varchar(30) NOT NULL,
    `svvid` varchar(30) NOT NULL,
    `pwd` varchar(32) NOT NULL,
    `type` int NOT NULL DEFAULT 3,

    PRIMARY KEY (`svvid`),
    FOREIGN KEY (`type`) 
        REFERENCES `user_type` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
    `id` int NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `ground` int NOT NULL,
    `user_svvid` varchar(30) NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`ground`)
        REFERENCES `ground` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (`user_svvid`)
        REFERENCES `user` (`svvid`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `ground_to_user`;
CREATE TABLE `ground_to_user` (
    `ground_id` int NOT NULL,
    `user_svvid` varchar(30) NOT NULL,

    FOREIGN KEY (`ground_id`)
        REFERENCES `ground` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (`user_svvid`)
        REFERENCES `user` (`svvid`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);