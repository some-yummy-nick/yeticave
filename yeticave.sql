-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 01, 2020 at 04:02 PM
-- Server version: 8.0.12
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yeticave`
--

-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE `bets`
(
  `id`      int(11)  NOT NULL,
  `date`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price`   int(11)  NOT NULL,
  `user_id` int(11)  NOT NULL,
  `lot_id`  int(11)  NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `bets`
--

INSERT INTO `bets` (`id`, `date`, `price`, `user_id`, `lot_id`)
VALUES (1, ''2020-02-13 17:54:35'', 12000, 1, 3),
       (2, ''2020-02-15 18:18:54'', 12100, 1, 3),
       (3, ''2020-02-15 19:19:22'', 7600, 1, 1),
       (4, ''2020-02-14 21:46:58'', 12300, 24, 3),
       (5, ''2020-02-15 21:49:27'', 12400, 24, 3),
       (6, ''2020-02-15 21:52:52'', 12500, 24, 3),
       (7, ''2020-02-15 21:53:24'', 12600, 24, 3),
       (8, ''2020-02-15 21:59:18'', 12700, 24, 3),
       (9, ''2020-02-15 22:04:49'', 12800, 24, 3),
       (10, ''2020-02-15 22:05:49'', 12900, 24, 3),
       (11, ''2020-02-15 22:42:35'', 7800, 24, 1),
       (12, ''2020-02-20 19:38:48'', 161000, 24, 7),
       (13, ''2020-02-23 20:40:06'', 9100, 31, 8),
       (14, ''2020-02-23 21:07:07'', 7900, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories`
(
  `id`           int(11)                                             NOT NULL,
  `name`         char(48)                                            NOT NULL,
  `english_name` char(48) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  ROW_FORMAT = COMPACT;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `english_name`)
VALUES (1, ''Одежда'', ''clothing''),
       (3, ''Доски и лыжи'', ''boards''),
       (4, ''Крепления'', ''attachment''),
       (5, ''Ботинки'', ''boots''),
       (6, ''Инструменты'', ''tools''),
       (7, ''Разное'', ''other'');

-- --------------------------------------------------------

--
-- Table structure for table `lots`
--

CREATE TABLE `lots`
(
  `id`          int(11)                                             NOT NULL,
  `name`        char(48) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `image`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci      DEFAULT '' img/lot-1.jpg '',
  `price`       int(8)                                              NOT NULL,
  `date_start`  datetime                                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end`    datetime                                            NOT NULL,
  `step`        int(8)                                                       DEFAULT '' 100 '',
  `favorite`    tinyint(1)                                                   DEFAULT '' 0 '',
  `user_id`     int(8)                                              NOT NULL,
  `winner_id`   int(8)                                                       DEFAULT NULL,
  `category_id` int(8)                                              NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `lots`
--

INSERT INTO `lots` (`id`, `name`, `description`, `image`, `price`, `date_start`, `date_end`, `step`, `favorite`,
                    `user_id`, `winner_id`, `category_id`)
VALUES (1, ''Куртка для сноуборда DC Multiny Charocal'', NULL, ''img/lot-5.jpg'', 7500, ''2020-01-26 10:59:10'', ''2020-02-25 00:00:00'', 100, 0, 1, 1, 1),
       (3, ''2014 Rossignol District Snowboard'', NULL, ''img/lot-1.jpg'', 11000, ''2020-02-06 21:30:20'', ''2020-02-27 00:00:00'', 100, 0, 1, 24, 1),
       (7, ''DC Ply Mens 2014 Snowboard'', ''some'', ''uploads/lot-2.jpg'', 160000, ''2020-02-17 22:11:52'', ''2020-02-26 00:00:00'', 1000, 0, 24, 24, 3),
       (8, ''Одежда для сноубода DC Multiny Charocal'', ''some'', ''uploads/lot-4.jpg'', 9000, ''2020-02-22 13:47:28'', ''2020-02-26 00:00:00'', 100, 0, 24, 31, 1),
       (10, ''Маска Oakley Canopy'', ''some'', ''uploads/lot-6.jpg'', 5400, ''2020-02-23 15:29:08'', ''2020-02-26 00:00:00'', 100, 0, 24, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users`
(
  `id`       int(11)                                              NOT NULL,
  `date`     datetime                                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email`    char(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` char(64) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,
  `name`     char(48) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '' You '',
  `contacts` text CHARACTER SET utf8 COLLATE utf8_general_ci      NOT NULL,
  `avatar`   char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lots_id`  int(11)                                                       DEFAULT NULL,
  `bets_id`  int(11)                                                       DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `date`, `email`, `password`, `name`, `contacts`, `avatar`, `lots_id`, `bets_id`)
VALUES (1, ''2020-01-26 10:54:57'', ''ignat.v@gmail.com'', ''$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'', ''Игнат'', ''89999999999'', ''img/user.jpg'', 1, 14),
       (24, ''2020-02-08 16:28:13'', ''kitty_93@li.ru'', ''$2y$10$Cs8kJj70t/Cz4887Bw.9cOMF.pk83iAa8hoK.aBgPXthG3PSo/Xwq'', ''Леночка'', ''899999999999'', ''img/user.jpg'', 7, 12),
       (31, ''2020-02-08 17:10:01'', ''warrior07@mail.ru'', ''$2y$10$F6tFZ17FmfNf9wy5QJY7yOBaphvv1Mq0n.zzx5UeMVCxkHM3c4lfq'', ''Руслан'', ''89782489826'', ''uploads/cloud.jpg'', 8, 13);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bets`
--
ALTER TABLE `bets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lot_id` (`lot_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`name`);

--
-- Indexes for table `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `winner_id` (`winner_id`),
  ADD KEY `lots_ibfk_2` (`category_id`),
  ADD KEY `lots_ibfk_1` (`user_id`);
ALTER TABLE `lots`
  ADD FULLTEXT KEY `lots_ft_search` (`name`, `description`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `lots_id` (`lots_id`),
  ADD KEY `bets_id` (`bets_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bets`
--
ALTER TABLE `bets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 9;

--
-- AUTO_INCREMENT for table `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bets`
--
ALTER TABLE `bets`
  ADD CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bets_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`);

--
-- Constraints for table `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`lots_id`) REFERENCES `lots` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`bets_id`) REFERENCES `bets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
