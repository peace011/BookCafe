-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2023 at 04:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `obbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 5689784589, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2020-01-21 11:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `ID` int(10) NOT NULL,
  `BookingID` int(10) DEFAULT NULL,
  `ServiceID` int(10) DEFAULT NULL,
  `UserID` int(5) DEFAULT NULL,
  `BookingFrom` date DEFAULT NULL,
  `BookingTo` time DEFAULT NULL,
  `TableType` varchar(250) NOT NULL,
  `Numberofguest` int(10) DEFAULT NULL,
  `Message` mediumtext DEFAULT NULL,
  `BookingDate` timestamp NULL DEFAULT current_timestamp(),
  `Remark` varchar(200) DEFAULT NULL,
  `Status` varchar(200) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Payment` int(250) NOT NULL,
  `PaymentAmt` int(250) NOT NULL,
  `ServiceStatus` int(50) NOT NULL,
  `ItemID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`ID`, `BookingID`, `ServiceID`, `UserID`, `BookingFrom`, `BookingTo`, `TableType`, `Numberofguest`, `Message`, `BookingDate`, `Remark`, `Status`, `UpdationDate`, `Payment`, `PaymentAmt`, `ServiceStatus`, `ItemID`) VALUES
(260, 172817330, 15, 6, '2023-12-30', NULL, 'Table 6', 1, 'ok', '2023-12-27 15:00:58', 'ok', 'Approved', '2023-12-27 15:03:39', 0, 0, 0, NULL),
(261, 873515472, 13, 6, '2023-12-30', NULL, 'Table 6', 1, 'ok', '2023-12-27 15:10:41', 'ok', 'Approved', '2023-12-27 15:18:12', 1, 33, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(11) NOT NULL,
  `CategoryName` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `CategoryName`, `CreationDate`) VALUES
(1, 'Fantasy', '2023-09-26 11:18:59'),
(2, 'Horror', '2023-09-26 11:18:59'),
(3, 'Novel', '2023-09-26 11:18:59'),
(4, 'Manga', '2023-12-23 15:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontact`
--

CREATE TABLE `tblcontact` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Message` mediumtext DEFAULT NULL,
  `EnquiryDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsRead` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontact`
--

