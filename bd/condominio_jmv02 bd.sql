-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2023 a las 23:44:07
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `condominio_jmv02`
--
CREATE DATABASE IF NOT EXISTS `condominio_jmv02` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `condominio_jmv02`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartamento`
--

CREATE TABLE `apartamento` (
  `id_apartamento` int(11) NOT NULL,
  `num_letra_apartamento` varchar(11) NOT NULL,
  `propietario` int(11) NOT NULL,
  `inquilino` int(11) DEFAULT NULL,
  `torre` varchar(45) NOT NULL,
  `piso` varchar(45) NOT NULL,
  `tipo_apartamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `apartamento`
--

INSERT INTO `apartamento` (`id_apartamento`, `num_letra_apartamento`, `propietario`, `inquilino`, `torre`, `piso`, `tipo_apartamento`) VALUES
(1, 'A-01', 2, NULL, '22', '1', 4),
(2, 'A-02', 1, NULL, '22', '2', 3),
(3, 'A-03', 2, NULL, '22', '3', 3),
(4, 'A-04', 1, NULL, '22', '4', 2),
(5, 'A-05', 3, NULL, '22', '5', 1),
(6, 'B-01', 3, NULL, '23', '1', 4),
(7, 'B-02', 2, NULL, '23', '1', 4),
(8, 'B-03', 2, 1, '23', '2', 3),
(9, 'B-04', 1, NULL, '23', '4', 2),
(10, 'B-05', 2, NULL, '23', '5', 1),
(11, 'C-01', 3, NULL, '24', '1', 4),
(12, 'C-02', 1, NULL, '24', '1', 4),
(13, 'C-03', 2, NULL, '24', '2', 3),
(14, 'C-04', 2, NULL, '24', '3', 3),
(15, 'C-05', 3, NULL, '24', '4', 2),
(16, 'C-06', 2, NULL, '24', '5', 1),
(17, 'D-01', 2, NULL, '25', '1', 4),
(18, 'D-02', 1, NULL, '25', '1', 4),
(19, 'D-03', 3, NULL, '25', '1', 4),
(20, 'D-04', 2, NULL, '25', '2', 3),
(21, 'D-05', 1, NULL, '25', '2', 3),
(22, 'D-06', 1, NULL, '25', '2', 3),
(23, 'D-07', 3, NULL, '25', '3', 2),
(24, 'D-08', 3, 2, '25', '4', 1),
(25, 'X-123', 3, NULL, '22', '1', 1);

--
-- Disparadores `apartamento`
--
DELIMITER $$
CREATE TRIGGER `resta_hijos_apartamento` AFTER DELETE ON `apartamento` FOR EACH ROW BEGIN
	UPDATE tipo_apartamento SET cantidadHijos = cantidadHijos - 1 WHERE id_tipo_apartamento = OLD.tipo_apartamento;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `suma_hijos_apartamento` AFTER INSERT ON `apartamento` FOR EACH ROW BEGIN
	UPDATE tipo_apartamento SET cantidadHijos = cantidadHijos + 1 WHERE id_tipo_apartamento = NEW.tipo_apartamento;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartamentos_lista_cargos`
--

CREATE TABLE `apartamentos_lista_cargos` (
  `id_apartamento` int(11) NOT NULL,
  `id_lista_cargos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `apartamentos_lista_cargos`
--

