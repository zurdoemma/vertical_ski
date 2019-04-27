-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2019 a las 02:04:52
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
  `fecha` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cadena`
--

CREATE TABLE IF NOT EXISTS `cadena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `cuit_cuil` bigint(20) NOT NULL,
  `email` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre_fantasia` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `razon_social` (`razon_social`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `cadena`
--

INSERT INTO `cadena` (`id`, `razon_social`, `cuit_cuil`, `email`, `telefono`, `nombre_fantasia`) VALUES
(1, 'SISTEMAS', 999999999, 'nada@sistemas.com.ar', NULL, 'SISTEMAS'),
(2, 'ferni', 23232, NULL, '123', 'asas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `nombres` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `apellidos` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `cuil_cuit` bigint(20) DEFAULT NULL,
  `fecha_nacimiento` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fecha_alta` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `id_titular` int(11) DEFAULT NULL,
  `observaciones` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
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
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
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
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
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
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `resultado_xml` text COLLATE latin1_spanish_ci NOT NULL,
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
  `fecha` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `id_usuario` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_perfil_credito` int(11) NOT NULL,
  PRIMARY KEY (`id_credito`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_perfil_credito` (`id_perfil_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_credito`
--

CREATE TABLE IF NOT EXISTS `cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_credito` bigint(20) NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `fecha_vencimiento` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `monto_cuota_original` int(11) NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_pago` char(14) COLLATE latin1_spanish_ci NOT NULL,
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
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `empresa` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  `legajo` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `propietario` bit(1) DEFAULT NULL,
  `cargo` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `horario` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `email` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `observaciones` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
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
  `calle` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `nro_calle` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL,
  `localidad` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `departamento` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL,
  `piso` int(11) DEFAULT NULL,
  `codigo_postal` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL,
  `entre_calle_1` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  `entre_calle_2` varchar(150) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_provincia` (`id_provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=41 ;

--
-- Volcado de datos para la tabla `domicilio`
--

INSERT INTO `domicilio` (`id`, `calle`, `nro_calle`, `id_provincia`, `localidad`, `departamento`, `piso`, `codigo_postal`, `entre_calle_1`, `entre_calle_2`) VALUES
(1, 'S/N', 0, 1, 'SIN UBICACION', NULL, NULL, NULL, NULL, NULL),
(4, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(5, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(6, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(7, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(8, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(9, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(10, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(11, 'Mas una', 124, 1, 'lkkj', NULL, NULL, 'asdf123', 'kljlkjlk', 'Ã±lklÃ±lÃ±'),
(19, 'sas', 1, 1, 'asas', NULL, NULL, NULL, NULL, NULL),
(20, 'asasa', 1, 1, 'dsfdfd', NULL, NULL, NULL, NULL, NULL),
(32, 'Linda', 1234, 1, 'PALERMO', NULL, NULL, '1000', 'ROBLE', 'PINO'),
(33, 'derf', 3456, 1, 'sare', NULL, NULL, '4356', NULL, NULL),
(34, 'Sucre', 234, 2, 'Belice', 'A', 1, '2345', 'Ferni', 'Plaf'),
(36, 'zxzx', 12, 1, 'asas', 'asas', NULL, NULL, NULL, NULL),
(37, 'asas', 222, 1, 'dddd', NULL, NULL, NULL, NULL, NULL),
(38, 'adadad', 333, 1, 'dsdsd', NULL, NULL, NULL, NULL, NULL),
(40, 'sadsad', 2222, 1, 'sdsd', NULL, 1, '3456', 'ss', 'dfff');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cliente`
--

CREATE TABLE IF NOT EXISTS `estado_cliente` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `id_motivo` int(11) NOT NULL,
  `comentario` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
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
  `usuario` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `time` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `ip_conexion` varchar(128) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`usuario`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `login_attempts`
--

INSERT INTO `login_attempts` (`usuario`, `time`, `ip_conexion`) VALUES
('admin_sys', '1554926892', '10.146.127.125'),
('admin_sys', '1555333334', '10.146.127.125'),
('admin_sys', '1555954864', '10.146.127.125'),
('admin_sys', '1556023912', '10.146.127.125'),
('admin_sys', '1556045716', '10.146.127.125'),
('admin_sys', '1556053193', '10.146.127.125'),
('supervisor', '1556052248', '10.146.127.125');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_usuario`
--

CREATE TABLE IF NOT EXISTS `log_usuario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` char(14) COLLATE latin1_spanish_ci NOT NULL,
  `id_motivo` int(11) NOT NULL,
  `valor` varchar(5000) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`,`id_motivo`),
  KEY `id_motivo` (`id_motivo`),
  KEY `id_usuario_2` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=248 ;

--
-- Volcado de datos para la tabla `log_usuario`
--

INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(163, 'admin_sys', '20190423173723', 3, 'El usuario: supervisor, fue deshabilitado el: 2019-04-23 17:37:23, por el usuario: admin_sys!!'),
(164, 'admin_sys', '20190423173736', 9, 'El usuario: supervisor, fue habilitado el: 2019-04-23 17:37:36, por el usuario: admin_sys!!'),
(165, 'admin_sys', '20190423174400', 2, 'Cierre de Sesion en Fecha y Hora: 2019-04-23 17:44:00'),
(166, 'supervisor', '20190423174413', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 17:44:13'),
(167, 'supervisor', '20190423174457', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 12341234, email = teestamosobservando@segu.com, perfil = Administrador, sucursal = SISTEMAS  -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 12341234, email = teestamosobservando@segu.com, id_perfil = 3, id_sucursal = 1 WHERE id =supervisor'),
(168, 'supervisor', '20190423174517', 2, 'Cierre de Sesion en Fecha y Hora: 2019-04-23 17:45:17'),
(169, 'supervisor', '20190423174524', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 17:45:24'),
(170, 'supervisor', '20190423175624', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 12341234, email = teestamosobservando@segu.com -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 12341234, email = teestamosobservando@segu.com WHERE id =supervisor'),
(171, 'supervisor', '20190423175643', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 12341234, email = teestamosobservando@segu.com -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 1234123, email = teestamosobservando@segu.com WHERE id =supervisor'),
(172, 'supervisor', '20190423175707', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (sdsd,22,1,sdsd,NULL,NULL,NULL,NULL,NULL)'),
(173, 'supervisor', '20190423175714', 5, '0'),
(174, 'supervisor', '20190423175730', 6, 'ANTERIOR: id = 32, calle = Linda, nro_calle = 123, provincia = BUENOS AIRES, localidad = PALERMO, departamento = , piso = , codigo_postal = 1000, entre_calle_1 = ROBLE, entre_calle_2 = PINO  -- NUEVO: UPDATE finan_cli.domicilio SET calle = Linda, nro_calle = 1234, id_provincia = 1, localidad = PALERMO, departamento = NULL, piso = NULL, codigo_postal = 1000, entre_calle_1 = ROBLE, entre_calle_2 = PINO WHERE id =32'),
(175, 'supervisor', '20190423175837', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 1234123, email = teestamosobservando@segu.com -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 1234123, email = teestamosobservando@segu.com, clave =623481f466bb2ea88e6cd65a400c146a25593d1a66da5fcf326b92ad85f34207c0952fe5879a691c362cd335417fc0d5b3e7b6560f8b4790ccff875b8efb625a, salt =26eed5138588a68c36f9740d8b8fc4e5997e993ae5bf94b5bcad10007982e36734b749f901489141d1b9098b8280898f623aa2ea0be3d10b0d0ca228ad5b1673 WHERE id =supervisor'),
(176, 'supervisor', '20190423175908', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 17:59:08'),
(177, 'supervisor', '20190423175948', 2, 'Cierre de Sesion en Fecha y Hora: 2019-04-23 17:59:48'),
(178, 'admin_sys', '20190423175959', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 17:59:59'),
(179, 'admin_sys', '20190423180022', 7, 'ANTERIOR: id = admin_sys, nombre = SISTEMAS, apellido = ROOT, tipo_documento = DNI, documento = 9999999, email = nada@sistemas.com.ar -- NUEVO: UPDATE finan_cli.usuario SET nombre = SISTEMAS, apellido = ROOT, tipo_documento = 1, documento = 9999999, email = nada@sistemas.com.ar, clave =93bb51d9533786fc3829733934e1cae40bb39004c76b2fd1713347930cb4f8c8b3ec7768d8501670316e6d0442cafde359737a8619574da36d0d6ad6062269b9, salt =8e7c1afb2e7ef7ead69c130ee767f0e7ec55a6e4dab47f07cd2e83dda811d763089f8d9717cb83188c040e71f238646700e37a0b6af8b8cc1f0fa166ac299e4d WHERE id =admin_sys'),
(180, 'admin_sys', '20190423180032', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 18:00:32'),
(181, 'admin_sys', '20190423180039', 3, 'El usuario: supervisor, fue deshabilitado el: 2019-04-23 18:00:39, por el usuario: admin_sys!!'),
(182, 'admin_sys', '20190423180044', 2, 'Cierre de Sesion en Fecha y Hora: 2019-04-23 18:00:44'),
(183, 'admin_sys', '20190423180115', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-23 18:01:15'),
(184, 'admin_sys', '20190424104554', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-24 10:45:54'),
(185, 'admin_sys', '20190424105104', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 1234123, email = teestamosobservando@segu.com, perfil = Supervisor, sucursal = SISTEMAS  -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 1234123, email = teestamosobservando@segu.com, id_perfil = 3, id_sucursal = 1 WHERE id =supervisor'),
(186, 'admin_sys', '20190424123740', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (zxzx,12,1,asas,asas,NULL,NULL,NULL,NULL)'),
(187, 'admin_sys', '20190424123746', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (asas,222,1,dddd,NULL,NULL,NULL,NULL,NULL)'),
(188, 'admin_sys', '20190424123754', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (adadad,333,1,dsdsd,NULL,NULL,NULL,NULL,NULL)'),
(189, 'admin_sys', '20190424123803', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (sdfgfhgfh,456,1,assas,NULL,NULL,NULL,NULL,NULL)'),
(190, 'admin_sys', '20190424123825', 5, '0'),
(191, 'admin_sys', '20190424143607', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (2,456234567)'),
(192, 'admin_sys', '20190424143735', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,345667890)'),
(193, 'admin_sys', '20190424152119', 11, 'DELETE finan_cli.telefono --> id: 1 - Tipo Telefono: 1 - Nro. Telefono: 3513814587'),
(194, 'admin_sys', '20190424152204', 11, 'DELETE finan_cli.telefono --> id: 2 - Tipo Telefono: 2 - Nro. Telefono: 456234567'),
(195, 'admin_sys', '20190424152209', 11, 'DELETE finan_cli.telefono --> id: 3 - Tipo Telefono: 1 - Nro. Telefono: 345667890'),
(196, 'admin_sys', '20190424152510', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,12121212)'),
(197, 'admin_sys', '20190424152515', 11, 'DELETE finan_cli.telefono --> id: 4 - Tipo Telefono: 1 - Nro. Telefono: 12121212'),
(198, 'admin_sys', '20190424152528', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,2124444)'),
(199, 'admin_sys', '20190424152533', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,3434565656)'),
(200, 'admin_sys', '20190424152538', 11, 'DELETE finan_cli.telefono --> id: 6 - Tipo Telefono: 1 - Nro. Telefono: 3434565656'),
(201, 'admin_sys', '20190424153152', 11, 'DELETE finan_cli.telefono --> id: 5 - Tipo Telefono: 1 - Nro. Telefono: 2124444'),
(202, 'admin_sys', '20190424153306', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,121121212)'),
(203, 'admin_sys', '20190424153311', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,34366767)'),
(204, 'admin_sys', '20190424153317', 11, 'DELETE finan_cli.telefono --> id: 7 - Tipo Telefono: 1 - Nro. Telefono: 121121212'),
(205, 'admin_sys', '20190424153323', 11, 'DELETE finan_cli.telefono --> id: 8 - Tipo Telefono: 1 - Nro. Telefono: 34366767'),
(206, 'admin_sys', '20190424153503', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,1221121212)'),
(207, 'admin_sys', '20190424153506', 11, 'DELETE finan_cli.telefono --> id: 9 - Tipo Telefono: 1 - Nro. Telefono: 1221121212'),
(208, 'admin_sys', '20190424153609', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,12123434343)'),
(209, 'admin_sys', '20190424153613', 11, 'DELETE finan_cli.telefono --> id: 10 - Tipo Telefono: 1 - Nro. Telefono: 12123434343'),
(210, 'admin_sys', '20190424153624', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,3322342342)'),
(211, 'admin_sys', '20190424153630', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,455656565)'),
(212, 'admin_sys', '20190424153633', 11, 'DELETE finan_cli.telefono --> id: 12 - Tipo Telefono: 1 - Nro. Telefono: 455656565'),
(213, 'admin_sys', '20190424155515', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero) VALUES (1,43563242323)'),
(214, 'admin_sys', '20190424160707', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (2,12345678,2)'),
(215, 'admin_sys', '20190424163611', 12, 'ANTERIOR: id = 13, tipo_telefono = 1, numero = 43563242323, digitos_prefijo = 4  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 2, numero = 4353242321, digitos_prefijo = 3 WHERE id =13'),
(216, 'admin_sys', '20190424163629', 12, 'ANTERIOR: id = 13, tipo_telefono = 2, numero = 4353242321, digitos_prefijo = 3  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 2, numero = 433242, digitos_prefijo = 2 WHERE id =13'),
(217, 'admin_sys', '20190424163637', 12, 'ANTERIOR: id = 13, tipo_telefono = 2, numero = 433242, digitos_prefijo = 2  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 433242, digitos_prefijo = 2 WHERE id =13'),
(218, 'admin_sys', '20190424170847', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,345123456,3)'),
(219, 'admin_sys', '20190424173311', 2, 'Cierre de Sesion en Fecha y Hora: 2019-04-24 17:33:11'),
(220, 'admin_sys', '20190424173336', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-24 17:33:36'),
(221, 'admin_sys', '20190424174704', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,7683434343,3)'),
(222, 'admin_sys', '20190424174726', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,1212343434,4)'),
(223, 'admin_sys', '20190424180301', 11, 'DELETE finan_cli.telefono --> id: 16 - Tipo Telefono: 1 - Nro. Telefono: 7683434343'),
(224, 'admin_sys', '20190424180355', 11, 'DELETE finan_cli.telefono --> id: 15 - Tipo Telefono: 1 - Nro. Telefono: 345123456'),
(225, 'admin_sys', '20190424180358', 11, 'DELETE finan_cli.telefono --> id: 17 - Tipo Telefono: 1 - Nro. Telefono: 1212343434'),
(226, 'admin_sys', '20190424180403', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3232343434,4)'),
(227, 'admin_sys', '20190424180850', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,12122343434,4)'),
(228, 'admin_sys', '20190424180858', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,212544545,3)'),
(229, 'admin_sys', '20190424180909', 5, '0Caraffa'),
(230, 'admin_sys', '20190424180939', 11, 'DELETE finan_cli.telefono --> id: 18 - Tipo Telefono: 1 - Nro. Telefono: 3232343434'),
(231, 'admin_sys', '20190424181104', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,2323454545,4)'),
(232, 'admin_sys', '20190424181112', 11, 'DELETE finan_cli.telefono --> id: 19 - Tipo Telefono: 1 - Nro. Telefono: 12122343434'),
(233, 'admin_sys', '20190424181115', 11, 'DELETE finan_cli.telefono --> id: 20 - Tipo Telefono: 1 - Nro. Telefono: 212544545'),
(234, 'admin_sys', '20190424181118', 11, 'DELETE finan_cli.telefono --> id: 21 - Tipo Telefono: 1 - Nro. Telefono: 2323454545'),
(235, 'admin_sys', '20190424181123', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3423243,3)'),
(236, 'admin_sys', '20190424181129', 11, 'DELETE finan_cli.telefono --> id: 22 - Tipo Telefono: 1 - Nro. Telefono: 3423243'),
(237, 'admin_sys', '20190424181135', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,345354545,3)'),
(238, 'admin_sys', '20190424181152', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (sadsad,2222,1,sdsd,NULL,1,3456,ss,dfff)'),
(239, 'admin_sys', '20190424181208', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (2,454321212,2)'),
(240, 'admin_sys', '20190425095144', 12, 'ANTERIOR: id = 24, tipo_telefono = 2, numero = 454321212, digitos_prefijo = 2  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 2, numero = 4514321212, digitos_prefijo = 3 WHERE id =24'),
(241, 'admin_sys', '20190425095152', 11, 'DELETE finan_cli.telefono --> id: 23 - Tipo Telefono: 1 - Nro. Telefono: 345354545'),
(242, 'admin_sys', '20190425095203', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,12344545454,4)'),
(243, 'admin_sys', '20190425095211', 12, 'ANTERIOR: id = 25, tipo_telefono = 1, numero = 12344545454, digitos_prefijo = 4  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 124545454, digitos_prefijo = 2 WHERE id =25'),
(244, 'admin_sys', '20190425095220', 12, 'ANTERIOR: id = 25, tipo_telefono = 1, numero = 124545454, digitos_prefijo = 2  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 2, numero = 124545454, digitos_prefijo = 2 WHERE id =25'),
(245, 'admin_sys', '20190425095226', 12, 'ANTERIOR: id = 24, tipo_telefono = 2, numero = 4514321212, digitos_prefijo = 3  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 4514321212, digitos_prefijo = 3 WHERE id =24'),
(246, 'admin_sys', '20190425150911', 1, 'Inicio de Sesion en Fecha y Hora: 2019-04-25 15:09:11'),
(247, 'admin_sys', '20190425175739', 13, 'INSERT INTO finan_cli.cadena(razon_social,cuit_cuil,email,telefono,nombre_fantasia) VALUES (ferni,23232,NULL,123,asas)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mora_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `mora_cuota_credito` (
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha_interes` char(14) COLLATE latin1_spanish_ci NOT NULL,
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
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=14 ;

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
(13, 'Nueva Cadena', 'Cuando se registra una nueva cadena en el sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  `valor` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`id`, `nombre`, `descripcion`, `valor`) VALUES
(1, 'cantidad_intentos_fallidos_inicio_sesion', 'Es la cantidad de veces en la cual se puede intentar loguear un usuario.', '5'),
(2, 'cantidad_horas_bloqueo_usuario', 'Es el tiempo en horas que se bloquea el usuario para volver a reintentar.', '2'),
(3, 'cantidad_domicilios_x_usuario_cliente', 'Es la cantidad de domicilios que se permiten cargar por usuario o cliente.', '5'),
(4, 'cantidad_telefonos_x_usuario_cliente', 'Es la cantidad de teléfonos por usuario o cliente que se permiten cargar.', '3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
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
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `monto_maximo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

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
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  `cantidad_cuotas` int(11) NOT NULL,
  `interes_fijo` int(11) NOT NULL,
  `id_tipo_diferimiento_cuota` int(11) NOT NULL,
  `id_cadena` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_diferimiento_cuota` (`id_tipo_diferimiento_cuota`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE IF NOT EXISTS `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
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
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  `email` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `id_cadena` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `id_domicilio` (`id_domicilio`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `codigo`, `nombre`, `id_domicilio`, `email`, `id_cadena`) VALUES
(1, 999999, 'SISTEMAS', 1, NULL, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`id`, `tipo_telefono`, `numero`, `digitos_prefijo`) VALUES
(11, 1, 3322342342, 3),
(13, 1, 433242, 2),
(14, 2, 12345678, 2),
(24, 1, 4514321212, 3),
(25, 2, 124545454, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_diferimiento_cuota`
--

CREATE TABLE IF NOT EXISTS `tipo_diferimiento_cuota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
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
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
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
  `id` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `estado` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `clave` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
  `salt` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_domicilio` (`id_perfil`,`id_sucursal`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `tipo_documento`, `documento`, `email`, `id_perfil`, `id_sucursal`, `estado`, `clave`, `salt`) VALUES
('admin_sys', 'SISTEMAS', 'ROOT', 1, '9999999', 'nada@sistemas.com.ar', 1, 1, 'Habilitado', '93bb51d9533786fc3829733934e1cae40bb39004c76b2fd1713347930cb4f8c8b3ec7768d8501670316e6d0442cafde359737a8619574da36d0d6ad6062269b9', '8e7c1afb2e7ef7ead69c130ee767f0e7ec55a6e4dab47f07cd2e83dda811d763089f8d9717cb83188c040e71f238646700e37a0b6af8b8cc1f0fa166ac299e4d'),
('supervisor', 'Supervisa', 'TODO', 1, '1234123', 'teestamosobservando@segu.com', 3, 1, 'Deshabilitado', '623481f466bb2ea88e6cd65a400c146a25593d1a66da5fcf326b92ad85f34207c0952fe5879a691c362cd335417fc0d5b3e7b6560f8b4790ccff875b8efb625a', '26eed5138588a68c36f9740d8b8fc4e5997e993ae5bf94b5bcad10007982e36734b749f901489141d1b9098b8280898f623aa2ea0be3d10b0d0ca228ad5b1673');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_domicilio`
--

CREATE TABLE IF NOT EXISTS `usuario_x_domicilio` (
  `id_usuario` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `id_domicilio` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_domicilio`),
  KEY `id_domicilio` (`id_domicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_x_domicilio`
--

INSERT INTO `usuario_x_domicilio` (`id_usuario`, `id_domicilio`) VALUES
('admin_sys', 19),
('admin_sys', 20),
('supervisor', 32),
('admin_sys', 33),
('admin_sys', 34),
('supervisor', 36),
('supervisor', 37),
('supervisor', 38),
('admin_sys', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_telefono`
--

CREATE TABLE IF NOT EXISTS `usuario_x_telefono` (
  `id_usuario` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `id_telefono` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_telefono`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_x_telefono`
--

INSERT INTO `usuario_x_telefono` (`id_usuario`, `id_telefono`, `descripcion`) VALUES
('admin_sys', 24, NULL),
('admin_sys', 25, NULL),
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
  ADD CONSTRAINT `credito_cliente_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_4` FOREIGN KEY (`id_perfil_credito`) REFERENCES `perfil_credito` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_5` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`);

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
  ADD CONSTRAINT `plan_credito_ibfk_1` FOREIGN KEY (`id_tipo_diferimiento_cuota`) REFERENCES `tipo_diferimiento_cuota` (`id`),
  ADD CONSTRAINT `plan_credito_ibfk_2` FOREIGN KEY (`id_cadena`) REFERENCES `cadena` (`id`);

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
  ADD CONSTRAINT `usuario_x_domicilio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `usuario_x_domicilio_ibfk_2` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
