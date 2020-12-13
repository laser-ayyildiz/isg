-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 11 Ara 2020, 06:39:17
-- Sunucu sürümü: 8.0.22-0ubuntu0.20.04.3
-- PHP Sürümü: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `isg`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coop_companies`
--

CREATE TABLE `coop_companies` (
  `id` int NOT NULL,
  `comp_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `is_veren` varchar(250) DEFAULT NULL,
  `mail` varchar(125) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `address` varchar(2500) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `town` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `contract_date` date NOT NULL,
  `danger_type` int NOT NULL DEFAULT '0',
  `nace_kodu` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `mersis_no` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `sgk_sicil` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `vergi_no` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `vergi_dairesi` varchar(500) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `katip_is_yeri_id` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `katip_kurum_id` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT '0',
  `uzman_id` int DEFAULT '0',
  `uzman_id_2` int DEFAULT '0',
  `uzman_id_3` int DEFAULT '0',
  `hekim_id` int DEFAULT '0',
  `hekim_id_2` int DEFAULT '0',
  `hekim_id_3` int DEFAULT '0',
  `saglık_p_id` int DEFAULT '0',
  `saglık_p_id_2` int DEFAULT '0',
  `ofis_p_id` int DEFAULT '0',
  `ofis_p_id_2` int DEFAULT '0',
  `muhasebe_p_id` int DEFAULT '0',
  `muhasebe_p_id_2` int DEFAULT '0',
  `yetkili_id` int DEFAULT '0',
  `change` varchar(2) NOT NULL DEFAULT '1',
  `remi_freq` int DEFAULT '0',
  `changer` varchar(100) DEFAULT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `coop_companies`
--