INSERT INTO `apartamentos_lista_cargos` (`id_apartamento`, `id_lista_cargos`) VALUES
(1, 14),
(1, 16),
(2, 14),
(2, 16),
(3, 14),
(3, 16),
(4, 14),
(4, 16),
(5, 16),
(6, 16),
(7, 16),
(8, 16),
(9, 16),
(10, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avisos`
--

CREATE TABLE `avisos` (
  `id_aviso` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `descripcion` text NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `avisos`
--

INSERT INTO `avisos` (`id_aviso`, `titulo`, `descripcion`, `desde`, `hasta`) VALUES
(2, 'hola como estan', 'sdjflasdjfañsldfjañsldfjñasldkjfñasldjf \r\n...............................', '2023-09-27', '2023-09-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `descrip` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `user`, `descrip`, `fecha`) VALUES
(1, 3, 'Registro en servicios', '2023-09-13 01:20:55'),
(2, 3, 'Registro en servicios pago de servicio', '2023-09-13 01:20:55'),
(3, 3, 'Registro en servicios un servicio', '2023-09-13 01:21:24'),
(4, 3, 'Eliminación en servicios un servicio', '2023-09-13 01:21:42'),
(5, 3, 'Inicio sesionlogin', '2023-09-17 20:24:58'),
(6, 3, 'Inicio sesionlogin', '2023-09-17 20:28:59'),
(7, 3, 'Modificación en usuarios-administracion ', '2023-09-17 20:29:46'),
(8, 3, 'Inicio sesionlogin', '2023-09-17 20:30:06'),
(9, 3, 'Inicio sesionlogin', '2023-09-17 20:41:11'),
(10, 3, 'Inicio sesionlogin', '2023-09-17 20:42:55'),
(11, 3, 'Inicio sesionlogin', '2023-09-17 20:55:18'),
(12, 3, 'Inicio sesionlogin', '2023-09-17 20:58:44'),
(13, 3, 'Inicio sesionlogin', '2023-09-17 21:25:28'),
(14, 3, 'Registro en apartamentos ', '2023-09-17 21:52:06'),
(15, 3, 'Inicio sesionlogin', '2023-09-17 21:58:19'),
(16, 3, 'Registro en deuda-condominio ', '2023-09-17 21:59:15'),
(17, 3, 'Eliminación en deuda-condominio ', '2023-09-17 21:59:40'),
(18, 3, 'Inicio sesionlogin', '2023-09-17 22:12:18'),
(19, 3, 'Registro en avisos ', '2023-09-17 22:13:56'),
(20, 3, 'Eliminación en avisos ', '2023-09-17 22:14:04'),
(21, 3, 'Registro en avisos ', '2023-09-17 22:14:42'),
(22, 3, 'Modificación en avisos ', '2023-09-17 22:55:22'),
(23, 3, 'Modificación en avisos ', '2023-09-17 22:55:44'),
(24, 3, 'Modificación en avisos ', '2023-09-17 23:29:58'),
(25, 3, 'Modificación en avisos ', '2023-09-17 23:41:11'),
(26, 3, 'Inicio sesionlogin', '2023-09-18 04:57:35'),
(27, 3, 'Inicio sesionlogin', '2023-09-18 06:25:50'),
(28, 3, 'Eliminación en servicios pago de servicio', '2023-09-18 15:10:51'),
(29, 3, 'Inicio sesionlogin', '2023-09-18 16:06:45'),
(30, 3, 'Inicio sesionlogin', '2023-09-18 16:16:03'),
(31, 3, 'Registro en foro ', '2023-09-18 17:32:37'),
(32, 3, 'Registro en foro ', '2023-09-18 17:41:09'),
(33, 3, 'Registro en foro ', '2023-09-18 17:43:05'),
(34, 3, 'Registro en foro ', '2023-09-18 17:43:31'),
(35, 3, 'Registro en foro ', '2023-09-18 17:45:00'),
(36, 3, 'Modificación en foro ', '2023-09-18 17:57:50'),
(37, 3, 'Eliminación en foro ', '2023-09-18 17:58:00'),
(38, 3, 'Eliminación en foro ', '2023-09-18 17:58:13'),
(39, 3, 'Eliminación en foro ', '2023-09-18 17:58:22'),
(40, 3, 'Eliminación en foro ', '2023-09-18 17:58:29'),
(41, 3, 'Registro en foro ', '2023-09-18 18:01:00'),
(42, 3, 'Eliminación en foro ', '2023-09-18 18:08:14'),
(43, 3, 'Inicio sesionlogin', '2023-09-18 21:35:02'),
(44, 3, 'Inicio sesionlogin', '2023-09-19 05:59:18'),
(45, 3, 'Registro en deuda-condominio ', '2023-09-19 08:02:38'),
(46, 3, 'Eliminación en deuda-condominio ', '2023-09-19 08:42:57'),
(47, 3, 'Inicio sesionlogin', '2023-09-19 10:08:58'),
(48, 3, 'Inicio sesionlogin', '2023-10-02 11:11:02'),
(49, 3, 'Inicio sesionlogin', '2023-10-02 14:26:35'),
(50, 3, 'Inicio sesionlogin', '2023-10-02 16:21:31'),
(51, 3, 'Inicio sesionlogin', '2023-10-06 15:37:37'),
(52, 3, 'Inicio sesionlogin', '2023-10-07 00:20:59'),
(53, 3, 'Inicio sesionlogin', '2023-10-08 05:00:02'),
(54, 3, 'Inicio sesionlogin', '2023-10-08 10:40:38'),
(55, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:33:24'),
(56, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:35:02'),
(57, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:38:21'),
(58, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:42:21'),
(59, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:43:10'),
(60, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:45:36'),
(61, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:46:35'),
(62, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:47:17'),
(63, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:48:05'),
(64, 3, 'Registro en servicios pago de servicio', '2023-10-09 03:49:33'),
(65, 3, 'Registro en servicios pago de servicio', '2023-10-09 04:01:37'),
(66, 3, 'Inicio sesionlogin', '2023-10-09 13:31:13'),
(67, 3, 'Registro en servicios pago de servicio', '2023-10-09 14:30:45'),
(68, 3, 'Registro en servicios pago de servicio', '2023-10-09 18:11:34'),
(69, 3, 'Registro en servicios pago de servicio', '2023-10-09 18:32:35'),
(70, 3, 'Registro en servicios pago de servicio', '2023-10-09 20:57:20'),
(71, 3, 'Registro en servicios pago de servicio', '2023-10-09 21:00:08'),
(72, 3, 'Registro en servicios pago de servicio', '2023-10-09 21:04:21'),
(73, 3, 'Registro en servicios pago de servicio', '2023-10-09 21:10:05'),
(74, 3, 'Registro en servicios pago de servicio', '2023-10-09 21:11:34'),
(75, 3, 'Registro en servicios pago de servicio', '2023-10-09 21:13:37'),
(76, 3, 'Eliminación en servicios pago de servicio', '2023-10-09 22:55:23'),
(77, 3, 'Registro en servicios pago de servicio', '2023-10-09 23:21:50'),
(78, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:27:32'),
(79, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:30:44'),
(80, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:33:21'),
(81, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:37:26'),
(82, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:37:49'),
(83, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:38:03'),
(84, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:38:48'),
(85, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:51:36'),
(86, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:51:58'),
(87, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:53:37'),
(88, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:54:25'),
(89, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:55:22'),
(90, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:56:14'),
(91, 3, 'Modificación en servicios pago de servicio', '2023-10-10 01:56:34'),
(92, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:00:29'),
(93, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:00:39'),
(94, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:09:21'),
(95, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:09:55'),
(96, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:10:11'),
(97, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:13:16'),
(98, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:14:09'),
(99, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:14:18'),
(100, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:35:21'),
(101, 3, 'Modificación en servicios pago de servicio', '2023-10-10 02:37:18'),
(102, 3, 'Inicio sesionlogin', '2023-10-10 22:05:54'),
(103, 3, 'Inicio sesionlogin', '2023-10-10 22:06:16'),
(104, 3, 'Eliminación en servicios pago de servicio', '2023-10-10 22:13:59'),
(105, 3, 'Inicio sesionlogin', '2023-10-11 03:47:03'),
(106, 3, 'Registro en servicios pago de servicio', '2023-10-11 10:21:37'),
(107, 3, 'Inicio sesionlogin', '2023-10-12 01:59:28'),
(108, 3, 'Inicio sesionlogin', '2023-10-12 04:04:45'),
(109, 3, 'Inicio sesionlogin', '2023-10-12 14:20:44'),
(110, 3, 'Inicio sesionlogin', '2023-10-14 08:23:17'),
(111, 3, 'Inicio sesionlogin', '2023-10-15 09:19:08'),
(112, 3, 'Inicio sesionlogin', '2023-10-15 20:27:44'),
(113, 3, 'Inicio sesionlogin', '2023-10-16 05:08:38'),
(114, 3, 'Modificación en servicios pago de servicio', '2023-10-16 06:52:46'),
(115, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:08:20'),
(116, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:10:31'),
(117, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:10:44'),
(118, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:12:34'),
(119, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:13:05'),
(120, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:16:48'),
(121, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:17:13'),
(122, 3, 'Modificación en nomina pago de nomina', '2023-10-16 07:27:09'),
(123, 3, 'Registro en nomina nomina_pago', '2023-10-16 08:14:20'),
(124, 3, 'Registro en nomina detalles_pagos', '2023-10-16 08:14:20'),
(125, 3, 'Eliminación en nomina pago de nomina', '2023-10-16 08:14:30'),
(126, 3, 'Registro en nomina nomina_pago', '2023-10-16 08:31:42'),
(127, 3, 'Registro en nomina detalles_pagos', '2023-10-16 08:31:42'),
(128, 3, 'Inicio sesion login', '2023-10-16 09:06:58'),
(129, 3, 'Registro en deuda-condominio lista cargos', '2023-10-16 20:03:56'),
(130, 3, 'Registro en deuda-condominio lista cargos', '2023-10-16 20:10:39'),
(131, 3, 'Registro en deuda-condominio lista cargos', '2023-10-16 20:15:53'),
(132, 3, 'Registro en deuda-condominio lista cargos', '2023-10-16 20:28:36'),
(133, 3, 'Registro en deuda-condominio lista cargos', '2023-10-16 20:30:26'),
(134, 3, 'Inicio sesion login', '2023-10-16 22:28:23'),
(135, 3, 'Inicio sesion login', '2023-10-17 00:17:33'),
(136, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:00:46'),
(137, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:06:11'),
(138, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:07:24'),
(139, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:07:57'),
(140, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:10:06'),
(141, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:14:18'),
(142, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 02:14:50'),
(143, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:45:56'),
(144, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:47:44'),
(145, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:48:14'),
(146, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:50:53'),
(147, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:51:11'),
(148, 3, 'Registro en deuda-condominio lista cargos', '2023-10-17 04:53:12'),
(149, 3, 'Inicio sesion login', '2023-10-17 09:19:46'),
(150, 3, 'Inicio sesion login', '2023-10-17 09:36:38'),
(151, 3, 'Inicio sesion login', '2023-10-17 09:40:48'),
(152, 3, 'Inicio sesion login', '2023-10-17 09:42:29'),
(153, 3, 'Inicio sesion login', '2023-10-17 09:46:33'),
(154, 3, 'Inicio sesion login', '2023-10-17 09:49:51'),
(155, 3, 'Inicio sesion login', '2023-10-17 09:56:31'),
(156, 3, 'Inicio sesion login', '2023-10-17 10:04:54'),
(157, 3, 'Inicio sesion login', '2023-10-17 10:05:09'),
(158, 3, 'Inicio sesion login', '2023-10-17 10:06:21'),
(159, 3, 'Inicio sesion login', '2023-10-17 10:06:48'),
(160, 3, 'Inicio sesion login', '2023-10-17 10:09:30'),
(161, 3, 'Inicio sesion login', '2023-10-17 10:25:18'),
(162, 3, 'Inicio sesion login', '2023-10-17 10:49:16'),
(163, 3, 'Inicio sesion login', '2023-10-17 10:51:16'),
(164, 3, 'Inicio sesion login', '2023-10-17 10:58:15'),
(165, 3, 'Modificación en apartamentos ', '2023-10-17 12:17:36'),
(166, 3, 'Modificación en apartamentos ', '2023-10-17 12:18:31'),
(167, 3, 'Modificación en apartamentos ', '2023-10-17 12:20:08'),
(168, 3, 'Modificación en apartamentos ', '2023-10-17 12:20:42'),
(169, 3, 'Modificación en apartamentos ', '2023-10-17 12:21:24'),
(170, 3, 'Modificación en apartamentos ', '2023-10-17 12:21:30'),
(171, 3, 'Modificación en usuarios-administracion ', '2023-10-17 13:03:35'),
(172, 3, 'Modificación en usuarios-administracion ', '2023-10-17 13:04:01'),
(173, 3, 'Registro en habitantes ', '2023-10-17 13:08:53'),
(174, 3, 'Modificación en habitantes ', '2023-10-17 13:12:11'),
(175, 3, 'Modificación en estacionamiento ', '2023-10-17 13:17:27'),
(176, 3, 'Inicio sesion cerrarsesion', '2023-10-17 13:18:08'),
(177, 3, 'Inicio sesion login', '2023-10-17 13:18:37'),
(178, 3, 'Inicio sesion login', '2023-10-19 07:18:09'),
(179, 3, 'Registro nuevo monto BCV', '2023-10-19 07:29:47'),
(180, 3, 'Registro nuevo monto BCV', '2023-10-19 07:30:11'),
(181, 3, 'Inicio sesion login', '2023-10-19 07:31:38'),
(182, 3, 'Registro nuevo monto BCV', '2023-10-19 07:31:39'),
(183, 3, 'Inicio sesion login', '2023-10-19 07:33:04'),
(184, 3, 'Registro nuevo monto BCV', '2023-10-19 07:33:06'),
(185, 3, 'Inicio sesion login', '2023-10-19 07:58:52'),
(186, 3, 'Inicio sesion login', '2023-10-19 07:59:13'),
(187, 3, 'Inicio sesion login', '2023-10-19 08:00:04'),
(188, 3, 'Inicio sesion login', '2023-10-19 08:01:05'),
(189, 3, 'Inicio sesion login', '2023-10-19 08:01:53'),
(190, 3, 'Registro nuevo monto BCV', '2023-10-19 08:01:55'),
(191, 3, 'Inicio sesion login', '2023-10-19 08:02:48'),
(192, 3, 'Registro nuevo monto BCV', '2023-10-19 08:02:50'),
(193, 3, 'Inicio sesion login', '2023-10-19 08:06:05'),
(194, 3, 'Registro nuevo monto BCV', '2023-10-19 08:06:07'),
(195, 3, 'Inicio sesion login', '2023-10-19 13:15:35'),
(196, 3, 'Inicio sesion login', '2023-10-19 13:56:39'),
(197, 3, 'Inicio sesion login', '2023-10-19 14:25:26'),
(198, 3, 'Registro nuevo monto BCV', '2023-10-19 14:27:33'),
(199, 3, 'Inicio sesion login', '2023-10-19 14:33:30'),
(200, 3, 'Registro nuevo monto BCV', '2023-10-19 14:33:31'),
(201, 3, 'Inicio sesion login', '2023-10-19 14:37:42'),
(202, 3, 'Registro nuevo monto BCV', '2023-10-19 14:37:44'),
(203, 3, 'Inicio sesion login', '2023-10-19 14:38:32'),
(204, 3, 'Registro nuevo monto BCV', '2023-10-19 14:38:34'),
(205, 3, 'Inicio sesion login', '2023-10-19 14:40:26'),
(206, 3, 'Registro nuevo monto BCV', '2023-10-19 14:40:28'),
(207, 3, 'Inicio sesion login', '2023-10-19 14:42:12'),
(208, 3, 'Registro nuevo monto BCV', '2023-10-19 14:42:14'),
(209, 3, 'Inicio sesion login', '2023-10-19 14:44:00'),
(210, 3, 'Registro nuevo monto BCV', '2023-10-19 14:44:02'),
(211, 3, 'Inicio sesion login', '2023-10-19 14:48:56'),
(212, 3, 'Inicio sesion login', '2023-10-19 14:54:17'),
(213, 3, 'Inicio sesion login', '2023-10-19 15:05:24'),
(214, 3, 'Registro en foroAction ', '2023-10-19 18:10:48'),
(215, 3, 'Eliminación en foroAction ', '2023-10-19 18:10:55'),
(216, 3, 'Registro en usuarios-administracion ', '2023-10-19 18:22:45'),
(217, 5, 'Inicio sesion login', '2023-10-19 18:28:02'),
(218, 3, 'Inicio sesion login', '2023-10-19 18:28:44'),
(219, 3, 'Registro en deuda-condominio lista cargos', '2023-10-19 21:07:33'),
(220, 3, 'Registro en deuda-condominio lista cargos', '2023-10-19 21:13:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` bigint(20) NOT NULL,
  `id_foro` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `create_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `titulo` varchar(30) NOT NULL,
  `valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`titulo`, `valor`) VALUES
