-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 05, 2024 at 06:55 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodbox-2`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `userid` int DEFAULT NULL,
  `streetid` int DEFAULT NULL,
  `districtid` int DEFAULT NULL,
  `cityid` int DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int NOT NULL,
  `questionid` int NOT NULL,
  `text` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `emoji` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `emoji`, `active`) VALUES
(1, 'Fast Food', '🍔', 1),
(2, 'Italian Cuisine', '🍝', 1),
(3, 'Asian Cuisine', '🍜', 1),
(4, 'Vegetarian', '🥦', 1),
(5, 'Healthy Options', '🥗', 1),
(6, 'Desserts', '🍰', 1),
(7, 'Beverages', '🥤', 1),
(8, 'Mexican Cuisine', '🌮', 1),
(9, 'Mediterranean Cuisine', '🥙', 1),
(10, 'Indian Cuisine', '🍛', 1),
(11, 'Sushi', '🍣', 1),
(12, 'Burgers', '🍔', 1),
(13, 'Pizza', '🍕', 1),
(14, 'Salads', '🥗', 1),
(15, 'Sandwiches', '🥪', 1),
(16, 'Seafood', '🦞', 1),
(17, 'Steaks', '🥩', 1),
(18, 'Pasta', '🍝', 1),
(19, 'Soups', '🍲', 1),
(20, 'Vegan', '🥕', 1),
(21, 'Juices', '🥤', 1),
(22, 'Coffee', '☕', 1),
(23, 'Ice Cream', '🍨', 1),
(24, 'Chinese Cuisine', '🥡', 1),
(25, 'Thai Cuisine', '🍛', 1),
(26, 'Greek Cuisine', '🥙', 1),
(27, 'Japanese Cuisine', '🍣', 1),
(28, 'Middle Eastern Cuisine', '🥙', 1),
(29, 'BBQ', '🍖', 1),
(30, 'Frozen Yogurt', '🍦', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `active`) VALUES
(1, 'Istanbul', 1),
(2, 'Ankara', 1),
(3, 'Izmir', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `orderdetailid` int NOT NULL,
  `score` int NOT NULL,
  `comment` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `cityid` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `cityid`, `active`) VALUES
(1, 'Kadikoy', 1, 1),
(2, 'Besiktas', 1, 1),
(3, 'Sariyer', 1, 1),
(4, 'Cankaya', 2, 1),
(5, 'Kecioren', 2, 1),
(6, 'Mamak', 2, 1),
(7, 'Bornova', 3, 1),
(8, 'Konak', 3, 1),
(9, 'Alsancak', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `foodid` int NOT NULL,
  `userid` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodcategories`
--

CREATE TABLE `foodcategories` (
  `id` int NOT NULL,
  `foodid` int NOT NULL,
  `categoryid` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `restaurantid` int NOT NULL,
  `onlyextra` tinyint(1) NOT NULL,
  `price` float NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `restaurantid`, `onlyextra`, `price`, `visible`, `active`) VALUES
