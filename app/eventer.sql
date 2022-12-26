-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 26. dec 2022 ob 16.34
-- Različica strežnika: 10.4.25-MariaDB
-- Različica PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `eventer`
--

-- --------------------------------------------------------

--
-- Struktura tabele `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `addresses`
--

INSERT INTO `addresses` (`id`, `street`, `city`, `postal_code`, `country_code`, `created_at`, `updated_at`) VALUES
(1, 'Naslov 123', 'Ljubljana', '1000', 'SL', '2022-12-15 14:29:36', '2022-12-15 14:29:36'),
(2, 'Naslov 231', 'Ljubljana', '1000', 'SL', '2022-12-16 17:23:14', '2022-12-16 17:23:14'),
(3, 'Naslov 41', 'Ljubljana', '1000', 'SL', '2022-12-17 16:44:08', '2022-12-17 16:44:08'),
(4, 'Naslov 42', 'Ljubljana', '1000', 'SL', '2022-12-17 16:44:18', '2022-12-17 16:44:18'),
(5, 'Naslov 12', 'Ljubljana', '1000', 'SL', '2022-12-21 16:49:51', '2022-12-21 16:49:51'),
(6, 'Naslov 122', 'Ljubljana', '1000', 'SL', '2022-12-24 14:44:46', '2022-12-24 14:44:46'),
(7, 'naslov', 'Ljubljana', '1000', 'SL', '2022-12-25 18:57:25', '2022-12-25 18:57:25');

-- --------------------------------------------------------

--
-- Struktura tabele `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `opens_at` time NOT NULL,
  `closes_at` time NOT NULL,
  `address_id` bigint(20) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `categories`
--

INSERT INTO `categories` (`id`, `name`, `category_image`, `description`, `agent_id`, `amount`, `opens_at`, `closes_at`, `address_id`, `deleted`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Restavracija Halo', '/category_images/1_Restavracija Halo.jpg', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 2, 5, '08:00:00', '22:00:00', 3, 0, NULL, '2022-12-15 14:33:04', '2022-12-17 16:44:08'),
(2, 'Restavracija HaloKatra', '/category_images/2_Restavracija Halo1233.jpg', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 2, 5, '07:00:00', '20:00:00', 4, 0, NULL, '2022-12-15 14:51:22', '2022-12-17 16:44:18');

-- --------------------------------------------------------

--
-- Struktura tabele `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '2014_10_12_000000_create_users_table', 1),
(9, '2014_10_12_100000_create_password_resets_table', 1),
(10, '2022_03_04_080042_create_categories_table', 1),
(11, '2022_03_04_081523_create_products_table', 1),
(12, '2022_03_08_104718_create_addresses_table', 1),
(13, '2022_03_08_105618_create_rents_table', 1),
(14, '2022_04_20_074948_create_reservations_table', 1);

-- --------------------------------------------------------

--
-- Struktura tabele `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `deleted`, `deleted_at`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'Miza št. 1', '/product_images/1_Miza št. 1.jpg', 0, NULL, '2022-12-15 15:01:16', '2022-12-15 15:04:47', 1),
(2, 'Miza št. 2', '/product_images/2_Miza št. 2.jpg', 0, NULL, '2022-12-17 14:27:34', '2022-12-17 14:27:34', 1),
(3, '123123', '/product_images/3_123123.jpg', 0, NULL, '2022-12-17 17:37:32', '2022-12-17 17:37:40', 2);

-- --------------------------------------------------------

--
-- Struktura tabele `rents`
--

CREATE TABLE `rents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `braintree_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_transaction_timestamp` timestamp NULL DEFAULT NULL,
  `braintree_refund_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_transaction_timestamp` timestamp NULL DEFAULT NULL,
  `ready_for_take_over` tinyint(1) NOT NULL DEFAULT 0,
  `equipment_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipment_ids`)),
  `reservation_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reservation_ids`)),
  `status` enum('pending','successfully_paid','in_progress','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rental_from` datetime DEFAULT NULL,
  `rental_to` datetime DEFAULT NULL,
  `canceled_by` bigint(20) DEFAULT NULL,
  `canceled_timestamp` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `rents`
