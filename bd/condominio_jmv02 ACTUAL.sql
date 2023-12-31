-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-09-2023 a las 00:58:16
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `condominio_jmv02`
--

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
(24, 'D-08', 3, 2, '25', '4', 1);

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
(3, '27250544', 0, 'Xavier Suarez', 'Calle 28', '0424-5798958', '@gmail.com'),
(4, '28406750', 0, 'Luis Colmenares', 'Calle 28', '0426-3525659', '@gmail.com'),
(45, '00000000', 1, 'metauser', 'none', '0none', 'none@none.com');

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
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `rif_cedula` varchar(40) NOT NULL,
  `tipo_identificacion` int(1) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `domicilio` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `estado_civil` enum('Soltero','Casado','Divorciado','Viudo','Otros') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacionamiento`
--

CREATE TABLE `estacionamiento` (
  `num_estacionamiento` int(11) NOT NULL,
  `id_apartamento` int(11) DEFAULT NULL,
  `costo` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estacionamiento`
--

INSERT INTO `estacionamiento` (`num_estacionamiento`, `id_apartamento`, `costo`) VALUES
(1, 4, 2);

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
  `domicilio_fiscal` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `habitantes`
--

INSERT INTO `habitantes` (`id`, `cedula_rif`, `tipo_identificacion`, `nombres`, `apellidos`, `telefono`, `correo`, `domicilio_fiscal`) VALUES
(1, '28609560', 1, 'Jugney', 'Vargas', '0424-5681343', 'jugneyv@gmail.com', 'br union'),
(2, '26846371', 0, 'Diego Andres', 'Salazar Gonzalez', '0424-4034516', 'diego14asf@gmail.com', 'Calle 28 entre 19 y 20'),
(3, '10846157', 0, 'Juan', 'Jimenez', '0426-5551234', 'juanjimenez@gmail.com', 'Conjunto residencial jose maria vargas');

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
(9, 'nomina'),
(10, 'servicios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `id_nom` int(11) NOT NULL,
  `cedula_rif` varchar(10) NOT NULL,
  `tipo_identificacion` int(15) NOT NULL,
  `nombres` varchar(15) NOT NULL,
  `apellidos` varchar(15) NOT NULL,
  `domicilio` varchar(45) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `correo` varchar(20) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `metodo` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `monto` int(45) NOT NULL,
  `referencia` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nomina`
--

INSERT INTO `nomina` (`id_nom`, `cedula_rif`, `tipo_identificacion`, `nombres`, `apellidos`, `domicilio`, `telefono`, `correo`, `descripcion`, `metodo`, `fecha`, `monto`, `referencia`) VALUES
(1, '286666', 1, 'gcgc', 'cfgcfg', 'cfgcfg', 'cf51515', 'cfgfcg', 'cfgcfg', 'cfgcfg', '2023-09-13', 262266, 'cfgcfgcfg'),
(2, '15254452', 4242, '4242', '42424', '4242', '4242', '4242', '4242', '4242', '2023-09-21', 42424, '424242'),
(3, '54554545', 1, 'fghgfhg', 'fghgfhgf', 'gfhgfhfg', '0424-555555', 'hgjghjg@gmail.com', 'ghfghgfhgfh', 'pagomovil', '2023-09-07', 322, '12121');

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
(94, '1222', '2023-02-25', 'Pago Movil', '18.75', 110, 3, 'confirmado');

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
(2, 'Administrador'),
(3, 'superusuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_modulos`
--

CREATE TABLE `roles_modulos` (
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `crear` int(11) NOT NULL DEFAULT 1,
  `consultar` int(11) NOT NULL DEFAULT 1,
  `modificar` int(11) NOT NULL DEFAULT 1,
  `eliminar` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles_modulos`
--

INSERT INTO `roles_modulos` (`id_rol`, `id_modulo`, `crear`, `consultar`, `modificar`, `eliminar`) VALUES
(1, 1, 0, 1, 1, 0),
(2, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1),
(1, 3, 0, 1, 1, 0),
(2, 3, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 0),
(2, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 0, 0),
(2, 5, 1, 1, 1, 1),
(1, 6, 0, 1, 1, 0),
(2, 6, 1, 1, 1, 1),
(1, 7, 0, 0, 0, 0),
(2, 7, 1, 1, 1, 1),
(1, 8, 0, 1, 1, 0),
(2, 8, 1, 1, 1, 1),
(3, 1, 1, 1, 1, 1),
(3, 2, 1, 1, 1, 1),
(3, 3, 1, 1, 1, 1),
(3, 4, 1, 1, 1, 1),
(3, 5, 1, 1, 1, 1),
(3, 6, 1, 1, 1, 1),
(3, 7, 1, 1, 1, 1),
(3, 8, 1, 1, 1, 1),
(3, 9, 1, 1, 1, 1),
(3, 10, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicios` int(11) NOT NULL,
  `servicio` varchar(60) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `monto` double DEFAULT 0,
  `referencia` int(30) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicios`, `servicio`, `descripcion`, `fecha`, `monto`, `referencia`, `usuario_id`) VALUES
(1, 'corpoelec', '1212', '2023-09-05', 12.12, 121212, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_apartamento`
--

CREATE TABLE `tipo_apartamento` (
  `id_tipo_apartamento` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `alicuota` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_apartamento`
--

INSERT INTO `tipo_apartamento` (`id_tipo_apartamento`, `descripcion`, `alicuota`) VALUES
(1, 'Penthouse', 25),
(2, 'Duplex', 20),
(3, 'Departamento Básico', 30),
(4, 'Mezzanina', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicios`
--

CREATE TABLE `tipo_servicios` (
  `id_tipo_servicios` int(11) NOT NULL,
  `servicio` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 1, 'Hola123'),
(2, 1, 'Hola123'),
(3, 2, 'Hola123'),
(1, 1, 'Hola123'),
(45, 3, 'JoseMariaVargas');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  ADD PRIMARY KEY (`id_apartamento`),
  ADD KEY `propietario` (`propietario`),
  ADD KEY `inquilino` (`inquilino`),
  ADD KEY `id_tipo_apartamento` (`tipo_apartamento`) USING BTREE;

--
-- Indices de la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rif_cedula` (`rif_cedula`) USING BTREE;

--
-- Indices de la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_apartamento` (`id_apartamento`),
  ADD KEY `id_deuda_condominio` (`id_deuda_condominio`);

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
  ADD UNIQUE KEY `id_apartamento` (`id_apartamento`);

--
-- Indices de la tabla `habitantes`
--
ALTER TABLE `habitantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cedula_rif` (`cedula_rif`) USING BTREE;

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`id_nom`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `pago_deuda` (`deuda`) USING BTREE;

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_modulos`
--
ALTER TABLE `roles_modulos`
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicios`);

--
-- Indices de la tabla `tipo_apartamento`
--
ALTER TABLE `tipo_apartamento`
  ADD PRIMARY KEY (`id_tipo_apartamento`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  MODIFY `id_apartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `datos_usuarios`
--
ALTER TABLE `datos_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitantes`
--
ALTER TABLE `habitantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id_nom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_apartamento`
--
ALTER TABLE `tipo_apartamento`
  MODIFY `id_tipo_apartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Filtros para la tabla `deuda_condominio`
--
ALTER TABLE `deuda_condominio`
  ADD CONSTRAINT `deuda_condominio_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `datos_usuarios` (`id`);

--
-- Filtros para la tabla `deuda_pendiente`
--
ALTER TABLE `deuda_pendiente`
  ADD CONSTRAINT `deuda_pendiente_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`),
  ADD CONSTRAINT `deuda_pendiente_ibfk_2` FOREIGN KEY (`id_deuda_condominio`) REFERENCES `deuda_condominio` (`id`);

--
-- Filtros para la tabla `estacionamiento`
--
ALTER TABLE `estacionamiento`
  ADD CONSTRAINT `estacionamiento_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`id_apartamento`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_deuda` FOREIGN KEY (`deuda`) REFERENCES `deuda_pendiente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos_usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `roles_modulos`
--
ALTER TABLE `roles_modulos`
  ADD CONSTRAINT `roles_modulos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `roles_modulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `datos_usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
