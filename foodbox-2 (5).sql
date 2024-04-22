-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2024 at 04:31 AM
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
  `userid` int NOT NULL DEFAULT '-1',
  `name` varchar(60) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'my address',
  `streetid` int NOT NULL DEFAULT '-1',
  `districtid` int NOT NULL DEFAULT '-1',
  `cityid` int NOT NULL DEFAULT '-1',
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `userid`, `name`, `streetid`, `districtid`, `cityid`, `active`) VALUES
(1, -1, 'my address', 12, 4, 2, NULL),
(2, -1, 'work', 15, 5, 2, 1),
(3, -1, 'king', 27, 9, 3, 1),
(8, -1, 'merkez', 10, 4, 2, 1),
(12, 1, 'home', 1, 1, 1, 1),
(14, 1, 'job', 14, 5, 2, 1),
(17, 1, 'test', 27, 9, 3, 1),
(18, 2, 'White house', 23, 8, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int NOT NULL,
  `questionid` int NOT NULL,
  `foodid` int NOT NULL DEFAULT '-1',
  `text` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'Example text',
  `price` float NOT NULL DEFAULT '0',
  `orderval` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `questionid`, `foodid`, `text`, `price`, `orderval`, `active`) VALUES
(11, 3, -1, 'AAA', 0, 0, 1),
(12, 3, -1, 'GGGGG', 99, -3, 1),
(13, 3, 9, 'CCCCC', 15, 4, 1),
(17, 4, -1, 'evet', 0, 0, 1),
(18, 4, -1, 'hayır', 0, 0, 1),
(25, 5, -1, '+1 kanat', 0.2, 0, 1),
(26, 5, -1, '+5 kanat', 1.8, 0, 1),
(27, 5, -1, '+10', 4, 0, 1),
(31, 6, -1, 'Kola', 2, 0, 1),
(32, 6, -1, 'şişe su', 0, 0, 1),
(34, 7, -1, 'Kabul ediyorum', 0, 0, 1),
(35, 8, -1, 'Example text', 0, 0, 1),
(42, 9, -1, 'istemiyorum', 0, 0, 1),
(43, 9, -1, '2', 0.5, 0, 1),
(44, 9, -1, '5', 2, 0, 1),
(48, 10, -1, 'Example text', 0, 0, 1),
(49, 10, -1, 'Example text 2', 999, 0, 1);

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
-- Table structure for table `favoriterestaurants`
--

CREATE TABLE `favoriterestaurants` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `restaurantid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `favoriterestaurants`
--

INSERT INTO `favoriterestaurants` (`id`, `userid`, `restaurantid`) VALUES
(2, 1, 2);

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

--
-- Dumping data for table `foodcategories`
--

INSERT INTO `foodcategories` (`id`, `foodid`, `categoryid`, `active`) VALUES
(1, 1, 3, 1),
(9, 3, 1, 1),
(10, 3, 4, 1),
(11, 3, 5, 1),
(12, 3, 6, 1),
(13, 9, 4, 1),
(14, 9, 5, 1),
(15, 9, 6, 1),
(16, 9, 7, 1),
(17, 9, 13, 1),
(21, 11, 1, 1),
(22, 11, 4, 1),
(23, 11, 28, 1),
(24, 11, 29, 1),
(26, 10, 21, 1),
(27, 10, 22, 1);

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
  `image` varchar(200) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `restaurantid`, `onlyextra`, `price`, `image`, `visible`, `active`) VALUES
(1, 'aaaaa', 'bbbbb', 1, 1, 99, NULL, 1, 1),
(2, 'Test Burger', 'yummy en leziz burger bu', 1, 0, 14.99, NULL, 1, 1),
(3, 'Kral Kebab', 'OOOF ATEŞ GIBI', 1, 0, 29.99, NULL, 1, 1),
(4, 'Ejderha Döner', 'Çok acılıdır dikkat!', 1, 0, 6.75, NULL, 1, 1),
(5, '1L Su', 'lıkır lıkır', 1, 0, 0.55, NULL, 1, 1),
(6, 'Dondurma', 'çok soğuktur dikkat!', 1, 0, 2.99, NULL, 1, 1),
(7, 'Pepsi (500ml)', 'yaşatır seni', 1, 0, 2.99, NULL, 1, 1),
(8, 'Balık ekmek', '100gr balık ile yapılır', 1, 0, 6.3, NULL, 1, 1),
(9, 'Tavuk Kasa', '1 kasa dolu tavuk', 2, 0, 17.3, 'food_661c3a5ebe7e0.jpeg', 1, 1),
(10, '10 Litre Kola (Paket, Büyük)', 'doya doya için sağlığa yararlı!!!!!!!!!', 2, 0, 49, 'food_661c39de79abd.jpg', 1, 1),
(11, 'Baharatlı Kanat', 'kanatlarrrr loremroekroekroelroerloellll', 2, 0, 23.4, 'food_661c3ac7e1dee.jpg', 0, 1);

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
-- Table structure for table `orderdetailquestionanswers`
--

CREATE TABLE `orderdetailquestionanswers` (
  `id` int NOT NULL,
  `orderdetailid` int NOT NULL,
  `questionid` int NOT NULL,
  `answerid` int NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `orderdetailquestionanswers`
--

INSERT INTO `orderdetailquestionanswers` (`id`, `orderdetailid`, `questionid`, `answerid`, `price`, `active`) VALUES
(7, 4, 3, 11, 0, 1),
(8, 4, 3, 13, 15, 1),
(9, 4, 4, 17, 0, 1),
(10, 6, 5, 27, 4, 1),
(11, 6, 6, 32, 0, 1),
(12, 6, 7, 34, 0, 1),
(13, 7, 5, 26, 1.8, 1),
(14, 7, 6, 31, 2, 1),
(15, 8, 5, 26, 1.8, 1),
(16, 8, 6, 32, 0, 1),
(17, 8, 7, 34, 0, 1),
(18, 10, 9, 44, 2, 1),
(19, 11, 3, 11, 0, 1),
(20, 11, 4, 17, 0, 1),
(21, 12, 9, 43, 0.5, 1),
(22, 14, 9, 43, 0.5, 1),
(23, 15, 9, 42, 0, 1),
(24, 17, 9, 43, 0.5, 1),
(25, 18, 9, 43, 0.5, 1),
(26, 19, 9, 42, 0, 1),
(27, 20, 5, 26, 1.8, 1),
(28, 20, 6, 32, 0, 1),
(29, 20, 7, 34, 0, 1),
(30, 21, 3, 11, 0, 1),
(31, 21, 3, 12, 99, 1),
(32, 21, 3, 13, 15, 1),
(33, 21, 4, 18, 0, 1),
(34, 22, 9, 43, 0.5, 1),
(35, 22, 9, 44, 2, 1),
(36, 23, 5, 27, 4, 1),
(37, 23, 6, 32, 0, 1),
(38, 23, 7, 34, 0, 1),
(39, 24, 3, 11, 0, 1),
(40, 24, 4, 17, 0, 1),
(41, 25, 9, 43, 0.5, 1),
(42, 26, 9, 42, 0, 1);

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

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `orderid`, `foodid`, `price`, `active`) VALUES
(4, 2, 9, 17.3, 1),
(5, 2, 10, 49, 1),
(6, 2, 11, 23.4, 1),
(19, 9, 10, 49, 1),
(20, 10, 11, 23.4, 1),
(21, 10, 9, 17.3, 1),
(22, 11, 10, 49, 1),
(23, 11, 11, 23.4, 1),
(24, 12, 9, 17.3, 1),
(25, 12, 10, 49, 1),
(26, 12, 10, 49, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `restaurantid` int NOT NULL,
  `userid` int NOT NULL,
  `addressid` int NOT NULL DEFAULT '-1',
  `approvalpersonnel` int NOT NULL DEFAULT '-1',
  `deliverypersonnel` int NOT NULL DEFAULT '-1',
  `status` int NOT NULL DEFAULT '0',
  `orderconfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `restaurantid`, `userid`, `addressid`, `approvalpersonnel`, `deliverypersonnel`, `status`, `orderconfirmed`, `timestamp`, `active`) VALUES