--

INSERT INTO `rents` (`id`, `customer_id`, `braintree_transaction_id`, `payment_transaction_timestamp`, `braintree_refund_transaction_id`, `refund_transaction_timestamp`, `ready_for_take_over`, `equipment_ids`, `reservation_ids`, `status`, `rental_from`, `rental_to`, `canceled_by`, `canceled_timestamp`, `created_at`, `updated_at`) VALUES
(7, 3, 'interna rezervacija', '2022-12-17 06:09:14', NULL, NULL, 1, '[2]', '[10]', 'completed', '2022-12-18 10:00:00', '2022-12-18 17:00:00', NULL, NULL, '2022-12-17 17:45:17', '2022-12-25 16:46:24'),
(8, 3, NULL, NULL, NULL, NULL, 0, NULL, '[11]', 'pending', '2022-12-18 10:00:00', '2022-12-18 15:00:00', NULL, NULL, '2022-12-17 18:12:38', '2022-12-17 18:12:38'),
(10, 1, 'interna rezervacija', '2022-12-23 03:10:39', NULL, NULL, 0, '[1]', '[14]', 'canceled', '2022-12-23 18:00:00', '2022-12-23 20:00:00', 2, '2022-12-25 13:13:53', '2022-12-23 15:02:26', '2022-12-25 12:13:53'),
(11, 1, 'interna rezervacija', '2022-12-23 03:11:34', NULL, NULL, 1, '[1]', '[15]', 'successfully_paid', '2022-12-24 16:00:00', '2022-12-24 20:00:00', NULL, NULL, '2022-12-23 15:11:26', '2022-12-25 12:14:11'),
(12, 1, 'interna rezervacija', '2022-12-23 03:12:00', NULL, NULL, 0, '[2]', '[16]', 'successfully_paid', '2022-12-24 12:00:00', '2022-12-24 17:00:00', NULL, NULL, '2022-12-23 15:11:53', '2022-12-23 15:12:00'),
(14, 1, 'r8r79qy0', '2022-12-23 04:44:58', NULL, NULL, 1, '[2]', '[18]', 'canceled', '2022-12-23 17:00:00', '2022-12-23 21:00:00', 2, '2022-12-25 17:51:47', '2022-12-23 15:14:15', '2022-12-25 16:51:47'),
(15, 1, '9rt92t5r', '2022-12-23 05:08:42', NULL, NULL, 0, '[3]', '[19]', 'successfully_paid', '2022-12-24 13:00:00', '2022-12-24 18:00:00', NULL, NULL, '2022-12-23 16:58:55', '2022-12-23 17:08:42'),
(16, 1, 'bnypkc73', '2022-12-23 05:09:36', NULL, NULL, 0, '[3]', '[20]', 'successfully_paid', '2022-12-25 08:00:00', '2022-12-25 12:00:00', NULL, NULL, '2022-12-23 17:09:18', '2022-12-23 17:09:36'),
(17, 1, 'dkydt37e', '2022-12-23 05:11:38', '4g287qjg', '2022-12-25 04:52:57', 1, '[3]', '[21]', 'completed', '2022-12-24 07:00:00', '2022-12-24 11:00:00', NULL, NULL, '2022-12-23 17:11:07', '2022-12-25 16:52:57'),
(18, 1, '9rsds40s', '2022-12-23 05:17:17', '8eh1tj19', '2022-12-25 04:53:22', 1, '[2]', '[22]', 'completed', '2022-12-25 10:00:00', '2022-12-25 16:00:00', NULL, NULL, '2022-12-23 17:16:51', '2022-12-25 16:53:22'),
(21, 7, 'interna rezervacija', '2022-12-26 04:07:51', NULL, NULL, 0, '[1]', '[25]', 'pending', '2022-12-25 10:00:00', '2022-12-25 12:00:00', NULL, NULL, '2022-12-24 12:33:40', '2022-12-26 16:07:51'),
(23, 7, 'mj4c96m5', '2022-12-24 01:36:57', 'mj4c96m5', '2022-12-24 04:06:22', 0, '[2]', '[32]', 'canceled', '2023-01-05 11:00:00', '2023-01-05 16:00:00', 7, '2022-12-24 17:06:16', '2022-12-24 12:56:27', '2022-12-24 16:06:22'),
(25, 7, 'interna rezervacija', '2022-12-26 04:19:18', NULL, NULL, 0, '[1]', '[36]', 'canceled', '2022-12-26 20:00:00', '2022-12-26 19:00:00', 7, '2022-12-26 17:28:17', '2022-12-26 16:19:18', '2022-12-26 16:28:17');

