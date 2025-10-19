-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 19, 2025 at 08:07 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `broadcast_loan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `code_category`, `created_at`, `updated_at`) VALUES
(52, 'APUTURE AMARAN TRI-8C V-MOUNT', 'APU', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(53, 'DCK KDJF22 Reciprocating Saw', 'DCK', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(54, 'Lensa CANON HDTV Zoom Lens 40X', 'LEN', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(55, 'Marshall CV506 HD Cam', 'MAR', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(56, 'SDI to HDMI Converter', 'SDI', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(57, 'KRAMER VM-5HD 3G HD-SDI DA', 'KRA', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(58, 'FLUKE 117 TRUE RMS MULTIMETER', 'FLU', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(59, 'OPTIC CABLE 300M - KUNING', 'OPT', '2025-09-15 14:04:58', '2025-09-15 14:04:58'),
(181, 'Converter/Scaler', 'CON', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(182, 'Aksesoris', 'AKS', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(183, 'Lighting', 'LIG', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(184, 'Tools', 'TOO', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(185, 'Alat Ukur', 'ALA', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(186, 'Distributor/Router SDI', 'DIS', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(187, 'Monitor', 'MON', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(188, 'Encoder/Video Transmission', 'ENC', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(189, 'Aksesoris/Media Dock', 'AKB', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(190, 'Converter/Capture', 'COB', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(191, 'Converter/Optical Fiber', 'COC', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(192, 'Networking', 'NET', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(193, 'Kamera', 'KAM', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(194, 'Lainnya', 'LAI', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(195, 'Extender', 'EXT', '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(249, 'Baterai', 'BAT', '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(250, 'STB/Receiver', 'STB', '2025-09-25 17:52:33', '2025-09-25 17:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procurement_year` smallint UNSIGNED DEFAULT NULL,
  `condition` enum('baik','rusak_ringan','rusak_berat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `is_missing` tinyint(1) NOT NULL DEFAULT '0',
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `code`, `name`, `merk`, `tipe`, `details`, `serial_number`, `procurement_year`, `condition`, `is_missing`, `lokasi`, `category_id`, `created_at`, `updated_at`) VALUES
(546, 'BMD010', 'BMD SDI to Audio', NULL, NULL, 'nan', '5706101', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(547, 'BMD011', 'BMD UpDownCross', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(548, 'BMD012', 'BMD Video Router 12x12', NULL, NULL, 'nan', '10510067', NULL, 'baik', 0, NULL, 186, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(549, 'BMD013', 'BMD Video Assist 7\"', NULL, NULL, 'nan', '4677095', NULL, 'baik', 0, NULL, 187, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(550, 'BMD014', 'BMD Optic to SDI', NULL, NULL, 'nan', '11067296', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(551, 'BMD015', 'BMD Optic to SDI', NULL, NULL, 'nan', '11067274', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(552, 'BMD016', 'BMD Video Assist 7\"', NULL, NULL, 'nan', '4677060', NULL, 'baik', 0, NULL, 187, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(553, 'BMD017', 'BMD SDI to Audio', NULL, NULL, 'nan', '1539601', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(554, 'AT001', 'ATEN Hdmi Splitter 4 Port', NULL, NULL, 'nan', 'Z3LB0710340', 2023, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(555, 'VT001', 'Vention HDMI Splitter 4 Port', NULL, NULL, 'nan', '-', 2023, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(556, 'VT002', 'Vention HDMI to RCA', NULL, NULL, 'nan', '-', 2023, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(557, 'VT003', 'Vention HDMI Splitter 4 Port', NULL, NULL, 'nan', '-', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(558, 'VT004', 'Vention HDMI Splitter 4 Port', NULL, NULL, 'nan', '-', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(559, 'SN001', 'Soliton Zao-X', NULL, NULL, 'nan', 'ZAO221128051', 2023, 'baik', 0, NULL, 188, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(560, 'SN002', 'Soliton Zao-S', NULL, NULL, 'nan', '2001800', NULL, 'baik', 0, NULL, 188, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(561, 'SN003', 'Soliton Zao-X', NULL, NULL, 'nan', 'ZAO221117038', NULL, 'baik', 0, NULL, 188, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(562, 'LV001', 'Live U LU-300S', NULL, NULL, 'nan', '3S2312-41512', 2023, 'baik', 0, NULL, 188, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(563, 'LV002', 'Live U LU-300S', NULL, NULL, 'nan', '3S2249-38246', 2023, 'baik', 0, NULL, 188, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(564, 'AJ001', 'AJA 3G HD Distribution Amplifier', NULL, NULL, 'nan', 'K0463224', NULL, 'baik', 0, NULL, 186, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(565, 'AJ002', 'AJA Pak Dock', NULL, NULL, 'nan', 'K0478976', NULL, 'baik', 0, NULL, 189, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(566, 'AJ003', 'AJA U-TAP SDI', NULL, NULL, 'nan', 'K0528729', NULL, 'baik', 0, NULL, 190, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(567, 'AJ004', 'AJA U-TAP SDI', NULL, NULL, 'nan', 'K0527644', NULL, 'baik', 0, NULL, 190, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(568, 'AJ005', 'AJA U-TAP SDI', NULL, NULL, 'nan', 'K0620858', NULL, 'baik', 0, NULL, 190, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(569, 'AJ006', 'AJA FIDO-TR', NULL, NULL, 'nan', 'K0632806', NULL, 'baik', 0, NULL, 191, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(570, 'AJ007', 'AJA FIDO-TR', NULL, NULL, 'nan', 'K0632807', NULL, 'baik', 0, NULL, 191, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(571, 'TP001', 'TP-LINK Optic - LAN', NULL, NULL, 'nan', '22B1Q1001010', 2023, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(572, 'TP002', 'TP-LINK Optic - LAN', NULL, NULL, 'nan', '-', 2023, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(573, 'DV001', 'Data Video VP-597 SDI Distribution', NULL, NULL, 'nan', '00761539', NULL, 'baik', 0, NULL, 186, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(574, 'DV002', 'Data Video DAC-70', NULL, NULL, 'nan', '00724847', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(575, 'DV003', 'Data Video DAC-70', NULL, NULL, 'nan', '00724846', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(576, 'DV004', 'Data Video DAC-70', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(577, 'DV005', 'Data Video DAC-70', NULL, NULL, 'nan', '00692026', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(578, 'DV006', 'Data Video DAC-70', NULL, NULL, 'nan', '00773362', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(579, 'DV007', 'Data Video DAC-70', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(580, 'DV008', 'Data Video DAC-70', NULL, NULL, 'nan', '00700156', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(581, 'PX001', 'PX Wireless Presentation', NULL, NULL, 'nan', '4717536602184', NULL, 'baik', 0, NULL, 182, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(582, 'PX002', 'PX Wireless Presentation', NULL, NULL, 'nan', '4717536602184', NULL, 'baik', 0, NULL, 182, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(583, 'AM001', 'Atomos Shogun 7\" Monitor', NULL, NULL, 'nan', 'K1194SH750D55', NULL, 'baik', 0, NULL, 187, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(584, 'AM002', 'Atomos Docking SSD', NULL, NULL, 'Atomos SSD Docking Type-C', 'K1294PHX00G70', NULL, 'baik', 0, NULL, 182, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(585, 'FW001', 'Feel World 7\" Monitor', NULL, NULL, 'nan', 'L7S35220767', NULL, 'baik', 0, NULL, 187, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(586, 'BAT003', 'Battery Pack Sony NP-F970', NULL, NULL, 'Battery Pack Sony NP-F970 6600mAh', 'Y21ZG-1EK', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(587, 'BAT012', 'Battery Pack SONY NP-F770', NULL, NULL, 'nan', 'nan', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(588, 'RF001', 'Sndway Laser Rangefinder', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 185, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(589, 'RF002', 'Sndway Laser Distance Meter', NULL, NULL, 'Sndway Laser Distance Meter H-D100A', '230187507', 2024, 'baik', 0, NULL, 185, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(590, 'RF003', 'Sndway Laser Distance Meter', NULL, NULL, 'Sndway Laser Distance Meter H-D100A', '230026702', NULL, 'baik', 0, NULL, 185, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(591, 'GP001', 'GoPro Hero 7', NULL, NULL, 'nan', 'C3261325694482', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(592, 'LI001', 'LITE LED Lighting', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 183, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(593, 'AN001', 'AOKI UTX AT-6100 Antena Digital', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 194, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(594, 'HD001', 'Hyper Drive MacBook Type-C Converter', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(595, 'HD002', 'Hyper Drive MacBook Type-C Converter', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(596, 'GC001', 'Google Chrome Cast 4K', NULL, NULL, 'nan', '1C151HFDD4X1DB', 2023, 'baik', 0, NULL, 182, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(597, 'MK001', 'Mikrotik Cloud Switch', NULL, NULL, 'Mikrotik Cloud Switch CSS610-8G-2S+IN', 'HDA08CC785M/242', 2024, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(598, 'MX001', 'MOXA Nport 5630 16 Port Switch', NULL, NULL, 'nan', 'TAZF01027482', NULL, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(599, 'EA001', 'EVA-214 VGA EXTENDER', NULL, NULL, 'nan', 'V35000MK00090', NULL, 'baik', 0, NULL, 195, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(600, 'EA002', 'EVA-214 VGA EXTENDER', NULL, NULL, 'nan', 'V37000MK00090', NULL, 'baik', 0, NULL, 195, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(601, 'EA003', 'EVA-214 VGA EXTENDER', NULL, NULL, 'nan', 'V35000MK00081', NULL, 'baik', 0, NULL, 195, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(602, 'EA004', 'EVA-214 VGA EXTENDER', NULL, NULL, 'nan', 'V37000MK00081', NULL, 'baik', 0, NULL, 195, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(603, 'HL001', 'HOLLYLAND MARS T1000', NULL, NULL, 'nan', '022251T1012159', 2023, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(604, 'HL002', 'HOLLYLAND MARS T1000', NULL, NULL, 'nan', '022251T101215A', 2023, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(605, 'DC001', 'DCK KDJF22 Reciprocating Saw', NULL, NULL, 'nan', '612309003305', 2024, 'baik', 0, NULL, 184, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(606, 'TL001', 'Lensa CANON HDTV Zoom Lens 40X', NULL, NULL, 'nan', '0032T763', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(607, 'MS001', 'Marshall CV506 HD Cam', NULL, NULL, 'nan', '15520501CD0582', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(608, 'SH001', 'SDI to HDMI Converter', NULL, NULL, 'nan', '160800000000000992', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(609, 'KR001', 'KRAMER VM-5HD 3G HD-SDI DA', NULL, NULL, 'nan', '09121166500068', NULL, 'baik', 0, NULL, 186, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(610, 'FL001', 'FLUKE 117 TRUE RMS MULTIMETER', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 185, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(611, 'TL002', 'Lensa CANON HDTV Zoom Lens 40X', NULL, NULL, 'nan', '00610046', NULL, 'baik', 0, NULL, 193, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(612, 'OPT001', 'OPTIC CABLE 300M - KUNING', NULL, NULL, 'nan', 'nan', NULL, 'baik', 0, NULL, 192, '2025-09-25 17:32:19', '2025-09-25 17:52:33'),
(651, 'AV001', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40765084', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(652, 'AV002', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40765152', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(653, 'AV003', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40765087', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(654, 'AV004', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40765052', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(655, 'AV005', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40765110', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(656, 'AV006', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112A56023122', 2023, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(657, 'AV007', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112A56023154', 2023, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(658, 'AV008', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112A56023132', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(659, 'AV009', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40767101', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(660, 'AV010', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40767085', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(661, 'AV011', 'AV Matrix SDI to HDMI', NULL, NULL, 'nan', '1112D40767071', 2024, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(662, 'DCK001', 'Orico HDD/SSD Docking', NULL, NULL, 'nan', '24051765229', 2024, 'baik', 0, NULL, 182, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(663, 'WL001', 'Working Lamp Sunrei C1500', NULL, NULL, 'nan', 'SMG22D0381', 2024, 'baik', 0, NULL, 183, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(664, 'WL002', 'Working Lamp Sunrei C1500', NULL, NULL, 'nan', 'SMG22D0426', 2024, 'baik', 0, NULL, 183, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(665, 'BD001', 'B+D Smart Screwdriver', NULL, NULL, 'nan', '5056174512554', 2024, 'baik', 0, NULL, 184, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(666, 'DP001', 'Thermaltake PSU Tester', NULL, NULL, 'nan', 'AC069OO1NANA1ODCW00970', 2024, 'baik', 0, NULL, 185, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(667, 'BMD001', 'BMD SDI Distribution', NULL, NULL, 'nan', '5707953', NULL, 'baik', 0, NULL, 186, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(668, 'BMD002', 'BMD UpDownCross', NULL, NULL, 'nan', '2400931', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(669, 'BMD003', 'BMD UpDownCross', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(670, 'BMD004', 'BMD UpDownCross', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(671, 'BMD005', 'BMD UpDownCross', NULL, NULL, 'nan', '2400951', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(672, 'BMD006', 'BMD Audio to SDI', NULL, NULL, 'nan', '2123232', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(673, 'BMD007', 'BMD SDI to Analog', NULL, NULL, 'nan', '1550901', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(674, 'BMD008', 'BMD HDMI to SDI', NULL, NULL, 'nan', '2384096', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(675, 'BMD009', 'BMD DVI to SDI', NULL, NULL, 'nan', '2205836', NULL, 'baik', 0, NULL, 181, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(676, 'BAT001', 'Battery Pack Kingma 1960mAh', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(677, 'BAT002', 'Battery Pack Kingma 1960mAh', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(678, 'BAT004', 'Battery Pack Powerange DF-248', NULL, NULL, 'Battery Pack Powerange DF-248 6600mAh', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(679, 'BAT005', 'Battery Pack VECT V-Mount', NULL, NULL, 'Battery Pack VECT V-Mount ET-130S 14,8V 130Wh', 'YH160818PS20102', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(680, 'BAT006', 'Battery Pack VECT V-Mount', NULL, NULL, 'Battery Pack VECT V-Mount ET-130S 14,8V 130Wh', 'YH160818PS20170', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(681, 'BAT007', 'Battery Pack NP-F970', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(682, 'BAT008', 'Battery Pack NP-F970', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(683, 'BAT009', 'Battery Pack NP-F970', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(684, 'BAT010', 'Battery Pack CN-LP-E6', NULL, NULL, 'nan', '-', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(685, 'BAT011', 'Battery Pack CN-LP-E6', NULL, NULL, 'nan', 'nan', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(686, 'SB001', 'STB POLITRON PDV 620T2', NULL, NULL, 'nan', 'AOL423A12663', NULL, 'baik', 0, NULL, 250, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(687, 'AP001', 'APUTURE AMARAN TRI-8C V-MOUNT', NULL, NULL, 'nan', '6BH00P88582', NULL, 'baik', 0, NULL, 249, '2025-09-25 17:52:33', '2025-09-25 17:52:33'),
(690, 'KAM007', 'Kamera Sigma A6000', 'Sonic', 'Sigma', 'nyoba update', '123', 2024, 'baik', 0, 'Bogor', 193, '2025-10-18 21:48:43', '2025-10-18 21:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `partner_id` bigint UNSIGNED NOT NULL,
  `loan_date` datetime NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('dipinjam','sebagian_kembali','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_items`
--

CREATE TABLE `loan_items` (
  `id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `return_condition` enum('baik','rusak_ringan','rusak_berat','hilang') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_09_112410_create_categories_table', 1),
(5, '2025_08_09_112718_create_items_table', 1),
(6, '2025_08_09_113851_create_partners_table', 1),
(7, '2025_08_09_113908_create_loans_table', 1),
(8, '2025_08_09_114148_create_loan_items_table', 1),
(9, '2025_08_09_115000_create_assets_table', 1),
(10, '2025_08_29_120000_merge_assets_into_items', 1),
(11, '2025_09_25_000001_add_is_missing_to_items_table', 2),
(12, '2025_09_25_000002_alter_loan_items_add_hilang_return_condition', 2),
(13, '2025_10_19_000001_rename_purpose_to_lokasi_on_loans_table', 3),
(14, '2025_10_19_000002_add_lokasi_to_items_table', 3),
(15, '2025_10_19_000003_add_merk_tipe_to_items_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `unit`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Jale', NULL, NULL, '2025-09-15 07:18:13', '2025-09-15 07:18:13'),
(2, 'Komeng', NULL, NULL, '2025-09-15 08:53:04', '2025-09-15 08:53:04'),
(3, 'Rizal', 'Personal', '08333659359', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(4, 'Farqie', 'Personal', '08489786093', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(5, 'Daniel', 'Personal', '08213022233', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(6, 'Dhia M', 'Personal', '08440673887', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(7, 'Salmen K', 'Personal', '08770121541', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(8, 'Naafa', 'Personal', '08254208944', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(9, 'Nasrul', 'Personal', '08672350040', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(10, 'Yuman', 'Personal', '08278678903', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(11, 'Haidar', 'Personal', '08198999699', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(12, 'Nuge', 'Personal', '08403957421', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(13, 'Akbal', 'Personal', '08493811516', '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(14, 'Adul', NULL, NULL, '2025-10-19 00:15:47', '2025-10-19 00:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('i5ktHVabVzgPgkLlYTaIavwh3qkNGZKg8mrUCcIL', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNEtCNlhJUml3WlF3MTZwbUNGRmpRU0VkdXVoZDRzdFJ3clZxU0RRMyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovL2Jyb2FkY2FzdC1sb2FuLnRlc3Q6ODA4MCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760860757);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jal Jal Rijal', 'jal@admin.com', NULL, '$2y$12$vWrcdq/JzJsQBjejfz62GOqGHe21nbgs6Gzayw8xvGnwZpZE2.Ohq', NULL, '2025-09-15 07:05:39', '2025-09-15 07:05:39'),
(2, 'Muhammad Rizal Anshurulloh', 'mrizalansh@gmail.com', NULL, '$2y$12$GpiGEvEewyRglenFJZNQSOYDswRf/FKpIiM6xbjC.LnUq/huvNsx6', 'HY1rdDjqZj9u7iEJB9TYmHo5B8FfyfUGnHIduqFQTDV262Fh9SMqVEvj2svx', '2025-09-20 20:19:42', '2025-09-20 20:19:42'),
(3, 'Rizal', 'rizal@example.com', NULL, '$2y$12$SDfkB0dX4wMHCVXqwiFbQ.jhXEVzipiNjpp5pnahbch1hB8hdtfXS', NULL, '2025-09-25 10:49:52', '2025-09-25 10:49:52'),
(4, 'Farqie', 'farqie@example.com', NULL, '$2y$12$zMO/LIkcwnLxfRblZBmztuPKKEXfrxKYiYewW2kudjlt/mJ.peep2', NULL, '2025-09-25 10:49:52', '2025-09-25 10:49:52'),
(5, 'Daniel', 'daniel@example.com', NULL, '$2y$12$nV2Rig3IM3S80Diq0DDLU.txhYPI/JCdckaSAYcUe9fhdpejNXnJq', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(6, 'Dhia M', 'dhia.m@example.com', NULL, '$2y$12$4QV9OVbnWbgC4qbzxcdxE.WgAhmZXXRbgkFc35Pf.zKz9EGU7BiU2', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(7, 'Salmen K', 'salmen.k@example.com', NULL, '$2y$12$HPn3uo.fAy98MAK3AGCfL.Il0yshp34JNVc8prcH1zbbobu83kSXW', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(8, 'Naafa', 'naafa@example.com', NULL, '$2y$12$Ma6v.V/A4EergCa7b0/XjOBJbuRmbrviHbhYXbgjT1F62tYH3wBI6', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(9, 'Nasrul', 'nasrul@example.com', NULL, '$2y$12$WZ.S9v5rngThtWaeckgzy.bjoKLsGrOVglw9zrEAp1R835aqtG006', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(10, 'Yuman', 'yuman@example.com', NULL, '$2y$12$YVbdxWTFf1PXSJUHLXvPjebJ0OmvOeJnvmrXpzMdfGllnqEgZ6jcS', NULL, '2025-09-25 10:49:53', '2025-09-25 10:49:53'),
(11, 'Haidar', 'haidar@example.com', NULL, '$2y$12$WTcYn4EXNeiXZGzPUJRrC.1sw1cCAVA4HwfOeS..iS9zecrU774WC', NULL, '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(12, 'Nuge', 'nuge@example.com', NULL, '$2y$12$zL1cDBx1OIvP9zYWztaob.y6/jksI1zrCX3QiZw3hC///Az1bMoxa', NULL, '2025-09-25 10:49:54', '2025-09-25 10:49:54'),
(13, 'Akbal', 'akbal@example.com', NULL, '$2y$12$wM4oJsWk9JQq9CyJfTnTJ.SS.h7Ew0.qCN6LApDWop41DDeO3K7I2', NULL, '2025-09-25 10:49:54', '2025-09-25 10:49:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_code_category_unique` (`code_category`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loans_code_unique` (`code`),
  ADD KEY `loans_partner_id_foreign` (`partner_id`),
  ADD KEY `loans_user_id_foreign` (`user_id`);

--
-- Indexes for table `loan_items`
--
ALTER TABLE `loan_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_items_loan_id_item_id_unique` (`loan_id`,`item_id`),
  ADD KEY `loan_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=691;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `loan_items`
--
ALTER TABLE `loan_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_items`
--
ALTER TABLE `loan_items`
  ADD CONSTRAINT `loan_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_items_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
