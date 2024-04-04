-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2024 at 11:19 PM
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
  `ID` int NOT NULL,
  `UserID` int DEFAULT NULL,
  `StreetID` int DEFAULT NULL,
  `DistrictID` int DEFAULT NULL,
  `CityID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `ID` int NOT NULL,
  `QuestionID` int DEFAULT NULL,
  `Text` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int NOT NULL,
  `Name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `Emoji` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Emoji`, `Active`) VALUES
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
  `ID` int NOT NULL,
  `CityName` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`ID`, `CityName`, `Active`) VALUES
(1, 'Istanbul', 1),
(2, 'Ankara', 1),
(3, 'Izmir', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int NOT NULL,
  `OrderDetailID` int DEFAULT NULL,
  `Score` int DEFAULT NULL,
  `Comment` varchar(200) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `ID` int NOT NULL,
  `DistrictName` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `CityID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`ID`, `DistrictName`, `CityID`, `Active`) VALUES
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
  `ID` int NOT NULL,
  `FoodID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodcategories`
--

CREATE TABLE `foodcategories` (
  `ID` int NOT NULL,
  `FoodID` int DEFAULT NULL,
  `CategoryID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `ID` int NOT NULL,
  `Name` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Description` varchar(150) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `RestaurantID` int DEFAULT NULL,
  `OnlyExtra` tinyint(1) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT '1',
  `Active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`ID`, `Name`, `Description`, `RestaurantID`, `OnlyExtra`, `Price`, `Visible`, `Active`) VALUES
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
  `ID` int NOT NULL,
  `MenuID` int DEFAULT NULL,
  `FoodID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `ID` int NOT NULL,
  `RestaurantID` int DEFAULT NULL,
  `Name` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Description` varchar(150) COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetailquestionanswer`
--

CREATE TABLE `orderdetailquestionanswer` (
  `ID` int NOT NULL,
  `OrderDetailID` int DEFAULT NULL,
  `QuestionID` int DEFAULT NULL,
  `AnswerID` int DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `ID` int NOT NULL,
  `OrderID` int DEFAULT NULL,
  `FoodID` int DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int NOT NULL,
  `RestaurantID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `ApprovalPersonnel` int DEFAULT NULL,
  `DeliveryPersonnel` int DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `OrderConfirmed` tinyint(1) DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questionanswers`
--

CREATE TABLE `questionanswers` (
  `ID` int NOT NULL,
  `QuestionID` int DEFAULT NULL,
  `AnswerID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `ID` int NOT NULL,
  `FoodID` int DEFAULT NULL,
  `Text` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Required` tinyint(1) DEFAULT NULL,
  `Type` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `ID` int NOT NULL,
  `Username` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Name` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Email` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Phone` varchar(15) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Password` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `AddressID` int DEFAULT NULL,
  `UserType` int NOT NULL DEFAULT '1',
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`ID`, `Username`, `Name`, `Email`, `Phone`, `Password`, `AddressID`, `UserType`, `Active`) VALUES
(1, 'mcdonalds', 'McDonalds', 'mcdonalds@gmail.com', NULL, '123', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `streets`
--

CREATE TABLE `streets` (
  `ID` int NOT NULL,
  `NeighborhoodName` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `DistrictID` int DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `streets`
--

INSERT INTO `streets` (`ID`, `NeighborhoodName`, `DistrictID`, `Active`) VALUES
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
  `ID` int NOT NULL,
  `Username` varchar(30) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Name` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Email` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Phone` varchar(15) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `Password` varchar(60) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `RestaurantID` int DEFAULT NULL,
  `AddressID` int DEFAULT NULL,
  `UserType` int NOT NULL DEFAULT '0',
  `Active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Name`, `Email`, `Phone`, `Password`, `RestaurantID`, `AddressID`, `UserType`, `Active`) VALUES
(1, 'aga', 'McDonaldsss', 'aga@gmail.com', '123123', '123', NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `foodcategories`
--
ALTER TABLE `foodcategories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `menufoods`
--
ALTER TABLE `menufoods`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orderdetailquestionanswer`
--
ALTER TABLE `orderdetailquestionanswer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `questionanswers`
--
ALTER TABLE `questionanswers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `streets`
--
ALTER TABLE `streets`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodcategories`
--
ALTER TABLE `foodcategories`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menufoods`
--
ALTER TABLE `menufoods`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetailquestionanswer`
--
ALTER TABLE `orderdetailquestionanswer`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionanswers`
--
ALTER TABLE `questionanswers`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `streets`
--
ALTER TABLE `streets`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