-- --------------------------------------------------------

--
-- Struktura tabele `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `reservations`
--

INSERT INTO `reservations` (`id`, `product_id`, `date_from`, `date_to`, `created_at`, `updated_at`) VALUES
(6, 2, '2022-12-21 18:00:00', '2022-12-21 19:00:00', NULL, NULL),
(10, 2, '2022-12-18 10:00:00', '2022-12-18 17:00:00', '2022-12-17 17:45:17', '2022-12-17 17:45:17'),
(11, 3, '2022-12-18 10:00:00', '2022-12-18 15:00:00', '2022-12-17 18:12:38', '2022-12-17 18:12:38'),
(14, 1, '2022-12-23 18:00:00', '2022-12-23 20:00:00', '2022-12-23 15:02:26', '2022-12-23 15:02:26'),
(15, 1, '2022-12-24 16:00:00', '2022-12-24 20:00:00', '2022-12-23 15:11:26', '2022-12-23 15:11:26'),
(16, 2, '2022-12-24 12:00:00', '2022-12-24 17:00:00', '2022-12-23 15:11:53', '2022-12-23 15:11:53'),
(18, 2, '2022-12-23 17:00:00', '2022-12-23 21:00:00', '2022-12-23 15:14:15', '2022-12-23 15:14:15'),
(19, 3, '2022-12-24 13:00:00', '2022-12-24 18:00:00', '2022-12-23 16:58:55', '2022-12-23 16:58:55'),
(20, 3, '2022-12-25 08:00:00', '2022-12-25 12:00:00', '2022-12-23 17:09:18', '2022-12-23 17:09:18'),
(21, 3, '2022-12-24 07:00:00', '2022-12-24 11:00:00', '2022-12-23 17:11:07', '2022-12-23 17:11:07'),
(22, 2, '2022-12-25 10:00:00', '2022-12-25 16:00:00', '2022-12-23 17:16:51', '2022-12-23 17:16:51'),
(25, 1, '2022-12-25 10:00:00', '2022-12-25 12:00:00', '2022-12-24 12:33:40', '2022-12-24 12:33:40'),
(32, 2, '2023-01-05 11:00:00', '2023-01-05 16:00:00', '2022-12-24 13:33:48', '2022-12-24 13:33:48'),
(36, 1, '2022-12-26 19:00:00', '2022-12-26 20:00:00', '2022-12-26 16:19:18', '2022-12-26 16:19:18');

-- --------------------------------------------------------

--
-- Struktura tabele `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` enum('agent','admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_logged_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `address_id` bigint(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Odloži podatke za tabelo `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `user_role`, `phone_number`, `last_logged_in`, `status`, `address_id`, `email_verified_at`, `password`, `api_token`, `deleted`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 'admin@gmail.com', 'admin', '041123123', '2022-12-25 17:23:09', 1, 1, NULL, '$2y$10$sqA1ycmpcsTo4QssryTfKuI2FvulhLQIB/M7HvmC0E7HOq7nxC25e', NULL, 0, NULL, '2022-12-15 14:29:36', '2022-12-15 14:30:21'),
(2, 'Janez', 'Novak', 'janez@gmail.com', 'agent', '041123123', '2022-12-15 14:51:49', 1, 1, NULL, '$2y$10$nqGhoxXY/4HF52GysQyoR.Kq8rBJ8Y2qaShXrUVxxmfqmDgJe0j8a', NULL, 0, NULL, '2022-12-15 14:47:35', '2022-12-15 14:47:35'),
(3, 'Janez', 'Novak', 'janez1@gmail.com', 'customer', '041123123', '2022-12-17 18:11:34', 1, 2, NULL, '$2y$10$WIFCtrRo/FAOs2Mewl5iaeleXxag/FVvTUFMgZn.P2OeX7bUmOLri', NULL, 0, NULL, '2022-12-16 17:23:14', '2022-12-16 17:23:14'),
(4, 'Janez', 'Novak', 'user@gmail.com', 'agent', '031123123', '2022-12-24 16:30:27', 1, 1, NULL, '$2y$10$tNMiVN2e1uYMtgT7PZ4Cu.8ENXf5kK7w6HoBbp3R4KiokUUiIbx7O', 'JFY5B3THqZM93ESTStHmhShtTwvPKsWnwapyaHsXPolxN653uvfYvYVJzqsv', 0, NULL, '2022-12-21 16:06:41', '2022-12-21 16:06:41'),
(5, 'Janez', 'Novak', 'user1@gmail.com', 'customer', '031123123', '2022-12-21 16:18:31', 1, 1, NULL, '$2y$10$qoCHEEKyCMHFmTrYA9FhSeLC2eWeuSU9KYlbKhkpklsEUYNtQQtnm', 'WFVPhlqSfjrJA3Sg8NvpL1brvQmTf8csPvCr6fCKaPzvg7vN74rwEOAnjq6a', 0, NULL, '2022-12-21 16:18:31', '2022-12-21 16:18:31'),
(6, 'Janez', 'Novak', 'user2@gmail.com', 'customer', '031123123', '2022-12-21 16:19:00', 1, 1, NULL, '$2y$10$.p3iR1ttKAKV9.y.PGNQqu4GhXU2gllQ0v8ecJIOmbE0/6r.AyQrS', 'QZ67CC6YqayW2Ctta1fTOzudo9p9IeGtospybazD7htz7bY55cE7NwLhUIZh', 0, NULL, '2022-12-21 16:19:00', '2022-12-21 16:19:00'),
(7, 'ApiTestIme', 'ApiTestPriimek', 'api@gmail.com', 'customer', '031123123', '2022-12-24 14:44:46', 1, 6, NULL, '$2y$10$qm1vl2HrkiHLdHFlADB8TO7N3WoKVLBh0oO6yEGpujLZbKzAUlO.u', 'DtqUeC9W92w59B9jslBxvA6h88hBVPWsA2eJSX7QIX5z59NptfLMd3XSrM5q', 0, NULL, '2022-12-21 16:50:08', '2022-12-24 14:44:46'),
(8, 'test-change', 'test', 'tes3122t@gmail.com', 'customer', '0123456789', '2022-12-26 13:07:33', 1, 7, NULL, '$2y$10$aPKyLcbdTlBJsP872VcvEe1MSKQZaevSnZ197O3b4xBeRHbAsSIga', 'PMHYVAKZeFAD8O1nWrMFM9WwVLnoHuk9i3Vslgw5NuuNy9T9K8E1qaPcNsTF', 0, NULL, '2022-12-25 18:57:26', '2022-12-26 13:07:33');

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indeksi tabele `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksi tabele `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksi tabele `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeksi tabele `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeksi tabele `rents`
--
ALTER TABLE `rents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rents_customer_id_foreign` (`customer_id`);

--
-- Indeksi tabele `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksi tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT tabele `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT tabele `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT tabele `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT tabele `rents`
--
ALTER TABLE `rents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT tabele `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT tabele `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `rents`
--
ALTER TABLE `rents`
  ADD CONSTRAINT `rents_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
