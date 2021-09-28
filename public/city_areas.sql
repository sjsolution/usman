-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2019 at 11:55 AM
-- Server version: 10.1.41-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev-red-carpet`
--

-- --------------------------------------------------------

--
-- Table structure for table `city_areas`
--

CREATE TABLE `city_areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `city_areas`
--

INSERT INTO `city_areas` (`id`, `name_en`, `name_ar`, `city_id`, `created_at`, `updated_at`) VALUES
(1, 'Kuwait City', 'مدينة الكويت', 1, NULL, NULL),
(2, 'Dasmān', 'دسمان', 1, NULL, NULL),
(3, 'Sharq', 'شرق', 1, NULL, NULL),
(4, 'Mirgāb', 'المرقاب', 1, NULL, NULL),
(5, 'Jibla', 'جبلة', 1, NULL, NULL),
(6, 'Dasma', 'الدسمة', 1, NULL, NULL),
(7, 'Da\'iya', 'الدعية', 1, NULL, NULL),
(8, 'Sawābir', 'الصوابر', 1, NULL, NULL),
(9, 'Salhiya', 'الصالحية', 1, NULL, NULL),
(10, 'Bneid il-Gār', 'بنيد القار', 1, NULL, NULL),
(11, 'Kaifan', 'كيفان', 1, NULL, NULL),
(12, 'Mansūriya', 'المنصورية', 1, NULL, NULL),
(13, 'Abdullah as-Salim suburb', 'ضاحية عبد الله السالم', 1, NULL, NULL),
(14, 'Nuzha', 'النزهة', 1, NULL, NULL),
(15, 'Faiha\'', 'الفيحاء', 1, NULL, NULL),
(16, 'Shamiya', 'الشامية', 1, NULL, NULL),
(17, 'Rawda', 'الروضة', 1, NULL, NULL),
(18, 'Adiliya', 'العديلية', 1, NULL, NULL),
(19, 'Khaldiya', 'الخالدية', 1, NULL, NULL),
(20, 'Qadsiya', 'القادسية', 1, NULL, NULL),
(21, 'Qurtuba', 'قرطبة', 1, NULL, NULL),
(22, 'Surra', 'السرة', 1, NULL, NULL),
(23, 'Yarmūk', 'اليرموك', 1, NULL, NULL),
(24, 'Shuwaikh', 'الشويخ', 1, NULL, NULL),
(25, 'Rai', 'الري', 1, NULL, NULL),
(26, 'Ghirnata', 'غرناطة', 1, NULL, NULL),
(27, 'Sulaibikhat', 'الصليبخات', 1, NULL, NULL),
(28, 'Doha', 'الدوحة', 1, NULL, NULL),
(29, 'Nahdha', 'النهضة', 1, NULL, NULL),
(30, 'Jabir al-Ahmad City', 'مدينة جابر الأحمد', 1, NULL, NULL),
(31, 'Qairawān', 'القيروان', 1, NULL, NULL),
(32, 'Hawally', 'حولي', 2, NULL, NULL),
(33, 'Rumaithiya', 'الرميثية', 2, NULL, NULL),
(34, 'Jabriya', 'الجابرية', 2, NULL, NULL),
(35, 'Salmiya', 'السالمية', 2, NULL, NULL),
(36, 'Mishrif', 'مشرف', 2, NULL, NULL),
(37, 'Sha\'ab', 'الشعب', 2, NULL, NULL),
(38, 'Bayān', 'بيان', 2, NULL, NULL),
(39, 'Bi\'di\'', 'البدع', 2, NULL, NULL),
(40, 'Nigra', 'النقرة', 2, NULL, NULL),
(41, 'Salwa', 'سلوى', 2, NULL, NULL),
(42, 'Maidan Hawalli', 'ميدان حولي', 2, NULL, NULL),
(43, 'Mubarak aj-Jabir suburb', 'ضاحية مبارك الجابر', 2, NULL, NULL),
(44, 'South Surra', 'جنوب السرة', 2, NULL, NULL),
(45, 'Hittin', 'حطين', 2, NULL, NULL),
(46, 'Ardiya', 'عارضية', 3, NULL, NULL),
(47, 'Industrial Ardiya', 'عارضية الصناعية', 3, NULL, NULL),
(48, 'Fordous', 'فردوس', 3, NULL, NULL),
(49, 'Farwaniya', 'فروانية', 3, NULL, NULL),
(50, 'Shadadiya', 'شدادية', 3, NULL, NULL),
(51, 'Rihab', 'رحاب', 3, NULL, NULL),
(52, 'Rabiya', 'رابية', 3, NULL, NULL),
(53, 'Industrial Rai', 'الري الصناعية', 3, NULL, NULL),
(54, 'Abdullah Al Mubarak', 'ضاحية عبدالله المبارك', 3, NULL, NULL),
(55, 'Dajeej', 'الضجيج', 3, NULL, NULL),
(56, 'South Khaitan', 'جنوب خيطان', 3, NULL, NULL),
(57, 'Mubarak al-Kabeer', 'مبارك الكبير', 4, NULL, NULL),
(58, 'Adān', 'العدان', 4, NULL, NULL),
(59, 'Qurain', 'القرين', 4, NULL, NULL),
(60, 'Qusūr', 'القصور', 4, NULL, NULL),
(61, 'Sabah as-Salim suburb', 'ضاحية صباح السالم', 4, NULL, NULL),
(62, 'Misīla', 'المسيلة', 4, NULL, NULL),
(63, 'Abu \'Fteira', 'أبو فطيرة', 4, NULL, NULL),
(64, 'Sabhān', 'صبحان', 4, NULL, NULL),
(65, 'Fintās', 'الفنطاس', 4, NULL, NULL),
(66, 'Funaitīs', 'الفنيطيس', 4, NULL, NULL),
(67, 'Ahmadi', 'الأحمدي', 5, NULL, NULL),
(68, 'Aqila', 'العقيلة', 5, NULL, NULL),
(69, 'Zuhar', 'الظهر', 5, NULL, NULL),
(70, 'Miqwa\'', 'المقوع', 5, NULL, NULL),
(71, 'Mahbula', 'المهبولة', 5, NULL, NULL),
(72, 'Rigga', 'الرقة', 5, NULL, NULL),
(73, 'Hadiya', 'هدية', 5, NULL, NULL),
(74, 'Abu Hulaifa', 'أبو حليفة', 5, NULL, NULL),
(75, 'Sabahiya', 'الصباحية', 5, NULL, NULL),
(76, 'Mangaf', 'المنقف', 5, NULL, NULL),
(77, 'Fahaheel', 'الفحيحيل', 5, NULL, NULL),
(78, 'Wafra', 'الوفرة', 5, NULL, NULL),
(79, 'Zoor', 'الزور', 5, NULL, NULL),
(80, 'Khairan', 'الخيران', 5, NULL, NULL),
(81, 'Abdullah Port', 'ميناء عبد الله', 5, NULL, NULL),
(82, 'Agricultural Wafra', 'الوفرة الزراعية', 5, NULL, NULL),
(83, 'Bneidar', 'بنيدر', 5, NULL, NULL),
(84, 'Jilei\'a', 'الجليعة', 5, NULL, NULL),
(85, 'Jabir al-Ali Suburb', 'ضاحية جابر العلي', 5, NULL, NULL),
(86, 'Fahd al-Ahmad Suburb', 'ضاحية فهد الأحمد', 5, NULL, NULL),
(87, 'Shu\'aiba', 'الشعيبة', 5, NULL, NULL),
(88, 'Sabah al-Ahmad City', 'مدينة صباح الأحمد', 5, NULL, NULL),
(89, 'Nuwaiseeb', 'النويصيب', 5, NULL, NULL),
(90, 'Khairan City', 'مدينة الخيران', 5, NULL, NULL),
(91, 'Ali as-Salim suburb', 'ضاحية علي صباح السالم', 5, NULL, NULL),
(92, 'Sabah al-Ahmad Nautical City', 'مدينة صباح الأحمد البحرية', 5, NULL, NULL),
(93, 'Al abdally', 'العبدلي', 6, NULL, NULL),
(94, 'Al bhaith', 'Al bhaith', 6, NULL, NULL),
(95, 'Al jahra', 'الجهرا', 6, NULL, NULL),
(96, 'Al khwaisat', 'Al khwaisat', 6, NULL, NULL),
(97, 'Al mutlaa', 'المطلاع', 6, NULL, NULL),
(98, 'Al naeem', 'Al naeem', 6, NULL, NULL),
(99, 'Al naseem', 'النسيم', 6, NULL, NULL),
(100, 'Aloyoon', 'العيون', 6, NULL, NULL),
(101, 'Alqaser', 'القيصرية', 6, NULL, NULL),
(102, 'Alretqah', 'الرقة', 6, NULL, NULL),
(103, 'Alroudhatain', 'الروضتين', 6, NULL, NULL),
(104, 'Alsalmy', 'السالمي', 6, NULL, NULL),
(105, 'Alsubbyah', 'الصبية', 6, NULL, NULL),
(106, 'Alsulaibya', 'الصليبية', 6, NULL, NULL),
(107, 'Alwaha', 'الواحة', 6, NULL, NULL),
(108, 'Amghara', 'أمغرة', 6, NULL, NULL),
(109, 'Boubyan island', 'Boubyan island', 6, NULL, NULL),
(110, 'Jaber Alahmad City', 'مدينة جابر الأحمد ', 6, NULL, NULL),
(111, 'Kabd', 'كبد', 6, NULL, NULL),
(112, 'Kazma', 'كاضمة', 6, NULL, NULL),
(113, 'Om Alaish', 'Om Alaish', 6, NULL, NULL),
(114, 'Saad Alabdaullah City', 'مدينة سعد عبدالله', 6, NULL, NULL),
(115, 'Silk City', 'مدينة الحرير', 6, NULL, NULL),
(116, 'Taimaa', 'تيماء', 6, NULL, NULL),
(117, 'Warbah island', 'Warbah island', 6, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city_areas`
--
ALTER TABLE `city_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_areas_city_id_foreign` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city_areas`
--
ALTER TABLE `city_areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `city_areas`
--
ALTER TABLE `city_areas`
  ADD CONSTRAINT `city_areas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
