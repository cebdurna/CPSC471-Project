-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 04, 2022 at 10:35 PM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `booked_at`
--

CREATE TABLE `booked_at` (
  `Hotel_ID` varchar(255) NOT NULL,
  `Room_Number` int(11) NOT NULL,
  `Booking_Number` int(11) NOT NULL,
  `Customer_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Number` int(11) NOT NULL,
  `Customer_ID` varchar(255) NOT NULL,
  `Check_Out_Date` date NOT NULL,
  `Check_In_Date` date NOT NULL,
  `CC_Number` varchar(255) NOT NULL,
  `Invoice_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charge`
--

CREATE TABLE `charge` (
  `Charge_ID` varchar(255) NOT NULL,
  `Invoice_ID` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Tax` float NOT NULL,
  `Price` float NOT NULL,
  `ChargeTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_card`
--

CREATE TABLE `credit_card` (
  `CC_Number` varchar(255) NOT NULL,
  `Cardholder_Name` varchar(255) NOT NULL,
  `Expiry` date NOT NULL,
  `CVV` varchar(255) NOT NULL,
  `Street_Address` varchar(255) NOT NULL,
  `Postal_Code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Phone_Number` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Birthdate` date NOT NULL,
  `Full_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_ID` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Hotel_ID` varchar(255) NOT NULL,
  `Birthdate` varchar(255) NOT NULL,
  `Job_Title` varchar(255) NOT NULL,
  `Full_Name` varchar(255) NOT NULL,
  `Salary` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `ID` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Invoice_ID` varchar(255) NOT NULL,
  `Format` varchar(255) NOT NULL,
  `Date_created` date NOT NULL,
  `Date_due` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paid_with`
--

CREATE TABLE `paid_with` (
  `CC_Number` varchar(255) NOT NULL,
  `Transaction_Number` int(11) NOT NULL,
  `Invoice_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Transaction_Number` int(11) NOT NULL,
  `Invoice_ID` varchar(255) NOT NULL,
  `Amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `Number` int(11) NOT NULL,
  `Hotel_ID` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Beds` int(11) NOT NULL,
  `Floor` int(11) NOT NULL,
  `Furniture` varchar(255) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Orientation` varchar(255) NOT NULL,
  `Rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `Service_ID` varchar(255) NOT NULL,
  `Hotel_ID` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booked_at`
--
ALTER TABLE `booked_at`
  ADD PRIMARY KEY (`Hotel_ID`,`Room_Number`,`Booking_Number`,`Customer_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Booking_Number` (`Booking_Number`),
  ADD KEY `Room_Number` (`Room_Number`),
  ADD KEY `Hotel_ID` (`Hotel_ID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Number`,`Customer_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `CC_Number` (`CC_Number`),
  ADD KEY `Invoice_ID` (`Invoice_ID`);

--
-- Indexes for table `charge`
--
ALTER TABLE `charge`
  ADD PRIMARY KEY (`Charge_ID`,`Invoice_ID`),
  ADD KEY `Invoice_ID` (`Invoice_ID`);

--
-- Indexes for table `credit_card`
--
ALTER TABLE `credit_card`
  ADD PRIMARY KEY (`CC_Number`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`,`Username`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_ID`,`Username`),
  ADD KEY `Hotel_ID` (`Hotel_ID`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Invoice_ID`);

--
-- Indexes for table `paid_with`
--
ALTER TABLE `paid_with`
  ADD PRIMARY KEY (`CC_Number`,`Transaction_Number`,`Invoice_ID`),
  ADD KEY `Transaction_Number` (`Transaction_Number`),
  ADD KEY `Invoice_ID` (`Invoice_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Transaction_Number`,`Invoice_ID`),
  ADD KEY `Invoice_ID` (`Invoice_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`Number`,`Hotel_ID`),
  ADD KEY `Hotel_ID` (`Hotel_ID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`Service_ID`),
  ADD KEY `Hotel_ID` (`Hotel_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booked_at`
--
ALTER TABLE `booked_at`
  ADD CONSTRAINT `booked_at_ibfk_2` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booked_at_ibfk_3` FOREIGN KEY (`Booking_Number`) REFERENCES `booking` (`Number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booked_at_ibfk_4` FOREIGN KEY (`Room_Number`) REFERENCES `room` (`Number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booked_at_ibfk_5` FOREIGN KEY (`Hotel_ID`) REFERENCES `hotel` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`CC_Number`) REFERENCES `credit_card` (`CC_Number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `charge`
--
ALTER TABLE `charge`
  ADD CONSTRAINT `charge_ibfk_1` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`Hotel_ID`) REFERENCES `hotel` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `paid_with`
--
ALTER TABLE `paid_with`
  ADD CONSTRAINT `paid_with_ibfk_1` FOREIGN KEY (`CC_Number`) REFERENCES `credit_card` (`CC_Number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `paid_with_ibfk_2` FOREIGN KEY (`Transaction_Number`) REFERENCES `payment` (`Transaction_Number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `paid_with_ibfk_3` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`Hotel_ID`) REFERENCES `hotel` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`Hotel_ID`) REFERENCES `hotel` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
