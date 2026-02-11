-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-02-2026 a las 13:52:12
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `turnos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint UNSIGNED NOT NULL,
  `numero` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `numero`, `nombre`, `activa`, `created_at`, `updated_at`) VALUES
(1, 1, 'Caja 1 - Atención General', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(2, 2, 'Caja 2 - Pagos e Impuestos', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(3, 3, 'Caja 3 - Licencias y Permisos', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(4, 4, 'Caja 4 - Registros Civiles', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(5, 5, 'Caja 5 - Información', 0, '2026-02-09 21:15:09', '2026-02-09 21:15:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
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
-- Estructura de tabla para la tabla `jobs`
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
-- Estructura de tabla para la tabla `job_batches`
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
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_00_999999_create_cajas_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_02_09_152554_create_tipos_tramite_table', 1),
(6, '2026_02_09_152600_create_turnos_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
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
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('f0fERIjHL9HSJhSbiLkTqRQEpFBxqcxSKVpvWSbb', NULL, '127.0.0.1', 'curl/8.17.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEZnaWNodU1FWHh3WWZVcHQ5dVFZWWpRMVJVd2pvVnRiUUpOWVJWTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYW50YWxsYS1wdWJsaWNhIjtzOjU6InJvdXRlIjtzOjE2OiJwYW50YWxsYS5wdWJsaWNhIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770667514),
('IJxGi2ObOJgadUACPKJwo7CfF3I6bq85KBu640zR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYzZEczRWemFLME1vUHFaUWk1QkZhR3RRbTNtMUZkbktacnpuWVdxUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi91c3VhcmlvcyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4udXN1YXJpb3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1770667805),
('ipz1bZpISEAjUNESP5EIGADSmsy6scjQn4aoJDU1', NULL, '127.0.0.1', 'curl/8.17.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTViWGNOYUFRZ2pmblUyQm04bjhOcnRmSmduazNmcmx6TEwydWFrWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770674470),
('KmfW1MT4npB0mE7uV9bhQSk9Y0JtvN6kc4uSoLOW', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOUhKWktGaEU5VDc0cUdvTlI3aEpLUVRqM09vYnV1WjQ0RVVSSnk2VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYWplcm8iO3M6NToicm91dGUiO3M6MTI6ImNhamVyby5wYW5lbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1770668113),
('mI04g2x27PpQNwFGWUdNdymL238x1mnuVQOn4TYu', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTE5HdEJIblNQRmcyMnZSdnBmSUJlU1dKbWZ0T2FTbXlpTVo1TUQySiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvdHVybm9zLWFjdHVhbGl6YWRvcyI7czo1OiJyb3V0ZSI7czoxOToicGFudGFsbGEuYWN0dWFsaXphciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1770668275),
('oHLx5E0bNKvXJuBbeo54XLp8ucgZNa2JHrGEjp58', NULL, '127.0.0.1', 'curl/8.17.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0NTQThUdElsQzZ3c0R3N2c0bG1GTFUzS2tOQzdqU3N5ekNDMTlRWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvdHVybm9zLWFjdHVhbGl6YWRvcyI7czo1OiJyb3V0ZSI7czoxOToicGFudGFsbGEuYWN0dWFsaXphciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770666014),
('px1k4zG5kIO9qIlpNlsTNRNT2MAm6pGkSDCVqMvJ', NULL, '127.0.0.1', 'curl/8.17.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWk0Mmc5RnRZQ2pHcHBFdjR6OHczUVVtckgxQktRd2J4T0RsMzVYNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvdHVybm9zLWFjdHVhbGl6YWRvcyI7czo1OiJyb3V0ZSI7czoxOToicGFudGFsbGEuYWN0dWFsaXphciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770667503),
('sIXRABg9hcHpo6ttGuW7y2Dpoj5qtCYJTrh82hyL', NULL, '127.0.0.1', 'curl/8.17.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjlxZnNFV25aSWVoWG1xd0pMWGxuYjYxeTNPem5SN1AwTzhPb0M0TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvdHVybm9zLWFjdHVhbGl6YWRvcyI7czo1OiJyb3V0ZSI7czoxOToicGFudGFsbGEuYWN0dWFsaXphciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770667571),
('ypi3ElUEnExyF3dFz2052NxP8b3sMVBpRCclQRWU', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTHBqakQwTFZoZmcxUGxJcnJzTkloOTBVMTBITndZOHFzRnpmTmZUSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYWplcm8iO3M6NToicm91dGUiO3M6MTI6ImNhamVyby5wYW5lbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1770674917);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_tramite`
--

