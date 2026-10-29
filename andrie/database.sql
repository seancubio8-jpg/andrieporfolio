-- ==============================================================
-- Portfolio Database Schema & Seed Data
-- Owner: Sean Andrie Cubio
-- File: database.sql
-- Compatible with: MySQL / MariaDB (XAMPP phpMyAdmin)
-- ==============================================================

-- 1. Create Database if it doesn't already exist
CREATE DATABASE IF NOT EXISTS `andrie_db` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `andrie_db`;

-- --------------------------------------------------------
-- 2. Table: profile_info
-- Stores Sean Andrie Cubio's primary profile & contact information
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `profile_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `tagline` VARCHAR(255) NOT NULL,
  `bio` TEXT NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(50) DEFAULT '+63 9126782183',
  `location` VARCHAR(100) DEFAULT 'Paranaque, Metro Manila Philippines',
  `experience_years` INT DEFAULT 3,
  `projects_completed` INT DEFAULT 15,
  `happy_clients` INT DEFAULT 10,
  `github_url` VARCHAR(255) DEFAULT 'https://github.com',
  `linkedin_url` VARCHAR(255) DEFAULT 'https://linkedin.com',
  `facebook_url` VARCHAR(255) DEFAULT 'https://facebook.com',
  `resume_file` VARCHAR(255) DEFAULT '#',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Table: projects
-- Stores showcase portfolio projects (Exclusively 3 featured systems)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Web App',
  `tech_stack` VARCHAR(255) NOT NULL,
  `image_url` VARCHAR(255) DEFAULT '',
  `demo_url` VARCHAR(255) DEFAULT '#',
  `github_url` VARCHAR(255) DEFAULT '#',
  `is_featured` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. Table: messages
-- Stores incoming messages submitted from the portfolio contact form
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 5. Table: users
-- Stores administrator & authorized accounts
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin User (Username: andrie123, Password: andrie12345)
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'andrie123', 'andrie12345', 'admin')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- ==============================================================
-- 6. Insert / Update Default Seed Data for Sean Andrie Cubio
-- ==============================================================

-- Profile Data
INSERT INTO `profile_info` (`id`, `full_name`, `title`, `tagline`, `bio`, `email`, `phone`, `location`, `experience_years`, `projects_completed`, `happy_clients`, `github_url`, `linkedin_url`, `facebook_url`) 
VALUES (
  1,
  'Sean Adrei Cubio',
  'BSIT Student & Aspiring Web Developer',
  'Information Technology student passionate about building practical PHP, MySQL, and IoT solutions.',
  'Hello! I am Sean Adrei Cubio, an Information Technology (BSIT) undergraduate student at Datamex College of St. Adeline. I build web applications with a solid foundation in PHP backends, MySQL relational databases, responsive design, and Arduino microcontroller prototyping. I am eager to secure an internship, OJT, or entry-level role where I can apply my knowledge, gain hands-on industry experience, and contribute to team goals.',
  'SeanCubio8@gmail.com',
  '09687480382',
  'Parañaque City, Metro Manila, Philippines',
  0,
  3,
  0,
  'https://github.com',
  'https://linkedin.com',
  'https://facebook.com'
) ON DUPLICATE KEY UPDATE 
  `full_name` = VALUES(`full_name`),
  `email`     = VALUES(`email`),
  `phone`     = VALUES(`phone`),
  `location`  = VALUES(`location`),
  `title`     = VALUES(`title`),
  `tagline`   = VALUES(`tagline`),
  `bio`       = VALUES(`bio`);

-- Projects Data (Exclusively the 3 featured systems)
DELETE FROM `projects`;

INSERT INTO `projects` (`id`, `title`, `description`, `category`, `tech_stack`, `image_url`, `demo_url`, `github_url`, `is_featured`) VALUES
(
  1,
  'Tapseafo: Online Food Ordering',
  'A full-featured online food ordering and delivery web application featuring interactive seafood & specialty food menus, dynamic shopping cart management, instant checkout, and an administrative order dispatch panel.',
  'Web App',
  'PHP, MySQL, JavaScript, CSS3, Bootstrap',
  'assets/images/tapseafo.jpg',
  'demos/tapseafo.php',
  '#',
  1
),
(
  2,
  'Hotel Booking Website',
  'A comprehensive online hospitality booking platform allowing guests to browse luxury suites, check real-time date availability, calculate pricing, and secure online room reservations with automated booking receipts.',
  'PHP/MySQL',
  'PHP, PDO, MySQL, Ajax, CSS3',
  'assets/images/hotel_booking.jpg',
  'demos/hotel.php',
  '#',
  1
),
(
  3,
  'Arduino Water Dispenser',
  'An automated touchless smart water dispensing system built with an Arduino microcontroller, ultrasonic distance sensors for cup detection, relay-operated solenoid valve, and LED status indicators.',
  'IoT & Hardware',
  'Arduino, C/C++, Ultrasonic Sensor, Solenoid Valve, Relay Module',
  'assets/images/arduino_dispenser.jpg',
  'demos/arduino.php',
  '#',
  1
);
