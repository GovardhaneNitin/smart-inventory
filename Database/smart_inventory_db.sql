-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `performed_by`, `timestamp`) VALUES
(1, 3, 'Promoted to Manager', 1, '2024-10-05 10:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `inventoryitem`
--

CREATE TABLE `inventoryitem` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventoryitem`
--

INSERT INTO `inventoryitem` (`ItemID`, `ItemName`, `Category`, `Quantity`, `Location`, `Price`, `SupplierID`, `Image`, `CreatedAt`, `UpdatedAt`, `is_deleted`) VALUES
(1, 'Samsung Galaxy S21', 'Smartphones', 20, 'Warehouse A', 69999.00, 1, 'assets/images/InventoryItems/Samsung-Galaxy-S21.png', '2024-10-13 18:33:48', '2024-10-13 18:51:32', 0),
(2, 'Apple MacBook Pro 13-inch', 'Laptops', 10, 'Warehouse B', 149999.00, 2, 'assets/images/InventoryItems/Apple-MacBook-Pro-13-inch.jpg', '2024-10-13 18:36:21', '2024-10-13 18:52:34', 0),
(3, 'Sony 55-inch LED TV', 'Televisions', 7, 'Warehouse C', 39999.00, 3, 'assets/images/InventoryItems/Sony-55-inch-LED-TV.jpg', '2024-10-13 18:38:08', '2024-10-13 18:53:15', 0),
(4, 'Bose SoundLink Speaker', 'Audio', 10, 'Showroom', 2999.00, 4, 'assets/images/InventoryItems/Bose-SoundLink-Speaker.jpg', '2024-10-13 18:41:13', '2024-10-13 18:56:11', 0),
(5, 'HP Pavilion Gaming Laptop', 'Laptops', 15, 'Warehouse D', 59999.00, 5, 'assets/images/InventoryItems/HP-Pavilion-Gaming-Laptop.jpg', '2024-10-13 18:43:10', '2024-10-13 18:54:13', 0),
(6, 'Panasonic Air Conditioner', 'Home Appliances', 20, 'Warehouse E', 13999.00, 6, 'assets/images/InventoryItems/Panasonic-Air-Conditioner.jpg', '2024-10-13 18:47:04', '2024-10-13 18:56:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prediction`
--

CREATE TABLE `prediction` (
  `PredictionID` int(11) NOT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `PredictedRestockDate` date NOT NULL,
  `PredictedQuantity` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ReportType` enum('Sales Report','Inventory Report') NOT NULL,
  `DateRangeStart` date NOT NULL,
  `DateRangeEnd` date NOT NULL,
  `GeneratedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salestransaction`
--

CREATE TABLE `salestransaction` (
  `TransactionID` int(11) NOT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `QuantitySold` int(11) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `SaleDate` date NOT NULL,
  `CustomerName` varchar(255) DEFAULT NULL,
  `CustomerEmail` varchar(255) DEFAULT NULL,
  `CustomerPhone` varchar(20) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salestransaction`
--

INSERT INTO `salestransaction` (`TransactionID`, `ItemID`, `UserID`, `QuantitySold`, `TotalPrice`, `SaleDate`, `CustomerName`, `CustomerEmail`, `CustomerPhone`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 1, 1, 10, 699990.00, '2024-10-14', 'Rohit Sharma', 'rohitsharma@gmail.com', '9876543210', '2024-10-13 18:51:32', '2024-10-13 18:51:32'),
(2, 2, 1, 5, 749995.00, '2024-10-14', 'Anjali Patel', 'anjalipatel@gmail.com', '9123456789', '2024-10-13 18:52:33', '2024-10-13 18:52:33'),
(3, 3, 1, 3, 119997.00, '2024-10-14', 'Rajesh Kumar', 'rajeshkumar@gmail.com', '9988776655', '2024-10-13 18:53:15', '2024-10-13 18:53:15'),
(4, 5, 1, 5, 299995.00, '2024-10-14', 'Amit Verma', 'amitverma@gmail.com', '9876512345', '2024-10-13 18:54:12', '2024-10-13 18:54:12'),
(5, 4, 1, 15, 44985.00, '2024-10-14', 'Priya Singh', 'priyasingh@gmail.com', '9876541230', '2024-10-13 18:56:11', '2024-10-13 18:56:11'),
(6, 6, 1, 10, 139990.00, '2024-10-14', 'Pooja Desai', 'poojadesai@gmail.com', '9988771122', '2024-10-13 18:56:44', '2024-10-13 18:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `ContactPerson` varchar(100) DEFAULT NULL,
  `ContactEmail` varchar(100) DEFAULT NULL,
  `ContactPhone` varchar(15) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `ContactPerson`, `ContactEmail`, `ContactPhone`, `Address`, `CreatedAt`, `UpdatedAt`, `is_deleted`) VALUES
(1, 'Bharat Electronics', 'Rajesh Kumar', 'rajesh.kumar@bharatelectronics.com', '9876543210', 'No. 12, MG Road, Bengaluru, Karnataka', '2024-10-05 10:09:58', '2024-10-05 10:09:58', 0),
(2, 'Tata Electricals', 'Amit Desai', 'amit.desai@tataelectricals.in', '9823123456', 'Plot 5, MIDC, Pune, Maharashtra', '2024-10-05 10:14:37', '2024-10-05 10:14:37', 0),
(3, 'Reliance Electronics', 'Anjali Mehta', 'anjali.mehta@relianceelectronics.com', '9967123456', 'Ghatkopar West, Mumbai, Maharashtra', '2024-10-05 10:15:17', '2024-10-05 10:15:17', 0),
(4, 'L&T Tech Supplies', 'Suresh Nair', 'suresh.nair@lttech.in', '9845123456', 'Sector 21, Gurgaon, Haryana', '2024-10-05 10:16:10', '2024-10-05 10:16:10', 0),
(5, 'Havells India', 'Vikram Singh', 'vikram.singh@havellsindia.in', '9911223344', 'DLF Cyber City, Hyderabad, Telangana', '2024-10-05 10:16:41', '2024-10-05 10:16:41', 0),
(6, 'Godrej Electricals', 'Pooja Sharma', 'pooja.sharma@godrejelectricals.in', '9988776655', 'Churchgate, Mumbai, Maharashtra', '2024-10-05 10:17:29', '2024-10-05 10:17:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_activity_log`
--

CREATE TABLE `supplier_activity_log` (
  `log_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_activity_log`
--

INSERT INTO `supplier_activity_log` (`log_id`, `supplier_id`, `action`, `performed_by`, `role`, `timestamp`) VALUES
(1, 1, 'Added', 1, 'Admin', '2024-10-05 10:09:58'),
(2, 2, 'Added', 1, 'Admin', '2024-10-05 10:14:37'),
(3, 3, 'Added', 1, 'Admin', '2024-10-05 10:15:18'),
(4, 4, 'Added', 1, 'Manager', '2024-10-05 10:16:10'),
(5, 5, 'Added', 1, 'Manager', '2024-10-05 10:16:41'),
(6, 6, 'Added', 1, 'Manager', '2024-10-05 10:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` enum('Admin','Manager','Employee') NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `JobTitle` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role_assigned_by` int(11) DEFAULT NULL,
  `role_assigned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `Role`, `FirstName`, `LastName`, `PhoneNumber`, `Address`, `DateOfBirth`, `ProfileImage`, `JobTitle`, `Department`, `Gender`, `CreatedAt`, `UpdatedAt`, `role_assigned_by`, `role_assigned_at`) VALUES
(1, 'Admin', '$2y$10$W7JV0YSU05n93Dvu66rVoutR2L1mqANJrl7qxbWD5m3e6xelz5TGS', 'admin@gmail.com', 'Admin', 'Nitin', 'Govardhane', '9503072082', 'Nashik, Maharashtra, India.', '2000-10-30', '1_1728843703_1_1728240078_NG-logo.jpeg', 'Developer', 'Development ', 'Male', '2024-10-05 10:07:11', '2024-10-13 18:24:51', NULL, NULL),
(2, 'Jitesh', '$2y$10$2Ou9fydCTW1hjYKv4dncuuaQq0Ueo.jH7N4wrvoGgQ/3Qck0ix3v6', 'jitesh@gmail.com', 'Employee', 'Jitesh', 'Borse', NULL, NULL, NULL, '2_1728843781_men.jpg', NULL, NULL, 'Male', '2024-10-05 10:08:01', '2024-10-13 18:23:01', NULL, NULL),
(3, 'Vedanti', '$2y$10$mVeOUZCc0djVs6sbUSPWBeqWhzMLd1V6xDIr3gMkqV5QiUVztT0PG', 'vedanti@gmail.com', 'Manager', 'Vedanti', 'Lakade', NULL, NULL, NULL, '3_1728843741_3_1728241885_female.png', NULL, NULL, 'Female', '2024-10-05 10:08:25', '2024-10-13 18:22:21', 1, '2024-10-05 10:09:06'),
(4, 'Shalaka', '$2y$10$at1aP.vjz2a6DcT9wIUmPeDTcbOFBGKrsGY/RUWS1Sn4TrnmMHuVS', 'shalaka@gmail.com', 'Employee', 'Shalaka', 'Nikam', NULL, NULL, NULL, NULL, NULL, NULL, 'Female', '2024-10-05 10:08:53', '2024-10-05 10:08:53', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_performed_by` (`performed_by`);

--
-- Indexes for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `idx_item_name` (`ItemName`),
  ADD KEY `idx_category` (`Category`);

--
-- Indexes for table `prediction`
--
ALTER TABLE `prediction`
  ADD PRIMARY KEY (`PredictionID`),
  ADD KEY `idx_item_id` (`ItemID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `idx_user_id` (`UserID`),
  ADD KEY `idx_report_type` (`ReportType`);

--
-- Indexes for table `salestransaction`
--
ALTER TABLE `salestransaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `idx_item_id` (`ItemID`),
  ADD KEY `idx_user_id` (`UserID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`),
  ADD KEY `idx_supplier_name` (`SupplierName`);

--
-- Indexes for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_supplier_id` (`supplier_id`),
  ADD KEY `idx_performed_by` (`performed_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `role_assigned_by` (`role_assigned_by`),
  ADD KEY `idx_email` (`Email`),
  ADD KEY `idx_username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prediction`
--
ALTER TABLE `prediction`
  MODIFY `PredictionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salestransaction`
--
ALTER TABLE `salestransaction`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD CONSTRAINT `inventoryitem_ibfk_1` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`) ON DELETE SET NULL;

--
-- Constraints for table `prediction`
--
ALTER TABLE `prediction`
  ADD CONSTRAINT `prediction_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `inventoryitem` (`ItemID`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `salestransaction`
--
ALTER TABLE `salestransaction`
  ADD CONSTRAINT `salestransaction_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `inventoryitem` (`ItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `salestransaction_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  ADD CONSTRAINT `supplier_activity_log_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`SupplierID`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_activity_log_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_assigned_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
