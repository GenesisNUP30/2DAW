-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-02-2026 a las 19:58:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_2eval`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `cif` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `cuenta_bancaria` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `importe_cuota` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `cif`, `nombre`, `telefono`, `correo`, `cuenta_bancaria`, `pais`, `moneda`, `importe_cuota`) VALUES
(11, '12345678Z', 'Manuel García', '111 222 333', 'manuelgarcia@gmail.com', NULL, NULL, NULL, NULL),
(12, '04150556E', 'pepe', '986 562 147', 'prueba2@gmail.com', NULL, NULL, NULL, NULL),
(13, 'X0153310S', 'laravel5', '986 562 147', 'prueba5@gmail.com', NULL, NULL, NULL, NULL),
(14, 'Q6501800D', 'yujuu', '+34 654 874 320', 'prueba2@gmail.com', NULL, NULL, NULL, NULL),
(15, '79208835D', 'No lo se', '123456789', 'adminstrador@gmail.com', NULL, NULL, NULL, NULL),
(16, 'A59244095', 'Antonio Murillo', '959 78 65 01', 'examen2@gmail.com', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_avanzada`
--

CREATE TABLE `config_avanzada` (
  `id` int(11) NOT NULL,
  `provincia_defecto` varchar(2) DEFAULT NULL,
  `poblacion_defecto` varchar(50) DEFAULT NULL,
  `items_por_pagina` int(11) DEFAULT 5,
  `tiempo_sesion` int(11) NOT NULL,
  `tema` enum('claro','oscuro') DEFAULT 'claro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `config_avanzada`
--

INSERT INTO `config_avanzada` (`id`, `provincia_defecto`, `poblacion_defecto`, `items_por_pagina`, `tiempo_sesion`, `tema`) VALUES
(1, '41', 'Tomares', 2, 360, 'oscuro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas`
--

CREATE TABLE `cuotas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `importe` decimal(10,2) DEFAULT NULL,
  `pagada` tinyint(1) DEFAULT 0,
  `fecha_pago` date DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(12, '0001_01_01_000000_create_users_table', 1),
(13, '0001_01_01_000001_create_cache_table', 1),
(14, '0001_01_01_000002_create_jobs_table', 1),
(15, '2026_02_06_205204_add_fields_to_users_table', 1),
(16, '2026_02_07_180452_add_fecha_baja_to_users_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5pJiEdx29tgnIWpXaI4DcNLnZcQEawATxN5YEVh8', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielJQRXBOdUpJSldFb0p4TjFFWXVTRlpnMG1MMkV6bzN0NGxvdFJ4YiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly9sb2NhbGhvc3QvREFXRVMvbGFyYXZlbC8yVHJpbWVzdHJlL3Byb3llY3RvLTJldmFsL3B1YmxpYyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770486980),
('6vp0LtTg5zDTSNc8AHOH9NauJzMQx7cYzSSywFOF', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRGQ5ZHR3cHh5UThhcGp3aGVDZjlUVDNSNEJScTR2Ynp6WEpVVVRQcyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc1OiJodHRwOi8vbG9jYWxob3N0L0RBV0VTL2xhcmF2ZWwvMlRyaW1lc3RyZS9hcHAtcHJveWVjdG8yL3B1YmxpYy90YXJlYXMvY3JlYXIiO3M6NToicm91dGUiO3M6MTM6InRhcmVhcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MDQ4Njg2MTt9fQ==', 1770486971),
('bHThWnhUcTiOFNgKHmmfXa3opzwszkmhSoIXJz6I', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMDVlcGJ0Q1g4dXRqV0NEVjBvZ09RZ2dkTm5LQ2Mwa3pDWU9BWUVJNyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjcxOiJodHRwOi8vbG9jYWxob3N0L0RBV0VTL2xhcmF2ZWwvMlRyaW1lc3RyZS9hcHAtcHJveWVjdG8yL3B1YmxpYy90YXJlYXMvNCI7czo1OiJyb3V0ZSI7czoxMToidGFyZWFzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MDQ4NzAwMjt9fQ==', 1770487116),
('C5421IuUlRytgSRwxVjsa1403foZc6wsuGuPoPeK', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWVLSm5SYUF5MkV5eEswTVJHbUJ3TU9OSjRyTk1GWjRsRms2TFd2RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzE6Imh0dHA6Ly9sb2NhbGhvc3QvREFXRVMvbGFyYXZlbC8yVHJpbWVzdHJlL3Byb3llY3RvLTJldmFsL3B1YmxpYy90YXJlYS82IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1770487125),
('cvlgpgNPp1EEJ6z6riyLNidFiyMRRANsqlt1Np5a', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibWNGOTZiQkF6d0dqemc5cFpnMms5REJiVEtZU1dtbXhqbEVmQThrUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjcyOiJodHRwOi8vbG9jYWxob3N0L0RBV0VTL2xhcmF2ZWwvMlRyaW1lc3RyZS9hcHAtcHJveWVjdG8yL3B1YmxpYy9lbXBsZWFkb3MiO3M6NToicm91dGUiO3M6MTU6ImVtcGxlYWRvcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzcwNDg4MDYxO319', 1770490103),
('rl0QnTEHv12TjWQdFs1slDijrc8HZQ2lvaJdS41z', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoic2lVaGZCcGxJR0JPZ292a1FmbENWekduOEp4c2VvVFVuT3FvbW1QYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc1OiJodHRwOi8vbG9jYWxob3N0L0RBV0VTL2xhcmF2ZWwvMlRyaW1lc3RyZS9hcHAtcHJveWVjdG8yL3B1YmxpYy90YXJlYXMvY3JlYXIiO3M6NToicm91dGUiO3M6MTM6InRhcmVhcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MDQ3OTU1Nzt9fQ==', 1770486813),
('vGWot65EyvBgMAxP1Z2WNRcWpTUxh8kKr942F4tN', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicUYzNzNWcVpmd0Z1SzR3SVJ6ZmdTOGtxdkYwaHRoTFQ5SUFCNjNJSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHA6Ly9sb2NhbGhvc3QvREFXRVMvbGFyYXZlbC8yVHJpbWVzdHJlL3Byb3llY3RvLTJldmFsL3B1YmxpYy9hbHRhIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1770486828);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `persona_contacto` varchar(255) NOT NULL,
  `telefono_contacto` varchar(20) NOT NULL,
  `operario_id` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `correo_contacto` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `poblacion` varchar(100) DEFAULT NULL,
  `codigo_postal` char(5) DEFAULT NULL,
  `provincia` char(2) DEFAULT NULL,
  `estado` enum('B','P','R','C') DEFAULT 'P',
  `fecha_creacion` datetime NOT NULL,
  `fecha_realizacion` date DEFAULT NULL,
  `anotaciones_anteriores` text DEFAULT NULL,
  `anotaciones_posteriores` text DEFAULT NULL,
  `fichero_resumen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `cliente_id`, `persona_contacto`, `telefono_contacto`, `operario_id`, `descripcion`, `correo_contacto`, `direccion`, `poblacion`, `codigo_postal`, `provincia`, `estado`, `fecha_creacion`, `fecha_realizacion`, `anotaciones_anteriores`, `anotaciones_posteriores`, `fichero_resumen`) VALUES
(1, 12, '', '', NULL, 'dhftfn', '', 'Calle Falsa 123', 'Sevilla', '41014', '41', 'R', '2025-11-20 08:41:00', '2025-12-04', '', 'PROBANDO A COMPLETAR OTRA TAREA', NULL),
(2, 11, '', '', NULL, 'Prueba laravel', '', 'Av Santa Marta', 'Bollullos', '21710', '21', 'R', '2025-11-20 08:20:58', '2025-12-31', '', 'Esto es para probar el rol de administrador. AHORA ESTA MODIFICANDO EL OPERARIO', NULL),
(3, 15, '', '', NULL, 'Probando a ver si el administrador puede crear una tarea', '', 'Calle Picos', 'Moguer', '21800', '21', 'R', '2025-11-27 08:30:27', '2026-01-02', '', 'subiendo archivo .docx', NULL),
(4, 16, 'ADIOS', '624985011', 2, 'probando nuevo xampp', 'prueba@gmail.com', 'Calle Espejo, 12', 'Moguer', '21800', '21', 'P', '2026-01-03 18:28:01', '2026-03-19', 'xampp nuevo instalado', 'probando a subir los ficheros despues de instalar xampp de nuevo', NULL),
(5, 14, 'Magdalena Garcia', '987654321', 3, 'foerkpwj', 'magda@gmail.com', 'Calle Ribera, 34 B', 'Huelva', '21004', '21', 'C', '2025-11-24 23:54:29', '2026-03-14', NULL, 'regvergv', NULL),
(6, 13, '', '', NULL, 't`kgbodrgb', '', '', 'sevilla', '41014', '41', 'P', '2025-11-24 22:07:34', '2026-02-20', '', 'fergverbvetbte', NULL),
(7, 12, 'jesus reyes', '788 09 87 34', 2, 'Aplicacion 2ª evaluacion laravel', 'jesusr6@gmail.com', NULL, NULL, '21005', '21', 'B', '2026-01-21 12:08:39', '2026-03-05', NULL, NULL, NULL),
(9, 14, 'juan pérez', '987564201', 1, 'NADA', 'AAAAAAA@gmail.com', 'Calle Falsa 123', 'Camas', '41014', '41', 'P', '2026-02-04 22:18:42', '2026-02-23', NULL, NULL, NULL),
(10, 13, 'Antonio Murillo', '987564201', 3, 'Cambios importantes de Empleados a Users', 'correo@gmail.com', 'Av Santa Marta', 'moguer', '21800', '21', 'P', '2026-02-06 23:18:01', '2026-03-06', NULL, NULL, NULL);

--
-- Disparadores `tareas`
--
DELIMITER $$
CREATE TRIGGER `tareas_before_insert` BEFORE INSERT ON `tareas` FOR EACH ROW BEGIN
    SET NEW.fecha_creacion = NOW();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_tareas_fecha_creacion_insert` BEFORE INSERT ON `tareas` FOR EACH ROW BEGIN
    IF NEW.fecha_creacion IS NULL THEN
        SET NEW.fecha_creacion = CURRENT_TIMESTAMP();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_tareas_fecha_creacion_update` BEFORE UPDATE ON `tareas` FOR EACH ROW BEGIN
    SET NEW.fecha_creacion = OLD.fecha_creacion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dni` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `tipo` enum('administrador','operario') NOT NULL DEFAULT 'operario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `dni`, `telefono`, `direccion`, `fecha_alta`, `fecha_baja`, `tipo`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$nQ3ZHBePyTTnLERaWUyZBOSClEsWEw3Tdxbt3Y92Q1Bv6xYIFuz0.', NULL, '2026-02-06 20:12:19', '2026-02-07 14:36:26', 'X5114563F', '644789225', 'Calle Galaroza, 21', '2026-02-03', NULL, 'administrador'),
(2, 'operario1', 'operario1@gmail.com', NULL, '$2y$12$9hFZKXZkWyzZ0ZnTTvHyP.9Htq3BReXpU6y1JA.2ZFOUzt5eyjPC6', NULL, '2026-02-06 20:12:58', '2026-02-06 20:12:58', '70907286B', '778023544', 'Calle Prueba2', '2026-02-04', NULL, 'operario'),
(3, 'Daniel', 'daniel04@gmail.com', NULL, '$2y$12$GhhcV09sAdCVKo6iqjLyZuZD8Chv9ys9n37yYGtJ6oxhq8o8F.kNq', NULL, '2026-02-06 21:28:58', '2026-02-06 21:28:58', '21237945K', '730021954', 'Calle Ribera, 34 B', '2026-02-05', NULL, 'operario'),
(4, 'Prueba Baja', 'prueba000@gmail.com', NULL, '$2y$12$oIXAeUOMgMTpvDvK7kT4Q.osApLGeWVnfKqiTSZh2fwd3eD5MDfEa', NULL, '2026-02-07 17:41:05', '2026-02-07 17:47:54', '82572266N', '123456789', 'Calle Baja', '2026-01-27', NULL, 'operario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cif` (`cif`);

--
-- Indices de la tabla `config_avanzada`
--
ALTER TABLE `config_avanzada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

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
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `operario_id` (`operario_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_dni_unique` (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `config_avanzada`
--
ALTER TABLE `config_avanzada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD CONSTRAINT `cuotas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
