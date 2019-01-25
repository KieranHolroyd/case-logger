SET NAMES utf8;
SET time_zone = '+00:00';

DROP DATABASE IF EXISTS `case_logger`;
CREATE DATABASE `case_logger` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `case_logger`;

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log`
(
  `id`             int(11)   NOT NULL AUTO_INCREMENT,
  `log_content`    text,
  `log_context`    varchar(512)   DEFAULT 'admin',
  `logged_in_user` int(11)        DEFAULT NULL,
  `timestamp`      timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `ban_reports`;
CREATE TABLE `ban_reports`
(
  `id`             int(11)             NOT NULL AUTO_INCREMENT,
  `case_id`        int(11)             NOT NULL DEFAULT '0',
  `length`         int(11)                      DEFAULT '0',
  `message`        text                NOT NULL,
  `teamspeak`      tinyint(4)          NOT NULL DEFAULT '0',
  `ingame`         tinyint(4)          NOT NULL DEFAULT '0',
  `website`        tinyint(3) unsigned NOT NULL DEFAULT '0',
  `permenant`      tinyint(4)          NOT NULL DEFAULT '0',
  `manual_expired` tinyint(4)          NOT NULL DEFAULT '0',
  `player`         text                NOT NULL,
  `timestamp`      timestamp           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1
  ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `case_logs`;
CREATE TABLE `case_logs`
(
  `id`                    int(11)      NOT NULL AUTO_INCREMENT,
  `lead_staff`            text,
  `other_staff`           text,
  `type_of_report`        varchar(512) NOT NULL DEFAULT 'Other',
  `description_of_events` text,
  `timestamp`             timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `case_players`;
CREATE TABLE `case_players`
(
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11)      DEFAULT NULL,
  `type`    varchar(512) DEFAULT NULL,
  `name`    varchar(512) DEFAULT NULL,
  `guid`    varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`),
  KEY `name` (`name`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `errorlog`;
CREATE TABLE `errorlog`
(
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `errorinfopdo` text    NOT NULL,
  `query`        text    NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `guides`;
CREATE TABLE `guides`
(
  `id`        int(11)      NOT NULL AUTO_INCREMENT,
  `title`     varchar(512) NOT NULL,
  `body`      text         NOT NULL,
  `author`    varchar(256) NOT NULL,
  `timestamp` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `effective` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `login_tokens`;
CREATE TABLE `login_tokens`
(
  `id`      int(11)      NOT NULL AUTO_INCREMENT,
  `token`   varchar(64)  NOT NULL,
  `user_id` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `meetings`;
CREATE TABLE `meetings`
(
  `id`    int(11)    NOT NULL AUTO_INCREMENT,
  `date`  date       NOT NULL,
  `slt`   tinyint(1) NOT NULL DEFAULT '0',
  `pd`    tinyint(1) NOT NULL DEFAULT '0',
  `ems`   tinyint(1) NOT NULL DEFAULT '0',
  `staff` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `meeting_comments`;
CREATE TABLE `meeting_comments`
(
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `content`    text         NOT NULL,
  `author`     varchar(128) NOT NULL,
  `pointID`    int(11)           DEFAULT NULL,
  `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `meeting_points`;
CREATE TABLE `meeting_points`
(
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `name`        varchar(512) NOT NULL,
  `description` text         NOT NULL,
  `votes_up`    varchar(128) DEFAULT NULL,
  `votes_down`  varchar(128) DEFAULT NULL,
  `comments`    text         NOT NULL,
  `author`      varchar(256) NOT NULL,
  `meetingID`   int(11)      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages`
(
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `content`    text    NOT NULL,
  `title`      text    NOT NULL,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `punishment_reports`;
CREATE TABLE `punishment_reports`
(
  `id`        int(11)   NOT NULL AUTO_INCREMENT,
  `case_id`   int(11)            DEFAULT '0',
  `points`    int(11)            DEFAULT '0',
  `rules`     text      NOT NULL,
  `comments`  text      NOT NULL,
  `player`    text      NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `rollcall`;
CREATE TABLE `rollcall`
(
  `id`   int(11) NOT NULL AUTO_INCREMENT,
  `name` text    NOT NULL,
  `rank` text    NOT NULL,
  `team` text    NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `staffMessages`;
CREATE TABLE `staffMessages`
(
  `id`        int(11)   NOT NULL AUTO_INCREMENT,
  `user`      int(11)        DEFAULT NULL,
  `content`   text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `staff_interviews`;
CREATE TABLE `staff_interviews`
(
  `id`                    int(11) NOT NULL AUTO_INCREMENT,
  `previous_experience`   text,
  `ever_banned_reason`    text,
  `how_much_time`         text,
  `time_away_from_server` text,
  `work_flexibly`         text,
  `passed`                tinyint(4)  DEFAULT '0',
  `processed`             tinyint(4)  DEFAULT '0',
  `applicant_name`        varchar(50) DEFAULT NULL,
  `applicant_region`      varchar(50) DEFAULT NULL,
  `interviewer_id`        int(11)     DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE `suggestions`
(
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `name`       varchar(255) NOT NULL,
  `suggestion` text         NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
  `id`                        int(11)      NOT NULL AUTO_INCREMENT,
  `first_name`                varchar(50)  NOT NULL,
  `last_name`                 varchar(50)  NOT NULL,
  `username`                  varchar(200) NOT NULL,
  `email`                     varchar(100) NOT NULL,
  `password`                  varchar(100) NOT NULL,
  `unique_id`                 varchar(512) NOT NULL,
  `profile_picture`           varchar(500) NOT NULL DEFAULT '/favicon.ico',
  `rank`                      varchar(255)          DEFAULT NULL,
  `rank_lvl`                  int(11)               DEFAULT NULL,
  `staff_team`                int(11)               DEFAULT NULL,
  `sRep`                      tinyint(1)            DEFAULT '0',
  `isStaff`                   tinyint(1)            DEFAULT '0',
  `isCommand`                 tinyint(4)   NOT NULL DEFAULT '0',
  `isPD`                      tinyint(4)   NOT NULL DEFAULT '0',
  `isEMS`                     tinyint(4)   NOT NULL DEFAULT '0',
  `SLT`                       tinyint(1)            DEFAULT NULL,
  `Developer`                 tinyint(1)            DEFAULT NULL,
  `timezone`                  text,
  `steamid`                   text,
  `active`                    tinyint(1)            DEFAULT NULL,
  `strikes`                   int(11)               DEFAULT NULL,
  `notes`                     text,
  `essentialNotification`     text,
  `readEssentialNotification` tinyint(4)            DEFAULT '0',
  `region`                    varchar(50)           DEFAULT NULL,
  `loa`                       text,
  `suspended`                 tinyint(4)            DEFAULT '0',
  `lastPromotion`             date                  DEFAULT NULL,
  `createdAt`                 timestamp    NULL     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;