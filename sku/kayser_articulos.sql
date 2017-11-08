-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2017 a las 15:05:07
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kayser_articulos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`id`, `nombre`) VALUES
(1, 'ACERO'),
(2, 'ALMENDRA'),
(3, 'AMARILLO'),
(4, 'AMARILLO/ROJO'),
(5, 'ANIMAL PRINT'),
(6, 'ANIMAL PRINT/GRIS'),
(7, 'AQUA'),
(8, 'AZUL'),
(9, 'AZUL REY'),
(10, 'AZUL/FLUOR'),
(11, 'AZUL/GRIS'),
(12, 'AZUL/PETROLEO'),
(13, 'AZUL/ROSADO'),
(14, 'AZUL/VERDE'),
(15, 'AZULINO'),
(16, 'BARQUILLO'),
(17, 'BEIGE'),
(18, 'BERENJENA'),
(19, 'BERRY'),
(20, 'BLANCO'),
(21, 'BLANCO/AZUL'),
(22, 'BLANCO/FUCSIA/CALIPSO'),
(23, 'BLANCO/GRIS'),
(24, 'BLANCO/GRIS/NEGRO'),
(25, 'BLANCO/NEGRO'),
(26, 'BLANCO/NEGRO/ROSADO'),
(27, 'BLUE'),
(28, 'BURDEO'),
(29, 'CAFÉ'),
(30, 'CALIPSO'),
(31, 'CALIPSO/FUCSIA'),
(32, 'CALIPSO/MORADO'),
(33, 'CALIPSO/ROJO'),
(34, 'CALIPSO/TURQUESA'),
(35, 'CALIPSO/VERDE/PETROLEO'),
(36, 'CARBON'),
(37, 'CARMIN'),
(38, 'CELESTE'),
(39, 'CEREZA'),
(40, 'CHOCOLATE'),
(41, 'COGÑAC'),
(42, 'COLOR 1'),
(43, 'COLOR 2'),
(44, 'COLOR 3'),
(45, 'COLOR 4'),
(46, 'COM1'),
(47, 'COM2'),
(48, 'COM3'),
(49, 'COM4'),
(50, 'COM5'),
(51, 'CORAL'),
(52, 'CREMA'),
(53, 'DAMASCO'),
(54, 'DEG'),
(55, 'DISEÑO'),
(56, 'ESMERALDA'),
(57, 'ESMERALDA/GRIS'),
(58, 'ESTAMPADO'),
(59, 'ETNICO'),
(60, 'ETNICO MORADO'),
(61, 'ETNICO NEGRO'),
(62, 'FLOR'),
(63, 'FLOR LILA'),
(64, 'FLOR MORADA'),
(65, 'FLOR MORADO'),
(66, 'FLUOR'),
(67, 'FRESA'),
(68, 'FRUTILLA'),
(69, 'FRUTILLA/AZUL'),
(70, 'FUCSIA'),
(71, 'GRAFITO'),
(72, 'GREEN'),
(73, 'GREY'),
(74, 'GRIS'),
(75, 'GRIS/BLANCO'),
(76, 'GRIS/CALIPSO'),
(77, 'GUINDA'),
(78, 'JEANS'),
(79, 'LAVANDA'),
(80, 'LILA'),
(81, 'LIMA'),
(82, 'LISO'),
(83, 'LUCUMA'),
(84, 'LUNARES'),
(85, 'LUNARES ROJOS'),
(86, 'MAGENTA'),
(87, 'MALVA'),
(88, 'MARENGO'),
(89, 'MARFIL'),
(90, 'MARRON'),
(91, 'MELON'),
(92, 'MENTA'),
(93, 'MOCA'),
(94, 'MORA'),
(95, 'MORADO'),
(96, 'MORADO/AZUL'),
(97, 'MORADO/NEGRO'),
(98, 'MORADO/VERDE'),
(99, 'MOSTAZA'),
(100, 'NARANJA'),
(101, 'NARANJO'),
(102, 'NATURAL'),
(103, 'NAVY'),
(104, 'NEGRO'),
(105, 'NEGRO/BLANCO'),
(106, 'NEGRO/CALIPSO'),
(107, 'NEGRO/CELESTE'),
(108, 'NEGRO/FLUOR'),
(109, 'NEGRO/GRAFITO'),
(110, 'NEGRO/NARANJO'),
(111, 'NEGRO/ROJO'),
(112, 'NEGRO/ROSADO'),
(113, 'NEGRO/VIOLETA'),
(114, 'NUDE'),
(115, 'OBISPO'),
(116, 'ORANGE'),
(117, 'PACK1'),
(118, 'PACK2'),
(119, 'PACK3'),
(120, 'PALO ROSA'),
(121, 'PARTY'),
(122, 'PERA'),
(123, 'PETROLEO'),
(124, 'PETROLEO/GRIS'),
(125, 'PIEL'),
(126, 'PINK'),
(127, 'PISTACHO'),
(128, 'PLOMO'),
(129, 'PRINT'),
(130, 'PRINT AZUL'),
(131, 'PUNTOS MORA'),
(132, 'PUNTOS MORADOS'),
(133, 'PURPLE'),
(134, 'PURPURA'),
(135, 'RAYON'),
(136, 'RED'),
(137, 'RICH'),
(138, 'ROJO'),
(139, 'ROJO/MORADO'),
(140, 'ROJO/NEGRO'),
(141, 'ROSA'),
(142, 'ROSADO'),
(143, 'ROSADO/AZUL'),
(144, 'ROYAL'),
(145, 'RUBI'),
(146, 'S/C'),
(147, 'SAFIRO'),
(148, 'SANDIA'),
(149, 'SUN'),
(150, 'SURTIDO'),
(151, 'TAUPE'),
(152, 'TERRACOTA'),
(153, 'TOSTADO'),
(154, 'TURQUESA'),
(155, 'UVA'),
(156, 'VERDE'),
(157, 'VIOLETA'),
(158, 'VIOLETA/NAVY'),
(159, 'VISON'),
(160, 'WARMRED'),
(161, 'WHITE'),
(162, 'ZEBRA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composicion`
--

CREATE TABLE `composicion` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(85) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `composicion`
--

INSERT INTO `composicion` (`id`, `nombre`) VALUES
(1, '100% ACRILICO'),
(2, '100% ALGODON'),
(3, '100% NYLON'),
(4, '100% POLIESTER'),
(5, '100% POLYAMIDA'),
(6, '100% SILICONA'),
(7, '100% SPANDEX'),
(8, '40% ALGODON 55% POLIESTER 5% ELASTANO'),
(9, '40% POLYAMIDA 40% POLIESTER 20%ELASTANO'),
(10, '48% ALGODON 47% POLIESTER 5% SPANDEX'),
(11, '50% ALGODON 50% POLIESTER'),
(12, '50% POLIESTER 50% POLYAMIDA'),
(13, '60% ALGODON 40% ELASTANO'),
(14, '60% ALGODON 40% POLIESTER'),
(15, '60% ALGODON 40% POLYAMIDA'),
(16, '60% POLIESTER 40% ALGODON'),
(17, '63% POLIESTER 32% ALGODON 5% ELASTANO'),
(18, '65% ALGODON 35% POLIESTER'),
(19, '65% POLIESTER 35% ALGODON'),
(20, '66% ALGODON 27% POLIESTER 7% ELASTANO'),
(21, '70% ACRILICO 25% POLIETER 5%ELASTANO'),
(22, '70% ALGODON 20% POLIESTER 10% ESLASTANO'),
(23, '70% POLIESTER 20% NYLON 10% ELASTANO'),
(24, '70% POLYAMIDA 20% POLIESTER 10% ELASTANO'),
(25, '75% ALGODON 22% POLIESTER 3% ELASTANO'),
(26, '75% ALGODON 22% POLIESTER 3% SPANDEX'),
(27, '75% ALGODON 22% POLYAMIDA 3% ELASTANO'),
(28, '75% ALGODON 23% POLYAMIDA 2% ELASTANO'),
(29, '75% ALGODON 24% POLIESTER 1% ELASTANO'),
(30, '75% ALGODON 25% POLIESTER'),
(31, '75% BAMBOO 25 %POLYAMIDA'),
(32, '75% BAMBOO 25% POLIESTER'),
(33, '75% POLYAMIDA 25% ELASTANO'),
(34, '78% NYLON 15% ELASTANO 7% ALGODON'),
(35, '78% POLIAMIDA 12% POLIESTER 10% ELASTANO'),
(36, '78% POLYAMIDA 12% POLIE STER 10% ELASTANO'),
(37, '80% ALGODON 10% POLYAMIDA 10% ELASTANO'),
(38, '80% ALGODON 15% POLIESTER 5% ELASTANO'),
(39, '80% ALGODON 15% POLYAMIDA 5% ELASTANO'),
(40, '80% ALGODON 18% POLIESTER 2% ELASTANO'),
(41, '80% ALGODON 20% POLIESTER'),
(42, '80% NYLON 20% ELASTANO'),
(43, '80% NYLON 20% SPANDEX'),
(44, '80% POLIESTER 10% ALGODON 10% ELASTANO'),
(45, '80% POLYAMIDA 10% ALGODON 10% ELASTANO'),
(46, '80% POLYAMIDA 10% POLIESTR 10% ELASTANO'),
(47, '80% POLYAMIDA 20% ELASTANO'),
(48, '80% POLIESTER 20% ELASTANO'),
(49, '82% POLYAMIDA 18% ELASTANO'),
(50, '82% POLIESTER 18% ELASTANO'),
(51, '83% POLIESTER 17% ALGODON'),
(52, '83% POLYAMIDA 17% ELASTANO'),
(53, '85% NYLON 15% SPANDEX'),
(54, '85% POLIESTER 10% ELASTANO 5% ALGODON'),
(55, '85% POLYAMIDA 10% ELASTANO 5% ALGODON'),
(56, '85% POLIESTER 15% ELASTANO'),
(57, '85% POLYAMIDA 15% ELASTANO'),
(58, '86% NYLON 14% ELASTANO'),
(59, '86% NYLON 14% SPANDEX'),
(60, '86% POLYAMIDA 14% ELASTANO'),
(61, '87% POLYAMIDA 13% ELASTANO'),
(62, '88% POLIESTER 12% ELASTANO'),
(63, '88% POLYAMIDA 12% ELASTANO'),
(64, '90% ALGODON 10% ELASTANO'),
(65, '90% POLIESTER 10% ELASTANO'),
(66, '90% POLYAMIDA 10% ELASTANO'),
(67, '92% ALGODON 8% ELASTANO'),
(68, '92% ALGODON 8% SPANDEX'),
(69, '92% NYLON 8% SPANDEX'),
(70, '92% POLIESTER 8% ELASTANO'),
(71, '92% POLIESTER 8% SPANDEX'),
(72, '92% POLYAMIDA 8% ELASTANO'),
(73, '93% ALGODON 7% ELASTANO'),
(74, '93% POLIESTER 7% ELASTANO'),
(75, '93% POLYAMIDA 7% ELASTANO'),
(76, '94% POLIESTER 6% ELASTANO'),
(77, '94% POLYAMIDA 6% ELASTANO'),
(78, '95% ALGODON 5% ELASTANO'),
(79, '95% ALGODON 5% POLYAMIDA'),
(80, '95% BAMBOO 5% ELASTANO'),
(81, '95% COTTON 5% ELASTANO'),
(82, '95% POLIESTER 5% ELASTANO'),
(83, '95% POLYAMIDA 5% ELASTANO'),
(84, '95% POLYAMIDA 5% SPANDEX'),
(85, '97% POLIESTER 3% ELASTANO'),
(86, '98% ALGODON 5% ELASTANO'),
(87, '98% POLIESTER 2% ELASTANO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `copa`
--

CREATE TABLE `copa` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `copa`
--

INSERT INTO `copa` (`id`, `nombre`) VALUES
(2, 'A'),
(4, 'B'),
(3, 'C'),
(5, 'D'),
(6, 'DD'),
(1, 'S/C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalletalla`
--

CREATE TABLE `detalletalla` (
  `Talla_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `orden` smallint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalletalla`
--

INSERT INTO `detalletalla` (`Talla_codigo`, `nombre`, `orden`) VALUES
('T01', '00', 1),
('T01', '01', 2),
('T01', '02', 3),
('T01', '03', 4),
('T01', '04', 5),
('T01', '05', 6),
('T02', '0/3', 1),
('T02', '10/12', 6),
('T02', '12/18', 7),
('T02', '14/16', 8),
('T02', '18/24', 9),
('T02', '2/4', 10),
('T02', '3/6', 2),
('T02', '6/8', 3),
('T02', '6/9', 4),
('T02', '9/12', 5),
('T03', 'L', 3),
('T03', 'M', 2),
('T03', 'S', 1),
('T03', 'XL', 4),
('T03', 'XS', 7),
('T03', 'XXL', 5),
('T03', 'XXXL', 6),
('T04', '2', 1),
('T04', '3', 2),
('T04', '5', 10),
('T05', '32', 1),
('T05', '34', 2),
('T05', '36', 3),
('T05', '38', 4),
('T05', '40', 5),
('T05', '42', 6),
('T05', '44', 7),
('T06', '52', 1),
('T06', '54', 2),
('T06', '56', 3),
('T06', 'L', 4),
('T06', 'M', 5),
('T06', 'S', 6),
('T06', 'XL', 7),
('T06', 'XXL', 8),
('T07', 'UNI', 1),
('T17', '27/28', 1),
('T17', '29/30', 2),
('T17', '31/32', 3),
('T17', '33/34', 4),
('T17', '35/36', 5),
('T17', '37/38', 6),
('T17', '39/40', 7),
('T17', '41/42', 8),
('T17', '43/44', 9),
('T17', '45/46', 10),
('T24', '28A', 1),
('T24', '30A', 2),
('T24', '32A', 3),
('T24', '34A', 4),
('T30', '10', 6),
('T30', '12', 7),
('T30', '14', 8),
('T30', '16', 9),
('T30', '4', 3),
('T30', '6', 4),
('T30', '8', 5),
('T32', '39/42', 1),
('T32', '43/46', 2),
('T33', '30/34', 1),
('T33', '35/37', 2),
('T34', '30/33', 1),
('T34', '34/36', 2),
('T34', '37/39', 3),
('T34', '40/43', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpto_prenda`
--

CREATE TABLE `dpto_prenda` (
  `Dpto_codigo` smallint(4) NOT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dpto_prenda`
--

INSERT INTO `dpto_prenda` (`Dpto_codigo`, `Prenda_codigo`) VALUES
(103, '22'),
(105, '31'),
(106, '01'),
(106, '02'),
(106, '03'),
(106, '06'),
(106, '08'),
(106, '09'),
(106, '12'),
(106, '13'),
(106, '14'),
(106, '16'),
(106, '17'),
(106, '18'),
(106, '21'),
(106, '22'),
(106, '25'),
(106, '34'),
(106, '35'),
(106, '36'),
(106, '37'),
(106, '38'),
(106, '40'),
(106, '41'),
(106, '42'),
(106, '43'),
(106, '46'),
(106, '48'),
(106, '50'),
(108, '03'),
(108, '07'),
(108, '08'),
(108, '10'),
(108, '11'),
(108, '12'),
(108, '22'),
(108, '26'),
(127, '03'),
(127, '08'),
(127, '09'),
(127, '12'),
(127, '22'),
(127, '25'),
(127, '42'),
(128, '03'),
(128, '07'),
(128, '08'),
(128, '10'),
(128, '12'),
(128, '22'),
(129, '08'),
(129, '09'),
(129, '12'),
(129, '22'),
(129, '42'),
(130, '07'),
(130, '08'),
(130, '10'),
(130, '12'),
(130, '22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dpto_subdpto`
--

CREATE TABLE `dpto_subdpto` (
  `Dpto_codigo` smallint(4) NOT NULL,
  `Subdpto_id` smallint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dpto_subdpto`
--

INSERT INTO `dpto_subdpto` (`Dpto_codigo`, `Subdpto_id`) VALUES
(103, 8),
(105, 4),
(106, 1),
(106, 2),
(106, 3),
(106, 5),
(106, 6),
(106, 8),
(106, 9),
(106, 10),
(106, 11),
(106, 13),
(106, 14),
(106, 15),
(106, 16),
(106, 18),
(108, 3),
(108, 8),
(108, 13),
(108, 17),
(127, 3),
(127, 5),
(127, 8),
(128, 3),
(128, 8),
(128, 17),
(129, 3),
(129, 5),
(129, 8),
(130, 3),
(130, 8),
(130, 17),
(145, 3),
(147, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacopa`
--

CREATE TABLE `formacopa` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `formacopa`
--

INSERT INTO `formacopa` (`id`, `nombre`) VALUES
(3, 'BALCONET'),
(4, 'BICASCO'),
(5, 'COBERTURA COMPLETA'),
(2, 'COPA S'),
(7, 'MAXI COPA'),
(9, 'SIN ARCO'),
(1, 'SIN COPA'),
(8, 'STRAPLESS'),
(6, 'TRICASCO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupouso`
--

CREATE TABLE `grupouso` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `grupouso`
--

INSERT INTO `grupouso` (`id`, `nombre`) VALUES
(1, 'NIÑOS'),
(2, 'SEÑORA'),
(3, 'MUJER'),
(4, 'JUVENIL'),
(5, 'ADULTO'),
(6, 'HOGAR'),
(7, 'PROMOTORA'),
(8, 'INSUMOS'),
(9, 'ESCOLAR'),
(10, 'OUTLET'),
(11, 'INFANTIL'),
(12, 'EXHIBIDOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `prefijo` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `nombre`, `prefijo`) VALUES
(1, 'KAYSER', ''),
(2, 'SIMPSONS', 'S'),
(3, 'KAYSER SUMMER', ''),
(4, 'BETTY BOOP', ''),
(5, 'HANNA BARBERA', ''),
(6, 'SENS', 'SE'),
(7, 'DISNEY', 'D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id`, `nombre`) VALUES
(1, 'ACRILICO'),
(2, 'ALGODON'),
(3, 'BAMBOO'),
(4, 'CARTON'),
(5, 'CATALOGO'),
(6, 'CORAL FLEECE'),
(7, 'CREMA'),
(8, 'ENCAJE'),
(9, 'FRAGANCIA'),
(10, 'FRANELA'),
(11, 'JACQUARD'),
(12, 'LIQUIDACION'),
(13, 'MICROFIBRA'),
(14, 'MODAL'),
(15, 'PLASTICO'),
(16, 'PLUSH'),
(17, 'POLAR'),
(18, 'POLIAMIDA'),
(19, 'POLIESTER'),
(20, 'PUÑO'),
(21, 'PVC'),
(22, 'SATIN'),
(23, 'SIN COSTURA'),
(24, 'STOCK LOT'),
(25, 'TOALLA'),
(26, 'TREVIRA'),
(27, 'TULL'),
(28, 'VISCOSA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prenda_categoria`
--

CREATE TABLE `prenda_categoria` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Categoria_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `prenda_categoria`
--

INSERT INTO `prenda_categoria` (`Prenda_codigo`, `Categoria_codigo`) VALUES
('01', '74'),
('02', '26'),
('02', '29'),
('02', '67'),
('03', '05'),
('03', '06'),
('06', '31'),
('06', '70'),
('07', '05'),
('07', '06'),
('08', '05'),
('08', '06'),
('08', '07'),
('09', '03'),
('09', '08'),
('09', '19'),
('09', '24'),
('09', '37'),
('09', '45'),
('09', '54'),
('09', '55'),
('09', '62'),
('10', '55'),
('10', '56'),
('10', '79'),
('11', '06'),
('12', '51'),
('12', '52'),
('12', '68'),
('13', '28'),
('13', '30'),
('14', '34'),
('15', '44'),
('16', '42'),
('17', '22'),
('17', '40'),
('18', '66'),
('21', '39'),
('21', '49'),
('22', '01'),
('22', '04'),
('22', '20'),
('22', '41'),
('25', '02'),
('25', '03'),
('25', '07'),
('25', '14'),
('25', '33'),
('25', '38'),
('25', '54'),
('25', '57'),
('25', '60'),
('25', '63'),
('25', '65'),
('25', '69'),
('25', '72'),
('25', '73'),
('26', '78'),
('28', '77'),
('31', '09'),
('31', '21'),
('33', '19'),
('33', '32'),
('33', '71'),
('33', '75'),
('34', '18'),
('34', '46'),
('35', '74'),
('36', '47'),
('37', '39'),
('37', '76'),
('38', '43'),
('39', '06'),
('40', '15'),
('41', '51'),
('41', '52'),
('42', '26'),
('42', '51'),
('42', '52'),
('43', '27'),
('45', '35'),
('46', '36'),
('47', '09'),
('47', '10'),
('47', '11'),
('47', '12'),
('47', '13'),
('47', '16'),
('47', '17'),
('47', '25'),
('47', '50'),
('47', '53'),
('47', '58'),
('47', '59'),
('48', '48'),
('49', '61'),
('50', '64');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prenda_copa`
--

CREATE TABLE `prenda_copa` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Copa_id` tinyint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `prenda_copa`
--

INSERT INTO `prenda_copa` (`Prenda_codigo`, `Copa_id`) VALUES
('13', 3),
('25', 1),
('25', 2),
('25', 3),
('25', 4),
('25', 5),
('25', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prenda_formacopa`
--

CREATE TABLE `prenda_formacopa` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `FormaCopa_id` tinyint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `prenda_formacopa`
--

INSERT INTO `prenda_formacopa` (`Prenda_codigo`, `FormaCopa_id`) VALUES
('25', 1),
('25', 2),
('25', 3),
('25', 4),
('25', 5),
('25', 6),
('25', 7),
('25', 8),
('25', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prenda_talla`
--

CREATE TABLE `prenda_talla` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Talla_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `prenda_talla`
--

INSERT INTO `prenda_talla` (`Prenda_codigo`, `Talla_codigo`) VALUES
('01', 'T07'),
('02', 'T03'),
('02', 'T07'),
('03', 'T02'),
('03', 'T03'),
('06', 'T03'),
('07', 'T03'),
('07', 'T07'),
('07', 'T30'),
('08', 'T04'),
('08', 'T07'),
('08', 'T17'),
('08', 'T32'),
('08', 'T33'),
('08', 'T34'),
('09', 'T03'),
('09', 'T06'),
('09', 'T07'),
('09', 'T30'),
('10', 'T03'),
('10', 'T30'),
('11', 'T03'),
('11', 'T30'),
('12', 'T03'),
('12', 'T07'),
('12', 'T30'),
('13', 'T03'),
('13', 'T05'),
('14', 'T03'),
('14', 'T05'),
('15', 'T07'),
('16', 'T07'),
('17', 'T03'),
('17', 'T06'),
('18', 'T07'),
('20', 'T07'),
('21', 'T03'),
('21', 'T04'),
('21', 'T07'),
('22', 'T02'),
('22', 'T03'),
('22', 'T07'),
('22', 'T30'),
('25', 'T03'),
('25', 'T05'),
('25', 'T07'),
('25', 'T24'),
('25', 'T30'),
('26', 'T17'),
('28', 'T07'),
('31', 'T07'),
('32', 'T07'),
('32', 'T17'),
('32', 'T24'),
('32', 'T30'),
('33', 'T03'),
('34', 'T03'),
('34', 'T07'),
('35', 'T07'),
('36', 'T07'),
('37', 'T07'),
('38', 'T07'),
('39', 'T33'),
('39', 'T34'),
('40', 'T03'),
('41', 'T03'),
('41', 'T06'),
('41', 'T07'),
('42', 'T03'),
('42', 'T06'),
('42', 'T30'),
('43', 'T03'),
('45', 'T07'),
('46', 'T07'),
('47', 'T03'),
('47', 'T05'),
('47', 'T07'),
('48', 'T07'),
('49', 'T03'),
('49', 'T07'),
('50', 'T03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id` tinyint(4) NOT NULL,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id`, `nombre`) VALUES
(1, 'UNITARIO'),
(2, 'BIPACK'),
(3, 'TRIPACK'),
(4, 'CATALOGO'),
(5, 'PROMOCION'),
(6, 'PACK 5'),
(7, 'PACK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacionprefijo`
--

CREATE TABLE `relacionprefijo` (
  `id` int(10) UNSIGNED NOT NULL,
  `Dpto_codigo` smallint(4) UNSIGNED NOT NULL,
  `SubDpto_id` smallint(5) UNSIGNED DEFAULT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Categoria_codigo` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Presentacion_id` tinyint(4) UNSIGNED DEFAULT NULL,
  `prefijo` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `relacionprefijo`
--

INSERT INTO `relacionprefijo` (`id`, `Dpto_codigo`, `SubDpto_id`, `Prenda_codigo`, `Categoria_codigo`, `Presentacion_id`, `prefijo`) VALUES
(1, 103, NULL, '22', '', NULL, '00'),
(2, 105, NULL, '31', '', NULL, 'CATA'),
(3, 106, NULL, '01', '', NULL, 'AC'),
(4, 106, NULL, '02', '', NULL, '73'),
(5, 106, NULL, '03', '', NULL, '78'),
(6, 106, NULL, '06', '', NULL, '150'),
(7, 106, NULL, '08', '', NULL, '99M'),
(8, 106, NULL, '09', '55', NULL, '10'),
(9, 106, NULL, '09', '45', NULL, '11'),
(10, 106, NULL, '09', '24', NULL, '12'),
(11, 106, NULL, '09', '08', NULL, '13'),
(12, 106, NULL, '09', '62', NULL, '14'),
(13, 106, 11, '09', '', NULL, '118'),
(14, 106, NULL, '12', '52', NULL, '40'),
(15, 106, NULL, '12', '68', NULL, '41'),
(16, 106, 9, '12', '', NULL, '141'),
(17, 106, NULL, '13', '', NULL, '180'),
(18, 106, NULL, '14', '', NULL, '32'),
(19, 106, NULL, '16', '', NULL, 'AC'),
(20, 106, NULL, '17', '', NULL, '19'),
(21, 106, NULL, '18', '', NULL, 'AC'),
(22, 106, NULL, '21', '', NULL, '101'),
(23, 106, NULL, '22', '04', NULL, '70'),
(24, 106, NULL, '22', '01', NULL, '60'),
(25, 106, NULL, '25', '', NULL, '50'),
(26, 106, 9, '25', '', NULL, '150'),
(27, 106, 14, '34', '', NULL, '101'),
(28, 106, NULL, '35', '', NULL, 'AC'),
(29, 106, NULL, '36', '', NULL, 'AC'),
(30, 106, NULL, '37', '', NULL, 'AC'),
(31, 106, NULL, '38', '', NULL, 'AC'),
(32, 106, NULL, '40', '', NULL, '56'),
(33, 106, NULL, '41', '52', NULL, '61'),
(34, 106, NULL, '41', '51', NULL, '71'),
(35, 106, NULL, '42', '52', NULL, '61'),
(36, 106, NULL, '42', '51', NULL, '71'),
(37, 106, NULL, '43', '', NULL, '160'),
(38, 106, NULL, '46', '', NULL, 'AC'),
(39, 106, NULL, '48', '', NULL, 'AC'),
(40, 106, NULL, '50', '', NULL, '52'),
(41, 108, NULL, '03', '', NULL, '79'),
(42, 108, NULL, '07', '', NULL, '93'),
(43, 108, NULL, '08', '', NULL, '99H'),
(44, 108, NULL, '10', '', NULL, '91'),
(45, 108, NULL, '11', '', NULL, '98'),
(46, 108, NULL, '12', '', NULL, '40'),
(47, 108, NULL, '22', '', NULL, '67'),
(48, 108, NULL, '26', '', NULL, 'ZH'),
(49, 127, NULL, '03', '', NULL, '69'),
(50, 127, NULL, '08', '', NULL, '99NP'),
(51, 127, NULL, '09', '08', NULL, '16'),
(52, 127, NULL, '09', '62', NULL, '15'),
(53, 127, NULL, '12', '', NULL, '45'),
(54, 127, NULL, '22', '01', NULL, '65'),
(55, 127, NULL, '22', '04', NULL, '75'),
(56, 127, NULL, '25', '', NULL, '51'),
(57, 127, NULL, '25', '63', NULL, '25'),
(58, 127, NULL, '42', '', NULL, '75'),
(59, 128, NULL, '03', '', NULL, '69'),
(60, 128, NULL, '07', '', NULL, '97'),
(61, 128, NULL, '08', '', NULL, '99NP'),
(62, 128, NULL, '10', '', NULL, '95'),
(63, 128, NULL, '12', '', NULL, '45'),
(64, 128, NULL, '22', '01', NULL, '66'),
(65, 128, NULL, '22', '04', NULL, '76'),
(66, 129, NULL, '08', '', NULL, '99NP'),
(67, 129, NULL, '09', '', NULL, '17'),
(68, 129, NULL, '12', '', NULL, '47'),
(69, 129, NULL, '22', '04', NULL, '73'),
(70, 129, NULL, '22', '01', NULL, '63'),
(71, 129, NULL, '42', '', NULL, '73'),
(72, 130, NULL, '07', '', NULL, '97'),
(73, 130, NULL, '08', '', NULL, '99NP'),
(74, 130, NULL, '10', '', NULL, '97'),
(75, 130, NULL, '12', '', NULL, '47'),
(76, 130, NULL, '22', '04', NULL, '74'),
(77, 130, NULL, '22', '01', NULL, '64');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skucreated`
--

CREATE TABLE `skucreated` (
  `Sku_codigo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `Usuario_username` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skutemp`
--

CREATE TABLE `skutemp` (
  `codigo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `articulo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `barcode` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Dpto_codigo` smallint(4) NOT NULL,
  `Marca_nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Subdpto_id` smallint(5) DEFAULT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Categoria_codigo` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Presentacion_id` tinyint(4) DEFAULT NULL,
  `Material_nombre` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Color_nombre` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Talla_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Copa_nombre` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `FormaCopa_nombre` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `TempPrenda_nombre` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `TempCatalogo_nombre` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `GrupoUso_nombre` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `caracteristicas` text COLLATE utf8_spanish_ci,
  `composicion` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `peso` smallint(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skuupdated`
--

CREATE TABLE `skuupdated` (
  `id` int(11) NOT NULL,
  `Sku_codigo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `campo` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `previos_value` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `new_value` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario_username` varchar(25) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subdpto`
--

CREATE TABLE `subdpto` (
  `id` smallint(5) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `subdpto`
--

INSERT INTO `subdpto` (`id`, `nombre`) VALUES
(1, 'ACCESORIOS'),
(2, 'BABY DOLL'),
(3, 'CALCETINES'),
(4, 'CATALOGO'),
(5, 'CORSETERIA'),
(6, 'ENAGUAS'),
(7, 'INSUMOS'),
(8, 'LENCERIA'),
(9, 'LINEA CONTROL'),
(10, 'LINEA MODELADORA'),
(11, 'MATERNAL'),
(12, 'OUTLET'),
(13, 'PANTUFLAS'),
(14, 'PANTYS'),
(15, 'PERFUMERIA'),
(16, 'ROPA DEPORTIVA'),
(17, 'ROPA INTERIOR'),
(18, 'TRAJE DE BAÑO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subdpto_prenda`
--

CREATE TABLE `subdpto_prenda` (
  `Subdpto_id` smallint(5) NOT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `subdpto_prenda`
--

INSERT INTO `subdpto_prenda` (`Subdpto_id`, `Prenda_codigo`) VALUES
(1, '01'),
(1, '18'),
(1, '25'),
(1, '28'),
(1, '35'),
(1, '36'),
(1, '37'),
(1, '38'),
(1, '46'),
(2, '02'),
(3, '08'),
(3, '39'),
(4, '31'),
(5, '06'),
(5, '09'),
(5, '14'),
(5, '25'),
(6, '17'),
(7, '47'),
(8, '03'),
(8, '22'),
(8, '41'),
(8, '42'),
(9, '06'),
(9, '13'),
(9, '25'),
(9, '43'),
(10, '09'),
(11, '09'),
(11, '25'),
(12, '49'),
(13, '26'),
(14, '21'),
(14, '34'),
(15, '15'),
(15, '16'),
(15, '45'),
(15, '48'),
(16, '34'),
(16, '40'),
(16, '50'),
(17, '07'),
(17, '10'),
(17, '11'),
(18, '33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE `talla` (
  `codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`codigo`) VALUES
('T01'),
('T02'),
('T03'),
('T04'),
('T05'),
('T06'),
('T07'),
('T17'),
('T24'),
('T30'),
('T32'),
('T33'),
('T34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tempcatalogo`
--

CREATE TABLE `tempcatalogo` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tempcatalogo`
--

INSERT INTO `tempcatalogo` (`id`, `nombre`) VALUES
(1, 'DESCONTINUADO'),
(2, 'EVD'),
(3, 'EVD/COL'),
(22, 'INV17'),
(23, 'INV18'),
(12, 'LIMAS'),
(13, 'OUTLET'),
(14, 'STOCK LOT'),
(20, 'VER17'),
(21, 'VER18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tempprenda`
--

CREATE TABLE `tempprenda` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tempprenda`
--

INSERT INTO `tempprenda` (`id`, `nombre`) VALUES
(1, 'INVIERNO'),
(3, 'TODA TEMPORADA'),
(2, 'VERANO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `composicion`
--
ALTER TABLE `composicion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `copa`
--
ALTER TABLE `copa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `detalletalla`
--
ALTER TABLE `detalletalla`
  ADD PRIMARY KEY (`Talla_codigo`,`nombre`),
  ADD KEY `fk_DetalleTalla_Talla1_idx` (`Talla_codigo`);

--
-- Indices de la tabla `dpto_prenda`
--
ALTER TABLE `dpto_prenda`
  ADD PRIMARY KEY (`Dpto_codigo`,`Prenda_codigo`);

--
-- Indices de la tabla `dpto_subdpto`
--
ALTER TABLE `dpto_subdpto`
  ADD PRIMARY KEY (`Dpto_codigo`,`Subdpto_id`),
  ADD KEY `fk_Dpto_SubDpto_Subdpto1_idx` (`Subdpto_id`);

--
-- Indices de la tabla `formacopa`
--
ALTER TABLE `formacopa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `grupouso`
--
ALTER TABLE `grupouso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `prenda_categoria`
--
ALTER TABLE `prenda_categoria`
  ADD PRIMARY KEY (`Prenda_codigo`,`Categoria_codigo`);

--
-- Indices de la tabla `prenda_copa`
--
ALTER TABLE `prenda_copa`
  ADD PRIMARY KEY (`Prenda_codigo`,`Copa_id`),
  ADD KEY `fk_Prenda_Copa_Copa1_idx` (`Copa_id`);

--
-- Indices de la tabla `prenda_formacopa`
--
ALTER TABLE `prenda_formacopa`
  ADD PRIMARY KEY (`Prenda_codigo`,`FormaCopa_id`),
  ADD KEY `fk_Prenda_FormaCopa_FormaCopa1_idx` (`FormaCopa_id`);

--
-- Indices de la tabla `prenda_talla`
--
ALTER TABLE `prenda_talla`
  ADD PRIMARY KEY (`Prenda_codigo`,`Talla_codigo`),
  ADD KEY `fk_Prenda_Talla_Talla1_idx` (`Talla_codigo`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `relacionprefijo`
--
ALTER TABLE `relacionprefijo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `skucreated`
--
ALTER TABLE `skucreated`
  ADD PRIMARY KEY (`Sku_codigo`);

--
-- Indices de la tabla `skutemp`
--
ALTER TABLE `skutemp`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_SkuTemp_Subdpto_idx` (`Subdpto_id`),
  ADD KEY `fk_SkuTemp_Talla1_idx` (`Talla_codigo`),
  ADD KEY `fk_SkuTemp_Presentacion1_idx` (`Presentacion_id`);

--
-- Indices de la tabla `skuupdated`
--
ALTER TABLE `skuupdated`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subdpto`
--
ALTER TABLE `subdpto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subdpto_prenda`
--
ALTER TABLE `subdpto_prenda`
  ADD PRIMARY KEY (`Subdpto_id`,`Prenda_codigo`),
  ADD KEY `fk_SubDpto_Prenda_Subdpto1_idx` (`Subdpto_id`);

--
-- Indices de la tabla `talla`
--
ALTER TABLE `talla`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `tempcatalogo`
--
ALTER TABLE `tempcatalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `tempprenda`
--
ALTER TABLE `tempprenda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT de la tabla `composicion`
--
ALTER TABLE `composicion`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT de la tabla `copa`
--
ALTER TABLE `copa`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `formacopa`
--
ALTER TABLE `formacopa`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `grupouso`
--
ALTER TABLE `grupouso`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `relacionprefijo`
--
ALTER TABLE `relacionprefijo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT de la tabla `skuupdated`
--
ALTER TABLE `skuupdated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `subdpto`
--
ALTER TABLE `subdpto`
  MODIFY `id` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `tempcatalogo`
--
ALTER TABLE `tempcatalogo`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `tempprenda`
--
ALTER TABLE `tempprenda`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalletalla`
--
ALTER TABLE `detalletalla`
  ADD CONSTRAINT `fk_DetalleTalla_Talla1` FOREIGN KEY (`Talla_codigo`) REFERENCES `talla` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dpto_subdpto`
--
ALTER TABLE `dpto_subdpto`
  ADD CONSTRAINT `fk_Dpto_SubDpto_Subdpto1` FOREIGN KEY (`Subdpto_id`) REFERENCES `subdpto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prenda_copa`
--
ALTER TABLE `prenda_copa`
  ADD CONSTRAINT `fk_Prenda_Copa_Copa1` FOREIGN KEY (`Copa_id`) REFERENCES `copa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prenda_formacopa`
--
ALTER TABLE `prenda_formacopa`
  ADD CONSTRAINT `fk_Prenda_FormaCopa_FormaCopa1` FOREIGN KEY (`FormaCopa_id`) REFERENCES `formacopa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prenda_talla`
--
ALTER TABLE `prenda_talla`
  ADD CONSTRAINT `fk_Prenda_Talla_Talla1` FOREIGN KEY (`Talla_codigo`) REFERENCES `talla` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `skutemp`
--
ALTER TABLE `skutemp`
  ADD CONSTRAINT `fk_SkuTemp_Presentacion1` FOREIGN KEY (`Presentacion_id`) REFERENCES `presentacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SkuTemp_Subdpto` FOREIGN KEY (`Subdpto_id`) REFERENCES `subdpto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SkuTemp_Talla1` FOREIGN KEY (`Talla_codigo`) REFERENCES `talla` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `subdpto_prenda`
--
ALTER TABLE `subdpto_prenda`
  ADD CONSTRAINT `fk_SubDpto_Prenda_Subdpto1` FOREIGN KEY (`Subdpto_id`) REFERENCES `subdpto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
