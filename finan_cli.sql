-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2019 a las 01:10:36
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `finan_cli`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_x_mora`
--

CREATE TABLE IF NOT EXISTS `aviso_x_mora` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_credito` bigint(20) NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cadena`
--

CREATE TABLE IF NOT EXISTS `cadena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cuit_cuil` bigint(20) NOT NULL,
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre_fantasia` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `razon_social` (`razon_social`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `cadena`
--

INSERT INTO `cadena` (`id`, `razon_social`, `cuit_cuil`, `email`, `telefono`, `nombre_fantasia`) VALUES
(1, 'SISTEMAS', 999999999, 'nada@sistemas.com.ar', NULL, 'SISTEMAS'),
(5, 'PRUEBA', 2132345, '---', '0', 'BERRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nombres` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cuil_cuit` bigint(20) DEFAULT NULL,
  `fecha_nacimiento` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_alta` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_titular` int(11) DEFAULT NULL,
  `observaciones` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `monto_maximo_credito` int(11) NOT NULL,
  `id_perfil_credito` int(11) NOT NULL,
  PRIMARY KEY (`tipo_documento`,`documento`,`id`),
  KEY `id` (`id`,`id_titular`,`id_perfil_credito`),
  KEY `id_titular` (`id_titular`),
  KEY `id_perfil_credito` (`id_perfil_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_domicilio`
--

CREATE TABLE IF NOT EXISTS `cliente_x_domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_domicilio`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_telefono`
--

CREATE TABLE IF NOT EXISTS `cliente_x_telefono` (
  `id_telefono` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_telefono`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_estado_financiero`
--

CREATE TABLE IF NOT EXISTS `consulta_estado_financiero` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `resultado_xml` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE IF NOT EXISTS `credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cantidad_cuotas` int(11) NOT NULL,
  `monto_compra` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito_cliente`
--

CREATE TABLE IF NOT EXISTS `credito_cliente` (
  `id_credito` bigint(20) NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  PRIMARY KEY (`id_credito`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_credito`
--

CREATE TABLE IF NOT EXISTS `cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_credito` bigint(20) NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `fecha_vencimiento` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_cuota_original` int(11) NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha_pago` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_laboral_x_cliente`
--

CREATE TABLE IF NOT EXISTS `dato_laboral_x_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `empresa` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  `legajo` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `propietario` bit(1) DEFAULT NULL,
  `cargo` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `horario` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `observaciones` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`,`documento`,`id_domicilio`),
  KEY `documento` (`documento`),
  KEY `id_domicilio` (`id_domicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_laboral_x_telefono`
--

CREATE TABLE IF NOT EXISTS `dato_laboral_x_telefono` (
  `id_telefono` int(11) NOT NULL,
  `id_dato_laboral` int(11) NOT NULL,
  PRIMARY KEY (`id_telefono`,`id_dato_laboral`),
  KEY `id_dato_laboral` (`id_dato_laboral`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domicilio`
--

CREATE TABLE IF NOT EXISTS `domicilio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calle` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nro_calle` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL,
  `localidad` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `departamento` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `piso` int(11) DEFAULT NULL,
  `codigo_postal` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `entre_calle_1` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `entre_calle_2` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_provincia` (`id_provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=62 ;

--
-- Volcado de datos para la tabla `domicilio`
--

INSERT INTO `domicilio` (`id`, `calle`, `nro_calle`, `id_provincia`, `localidad`, `departamento`, `piso`, `codigo_postal`, `entre_calle_1`, `entre_calle_2`) VALUES
(1, 'S/N', 0, 1, 'SIN UBICACION', '---', 0, '---', '---', '---'),
(4, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(5, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(6, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(7, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(8, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(9, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(10, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(11, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(32, 'Linda', 1234, 1, 'PALERMO', NULL, NULL, '1000', 'ROBLE', 'PINO'),
(34, 'Sucre', 234, 2, 'Belice', 'A', 1, '2345', 'Ferni', 'Plaf'),
(36, 'zxzx', 12, 1, 'asas', 'asas', NULL, NULL, NULL, NULL),
(37, 'asas', 222, 1, 'dddd', NULL, NULL, NULL, NULL, NULL),
(38, 'adadad', 333, 1, 'dsdsd', NULL, NULL, NULL, NULL, NULL),
(52, 'fgjfgkj', 333, 1, 'asas', 'A', 0, 'SASAS', '---', 'fer'),
(54, 'ferr', 1212, 1, 'lklñk', '---', 1, '---', '---', '---'),
(56, 'asas1', 902, 2, 'asas123', 'z', 2, 'ferg', '---', '---'),
(61, 'ASSAS', 2323, 1, 'SDSD', '---', 0, '---', '---', '---');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cliente`
--

CREATE TABLE IF NOT EXISTS `estado_cliente` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_motivo` int(11) NOT NULL,
  `comentario` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`,`documento`,`id_motivo`),
  KEY `documento` (`documento`),
  KEY `id_motivo` (`id_motivo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes_x_mora`
--

CREATE TABLE IF NOT EXISTS `interes_x_mora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan_credito` int(11) NOT NULL,
  `interes` int(11) NOT NULL,
  `cantidad_dias` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `time` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ip_conexion` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`usuario`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `login_attempts`
--

INSERT INTO `login_attempts` (`usuario`, `time`, `ip_conexion`) VALUES
('admin_sys', '1557163634', '10.146.127.125'),
('admin_sys', '1557514483', '10.146.127.125');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_usuario`
--

CREATE TABLE IF NOT EXISTS `log_usuario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_motivo` int(11) NOT NULL,
  `valor` varchar(5000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`,`id_motivo`),
  KEY `id_motivo` (`id_motivo`),
  KEY `id_usuario_2` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=225 ;

--
-- Volcado de datos para la tabla `log_usuario`
--

INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(161, 'admin_sys', '20190503145335', 20, 'DELETE finan_cli.sucursal --> id: 9 - Codigo: 258 - Nombre: mANSU - id_domicilio: 62 - Email: FERA@mance.com.ar - Cadena: ---'),
(162, 'admin_sys', '20190503145335', 21, 'DELETE finan_cli.domicilio --> id: 62 - Calle: serv - Nro. Calle: 421 - Provincia: BUENOS AIRES - Localidad: juil - Departamento: A - Piso: 1 - Codigo Postal: 5000 - Entre Calle 1: fyui - Entre Calle 2: juio'),
(163, 'admin_sys', '20190503145451', 20, 'DELETE finan_cli.sucursal --> id: 7 - Codigo: 123 - Nombre: ASAS - id_domicilio: 60 - Email: --- - Cadena: ---'),
(164, 'admin_sys', '20190503145451', 21, 'DELETE finan_cli.domicilio --> id: 60 - Calle: asas - Nro. Calle: 122 - Provincia: CORDOBA - Localidad: dsdsd - Departamento: --- - Piso: --- - Codigo Postal: --- - Entre Calle 1: --- - Entre Calle 2: ---'),
(165, 'admin_sys', '20190503152804', 15, 'La sesión expiró: 2019-05-03 15:28:04'),
(166, 'admin_sys', '20190503152812', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-03 15:28:12'),
(167, 'admin_sys', '20190503192831', 15, 'La sesión expiró: 2019-05-03 19:28:31'),
(168, 'admin_sys', '20190503192840', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-03 19:28:40'),
(169, 'admin_sys', '20190503192942', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N1, nro_calle = 01, provincia = 1, localidad = SIN UBICACION1, departamento = 1, piso = 1, codigo_postal = 1, entre_calle_1 = 1, entre_calle_2 = 1'),
(170, 'admin_sys', '20190503192942', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = , nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio =  -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas1 codigo = 3451, email = ser@mas.com.ar, id_cadena = NULL, id_domicilio = '),
(171, 'admin_sys', '20190503193039', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N1, nro_calle = 01, provincia = 1, localidad = SIN UBICACION1, departamento = 1, piso = 1, codigo_postal = 1, entre_calle_1 = 1, entre_calle_2 = 1'),
(172, 'admin_sys', '20190503193039', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = , nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio =  -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas1 codigo = 3451, email = ser@mas.com.ar, id_cadena = NULL, id_domicilio = '),
(173, 'admin_sys', '20190503193127', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N1, nro_calle = 01, provincia = 1, localidad = SIN UBICACION1, departamento = 1, piso = 1, codigo_postal = 1, entre_calle_1 = 1, entre_calle_2 = 1'),
(174, 'admin_sys', '20190503193127', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = , nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio =  -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas1 codigo = 3451, email = ser@mas.com.ar, id_cadena = NULL, id_domicilio = '),
(175, 'admin_sys', '20190503193210', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N1, nro_calle = 01, provincia = 1, localidad = SIN UBICACION1, departamento = 1, piso = 1, codigo_postal = 1, entre_calle_1 = 1, entre_calle_2 = 1'),
(176, 'admin_sys', '20190503193210', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = , nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio =  -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas1 codigo = 3451, email = ser@mas.com.ar, id_cadena = 1, id_domicilio = '),
(177, 'admin_sys', '20190503193859', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N1, nro_calle = 01, provincia = 2, localidad = SIN UBICACION1, departamento = 1, piso = 1, codigo_postal = 1, entre_calle_1 = 1, entre_calle_2 = 1'),
(178, 'admin_sys', '20190503193859', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas1 codigo = 3451, email = ser@mas.com.ar, id_cadena = NULL, id_domicilio = 1'),
(179, 'admin_sys', '20190503193946', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = , nro_calle = , provincia = , localidad = ---, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(180, 'admin_sys', '20190503193946', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre =  codigo = , email = ---, id_cadena = ---, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ---, id_cadena = 5, id_domicilio = 1'),
(181, 'admin_sys', '20190503194018', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = CORDOBA, localidad = SIN UBICACION, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(182, 'admin_sys', '20190503194018', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre = assas codigo = 345, email = ---, id_cadena = 5, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ---, id_cadena = NULL, id_domicilio = 1'),
(183, 'admin_sys', '20190503194542', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = CORDOBA, localidad = SIN UBICACION, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(184, 'admin_sys', '20190503194542', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre = assas codigo = 345, email = ---, id_cadena = 5, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ser@mas.com.ar, id_cadena = NULL, id_domicilio = 1'),
(185, 'admin_sys', '20190503194608', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = CORDOBA, localidad = SIN UBICACION, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(186, 'admin_sys', '20190503194608', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre = assas codigo = 345, email = ser@mas.com.ar, id_cadena = ---, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ---, id_cadena = 1, id_domicilio = 1'),
(187, 'admin_sys', '20190503194615', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = CORDOBA, localidad = SIN UBICACION, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(188, 'admin_sys', '20190503194615', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre = assas codigo = 345, email = ---, id_cadena = 1, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ---, id_cadena = NULL, id_domicilio = 1'),
(189, 'admin_sys', '20190503194835', 2, 'Cierre de Sesion en Fecha y Hora: 2019-05-03 19:48:35'),
(190, 'admin_sys', '20190506121045', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-06 12:10:45'),
(191, 'admin_sys', '20190506142706', 15, 'La sesión expiró: 2019-05-06 14:27:06'),
(192, 'admin_sys', '20190506142718', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-06 14:27:18'),
(193, 'admin_sys', '20190506153212', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-06 15:32:12'),
(194, 'admin_sys', '20190506160236', 25, 'INSERT INTO finan_cli.perfil_credito(nombre,descripcion,monto_maximo) VALUES (Clasico,Perfil con condiciones crediticias clásicas.,1000000)'),
(195, 'admin_sys', '20190506160931', 25, 'INSERT INTO finan_cli.perfil_credito(nombre,descripcion,monto_maximo) VALUES (Especial,Perfil con condiciones crediticias especiales.,2000000)'),
(196, 'admin_sys', '20190506165930', 25, 'INSERT INTO finan_cli.perfil_credito(nombre,descripcion,monto_maximo) VALUES (borr,nada,123456)'),
(197, 'admin_sys', '20190506172746', 26, 'ANTERIOR: UPDATE finan_cli.perfil_credito SET nombre = borr, descripcion = nada, monto_maximo = 123456 WHERE id = 3 - NUEVO: UPDATE finan_cli.perfil_credito SET nombre = bor2, descripcion = nada2, monto_maximo = 123458 WHERE id = 3'),
(198, 'admin_sys', '20190506172802', 26, 'ANTERIOR: UPDATE finan_cli.perfil_credito SET nombre = bor2, descripcion = nada2, monto_maximo = 123458 WHERE id = 3 - NUEVO: UPDATE finan_cli.perfil_credito SET nombre = bor, descripcion = nada, monto_maximo = 123455 WHERE id = 3'),
(199, 'admin_sys', '20190507115511', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-07 11:55:11'),
(200, 'admin_sys', '20190507120325', 27, 'DELETE finan_cli.perfil_credito --> id: 3 - Nombre: bor - Descripcion: nada - Monto_Maximo: 123455'),
(201, 'admin_sys', '20190507131042', 15, 'La sesión expiró: 2019-05-07 13:10:42'),
(202, 'admin_sys', '20190508152839', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-08 15:28:39'),
(203, 'admin_sys', '20190508160122', 15, 'La sesión expiró: 2019-05-08 16:01:22'),
(204, 'admin_sys', '20190508160129', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-08 16:01:29'),
(205, 'admin_sys', '20190508174110', 15, 'La sesión expiró: 2019-05-08 17:41:10'),
(206, 'admin_sys', '20190508174114', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-08 17:41:14'),
(207, 'admin_sys', '20190510103435', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 10:34:35'),
(208, 'admin_sys', '20190510115111', 15, 'La sesión expiró: 2019-05-10 11:51:11'),
(209, 'admin_sys', '20190510115118', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 11:51:18'),
(210, 'admin_sys', '20190510120822', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (Plan 3 Cuotas Clasico,Es un plan de 3 cuotas clásico con diferimiento de cuota estricto.,3,10,5,5)'),
(211, 'admin_sys', '20190510123237', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 12:32:37'),
(212, 'admin_sys', '20190510123521', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (Plan 6 Cuotas Clasico,Es un plan de 6 cuotas clásico con un diferimiento estricto.,6,20,5,5)'),
(213, 'admin_sys', '20190510123748', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (Plan 1 Cuota Clasico,Plan de una cuota clásico con diferimiento estricto.,1,0,5,5)'),
(214, 'admin_sys', '20190510151623', 15, 'La sesión expiró: 2019-05-10 15:16:23'),
(215, 'admin_sys', '20190510151629', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 15:16:29'),
(216, 'admin_sys', '20190510155437', 15, 'La sesión expiró: 2019-05-10 15:54:37'),
(217, 'admin_sys', '20190510155450', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 15:54:50'),
(218, 'admin_sys', '20190510155755', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 3 Cuotas Clasico, descripcion = Es un plan de 3 cuotas clásico con diferimiento de cuota estricto., cantidad_cuotas = 3, interes_fijo = 10, id_tipo_diferimiento_cuota = 6, id_cadena = 1 WHERE id = 4 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = , descripcion = , cantidad_cuotas = , interes_fijo = , id_tipo_diferimiento_cuota = , id_cadena =  WHERE id = 4'),
(219, 'admin_sys', '20190510155821', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 3 Cuotas Clasicos, descripcion = Es un plan de 3 cuotas clásico con diferimiento de cuota estricto.s, cantidad_cuotas = 5, interes_fijo = 101, id_tipo_diferimiento_cuota = 5, id_cadena = 5 WHERE id = 4 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = , descripcion = , cantidad_cuotas = , interes_fijo = , id_tipo_diferimiento_cuota = , id_cadena =  WHERE id = 4'),
(220, 'admin_sys', '20190510155848', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 3 Cuotas Clasico, descripcion = Es un plan de 3 cuotas clásico con diferimiento de cuota estricto., cantidad_cuotas = 3, interes_fijo = 10, id_tipo_diferimiento_cuota = 6, id_cadena = 5 WHERE id = 4 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = , descripcion = , cantidad_cuotas = , interes_fijo = , id_tipo_diferimiento_cuota = , id_cadena =  WHERE id = 4'),
(221, 'admin_sys', '20190510160605', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 1 Cuota Clasico, descripcion = Plan de una cuota clásico con diferimiento estricto., cantidad_cuotas = 1, interes_fijo = 0, id_tipo_diferimiento_cuota = 6, id_cadena = 5 WHERE id = 6 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = Plan 1 Cuota Clasico, descripcion = Plan de una cuota clásico con diferimiento estricto., cantidad_cuotas = 1, interes_fijo = 0, id_tipo_diferimiento_cuota = 5, id_cadena = 5 WHERE id = 6'),
(222, 'admin_sys', '20190510162420', 30, 'DELETE finan_cli.plan_credito --> id: 6 - Nombre: Plan 1 Cuota Clasico - Descripcion: Plan de una cuota clásico con diferimiento estricto. - cantidad_cuotas = 1 - interes_fijo = 0 - id_tipo_diferimiento_cuota = 6 - id_cadena = 5 WHERE id = 6'),
(223, 'admin_sys', '20190510174305', 15, 'La sesión expiró: 2019-05-10 17:43:05'),
(224, 'admin_sys', '20190510174312', 1, 'Inicio de Sesion en Fecha y Hora: 2019-05-10 17:43:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mora_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `mora_cuota_credito` (
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha_interes` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_interes` int(11) NOT NULL,
  `porcentaje_interes` int(11) NOT NULL,
  PRIMARY KEY (`id_cuota_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo`
--

CREATE TABLE IF NOT EXISTS `motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=31 ;

--
-- Volcado de datos para la tabla `motivo`
--

INSERT INTO `motivo` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Inicio de Sesión', 'Cuando usuario inicia una sesión en el sistema.'),
(2, 'Cierre de Sesión', 'Cuando un usuario cierra su sesión en el sistema.'),
(3, 'Deshabilitar Usuario', 'Cuando un usuario deshabilita a otro.'),
(4, 'Nuevo Domicilio Usuario', 'Cuando un usuario registra un nuevo domicilio para un usuario.'),
(5, 'Borrar Domicilio Usuario', 'Cuando un usuario borra un domicilio asociado a un usuario.'),
(6, 'Modificar Domicilio Usuario', 'Cuando se modifica el domicilio de un usuario.'),
(7, 'Modificar Datos Usuario', 'Cuando se modifican los datos de un usuario.'),
(8, 'Nuevo Usuario', 'Cuando se registra un nuevo usuario en el sistema.'),
(9, 'Habilitar Usuario', 'Cuando un usuario habilita a otro.'),
(10, 'Nuevo Teléfono de Usuario', 'Cuando se registra un nuevo teléfono de usuario.'),
(11, 'Borrar Teléfono de Usuario', 'Cuando se borra un teléfono asociado a un usuario.'),
(12, 'Modificar Teléfono de Usuario', 'Cuando se modifica el teléfono de un usuario.'),
(13, 'Nueva Cadena', 'Cuando se registra una nueva cadena en el sistema.'),
(14, 'Borrar Cadena', 'Cuando se borra una cadena.'),
(15, 'Cierre de Sesion por Tiempo', 'Cuando una sesión de usuario expira por tiempo.'),
(16, 'Asignar Sucursales a Cadena', 'Cuando se agregan una o mas sucursales a una cadena.'),
(17, 'Desasingar Sucursal de Cadena', 'Cuando se desasigna una sucursal de una cadena.'),
(18, 'Modificar Cadena', 'Cuando se modifica una cadena.'),
(19, 'Nueva Sucursal', 'Cuando se registra una nueva sucursal en el sistema.'),
(20, 'Borrar Sucursal', 'Cuando se borra una sucursal.'),
(21, 'Borrar Domicilio Sucursal', 'Cuando se borrar un domicilio asociado a una sucursal.'),
(22, 'Nuevo Domicilio Sucursal', 'Cuando se registra un nuevo domicilio para una sucursal.'),
(23, 'Modificar Domicilio Sucursal', 'Cuando se modifica un domicilio asociado a una sucursal.'),
(24, 'Modificar Sucursal', 'Cuando se modifica una sucursal.'),
(25, 'Nuevo Perfil de Credito', 'Cuando se registra un nuevo perfil de crédito.'),
(26, 'Modificar Perfil de Credito', 'Cuando se modifica un perfil de crédito.'),
(27, 'Borrar Perfil de Credito', 'Cuando se borra un perfil de crédito.'),
(28, 'Nuevo Plan de Credito', 'Cuando se registra un nuevo plan de crédito.'),
(29, 'Modificar Plan de Credito', 'Cuando se modifica un plan de crédito.'),
(30, 'Borrar Plan de Credito', 'Cuando se borra un plan de crédito.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id`, `nombre`, `descripcion`, `valor`) VALUES
(1, 'cantidad_intentos_fallidos_inicio_sesion', 'Es la cantidad de veces en la cual se puede intentar loguear un usuario.', '5'),
(2, 'cantidad_horas_bloqueo_usuario', 'Es el tiempo en horas que se bloquea el usuario para volver a reintentar.', '2'),
(3, 'cantidad_domicilios_x_usuario_cliente', 'Es la cantidad de domicilios que se permiten cargar por usuario o cliente.', '5'),
(4, 'cantidad_telefonos_x_usuario_cliente', 'Es la cantidad de teléfonos por usuario o cliente que se permiten cargar.', '3'),
(5, 'tipo_diferimiento_cuota_estricto\r\n', 'Es el tipo de diferimiento del vencimiento de la primera cuota que se tiene que realizar un mes posterior a la compra. ', 'Estricto'),
(6, 'tipo_diferimiento_cuota_liviano', 'Es el tipo de diferimiento del vencimiento de la primera cuota que se tiene que realizar los primeros días del mes posterior con una distancia no menor a 30 días desde la fecha de compra. ', 'Liviano'),
(7, 'maxima_cantidad_cuotas_plan_credito', 'Es la cantidad máxima de cuotas permitidas para un plan de crédito.', '12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Administrador de la aplicación.'),
(2, 'Usuario Normal', 'El usuario común del sistema con los mínimos accesos necesarios para interectuar con el sistema.'),
(3, 'Supervisor', 'El usuario que autoriza acciones importantes en el sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_credito`
--

CREATE TABLE IF NOT EXISTS `perfil_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_maximo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `perfil_credito`
--

INSERT INTO `perfil_credito` (`id`, `nombre`, `descripcion`, `monto_maximo`) VALUES
(1, 'Clasico', 'Perfil con condiciones crediticias clásicas.', 1000000),
(2, 'Especial', 'Perfil con condiciones crediticias especiales.', 2000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_credito_x_plan`
--

CREATE TABLE IF NOT EXISTS `perfil_credito_x_plan` (
  `id_perfil_credito` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  PRIMARY KEY (`id_perfil_credito`,`id_plan_credito`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_credito`
--

CREATE TABLE IF NOT EXISTS `plan_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad_cuotas` int(11) NOT NULL,
  `interes_fijo` int(11) NOT NULL,
  `id_tipo_diferimiento_cuota` int(11) NOT NULL,
  `id_cadena` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_diferimiento_cuota` (`id_tipo_diferimiento_cuota`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `plan_credito`
--

INSERT INTO `plan_credito` (`id`, `nombre`, `descripcion`, `cantidad_cuotas`, `interes_fijo`, `id_tipo_diferimiento_cuota`, `id_cadena`) VALUES
(4, 'Plan 3 Cuotas Clasico', 'Es un plan de 3 cuotas clásico con diferimiento de cuota estricto.', 3, 10, 6, 5),
(5, 'Plan 6 Cuotas Clasico', 'Es un plan de 6 cuotas clásico con un diferimiento estricto.', 6, 20, 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE IF NOT EXISTS `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id`, `nombre`) VALUES
(1, 'CORDOBA'),
(2, 'BUENOS AIRES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_cadena` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `id_domicilio` (`id_domicilio`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `codigo`, `nombre`, `id_domicilio`, `email`, `id_cadena`) VALUES
(1, 999999, 'SISTEMAS', 1, NULL, 1),
(2, 345, 'assas', 1, '---', NULL),
(3, 456, 'fgrty', 1, NULL, 5),
(8, 457, 'GIRF', 61, '---', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE IF NOT EXISTS `telefono` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_telefono` int(11) NOT NULL,
  `numero` bigint(20) NOT NULL,
  `digitos_prefijo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_telefono` (`tipo_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=35 ;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`id`, `tipo_telefono`, `numero`, `digitos_prefijo`) VALUES
(11, 1, 3322342342, 3),
(13, 1, 433242, 2),
(14, 2, 12345678, 2),
(24, 1, 4514321212, 3),
(25, 2, 124545454, 2),
(30, 2, 12356789, 3),
(31, 2, 2347890, 3),
(33, 1, 23123234, 3),
(34, 1, 2323231, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES
(1, 'DNI'),
(2, 'PASAP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_telefono`
--

CREATE TABLE IF NOT EXISTS `tipo_telefono` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_telefono`
--

INSERT INTO `tipo_telefono` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Celular', 'Para teléfonos móviles.'),
(2, 'Fijo', 'Para teléfonos fijos.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `clave` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `salt` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_domicilio` (`id_perfil`,`id_sucursal`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `tipo_documento`, `documento`, `email`, `id_perfil`, `id_sucursal`, `estado`, `clave`, `salt`) VALUES
('admin_sys', 'SISTEMAS', 'ROOT', 1, '9999999', 'nada@sistemas.com.ar', 1, 1, 'Habilitado', '01cb65f711ac7c7a3f23c197684d71acdf4773f1639f536ec54d0712bb6f56176a303b058d455a3a554297f1d9478272993838282d1f9ccc1b97244d38c1a065', '2d5101134a3866e11d6a576250d613f7f799d44cf2763b71c77033febcedf9124f7a216da9c88193385d31ba113b078e79a5574855166b16f9b6b0deb864f14f'),
('her', 'herbasio', 'serio', 1, '2323211', 'ferd1@gmail.com', 2, 2, 'Habilitado', '317aad4981ba3d5e9b80613ede5907a674fd8fd385b16d7bf5ecd3e475f9e8d7499e8e2cbde6d146217083bf69f7f1f295cc532076db2d26f354dee836fedc3a', '4e5065a20bc762b15bcb0557e3f0d482ca512dde7a9f5633b1e5b28cb40b15f27335a1397feb78c635be30aa72f320aca6503cc9bfa2b0c0cc325a84fbde8686'),
('servi', 'gerad', 'jjkk', 1, '1234', 'ger@lib.com.ar', 2, 1, 'Deshabilitado', '7e698f194822ec8c2adb8f228fc9d806fbbb4c05df8ea8ed0a85d6332f75f3e3523bd6fddd391e398f853ae4ec21e8c78c044febe5cfd79b0dd479ce23d61d4f', 'd1a3a217f6af36c4286a2a14d194bce0f6d1278c5d54dbcd91273d0852868f428d8592a6b8f1b586d087418136edc3b58bf787fb39cec3486d9486f836760d65'),
('supervisor', 'Supervisa', 'TODO', 1, '1234123', 'teestamosobservando@segu.com', 3, 1, 'Habilitado', '30664284d8506bf354ba109da9e56c78bf038867db8a55fca72d2a1021ee5473a6d76d56c2d59f2e53825a6919a4fb3da0f057bca8c9fce3b6ad9c1a2cc1424e', 'fcbd814ca4f11378c0b2f38c30265e6ce290ff3d82a1dac1afa9d98fda501e49aab1365f4e634bc9db4df9a04651caae211233601860819efa052bf5a8bfe29c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_domicilio`
--

CREATE TABLE IF NOT EXISTS `usuario_x_domicilio` (
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_domicilio`),
  KEY `id_domicilio` (`id_domicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_x_domicilio`
--

INSERT INTO `usuario_x_domicilio` (`id_usuario`, `id_domicilio`) VALUES
('supervisor', 32),
('admin_sys', 34),
('supervisor', 36),
('supervisor', 37),
('supervisor', 38),
('admin_sys', 52),
('her', 54),
('servi', 56);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_telefono`
--

CREATE TABLE IF NOT EXISTS `usuario_x_telefono` (
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_telefono` int(11) NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_telefono`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_x_telefono`
--

INSERT INTO `usuario_x_telefono` (`id_usuario`, `id_telefono`, `descripcion`) VALUES
('admin_sys', 24, NULL),
('admin_sys', 25, NULL),
('admin_sys', 30, NULL),
('servi', 31, NULL),
('servi', 33, NULL),
('servi', 34, NULL),
('supervisor', 11, NULL),
('supervisor', 13, NULL),
('supervisor', 14, NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aviso_x_mora`
--
ALTER TABLE `aviso_x_mora`
  ADD CONSTRAINT `aviso_x_mora_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_perfil_credito`) REFERENCES `perfil_credito` (`id`);

--
-- Filtros para la tabla `cliente_x_domicilio`
--
ALTER TABLE `cliente_x_domicilio`
  ADD CONSTRAINT `cliente_x_domicilio_ibfk_1` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`),
  ADD CONSTRAINT `cliente_x_domicilio_ibfk_2` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `cliente_x_telefono`
--
ALTER TABLE `cliente_x_telefono`
  ADD CONSTRAINT `cliente_x_telefono_ibfk_1` FOREIGN KEY (`id_telefono`) REFERENCES `telefono` (`id`),
  ADD CONSTRAINT `cliente_x_telefono_ibfk_2` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `consulta_estado_financiero`
--
ALTER TABLE `consulta_estado_financiero`
  ADD CONSTRAINT `consulta_estado_financiero_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `credito_cliente`
--
ALTER TABLE `credito_cliente`
  ADD CONSTRAINT `credito_cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_5` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_6` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `cuota_credito`
--
ALTER TABLE `cuota_credito`
  ADD CONSTRAINT `cuota_credito_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`);

--
-- Filtros para la tabla `dato_laboral_x_cliente`
--
ALTER TABLE `dato_laboral_x_cliente`
  ADD CONSTRAINT `dato_laboral_x_cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `dato_laboral_x_cliente_ibfk_2` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`);

--
-- Filtros para la tabla `dato_laboral_x_telefono`
--
ALTER TABLE `dato_laboral_x_telefono`
  ADD CONSTRAINT `dato_laboral_x_telefono_ibfk_1` FOREIGN KEY (`id_telefono`) REFERENCES `telefono` (`id`),
  ADD CONSTRAINT `dato_laboral_x_telefono_ibfk_2` FOREIGN KEY (`id_dato_laboral`) REFERENCES `dato_laboral_x_cliente` (`id`);

--
-- Filtros para la tabla `domicilio`
--
ALTER TABLE `domicilio`
  ADD CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincia` (`id`);

--
-- Filtros para la tabla `estado_cliente`
--
ALTER TABLE `estado_cliente`
  ADD CONSTRAINT `estado_cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `estado_cliente_ibfk_2` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`);

--
-- Filtros para la tabla `interes_x_mora`
--
ALTER TABLE `interes_x_mora`
  ADD CONSTRAINT `interes_x_mora_ibfk_1` FOREIGN KEY (`id_plan_credito`) REFERENCES `plan_credito` (`id`);

--
-- Filtros para la tabla `log_usuario`
--
ALTER TABLE `log_usuario`
  ADD CONSTRAINT `log_usuario_ibfk_1` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`),
  ADD CONSTRAINT `log_usuario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `mora_cuota_credito`
--
ALTER TABLE `mora_cuota_credito`
  ADD CONSTRAINT `mora_cuota_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`);

--
-- Filtros para la tabla `perfil_credito_x_plan`
--
ALTER TABLE `perfil_credito_x_plan`
  ADD CONSTRAINT `perfil_credito_x_plan_ibfk_1` FOREIGN KEY (`id_perfil_credito`) REFERENCES `perfil_credito` (`id`),
  ADD CONSTRAINT `perfil_credito_x_plan_ibfk_2` FOREIGN KEY (`id_plan_credito`) REFERENCES `plan_credito` (`id`);

--
-- Filtros para la tabla `plan_credito`
--
ALTER TABLE `plan_credito`
  ADD CONSTRAINT `plan_credito_ibfk_2` FOREIGN KEY (`id_cadena`) REFERENCES `cadena` (`id`),
  ADD CONSTRAINT `plan_credito_ibfk_3` FOREIGN KEY (`id_tipo_diferimiento_cuota`) REFERENCES `parametros` (`id`);

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`),
  ADD CONSTRAINT `sucursal_ibfk_2` FOREIGN KEY (`id_cadena`) REFERENCES `cadena` (`id`);

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `telefono_ibfk_1` FOREIGN KEY (`tipo_telefono`) REFERENCES `tipo_telefono` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`);

--
-- Filtros para la tabla `usuario_x_domicilio`
--
ALTER TABLE `usuario_x_domicilio`
  ADD CONSTRAINT `usuario_x_domicilio_ibfk_2` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`),
  ADD CONSTRAINT `usuario_x_domicilio_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