INSERT INTO `coop_companies` (`id`, `comp_type`, `name`, `is_veren`, `mail`, `phone`, `address`, `city`, `town`, `contract_date`, `danger_type`, `nace_kodu`, `mersis_no`, `sgk_sicil`, `vergi_no`, `vergi_dairesi`, `katip_is_yeri_id`, `katip_kurum_id`, `uzman_id`, `uzman_id_2`, `uzman_id_3`, `hekim_id`, `hekim_id_2`, `hekim_id_3`, `saglık_p_id`, `saglık_p_id_2`, `ofis_p_id`, `ofis_p_id_2`, `muhasebe_p_id`, `muhasebe_p_id_2`, `yetkili_id`, `change`, `remi_freq`, `changer`, `deleted`) VALUES
(432, 'Hizmet', 'deneme1', NULL, 'deneme@gmail.com', '11111111111', 'dsfsd', 'Bursa', 'Gemlik', '2020-12-05', 1, '86.90.17', '0', '0', '0', '', '0', '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 0, NULL, 0),
(433, 'Sağlık', 'deneme2', NULL, 'deneme@gmail.com', '22222222222', 'zxcz', 'Bilecik', 'Merkez', '2020-12-05', 1, '82.20.01', '1111111111111111', '222222222222', '2222222222', 'yeşilyurt vergi dairesi', '222222222222222222222222222222', '111111111111111111111111111111', 58, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 0, NULL, 0),
(434, 'Sağlık', 'deneme3', NULL, 'deneme@gmail.com', '33333333333', 'asdasd', 'Çankırı', 'Merkez', '2020-12-06', 2, '86.90.17', '0', '0', '0', '', '0', '0', 52, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 0, NULL, 0),
(435, 'Sanayi', 'deneme4', NULL, 'aliserkan@gmail.com', '33333333333', 'asdasd', 'Çankırı', 'Merkez', '2020-12-06', 2, '56.10.20', '0', '0', '0', '', '0', '0', 58, 52, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 0, NULL, 0),
(436, 'Tarım', 'yeni_yapı', NULL, 'deneme@gmail.com', '44444444444', 'sadadad', 'Çanakkale', 'Ayvacık', '2020-12-07', 1, '71.12.14', '0', '0', '0', '', '0', '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 0, NULL, 0),
(509, 'Hizmet', 'deneme6', NULL, 'deneme@gmail.com', '44444444444', 'adasdadasd', 'Çanakkale', 'Bozcaada', '2020-12-09', 3, '81.22.03', '1111111111111111', '111111111111', '1111111111', 'yeşilyurt vergi dairesi', '222222222222222222222222222222', '111111111111111111111111111111', 58, 56, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 10, 'laserayyildiz@gmail.com', 1),
(514, 'Hizmet', 'deneme5', NULL, 'aaa@gmail.com', '12312312321', 'ddd', 'Çankırı', 'Merkez', '2020-12-09', 3, '0', '2222222222222222', '222222222222', '2222222222', 'aaaa vergi dairesi', '222222222222222222222222222222', '222222222222222222222222222222', 58, 56, 52, 58, 0, 0, 58, 0, 58, 0, 58, 0, 0, '1', 7, 'laserayyildiz@gmail.com', 0),
(515, 'Hizmet', 'deneme7', 'Laser Ayyıldız', 'deneme@gmail.com', '77777777777', 'deneme', 'Çankırı', 'Merkez', '2020-12-10', 3, '82.20.01', '7777777777777777', '777777777777', '7777777777', 'deneme7', '777777777777777777777777777777', '777777777777777777777777777777', 56, 58, 0, 57, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 9, NULL, 0),
(516, 'Hizmet', 'deneme8', 'Diyar Ayyıldız', 'deneme@gmail.com', '88888888888', 'ertertertertet', 'Denizli', 'Acıpayam', '2020-12-10', 1, '86.90.17', '8888888888888888', '888888888888', '8888888888', 'aaaaaaaa', '888888888888888888888888888888', '888888888888888888888888888888', 56, 0, 0, 57, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 36, NULL, 0),
(517, 'Tarım', 'deneme9', NULL, 'deneme@gmail.com', '99999999999', 'aaaaaaaaaaa', 'Çanakkale', 'Ayvacık', '2020-12-10', 2, '86.90.17', '9999999999999999', '999999999999', '9999999999', 'aa', '999999999999999999999999999999', '999999999999999999999999999999', 56, 0, 0, 57, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 18, NULL, 0),
(518, 'Diğer', 'deneme10', 'Diyar Ayyıldız', 'deneme@gmail.com', '10100101010', 'deneme', 'Çorum', 'Alaca', '2020-12-10', 3, '82.20.01', '1010101001010101', '101010010101', '1010100101', 'deneme', '101010010101001010101010101010', '101001010101001010100101010010', 56, 0, 0, 57, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', 36, NULL, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coop_workers`
--

CREATE TABLE `coop_workers` (
  `id` int NOT NULL,
  `company_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `tc` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `position` varchar(350) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `sex` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `contract_date` date NOT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `coop_workers`
--

INSERT INTO `coop_workers` (`id`, `company_id`, `name`, `tc`, `position`, `sex`, `mail`, `phone`, `contract_date`, `deleted`) VALUES
(120, 448, 'laser ayyıldız', '43658564345', 'mkjashdşlkash', 'Erkek', 'ali@ali', '82365083297', '2020-12-08', 0),
(121, 448, 'jaksgdlkj jsakhdljkasd', '32547657563', 'asdsadas', 'Kadın', 'ali@ali', '82365083297', '2020-12-08', 0),
(122, 508, 'vddfvd', '11111111111', 'müdür', 'Erkek', 'vddfvd@vddfvd', '11111111111', '2020-12-09', 0),
(123, 508, 'asdsad', '22222222222', 'jasgdkjas', 'Kadın', 'asdsad@asdsad', '22222222222', '2020-12-09', 0),
(124, 508, 'laser ayyıldız', '67238647832', 'müdür', 'Erkek', 'laser@laser', '82365083297', '2020-12-09', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coop_workplaces`
--

CREATE TABLE `coop_workplaces` (
  `id` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `coop_wp` int NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `districts`
--

CREATE TABLE `districts` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `equipment`
--

CREATE TABLE `equipment` (
  `id` int NOT NULL,
  `company_id` int NOT NULL DEFAULT '0',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `purpose` varchar(200) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `maintenance_freq` int NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `equipment`
--

INSERT INTO `equipment` (`id`, `company_id`, `name`, `purpose`, `maintenance_freq`) VALUES
(1, 430, 'asdasd', 'adasda', 4),
(2, 52, 'assadas', 'dsadasda', 6),
(3, 429, 'sacascas', 'csacaca', 9),
(4, 429, 'cascs', 'sacsa', 5),
(5, 435, 'assadas', 'dsadasda', 4),
(6, 435, 'assadas', 'dsadasda', 4),
(7, 435, 'cascsjvmgcvmnvmn', 'csacaca', 6),
(8, 435, 'assadas', 'dsadasda', 4),
(9, 435, 'yeni yeni', 'yeniynei', 3),
(10, 435, 'assadas', 'dsadasda', 1),
(11, 437, 'assadas', 'dsadasda', 3),
(12, 508, 'assadas', 'dsadasda', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `company_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `notif` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `events`
--

INSERT INTO `events` (`id`, `user_id`, `company_id`, `title`, `description`, `color`, `start`, `end`, `notif`) VALUES
(132, 52, 0, 'asacascasc', 'şalsöcşsaö', '#40E0D0', '2020-12-23 13:32:31', '2020-12-11 00:00:00', 1),
(138, 52, 435, 'qqqqqqqqqqqqqqqqqqq', 'asdasd', '#008000', '2020-12-23 08:38:34', '2020-12-02 00:00:00', 1),
(140, 52, 437, 'qqqqqqqqqqqqqqqqqqq', 'asdas', '#0071c5', '2020-12-23 10:20:45', '2020-12-02 00:00:00', 1),
(143, 52, 508, 'asfsa', 'asdasdasda', '#FFD700', '2020-12-23 04:46:32', '2020-12-24 00:00:00', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `konu` text CHARACTER SET utf8 COLLATE utf8_turkish_ci,
  `kimden` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `kime` varchar(1000) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `mesaj` text CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `tarih` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `okundu` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `notif_text` text COLLATE utf8_turkish_ci NOT NULL,
  `user_id` int NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `notifications`
--

INSERT INTO `notifications` (`id`, `notif_text`, `user_id`, `reg_date`) VALUES
(304, 'Başlık: asacascasc, Açıklama: şalsöcşsaö', 52, '2020-12-09 19:54:07'),
(305, 'deneme5 işletmesi üzerinde yaptığınız değişiklikler onaylandı', 52, '2020-12-09 22:08:40'),
(306, 'deneme5 işletmesi üzerinde yaptığınız değişiklikler onaylandı', 52, '2020-12-09 22:20:28'),
(307, 'deneme5 işletmesi üzerinde yaptığınız değişiklikler onaylandı', 52, '2020-12-09 22:24:36'),
(308, 'deneme5 işletmesi üzerinde yaptığınız değişiklikler onaylandı', 52, '2020-12-09 22:26:35'),
(309, 'deneme5 işletmesi üzerinde yaptığınız değişiklikler onaylandı', 52, '2020-12-09 22:28:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `osgb_companies`
--

CREATE TABLE `osgb_companies` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `boss_name` varchar(100) NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `town` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `district` varchar(100) NOT NULL,
  `v_dairesi` varchar(100) NOT NULL,
  `v_no` varchar(25) NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `osgb_companies`
--

INSERT INTO `osgb_companies` (`id`, `name`, `boss_name`, `mail`, `phone`, `address`, `city`, `town`, `district`, `v_dairesi`, `v_no`) VALUES
(1, 'Özgür OSGB', 'Özgür Fırat Yakut', 'info@ozgurosgb.com.tr', '05000000000', 'Birtane Plaza Kat:7', 'Diyarbakır', 'Bağlar', 'Zaviye', 'Diyarbakır Bağlar Vergi Dairesi', '111111111');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `osgb_workers`
--

CREATE TABLE `osgb_workers` (
  `id` int NOT NULL,
  `user_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `firstname` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `lastname` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `tc` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `start_date` date NOT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  `worker_text` text CHARACTER SET utf8 COLLATE utf8_turkish_ci,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `osgb_workers`
--

INSERT INTO `osgb_workers` (`id`, `user_type`, `firstname`, `lastname`, `mail`, `phone`, `tc`, `start_date`, `deleted`, `worker_text`) VALUES
(61, 'İsg Uzmanı 3', 'ahmet', 'ali', 'laser.ayyildiz@outlook.com', '33333333333', '33333333333', '2015-11-11', 0, NULL),
(62, 'İş Yeri Hekimi', 'veli', 'veli', 'laser.ayyildiz@protonmail.com', '43534543534', '43543532423', '2020-11-07', 0, NULL),
(63, 'İsg Uzmanı 2', 'diyar', 'Ayyıldız', 'angaralastigi@gmail.com', '11111111111', '43234243243', '2020-11-05', 0, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `picture` varchar(250) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL DEFAULT 'custom.png',
  `firstname` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `lastname` varchar(30) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `valid_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `valid` varchar(10) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL DEFAULT '0',
  `auth` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `picture`, `firstname`, `lastname`, `username`, `phone`, `password`, `valid_code`, `valid`, `auth`) VALUES
(52, '44266930013LaserAyyıldız52.jpeg', 'Laser', 'Ayyıldız', 'laserayyildiz@gmail.com', '32432432432', '6ebf287c5b21aa2fb3d601da54dfa1ab', '23caaab96dd8fdec6ed0b1350a64c4d5', '1', 1),
(56, 'custom.png', 'ahmet', 'ali', 'laser.ayyildiz@outlook.com', '33333333333', '6ebf287c5b21aa2fb3d601da54dfa1ab', '4122d34c54d8b26f5d783a909b287fd1', '1', 0),
(57, 'custom.png', 'veli', 'veli', 'laser.ayyildiz@protonmail.com', '43534543534', '6ebf287c5b21aa2fb3d601da54dfa1ab', '1542e7a17b0947ba536c6df73bc0d003', '1', 2),
(58, 'custom.png', 'diyar', 'Ayyıldız', 'angaralastigi@gmail.com', '11111111111', '6ebf287c5b21aa2fb3d601da54dfa1ab', 'f3c100abef71d522d259019df56a056c', '1', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coop_companies`
--
ALTER TABLE `coop_companies`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coop_workers`
--
ALTER TABLE `coop_workers`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coop_workplaces`
--
ALTER TABLE `coop_workplaces`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `osgb_companies`
--
ALTER TABLE `osgb_companies`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `osgb_workers`
--
ALTER TABLE `osgb_workers`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `coop_companies`
--
ALTER TABLE `coop_companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=519;

--
-- Tablo için AUTO_INCREMENT değeri `coop_workers`
--
ALTER TABLE `coop_workers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Tablo için AUTO_INCREMENT değeri `coop_workplaces`
--
ALTER TABLE `coop_workplaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- Tablo için AUTO_INCREMENT değeri `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Tablo için AUTO_INCREMENT değeri `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- Tablo için AUTO_INCREMENT değeri `osgb_companies`
--
ALTER TABLE `osgb_companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `osgb_workers`
--
ALTER TABLE `osgb_workers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
