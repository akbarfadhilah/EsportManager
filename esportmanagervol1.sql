-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Nov 2026 pada 10.35
-- Versi server: 8.0.30
-- Versi PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esportmanagervol1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `esport_teams`
--

CREATE TABLE `esport_teams` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_tim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_tim` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_esport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_kota_gh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_kekayaan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `winrate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `penghargaan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `pemilik_id` bigint UNSIGNED DEFAULT NULL,
  `ceo_id` bigint UNSIGNED DEFAULT NULL,
  `pelatih_utama_id` bigint UNSIGNED DEFAULT NULL,
  `pelatih_kedua_id` bigint UNSIGNED DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `analis_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `heroes`
--

CREATE TABLE `heroes` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_hero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_hero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `targeting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gaya_permainan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json DEFAULT NULL,
  `jenis_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pola_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_pendukung` json DEFAULT NULL,
  `role_tidak_direkomendasikan` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `matches`
--

CREATE TABLE `matches` (
  `id` bigint UNSIGNED NOT NULL,
  `tournament_id` bigint UNSIGNED NOT NULL,
  `match_number` int NOT NULL,
  `round_index` int UNSIGNED NOT NULL DEFAULT '1',
  `bo_format` enum('BO3','BO5','BO7') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_grand_final` tinyint(1) NOT NULL DEFAULT '0',
  `winner_team_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `match_participants`
--

CREATE TABLE `match_participants` (
  `id` bigint UNSIGNED NOT NULL,
  `match_id` bigint UNSIGNED NOT NULL,
  `esport_team_id` bigint UNSIGNED NOT NULL,
  `rounds_won` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `previous_match_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_11_094822_create_tournaments_table', 1),
