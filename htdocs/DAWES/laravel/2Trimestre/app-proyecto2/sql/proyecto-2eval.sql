-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-02-2026 a las 18:04:54
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
  `cuenta_corriente` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL,
  `importe_cuota_mensual` decimal(10,2) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `cif`, `nombre`, `telefono`, `correo`, `cuenta_corriente`, `pais`, `moneda`, `importe_cuota_mensual`, `fecha_alta`, `fecha_baja`) VALUES
(11, 'A78818549', 'Manuel García', '+34 684 247 233', 'manuelgarcia@gmail.com', NULL, 'ES', 'EUR', NULL, '2026-01-13', NULL),
(12, 'U56648900', 'Francesco', '986 562 147', 'prueba2@gmail.com', NULL, 'IT', 'EUR', NULL, '2026-01-13', NULL),
(13, 'X0153310S', 'Camille', '986 562 147', 'prueba5@gmail.com', NULL, 'FR', 'EUR', NULL, '2026-01-13', NULL),
(14, 'Q6501800D', 'Tiago', '+34 654 874 320', 'prueba2@gmail.com', NULL, 'PT', 'EUR', NULL, '2026-01-14', NULL),
(15, 'V27619139', 'Viktoria', '123456789', 'viki62@gmail.com', NULL, 'AT', 'EUR', NULL, '2026-01-14', NULL),
(16, 'A59244095', 'Roos', '959 78 65 01', 'roos.prueba@gmail.com', NULL, 'NL', 'EUR', NULL, '2026-01-14', NULL);

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
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `id` smallint(3) UNSIGNED ZEROFILL NOT NULL,
  `iso2` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `iso3` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prefijo` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `continente` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `subcontinente` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_moneda` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_moneda` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`id`, `iso2`, `iso3`, `prefijo`, `nombre`, `continente`, `subcontinente`, `iso_moneda`, `nombre_moneda`) VALUES
(004, 'AF', 'AFG', 93, 'Afganistán', 'Asia', NULL, 'AFN', 'Afgani afgano'),
(008, 'AL', 'ALB', 355, 'Albania', 'Europa', NULL, 'ALL', 'Lek albanés'),
(010, 'AQ', 'ATA', 672, 'Antártida', 'Antártida', NULL, NULL, NULL),
(012, 'DZ', 'DZA', 213, 'Argelia', 'África', NULL, 'DZD', 'Dinar algerino'),
(016, 'AS', 'ASM', 1684, 'Samoa Americana', 'Oceanía', NULL, NULL, NULL),
(020, 'AD', 'AND', 376, 'Andorra', 'Europa', NULL, 'EUR', 'Euro'),
(024, 'AO', 'AGO', 244, 'Angola', 'África', NULL, 'AOA', 'Kwanza angoleño'),
(028, 'AG', 'ATG', 1268, 'Antigua y Barbuda', 'América', 'El Caribe', NULL, NULL),
(031, 'AZ', 'AZE', 994, 'Azerbaiyán', 'Asia', NULL, 'AZM', 'Manat azerbaiyano'),
(032, 'AR', 'ARG', 54, 'Argentina', 'América', 'América del Sur', 'ARS', 'Peso argentino'),
(036, 'AU', 'AUS', 61, 'Australia', 'Oceanía', NULL, 'AUD', 'Dólar australiano'),
(040, 'AT', 'AUT', 43, 'Austria', 'Europa', NULL, 'EUR', 'Euro'),
(044, 'BS', 'BHS', 1242, 'Bahamas', 'América', 'El Caribe', 'BSD', 'Dólar bahameño'),
(048, 'BH', 'BHR', 973, 'Bahrein', 'Asia', NULL, 'BHD', 'Dinar bahreiní'),
(050, 'BD', 'BGD', 880, 'Bangladesh', 'Asia', NULL, 'BDT', 'Taka de Bangladesh'),
(051, 'AM', 'ARM', 374, 'Armenia', 'Asia', NULL, 'AMD', 'Dram armenio'),
(052, 'BB', 'BRB', 1246, 'Barbados', 'América', 'El Caribe', 'BBD', 'Dólar de Barbados'),
(056, 'BE', 'BEL', 32, 'Bélgica', 'Europa', NULL, 'EUR', 'Euro'),
(060, 'BM', 'BMU', 1441, 'Bermudas', 'América', 'El Caribe', 'BMD', 'Dólar de Bermuda'),
(064, 'BT', 'BTN', 975, 'Bután', 'Asia', NULL, 'BTN', 'Ngultrum de Bután'),
(068, 'BO', 'BOL', 591, 'Bolivia', 'América', 'América del Sur', 'BOB', 'Boliviano'),
(070, 'BA', 'BIH', 387, 'Bosnia y Herzegovina', 'Europa', NULL, 'BAM', 'Marco convertible de Bosnia-Herzegovina'),
(072, 'BW', 'BWA', 267, 'Botsuana', 'África', NULL, 'BWP', 'Pula de Botsuana'),
(074, 'BV', 'BVT', 0, 'Isla Bouvet', NULL, NULL, NULL, NULL),
(076, 'BR', 'BRA', 55, 'Brasil', 'América', 'América del Sur', 'BRL', 'Real brasileño'),
(084, 'BZ', 'BLZ', 501, 'Belice', 'América', 'América Central', 'BZD', 'Dólar de Belice'),
(086, 'IO', 'IOT', 0, 'Territorio Británico del Océano Índico', NULL, NULL, NULL, NULL),
(090, 'SB', 'SLB', 677, 'Islas Salomón', 'Oceanía', NULL, 'SBD', 'Dólar de las Islas Salomón'),
(092, 'VG', 'VGB', 1284, 'Islas Vírgenes Británicas', 'América', 'El Caribe', NULL, NULL),
(096, 'BN', 'BRN', 673, 'Brunéi', 'Asia', NULL, 'BND', 'Dólar de Brunéi'),
(100, 'BG', 'BGR', 359, 'Bulgaria', 'Europa', NULL, 'BGN', 'Lev búlgaro'),
(104, 'MM', 'MMR', 95, 'Myanmar', 'Asia', NULL, 'MMK', 'Kyat birmano'),
(108, 'BI', 'BDI', 257, 'Burundi', 'África', NULL, 'BIF', 'Franco burundés'),
(112, 'BY', 'BLR', 375, 'Bielorrusia', 'Europa', NULL, 'BYR', 'Rublo bielorruso'),
(116, 'KH', 'KHM', 855, 'Camboya', 'Asia', NULL, 'KHR', 'Riel camboyano'),
(120, 'CM', 'CMR', 237, 'Camerún', 'África', NULL, NULL, NULL),
(124, 'CA', 'CAN', 1, 'Canadá', 'América', 'América del Norte', 'CAD', 'Dólar canadiense'),
(132, 'CV', 'CPV', 238, 'Cabo Verde', 'África', NULL, 'CVE', 'Escudo caboverdiano'),
(136, 'KY', 'CYM', 1345, 'Islas Caimán', 'América', 'El Caribe', 'KYD', 'Dólar caimano (de Islas Caimán)'),
(140, 'CF', 'CAF', 236, 'República Centroafricana', 'África', NULL, NULL, NULL),
(144, 'LK', 'LKA', 94, 'Sri Lanka', 'Asia', NULL, 'LKR', 'Rupia de Sri Lanka'),
(148, 'TD', 'TCD', 235, 'Chad', 'África', NULL, NULL, NULL),
(152, 'CL', 'CHL', 56, 'Chile', 'América', 'América del Sur', 'CLP', 'Peso chileno'),
(156, 'CN', 'CHN', 86, 'China', 'Asia', NULL, 'CNY', 'Yuan Renminbi de China'),
(158, 'TW', 'TWN', 886, 'Taiwán', 'Asia', NULL, 'TWD', 'Dólar taiwanés'),
(162, 'CX', 'CXR', 61, 'Isla de Navidad', 'Oceanía', NULL, NULL, NULL),
(166, 'CC', 'CCK', 61, 'Islas Cocos', 'Oceanía', NULL, NULL, NULL),
(170, 'CO', 'COL', 57, 'Colombia', 'América', 'América del Sur', 'COP', 'Peso colombiano'),
(174, 'KM', 'COM', 269, 'Comoras', 'África', NULL, 'KMF', 'Franco comoriano (de Comoras)'),
(175, 'YT', 'MYT', 262, 'Mayotte', 'África', NULL, NULL, NULL),
(178, 'CG', 'COG', 242, 'Congo', 'África', NULL, NULL, NULL),
(180, 'CD', 'COD', 243, 'República Democrática del Congo', 'África', NULL, 'CDF', 'Franco congoleño'),
(184, 'CK', 'COK', 682, 'Islas Cook', 'Oceanía', NULL, NULL, NULL),
(188, 'CR', 'CRI', 506, 'Costa Rica', 'América', 'América Central', 'CRC', 'Colón costarricense'),
(191, 'HR', 'HRV', 385, 'Croacia', 'Europa', NULL, 'HRK', 'Kuna croata'),
(192, 'CU', 'CUB', 53, 'Cuba', 'América', 'El Caribe', 'CUP', 'Peso cubano'),
(196, 'CY', 'CYP', 357, 'Chipre', 'Europa', NULL, 'CYP', 'Libra chipriota'),
(203, 'CZ', 'CZE', 420, 'República Checa', 'Europa', NULL, 'CZK', 'Koruna checa'),
(204, 'BJ', 'BEN', 229, 'Benín', 'África', NULL, NULL, NULL),
(208, 'DK', 'DNK', 45, 'Dinamarca', 'Europa', NULL, 'DKK', 'Corona danesa'),
(212, 'DM', 'DMA', 1767, 'Dominica', 'América', 'El Caribe', NULL, NULL),
(214, 'DO', 'DOM', 1809, 'República Dominicana', 'América', 'El Caribe', 'DOP', 'Peso dominicano'),
(218, 'EC', 'ECU', 593, 'Ecuador', 'América', 'América del Sur', NULL, NULL),
(222, 'SV', 'SLV', 503, 'El Salvador', 'América', 'América Central', 'SVC', 'Colón salvadoreño'),
(226, 'GQ', 'GNQ', 240, 'Guinea Ecuatorial', 'África', NULL, NULL, NULL),
(231, 'ET', 'ETH', 251, 'Etiopía', 'África', NULL, 'ETB', 'Birr etíope'),
(232, 'ER', 'ERI', 291, 'Eritrea', 'África', NULL, 'ERN', 'Nakfa eritreo'),
(233, 'EE', 'EST', 372, 'Estonia', 'Europa', NULL, 'EEK', 'Corona estonia'),
(234, 'FO', 'FRO', 298, 'Islas Feroe', 'Europa', NULL, NULL, NULL),
(238, 'FK', 'FLK', 500, 'Islas Malvinas', 'América', 'América del Sur', 'FKP', 'Libra malvinense'),
(239, 'GS', 'SGS', 0, 'Islas Georgias del Sur y Sandwich del Sur', 'América', 'América del Sur', NULL, NULL),
(242, 'FJ', 'FJI', 679, 'Fiyi', 'Oceanía', NULL, 'FJD', 'Dólar fijiano'),
(246, 'FI', 'FIN', 358, 'Finlandia', 'Europa', NULL, 'EUR', 'Euro'),
(248, 'AX', 'ALA', 0, 'Islas Gland', 'Europa', NULL, NULL, NULL),
(250, 'FR', 'FRA', 33, 'Francia', 'Europa', NULL, 'EUR', 'Euro'),
(254, 'GF', 'GUF', 0, 'Guayana Francesa', 'América', 'América del Sur', NULL, NULL),
(258, 'PF', 'PYF', 689, 'Polinesia Francesa', 'Oceanía', NULL, NULL, NULL),
(260, 'TF', 'ATF', 0, 'Territorios Australes Franceses', NULL, NULL, NULL, NULL),
(262, 'DJ', 'DJI', 253, 'Yibuti', 'África', NULL, 'DJF', 'Franco yibutiano'),
(266, 'GA', 'GAB', 241, 'Gabón', 'África', NULL, NULL, NULL),
(268, 'GE', 'GEO', 995, 'Georgia', 'Europa', NULL, 'GEL', 'Lari georgiano'),
(270, 'GM', 'GMB', 220, 'Gambia', 'África', NULL, 'GMD', 'Dalasi gambiano'),
(275, 'PS', 'PSE', 0, 'Palestina', 'Asia', NULL, NULL, NULL),
(276, 'DE', 'DEU', 49, 'Alemania', 'Europa', NULL, 'EUR', 'Euro'),
(288, 'GH', 'GHA', 233, 'Ghana', 'África', NULL, 'GHC', 'Cedi ghanés'),
(292, 'GI', 'GIB', 350, 'Gibraltar', 'Europa', NULL, 'GIP', 'Libra de Gibraltar'),
(296, 'KI', 'KIR', 686, 'Kiribati', 'Oceanía', NULL, NULL, NULL),
(300, 'GR', 'GRC', 30, 'Grecia', 'Europa', NULL, 'EUR', 'Euro'),
(304, 'GL', 'GRL', 299, 'Groenlandia', 'América', 'América del Norte', NULL, NULL),
(308, 'GD', 'GRD', 1473, 'Granada', 'América', 'El Caribe', NULL, NULL),
(312, 'GP', 'GLP', 0, 'Guadalupe', 'América', 'El Caribe', NULL, NULL),
(316, 'GU', 'GUM', 1671, 'Guam', 'Oceanía', NULL, NULL, NULL),
(320, 'GT', 'GTM', 502, 'Guatemala', 'América', 'América Central', 'GTQ', 'Quetzal guatemalteco'),
(324, 'GN', 'GIN', 224, 'Guinea', 'África', NULL, 'GNF', 'Franco guineano'),
(328, 'GY', 'GUY', 592, 'Guyana', 'América', 'América del Sur', 'GYD', 'Dólar guyanés'),
(332, 'HT', 'HTI', 509, 'Haití', 'América', 'El Caribe', 'HTG', 'Gourde haitiano'),
(334, 'HM', 'HMD', 0, 'Islas Heard y McDonald', 'Oceanía', NULL, NULL, NULL),
(336, 'VA', 'VAT', 39, 'Ciudad del Vaticano', 'Europa', NULL, NULL, NULL),
(340, 'HN', 'HND', 504, 'Honduras', 'América', 'América Central', 'HNL', 'Lempira hondureña'),
(344, 'HK', 'HKG', 852, 'Hong Kong', 'Asia', NULL, 'HKD', 'Dólar de Hong Kong'),
(348, 'HU', 'HUN', 36, 'Hungría', 'Europa', NULL, 'HUF', 'Forint húngaro'),
(352, 'IS', 'ISL', 354, 'Islandia', 'Europa', NULL, 'ISK', 'Króna islandesa'),
(356, 'IN', 'IND', 91, 'India', 'Asia', NULL, 'INR', 'Rupia india'),
(360, 'ID', 'IDN', 62, 'Indonesia', 'Asia', NULL, 'IDR', 'Rupiah indonesia'),
(364, 'IR', 'IRN', 98, 'Irán', 'Asia', NULL, 'IRR', 'Rial iraní'),
(368, 'IQ', 'IRQ', 964, 'Iraq', 'Asia', NULL, 'IQD', 'Dinar iraquí'),
(372, 'IE', 'IRL', 353, 'Irlanda', 'Europa', NULL, 'EUR', 'Euro'),
(376, 'IL', 'ISR', 972, 'Israel', 'Asia', NULL, 'ILS', 'Nuevo shéquel israelí'),
(380, 'IT', 'ITA', 39, 'Italia', 'Europa', NULL, 'EUR', 'Euro'),
(384, 'CI', 'CIV', 225, 'Costa de Marfil', 'África', NULL, NULL, NULL),
(388, 'JM', 'JAM', 1876, 'Jamaica', 'América', 'El Caribe', 'JMD', 'Dólar jamaicano'),
(392, 'JP', 'JPN', 81, 'Japón', 'Asia', NULL, 'JPY', 'Yen japonés'),
(398, 'KZ', 'KAZ', 7, 'Kazajstán', 'Asia', NULL, 'KZT', 'Tenge kazajo'),
(400, 'JO', 'JOR', 962, 'Jordania', 'Asia', NULL, 'JOD', 'Dinar jordano'),
(404, 'KE', 'KEN', 254, 'Kenia', 'África', NULL, 'KES', 'Chelín keniata'),
(408, 'KP', 'PRK', 850, 'Corea del Norte', 'Asia', NULL, 'KPW', 'Won norcoreano'),
(410, 'KR', 'KOR', 82, 'Corea del Sur', 'Asia', NULL, 'KRW', 'Won surcoreano'),
(414, 'KW', 'KWT', 965, 'Kuwait', 'Asia', NULL, 'KWD', 'Dinar kuwaití'),
(417, 'KG', 'KGZ', 996, 'Kirguistán', 'Asia', NULL, 'KGS', 'Som kirguís (de Kirguistán)'),
(418, 'LA', 'LAO', 856, 'Laos', 'Asia', NULL, 'LAK', 'Kip lao'),
(422, 'LB', 'LBN', 961, 'Líbano', 'Asia', NULL, 'LBP', 'Libra libanesa'),
(426, 'LS', 'LSO', 266, 'Lesotho', 'África', NULL, 'LSL', 'Loti lesotense'),
(428, 'LV', 'LVA', 371, 'Letonia', 'Europa', NULL, 'LVL', 'Lat letón'),
(430, 'LR', 'LBR', 231, 'Liberia', 'África', NULL, 'LRD', 'Dólar liberiano'),
(434, 'LY', 'LBY', 218, 'Libia', 'África', NULL, 'LYD', 'Dinar libio'),
(438, 'LI', 'LIE', 423, 'Liechtenstein', 'Europa', NULL, NULL, NULL),
(440, 'LT', 'LTU', 370, 'Lituania', 'Europa', NULL, 'LTL', 'Litas lituano'),
(442, 'LU', 'LUX', 352, 'Luxemburgo', 'Europa', NULL, 'EUR', 'Euro'),
(446, 'MO', 'MAC', 853, 'Macao', 'Asia', NULL, 'MOP', 'Pataca de Macao'),
(450, 'MG', 'MDG', 261, 'Madagascar', 'África', NULL, 'MGA', 'Ariary malgache'),
(454, 'MW', 'MWI', 265, 'Malaui', 'África', NULL, 'MWK', 'Kwacha malauiano'),
(458, 'MY', 'MYS', 60, 'Malasia', 'Asia', NULL, 'MYR', 'Ringgit malayo'),
(462, 'MV', 'MDV', 960, 'Maldivas', 'Asia', NULL, 'MVR', 'Rufiyaa maldiva'),
(466, 'ML', 'MLI', 223, 'Malí', 'África', NULL, NULL, NULL),
(470, 'MT', 'MLT', 356, 'Malta', 'Europa', NULL, 'MTL', 'Lira maltesa'),
(474, 'MQ', 'MTQ', 0, 'Martinica', 'América', 'El Caribe', NULL, NULL),
(478, 'MR', 'MRT', 222, 'Mauritania', 'África', NULL, 'MRO', 'Ouguiya mauritana'),
(480, 'MU', 'MUS', 230, 'Mauricio', 'África', NULL, 'MUR', 'Rupia mauricia'),
(484, 'MX', 'MEX', 52, 'México', 'América', 'América del Norte', 'MXN', 'Peso mexicano'),
(492, 'MC', 'MCO', 377, 'Mónaco', 'Europa', NULL, NULL, NULL),
(496, 'MN', 'MNG', 976, 'Mongolia', 'Asia', NULL, 'MNT', 'Tughrik mongol'),
(498, 'MD', 'MDA', 373, 'Moldavia', 'Europa', NULL, 'MDL', 'Leu moldavo'),
(499, 'ME', 'MNE', 382, 'Montenegro', 'Europa', NULL, NULL, NULL),
(500, 'MS', 'MSR', 1664, 'Montserrat', 'América', 'El Caribe', NULL, NULL),
(504, 'MA', 'MAR', 212, 'Marruecos', 'África', NULL, 'MAD', 'Dirham marroquí'),
(508, 'MZ', 'MOZ', 258, 'Mozambique', 'África', NULL, 'MZM', 'Metical mozambiqueño'),
(512, 'OM', 'OMN', 968, 'Omán', 'Asia', NULL, 'OMR', 'Rial omaní'),
(516, 'NA', 'NAM', 264, 'Namibia', 'África', NULL, 'NAD', 'Dólar namibio'),
(520, 'NR', 'NRU', 674, 'Nauru', 'Oceanía', NULL, NULL, NULL),
(524, 'NP', 'NPL', 977, 'Nepal', 'Asia', NULL, 'NPR', 'Rupia nepalesa'),
(528, 'NL', 'NLD', 31, 'Países Bajos', 'Europa', NULL, 'EUR', 'Euro'),
(530, 'AN', 'ANT', 599, 'Antillas Holandesas', 'América', 'El Caribe', 'ANG', 'Florín antillano neerlandés'),
(533, 'AW', 'ABW', 297, 'Aruba', 'América', 'El Caribe', 'AWG', 'Florín arubeño'),
(540, 'NC', 'NCL', 687, 'Nueva Caledonia', 'Oceanía', NULL, NULL, NULL),
(548, 'VU', 'VUT', 678, 'Vanuatu', 'Oceanía', NULL, 'VUV', 'Vatu vanuatense'),
(554, 'NZ', 'NZL', 64, 'Nueva Zelanda', 'Oceanía', NULL, 'NZD', 'Dólar neozelandés'),
(558, 'NI', 'NIC', 505, 'Nicaragua', 'América', 'América Central', 'NIO', 'Córdoba nicaragüense'),
(562, 'NE', 'NER', 227, 'Níger', 'África', NULL, NULL, NULL),
(566, 'NG', 'NGA', 234, 'Nigeria', 'África', NULL, 'NGN', 'Naira nigeriana'),
(570, 'NU', 'NIU', 683, 'Niue', 'Oceanía', NULL, NULL, NULL),
(574, 'NF', 'NFK', 0, 'Isla Norfolk', 'Oceanía', NULL, NULL, NULL),
(578, 'NO', 'NOR', 47, 'Noruega', 'Europa', NULL, 'NOK', 'Corona noruega'),
(580, 'MP', 'MNP', 1670, 'Islas Marianas del Norte', 'Oceanía', NULL, NULL, NULL),
(581, 'UM', 'UMI', 0, 'Islas Ultramarinas de Estados Unidos', NULL, NULL, NULL, NULL),
(583, 'FM', 'FSM', 691, 'Micronesia', 'Oceanía', NULL, NULL, NULL),
(584, 'MH', 'MHL', 692, 'Islas Marshall', 'Oceanía', NULL, NULL, NULL),
(585, 'PW', 'PLW', 680, 'Palaos', 'Oceanía', NULL, NULL, NULL),
(586, 'PK', 'PAK', 92, 'Pakistán', 'Asia', NULL, 'PKR', 'Rupia pakistaní'),
(591, 'PA', 'PAN', 507, 'Panamá', 'América', 'América Central', 'PAB', 'Balboa panameña'),
(598, 'PG', 'PNG', 675, 'Papúa Nueva Guinea', 'Oceanía', NULL, 'PGK', 'Kina de Papúa Nueva Guinea'),
(600, 'PY', 'PRY', 595, 'Paraguay', 'América', 'América del Sur', 'PYG', 'Guaraní paraguayo'),
(604, 'PE', 'PER', 51, 'Perú', 'América', 'América del Sur', 'PEN', 'Nuevo sol peruano'),
(608, 'PH', 'PHL', 63, 'Filipinas', 'Asia', NULL, 'PHP', 'Peso filipino'),
(612, 'PN', 'PCN', 870, 'Islas Pitcairn', 'Oceanía', NULL, NULL, NULL),
(616, 'PL', 'POL', 48, 'Polonia', 'Europa', NULL, 'PLN', 'zloty polaco'),
(620, 'PT', 'PRT', 351, 'Portugal', 'Europa', NULL, 'EUR', 'Euro'),
(624, 'GW', 'GNB', 245, 'Guinea-Bissau', 'África', NULL, NULL, NULL),
(626, 'TL', 'TLS', 670, 'Timor Oriental', 'Asia', NULL, NULL, NULL),
(630, 'PR', 'PRI', 1, 'Puerto Rico', 'América', 'El Caribe', NULL, NULL),
(634, 'QA', 'QAT', 974, 'Qatar', 'Asia', NULL, 'QAR', 'Rial qatarí'),
(638, 'RE', 'REU', 262, 'Reunión', 'África', NULL, NULL, NULL),
(642, 'RO', 'ROU', 40, 'Rumania', 'Europa', NULL, 'RON', 'Leu rumano'),
(643, 'RU', 'RUS', 7, 'Rusia', 'Asia', NULL, 'RUB', 'Rublo ruso'),
(646, 'RW', 'RWA', 250, 'Ruanda', 'África', NULL, 'RWF', 'Franco ruandés'),
(654, 'SH', 'SHN', 290, 'Santa Helena', 'África', NULL, 'SHP', 'Libra de Santa Helena'),
(659, 'KN', 'KNA', 1869, 'San Cristóbal y Nieves', 'América', 'El Caribe', NULL, NULL),
(660, 'AI', 'AIA', 1264, 'Anguila', 'América', 'El Caribe', NULL, NULL),
(662, 'LC', 'LCA', 1758, 'Santa Lucía', 'América', 'El Caribe', NULL, NULL),
(666, 'PM', 'SPM', 508, 'San Pedro y Miquelón', 'América', 'América del Norte', NULL, NULL),
(670, 'VC', 'VCT', 1784, 'San Vicente y las Granadinas', 'América', 'El Caribe', NULL, NULL),
(674, 'SM', 'SMR', 378, 'San Marino', 'Europa', NULL, NULL, NULL),
(678, 'ST', 'STP', 239, 'Santo Tomé y Príncipe', 'África', NULL, 'STD', 'Dobra de Santo Tomé y Príncipe'),
(682, 'SA', 'SAU', 966, 'Arabia Saudí', 'Asia', NULL, 'SAR', 'Riyal saudí'),
(686, 'SN', 'SEN', 221, 'Senegal', 'África', NULL, NULL, NULL),
(688, 'RS', 'SRB', 381, 'Serbia', 'Europa', NULL, NULL, NULL),
(690, 'SC', 'SYC', 248, 'Seychelles', 'África', NULL, 'SCR', 'Rupia de Seychelles'),
(694, 'SL', 'SLE', 232, 'Sierra Leona', 'África', NULL, 'SLL', 'Leone de Sierra Leona'),
(702, 'SG', 'SGP', 65, 'Singapur', 'Asia', NULL, 'SGD', 'Dólar de Singapur'),
(703, 'SK', 'SVK', 421, 'Eslovaquia', 'Europa', NULL, 'SKK', 'Corona eslovaca'),
(704, 'VN', 'VNM', 84, 'Vietnam', 'Asia', NULL, 'VND', 'Dong vietnamita'),
(705, 'SI', 'SVN', 386, 'Eslovenia', 'Europa', NULL, NULL, NULL),
(706, 'SO', 'SOM', 252, 'Somalia', 'África', NULL, 'SOS', 'Chelín somalí'),
(710, 'ZA', 'ZAF', 27, 'Sudáfrica', 'África', NULL, 'ZAR', 'Rand sudafricano'),
(716, 'ZW', 'ZWE', 263, 'Zimbabue', 'África', NULL, 'ZWL', 'Dólar zimbabuense'),
(724, 'ES', 'ESP', 34, 'España', 'Europa', NULL, 'EUR', 'Euro'),
(732, 'EH', 'ESH', 0, 'Sahara Occidental', 'África', NULL, NULL, NULL),
(736, 'SD', 'SDN', 249, 'Sudán', 'África', NULL, 'SDD', 'Dinar sudanés'),
(740, 'SR', 'SUR', 597, 'Surinam', 'América', 'América del Sur', 'SRD', 'Dólar surinamés'),
(744, 'SJ', 'SJM', 0, 'Svalbard y Jan Mayen', 'Europa', NULL, NULL, NULL),
(748, 'SZ', 'SWZ', 268, 'Suazilandia', 'África', NULL, 'SZL', 'Lilangeni suazi'),
(752, 'SE', 'SWE', 46, 'Suecia', 'Europa', NULL, 'SEK', 'Corona sueca'),
(756, 'CH', 'CHE', 41, 'Suiza', 'Europa', NULL, 'CHF', 'Franco suizo'),
(760, 'SY', 'SYR', 963, 'Siria', 'Asia', NULL, 'SYP', 'Libra siria'),
(762, 'TJ', 'TJK', 992, 'Tayikistán', 'Asia', NULL, 'TJS', 'Somoni tayik (de Tayikistán)'),
(764, 'TH', 'THA', 66, 'Tailandia', 'Asia', NULL, 'THB', 'Baht tailandés'),
(768, 'TG', 'TGO', 228, 'Togo', 'África', NULL, NULL, NULL),
(772, 'TK', 'TKL', 690, 'Tokelau', 'Oceanía', NULL, NULL, NULL),
(776, 'TO', 'TON', 676, 'Tonga', 'Oceanía', NULL, 'TOP', 'Pa\'anga tongano'),
(780, 'TT', 'TTO', 1868, 'Trinidad y Tobago', 'América', 'El Caribe', 'TTD', 'Dólar de Trinidad y Tobago'),
(784, 'AE', 'ARE', 971, 'Emiratos Árabes Unidos', 'Asia', NULL, 'AED', 'Dirham de los Emiratos Árabes Unidos'),
(788, 'TN', 'TUN', 216, 'Túnez', 'África', NULL, 'TND', 'Dinar tunecino'),
(792, 'TR', 'TUR', 90, 'Turquía', 'Asia', NULL, 'TRY', 'Lira turca'),
(795, 'TM', 'TKM', 993, 'Turkmenistán', 'Asia', NULL, 'TMM', 'Manat turcomano'),
(796, 'TC', 'TCA', 1649, 'Islas Turcas y Caicos', 'América', 'El Caribe', NULL, NULL),
(798, 'TV', 'TUV', 688, 'Tuvalu', 'Oceanía', NULL, NULL, NULL),
(800, 'UG', 'UGA', 256, 'Uganda', 'África', NULL, 'UGX', 'Chelín ugandés'),
(804, 'UA', 'UKR', 380, 'Ucrania', 'Europa', NULL, 'UAH', 'Grivna ucraniana'),
(807, 'MK', 'MKD', 389, 'Macedonia', 'Europa', NULL, 'MKD', 'Denar macedonio'),
(818, 'EG', 'EGY', 20, 'Egipto', 'África', NULL, 'EGP', 'Libra egipcia'),
(826, 'GB', 'GBR', 44, 'Reino Unido', 'Europa', NULL, 'GBP', 'Libra esterlina (libra de Gran Bretaña)'),
(834, 'TZ', 'TZA', 255, 'Tanzania', 'África', NULL, 'TZS', 'Chelín tanzano'),
(840, 'US', 'USA', 1, 'Estados Unidos', 'América', 'América del Norte', 'USD', 'Dólar estadounidense'),
(850, 'VI', 'VIR', 1340, 'Islas Vírgenes de los Estados Unidos', 'América', 'El Caribe', NULL, NULL),
(854, 'BF', 'BFA', 226, 'Burkina Faso', 'África', NULL, NULL, NULL),
(858, 'UY', 'URY', 598, 'Uruguay', 'América', 'América del Sur', 'UYU', 'Peso uruguayo'),
(860, 'UZ', 'UZB', 998, 'Uzbekistán', 'Asia', NULL, 'UZS', 'Som uzbeko'),
(862, 'VE', 'VEN', 58, 'Venezuela', 'América', 'América del Sur', 'VEB', 'Bolívar venezolano'),
(876, 'WF', 'WLF', 681, 'Wallis y Futuna', 'Oceanía', NULL, NULL, NULL),
(882, 'WS', 'WSM', 685, 'Samoa', 'Oceanía', NULL, 'WST', 'Tala samoana'),
(887, 'YE', 'YEM', 967, 'Yemen', 'Asia', NULL, 'YER', 'Rial yemení (de Yemen)'),
(894, 'ZM', 'ZMB', 260, 'Zambia', 'África', NULL, 'ZMK', 'Kwacha zambiano');

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
('DEzHYjhvOA0lfw5sOAMwB9J84asjoHBxNRVxTgkB', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOE5SZXJPNjl4S1BkN294eHhucE41V25xaTZrSkprMEhFZGczUHk2eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc0OiJodHRwOi8vbG9jYWxob3N0L0RBV0VTL2xhcmF2ZWwvMlRyaW1lc3RyZS9hcHAtcHJveWVjdG8yL3B1YmxpYy9jbGllbnRlcy8xMyI7czo1OiJyb3V0ZSI7czoxMzoiY2xpZW50ZXMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzcxMzQ0NTMzO319', 1771347690);

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
  `estado` enum('B','P','R','C') DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
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
(2, 11, 'Cristian Reyes', '674738220', 3, 'Prueba laravel', 'cristianreyes@gmail.com', 'Av Santa Marta', 'Bollullos', '21710', '21', 'R', '2025-11-20 08:20:58', '2026-03-19', NULL, 'Esto es para probar el rol de administrador. AHORA ESTA MODIFICANDO EL OPERARIO', NULL),
(3, 15, 'HOLA', '987564201', 4, 'Probando a ver si el administrador puede crear una tarea', 'AAAAAAA@gmail.com', 'Calle Picos', 'Moguer', '21800', '21', 'C', '2025-11-27 08:30:27', '2026-02-25', NULL, 'Probando el funcionamiento de Completar tarea', NULL),
(4, 16, 'ADIOS', '624985011', 2, 'probando nuevo xampp', 'prueba@gmail.com', 'Calle Espejo, 12', 'Moguer', '21800', '21', 'R', '2026-01-03 18:28:01', '2026-02-13', 'xampp nuevo instalado', NULL, 'ficheros_tareas/4_team-working.jpg'),
(5, 14, 'Magdalena Garcia', '987654321', 4, 'foerkpwj', 'magda@gmail.com', 'Calle Ribera, 34 B', 'Huelva', '21004', '21', 'C', '2025-11-24 23:54:29', '2026-03-14', NULL, 'regvergv', NULL),
(6, 13, 'Veronica Diaz', '610047235', 3, 'poerjgpaerjgfvp', 'vero45@gmail.com', 'C/Dario Murillo, 34', 'sevilla', '41014', '41', 'P', '2025-11-24 22:07:34', '2026-02-20', NULL, 'fergverbvetbte', NULL),
(7, 12, 'jesus reyes', '788 09 87 34', 2, 'Aplicacion 2ª evaluacion laravel', 'jesusr6@gmail.com', NULL, NULL, '21005', '21', 'B', '2026-01-21 12:08:39', '2026-03-05', NULL, NULL, NULL),
(9, 14, 'juan pérez', '987564201', 2, 'Asignando tareas a operarios', 'AAAAAAA@gmail.com', 'Calle Falsa 123', 'Camas', '41014', '41', 'P', '2026-02-04 22:18:42', '2026-02-23', NULL, NULL, NULL),
(10, 13, 'Antonio Murillo', '987564201', 4, 'Cambios importantes de Empleados a Users', 'correo@gmail.com', 'Av Santa Marta', 'moguer', '21800', '21', 'P', '2026-02-06 23:18:01', '2026-03-06', NULL, NULL, NULL),
(11, 12, 'Laura Castillo', '745 200 341', 2, 'Probar validaciones de los campos de la creación de tareas', 'pepe001@gmail.com', 'Calle Vallejo, 3', 'Valverde del Camino', '21600', '21', 'P', '2026-02-13 11:51:59', '2026-04-09', NULL, NULL, NULL);

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
(1, 'admin', 'admin123@gmail.com', NULL, '$2y$12$nQ3ZHBePyTTnLERaWUyZBOSClEsWEw3Tdxbt3Y92Q1Bv6xYIFuz0.', '3JiKLNM9iiQwCEoEB4lZpv0rGhPHlngynUXGxpIbCsQNMKjW33Hb3h9tdVxY', '2026-02-06 20:12:19', '2026-02-16 16:22:21', '73504055B', '644789336', 'Calle Galaroza, Bloque 3, 3A', '2026-02-03', NULL, 'administrador'),
(2, 'operario1', 'operario1@gmail.com', NULL, '$2y$12$bBDoN2HXIqKeeSkZxd4lMeInu6ct7V9GXjLHN5JS/zMFslcymtyRC', NULL, '2026-02-06 20:12:58', '2026-02-13 08:26:01', '70907286B', '778023544', 'Calle San Marino, Puerta 5A', '2026-02-04', NULL, 'operario'),
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
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iso2` (`iso2`),
  ADD UNIQUE KEY `iso3` (`iso3`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
