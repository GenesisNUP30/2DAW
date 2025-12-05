-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2025 a las 01:21:46
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
-- Base de datos: `ahorcado`
--


--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'animales'),
(2, 'paises'),
(3, 'frutas'),
(4, 'colores'),
(5, 'profesiones'),
(6, 'marcas');

-- --------------------------------------------------------

--

CREATE TABLE `palabras` (
  `id` int(11) NOT NULL,
  `palabra` varchar(50) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


--

INSERT INTO `palabras` (`id`, `palabra`, `categoria_id`) VALUES
(1, 'perro', 1),
(2, 'gato', 1),
(3, 'elefante', 1),
(4, 'tigre', 1),
(5, 'jirafa', 1),
(6, 'españa', 2),
(7, 'francia', 2),
(8, 'japon', 2),
(9, 'brasil', 2),
(10, 'canada', 2),
(11, 'manzana', 3),
(12, 'platano', 3),
(13, 'naranja', 3),
(14, 'uva', 3),
(15, 'fresa', 3),
(16, 'rojo', 4),
(17, 'azul', 4),
(18, 'verde', 4),
(19, 'amarillo', 4),
(20, 'morado', 4),
(21, 'medico', 5),
(22, 'maestro', 5),
(23, 'ingeniero', 5),
(24, 'piloto', 5),
(25, 'musico', 5),
(26, 'adidas', 6),
(27, 'nike', 6),
(28, 'gucci', 6),
(29, 'chanel', 6),
(30, 'oso', 1),
(31, 'chanel', 6);

-- --------------------------------------------------------

--

CREATE TABLE `partidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `palabra_id` int(11) NOT NULL,
  `estado` varchar(20) DEFAULT 'en_progreso',
  `puntos` int(11) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------


--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--

INSERT INTO `users` (`id`, `username`, `password`, `admin`) VALUES
(1, 'jugador', '1234', 0),
(2, 'ana', 'contraseña', 0),
(3, 'admin', 'admin123', 1);

--

--


ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--

--
ALTER TABLE `palabras`
  ADD PRIMARY KEY (`id`);

--

--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id`);


--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);


--


--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


--
ALTER TABLE `palabras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;


--
ALTER TABLE `partidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