(5, '2026_02_11_094825_create_esport_teams_table', 1),
(6, '2026_02_11_094834_create_players_table', 1),
(7, '2026_02_11_094837_create_tournament_slots_table', 1),
(8, '2026_02_11_094841_create_player_roles_table', 1),
(9, '2026_02_11_112235_create_tournament_awards_table', 1),
(10, '2026_02_11_112237_create_matches_table', 1),
(11, '2026_02_11_112238_create_match_participants_table', 1),
(12, '2026_02_11_112240_create_rounds_table', 1),
(13, '2026_02_11_112241_create_team_statistics_table', 1),
(14, '2026_02_11_112243_modify_tournaments_remove_awards', 1),
(15, '2026_02_11_112244_modify_tournament_slots_remove_prize', 1),
(16, '2026_02_11_120823_add_photo_to_players_table', 1),
(17, '2026_02_12_072827_create_tournament_prizes_table_and_add_status_to_tournaments', 1),
(18, '2026_02_19_041351_add_nationality_to_players_table', 1),
(19, '2026_02_19_044810_add_nationality_column_to_players_table', 1),
(20, '2026_02_19_053217_add_country_to_esport_teams_table', 1),
(21, '2026_02_19_140000_add_winrate_to_esport_teams_table', 1),
(22, '2026_02_20_075528_add_is_grand_final_to_matches_table', 1),
(23, '2026_09_13_102623_create_regions_table', 1),
(24, '2026_09_13_102743_modify_players_table', 1),
(25, '2026_09_13_102924_modify_esport_teams_table', 1),
(26, '2026_09_13_110000_create_heroes_table', 1),
(27, '2026_09_13_110001_add_format_and_bo_to_tournaments_table', 1),
(28, '2026_09_13_110002_add_winrate_to_players_table', 1),
(29, '2026_09_13_110003_add_round_index_to_matches_table', 1),
(30, '2026_09_13_110004_add_mvp_to_rounds_table', 1),
(31, '2026_09_13_110005_add_previous_match_to_match_participants_table', 1),
(32, '2026_09_13_110006_create_round_lineups_table', 1),
(33, '2026_09_27_100000_add_none_to_players_jabatan_table', 1),
(34, '2026_11_08_000000_update_hero_identity_fields', 1),
(35, '2026_11_19_000000_add_hero_attack_and_role_categories', 1),
(36, '2026_11_19_000001_add_personnel_category_and_athlete_profile', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `players`
--

CREATE TABLE `players` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_asli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname_game` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_pemain` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `esport_team_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan` enum('None','Player','Owner','CEO','Head Coach','Assistant Coach','Manager','Analyst','Ketua Persatuan Game','Pelatih Timnas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'None',
  `winrate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `kategori_personel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Common',
  `role_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_cadangan` json DEFAULT NULL,
  `role_kurang_kompeten` json DEFAULT NULL,
  `gaya_permainan` json DEFAULT NULL,
  `targeting_urut` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_negara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ketua_persatuan_game_id` bigint UNSIGNED DEFAULT NULL,
  `pelatih_timnas_id` bigint UNSIGNED DEFAULT NULL,
  `foto_negara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `regions`
--

INSERT INTO `regions` (`id`, `nama_negara`, `ketua_persatuan_game_id`, `pelatih_timnas_id`, `foto_negara`, `created_at`, `updated_at`) VALUES
(1, 'Brunei Darussalam', NULL, NULL, 'Bendera/Flag_of_Brunei-256x128.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(2, 'Mesir', NULL, NULL, 'Bendera/Flag_of_Egypt-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(3, 'Inggris', NULL, NULL, 'Bendera/Flag_of_England-256x154.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(4, 'Indonesia', NULL, NULL, 'Bendera/Flag_of_Indonesia-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(5, 'Irlandia', NULL, NULL, 'Bendera/Flag_of_Ireland-256x128.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(6, 'Jepang', NULL, NULL, 'Bendera/Flag_of_Japan-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(7, 'Malaysia', NULL, NULL, 'Bendera/Flag_of_Malaysia-256x128.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(8, 'Meksiko', NULL, NULL, 'Bendera/Flag_of_Mexico-256x146.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(9, 'Tiongkok', NULL, NULL, 'Bendera/Flag_of_Peoples_Republic_of_China-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(10, 'Filipina', NULL, NULL, 'Bendera/Flag_of_Philippines-256x128.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(11, 'Qatar', NULL, NULL, 'Bendera/Flag_of_Qatar-256x100.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(12, 'Rusia', NULL, NULL, 'Bendera/Flag_of_Russia-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(13, 'Arab Saudi', NULL, NULL, 'Bendera/Flag_of_Saudi_Arabia-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(14, 'Singapura', NULL, NULL, 'Bendera/Flag_of_Singapore-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(15, 'Thailand', NULL, NULL, 'Bendera/Flag_of_Thailand-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(16, 'Turki', NULL, NULL, 'Bendera/Flag_of_Turkey-256x171.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(17, 'Uni Emirat Arab', NULL, NULL, 'Bendera/Flag_of_United_Arab_Emirates-256x128.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36'),
(18, 'Amerika Serikat', NULL, NULL, 'Bendera/Flag_of_United_States-256x135.png', '2026-11-19 03:27:36', '2026-11-19 03:27:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rounds`
--

CREATE TABLE `rounds` (
  `id` bigint UNSIGNED NOT NULL,
  `match_id` bigint UNSIGNED NOT NULL,
  `round_number` int NOT NULL,
  `winner_team_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mvp_player_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `round_lineups`
--

CREATE TABLE `round_lineups` (
  `id` bigint UNSIGNED NOT NULL,
  `round_id` bigint UNSIGNED NOT NULL,
  `player_id` bigint UNSIGNED NOT NULL,
  `hero_id` bigint UNSIGNED NOT NULL,
  `esport_team_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('r0f5uhJr8KTv9FcRfLIl2PbXKmweMqCjk9SmRaDL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9818', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDM1NnpkWkt1Z2hKb1hoTThGczhoVkZEcDRyTmFGd1cydFR2RWxEbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpb25zIjtzOjU6InJvdXRlIjtzOjEzOiJyZWdpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1795084248);

-- --------------------------------------------------------

--
-- Struktur dari tabel `team_statistics`
--

CREATE TABLE `team_statistics` (
  `id` bigint UNSIGNED NOT NULL,
  `tournament_id` bigint UNSIGNED NOT NULL,
  `esport_team_id` bigint UNSIGNED NOT NULL,
  `matches_played` int NOT NULL DEFAULT '0',
  `matches_won` int NOT NULL DEFAULT '0',
  `rounds_played` int NOT NULL DEFAULT '0',
  `rounds_won` int NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `win_rate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tournaments`
--

CREATE TABLE `tournaments` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_tournament` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_hadiah` decimal(15,2) NOT NULL,
  `deskripsi_tournament` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_tournament` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_slot` int NOT NULL,
  `negara_penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_final` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tier` enum('S+ Tier','S Tier','A Tier','B Tier','C Tier','National Invitation','International Invitation') COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('standard','knockout') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `bo_format` enum('BO3','BO5','BO7') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BO3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tournament_awards`
--

CREATE TABLE `tournament_awards` (
  `id` bigint UNSIGNED NOT NULL,
  `tournament_id` bigint UNSIGNED NOT NULL,
  `award_name` enum('MVP Finals','Top Player','Most Killed Player') COLLATE utf8mb4_unicode_ci NOT NULL,
  `prize_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tournament_prizes`
--

CREATE TABLE `tournament_prizes` (
  `id` bigint UNSIGNED NOT NULL,
  `tournament_id` bigint UNSIGNED NOT NULL,
  `rank` int NOT NULL,
  `prize_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tournament_slots`
--

CREATE TABLE `tournament_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `tournament_id` bigint UNSIGNED NOT NULL,
  `slot_number` int NOT NULL,
  `esport_team_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `esport_teams`
--
ALTER TABLE `esport_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `esport_teams_region_id_foreign` (`region_id`),
  ADD KEY `esport_teams_pemilik_id_foreign` (`pemilik_id`),
  ADD KEY `esport_teams_ceo_id_foreign` (`ceo_id`),
  ADD KEY `esport_teams_pelatih_utama_id_foreign` (`pelatih_utama_id`),
  ADD KEY `esport_teams_pelatih_kedua_id_foreign` (`pelatih_kedua_id`),
  ADD KEY `esport_teams_manager_id_foreign` (`manager_id`),
  ADD KEY `esport_teams_analis_id_foreign` (`analis_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `heroes`
--
ALTER TABLE `heroes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matches_winner_team_id_foreign` (`winner_team_id`),
  ADD KEY `matches_tournament_id_index` (`tournament_id`),
  ADD KEY `matches_status_index` (`status`);

--
-- Indeks untuk tabel `match_participants`
--
ALTER TABLE `match_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `match_participants_match_id_index` (`match_id`),
  ADD KEY `match_participants_esport_team_id_index` (`esport_team_id`),
  ADD KEY `match_participants_previous_match_id_foreign` (`previous_match_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `players_esport_team_id_foreign` (`esport_team_id`),
  ADD KEY `players_region_id_foreign` (`region_id`);

--
-- Indeks untuk tabel `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regions_ketua_persatuan_game_id_foreign` (`ketua_persatuan_game_id`),
  ADD KEY `regions_pelatih_timnas_id_foreign` (`pelatih_timnas_id`);

--
-- Indeks untuk tabel `rounds`
--
ALTER TABLE `rounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rounds_winner_team_id_foreign` (`winner_team_id`),
  ADD KEY `rounds_match_id_index` (`match_id`),
  ADD KEY `rounds_mvp_player_id_foreign` (`mvp_player_id`);

--
-- Indeks untuk tabel `round_lineups`
--
ALTER TABLE `round_lineups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `round_lineups_round_id_player_id_unique` (`round_id`,`player_id`),
  ADD UNIQUE KEY `round_lineups_round_id_hero_id_unique` (`round_id`,`hero_id`),
  ADD KEY `round_lineups_player_id_foreign` (`player_id`),
  ADD KEY `round_lineups_hero_id_foreign` (`hero_id`),
  ADD KEY `round_lineups_esport_team_id_foreign` (`esport_team_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `team_statistics`
--
ALTER TABLE `team_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_statistics_tournament_id_esport_team_id_unique` (`tournament_id`,`esport_team_id`),
  ADD KEY `team_statistics_tournament_id_index` (`tournament_id`),
  ADD KEY `team_statistics_esport_team_id_index` (`esport_team_id`);

--
-- Indeks untuk tabel `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tournament_awards`
--
ALTER TABLE `tournament_awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_awards_tournament_id_index` (`tournament_id`);

--
-- Indeks untuk tabel `tournament_prizes`
--
ALTER TABLE `tournament_prizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_prizes_tournament_id_foreign` (`tournament_id`);

--
-- Indeks untuk tabel `tournament_slots`
--
ALTER TABLE `tournament_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_slots_tournament_id_foreign` (`tournament_id`),
  ADD KEY `tournament_slots_esport_team_id_foreign` (`esport_team_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `esport_teams`
--
ALTER TABLE `esport_teams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `heroes`
--
ALTER TABLE `heroes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `match_participants`
--
ALTER TABLE `match_participants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `players`
--
ALTER TABLE `players`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `rounds`
--
ALTER TABLE `rounds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `round_lineups`
--
ALTER TABLE `round_lineups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `team_statistics`
--
ALTER TABLE `team_statistics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tournament_awards`
--
ALTER TABLE `tournament_awards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tournament_prizes`
--
ALTER TABLE `tournament_prizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tournament_slots`
--
ALTER TABLE `tournament_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `esport_teams`
--
ALTER TABLE `esport_teams`
  ADD CONSTRAINT `esport_teams_analis_id_foreign` FOREIGN KEY (`analis_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_ceo_id_foreign` FOREIGN KEY (`ceo_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_pelatih_kedua_id_foreign` FOREIGN KEY (`pelatih_kedua_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_pelatih_utama_id_foreign` FOREIGN KEY (`pelatih_utama_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_pemilik_id_foreign` FOREIGN KEY (`pemilik_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `esport_teams_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_winner_team_id_foreign` FOREIGN KEY (`winner_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `match_participants`
--
ALTER TABLE `match_participants`
  ADD CONSTRAINT `match_participants_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `match_participants_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `match_participants_previous_match_id_foreign` FOREIGN KEY (`previous_match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `players_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `regions`
--
ALTER TABLE `regions`
  ADD CONSTRAINT `regions_ketua_persatuan_game_id_foreign` FOREIGN KEY (`ketua_persatuan_game_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `regions_pelatih_timnas_id_foreign` FOREIGN KEY (`pelatih_timnas_id`) REFERENCES `players` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rounds`
--
ALTER TABLE `rounds`
  ADD CONSTRAINT `rounds_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rounds_mvp_player_id_foreign` FOREIGN KEY (`mvp_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rounds_winner_team_id_foreign` FOREIGN KEY (`winner_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `round_lineups`
--
ALTER TABLE `round_lineups`
  ADD CONSTRAINT `round_lineups_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `round_lineups_hero_id_foreign` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `round_lineups_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `round_lineups_round_id_foreign` FOREIGN KEY (`round_id`) REFERENCES `rounds` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `team_statistics`
--
ALTER TABLE `team_statistics`
  ADD CONSTRAINT `team_statistics_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_statistics_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tournament_awards`
--
ALTER TABLE `tournament_awards`
  ADD CONSTRAINT `tournament_awards_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tournament_prizes`
--
ALTER TABLE `tournament_prizes`
  ADD CONSTRAINT `tournament_prizes_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tournament_slots`
--
ALTER TABLE `tournament_slots`
  ADD CONSTRAINT `tournament_slots_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tournament_slots_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
