-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 15, 2025 at 08:20 PM
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
-- Database: `travel_company`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `num_travelers` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `special_requests` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `trip_id`, `customer_name`, `customer_email`, `customer_phone`, `num_travelers`, `total_amount`, `payment_method`, `card_number`, `booking_date`, `special_requests`) VALUES
(1, 1, 'b', 'baselnajjar69@gmail.com', '0599', 1, 400.00, 'Visa Card', '7777', '2025-12-15 16:39:17', 'b'),
(2, 10, 'abdallah', 'abdallahnajjar@gmail.com', '05999', 1, 75.00, 'Visa Card', '1111', '2025-12-15 18:03:05', 'v');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `trip_name` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `available_seats` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `itinerary` text DEFAULT NULL,
  `inclusions` text DEFAULT NULL,
  `exclusions` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`trip_id`, `trip_name`, `destination`, `duration_days`, `price`, `start_date`, `end_date`, `available_seats`, `description`, `itinerary`, `inclusions`, `exclusions`, `requirements`, `image_url`) VALUES
(1, 'Jerusalem Weekend', 'Jerusalem', 3, 400.00, '2026-01-10', '2026-01-12', 11, 'Short cultural trip covering Jerusalem and Bethlehem.', 'Arrival & Old City tour|Religious sites visit|Free time & departure', 'Hotel|Breakfast|Transport|Tour guide', 'Flights|Lunch & dinner|Personal expenses', 'Valid ID or passport|Comfortable walking shoes', 'images/jerusalem.jpg'),
(2, 'Dubai Adventure', 'Dubai', 5, 850.00, '2026-01-15', '2026-01-19', 20, 'Luxury and adventure across Dubai and Abu Dhabi.', 'Arrival & city tour|Desert safari|Burj Khalifa & Mall|Abu Dhabi tour|Departure', '4-star hotel|Breakfast|Safari|Airport transfers', 'Flights|Personal expenses', 'Passport|Visa if required', 'images/dubai.jpg'),
(3, 'Beijing Discovery', 'Beijing', 6, 1100.00, '2026-02-01', '2026-02-06', 18, 'Top cultural tour exploring Beijing and the Great Wall.', 'Arrival dinner|Great Wall|Forbidden City|Summer Palace|Markets|Departure', 'Hotel|Breakfast|Guided tours|Local transport', 'Flights|Extra meals', 'Passport|Chinese visa', 'images/beijing.jpg'),
(4, 'Riyadh Heritage', 'Riyadh', 4, 700.00, '2026-02-10', '2026-02-13', 16, 'Traditional and modern Saudi culture experience.', 'Arrival & city tour|Diriyah & museum|Souqs & malls|Departure', 'Hotel|Breakfast|Guide|Transport', 'Flights|Meals', 'Passport|Saudi visa|Modest clothing', 'images/riyadh.jpg'),
(5, 'Holy Palestine Tour', 'Palestine', 7, 1200.00, '2026-03-01', '2026-03-07', 25, 'Complete Palestine journey across major cities.', 'Jerusalem|Bethlehem & Hebron|Jericho & Dead Sea|Nablus|Ramallah|Markets|Departure', 'Hotels|Breakfast|Transport|Licensed guide', 'Flights|Insurance|Lunch & dinner', 'Passport|Respect local dress code', 'images/palestine.jpg'),
(6, 'Dead Sea Relax Day', 'Dead Sea', 1, 120.00, '2026-01-20', '2026-01-20', 40, 'One-day relaxation trip at the Dead Sea.', 'Departure|Dead Sea swim|Return', 'Transport|Beach entry', 'Meals|Spa services', 'Swimwear|Towel', 'images/deadsea.jpg'),
(7, 'Nablus Heritage Tour', 'Nablus', 1, 90.00, '2026-01-22', '2026-01-22', 30, 'Discover Nablus old city and famous kanafeh.', 'Old city walk|Soap factory|Kanafeh tasting', 'Transport|Guide', 'Meals|Shopping', 'Walking shoes', 'images/nablus.jpg'),
(8, 'Acre & Haifa Trip', 'Haifa', 2, 260.00, '2026-02-05', '2026-02-06', 22, 'Coastal cities and historic ports.', 'Acre old city|Bahai Gardens|Beach walk', 'Hotel|Transport|Guide', 'Meals', 'ID|Comfortable shoes', 'images/haifa.jpg'),
(9, 'Galilee Nature Escape', 'Galilee', 2, 240.00, '2026-02-15', '2026-02-16', 20, 'Nature trails and scenic landscapes.', 'Hiking trail|Lake visit|Village tour', 'Hotel|Transport|Guide', 'Meals|Personal expenses', 'Hiking shoes|Water bottle', 'images/galilee.jpg'),
(10, 'Ramallah City Break', 'Ramallah', 1, 75.00, '2026-02-20', '2026-02-20', 44, 'Urban culture, cafes, and city life.', 'City tour|Museum|Cafe time', 'Transport|Guide', 'Meals', 'Comfortable outfit', 'images/ramallah.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_bookings_trips` (`trip_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_trips` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
