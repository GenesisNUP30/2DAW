-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-01-2026 a las 19:03:59
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
-- Base de datos: `proyecto_1eval`
--

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
-- Estructura de tabla para la tabla `login_token`
--

CREATE TABLE `login_token` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `selector_hash` varchar(255) NOT NULL,
  `validator_hash` varchar(255) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `is_expired` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `nif_cif` varchar(20) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` text NOT NULL,
  `poblacion` varchar(100) NOT NULL,
  `codigo_postal` char(5) NOT NULL,
  `provincia` char(2) NOT NULL,
  `descripcion` text DEFAULT NULL, 
  `anotaciones_anteriores` text DEFAULT NULL,
  `estado` enum('B','P','R','C') NOT NULL DEFAULT 'P',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `operario_encargado` varchar(50) DEFAULT NULL,
  `fecha_realizacion` date NOT NULL,
  `anotaciones_posteriores` text DEFAULT NULL,
  `fichero_resumen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `nif_cif`, `persona_contacto`, `telefono`, `correo`, `direccion`, `poblacion`, `codigo_postal`, `provincia`, `descripcion`, `anotaciones_anteriores`, `estado`, `fecha_creacion`, `operario_encargado`, `fecha_realizacion`, `anotaciones_posteriores`, `fichero_resumen`) VALUES
(1, '12345678Z', 'Manuel García', '111 222 333', 'manuelgarcia@gmail.com', 'Av Santa Marta', 'Bollullos', '21710', '21', 'Prueba laravel', '', 'R', '2025-11-20 08:20:58', 'Ana María Fernández', '2025-12-31', 'Esto es para probar el rol de administrador. AHORA ESTA MODIFICANDO EL OPERARIO', NULL),
(2, '12345678Z', 'pepe', '986 562 147', 'prueba2@gmail.com', 'Calle Falsa 123', 'Sevilla', '41014', '41', 'dhftfn', '', 'R', '2025-11-20 08:41:00', 'Lucía Hurtado', '2025-12-04', 'PROBANDO A COMPLETAR OTRA TAREA', NULL),
(3, '12345678Z', 'laravel5', '986 562 147', 'prueba5@gmail.com', '', 'sevilla', '41014', '41', 't`kgbodrgb', '', 'P', '2025-11-24 22:07:34', 'Carlos Ruiz', '2026-02-20', 'fergverbvetbte', NULL),
(4, '12345678Z', 'yujuu', '+34 654 874 320', 'prueba2@gmail.com', '', '', '21004', '21', 'foerkpwj', '', 'C', '2025-11-24 23:54:29', 'Carlos Ruiz', '2025-12-24', 'regvergv', NULL),
(5, '12345678Z', 'No lo se', '123456789', 'adminstrador@gmail.com', 'Calle Picos', 'Moguer', '21800', '21', 'Probando a ver si el administrador puede crear una tarea', '', 'R', '2025-11-27 08:30:27', 'Lucía Hurtado', '2026-01-02', 'subiendo archivo .docx', '5_prueba.docx'),
(6, '12345678Z', 'Antonio Murillo', '959 78 65 01', 'examen2@gmail.com', 'Calle Espejo, 12', 'Moguer', '21800', '21', 'probando nuevo xampp', 'xampp nuevo instalado', 'P', '2026-01-03 18:28:01', 'Carlos Ruiz', '2026-03-19', 'probando a subir los ficheros despues de instalar xampp de nuevo', '6_tarea_antonio-9-1-26.txt');

--
-- Disparadores `tareas`
--
DELIMITER $$
CREATE TRIGGER `tr_no_modificar_fecha_creacion` BEFORE UPDATE ON `tareas` FOR EACH ROW BEGIN
    SET NEW.fecha_creacion = OLD.fecha_creacion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` enum('administrador','operario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`) VALUES
(1, 'operario', 'operario', 'operario'),
(2, 'admin', 'admin123', 'administrador'),
(5, 'Genesis', 'genesis', 'administrador'),
(6, 'jose angel', 'jozeanje', 'administrador'),
(7, 'antonio', '1234', 'operario'),
(8, 'prueba', '123', 'operario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `config_avanzada`
--
ALTER TABLE `config_avanzada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `login_token`
--
ALTER TABLE `login_token`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `config_avanzada`
--
ALTER TABLE `config_avanzada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `login_token`
--
ALTER TABLE `login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