(1, 'aaaaa', 'bbbbb', 1, 1, 99, 1, 1),
(2, 'Test Burger', 'yummy en leziz burger bu', 1, 0, 14.99, 1, 1),
(3, 'Kral Kebab', 'OOOF ATEŞ GIBI', 1, 0, 29.99, 1, 1),
(4, 'Ejderha Döner', 'Çok acılıdır dikkat!', 1, 0, 6.75, 1, 1),
(5, '1L Su', 'lıkır lıkır', 1, 0, 0.55, 1, 1),
(6, 'Dondurma', 'çok soğuktur dikkat!', 1, 0, 2.99, 1, 1),
(7, 'Pepsi (500ml)', 'yaşatır seni', 1, 0, 2.99, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menufoods`
--

CREATE TABLE `menufoods` (
  `id` int NOT NULL,
  `menuid` int NOT NULL,
  `foodid` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int NOT NULL,
  `restaurantid` int NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetailquestionanswer`
--

CREATE TABLE `orderdetailquestionanswer` (
  `id` int NOT NULL,
  `orderdetailid` int NOT NULL,
  `questionid` int NOT NULL,
  `answerid` int NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `id` int NOT NULL,
  `orderid` int NOT NULL,
  `foodid` int NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `restaurantid` int NOT NULL,
  `userid` int NOT NULL,
  `approvalpersonnel` int NOT NULL,
  `deliverypersonnel` int NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `orderconfirmed` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questionanswers`
--

CREATE TABLE `questionanswers` (
  `id` int NOT NULL,
  `questionid` int NOT NULL,
  `answerid` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `ID` int NOT NULL,
  `foodid` int NOT NULL,
  `text` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `required` tinyint(1) NOT NULL,
  `type` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `addressID` int DEFAULT NULL,
  `description` varchar(180) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'default description',
  `usertype` int NOT NULL DEFAULT '1',
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `username`, `name`, `email`, `phone`, `password`, `addressID`, `description`, `usertype`, `active`) VALUES
(1, 'mcdonalds', 'McDonalds', 'mcdonalds@gmail.com', NULL, '123', NULL, 'default description', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `streets`
--

CREATE TABLE `streets` (
  `id` int NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `districtid` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `streets`
--

INSERT INTO `streets` (`id`, `name`, `districtid`, `active`) VALUES
(1, 'Bagdat Caddesi', 1, 1),
(2, 'Moda Caddesi', 1, 1),
(3, 'Caddebostan Caddesi', 1, 1),
(4, 'Bebek Sokak', 2, 1),
(5, 'Levent Caddesi', 2, 1),
(6, 'Ortakoy Meydani', 2, 1),
(7, 'Istinye Caddesi', 3, 1),
(8, 'Tarabya Caddesi', 3, 1),
(9, 'Yenikoy Sokak', 3, 1),
(10, 'Tunali Hilmi Caddesi', 4, 1),
(11, 'Kavaklidere Sokak', 4, 1),
(12, 'Cinnah Caddesi', 4, 1),
(13, 'Ugur Mumcu Caddesi', 5, 1),
(14, 'Fatih Sultan Mehmet Caddesi', 5, 1),
(15, 'Mustafa Kemal Caddesi', 5, 1),
(16, 'Turkkonut Caddesi', 6, 1),
(17, 'Eryaman Caddesi', 6, 1),
(18, 'Ulus Caddesi', 6, 1),
(19, 'Ataturk Caddesi', 7, 1),
(20, 'Ege Universitesi Caddesi', 7, 1),
(21, 'Cumhuriyet Caddesi', 7, 1),
(22, 'Basmane Caddesi', 8, 1),
(23, 'Alsancak Caddesi', 8, 1),
(24, 'Kordonboyu', 8, 1),
(25, '139 Sokak', 9, 1),
(26, '142 Sokak', 9, 1),
(27, '146 Sokak', 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `restaurantid` int DEFAULT NULL,
  `addressid` int DEFAULT NULL,
  `usertype` int NOT NULL DEFAULT '0',
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `password`, `restaurantid`, `addressid`, `usertype`, `active`) VALUES
(1, 'aga', 'McDonaldsss', 'aga@gmail.com', '123123', '123', NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foodcategories`
--
ALTER TABLE `foodcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menufoods`
--
ALTER TABLE `menufoods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderdetailquestionanswer`
--
ALTER TABLE `orderdetailquestionanswer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionanswers`
--
ALTER TABLE `questionanswers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `streets`
--
ALTER TABLE `streets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodcategories`
--
ALTER TABLE `foodcategories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menufoods`
--
ALTER TABLE `menufoods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetailquestionanswer`
--
ALTER TABLE `orderdetailquestionanswer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionanswers`
--
ALTER TABLE `questionanswers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `streets`
--
ALTER TABLE `streets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
