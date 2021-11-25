-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2021 at 04:48 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_e_commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`) VALUES
(1, 'Jakarta'),
(2, 'Bogor'),
(3, 'Depok'),
(4, 'Tangerang'),
(5, 'Bekasi');

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('customer','admin','mechanic') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`id`, `email`, `password`, `user_id`, `user_type`, `status`) VALUES
(1, 'admin@admin.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 1, 'admin', 1),
(2, 'muhamadfrio@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 2, 'customer', 1),
(3, 'berto@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 3, 'mechanic', 1),
(4, 'muhamadfrio+1@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 4, 'mechanic', 1),
(5, 'muhamadfrio+2@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 5, 'customer', 1),
(6, 'raja@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 7, 'customer', 1),
(8, 'bertus@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 9, 'mechanic', 1),
(9, 'muhamadfrio+3@gmail.com', '5c90b96a75d4f9d5a1cfaa6f532afdc8', 10, 'customer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`) VALUES
(1, 'Artha Graha'),
(2, 'BCA'),
(3, 'Mandiri');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `account_number` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_id`, `account_number`) VALUES
(1, 1, 50401056000076),
(2, 2, 5220304312),
(3, 3, 1560009861518);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `workshop_id` int(11) DEFAULT NULL,
  `area_id` int(11) NOT NULL,
  `mechanic_id` int(11) DEFAULT NULL,
  `type` enum('booking','shopping') NOT NULL,
  `complaint` text,
  `date` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `postal_code` int(5) DEFAULT NULL,
  `other_cost` bigint(20) DEFAULT '0',
  `other_cost_note` text,
  `booking_status` enum('waiting_confirmation','confirmed','process','shipped','waiting_payment','checking_payment','completed','canceled') NOT NULL DEFAULT 'waiting_confirmation',
  `bank_account_id` int(11) DEFAULT NULL,
  `awb_number` varchar(100) DEFAULT NULL,
  `payment_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `workshop_id`, `area_id`, `mechanic_id`, `type`, `complaint`, `date`, `address`, `phone`, `postal_code`, `other_cost`, `other_cost_note`, `booking_status`, `bank_account_id`, `awb_number`, `payment_url`, `created_at`) VALUES