INSERT INTO `tblcontact` (`ID`, `Name`, `Email`, `Message`, `EnquiryDate`, `IsRead`) VALUES
(1, 'Kiran', 'kran@gmail.com', 'location exact of Bookcafe', '2021-07-05 07:26:24', 1),
(2, 'Soniya Pandey', 'sar@gmail.com', 'address', '2021-07-09 12:48:40', 1),
(5, 'Koshila Tamang', 'meenu@gmail.com', 'gugjhjhgjhgjwerte', '2022-02-15 06:30:58', NULL),
(8, 'Lisa', 'l@g.com', 'Is there True Beauty book?', '2023-12-23 15:21:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblitem`
--

CREATE TABLE `tblitem` (
  `ID` int(250) NOT NULL,
  `ItemName` varchar(250) NOT NULL,
  `ItemDes` varchar(250) NOT NULL,
  `ItemPrice` int(11) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblitem`
--

INSERT INTO `tblitem` (`ID`, `ItemName`, `ItemDes`, `ItemPrice`, `CreationDate`) VALUES
(1, 'Chocolate Pastry', 'c', 3, '2023-12-23 15:50:18'),
(2, 'Cappacino', 'Coffee', 5, '2023-12-23 15:50:27'),
(3, 'Vegan Cake', 'This cake contains no dairy products.', 5, '2023-12-23 15:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

CREATE TABLE `tblorder` (
  `ID` int(11) NOT NULL,
  `BID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorder`
--

INSERT INTO `tblorder` (`ID`, `BID`, `ItemID`, `Quantity`) VALUES
(222, 260, 1, 1),
(223, 260, 2, 1),
(224, 260, 3, 1),
(225, 261, 1, 1),
(226, 261, 2, 1),
(227, 261, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(100) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`) VALUES
(1, 'aboutus', 'About Us', '<b>Book Cafe</b><div><b>Book Cafe is one of the Internet\'s largest and trusted online reservation service along with the books. </b></div><div><b><br></b></div><div><b>&nbsp;.&nbsp;</b></div>', NULL, NULL, '2023-11-21 09:46:33'),
(2, 'contactus', 'Contact Us', 'Lainchor , Kathmandu', 'bookcafe@gmail.com', 9811234556, '2023-11-21 09:49:46');

-- --------------------------------------------------------

--
-- Table structure for table `tblservice`
--

CREATE TABLE `tblservice` (
  `ID` int(10) NOT NULL,
  `ServiceName` varchar(200) DEFAULT NULL,
  `ServiceImage` varchar(250) NOT NULL,
  `ServiceAuthor` varchar(250) NOT NULL,
  `SerDes` varchar(250) NOT NULL,
  `ServicePrice` varchar(200) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `SerAvailable` int(255) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblservice`
--

INSERT INTO `tblservice` (`ID`, `ServiceName`, `ServiceImage`, `ServiceAuthor`, `SerDes`, `ServicePrice`, `CreationDate`, `SerAvailable`, `CategoryID`) VALUES
(13, 'Dark Forest', 'uploads/Dark Forest.jpeg', 'Vernon ', 'A person goes in search of his reincarnation.', '20', '2023-07-26 09:26:44', 5, 2),
(14, 'The Fallen Gates', 'uploads/The Fallen gates.jpeg', 'Park Eunwoo', 'A lovely princess that is binded by a magic gates.', '10', '2023-07-26 09:28:44', 2, 1),
(15, 'Daughter of HavenGlade', 'uploads/Daughter of Haven Glade.jpg', 'H.C.Harrington', 'A sleeping beauty that was cursed by the witches.', '10', '2023-07-28 08:02:44', 2, 1),
(16, 'Those Eyes', 'uploads/Those eyes.jpeg', 'Steven Cruise', 'A horror based story of a lost girl', '20', '2023-12-05 08:25:13', 1, 2),
(17, 'Astor', 'uploads/Astor.jpeg', 'Anderson Cooper', 'The rise and fall of a American fortune.', '80', '2023-12-05 08:27:42', 10, 3),
(18, 'The Forgotten Mother', 'uploads/The forgotten mother.jpeg', 'James Shong', 'A story about a mother who was victim of many cases.', '50', '2023-12-05 09:50:00', 8, 2),
(19, 'Wings of Fire', 'uploads/Wings of fire.jpeg', 'William Peter Blatty', 'A story about the victims that was isolated by many people..', '40', '2023-12-05 09:51:57', 12, 2),
(20, 'Scary Ghost', 'uploads/Scary Ghost.jpg', 'Alma Katsu', 'This is based on a horror based game played by teenagers. ', '40', '2023-12-05 09:53:05', 6, 2),
(21, 'Edge of the World', 'uploads/Edge of the world.jpeg', 'George R. R. Martin', 'A King that conquers all the states and unites it.', '60', '2023-12-05 09:54:53', 6, 1),
(23, 'Feeling of Warmth', 'uploads/Feeling of warmth.jpeg', 'Vernon', 'This is about warmth that comes from closest people.', '60', '2023-12-05 09:58:06', 11, 3),
(25, 'Ottoman Empire', 'uploads/Ottoman Europe.jpeg', 'George R. R. Martin', 'This is based on building the fragment states in a empire.', '50', '2023-12-05 11:00:20', 9, 3),
(28, 'Hanna', 'uploads/Hanna.jpg', 'Vernon', 'This is related to the power of manifestation.', '50', '2023-12-13 13:13:43', 15, 3),
(31, 'The Rest is History', 'uploads/The rest is history.jpg', 'H.C.Harrington', 'A book based on how the history is created.', '40', '2023-12-13 13:26:48', 9, 3),
(33, 'Think straights', 'uploads/think straight.jpeg', 'Vernon', 'Think straigt', '20', '2023-12-13 14:53:56', 7, 1),
(34, 'JInx Love', 'uploads/love.jpeg', 'Alexa Lincoln', 'A book about a boxer who had hand injuries in a final competition and he met a doctor. That\'s how the two of them met and start to connect with each other.', '40', '2023-12-23 15:16:28', 4, 4),
(35, 'Remember us', 'uploads/Jacqueline Woodson.jpg', 'Jacqueline Woodson', 'A book about a girl that gains her self confidence int his fake world.', '10', '2023-12-23 15:31:45', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbltable`
--

CREATE TABLE `tbltable` (
  `ID` int(250) NOT NULL,
  `TableType` varchar(250) NOT NULL,
  `TableStatus` int(250) NOT NULL,
  `TableCapacity` varchar(250) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltable`
--

INSERT INTO `tbltable` (`ID`, `TableType`, `TableStatus`, `TableCapacity`, `CreationDate`) VALUES
(1, 'Table 1', 0, 'Solo Table: For 1 people', '2023-12-25 12:52:32'),
(2, 'Table 2', 0, 'Dual Table: For 2 people', '2023-12-27 10:42:02'),
(3, 'Table 3', 0, 'Study Table: For 3 people', '2023-12-27 10:40:33'),
(4, 'Table 4', 1, 'Group Table: For 4 people', '2023-12-25 12:51:48'),
(5, 'Table 5', 0, 'Group Table: For 5 people', '2023-12-27 10:41:04'),
(17, 'Table 6', 0, 'Group Table: For 6 people', '2023-12-27 10:41:25'),
(18, 'Table 7', 0, 'Group table:  For 7 people', '2023-12-27 10:41:40'),
(19, 'Table 8', 0, 'Solo Table: For 1 people', '2023-12-27 10:43:51'),
(20, 'Table 9', 0, 'Dual Table: For 2 People', '2023-12-27 10:44:15'),
(21, 'Table 10', 0, 'Group Table: For 10 people', '2023-12-27 10:44:53'),
(22, 'Table 11', 0, 'Dual Table: For 2 people', '2023-12-27 10:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbltableavailability`
--

CREATE TABLE `tbltableavailability` (
  `ID` int(250) NOT NULL,
  `TableID` int(250) NOT NULL,
  `TableType` varchar(250) NOT NULL,
  `AvailableDate` date NOT NULL,
  `AvailableTime` time NOT NULL,
  `AvailableEndTime` time NOT NULL,
  `AvailableStatus` int(250) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltableavailability`
--

INSERT INTO `tbltableavailability` (`ID`, `TableID`, `TableType`, `AvailableDate`, `AvailableTime`, `AvailableEndTime`, `AvailableStatus`, `CreationDate`) VALUES
(3, 1, 'Table 1', '2023-12-29', '11:00:00', '14:00:00', 1, '2023-12-27 13:11:53'),
(4, 2, 'Table 2', '2023-12-30', '13:00:00', '16:00:00', 1, '2023-12-27 13:12:56'),
(5, 3, 'Table 3', '2023-12-31', '10:00:00', '13:00:00', 1, '2023-12-27 13:18:26'),
(6, 5, 'Table 5', '2023-01-10', '14:00:00', '17:00:00', 1, '2023-12-27 13:18:51'),
(7, 18, 'Table 7', '2023-01-04', '12:00:00', '15:00:00', 1, '2023-12-27 13:19:03'),
(8, 5, 'Table 5', '2023-12-29', '15:00:00', '18:00:00', 1, '2023-12-27 13:19:22'),
(9, 4, 'Table 4', '2023-12-29', '16:00:00', '19:00:00', 1, '2023-12-27 13:19:38'),
(11, 18, 'Table 7', '2023-12-31', '14:00:00', '17:00:00', 1, '2023-12-27 13:19:55'),
(12, 21, 'Table 10', '2024-01-01', '12:00:00', '15:00:00', 1, '2023-12-27 13:20:10'),
(13, 3, 'Table 3', '2023-12-31', '11:00:00', '14:00:00', 1, '2023-12-27 13:20:25'),
(14, 19, 'Table 8', '2023-12-31', '14:00:00', '17:00:00', 1, '2023-12-27 13:21:34'),
(16, 17, 'Table 6', '2023-12-30', '13:00:00', '16:00:00', 0, '2023-12-27 15:12:26'),
(17, 18, 'Table 7', '2023-12-30', '11:00:00', '14:00:00', 1, '2023-12-27 13:47:27'),
(18, 21, 'Table 10', '2023-12-28', '13:00:00', '16:00:00', 1, '2023-12-27 13:21:14'),
(19, 22, 'Table 11', '2024-01-01', '15:00:00', '18:00:00', 1, '2023-12-27 13:20:51'),
(20, 5, 'Table 5', '2024-01-02', '11:00:00', '14:00:00', 1, '2023-12-27 13:20:37'),
(21, 17, 'Table 6', '2023-12-30', '10:00:00', '12:00:00', 1, '2023-12-27 15:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `FullName`, `MobileNumber`, `Email`, `Password`, `RegDate`) VALUES
(6, 'puja', 9876543210, 'p@g.com', '670b14728ad9902aecba32e22fa4f6bd', '2023-07-24 10:17:53'),
(11, 'Jennie', 9852658963, 'j@gmail.com', '37b5f21d05fad230f65518b3d22a92b7', '2023-12-19 07:08:19'),
(12, 'Roshina', 9876543210, 'r@g.com', '894875f8c1462a1dd0494e32156fdf32', '2023-12-23 15:25:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ServiceID` (`ServiceID`),
  ADD KEY `fk_item` (`ItemID`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblitem`
--
ALTER TABLE `tblitem`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblservice`
--
ALTER TABLE `tblservice`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `tbltable`
--
ALTER TABLE `tbltable`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbltableavailability`
--
ALTER TABLE `tbltableavailability`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblitem`
--
ALTER TABLE `tblitem`
  MODIFY `ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblservice`
--
ALTER TABLE `tblservice`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbltable`
--
ALTER TABLE `tbltable`
  MODIFY `ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbltableavailability`
--
ALTER TABLE `tbltableavailability`
  MODIFY `ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD CONSTRAINT `serivdi` FOREIGN KEY (`ServiceID`) REFERENCES `tblservice` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