('hora_dolar', '13:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_usuarios`
--

CREATE TABLE `datos_usuarios` (
  `id` int(11) NOT NULL,
  `rif_cedula` varchar(40) NOT NULL,
  `tipo_identificacion` int(1) NOT NULL,
  `razon_social` varchar(120) NOT NULL,
  `domicilio_fiscal` varchar(200) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `datos_usuarios`
--

INSERT INTO `datos_usuarios` (`id`, `rif_cedula`, `tipo_identificacion`, `razon_social`, `domicilio_fiscal`, `telefono`, `correo`) VALUES
(1, '28609560', 0, 'Jugney Vargas', 'Barrio Unión', '0424-5698188', 'jugneyv@gmail.com'),
(2, '26846371', 0, 'Diego Salazar', 'Calle 28', '0424-4034515', 'diego14asf@gmail.com'),
(3, '27250544', 0, 'Xavier Suarez', 'Calle 28', '0424-5798958', 'algo@gmail.com'),
(4, '28406750', 0, 'Luis Colmenares', 'Calle 28', '0426-3525659', '@gmail.com'),
(5, '000000000', 2, 'SUPER USUARIO', '_______________________', '0000-0000000', 'SUPER@USUARIO.SSS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_deudas`
--

CREATE TABLE `detalles_deudas` (
  `id_deuda` bigint(20) NOT NULL,
  `concepto` varchar(80) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `tipo_monto` tinyint(1) DEFAULT '1',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pagos`
--

CREATE TABLE `detalles_pagos` (
  `id_detalles_pagos` bigint(20) NOT NULL,
  `id_pago` bigint(20) NOT NULL,
  `tipo_pago` int(11) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_monto` tinyint(1) NOT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `emisor` varchar(60) DEFAULT NULL,
  `cedula_rif` varchar(20) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalles_pagos`
--

INSERT INTO `detalles_pagos` (`id_detalles_pagos`, `id_pago`, `tipo_pago`, `monto`, `fecha`, `tipo_monto`, `numero`, `emisor`, `cedula_rif`, `telefono`) VALUES
(47, 72, 1, '1000.00', '2023-10-01 22:33:00', 0, NULL, NULL, NULL, NULL),
(48, 73, 2, '2000.00', '2023-10-20 21:34:00', 0, '10497231313', 'provincial', 'v12434091', NULL),
(49, 74, 3, '3000.00', '2023-10-10 21:40:00', 0, '32131312131', 'bamplus', 'v27250544', '04245798958'),
(50, 75, 1, '2500.00', '2023-10-18 12:51:00', 0, NULL, NULL, NULL, NULL),
(51, 85, 1, '250.00', '2023-10-10 17:31:00', 0, NULL, NULL, NULL, NULL),
(53, 87, 4, '352.70', '2023-10-14 01:39:00', 0, NULL, NULL, NULL, NULL),
(55, 89, 1, '250000.00', '2024-06-20 10:01:00', 0, NULL, NULL, NULL, NULL);

--
-- Disparadores `detalles_pagos`
--
DELIMITER $$
CREATE TRIGGER `U_detalles` AFTER UPDATE ON `detalles_pagos` FOR EACH ROW BEGIN
IF (NEW.id_pago <> OLD.id_pago) THEN
	UPDATE pagos SET pagos.total_pago = pagos.total_pago - OLD.monto WHERE pagos.id_pago = OLD.id_pago;
END IF;

IF (NEW.tipo_pago <> OLD.tipo_pago AND OLD.tipo_pago = 4) THEN
	DELETE FROM divisa WHERE id_detalles_pagos = OLD.id_detalles_pagos;
END if;

UPDATE pagos SET pagos.total_pago = (pagos.total_pago - OLD.monto) + NEW.monto WHERE pagos.id_pago = NEW.id_pago;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `detalles_pagos_BD` BEFORE DELETE ON `detalles_pagos` FOR EACH ROW BEGIN
	UPDATE pagos SET pagos.total_pago = pagos.total_pago - OLD.monto WHERE pagos.id_pago = OLD.id_pago;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_detalles` AFTER INSERT ON `detalles_pagos` FOR EACH ROW UPDATE pagos SET pagos.total_pago = pagos.total_pago + NEW.monto WHERE pagos.id_pago = NEW.id_pago
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudas`
--

CREATE TABLE `deudas` (
  `id_deuda` bigint(20) NOT NULL,
  `id_apartamento` int(11) NOT NULL,
  `id_distribucion` int(11) NOT NULL,
  `concepto` varchar(80) NOT NULL,
  `fecha` date NOT NULL,
  `total` decimal(20,2) NOT NULL,
  `moroso` tinyint(1) NOT NULL,
  `descuento` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda_condominio`
--

CREATE TABLE `deuda_condominio` (
  `id` int(15) NOT NULL,
  `fecha_generada` date NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `concepto` varchar(30) NOT NULL,
  `usuario` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `deuda_condominio`
--

INSERT INTO `deuda_condominio` (`id`, `fecha_generada`, `monto`, `concepto`, `usuario`) VALUES
(1, '2023-01-05', '355.00', 'Deuda de Enero', 2),
(3, '2022-12-05', '325.00', 'Deuda de Diciembre', 2),
(4, '2023-02-01', '500.00', 'Deuda de Febrero', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda_pagos`
--

CREATE TABLE `deuda_pagos` (
  `id_deuda` bigint(20) NOT NULL,
  `id_pago` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda_pendiente`
--

CREATE TABLE `deuda_pendiente` (
  `id` int(11) NOT NULL,
  `id_apartamento` int(11) NOT NULL,
  `id_deuda_condominio` int(11) NOT NULL,
  `fecha_a_cancelar` date NOT NULL,
  `total` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `deuda_pendiente`
--

INSERT INTO `deuda_pendiente` (`id`, `id_apartamento`, `id_deuda_condominio`, `fecha_a_cancelar`, `total`) VALUES
(1, 5, 1, '2023-02-05', '22.19'),
(2, 10, 1, '2023-02-05', '22.19'),
(3, 16, 1, '2023-02-05', '22.19'),
(4, 24, 1, '2023-02-05', '22.19'),
(5, 4, 1, '2023-02-05', '17.75'),
(6, 9, 1, '2023-02-05', '17.75'),
(7, 15, 1, '2023-02-05', '17.75'),
(8, 23, 1, '2023-02-05', '17.75'),
(9, 2, 1, '2023-02-05', '13.31'),
(10, 3, 1, '2023-02-05', '13.31'),
(11, 8, 1, '2023-02-05', '13.31'),
(12, 13, 1, '2023-02-05', '13.31'),
(13, 14, 1, '2023-02-05', '13.31'),
(14, 20, 1, '2023-02-05', '13.31'),
(15, 21, 1, '2023-02-05', '13.31'),
(16, 22, 1, '2023-02-05', '13.31'),
(17, 1, 1, '2023-02-05', '11.09'),
(18, 6, 1, '2023-02-05', '11.09'),
(19, 7, 1, '2023-02-05', '11.09'),
(20, 11, 1, '2023-02-05', '11.09'),
(21, 12, 1, '2023-02-05', '11.09'),
(22, 17, 1, '2023-02-05', '11.09'),
(23, 18, 1, '2023-02-05', '11.09'),
(24, 19, 1, '2023-02-05', '11.09'),
(25, 5, 3, '2023-01-05', '20.31'),
(26, 10, 3, '2023-01-05', '20.31'),
(27, 16, 3, '2023-01-05', '20.31'),
(28, 24, 3, '2023-01-05', '20.31'),
(29, 4, 3, '2023-01-05', '16.25'),
(30, 9, 3, '2023-01-05', '16.25'),
(31, 15, 3, '2023-01-05', '16.25'),
(32, 23, 3, '2023-01-05', '16.25'),
(33, 2, 3, '2023-01-05', '12.19'),
(34, 3, 3, '2023-01-05', '12.19'),
(35, 8, 3, '2023-01-05', '12.19'),
(36, 13, 3, '2023-01-05', '12.19'),
(37, 14, 3, '2023-01-05', '12.19'),
(38, 20, 3, '2023-01-05', '12.19'),
(39, 21, 3, '2023-01-05', '12.19'),
(40, 22, 3, '2023-01-05', '12.19'),
(41, 1, 3, '2023-01-05', '10.16'),
(42, 6, 3, '2023-01-05', '10.16'),
(43, 7, 3, '2023-01-05', '10.16'),
(44, 11, 3, '2023-01-05', '10.16'),
(45, 12, 3, '2023-01-05', '10.16'),
(46, 17, 3, '2023-01-05', '10.16'),
(47, 18, 3, '2023-01-05', '10.16'),
(48, 19, 3, '2023-01-05', '10.16'),
(97, 5, 4, '2023-03-01', '31.25'),
(98, 10, 4, '2023-03-01', '31.25'),
(99, 16, 4, '2023-03-01', '31.25'),
(100, 24, 4, '2023-03-01', '31.25'),
(101, 4, 4, '2023-03-01', '25.00'),
(102, 9, 4, '2023-03-01', '25.00'),
(103, 15, 4, '2023-03-01', '25.00'),
(104, 23, 4, '2023-03-01', '25.00'),
(105, 2, 4, '2023-03-01', '18.75'),
(106, 3, 4, '2023-03-01', '18.75'),
(107, 8, 4, '2023-03-01', '18.75'),
(108, 13, 4, '2023-03-01', '18.75'),
(109, 14, 4, '2023-03-01', '18.75'),
(110, 20, 4, '2023-03-01', '18.75'),
(111, 21, 4, '2023-03-01', '18.75'),
(112, 22, 4, '2023-03-01', '18.75'),
(113, 1, 4, '2023-03-01', '15.63'),
(114, 6, 4, '2023-03-01', '15.63'),
(115, 7, 4, '2023-03-01', '15.63'),
(116, 11, 4, '2023-03-01', '15.63'),
(117, 12, 4, '2023-03-01', '15.63'),
(118, 17, 4, '2023-03-01', '15.63'),
(119, 18, 4, '2023-03-01', '15.63'),
(120, 19, 4, '2023-03-01', '15.63');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribuciones`
--

CREATE TABLE `distribuciones` (
  `id_distribucion` int(11) NOT NULL COMMENT 'id de la distribución',
  `fecha` date NOT NULL COMMENT 'fecha de la distribución',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto de la distribución',
  `usuario` int(11) NOT NULL COMMENT 'usuario que genero la distribución'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='almacena las distribuciones de las deudas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisa`
--

CREATE TABLE `divisa` (
  `id_detalles_pagos` bigint(20) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `denominacion` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `divisa`
--

INSERT INTO `divisa` (`id_detalles_pagos`, `serial`, `denominacion`) VALUES
(53, '123', '10.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `rif_cedula` varchar(40) NOT NULL,
  `tipo_identificacion` int(1) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `salario` decimal(20,2) DEFAULT NULL,
  `domicilio` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `estado_civil` enum('Soltero','Casado','Divorciado','Viudo','Otros') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `rif_cedula`, `tipo_identificacion`, `nombre`, `apellido`, `fecha_contratacion`, `salario`, `domicilio`, `telefono`, `correo`, `cargo`, `fecha_nacimiento`, `estado_civil`) VALUES
(1, '12345678', 1, 'Maria Alejandra', 'Sanchez Rodriguez', '2023-09-12', '25.00', 'su casa', '0414-5555555', 'algo@algomas.algo', 'Vigilante', '1998-01-06', 'Soltero'),
(9, '27250544', 0, 'xavier', 'suarez', '2023-01-01', '777777.77', 'mi casa', '04145550000', 'algo@esto.com', 'chanclas', '2023-01-01', 'Soltero'),
(11, '27250544', 2, 'xavier david', 'suarez', '2023-01-01', '777777.77', 'mi casa', '04145550000', 'algo@esto.com', 'chanclas', '2023-01-01', 'Casado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacionamiento`
--

CREATE TABLE `estacionamiento` (
  `num_estacionamiento` int(11) NOT NULL,
  `id_apartamento` int(11) DEFAULT NULL,
  `costo` decimal(20,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estacionamiento`
--

INSERT INTO `estacionamiento` (`num_estacionamiento`, `id_apartamento`, `costo`) VALUES
(1, 1, '2000.00'),
(2, NULL, '25.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `foro`
--

INSERT INTO `foro` (`id`, `titulo`, `descripcion`, `fecha`, `create_by`) VALUES
(1, 'Cuantos quieren comprar la nota de SO', 'Hagan la vaca', '2023-09-18 17:32:37', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitantes`
--

CREATE TABLE `habitantes` (
  `id` int(11) NOT NULL,
  `cedula_rif` varchar(10) NOT NULL,
  `tipo_identificacion` int(1) NOT NULL,
  `nombres` varchar(45) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `domicilio_fiscal` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '123'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `habitantes`
--

INSERT INTO `habitantes` (`id`, `cedula_rif`, `tipo_identificacion`, `nombres`, `apellidos`, `telefono`, `correo`, `domicilio_fiscal`, `password`) VALUES
(1, '28609560', 1, 'Jugney', 'Vargas', '0424-5681343', 'jugneyv@gmail.com', 'br union', '123'),
(2, '26846371', 0, 'Diego Andres', 'Salazar Gonzalez', '0424-4034516', 'diego14asf@gmail.com', 'Calle 28 entre 19 y 20', '123'),
(3, '10846157', 0, 'Juan', 'Jimenez', '0426-5551234', 'juanjimenez@gmail.com', 'Conjunto residencial jose maria vargas', '123'),
(4, '77777777', 1, 'xavier D', 'suarez', '0414-5555555', 'algo.esto@queso.com', 'mi casa', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_cargos_d`
--

CREATE TABLE `lista_cargos_d` (
  `id_lista_cargos` int(11) NOT NULL,
  `concepto` varchar(80) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `tipo_monto` tinyint(1) DEFAULT '1',
  `tipo_cargo` tinyint(1) NOT NULL,
  `mensual` tinyint(1) NOT NULL,
  `aplicar_next_mes` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lista_cargos_d`
--

INSERT INTO `lista_cargos_d` (`id_lista_cargos`, `concepto`, `monto`, `tipo_monto`, `tipo_cargo`, `mensual`, `aplicar_next_mes`) VALUES
(11, 'cable para los puercos', '7777.78', 1, 1, 1, 1),
(13, 'probando otros', '8888.88', 0, 1, 0, 0),
(14, 'combinaciones', '99.99', 1, 0, 0, 1),
(16, 'xxxxxxx', '111.11', 0, 0, 1, 1);

--
-- Disparadores `lista_cargos_d`
--
DELIMITER $$
CREATE TRIGGER `lista_cargos_d_AU` AFTER UPDATE ON `lista_cargos_d` FOR EACH ROW BEGIN
IF (NEW.tipo_cargo = 1 AND OLD.tipo_cargo = 0) THEN
	DELETE FROM apartamentos_lista_cargos WHERE id_lista_cargos = OLD.id_lista_cargos;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_servicios`
--

CREATE TABLE `lista_servicios` (
  `id_servicios` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lista_servicios`
--

INSERT INTO `lista_servicios` (`id_servicios`, `nombre`) VALUES
(1, 'Corpoelec'),
(2, 'CANTV');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`) VALUES
(1, 'apartamentos'),
(2, 'deuda-condominio'),
(3, 'deudas'),
(4, 'habitantes'),
(5, 'pagos'),
(6, 'tipoapto'),
(7, 'usuarios-administracion'),
(8, 'estacionamiento'),
(9, 'servicios'),
(10, 'nomina'),
(11, 'avisos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_pago`
--

CREATE TABLE `nomina_pago` (
  `id_empleado` int(11) NOT NULL,
  `id_pago` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nomina_pago`
--

INSERT INTO `nomina_pago` (`id_empleado`, `id_pago`) VALUES
(9, 87),
(11, 85),
(11, 89);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `referencia` varchar(15) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `tipo_pago` varchar(45) NOT NULL,
  `total` decimal(20,2) NOT NULL,
  `deuda` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `referencia`, `fecha_entrega`, `tipo_pago`, `total`, `deuda`, `id_usuario`, `estado`) VALUES
(59, '2233', '2023-01-15', 'Pago Movil', '10.16', 46, 1, 'pendiente'),
(60, '2133', '2023-01-15', 'Zelle', '10.16', 43, 1, 'pendiente'),
(61, '21332', '2023-01-15', 'Pago Movil', '10.16', 41, 1, 'confirmado'),
(62, '5555', '2023-01-15', 'Pago Movil', '12.19', 38, 1, 'declinado'),
(63, '3666', '2023-01-15', 'Efectivo', '12.19', 37, 1, 'confirmado'),
(64, '3331', '2023-01-15', 'Pago Movil', '12.19', 36, 1, 'declinado'),
(65, '4243', '2023-01-15', 'Pago Movil', '12.19', 35, 1, 'confirmado'),
(66, '4566', '2023-01-15', 'Transferencia Bancaria', '12.19', 34, 1, 'confirmado'),
(67, '3334', '2023-01-15', 'Efectivo', '12.19', 36, 1, 'confirmado'),
(68, '4556', '2023-01-15', 'Pago Movil', '20.31', 27, 1, 'confirmado'),
(69, '1233', '2023-01-15', 'Efectivo', '20.31', 26, 1, 'confirmado'),
(70, '2333', '2023-01-21', 'Efectivo', '11.09', 22, 1, 'confirmado'),
(71, '2333', '2023-01-21', 'Pago Movil', '11.09', 19, 1, 'confirmado'),
(72, '2333', '2023-01-22', 'Efectivo', '11.09', 17, 1, 'confirmado'),
(73, '7245', '2023-01-22', 'Zelle', '20.31', 25, 1, 'confirmado'),
(74, '32555', '2023-01-22', 'Zelle', '12.19', 40, 1, 'confirmado'),
(75, '1402', '2023-01-22', 'Zelle', '16.25', 30, 2, 'confirmado'),
(76, '2333', '2023-01-21', 'Efectivo', '13.31', 11, 2, 'pendiente'),
(77, '2333', '2023-01-20', 'Pago Movil', '17.75', 6, 2, 'declinado'),
(78, '2525', '2023-01-22', 'Efectivo', '17.75', 6, 2, 'pendiente'),
(79, '2333576', '2023-01-21', 'Pago Movil', '22.19', 2, 1, 'confirmado'),
(81, '2333', '2023-02-04', 'Efectivo', '13.31', 14, 1, 'confirmado'),
(82, '2333', '2023-02-04', 'Efectivo', '10.16', 48, 1, 'confirmado'),
(83, '2236', '2023-02-07', 'Efectivo', '13.31', 10, 1, 'confirmado'),
(84, '2626', '2023-02-12', 'Pago Movil', '12.19', 33, 1, 'confirmado'),
(85, '2345', '2023-02-14', 'Efectivo', '13.31', 12, 1, 'confirmado'),
(86, '2632', '2023-02-14', 'Efectivo', '22.19', 3, 1, 'confirmado'),
(87, '2626', '2023-02-14', 'Efectivo', '12.19', 38, 1, 'declinado'),
(91, '2333', '2023-02-21', 'Efectivo', '12.19', 38, 1, 'declinado'),
(92, '2323', '2023-02-25', 'Efectivo', '13.31', 13, 1, 'declinado'),
(93, '2333', '2023-02-25', 'Efectivo', '13.31', 13, 1, 'declinado'),
(94, '1222', '2023-02-25', 'Pago Movil', '18.75', 110, 3, 'confirmado'),
(95, '132131321', '2023-09-17', 'Transferencia Bancaria', '18.75', 112, NULL, 'pendiente'),
(96, '132131321', '2023-09-17', 'Efectivo', '18.75', 112, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` bigint(20) NOT NULL,
  `total_pago` decimal(65,2) DEFAULT '0.00',
  `concepto_pago` varchar(100) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `total_pago`, `concepto_pago`, `estado`, `usuario_id`) VALUES
(72, '1000.00', 'probando efectivo', 2, 3),
(73, '2000.00', 'probando transferencia', 2, 3),
(74, '3000.00', 'probando pago movil', 2, 3),
(75, '2500.00', '', 2, 3),
(85, '250.00', 'servicio completo', 2, 3),
(87, '352.70', 'probando divisa cambiendo la descrip por una mucho mas larga', 2, 3),
(89, '250000.00', 'hola probando fecha', 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_historial_estado`
--

CREATE TABLE `pago_historial_estado` (
  `id_historial` bigint(20) NOT NULL,
  `estatus_viejo` tinyint(1) DEFAULT NULL,
  `estatus_nuevo` tinyint(1) DEFAULT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id_comentario` bigint(20) NOT NULL,
  `id_respuestas` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Secretaria'),
(2, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_modulos`
--

CREATE TABLE `roles_modulos` (
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `crear` int(11) NOT NULL DEFAULT '1',
  `consultar` int(11) NOT NULL DEFAULT '1',
  `modificar` int(11) NOT NULL DEFAULT '1',
  `eliminar` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles_modulos`
--

INSERT INTO `roles_modulos` (`id_rol`, `id_modulo`, `crear`, `consultar`, `modificar`, `eliminar`) VALUES
(1, 1, 0, 1, 1, 0),
(1, 2, 1, 1, 1, 1),
(1, 3, 0, 1, 1, 0),
(1, 4, 1, 1, 1, 0),
(1, 5, 1, 1, 0, 0),
(1, 6, 0, 1, 1, 0),
(1, 7, 0, 0, 0, 0),
(1, 8, 0, 1, 1, 0),
(1, 9, 0, 1, 1, 0),
(1, 10, 1, 1, 1, 1),
(1, 11, 1, 1, 1, 1),
(2, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1),
(2, 3, 1, 1, 1, 1),
(2, 4, 1, 1, 1, 1),
(2, 5, 1, 1, 1, 1),
(2, 6, 1, 1, 1, 1),
(2, 7, 1, 1, 1, 1),
(2, 8, 1, 1, 1, 1),
(2, 9, 1, 1, 1, 1),
(2, 10, 1, 1, 1, 1),
(2, 11, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_pagos`
--

CREATE TABLE `servicios_pagos` (
  `id_servicio` int(11) NOT NULL,
  `id_pago` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios_pagos`
--

INSERT INTO `servicios_pagos` (`id_servicio`, `id_pago`) VALUES
(1, 72),
(1, 73),
(1, 74),
(2, 75);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_apartamento`
--

CREATE TABLE `tipo_apartamento` (
  `id_tipo_apartamento` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `alicuota` decimal(20,2) NOT NULL,
  `cantidadHijos` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_apartamento`
--

INSERT INTO `tipo_apartamento` (`id_tipo_apartamento`, `descripcion`, `alicuota`, `cantidadHijos`) VALUES
(1, 'Penthouse', '25.00', 5),
(2, 'Duplex', '20.00', 4),
(3, 'Departamento Básico', '30.00', 8),
(4, 'Mezzanina', '25.00', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cambio_divisa`
--

CREATE TABLE `tipo_cambio_divisa` (
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_cambio_divisa`
--

INSERT INTO `tipo_cambio_divisa` (`fecha`, `monto`) VALUES
('2023-10-19 08:02:28', '0.00'),
('2023-10-19 08:02:49', '34.86');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id_tipo_pago` int(11) NOT NULL,
  `tipo_pago` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id_tipo_pago`, `tipo_pago`) VALUES
(1, 'Efectivo'),
(2, 'Transferencia'),
(3, 'Pago Movil'),
(4, 'Divisa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencia`
--

CREATE TABLE `transferencia` (
  `id_detalles_pagos` bigint(20) NOT NULL,
  `cedula_rif` varchar(20) NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `banco` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `clave` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`, `clave`) VALUES
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(2, 1, '$2y$10$xJrc2hrFMX8FgdvlFyJApOz8qdYXQpmll5FFy3VI7Gw5dAAq4ubD6'),
(3, 2, '$2y$10$VAwQiX4aR3/60baTzh3ZQuafYmjSSx91sNX64JPBeOJobg5RtGIVW'),
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(2, 1, '$2y$10$xJrc2hrFMX8FgdvlFyJApOz8qdYXQpmll5FFy3VI7Gw5dAAq4ubD6'),
(3, 2, '$2y$10$VAwQiX4aR3/60baTzh3ZQuafYmjSSx91sNX64JPBeOJobg5RtGIVW'),
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(5, 2, '$2y$10$cPUwinUVv00F4k21KGs3Qe8oTo9K9Ln6L7cz8Peu5pCJVPQT3.wSy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE `votos` (
  `id_foro` int(11) NOT NULL,
  `id_apartamento` int(11) NOT NULL,
  `voto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  ADD PRIMARY KEY (`id_apartamento`),
  ADD UNIQUE KEY `num_letra_apartamento` (`num_letra_apartamento`),
  ADD KEY `propietario` (`propietario`),
  ADD KEY `inquilino` (`inquilino`),
  ADD KEY `id_tipo_apartamento` (`tipo_apartamento`) USING BTREE;

--
-- Indices de la tabla `apartamentos_lista_cargos`
--
ALTER TABLE `apartamentos_lista_cargos`
  ADD PRIMARY KEY (`id_apartamento`,`id_lista_cargos`),
  ADD KEY `apartamentos_lista_cargos_ibfk_2` (`id_lista_cargos`);

--
-- Indices de la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id_aviso`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_by` (`create_by`),
  ADD KEY `id_foro` (`id_foro`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`titulo`);

--
-- Indices de la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rif_cedula` (`rif_cedula`) USING BTREE;

--
-- Indices de la tabla `detalles_deudas`
--
ALTER TABLE `detalles_deudas`
  ADD KEY `id_deuda` (`id_deuda`);

--
-- Indices de la tabla `detalles_pagos`
--
ALTER TABLE `detalles_pagos`
  ADD PRIMARY KEY (`id_detalles_pagos`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `tipo_pago` (`tipo_pago`);

--
-- Indices de la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD PRIMARY KEY (`id_deuda`,`id_apartamento`),
  ADD KEY `id_apartamento` (`id_apartamento`),
  ADD KEY `id_distribucion` (`id_distribucion`);

--
-- Indices de la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `deuda_pagos`
--
ALTER TABLE `deuda_pagos`
  ADD PRIMARY KEY (`id_deuda`,`id_pago`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_apartamento` (`id_apartamento`),
  ADD KEY `id_deuda_condominio` (`id_deuda_condominio`);

--
-- Indices de la tabla `distribuciones`
--
ALTER TABLE `distribuciones`
  ADD PRIMARY KEY (`id_distribucion`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `divisa`
--
ALTER TABLE `divisa`
  ADD KEY `id_detalles_pagos` (`id_detalles_pagos`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`);

--
-- Indices de la tabla `estacionamiento`
--
ALTER TABLE `estacionamiento`
  ADD PRIMARY KEY (`num_estacionamiento`),
  ADD KEY `id_apartamento` (`id_apartamento`) USING BTREE;

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_by` (`create_by`);

--
-- Indices de la tabla `habitantes`
--
ALTER TABLE `habitantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cedula_rif` (`cedula_rif`) USING BTREE;

--
-- Indices de la tabla `lista_cargos_d`
--
ALTER TABLE `lista_cargos_d`
  ADD PRIMARY KEY (`id_lista_cargos`);

--
-- Indices de la tabla `lista_servicios`
--
ALTER TABLE `lista_servicios`
  ADD PRIMARY KEY (`id_servicios`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nomina_pago`
--
ALTER TABLE `nomina_pago`
  ADD PRIMARY KEY (`id_empleado`,`id_pago`),
  ADD KEY `nomina_pago_ibfk_2` (`id_pago`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `pago_deuda` (`deuda`) USING BTREE;

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pago_historial_estado`
--
ALTER TABLE `pago_historial_estado`
  ADD KEY `id_historial` (`id_historial`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id_comentario`,`id_respuestas`),
  ADD KEY `id_respuestas` (`id_respuestas`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_modulos`
--
ALTER TABLE `roles_modulos`
  ADD PRIMARY KEY (`id_rol`,`id_modulo`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `servicios_pagos`
--
ALTER TABLE `servicios_pagos`
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `tipo_apartamento`
--
ALTER TABLE `tipo_apartamento`
  ADD PRIMARY KEY (`id_tipo_apartamento`);

--
-- Indices de la tabla `tipo_cambio_divisa`
--
ALTER TABLE `tipo_cambio_divisa`
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id_tipo_pago`);

--
-- Indices de la tabla `transferencia`
--
ALTER TABLE `transferencia`
  ADD PRIMARY KEY (`id_detalles_pagos`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`id_foro`,`id_apartamento`),
  ADD KEY `id_apartamento` (`id_apartamento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  MODIFY `id_apartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id_aviso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT de la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_pagos`
--
ALTER TABLE `detalles_pagos`
  MODIFY `id_detalles_pagos` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `deudas`
--
ALTER TABLE `deudas`
  MODIFY `id_deuda` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `distribuciones`
--
ALTER TABLE `distribuciones`
  MODIFY `id_distribucion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la distribución';

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `habitantes`
--
ALTER TABLE `habitantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `lista_cargos_d`
--
ALTER TABLE `lista_cargos_d`
  MODIFY `id_lista_cargos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `lista_servicios`
--
ALTER TABLE `lista_servicios`
  MODIFY `id_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_apartamento`
--
ALTER TABLE `tipo_apartamento`
  MODIFY `id_tipo_apartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id_tipo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `transferencia`
--
ALTER TABLE `transferencia`
  MODIFY `id_detalles_pagos` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apartamento`
--
ALTER TABLE `apartamento`
  ADD CONSTRAINT `apartamento_ibfk_1` FOREIGN KEY (`propietario`) REFERENCES `habitantes` (`id`),
  ADD CONSTRAINT `apartamento_ibfk_2` FOREIGN KEY (`inquilino`) REFERENCES `habitantes` (`id`),
  ADD CONSTRAINT `apartamento_ibfk_3` FOREIGN KEY (`tipo_apartamento`) REFERENCES `tipo_apartamento` (`id_tipo_apartamento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `apartamentos_lista_cargos`
--
ALTER TABLE `apartamentos_lista_cargos`
  ADD CONSTRAINT `apartamentos_lista_cargos_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`),
  ADD CONSTRAINT `apartamentos_lista_cargos_ibfk_2` FOREIGN KEY (`id_lista_cargos`) REFERENCES `lista_cargos_d` (`id_lista_cargos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`user`) REFERENCES `datos_usuarios` (`id`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_foro`) REFERENCES `foro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`create_by`) REFERENCES `habitantes` (`id`);

--
-- Filtros para la tabla `detalles_deudas`
--
ALTER TABLE `detalles_deudas`
  ADD CONSTRAINT `detalles_deudas_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id_deuda`);

--
-- Filtros para la tabla `detalles_pagos`
--
ALTER TABLE `detalles_pagos`
  ADD CONSTRAINT `detalles_pagos_ibfk_1` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_pagos_ibfk_2` FOREIGN KEY (`tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`);

--
-- Filtros para la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD CONSTRAINT `deudas_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`),
  ADD CONSTRAINT `deudas_ibfk_2` FOREIGN KEY (`id_distribucion`) REFERENCES `distribuciones` (`id_distribucion`);

--
-- Filtros para la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  ADD CONSTRAINT `deuda_condominio_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `datos_usuarios` (`id`);

--
-- Filtros para la tabla `deuda_pagos`
--
ALTER TABLE `deuda_pagos`
  ADD CONSTRAINT `deuda_pagos_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id_deuda`),
  ADD CONSTRAINT `deuda_pagos_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`);

--
-- Filtros para la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  ADD CONSTRAINT `deuda_pendiente_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`),
  ADD CONSTRAINT `deuda_pendiente_ibfk_2` FOREIGN KEY (`id_deuda_condominio`) REFERENCES `deuda_condominio` (`id`);

--
-- Filtros para la tabla `divisa`
--
ALTER TABLE `divisa`
  ADD CONSTRAINT `divisa_ibfk_1` FOREIGN KEY (`id_detalles_pagos`) REFERENCES `detalles_pagos` (`id_detalles_pagos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estacionamiento`
--
ALTER TABLE `estacionamiento`
  ADD CONSTRAINT `estacionamiento_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `foro`
--
ALTER TABLE `foro`
  ADD CONSTRAINT `foro_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `habitantes` (`id`);

--
-- Filtros para la tabla `nomina_pago`
--
ALTER TABLE `nomina_pago`
  ADD CONSTRAINT `nomina_pago_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`empleado_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nomina_pago_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_deuda` FOREIGN KEY (`deuda`) REFERENCES `deuda_pendiente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos_usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `datos_usuarios` (`id`);

--
-- Filtros para la tabla `pago_historial_estado`
--
ALTER TABLE `pago_historial_estado`
  ADD CONSTRAINT `pago_historial_estado_ibfk_1` FOREIGN KEY (`id_historial`) REFERENCES `pagos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`id_respuestas`) REFERENCES `comentarios` (`id`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentarios` (`id`);

--
-- Filtros para la tabla `roles_modulos`
--
ALTER TABLE `roles_modulos`
  ADD CONSTRAINT `roles_modulos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `roles_modulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`);

--
-- Filtros para la tabla `servicios_pagos`
--
ALTER TABLE `servicios_pagos`
  ADD CONSTRAINT `servicios_pagos_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `lista_servicios` (`id_servicios`),
  ADD CONSTRAINT `servicios_pagos_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `transferencia`
--
ALTER TABLE `transferencia`
  ADD CONSTRAINT `transferencia_ibfk_1` FOREIGN KEY (`id_detalles_pagos`) REFERENCES `detalles_pagos` (`id_detalles_pagos`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `datos_usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `votos`
--
ALTER TABLE `votos`
  ADD CONSTRAINT `votos_ibfk_1` FOREIGN KEY (`id_foro`) REFERENCES `foro` (`id`),
  ADD CONSTRAINT `votos_ibfk_2` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
