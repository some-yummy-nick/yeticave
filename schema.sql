-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 25, 2020 at 04:55 PM
-- Server version: 8.0.12
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yeticave`
--

-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE `bets` (
                      `id` int(11) NOT NULL,
                      `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `sum` int(11) NOT NULL,
                      `user_id` int(11) NOT NULL,
                      `lot_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
                            `id` int(11) NOT NULL,
                            `name` char(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `lots`
--

CREATE TABLE `lots` (
                      `id` int(11) NOT NULL,
                      `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `name` char(48) NOT NULL,
                      `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
                      `image` varchar(255) NOT NULL,
                      `price` int(8) NOT NULL,
                      `date_end` datetime NOT NULL,
                      `step` int(8) NOT NULL,
                      `favorite` int(8) DEFAULT NULL,
                      `user_id` int(8) NOT NULL,
                      `winner_id` int(8) NOT NULL,
                      `category_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                       `id` int(11) NOT NULL,
                       `date` datetime DEFAULT CURRENT_TIMESTAMP,
                       `email` char(128) DEFAULT NULL,
                       `password` char(64) DEFAULT NULL,
                       `name` char(48) NOT NULL,
                       `avatar` text NOT NULL,
                       `contacts` text NOT NULL,
                       `lots_id` int(11) NOT NULL,
                       `bets_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `winner_id` (`winner_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lots` (`id`),
  ADD CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `lots` (`id`),
  ADD CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`lots_id`) REFERENCES `lots` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`bets_id`) REFERENCES `bets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
