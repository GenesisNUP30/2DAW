-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2025 a las 08:41:51
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
  `anotaciones_posteriores` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `nif_cif`, `persona_contacto`, `telefono`, `correo`, `direccion`, `poblacion`, `codigo_postal`, `provincia`, `descripcion`, `anotaciones_anteriores`, `estado`, `fecha_creacion`, `operario_encargado`, `fecha_realizacion`, `anotaciones_posteriores`) VALUES
(1, '12345678Z', 'Manuel García', '111 222 333', 'manuelgarcia@gmail.com', 'Av Santa Marta', 'Bollullos', '21710', '21', 'Prueba laravel', '', 'R', '2025-11-20 08:20:58', 'Ana María Fernández', '2025-12-31', 'Esto es para probar el rol de administrador. AHORA ESTA MODIFICANDO EL OPERARIO'),
(2, '12345678Z', 'pepe', '986 562 147', 'prueba2@gmail.com', 'Calle Falsa 123', 'Sevilla', '41014', '41', 'dhftfn', '', 'R', '2025-11-20 08:41:00', 'Lucía Hurtado', '2025-12-04', 'PROBANDO A COMPLETAR OTRA TAREA'),
(3, '12345678Z', 'laravel5', '986 562 147', 'prueba5@gmail.com', '', 'sevilla', '41014', '41', 't`kgbodrgb', '', 'B', '2025-11-24 22:07:34', 'Carlos Ruiz', '2025-12-26', 'fergverbvetbte'),
(4, '12345678Z', 'Prueba 45', '+34 654 874 320', 'prueba2@gmail.com', '', '', '21004', '21', 'foerkpwj', '', 'C', '2025-11-24 23:54:29', 'Carlos Ruiz', '2025-12-24', 'regvergv'),
(5, '12345678Z', 'No lo se', '123456789', 'adminstrador@gmail.com', 'Calle Picos', 'Moguer', '21800', '21', 'Probando a ver si el administrador puede crear una tarea', '', 'P', '2025-11-27 08:30:27', 'Lucía Hurtado', '2026-01-02', NULL),
(6, '12345678Z', 'Examen1', '123456789', 'examen1@gmail.com', '', 'Huelva', '21005', '21', 'prueba', '', 'P', '2025-12-05 08:20:29', 'María López', '2026-01-02', NULL);

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
(1, 'Antonio', 'Antonio123', 'operario'),
(2, 'Jefe Proyectos', 'Jefe1', 'administrador');

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
