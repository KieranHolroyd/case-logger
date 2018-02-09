SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `case_logs` (
  `id` int(11) NOT NULL,
  `lead_staff` text,
  `other_staff` text,
  `type_of_report` varchar(512) NOT NULL DEFAULT 'Other',
  `description_of_events` text,
  `player_guid` text,
  `players` text,
  `link_to_player_report` text,
  `offence_committed` text,
  `points_awarded` tinyint(1) DEFAULT NULL,
  `amount_of_points` text,
  `evidence_supplied` text,
  `ban_awarded` tinyint(1) DEFAULT NULL,
  `ban_length` text,
  `ban_message` text,
  `ts_ban` int(11) DEFAULT NULL,
  `ingame_ban` int(11) DEFAULT NULL,
  `website_ban` int(11) DEFAULT NULL,
  `ban_perm` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `errorlog` (
  `id` int(11) NOT NULL,
  `errorinfopdo` text NOT NULL,
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `title` varchar(512) NOT NULL,
  `body` text NOT NULL,
  `author` varchar(256) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `login_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `user_id` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `rollcall` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `rank` text NOT NULL,
  `team` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `suggestion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `unique_id` varchar(512) NOT NULL,
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `profile_picture` varchar(500) NOT NULL DEFAULT '/favicon.ico',
  `rank` varchar(255) DEFAULT NULL,
  `rank_lvl` int(11) DEFAULT NULL,
  `staff_team` int(11) DEFAULT NULL,
  `sRep` tinyint(1) DEFAULT '0',
  `SLT` tinyint(1) DEFAULT NULL,
  `Developer` tinyint(1) DEFAULT NULL,
  `timezone` text,
  `steamid` text,
  `active` tinyint(1) DEFAULT NULL,
  `strikes` int(11) DEFAULT NULL,
  `notes` text,
  `loa` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `case_logs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `errorlog`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `rollcall`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `case_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1906;
ALTER TABLE `errorlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `login_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=559;
ALTER TABLE `rollcall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
ALTER TABLE `suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;