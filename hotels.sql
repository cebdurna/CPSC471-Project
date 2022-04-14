-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 11, 2022 at 01:32 AM
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
CREATE DATABASE hotels;

USE hotels;

DELIMITER $$
--
-- Procedures
--

CREATE DEFINER=`root`@`localhost` PROCEDURE `customeravailabilityget`(IN `checkInDate` DATE, IN `checkOutDate` DATE)
    NO SQL
SELECT room.Number, room.Type, room.Beds, room.Floor, room.Furniture, room.Capacity, room.Orientation, room.Rate
FROM room
WHERE NOT EXISTS (
	SELECT room2.Number
    FROM ((room as room2 JOIN booked_at ON room2.Number=booked_at.Room_Number AND room2.Hotel_ID=booked_at.Hotel_ID)
   		JOIN booking ON booking.Customer_ID=booked_at.Customer_ID AND booking.Number=booked_at.Booking_Number)
    WHERE (checkOutDate > booking.Check_In_Date AND checkInDate < booking.Check_Out_Date) AND room2.Number = room.Number
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerbookingget`(IN `customerID` INT(11))
    NO SQL
SELECT booking.Number, booked_at.Room_Number, booking.Check_In_Date, booking.Check_Out_Date, booking.CC_Number, booking.Invoice_ID, Sum(charge.Tax + charge.Price) as Total

FROM (((booking JOIN booked_at ON booking.Number=booked_at.Booking_Number AND booking.Customer_ID=booked_at.Customer_ID)
     	JOIN room ON room.Number=booked_at.Room_Number AND room.Hotel_ID=booked_at.Hotel_ID)
        		JOIN charge ON charge.Invoice_ID=booking.Invoice_ID)

WHERE booking.Customer_ID=customerID

GROUP BY Invoice_ID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerbookingpost` (IN `customerID` int(11), IN `roomNumber` int(11), IN `checkInDate` DATE, IN `checkOutDate` DATE, IN `ccNumber` VARCHAR(255), IN `ccName` VARCHAR(255), IN `ccExpiry` DATE, IN `cvv` INT, IN `ccAddress` VARCHAR(255), IN `ccPostal` VARCHAR(255))  NO SQL
BEGIN

SET @total = (
	SELECT room.Rate
	FROM room
	WHERE room.Number = roomNumber
);

SET @tax = (@total * 0.05);

INSERT IGNORE INTO credit_card
VALUES (ccNumber, ccName, ccExpiry, cvv, ccAddress, ccPostal);

# Might need to make invoice id column an integer for the MAX function
# Jeff already set it to integer type in his local branch

SET @invoice_id = (SELECT IFNULL(MAX(Invoice_ID) + 1, 1) FROM invoice);

INSERT INTO invoice
VALUES (@invoice_id, "Digital", CAST(NOW() as date), checkInDate);

SET @charge_id = (SELECT IFNULL(MAX(Charge_ID) + 1, 1) FROM charge);

INSERT INTO charge
VALUES (@charge_id, @invoice_id, "Room(s) Charge", @tax, @total, NOW());

# Now make booking\

SET @booking_number = (SELECT IFNULL(MAX(Number) + 1, 1) FROM booking);

INSERT INTO booking
VALUES (@booking_number, customerID, checkOutDate, checkInDate, ccNumber, @invoice_id);

# Now make booked_at

INSERT INTO booked_at
VALUES ("1", roomNumber, @booking_number, customerID);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerinvoicedetailcharges` (IN `invoiceID` int(11))  NO SQL
SELECT charge.Description, charge.Price, charge.Tax, charge.ChargeTime
FROM charge
WHERE charge.Invoice_ID=invoiceID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerinvoicedetailpayments` (IN `invoiceID` int(11))  NO SQL
SELECT payment.Transaction_Number, paid_with.CC_Number, payment.Amount, payment.Date
FROM (payment JOIN paid_with ON payment.Transaction_Number=paid_with.Transaction_Number AND payment.Invoice_ID=paid_with.Invoice_ID)
WHERE paid_with.Invoice_ID=invoiceID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerinvoiceget` (IN `customerID` int(11))  NO SQL
SELECT invoice.Invoice_ID, invoice.Format, invoice.Date_created, invoice.Date_due, booking.Number as booking_no, SUM(charge.Price + charge.Tax) as total, payments.TotalAmount as total_paid
FROM (((invoice JOIN booking ON invoice.Invoice_ID=booking.Invoice_ID)
      JOIN charge ON invoice.Invoice_ID=charge.Invoice_ID)
      		JOIN (
                SELECT payment.Invoice_ID, SUM(payment.Amount) as TotalAmount
                FROM payment
                GROUP BY payment.Invoice_ID) payments ON invoice.Invoice_ID=payments.Invoice_ID)
WHERE booking.Customer_ID=customerID

GROUP BY Invoice_ID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerloginget` (IN `username` VARCHAR(255), IN `password` VARCHAR(255))  NO SQL
SELECT Customer_ID
FROM customer
WHERE customer.Username = username AND customer.Password = password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeebookingsget` (IN `hotelID` int(11))  NO SQL
SELECT booking.Number, booking.Check_In_Date, booking.Check_Out_Date, booked_at.Room_Number, booking.Customer_ID, booking.Invoice_ID, booking.CC_Number
FROM (booking JOIN booked_at ON booking.Number=booked_at.Booking_Number)
WHERE booked_at.Hotel_ID = hotelID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeebookingspost` (IN `customerID` int(11), IN `roomNumber` int(11), IN `checkInDate` DATE, IN `checkOutDate` DATE, IN `ccNumber` VARCHAR(255), IN `ccName` VARCHAR(255), IN `ccExpiry` DATE, IN `cvv` INT, IN `ccAddress` VARCHAR(255), IN `ccPostal` VARCHAR(255))  NO SQL
BEGIN

SET @total = (
	SELECT room.Rate
	FROM room
	WHERE room.Number = roomNumber
);

SET @tax = (@total * 0.05);

INSERT IGNORE INTO credit_card
VALUES (ccNumber, ccName, ccExpiry, cvv, ccAddress, ccPostal);

# Might need to make invoice id column an integer for the MAX function
# Jeff already set it to integer type in his local branch

SET @invoice_id = (SELECT IFNULL(MAX(Invoice_ID) + 1, 1) FROM invoice);

INSERT INTO invoice
VALUES (@invoice_id, "Digital", CAST(NOW() as date), checkInDate);

SET @charge_id = (SELECT IFNULL(MAX(Charge_ID) + 1, 1) FROM charge);

INSERT INTO charge
VALUES (@charge_id, @invoice_id, "Room(s) Charge", @tax, @total, NOW());

# Now make booking\

SET @booking_number = (SELECT IFNULL(MAX(Number) + 1, 1) FROM booking);

INSERT INTO booking
VALUES (@booking_number, customerID, checkOutDate, checkInDate, ccNumber, @invoice_id);

# Now make booked_at

INSERT INTO booked_at
VALUES ("1", roomNumber, @booking_number, customerID);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeebookingsput`(IN `bookingNo` INT, IN `roomNumber` VARCHAR(255), IN `checkInDate` DATE, IN `checkOutDate` DATE, IN `ccNumber` VARCHAR(255), IN `ccName` VARCHAR(255), IN `ccExpiry` DATE, IN `cvv` INT, IN `ccAddress` VARCHAR(255), IN `ccPostal` VARCHAR(255))
    NO SQL
BEGIN

SET @total = (
	SELECT room.Rate
	FROM room
	WHERE room.Number = roomNumber
);

SET @tax = (@total * 0.05);

INSERT IGNORE INTO credit_card
VALUES (ccNumber, ccName, ccExpiry, cvv, ccAddress, ccPostal);

# Update invoice date created and due date

SET @invoice_id = (SELECT booking.Invoice_ID from booking where booking.Number=bookingNo);

UPDATE invoice
SET invoice.Date_created=CAST(NOW() as date), invoice.Date_due=checkInDate
WHERE invoice.Invoice_ID=@invoice_id;

# Update charge with new total price

SET @charge_id = (SELECT charge.Charge_ID FROM charge WHERE charge.Invoice_ID=@invoice_id AND charge.Description LIKE "Room(s) Charge");

UPDATE charge
SET charge.Tax = @tax, charge.Price=@total, charge.ChargeTime=NOW()
WHERE charge.Description LIKE "Room(s) Charge" AND charge.Invoice_ID=@invoice_id;

# Update booking

UPDATE booking
SET booking.Check_In_Date=checkInDate, booking.Check_Out_Date=checkOutDate, booking.CC_Number=ccNumber
WHERE booking.Number = bookingNo;

# Update booked_at

UPDATE booked_at
SET booked_at.Room_Number=roomNumber
WHERE booked_at.Booking_Number=bookingNo;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeloginget` (IN `username` VARCHAR(255), IN `password` VARCHAR(255))  NO SQL
SELECT employee.Employee_ID
FROM employee
WHERE employee.Username = username AND employee.Password = password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeroomsdelete` (IN `hotelID` int(11), IN `roomNo` INT)  NO SQL
DELETE FROM room
WHERE room.Hotel_ID=hotelID AND room.Number=roomNo$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeroomsget` (IN `hotel` int(11))  NO SQL
SELECT *
FROM room
WHERE room.Hotel_ID = hotel$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeroomspost` (IN `room_no` int(11), IN `hotelID` int(11), IN `type` VARCHAR(255), IN `beds` VARCHAR(255), IN `floor` VARCHAR(255), IN `furniture` VARCHAR(255), IN `capacity` INT, IN `orientation` VARCHAR(255), IN `rate` FLOAT)  NO SQL
INSERT IGNORE INTO room
VALUES (room_no, hotelID, type, beds, floor, furniture, capacity, orientation, rate)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeroomsput` (IN `room_no` int(11), IN `hotelID` int(11), IN `type` VARCHAR(255), IN `beds` VARCHAR(255), IN `floor` VARCHAR(255), IN `furniture` VARCHAR(255), IN `capacity` INT, IN `orientation` VARCHAR(255), IN `rate` FLOAT)  NO SQL
UPDATE room
SET Type=type, Beds=beds, Floor=floor, Furniture=furniture, Capacity=capacity, Orientation=orientation, Rate=rate
WHERE Number=room_no AND Hotel_ID=hotelID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `servicesget` (IN `hotel` int(11))  NO SQL
SELECT service.Description, service.Price
FROM service
WHERE service.Hotel_ID = hotel$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `invoice_getall`()
    READS SQL DATA
SELECT invoice.Invoice_ID, invoice.Format, invoice.Date_created, invoice.Date_due, booking.Number as booking_no, SUM(charge.Price + charge.Tax) as total, payments.TotalAmount as total_paid
FROM (((invoice JOIN booking ON invoice.Invoice_ID=booking.Invoice_ID)
      JOIN charge ON invoice.Invoice_ID=charge.Invoice_ID)
      		JOIN (
                SELECT payment.Invoice_ID, SUM(payment.Amount) as TotalAmount
                FROM payment
                GROUP BY payment.Invoice_ID) payments ON invoice.Invoice_ID=payments.Invoice_ID)

GROUP BY Invoice_ID$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `invoice_detail_get_payment`(IN `invoice_id` INT)
    READS SQL DATA
SELECT payment.Transaction_Number, paid_with.CC_Number, payment.Amount, payment.Date
FROM (payment JOIN paid_with ON payment.Transaction_Number=paid_with.Transaction_Number AND payment.Invoice_ID=paid_with.Invoice_ID)
WHERE paid_with.Invoice_ID=invoice_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `invoice_detail_get_charges`(IN `invoice_id` INT)
    READS SQL DATA
SELECT *
FROM charge
WHERE charge.Invoice_ID=invoice_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `employee_invoice_detail_charge`(IN `invoice_id` INT, IN `description` VARCHAR(255), IN `price` FLOAT, IN `charge_time` DATETIME)
    MODIFIES SQL DATA
BEGIN
set @tax = (0.05 * price);

insert into charge(Invoice_ID, Description, Tax, Price, ChargeTime)
values(invoice_id, description, @tax, price, charge_time);

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `employee_invoice_detail_payment`(IN `invoice_id` INT, IN `cc_no` VARCHAR(255), IN `amount` FLOAT, IN `date` DATE)
    MODIFIES SQL DATA
BEGIN
insert into payment(Invoice_ID, Amount, Date) values(invoice_id, amount, date);
DO SLEEP(0.2);
insert into paid_with values(cc_no,LAST_INSERT_ID(),invoice_id);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `invoice_detail_update`(IN `invoice_id` INT, IN `form` VARCHAR(255), IN `date_created` DATE, IN `date_due` DATE)
    MODIFIES SQL DATA
update invoice
set invoice.Format = form, invoice.Date_created=date_created, invoice.Date_due=date_due
where invoice.Invoice_ID=invoice_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `services_delete`(IN `service_id` INT)
    MODIFIES SQL DATA
delete FROM service
where service.Service_ID = service_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `services_get`()
    READS SQL DATA
select * 
from service$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `services_post`(IN `hotel_id` INT, IN `description` VARCHAR(255), IN `price` FLOAT)
    MODIFIES SQL DATA
insert into service (service.Hotel_ID, service.Description, service.Price)
values(hotel_id, description, price)$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `services_update`(IN `service_id` INT, IN `hotel_id` INT, IN `description` VARCHAR(255), IN `price` FLOAT)
    MODIFIES SQL DATA
update service
set service.Hotel_ID = hotel_id, service.Description = description, service.Price = price
where service.Service_ID = service_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `bookings_delete`(IN `book_no` INT, IN `hotel_id` INT)
    MODIFIES SQL DATA
BEGIN

delete from booked_at
where booked_at.Hotel_ID = hotel_id and  booked_at.Booking_Number = book_no;

set @invoice_id = (
    select booking.Invoice_ID 
    from booking 
    where booking.Number= book_no);

delete from booking
where booking.Number = book_no;

delete from paid_with
where paid_with.Invoice_ID = @invoice_id;

delete from charge
where charge.Invoice_ID = @invoice_id;

delete from payment
where payment.Invoice_ID = @invoice_id;

delete from invoice
where invoice.Invoice_ID = @invoice_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `customer_registration_post`(IN `username` VARCHAR(255), IN `password` VARCHAR(255), IN `phone_no` VARCHAR(255), IN `email` VARCHAR(255), IN `birthdate` DATE, IN `name` VARCHAR(255))
    MODIFIES SQL DATA
BEGIN
insert into customer(Username, Password, Phone_Number, Email, Birthdate, Full_Name)
values(username, password, phone_no, email, birthdate, name);

select Customer_ID
from customer
where customer.password = password and customer.username = username;
END$$

DELIMITER ;



-- --------------------------------------------------------

--
-- Table structure for table `auth_group`
--

CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_group_permissions`
--

CREATE TABLE `auth_group_permissions` (
  `id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permission`
--

CREATE TABLE `auth_permission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `codename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `auth_permission`
--

INSERT INTO `auth_permission` (`id`, `name`, `content_type_id`, `codename`) VALUES
(1, 'Can add log entry', 1, 'add_logentry'),
(2, 'Can change log entry', 1, 'change_logentry'),
(3, 'Can delete log entry', 1, 'delete_logentry'),
(4, 'Can view log entry', 1, 'view_logentry'),
(5, 'Can add permission', 2, 'add_permission'),
(6, 'Can change permission', 2, 'change_permission'),
(7, 'Can delete permission', 2, 'delete_permission'),
(8, 'Can view permission', 2, 'view_permission'),
(9, 'Can add group', 3, 'add_group'),
(10, 'Can change group', 3, 'change_group'),
(11, 'Can delete group', 3, 'delete_group'),
(12, 'Can view group', 3, 'view_group'),
(13, 'Can add user', 4, 'add_user'),
(14, 'Can change user', 4, 'change_user'),
(15, 'Can delete user', 4, 'delete_user'),
(16, 'Can view user', 4, 'view_user'),
(17, 'Can add content type', 5, 'add_contenttype'),
(18, 'Can change content type', 5, 'change_contenttype'),
(19, 'Can delete content type', 5, 'delete_contenttype'),
(20, 'Can view content type', 5, 'view_contenttype'),
(21, 'Can add session', 6, 'add_session'),
(22, 'Can change session', 6, 'change_session'),
(23, 'Can delete session', 6, 'delete_session'),
(24, 'Can view session', 6, 'view_session');

-- --------------------------------------------------------

--
-- Table structure for table `auth_user`
--

CREATE TABLE `auth_user` (
  `id` int(11) NOT NULL,
  `password` varchar(128) NOT NULL,
  `last_login` datetime(6) DEFAULT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `username` varchar(150) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(254) NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_joined` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_groups`
--

CREATE TABLE `auth_user_groups` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_user_permissions`
--

CREATE TABLE `auth_user_user_permissions` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booked_at`
--

CREATE TABLE `booked_at` (
  `Hotel_ID` int(11) NOT NULL,
  `Room_Number` int(11) NOT NULL,
  `Booking_Number` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Number` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Check_Out_Date` date NOT NULL,
  `Check_In_Date` date NOT NULL,
  `CC_Number` varchar(255) NOT NULL,
  `Invoice_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charge`
--

CREATE TABLE `charge` (
  `Charge_ID` int(11) NOT NULL,
  `Invoice_ID` int(11) NOT NULL,
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
  `CVV` int(11) NOT NULL,
  `Street_Address` varchar(255) NOT NULL,
  `Postal_Code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Phone_Number` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Birthdate` date NOT NULL,
  `Full_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `django_admin_log`
--

CREATE TABLE `django_admin_log` (
  `id` int(11) NOT NULL,
  `action_time` datetime(6) NOT NULL,
  `object_id` longtext,
  `object_repr` varchar(200) NOT NULL,
  `action_flag` smallint(5) UNSIGNED NOT NULL,
  `change_message` longtext NOT NULL,
  `content_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `django_content_type`
--

CREATE TABLE `django_content_type` (
  `id` int(11) NOT NULL,
  `app_label` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `django_content_type`
--

INSERT INTO `django_content_type` (`id`, `app_label`, `model`) VALUES
(1, 'admin', 'logentry'),
(3, 'auth', 'group'),
(2, 'auth', 'permission'),
(4, 'auth', 'user'),
(5, 'contenttypes', 'contenttype'),
(6, 'sessions', 'session');

-- --------------------------------------------------------

--
-- Table structure for table `django_migrations`
--

CREATE TABLE `django_migrations` (
  `id` bigint(20) NOT NULL,
  `app` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `applied` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `django_migrations`
--

INSERT INTO `django_migrations` (`id`, `app`, `name`, `applied`) VALUES
(1, 'contenttypes', '0001_initial', '2022-04-07 23:13:19.004691'),
(2, 'auth', '0001_initial', '2022-04-07 23:13:19.635343'),
(3, 'admin', '0001_initial', '2022-04-07 23:13:19.763973'),
(4, 'admin', '0002_logentry_remove_auto_add', '2022-04-07 23:13:19.773043'),
(5, 'admin', '0003_logentry_add_action_flag_choices', '2022-04-07 23:13:19.779347'),
(6, 'contenttypes', '0002_remove_content_type_name', '2022-04-07 23:13:19.862506'),
(7, 'auth', '0002_alter_permission_name_max_length', '2022-04-07 23:13:19.916767'),
(8, 'auth', '0003_alter_user_email_max_length', '2022-04-07 23:13:19.934630'),
(9, 'auth', '0004_alter_user_username_opts', '2022-04-07 23:13:19.940700'),
(10, 'auth', '0005_alter_user_last_login_null', '2022-04-07 23:13:19.984056'),
(11, 'auth', '0006_require_contenttypes_0002', '2022-04-07 23:13:19.988350'),
(12, 'auth', '0007_alter_validators_add_error_messages', '2022-04-07 23:13:19.995349'),
(13, 'auth', '0008_alter_user_username_max_length', '2022-04-07 23:13:20.045613'),
(14, 'auth', '0009_alter_user_last_name_max_length', '2022-04-07 23:13:20.101169'),
(15, 'auth', '0010_alter_group_name_max_length', '2022-04-07 23:13:20.133085'),
(16, 'auth', '0011_update_proxy_permissions', '2022-04-07 23:13:20.141148'),
(17, 'auth', '0012_alter_user_first_name_max_length', '2022-04-07 23:13:20.201053'),
(18, 'sessions', '0001_initial', '2022-04-07 23:13:20.235927');

-- --------------------------------------------------------

--
-- Table structure for table `django_session`
--

CREATE TABLE `django_session` (
  `session_key` varchar(40) NOT NULL,
  `session_data` longtext NOT NULL,
  `expire_date` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Hotel_ID` int(11) NOT NULL,
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
  `ID` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Invoice_ID` int(11) NOT NULL,
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
  `Invoice_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Transaction_Number` int(11) NOT NULL,
  `Invoice_ID` int(11) NOT NULL,
  `Amount` float NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `Number` int(11) NOT NULL,
  `Hotel_ID` int(11) NOT NULL,
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
  `Service_ID` int(11) NOT NULL,
  `Hotel_ID` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Indexes for table `auth_group`
--
ALTER TABLE `auth_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_group_permissions_group_id_permission_id_0cd325b0_uniq` (`group_id`,`permission_id`),
  ADD KEY `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` (`permission_id`);

--
-- Indexes for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_permission_content_type_id_codename_01ab375a_uniq` (`content_type_id`,`codename`);

--
-- Indexes for table `auth_user`
--
ALTER TABLE `auth_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `auth_user_groups`
--
ALTER TABLE `auth_user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_user_groups_user_id_group_id_94350c0c_uniq` (`user_id`,`group_id`),
  ADD KEY `auth_user_groups_group_id_97559544_fk_auth_group_id` (`group_id`);

--
-- Indexes for table `auth_user_user_permissions`
--
ALTER TABLE `auth_user_user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_user_user_permissions_user_id_permission_id_14a6b632_uniq` (`user_id`,`permission_id`),
  ADD KEY `auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm` (`permission_id`);

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
  ADD PRIMARY KEY (`Customer_ID`,`Username`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `django_admin_log_content_type_id_c4bce8eb_fk_django_co` (`content_type_id`),
  ADD KEY `django_admin_log_user_id_c564eba6_fk_auth_user_id` (`user_id`);

--
-- Indexes for table `django_content_type`
--
ALTER TABLE `django_content_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `django_content_type_app_label_model_76bd3d3b_uniq` (`app_label`,`model`);

--
-- Indexes for table `django_migrations`
--
ALTER TABLE `django_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `django_session`
--
ALTER TABLE `django_session`
  ADD PRIMARY KEY (`session_key`),
  ADD KEY `django_session_expire_date_a5c62663` (`expire_date`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_ID`,`Username`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Password` (`Password`),
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
-- AUTO_INCREMENT for table `auth_group`
--
ALTER TABLE `auth_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_permission`
--
ALTER TABLE `auth_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `auth_user`
--
ALTER TABLE `auth_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_user_groups`
--
ALTER TABLE `auth_user_groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_user_user_permissions`
--
ALTER TABLE `auth_user_user_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charge`
--
ALTER TABLE `charge`
  MODIFY `Charge_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `django_content_type`
--
ALTER TABLE `django_content_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `django_migrations`
--
ALTER TABLE `django_migrations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Transaction_Number` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `booking`
  MODIFY `Number` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(11) NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Invoice_ID` int(11) NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Service_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  ADD CONSTRAINT `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`),
  ADD CONSTRAINT `auth_group_permissions_group_id_b120cbf9_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`);

--
-- Constraints for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD CONSTRAINT `auth_permission_content_type_id_2f476e4b_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`);

--
-- Constraints for table `auth_user_groups`
--
ALTER TABLE `auth_user_groups`
  ADD CONSTRAINT `auth_user_groups_group_id_97559544_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
  ADD CONSTRAINT `auth_user_groups_user_id_6a12ed8b_fk_auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

--
-- Constraints for table `auth_user_user_permissions`
--
ALTER TABLE `auth_user_user_permissions`
  ADD CONSTRAINT `auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`),
  ADD CONSTRAINT `auth_user_user_permissions_user_id_a95ead1b_fk_auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

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
-- Constraints for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  ADD CONSTRAINT `django_admin_log_content_type_id_c4bce8eb_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`),
  ADD CONSTRAINT `django_admin_log_user_id_c564eba6_fk_auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

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