(2, 2, 2, 1, 5, 3, 'booking', 'Servis Rutin', '2021-08-03 19:48:47', 'SPBU Kuningan Jakarta Selatan', '0895-2903-7444', NULL, 50000, 'Biaya servis', 'completed', 2, NULL, 'assets/images/payments/payment_2.png', '2021-08-01 12:49:10'),
(3, 2, 1, 1, 5, NULL, 'shopping', NULL, '2021-08-01 20:01:02', 'Perumahan taman alamanda blok B11 No.28 RT 002 RW 022 Tambun Utara Kab Bekasi', '0895-2903-7444', 17511, 0, NULL, 'completed', NULL, 'AWB-1010-020821', 'assets/images/payments/payment_3.png', '2021-08-01 13:01:02'),
(4, 2, 1, 1, 5, NULL, 'shopping', NULL, '2021-08-01 20:04:05', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara', '0895-2903-7444', 17510, 0, NULL, 'completed', 2, '', 'assets/images/payments/payment_4.png', '2021-08-01 13:04:05'),
(5, 2, 2, 1, 5, 9, 'booking', 'Servis Rutin', '2021-09-01 20:07:30', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara Kab Bekasi', '0895-2903-7444', NULL, 50000, 'Servis Rutin', 'completed', 2, NULL, 'assets/images/payments/payment_5.png', '2021-08-01 13:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

CREATE TABLE `booking_items` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking_items`
--

INSERT INTO `booking_items` (`id`, `booking_id`, `item_id`, `price`, `qty`) VALUES
(1, 2, 11, 25900, 1),
(2, 3, 5, 45000, 1),
(3, 4, 13, 350000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Honda'),
(2, 'Yamaha'),
(3, 'Suzuki'),
(4, 'Kawasaki'),
(5, 'Benelli'),
(6, 'KTM'),
(7, 'TVS'),
(8, 'BMW'),
(9, 'VIAR'),
(10, 'Vespa'),
(11, 'Royal Enfield'),
(12, 'Ducati'),
(13, 'Triumph'),
(14, 'Bajaj'),
(15, 'SYM'),
(16, 'Harley'),
(17, 'Husqvarna'),
(18, 'Cleveland CycleWerks'),
(19, 'MV Agusta'),
(20, 'Kymco'),
(21, 'Aprilia'),
(22, 'Piaggio'),
(23, 'Peugeot'),
(24, 'Moto Guzzi'),
(25, 'Diablo'),
(26, 'Gesits'),
(27, 'Lambretta'),
(28, 'SM Sport'),
(29, 'Ecgi'),
(30, 'Selis'),
(31, 'United'),
(32, 'BF Goodrich'),
(33, 'Qooder'),
(34, 'Italjet'),
(35, 'Royal Alloy');

-- --------------------------------------------------------

--
-- Table structure for table `brand_types`
--

CREATE TABLE `brand_types` (
  `id` int(11) NOT NULL,
  `transportation_type_id` int(11) NOT NULL DEFAULT '1',
  `brand_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brand_types`
--

INSERT INTO `brand_types` (`id`, `transportation_type_id`, `brand_id`, `name`, `status`) VALUES
(1, 1, 1, 'Beat', 1),
(2, 1, 2, 'Mio', 1),
(3, 1, 4, 'Ninja 250 Fi', 1),
(4, 1, 1, 'Genio 2020', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('booking','shopping') NOT NULL DEFAULT 'shopping',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `type`, `status`) VALUES
(1, 2, 'booking', 0),
(2, 2, 'shopping', 0),
(3, 2, 'shopping', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `item_id`, `qty`) VALUES
(1, 1, 11, 1),
(3, 2, 5, 1),
(4, 3, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `brand_type_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `price` bigint(20) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `brand_type_id`, `name`, `price`, `image_url`, `qty`, `status`) VALUES
(1, 1, 'Kampas rem depan', 50000, '', 96, 1),
(2, 1, 'Kampas rem belakang', 60000, '', 98, 1),
(3, 2, 'Kampas rem depan', 40000, '', 99, 1),
(4, 2, 'Kampas rem belakang', 50000, '', 99, 1),
(5, NULL, 'Pertamina Enduro Matic, 10W-30, API SL, JASO MB 0.8L 1pc', 45000, 'assets/images/items/item_5.png', 91, 1),
(6, 1, 'Filter udara', 85000, '', 100, 1),
(7, 1, 'Lampu depan', 25900, '', 100, 1),
(8, 1, 'Kampas rem belakang', 60000, '', 98, 1),
(9, 1, 'Lampu belakang', 26000, '', 99, 1),
(10, 1, 'Lampu sein', 15000, '', 98, 1),
(11, NULL, 'Minyak rem', 25900, '', 92, 1),
(12, 1, 'Handle rem', 110000, 'assets/images/items/item_12.png', 98, 1),
(13, 2, 'Jok 2018', 350000, 'assets/images/items/item_13.png', 3, 1),
(14, 1, 'Headlamp', 154900, 'assets/images/items/item_14.png', 9, 1),
(15, NULL, 'Sein universal', 70000, 'assets/images/items/item_15.png', 45, 1),
(16, 3, 'Knalpot yoshimura', 1500000, 'assets/images/items/item_16.png', 9, 1),
(17, 4, 'Handle Rem', 60000, 'assets/images/items/item_17.png', 98, 0);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `url` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `image_url`, `url`) VALUES
(1, 'Belanja Spare Part', 'Belanja kebutuhan kendaraan kamu dari rumah, biar kami kirim secepatnya', 'assets/images/shopping-cart.png', 'shopping'),
(2, 'Booking Teknisi Rumah', 'Tetap dirumah aja, biar teknisi kami yang datang ke lokasimu', 'assets/images/maintenance.png', 'bookingservice');

-- --------------------------------------------------------

--
-- Table structure for table `transportation_types`
--

CREATE TABLE `transportation_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transportation_types`
--

INSERT INTO `transportation_types` (`id`, `name`, `status`) VALUES
(1, 'Motor', 1),
(2, 'Mobil', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` int(5) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `address`, `postal_code`, `dob`) VALUES
(1, 'Admin', '0821-2520-7042', 'Bekasi Utara', 17110, '1111-01-01'),
(2, 'M Fadhilah Rio Bagus Saputro', '0895-2903-7444', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara', 17510, '1998-05-08'),
(3, 'Berto', '082111111111', 'Harapan Jaya Bekasi Utara', 17510, '1997-07-01'),
(4, 'Rio', '0895-2903-7441', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara', 17510, '1998-05-06'),
(5, 'Riyo', '0895-2903-7442', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara', 17510, '1998-05-07'),
(6, 'M Fadhilah Rio Bagus Saputroo', '0895-2903-7443', 'Perumahan Taman Alamanda Blok G11 No.29 Rt 002 RW 022 Tambun Utara', 17510, '1998-05-09'),
(7, NULL, NULL, NULL, NULL, NULL),
(8, NULL, NULL, NULL, NULL, NULL),
(9, 'Betus', '0876-2256-675_', 'Perumahan taman anggrek tambun utara\r\n', 17510, '1998-07-26'),
(10, 'Rio', '0895-2903-7444', 'Bekasi', 17510, '1998-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

CREATE TABLE `workshops` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`id`, `area_id`, `name`, `phone`, `address`, `postal_code`) VALUES
(1, 1, 'Cabang Jakarta', '0821-2520-7042', 'Manggarai, Jakarta Selatan', 12850),
(2, 5, 'Cabang Bekasi', '0821-2520-7044', 'Harapan Jaya, Bekasi Utara', 17124);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_types`
--
ALTER TABLE `brand_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transportation_types`
--
ALTER TABLE `transportation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `brand_types`
--
ALTER TABLE `brand_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transportation_types`
--
ALTER TABLE `transportation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
