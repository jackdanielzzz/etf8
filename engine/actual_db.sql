-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 02 2025 г., 10:59
-- Версия сервера: 8.0.42-0ubuntu0.24.04.2
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `etfrix_com_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `accrual_logs`
--

CREATE TABLE `accrual_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `run_id` bigint UNSIGNED NOT NULL,
  `run_at` datetime NOT NULL,
  `today_date` date NOT NULL,
  `user_deal_id` bigint UNSIGNED NOT NULL,
  `accrual_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `daily_rate` decimal(12,6) NOT NULL,
  `day_index` int UNSIGNED NOT NULL,
  `principal` decimal(18,2) NOT NULL,
  `was_closed` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `asset_quotes`
--

CREATE TABLE `asset_quotes` (
  `symbol` varchar(32) NOT NULL,
  `price` decimal(18,5) NOT NULL,
  `percent_change` decimal(8,2) NOT NULL,
  `quote_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cron_runs`
--

CREATE TABLE `cron_runs` (
  `id` bigint UNSIGNED NOT NULL,
  `started_at` datetime NOT NULL,
  `finished_at` datetime DEFAULT NULL,
  `app_type` enum('prod','dev') NOT NULL,
  `today_date` date NOT NULL,
  `processed_deals` int UNSIGNED DEFAULT '0',
  `processed_quotes` int UNSIGNED DEFAULT '0',
  `status` enum('ok','error') DEFAULT 'ok',
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `deals`
--

CREATE TABLE `deals` (
  `deal_id` int NOT NULL,
  `team_id` int NOT NULL,
  `region_id` int NOT NULL,
  `need_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 – сделка требует ручного подтверждения',
  `payout_mode` enum('end','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'end',
  `deal_size` enum('Small','Medium','Large','Flash') NOT NULL,
  `product` varchar(255) NOT NULL,
  `amount_min` int NOT NULL,
  `amount_max` int NOT NULL,
  `term_days` int NOT NULL,
  `rate_without_RIX` decimal(6,4) NOT NULL,
  `rate_without_RIX_min` decimal(6,4) NOT NULL,
  `rate_without_RIX_max` decimal(6,4) NOT NULL,
  `rate_with_RIX` decimal(6,4) NOT NULL,
  `rate_with_RIX_min` decimal(6,4) NOT NULL,
  `rate_with_RIX_max` decimal(6,4) NOT NULL,
  `config_note` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `config_note_en` mediumtext NOT NULL,
  `config_note_cn` mediumtext NOT NULL,
  `config_note_ar` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `inputs`
--

CREATE TABLE `inputs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `amount_usd` decimal(18,2) NOT NULL,
  `amount_crypto` decimal(18,8) NOT NULL,
  `tx_hash` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `blocked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `nft_library`
--

CREATE TABLE `nft_library` (
  `id` int NOT NULL,
  `rarity` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `image_path` varchar(255) NOT NULL,
  `description_ru` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `outputs`
--

CREATE TABLE `outputs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount_usd` float NOT NULL,
  `wallet` text NOT NULL,
  `method` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint NOT NULL DEFAULT '0',
  `blocked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `region_id` int NOT NULL,
  `region_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roulette_items`
--

CREATE TABLE `roulette_items` (
  `id` int UNSIGNED NOT NULL,
  `token` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Обфусцированный идентификатор (base36, уникальный)',
  `item_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'empty',
  `value_amount` decimal(18,2) DEFAULT '0.00',
  `drop_chance` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Вес/шанс (0..100 или больше, если не нормализуете)',
  `drop_chance_guest` int NOT NULL DEFAULT '0',
  `single_prize` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(18,2) NOT NULL,
  `image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Имя файла в хранилище, напр. dragon.png',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int NOT NULL DEFAULT '500',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roulette_items_custom`
--

CREATE TABLE `roulette_items_custom` (
  `id` int UNSIGNED NOT NULL,
  `token` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Обфусцированный идентификатор (base36, уникальный)',
  `item_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'empty',
  `value_amount` decimal(18,2) DEFAULT '0.00',
  `drop_chance` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Вес/шанс (0..100 или больше, если не нормализуете)',
  `drop_chance_guest` int NOT NULL DEFAULT '0',
  `single_prize` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(18,2) NOT NULL,
  `image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Имя файла в хранилище, напр. dragon.png',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int NOT NULL DEFAULT '500',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roulette_items_custom2`
--

CREATE TABLE `roulette_items_custom2` (
  `id` int UNSIGNED NOT NULL,
  `token` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Обфусцированный идентификатор (base36, уникальный)',
  `item_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'empty',
  `value_amount` decimal(18,2) DEFAULT '0.00',
  `drop_chance` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Вес/шанс (0..100 или больше, если не нормализуете)',
  `drop_chance_guest` int NOT NULL DEFAULT '0',
  `single_prize` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(18,2) NOT NULL,
  `image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Имя файла в хранилище, напр. dragon.png',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int NOT NULL DEFAULT '500',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roulette_prize`
--

CREATE TABLE `roulette_prize` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `prize_token` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `spent` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tariff_levels`
--

CREATE TABLE `tariff_levels` (
  `id` tinyint UNSIGNED NOT NULL,
  `lvl` varchar(3) NOT NULL,
  `total_deposit_usd` decimal(10,2) NOT NULL,
  `income_usd` decimal(10,2) NOT NULL,
  `min_active_partners` smallint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `teams`
--

CREATE TABLE `teams` (
  `team_id` int NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `team_income_roll`
--

CREATE TABLE `team_income_roll` (
  `user_id` int UNSIGNED NOT NULL,
  `d0` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d1` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d2` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d3` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d4` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d5` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d6` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d7` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d8` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d9` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d10` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d11` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d12` decimal(18,2) NOT NULL DEFAULT '0.00',
  `d13` decimal(18,2) NOT NULL DEFAULT '0.00',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tokenization`
--

CREATE TABLE `tokenization` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `ref_id` int NOT NULL,
  `amount_usd` double NOT NULL,
  `percent` double NOT NULL,
  `total_usd` double NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `transaction_types`
--

CREATE TABLE `transaction_types` (
  `id` int NOT NULL,
  `trans_id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `uid` int NOT NULL,
  `email` varchar(300) NOT NULL,
  `team_id` int NOT NULL DEFAULT '0',
  `user_name` varchar(128) DEFAULT NULL,
  `sur_name` varchar(128) DEFAULT NULL,
  `telegram` varchar(128) NOT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `password` varchar(300) NOT NULL,
  `activation` varchar(300) NOT NULL,
  `balance` decimal(18,2) NOT NULL DEFAULT '0.00',
  `balance_team` decimal(18,2) NOT NULL DEFAULT '0.00',
  `balance_promo` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_accrued` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT 'Общий накопленный доход',
  `total_team_accrued` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_promo_accrued` decimal(18,2) NOT NULL DEFAULT '0.00',
  `roulette_coin` int NOT NULL DEFAULT '3000',
  `rating` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'L0',
  `rating_bonus` varchar(5) DEFAULT '0%',
  `referral` int NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_ip` varchar(128) NOT NULL DEFAULT '',
  `last_login_ismobile` int NOT NULL DEFAULT '-1',
  `last_login_agent` varchar(512) NOT NULL DEFAULT '',
  `email_status` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `admin` enum('user','admin','manager','custom_roulette','custom_roulette2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `status` int NOT NULL DEFAULT '0',
  `verified` enum('0','1') NOT NULL DEFAULT '0',
  `v_virtual` enum('0','1') NOT NULL DEFAULT '0',
  `v_total_partners` smallint NOT NULL DEFAULT '0',
  `v_active_partners` smallint NOT NULL DEFAULT '0',
  `v_team_depo` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_altcoins`
--

CREATE TABLE `user_altcoins` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `RIXCOIN` int NOT NULL DEFAULT '300',
  `RIX_freespin` int NOT NULL DEFAULT '0',
  `ARKF` decimal(12,2) NOT NULL DEFAULT '0.00',
  `ARKK` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BINANCE_BTCUSDT` decimal(12,10) NOT NULL DEFAULT '0.0000000000',
  `BINANCE_ETHUSDT` decimal(12,6) NOT NULL DEFAULT '0.000000',
  `BINANCE_LTCUSDT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BINANCE_SOLUSDT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BINANCE_TONUSDT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BINANCE_USDCUSDT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BINANCE_XRPUSDT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BITO` decimal(12,2) NOT NULL DEFAULT '0.00',
  `BLOK` decimal(12,2) NOT NULL DEFAULT '0.00',
  `GBTC` decimal(12,2) NOT NULL DEFAULT '0.00',
  `IBIT` decimal(12,2) NOT NULL DEFAULT '0.00',
  `IVV` decimal(12,2) NOT NULL DEFAULT '0.00',
  `QQQ` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SMH` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SPY` decimal(12,2) NOT NULL DEFAULT '0.00',
  `VOO` decimal(12,2) NOT NULL DEFAULT '0.00',
  `VTI` decimal(12,2) NOT NULL DEFAULT '0.00',
  `VUG` decimal(12,2) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_deals`
--

CREATE TABLE `user_deals` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `deal_id` int UNSIGNED NOT NULL,
  `principal` decimal(18,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payout_mode` enum('end','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'end',
  `daily_target` decimal(6,4) NOT NULL,
  `daily_min` decimal(6,4) NOT NULL,
  `daily_max` decimal(6,4) NOT NULL,
  `accrued_amount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `last_accrual_on` date DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `is_new` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 – уведомление ещё не показано, 0 – уже подтверждено',
  `is_closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 – баннер о завершении ещё не показан, 0 – показан'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_deal_accruals`
--

CREATE TABLE `user_deal_accruals` (
  `accrual_id` bigint UNSIGNED NOT NULL,
  `user_deal_id` int UNSIGNED NOT NULL,
  `accrual_date` date NOT NULL,
  `day_index` int UNSIGNED NOT NULL,
  `daily_rate` decimal(10,6) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `credited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_deal_requests`
--

CREATE TABLE `user_deal_requests` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `deal_id` int UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 – новая, 1 – подтверждено, 2 – отменено',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_login`
--

CREATE TABLE `user_login` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_ip` varchar(255) NOT NULL,
  `user_agent` varchar(512) NOT NULL,
  `ismobile` int NOT NULL,
  `hide` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user_nfts`
--

CREATE TABLE `user_nfts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nft_library_id` int NOT NULL,
  `received_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_verification`
--

CREATE TABLE `user_verification` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `file1` varchar(512) DEFAULT NULL,
  `file2` varchar(512) DEFAULT NULL,
  `file3` varchar(512) DEFAULT NULL,
  `file4` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `_wallets`
--

CREATE TABLE `_wallets` (
  `id` int NOT NULL,
  `config_name` varchar(255) NOT NULL,
  `full_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quotes_symbol` varchar(128) NOT NULL,
  `value` varchar(255) NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `accrual_logs`
--
ALTER TABLE `accrual_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_run` (`run_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_date` (`today_date`);

--
-- Индексы таблицы `asset_quotes`
--
ALTER TABLE `asset_quotes`
  ADD PRIMARY KEY (`symbol`);

--
-- Индексы таблицы `cron_runs`
--
ALTER TABLE `cron_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_today` (`today_date`);

--
-- Индексы таблицы `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`deal_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Индексы таблицы `inputs`
--
ALTER TABLE `inputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_status` (`user_id`,`status`);

--
-- Индексы таблицы `nft_library`
--
ALTER TABLE `nft_library`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `outputs`
--
ALTER TABLE `outputs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`region_id`);

--
-- Индексы таблицы `roulette_items`
--
ALTER TABLE `roulette_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_token` (`token`),
  ADD KEY `idx_active_sort` (`is_active`,`sort`),
  ADD KEY `idx_item_name` (`item_name`);

--
-- Индексы таблицы `roulette_items_custom`
--
ALTER TABLE `roulette_items_custom`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_token` (`token`),
  ADD KEY `idx_active_sort` (`is_active`,`sort`),
  ADD KEY `idx_item_name` (`item_name`);

--
-- Индексы таблицы `roulette_items_custom2`
--
ALTER TABLE `roulette_items_custom2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_token` (`token`),
  ADD KEY `idx_active_sort` (`is_active`,`sort`),
  ADD KEY `idx_item_name` (`item_name`);

--
-- Индексы таблицы `roulette_prize`
--
ALTER TABLE `roulette_prize`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Индексы таблицы `tariff_levels`
--
ALTER TABLE `tariff_levels`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Индексы таблицы `team_income_roll`
--
ALTER TABLE `team_income_roll`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `tokenization`
--
ALTER TABLE `tokenization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `ref_id` (`ref_id`);

--
-- Индексы таблицы `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `activation` (`activation`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `idx_uid` (`uid`);

--
-- Индексы таблицы `user_altcoins`
--
ALTER TABLE `user_altcoins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Индексы таблицы `user_deals`
--
ALTER TABLE `user_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `idx_status_dates` (`status`,`start_date`,`end_date`);

--
-- Индексы таблицы `user_deal_accruals`
--
ALTER TABLE `user_deal_accruals`
  ADD PRIMARY KEY (`accrual_id`),
  ADD UNIQUE KEY `uniq_deal_day` (`user_deal_id`,`day_index`),
  ADD KEY `idx_udid_date` (`user_deal_id`,`accrual_date`);

--
-- Индексы таблицы `user_deal_requests`
--
ALTER TABLE `user_deal_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_deal` (`user_id`,`deal_id`),
  ADD KEY `idx_status` (`status`);

--
-- Индексы таблицы `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `user_nfts`
--
ALTER TABLE `user_nfts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_verification`
--
ALTER TABLE `user_verification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `_wallets`
--
ALTER TABLE `_wallets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `accrual_logs`
--
ALTER TABLE `accrual_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cron_runs`
--
ALTER TABLE `cron_runs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `deals`
--
ALTER TABLE `deals`
  MODIFY `deal_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `inputs`
--
ALTER TABLE `inputs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `nft_library`
--
ALTER TABLE `nft_library`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `outputs`
--
ALTER TABLE `outputs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `region_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roulette_items`
--
ALTER TABLE `roulette_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roulette_items_custom`
--
ALTER TABLE `roulette_items_custom`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roulette_items_custom2`
--
ALTER TABLE `roulette_items_custom2`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roulette_prize`
--
ALTER TABLE `roulette_prize`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tariff_levels`
--
ALTER TABLE `tariff_levels`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tokenization`
--
ALTER TABLE `tokenization`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `transaction_types`
--
ALTER TABLE `transaction_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `uid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_altcoins`
--
ALTER TABLE `user_altcoins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_deals`
--
ALTER TABLE `user_deals`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_deal_accruals`
--
ALTER TABLE `user_deal_accruals`
  MODIFY `accrual_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_deal_requests`
--
ALTER TABLE `user_deal_requests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_nfts`
--
ALTER TABLE `user_nfts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_verification`
--
ALTER TABLE `user_verification`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `_wallets`
--
ALTER TABLE `_wallets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `deals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`),
  ADD CONSTRAINT `deals_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
