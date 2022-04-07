-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 07, 2022 at 10:46 PM
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
-- Database: `hotels`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `customerloginget` (IN `username` VARCHAR(255), IN `password` VARCHAR(255))  NO SQL
SELECT Customer_ID
FROM customer
WHERE customer.Username = username AND customer.Password = password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_registration_post` (IN `username` VARCHAR(255), IN `password` VARCHAR(255), IN `phone_no` VARCHAR(255), IN `email` VARCHAR(255), IN `birthdate` DATE, IN `name` VARCHAR(255))  MODIFIES SQL DATA
BEGIN
insert into customer(Customer_ID, Username, Password, Phone_Number, Email, Birthdate, Full_Name)
values(UUID(), username, password, phone_no, email, birthdate, name);

select Customer_ID
from customer
where customer.password = password and customer.username = username;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employee_invoice_detail_payment` (IN `invoice_id` VARCHAR(255), IN `cc_no` VARCHAR(255), IN `amount` FLOAT, IN `date` DATE)  MODIFIES SQL DATA
BEGIN
insert into payment(Invoice_ID, Amount, Date) values(invoice_id, amount, date);
DO SLEEP(0.2);
insert into paid_with values(cc_no,LAST_INSERT_ID(),invoice_id);
END$$

DELIMITER ;

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
  `Charge_ID` int(11) NOT NULL,
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

--
-- Dumping data for table `credit_card`
--

INSERT INTO `credit_card` (`CC_Number`, `Cardholder_Name`, `Expiry`, `CVV`, `Street_Address`, `Postal_Code`) VALUES
('123456789', 'hello', '0000-00-00', 'a', 'a', 'a');

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

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `Username`, `Password`, `Phone_Number`, `Email`, `Birthdate`, `Full_Name`) VALUES
('a85d6d50-b60d-11ec-a833-2cf05d928cb4', 'abc', '123', '123456', 'abcdef@aaa', '2022-01-01', 'hello');

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

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Invoice_ID`, `Format`, `Date_created`, `Date_due`) VALUES
('abcdef', 'a', '0000-00-00', '0000-00-00');

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
  `Amount` float NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `Number` int(11) NOT NULL,
  `Hotel_ID` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Beds` varchar(255) NOT NULL,
  `Floor` varchar(255) NOT NULL,
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `charge`
--
ALTER TABLE `charge`
  MODIFY `Charge_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Transaction_Number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
