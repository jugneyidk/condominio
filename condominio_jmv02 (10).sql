-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-01-2024 a las 08:44:00
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
(1, 'A-01', 2, 4, '22', '1', 4),
(2, 'A-02', 1, NULL, '22', '2', 3),
(3, 'A-03', 4, NULL, '22', '3', 3),
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
(4, 'probando avisos 1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-12-02', '2023-12-03'),
(5, 'probando aviaso 2', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-12-02', '2023-12-03'),
(6, 'prueva 3', 'asñlfkajsñdflkajsñdlfajsñfljasñdlfas asd asdf asdfasd', '2023-11-30', '2023-12-01'),
(7, 'prueva 4', 'sdfasdfasdf asd fasd fasd dfs', '2023-12-03', '2023-12-04'),
(8, 'probando avisos', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2024-01-16', '2025-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` tinyint(1) DEFAULT NULL COMMENT 'hab (0), user (1)',
  `descrip` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `user_id`, `user_type`, `descrip`, `fecha`) VALUES
(353, 3, 1, 'Inicio sesion login', '2024-01-27 05:25:34'),
(354, NULL, NULL, 'Inicio sesion consulta', '2024-01-27 05:30:23'),
(355, 3, 1, 'Inicio sesion login', '2024-01-27 05:31:07'),
(356, 3, 1, 'Inicio sesion login', '2024-01-27 05:32:56'),
(357, NULL, NULL, 'Inicio sesion consulta', '2024-01-27 05:33:31'),
(358, 1, 0, 'Inicio sesion', '2024-01-27 05:36:08'),
(359, 3, 1, 'Inicio sesion login', '2024-01-27 05:37:16'),
(360, 3, 1, 'Inicio sesion login', '2024-01-27 05:38:24'),
(361, NULL, NULL, 'Inicio sesion', '2024-01-27 06:02:44'),
(362, NULL, NULL, 'Inicio sesion', '2024-01-27 23:37:02'),
(363, 1, 0, 'Inicio sesion', '2024-01-28 02:28:45'),
(364, 1, 0, 'Inicio sesion', '2024-01-28 02:32:39'),
(365, 3, 1, 'Inicio sesion login', '2024-01-28 03:03:23'),
(366, NULL, NULL, 'Inicio sesion', '2024-01-28 03:22:09'),
(367, 3, 1, 'Inicio sesion login', '2024-01-28 03:23:01'),
(368, 1, 0, 'Inicio sesion', '2024-01-28 03:28:26'),
(369, 3, 1, 'Inicio sesion deudas', '2024-01-28 04:35:48'),
(370, NULL, NULL, 'Inicio sesion', '2024-01-28 04:41:38'),
(371, 1, 0, 'Inicio sesion', '2024-01-28 04:44:05'),
(372, 3, 1, 'Inicio sesion login', '2024-01-28 04:44:22'),
(373, 3, 1, 'Inicio sesion login', '2024-01-28 07:39:04'),
(374, NULL, NULL, 'Inicio sesion', '2024-01-28 07:39:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` bigint(20) NOT NULL,
  `id_post` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `create_by` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_post`, `comentario`, `create_by`, `fecha`) VALUES
(1, 1, 'hola como estas', 2, '2023-12-02 18:07:40'),
(2, 2, 'hola', 2, '2023-12-03 23:26:39'),
(3, 1, 'xavier no XD', 1, '2023-12-05 05:55:38'),
(4, 1, 'xxxxxxxxx', 1, '2024-01-16 08:20:53'),
(5, 2, 'xxxxxxxxxxx', 1, '2024-01-16 09:12:30'),
(6, 1, 'saldhfañsldfhañsldkfjasdas asdf asdfa', 1, '2024-01-16 09:13:00'),
(7, 1, 'xxxxxxxxxxx', 1, '2024-01-16 09:14:10'),
(8, 1, 'hhhhhhhhhh', 1, '2024-01-16 09:14:19'),
(9, 1, 'hola', 1, '2024-01-16 09:15:17'),
(10, 2, 'hola', 1, '2024-01-17 10:43:57'),
(11, 2, 'como estas\r\n', 1, '2024-01-17 10:44:07'),
(12, 2, 'hola\r\n', 1, '2024-01-28 03:09:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `titulo` varchar(30) NOT NULL,
  `valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(3, '27250544', 0, 'Xavier Suarez', 'Calle 28', '0424-5798958', 'david40ene@hotmail.com'),
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

--
-- Volcado de datos para la tabla `detalles_deudas`
--

INSERT INTO `detalles_deudas` (`id_deuda`, `concepto`, `monto`, `tipo_monto`, `fecha`) VALUES
(808, 'Estacionamiento', '7.00', 1, '2023-11-24 03:12:54'),
(802, 'Estacionamiento', '25.00', 1, '2023-11-24 03:12:54'),
(811, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:12:54'),
(814, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:12:54'),
(817, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:12:54'),
(823, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:12:54'),
(824, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:12:54'),
(802, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:12:54'),
(803, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:12:54'),
(820, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:12:54'),
(822, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:12:54'),
(801, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(806, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(807, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(810, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(812, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(813, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(816, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(825, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:12:54'),
(804, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(805, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(808, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(809, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(815, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(818, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(819, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(821, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:12:54'),
(808, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(801, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(825, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(802, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(817, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(818, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(809, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(810, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(803, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(811, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(819, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(804, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(812, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(813, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(820, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(814, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(815, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(805, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(821, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(816, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(806, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(807, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(822, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(823, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(824, 'probando otros', '8888.88', 0, '2023-11-24 03:12:54'),
(808, 'combinaciones', '1.00', 1, '2023-11-24 03:12:54'),
(801, 'combinaciones', '1.00', 1, '2023-11-24 03:12:54'),
(825, 'combinaciones', '1.00', 1, '2023-11-24 03:12:54'),
(802, 'combinaciones', '1.00', 1, '2023-11-24 03:12:54'),
(808, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(801, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(825, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(802, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(817, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(818, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(809, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(810, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(803, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(811, 'xxxxxxx', '0.00', 0, '2023-11-24 03:12:54'),
(839, 'Estacionamiento', '7.00', 1, '2023-11-24 03:14:33'),
(833, 'Estacionamiento', '25.00', 1, '2023-11-24 03:14:33'),
(842, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:14:33'),
(845, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:14:33'),
(848, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:14:33'),
(854, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:14:33'),
(855, 'Apartamento tipo Penthouse', '25.00', 1, '2023-11-24 03:14:33'),
(833, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:14:33'),
(834, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:14:33'),
(851, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:14:33'),
(853, 'Apartamento tipo Duplex', '20.00', 1, '2023-11-24 03:14:33'),
(832, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(837, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(838, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(841, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(843, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(844, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(847, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(856, 'Apartamento tipo Departamento Básico', '30.00', 1, '2023-11-24 03:14:33'),
(835, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(836, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(839, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(840, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(846, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(849, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(850, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(852, 'Apartamento tipo Mezzanina', '25.00', 1, '2023-11-24 03:14:33'),
(839, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(832, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(856, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(833, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(848, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(849, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(840, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(841, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(834, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(842, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(850, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(835, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(843, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(844, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(851, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(845, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(846, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(836, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(852, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(847, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(837, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(838, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(853, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(854, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(855, 'probando otros', '8888.88', 0, '2023-11-24 03:14:33'),
(839, 'combinaciones', '1.00', 1, '2023-11-24 03:14:33'),
(832, 'combinaciones', '1.00', 1, '2023-11-24 03:14:33'),
(856, 'combinaciones', '1.00', 1, '2023-11-24 03:14:33'),
(833, 'combinaciones', '1.00', 1, '2023-11-24 03:14:33'),
(839, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(832, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(856, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(833, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(848, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(849, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(840, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(841, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(834, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33'),
(842, 'xxxxxxx', '0.00', 0, '2023-11-24 03:14:33');

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
  `fecha_generada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_monto` tinyint(1) NOT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `emisor` varchar(60) DEFAULT NULL,
  `cedula_rif` varchar(20) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalles_pagos`
--

INSERT INTO `detalles_pagos` (`id_detalles_pagos`, `id_pago`, `tipo_pago`, `monto`, `fecha`, `fecha_generada`, `tipo_monto`, `numero`, `emisor`, `cedula_rif`, `telefono`) VALUES
(1, 1, 1, '9989.67', '2023-11-30 04:30:00', '2023-12-01 01:48:48', 0, NULL, NULL, NULL, NULL),
(2, 2, 2, '10522.32', '2023-11-30 04:30:00', '2023-12-01 02:00:27', 0, '123456', 'banplus', 'V-27250544', NULL),
(9, 9, 1, '9600.48', '2023-12-01 04:30:00', '2023-12-02 03:12:45', 0, NULL, NULL, NULL, NULL),
(10, 10, 1, '10525.56', '2023-12-01 04:30:00', '2023-12-02 03:20:35', 0, NULL, NULL, NULL, NULL),
(11, 11, 1, '10525.56', '2023-12-01 04:30:00', '2023-12-02 03:36:04', 0, NULL, NULL, NULL, NULL),
(12, 12, 1, '10525.56', '2023-12-01 04:30:00', '2023-12-02 03:38:07', 0, NULL, NULL, NULL, NULL),
(13, 13, 1, '20.00', '2023-12-07 04:30:00', '2023-12-07 15:37:12', 0, NULL, NULL, NULL, NULL),
(14, 14, 1, '0.00', '2023-12-07 04:30:00', '2023-12-07 15:37:22', 0, NULL, NULL, NULL, NULL),
(15, 15, 1, '0.01', '2023-12-07 04:30:00', '2023-12-07 15:42:02', 0, NULL, NULL, NULL, NULL);

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
  `moroso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `deudas`
--

INSERT INTO `deudas` (`id_deuda`, `id_apartamento`, `id_distribucion`, `moroso`) VALUES
(801, 2, 30, 1),
(802, 4, 30, 1),
(803, 9, 30, 1),
(804, 12, 30, 1),
(805, 18, 30, 1),
(806, 21, 30, 1),
(807, 22, 30, 1),
(808, 1, 30, 1),
(809, 7, 30, 1),
(810, 8, 30, 1),
(811, 10, 30, 1),
(812, 13, 30, 1),
(813, 14, 30, 1),
(814, 16, 30, 1),
(815, 17, 30, 1),
(816, 20, 30, 1),
(817, 5, 30, 1),
(818, 6, 30, 1),
(819, 11, 30, 1),
(820, 15, 30, 1),
(821, 19, 30, 1),
(822, 23, 30, 1),
(823, 24, 30, 1),
(824, 25, 30, 1),
(825, 3, 30, 1),
(832, 2, 31, 1),
(833, 4, 31, 1),
(834, 9, 31, 1),
(835, 12, 31, 1),
(836, 18, 31, 1),
(837, 21, 31, 1),
(838, 22, 31, 1),
(839, 1, 31, 1),
(840, 7, 31, 1),
(841, 8, 31, 1),
(842, 10, 31, 1),
(843, 13, 31, 1),
(844, 14, 31, 1),
(845, 16, 31, 1),
(846, 17, 31, 1),
(847, 20, 31, 1),
(848, 5, 31, 1),
(849, 6, 31, 1),
(850, 11, 31, 1),
(851, 15, 31, 1),
(852, 19, 31, 1),
(853, 23, 31, 1),
(854, 24, 31, 1),
(855, 25, 31, 1),
(856, 3, 31, 1);

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

--
-- Volcado de datos para la tabla `deuda_pagos`
--

INSERT INTO `deuda_pagos` (`id_deuda`, `id_pago`) VALUES
(802, 10),
(832, 1),
(833, 2),
(833, 11),
(833, 12),
(834, 9);

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

--
-- Volcado de datos para la tabla `distribuciones`
--

INSERT INTO `distribuciones` (`id_distribucion`, `fecha`, `concepto`, `usuario`) VALUES
(30, '2023-11-23', 'Factura del mes de noviembre 2023', 3),
(31, '2023-12-24', 'Factura del mes de diciembre 2023', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisa`
--

CREATE TABLE `divisa` (
  `id_detalles_pagos` bigint(20) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `denominacion` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(1, 1, '7.00'),
(2, 4, '25.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` int(11) NOT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT '0',
  `visto` tinyint(1) NOT NULL DEFAULT '0',
  `votaciones` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `foro`
--

INSERT INTO `foro` (`id`, `titulo`, `descripcion`, `fecha`, `create_by`, `aprobado`, `visto`, `votaciones`) VALUES
(1, 'hola', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-09-18 17:32:37', 2, 1, 0, 0),
(2, 'probando foro aprobado', 'si le da a alguno de las votaciones se perdera el estado de aprobado', '2023-12-02 22:46:00', 2, 1, 0, 0);

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
  `clave` varchar(255) NOT NULL DEFAULT '123456'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `habitantes`
--

INSERT INTO `habitantes` (`id`, `cedula_rif`, `tipo_identificacion`, `nombres`, `apellidos`, `telefono`, `correo`, `domicilio_fiscal`, `clave`) VALUES
(1, '28609560', 1, 'Jugney', 'Vargas', '0424-5681343', 'jugneyv@gmail.com', 'br union', '$2y$10$WqFXtm.SuvZI6kHjMxwLNuxpVqIKAGKqqQDVFXu6W928AunJy9YDS'),
(2, '26846371', 0, 'Diego Andres', 'Salazar Gonzalez', '0424-4034516', 'diego14asf@gmail.com', 'Calle 28 entre 19 y 20', '$2y$10$WqFXtm.SuvZI6kHjMxwLNuxpVqIKAGKqqQDVFXu6W928AunJy9YDS'),
(3, '10846157', 0, 'Juan', 'Jimenez', '0426-5551234', 'juanjimenez@gmail.com', 'Conjunto residencial jose maria vargas', '$2y$10$WqFXtm.SuvZI6kHjMxwLNuxpVqIKAGKqqQDVFXu6W928AunJy9YDS'),
(4, '77777777', 1, 'xavier D', 'suarez', '0414-5555555', 'uptaebxavier@gmail.com', 'mi casa', '$2y$10$WqFXtm.SuvZI6kHjMxwLNuxpVqIKAGKqqQDVFXu6W928AunJy9YDS'),
(6, '7777777', 0, 'probando prepare MODIF CLAVE', 'probando MODIFICANDO', '0414-5555555', 'algo@asdf.com', 'mi casa', '$2y$10$WqFXtm.SuvZI6kHjMxwLNuxpVqIKAGKqqQDVFXu6W928AunJy9YDS');

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
(13, 'probando otros', '8888.88', 0, 1, 0, 1),
(14, 'combinaciones', '1.00', 1, 0, 0, 1),
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
(1, '9989.67', '', 2, 3),
(2, '10522.32', '', 1, 3),
(9, '9600.48', '', 2, 3),
(10, '10525.56', '', 0, NULL),
(11, '10525.56', '', 1, 3),
(12, '10525.56', '', 0, NULL),
(13, '20.00', 'hola', 2, 3),
(14, '0.00', '13113', 2, 3),
(15, '0.01', '250', 2, 3);

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
(2, 13),
(1, 14),
(1, 15);

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
('2023-10-19 08:02:49', '34.86'),
('2023-11-26 08:41:44', '35.48'),
('2023-12-01 01:13:31', '35.51'),
('2023-12-02 05:46:57', '35.58'),
('2024-01-15 01:40:11', '36.04'),
('2024-01-16 18:47:55', '36.08'),
('2024-01-27 05:16:39', '36.20'),
('2024-01-27 05:21:24', '36.20');

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
(3, 2, '$2y$10$fo/Nsq0fikqxsEbHAN14ee7ohbf.zpaY8ychh38hAgkLOSzwv2CKC'),
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(1, 1, '$2y$10$6yhH2jjE5YEPAILI9Uf8suyQn8IpPJP/z2uRmctI1xP/BrFGKWwXm'),
(2, 1, '$2y$10$xJrc2hrFMX8FgdvlFyJApOz8qdYXQpmll5FFy3VI7Gw5dAAq4ubD6'),
(3, 2, '$2y$10$fo/Nsq0fikqxsEbHAN14ee7ohbf.zpaY8ychh38hAgkLOSzwv2CKC'),
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
-- Volcado de datos para la tabla `votos`
--

INSERT INTO `votos` (`id_foro`, `id_apartamento`, `voto`) VALUES
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(1, 8, 0),
(1, 9, 0),
(1, 12, 0),
(1, 18, 0),
(1, 21, 0),
(1, 22, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  ADD PRIMARY KEY (`id_apartamento`),
  ADD UNIQUE KEY `num_letra_apartamento` (`num_letra_apartamento`),
  ADD UNIQUE KEY `inquilino` (`inquilino`) USING BTREE,
  ADD KEY `propietario` (`propietario`),
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
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_by` (`create_by`),
  ADD KEY `id_foro` (`id_post`);

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
  ADD PRIMARY KEY (`id_pago`) USING BTREE,
  ADD KEY `id_deuda` (`id_deuda`) USING BTREE;

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
  MODIFY `id_aviso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_pagos`
--
ALTER TABLE `detalles_pagos`
  MODIFY `id_detalles_pagos` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `deudas`
--
ALTER TABLE `deudas`
  MODIFY `id_deuda` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=857;

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
  MODIFY `id_distribucion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la distribución', AUTO_INCREMENT=32;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id_pago` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `foro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`create_by`) REFERENCES `habitantes` (`id`);

--
-- Filtros para la tabla `detalles_deudas`
--
ALTER TABLE `detalles_deudas`
  ADD CONSTRAINT `detalles_deudas_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id_deuda`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `deudas_ibfk_2` FOREIGN KEY (`id_distribucion`) REFERENCES `distribuciones` (`id_distribucion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  ADD CONSTRAINT `deuda_condominio_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `datos_usuarios` (`id`);

--
-- Filtros para la tabla `deuda_pagos`
--
ALTER TABLE `deuda_pagos`
  ADD CONSTRAINT `deuda_pagos_ibfk_1` FOREIGN KEY (`id_deuda`) REFERENCES `deudas` (`id_deuda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deuda_pagos_ibfk_2` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

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