(2, 2, 1, 12, -1, -1, 1, 1, '2024-04-22 00:58:00', 1),
(9, 2, 1, 12, -1, -1, 0, 1, '2024-04-22 04:06:26', 1),
(10, 2, 1, 12, -1, -1, 0, 1, '2024-04-22 04:11:46', 1),
(11, 2, 1, 12, -1, -1, 0, 1, '2024-04-22 04:12:24', 1),
(12, 2, 2, 18, -1, -1, 0, 1, '2024-04-22 04:14:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `foodid` int NOT NULL,
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'example title',
  `text` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'example description text',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `foodid`, `title`, `text`, `required`, `type`, `active`) VALUES
(3, 9, 'Yanında soğuk soğuk...', 'içeceklerden en az birini seçin.', 1, 1, 1),
(4, 9, 'Fiş ister misiniz?', 'doğayı korumak için \"hayır\" seçeneğini seçin.', 1, 2, 1),
(5, 11, 'Extra Kanat', 'almak zorundasin', 1, 2, 1),
(6, 11, 'Extra içecekler?', 'tatlandırıc içerir', 0, 1, 1),
(7, 11, 'Gizlilik hakları', 'kabul etmek zorunlu', 1, 2, 1),
(9, 10, 'plastik bardak?', 'Kaç tane istersiniz?', 0, 1, 1),
(10, 10, 'Test checkbox', 'example description textasdasd', 0, 1, 1);

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
  `addressid` int DEFAULT '-1',
  `description` varchar(180) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'default description',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT 'restaurant.png',
  `usertype` int NOT NULL DEFAULT '1',
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `username`, `name`, `email`, `phone`, `password`, `addressid`, `description`, `image`, `usertype`, `active`) VALUES
(1, 'mcdonalds', 'McDonalds', 'mcdonalds@gmail.com', '2222', '123', NULL, 'default description', 'restaurant.png', 1, NULL),
(2, 'kfc', 'KFC', 'kfc@gmail.com', '999999999999', '123', 8, 'default description', 'pfp_661c37bf83fbd.jpg', 1, 1);

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
  `addressid` int NOT NULL DEFAULT '-1',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT 'user.png',
  `usertype` int NOT NULL DEFAULT '0',
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `password`, `restaurantid`, `addressid`, `image`, `usertype`, `active`) VALUES
(1, 'aga', 'Superman', 'aga@gmail.com', '2222', '123', NULL, 12, 'pfp_661c31d4ca382.jpg', 0, NULL),
(2, 'obama', 'Barack Obama', 'obama@gmail.com', NULL, '123', NULL, 18, 'user.png', 0, NULL);

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
-- Indexes for table `favoriterestaurants`
--
ALTER TABLE `favoriterestaurants`
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
-- Indexes for table `orderdetailquestionanswers`
--
ALTER TABLE `orderdetailquestionanswers`
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
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
-- AUTO_INCREMENT for table `favoriterestaurants`
--
ALTER TABLE `favoriterestaurants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodcategories`
--
ALTER TABLE `foodcategories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT for table `orderdetailquestionanswers`
--
ALTER TABLE `orderdetailquestionanswers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `streets`
--
ALTER TABLE `streets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