CREATE TABLE `tipos_tramite` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_tramite`
--

INSERT INTO `tipos_tramite` (`id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Pago de Impuesto Predial', 'Pago anual del impuesto predial unificado', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(2, 'Certificado de Paz y Salvo', 'Solicitud de certificado de paz y salvo de impuestos', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(3, 'Licencia de Construcción', 'Solicitud y trámite de licencia de construcción', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(4, 'Registro Civil', 'Expedición de registros civiles de nacimiento, matrimonio y defunción', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(5, 'Certificado de Estratificación', 'Expedición de certificado de estrato socioeconómico', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(6, 'Información General', 'Consultas e información general sobre trámites', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(7, 'Quejas y Reclamos', 'Atención de quejas, reclamos y sugerencias', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(8, 'Pago de Servicios Públicos', 'Pago de servicios públicos municipales', 1, '2026-02-09 21:15:09', '2026-02-09 21:15:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('CC','TI','CE','PAS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_tramite_id` bigint UNSIGNED NOT NULL,
  `estado` enum('pendiente','llamado','en_atencion','atendido','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `caja_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `hora_solicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hora_llamado` timestamp NULL DEFAULT NULL,
  `hora_inicio_atencion` timestamp NULL DEFAULT NULL,
  `hora_fin_atencion` timestamp NULL DEFAULT NULL,
  `tiempo_atencion` int DEFAULT NULL COMMENT 'Tiempo en segundos',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `codigo`, `tipo_documento`, `numero_documento`, `nombre_completo`, `tipo_tramite_id`, `estado`, `caja_id`, `user_id`, `hora_solicitud`, `hora_llamado`, `hora_inicio_atencion`, `hora_fin_atencion`, `tiempo_atencion`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'A001', 'CC', '1093765556', 'leonardo peñaranda', 5, 'pendiente', NULL, NULL, '2026-02-10 00:37:21', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:37:21', '2026-02-10 00:37:21'),
(2, 'A002', 'CC', '345678654', 'mario', 5, 'pendiente', NULL, NULL, '2026-02-10 00:38:45', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:38:45', '2026-02-10 00:38:45'),
(3, 'A003', 'CC', '23546578', 'maria morales', 6, 'pendiente', NULL, NULL, '2026-02-10 00:43:44', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:43:44', '2026-02-10 00:43:44'),
(4, 'A004', 'CC', '11432156', 'Carlos Martínez Sánchez', 8, 'pendiente', NULL, NULL, '2026-02-10 00:48:07', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:48:07', '2026-02-10 00:48:07'),
(5, 'A005', 'CC', '53171680', 'Luis Ramírez Castro', 2, 'pendiente', NULL, NULL, '2026-02-10 00:48:07', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:48:07', '2026-02-10 00:48:07'),
(6, 'A006', 'CC', '93564081', 'Luis Ramírez Castro', 5, 'pendiente', NULL, NULL, '2026-02-10 00:48:07', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:48:07', '2026-02-10 00:48:07'),
(7, 'A007', 'CC', '20135364', 'Ana González Torres', 5, 'pendiente', NULL, NULL, '2026-02-10 00:48:07', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:48:07', '2026-02-10 00:48:07'),
(8, 'A008', 'CC', '52535996', 'María López Rodríguez', 3, 'pendiente', NULL, NULL, '2026-02-10 00:48:07', NULL, NULL, NULL, NULL, NULL, '2026-02-10 00:48:07', '2026-02-10 00:48:07'),
(9, 'A009', 'CC', '31284700', 'Luis Ramírez Castro', 1, 'en_atencion', 1, 2, '2026-02-10 01:00:53', '2026-02-09 20:04:35', NULL, NULL, NULL, NULL, '2026-02-10 01:00:53', '2026-02-10 01:00:53'),
(10, 'A010', 'CC', '55968356', 'Ana González Torres', 5, 'llamado', 2, 3, '2026-02-10 01:00:53', '2026-02-09 20:05:27', NULL, NULL, NULL, NULL, '2026-02-10 01:00:53', '2026-02-10 01:00:53'),
(11, 'A011', 'CC', '49419381', 'Luis Ramírez Castro', 5, 'pendiente', NULL, NULL, '2026-02-10 01:00:53', NULL, NULL, NULL, NULL, NULL, '2026-02-10 01:00:53', '2026-02-10 01:00:53'),
(12, 'A012', 'CC', '876543', 'mario', 2, 'pendiente', NULL, NULL, '2026-02-10 01:09:08', NULL, NULL, NULL, NULL, NULL, '2026-02-10 01:09:08', '2026-02-10 01:09:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','cajero') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cajero',
  `caja_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `rol`, `caja_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Sistema', 'admin@cucuta.gov.co', '2026-02-09 21:15:09', '$2y$12$FxG4om14EaGHruOcSkTWW.AM9eon0QL2H5qbySyi8aAtntOe0Yudi', 'admin', NULL, NULL, '2026-02-09 21:15:09', '2026-02-09 21:15:09'),
(2, 'María González', 'maria.gonzalez@cucuta.gov.co', '2026-02-09 21:15:11', '$2y$12$1hEQFsVL4ZIPN3aWST2FcuLLxTqC1EgqZHPXwp7wE2U52PCxBt5T.', 'cajero', 1, NULL, '2026-02-09 21:15:11', '2026-02-09 21:15:11'),
(3, 'Carlos Ramírez', 'carlos.ramirez@cucuta.gov.co', '2026-02-09 21:15:11', '$2y$12$krFKfW/JSQsRkrTiIA/.fO3SiKjRaYeHb5gcm2MsgFkikCpjWAMbO', 'cajero', 2, NULL, '2026-02-09 21:15:11', '2026-02-09 21:15:11'),
(4, 'Ana Martínez', 'ana.martinez@cucuta.gov.co', '2026-02-09 21:15:11', '$2y$12$YG/7VcMol8K/UxDi7WUQP.wGMpiZFEF/smUdUrKObtp64fn7ANrwK', 'cajero', 3, NULL, '2026-02-09 21:15:11', '2026-02-09 21:15:11'),
(5, 'Luis Pérez', 'luis.perez@cucuta.gov.co', '2026-02-09 21:15:11', '$2y$12$q7.3vqJCaFs9cJSPw1NZ0O4qH9NDqmvA5KqE2t59hhe.MDkBiszIy', 'cajero', 4, NULL, '2026-02-09 21:15:11', '2026-02-09 21:15:11');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tipos_tramite`
--
ALTER TABLE `tipos_tramite`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `turnos_codigo_unique` (`codigo`),
  ADD KEY `turnos_tipo_tramite_id_foreign` (`tipo_tramite_id`),
  ADD KEY `turnos_caja_id_foreign` (`caja_id`),
  ADD KEY `turnos_user_id_foreign` (`user_id`),
  ADD KEY `turnos_estado_created_at_index` (`estado`,`created_at`),
  ADD KEY `turnos_numero_documento_index` (`numero_documento`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_caja_id_foreign` (`caja_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipos_tramite`
--
ALTER TABLE `tipos_tramite`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `turnos_tipo_tramite_id_foreign` FOREIGN KEY (`tipo_tramite_id`) REFERENCES `tipos_tramite` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
