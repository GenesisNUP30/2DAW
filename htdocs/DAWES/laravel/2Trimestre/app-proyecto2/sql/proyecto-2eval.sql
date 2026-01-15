-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-01-2026 a las 11:01:16
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
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_alta` date NOT NULL,
  `tipo` enum('administrador','operario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `dni`, `nombre`, `correo`, `telefono`, `direccion`, `fecha_alta`, `tipo`) VALUES
(1, 'operario', 'operario', 'operario@gmail.com', NULL, NULL, '2026-01-15', 'operario'),
(2, 'admin', 'admin', 'admin@gmail.com', NULL, NULL, '2026-01-15', 'administrador'),
(3, 'Genesis', 'Genesis', 'Genesis@gmail.com', NULL, NULL, '2026-01-15', 'administrador'),
(4, 'jose angel', 'jose angel', 'jose angel@gmail.com', NULL, NULL, '2026-01-15', 'administrador'),
(5, 'antonio', 'antonio', 'antonio@gmail.com', NULL, NULL, '2026-01-15', 'operario'),
(6, 'prueba', 'prueba', 'prueba@gmail.com', NULL, NULL, '2026-01-15', 'operario');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `operario_id` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `poblacion` varchar(100) DEFAULT NULL,
  `codigo_postal` char(5) DEFAULT NULL,
  `provincia` char(2) DEFAULT NULL,
  `estado` enum('P','R','C') DEFAULT 'P',
  `fecha_creacion` datetime NOT NULL,
  `fecha_realizacion` date DEFAULT NULL,
  `anotaciones_anteriores` text DEFAULT NULL,
  `anotaciones_posteriores` text DEFAULT NULL,
  `fichero_resumen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `cliente_id`, `operario_id`, `descripcion`, `direccion`, `poblacion`, `codigo_postal`, `provincia`, `estado`, `fecha_creacion`, `fecha_realizacion`, `anotaciones_anteriores`, `anotaciones_posteriores`, `fichero_resumen`) VALUES
(1, 12, NULL, 'dhftfn', 'Calle Falsa 123', 'Sevilla', '41014', '41', 'R', '2025-11-20 08:41:00', '2025-12-04', '', 'PROBANDO A COMPLETAR OTRA TAREA', NULL),
(2, 11, NULL, 'Prueba laravel', 'Av Santa Marta', 'Bollullos', '21710', '21', 'R', '2025-11-20 08:20:58', '2025-12-31', '', 'Esto es para probar el rol de administrador. AHORA ESTA MODIFICANDO EL OPERARIO', NULL),
(3, 15, NULL, 'Probando a ver si el administrador puede crear una tarea', 'Calle Picos', 'Moguer', '21800', '21', 'R', '2025-11-27 08:30:27', '2026-01-02', '', 'subiendo archivo .docx', NULL),
(4, 16, NULL, 'probando nuevo xampp', 'Calle Espejo, 12', 'Moguer', '21800', '21', 'P', '2026-01-03 18:28:01', '2026-03-19', 'xampp nuevo instalado', 'probando a subir los ficheros despues de instalar xampp de nuevo', NULL),
(5, 14, NULL, 'foerkpwj', '', '', '21004', '21', 'C', '2025-11-24 23:54:29', '2025-12-24', '', 'regvergv', NULL),
(6, 13, NULL, 't`kgbodrgb', '', 'sevilla', '41014', '41', 'P', '2025-11-24 22:07:34', '2026-02-20', '', 'fergverbvetbte', NULL);

--
-- Disparadores `tareas`
--
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
  `empleado_id` int(11) DEFAULT NULL,
  `tipo` enum('administrador','operario') NOT NULL DEFAULT 'operario',
  `selector_hash` varchar(255) DEFAULT NULL,
  `validator_hash` varchar(255) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `is_expired` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `empleado_id`, `tipo`, `selector_hash`, `validator_hash`, `expiry_date`, `is_expired`) VALUES
(1, 'operario', 'operario@gmail.com', NULL, 'operario', NULL, NULL, NULL, 1, 'operario', NULL, NULL, NULL, 0),
(2, 'admin', 'admin@gmail.com', NULL, 'admin123', NULL, NULL, NULL, 2, 'administrador', NULL, NULL, NULL, 0),
(3, 'Genesis', 'Genesis@gmail.com', NULL, 'genesis', NULL, NULL, NULL, 3, 'administrador', NULL, NULL, NULL, 0),
(4, 'jose angel', 'jose angel@gmail.com', NULL, 'jozeanje', NULL, NULL, NULL, 4, 'administrador', NULL, NULL, NULL, 0),
(5, 'antonio', 'antonio@gmail.com', NULL, '1234', NULL, NULL, NULL, 5, 'operario', NULL, NULL, NULL, 0),
(6, 'prueba', 'prueba@gmail.com', NULL, '123', NULL, NULL, NULL, 6, 'operario', NULL, NULL, NULL, 0);

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
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
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
  ADD KEY `users_empleado_fk` (`empleado_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`operario_id`) REFERENCES `empleados` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_empleado_fk` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
