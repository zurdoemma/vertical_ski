-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-02-2020 a las 15:55:48
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
  `id_cuota_credito` bigint(20) NOT NULL,
  `mensaje` varchar(1020) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_tipo_aviso` int(11) NOT NULL,
  `fecha_modificacion` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `comentario` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `id_tipo_aviso` (`id_tipo_aviso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `aviso_x_mora`
--

INSERT INTO `aviso_x_mora` (`id`, `id_credito`, `fecha`, `estado`, `id_cuota_credito`, `mensaje`, `id_tipo_aviso`, `fecha_modificacion`, `comentario`) VALUES
(1, 27, '20190806141410', 'Finalizado', 77, 'Regularice su situación.', 1, NULL, NULL),
(4, 27, '20190813143009', 'Finalizado', 77, 'Se informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190816165730', 'El mensaje fue enviado correctamente!!'),
(6, 27, '20190116104717', 'Error', 77, 'sasas', 1, '20190116121613', 'No hay un envío de SMS relacionado al aviso por mora!!'),
(7, 27, '20190219123226', 'Finalizado', 77, 'Se recuerda que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190219123535', 'El mensaje fue enviado correctamente!!'),
(8, 27, '20190819124203', 'Finalizado', 77, 'Se recuerda que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190819124648', 'El mensaje fue enviado correctamente!!'),
(9, 27, '20190902141138', 'Finalizado', 77, 'PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $313,44.', 1, '20191003120019', 'El mensaje fue enviado correctamente!!'),
(10, 27, '20191004000017', 'Finalizado', 77, 'PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $318,08.', 1, '20191005000017', 'El mensaje fue enviado correctamente!!'),
(11, 29, '20191004000017', 'Finalizado', 85, 'PRUEBA: informa que la cuota número: 1 del credito: 29, tiene una deuda pendiente de $561,00.', 1, '20191005000019', 'El mensaje fue enviado correctamente!!'),
(12, 4, '20191012000016', 'Finalizado', 11, 'PRUEBA: informa que la cuota número: 2 del credito: 4, tiene una deuda pendiente de $627,73.', 1, '20191013000020', 'El mensaje fue enviado correctamente!!'),
(13, 4, '20191013000017', 'Finalizado', 12, 'PRUEBA: informa que la cuota número: 3 del credito: 4, tiene una deuda pendiente de $598,39.', 1, '20191014000018', 'El mensaje fue enviado correctamente!!'),
(14, 27, '20191016000015', 'Finalizado', 78, 'PRUEBA: informa que la cuota número: 3 del credito: 27, tiene una deuda pendiente de $236,82.', 1, '20191017000017', 'El mensaje fue enviado correctamente!!'),
(15, 27, '20191102000017', 'Finalizado', 77, 'PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $329,69.', 1, '20191103000020', 'El mensaje fue enviado correctamente!!'),
(16, 29, '20191102120020', 'Finalizado', 85, 'PRUEBA: informa que la cuota número: 1 del credito: 29, tiene una deuda pendiente de $588,50.', 1, '20191103120019', 'El mensaje fue enviado correctamente!!'),
(17, 30, '20191103000018', 'Finalizado', 89, 'PRUEBA: informa que la cuota número: 2 del credito: 30, tiene una deuda pendiente de $748,00.', 1, '20191104000019', 'El mensaje fue enviado correctamente!!'),
(18, 29, '20191103000018', 'Finalizado', 86, 'PRUEBA: informa que la cuota número: 2 del credito: 29, tiene una deuda pendiente de $561,00.', 1, '20191104000021', 'El mensaje fue enviado correctamente!!'),
(19, 4, '20191110120015', 'Finalizado', 11, 'PRUEBA: le recuerda que la cuota número: 2 del credito: 4, tiene una deuda pendiente de $627,73.', 1, '20191111120020', 'El mensaje fue enviado correctamente!!'),
(20, 4, '20191111000018', 'Finalizado', 12, 'PRUEBA: informa que la cuota número: 3 del credito: 4, tiene una deuda pendiente de $627,72.', 1, '20191112000019', 'El mensaje fue enviado correctamente!!'),
(21, 4, '20191112000017', 'Finalizado', 11, 'PRUEBA: informa que la cuota número: 2 del credito: 4, tiene una deuda pendiente de $657,06.', 1, '20191113000019', 'El mensaje fue enviado correctamente!!'),
(22, 27, '20191114000017', 'Finalizado', 78, 'PRUEBA: informa que la cuota número: 3 del credito: 27, tiene una deuda pendiente de $248,43.', 1, '20191115000017', 'El mensaje fue enviado correctamente!!');

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
  `cuil_cuit` bigint(20) NOT NULL,
  `fecha_nacimiento` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_alta` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_titular` int(11) DEFAULT NULL,
  `observaciones` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `monto_maximo_credito` int(11) NOT NULL,
  `id_perfil_credito` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  PRIMARY KEY (`tipo_documento`,`documento`),
  UNIQUE KEY `id_3` (`id`),
  KEY `id` (`id`,`id_titular`,`id_perfil_credito`),
  KEY `id_titular` (`id_titular`),
  KEY `id_perfil_credito` (`id_perfil_credito`),
  KEY `id_genero` (`id_genero`),
  KEY `id_titular_2` (`id_titular`),
  KEY `documento` (`documento`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=36 ;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo_documento`, `documento`, `nombres`, `apellidos`, `cuil_cuit`, `fecha_nacimiento`, `email`, `fecha_alta`, `estado`, `id_titular`, `observaciones`, `monto_maximo_credito`, `id_perfil_credito`, `id_genero`) VALUES
(35, 1, '16731731', 'lis edith', 'soria', 27167317311, '19640331000000', '---', '20191220105543', 'Habilitado', NULL, '---', 500000, 2, 2),
(27, 1, '22774142', 'rabozzi', 'marcelo', 20227741423, '19720622000000', 'marcelo.rabozzi@gmail.com', '20191003123722', 'Habilitado', 1, 'nada', 500000, 1, 1),
(28, 1, '25000458', 'mariela sandra', 'negrete', 23250004583, '19760729000000', '---', '20191009122040', 'Habilitado', NULL, '---', 500000, 1, 2),
(33, 1, '28934582', 'Gisela', 'Sarale', 27289345820, '19811210000000', '---', '20191203082230', 'Deshabilitado', NULL, '\n', 500000, 1, 2),
(1, 1, '30443194', 'Fernando', 'Budasi', 20304431945, '19880501000000', 'fer@gmail.com', '20190520095955', 'Habilitado', NULL, '---', 500000, 1, 1),
(2, 1, '32443194', 'Bernardo', 'Arenga', 20324431945, '19780213000000', 'ferareng@gmail.com', '20190522175000', 'Habilitado', NULL, 'Ninguna', 1000000, 2, 1),
(14, 1, '35443194', 'Pedro', 'Decara', 20354431948, '19650305000000', 'pdecara@decarasa.com.ar', '20190606163942', 'Deshabilitado', NULL, 'Presenta documento borroso.', 450000, 2, 1),
(15, 1, '37443194', 'Adivino', 'Vividor', 20374431946, '19850131000000', '---', '20190606170045', 'Deshabilitado', 14, 'Nada', 245600, 1, 1),
(29, 1, '38292091', 'rocio de los milagros', 'diaz', 27382920916, '19940322000000', '---', '20191009122419', 'Habilitado', 28, '---', 300000, 1, 2),
(34, 1, '39173040', 'julieta', 'sarale', 23544412589, '19951108000000', 'saralejulieta@gmail.com', '20191204211841', 'Habilitado', NULL, '---', 300000, 2, 2),
(17, 1, '41443194', 'Servian', 'Juere', 20414431945, '19260730000000', '---', '20190607110919', 'Habilitado', NULL, 'asas', 550000, 1, 1),
(18, 1, '42443194', 'asas', 'asas', 20424431948, '19910818000000', '---', '20190607111212', 'Deshabilitado', 17, 'asas', 122222, 1, 1),
(30, 1, '43256789', 'sadasd', 'sadasd', 212312123, '19840110000000', 'asas@gmail.com', '20191014110130', 'Habilitado', NULL, '---', 12222, 1, 1),
(21, 1, '45443194', 'ASASAS', 'asasas', 20454431944, '19870404000000', '---', '20190607121436', 'Deshabilitado', 17, 'asasas', 100000, 1, 1),
(22, 1, '47898452', 'asas', 'JKLJKL', 23478984525, '19860307000000', '---', '20190607124036', 'Habilitado', NULL, 'ASASAS', 544444, 1, 1),
(23, 1, '50443194', 'asa', 'asas', 121212, '19870404000000', '---', '20190607124532', 'Habilitado', 17, 'asas', 499999, 1, 1),
(24, 1, '51443194', 'asas', 'klkl', 20514431945, '19900719000000', '---', '20190607140124', 'Habilitado', NULL, 'asas', 495000, 1, 1),
(31, 1, '67892222', 'asas', 'dfdfdf', 324324324, '19910619000000', 'sdsd@gmail.com', '20191014111113', 'Habilitado', NULL, '---', 12211, 1, 1),
(25, 1, '87654321', 'Pruebas Errores', 'Ejemplo', 20876543215, '19880101000000', 'linkinlinkin@gmail.com', '20190708104913', 'Habilitado', NULL, 'Ker.', 350010, 1, 1),
(26, 1, '89443194', 'asas', 'asas', 204433221, '19931228000000', 'asas@gmail.com', '20190829103810', 'Habilitado', NULL, 'asasas', 20000, 1, 1),
(32, 1, '9874561', 'ahghg', 'ghgh', 4354354, '19750128000000', 'ssdsd@hotmail.com', '20191014111534', 'Habilitado', NULL, '---', 34322, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_domicilio`
--

CREATE TABLE IF NOT EXISTS `cliente_x_domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `preferido` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_domicilio`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `fk_foreign_key_cliente_x_domicilio` (`tipo_documento`,`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cliente_x_domicilio`
--

INSERT INTO `cliente_x_domicilio` (`id_domicilio`, `tipo_documento`, `documento`, `preferido`) VALUES
(76, 1, '35443194', NULL),
(77, 1, '37443194', NULL),
(79, 1, '41443194', NULL),
(80, 1, '42443194', NULL),
(83, 1, '45443194', NULL),
(84, 1, '47898452', NULL),
(85, 1, '50443194', NULL),
(86, 1, '51443194', NULL),
(87, 1, '87654321', b'1'),
(89, 1, '89443194', b'1'),
(94, 1, '22774142', b'1'),
(95, 1, '25000458', b'1'),
(96, 1, '38292091', b'1'),
(97, 1, '43256789', b'1'),
(98, 1, '67892222', b'1'),
(99, 1, '9874561', b'1'),
(100, 1, '28934582', b'1'),
(101, 1, '39173040', b'1'),
(102, 1, '16731731', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_telefono`
--

CREATE TABLE IF NOT EXISTS `cliente_x_telefono` (
  `id_telefono` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `preferido` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_telefono`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `fk_foreign_key_cliente_x_telefono` (`tipo_documento`,`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cliente_x_telefono`
--

INSERT INTO `cliente_x_telefono` (`id_telefono`, `tipo_documento`, `documento`, `preferido`) VALUES
(49, 1, '35443194', b'1'),
(50, 1, '37443194', b'1'),
(52, 1, '41443194', b'1'),
(53, 1, '42443194', b'1'),
(56, 1, '45443194', b'1'),
(57, 1, '47898452', b'1'),
(58, 1, '50443194', b'1'),
(59, 1, '51443194', b'1'),
(60, 1, '87654321', b'1'),
(61, 1, '30443194', b'1'),
(61, 1, '32443194', b'1'),
(63, 1, '89443194', b'1'),
(67, 1, '22774142', b'0'),
(68, 1, '22774142', b'1'),
(69, 1, '25000458', b'1'),
(70, 1, '38292091', b'1'),
(71, 1, '43256789', b'1'),
(72, 1, '67892222', b'1'),
(73, 1, '67892222', b'0'),
(74, 1, '9874561', b'1'),
(75, 1, '28934582', b'1'),
(76, 1, '28934582', b'0'),
(77, 1, '39173040', b'1'),
(78, 1, '39173040', b'0'),
(79, 1, '16731731', b'1');

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
  `usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cuit_cuil` bigint(20) NOT NULL,
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `validado` bit(1) NOT NULL,
  `tipo_documento_adicional` int(11) DEFAULT NULL,
  `documento_adicional` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_cadena` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `tipo_documento_adicional` (`tipo_documento_adicional`),
  KEY `usuario` (`usuario`),
  KEY `fk_foreign_key_consulta_estado_financiero` (`tipo_documento`,`documento`),
  KEY `fk_foreign_key_consulta_estado_financiero_adicional` (`tipo_documento_adicional`,`documento_adicional`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `consulta_estado_financiero`
--

INSERT INTO `consulta_estado_financiero` (`id`, `tipo_documento`, `documento`, `fecha`, `resultado_xml`, `usuario`, `cuit_cuil`, `token`, `validado`, `tipo_documento_adicional`, `documento_adicional`, `id_cadena`) VALUES
(5, 1, '87654321', '20180607111439', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>ICBC - INDUSTRIAL AND COMMERCIONAL BANK OF CHINA</entidad><situacion>3</situacion><monto_maximo>10000.0000000000</monto_maximo><deuda_actual>10000.0000000000</deuda_actual><fecha>05/04/2017</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'admin_sys', 20876543215, 'ab05eaab9673f62d016d86e125ecf9ddcef363d2f197034caca97b304029d9333a28555a6cb838e754d6179ddabdd9c9500139893f3c51583665fb0b40bfb34d', b'1', NULL, NULL, 5),
(6, 1, '87654321', '20190710104717', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7fedcceafd3333a489c7f9c34d8db9bb25bc9c8987338c116129757c9d82a3e8b1fb0b8f0b698d3f2128e404e7ba112cd02172271eb1d77e5cd658adabb387f1', b'1', NULL, NULL, NULL),
(8, 1, '87654321', '20190821155006', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>1</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7d0a5ee043ad8ee3fdef0726c3990057cc66a7dca615d8dd41cd4548fc19c94c39cd3ad345c14fb43ebdb39e4064b36db7c27e92a59deb5a42fd925eea3b7893', b'1', NULL, NULL, 5),
(13, 1, '25000458', '20191009122441', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>NEGRETE MARIELA SANDRA</ape_nom><nume_docu>25000458</nume_docu><cdi>23250004583</cdi><fecha_nacimiento>29/07/1976</fecha_nacimiento><direc_calle>GENERAL CAMPOS 169</direc_calle><localidad>PILAR</localidad><codigo_postal>5972</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1976</clase><edad>43</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R14</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_ACT ><row><cuit>23250004583</cuit><entidad>BANCO DE LA PROVINCIA DE CORDOBA S.A.</entidad><fecha>05/07/2019</fecha><situacion>1</situacion><deuda_actual>2000.0000000000</deuda_actual></row></DEUDA_SISTEMA_FINANCIERO_ACT > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>4000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>5000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>5000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201908</ult_periodo><alta_trabajo_ultimo>200501</alta_trabajo_ultimo><cuit>23127381909</cuit><razon_social>SARALE VICTOR OMAR</razon_social><situacion_laboral_actual>SITUACION: ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <Tipo_Actividad ><row><tipo_actividad>RELACION DEPENDENCIA</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>NO</posee_autos><cantidad_autos>0 </cantidad_autos></row></Moviles_posee > <Celulares_><row><cuit></cuit><documento>25000458</documento><empresa>CLARO</empresa><celular>1164332072</celular><fecha_activacion>06/07/2010</fecha_activacion></row><row><cuit></cuit><documento>25000458</documento><empresa>CLARO</empresa><celular>3572532895</celular><fecha_activacion>23/02/2007</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>23127381909</inf_lab_cuit_><inf_lab_razon_>SARALE VICTOR OMAR</inf_lab_razon_><relacion_desde_>01/01/2005</relacion_desde_><relacion_hasta_>01/08/2019</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Domicilio_Normalizado ><row><ape_nom>NEGRETE MARIELA SANDRA</ape_nom><doc>25000458</doc><calle>GENERAL CAMPOS</calle><altura>169</altura><piso></piso><dpto></dpto><puerta></puerta><localidad>PILAR</localidad><codigo_postal>5972</codigo_postal><provincia>CORDOBA</provincia></row></Domicilio_Normalizado > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <CELULARES ><row><celular>(011)-1564332072</celular></row></CELULARES > <Score ><row><score>983</score></row></Score > <tipo_empleador ><row><tipo_empleador>PRIVADO</tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 23250004583, '619649deeeca5257d72370892c1c41813479aaafc5000e5e38b8002c4c92f42045e0ad7b8817e48b298f8f23f88992280ab8a643c3da34ec036e9be7d95b244e', b'1', 1, '38292091', 5),
(19, 1, '25000458', '20191220103053', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>NEGRETE MARIELA SANDRA</ape_nom><nume_docu>25000458</nume_docu><cdi>23250004583</cdi><fecha_nacimiento>29/07/1976</fecha_nacimiento><direc_calle>GENERAL CAMPOS 169</direc_calle><localidad>PILAR</localidad><codigo_postal>5972</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1976</clase><edad>43</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R14</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_ACT ><row><cuit>23250004583</cuit><entidad>BANCO DE LA PROVINCIA DE CORDOBA S.A.</entidad><fecha>05/10/2019</fecha><situacion>1</situacion><deuda_actual>3000.0000000000</deuda_actual></row></DEUDA_SISTEMA_FINANCIERO_ACT > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>4000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>5000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>DE LA PCIA.DE CORDOBA</entidad><situacion>1</situacion><monto_maximo>5000.0000000000</monto_maximo><deuda_actual>1000.0000000000</deuda_actual><fecha>05/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201910</ult_periodo><alta_trabajo_ultimo>200501</alta_trabajo_ultimo><cuit>23127381909</cuit><razon_social>SARALE VICTOR OMAR</razon_social><situacion_laboral_actual>SITUACION: ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <Tipo_Actividad ><row><tipo_actividad>RELACION DEPENDENCIA</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>NO</posee_autos><cantidad_autos>0 </cantidad_autos></row></Moviles_posee > <Celulares_><row><cuit></cuit><documento>25000458</documento><empresa>CLARO</empresa><celular>1164332072</celular><fecha_activacion>06/07/2010</fecha_activacion></row><row><cuit></cuit><documento>25000458</documento><empresa>CLARO</empresa><celular>3572532895</celular><fecha_activacion>23/02/2007</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>23127381909</inf_lab_cuit_><inf_lab_razon_>SARALE VICTOR OMAR</inf_lab_razon_><relacion_desde_>01/01/2005</relacion_desde_><relacion_hasta_>01/10/2019</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Domicilio_Normalizado ><row><ape_nom>NEGRETE MARIELA SANDRA</ape_nom><doc>25000458</doc><calle>GENERAL CAMPOS</calle><altura>169</altura><piso></piso><dpto></dpto><puerta></puerta><localidad>PILAR</localidad><codigo_postal>5972</codigo_postal><provincia>CORDOBA</provincia></row></Domicilio_Normalizado > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <CELULARES ><row><celular>(011)-1564332072</celular></row></CELULARES > <Score ><row><score>800</score></row></Score > <tipo_empleador ><row><tipo_empleador>PRIVADO</tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 23250004583, '4d7ffc018582bf9d5b673d9ad8e8d2a3840720f5f8ee979011bf1258edb902ddf05ad8dc2f5b7b60d790c144900c659b93f2ee5935ad8a665272ce4b45bd37ef', b'1', 1, '38292091', 5);
INSERT INTO `consulta_estado_financiero` (`id`, `tipo_documento`, `documento`, `fecha`, `resultado_xml`, `usuario`, `cuit_cuil`, `token`, `validado`, `tipo_documento_adicional`, `documento_adicional`, `id_cadena`) VALUES
(26, 1, '31443194', '20200210174123', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><ExistenciaFisicaEntidad_><row><ape_nom>CARBALLO EMMANUEL ENRIQUE</ape_nom><sexo>M</sexo><nume_docu>31443194</nume_docu><clase>1985</clase><fecha_nacimiento>23/02/1985</fecha_nacimiento><edad>34</edad><tipo_docu>DN</tipo_docu><ocupacion>ESTUDIANTE          </ocupacion><direc_calle>SAN JOSE DE CALAZANS 348 5 D</direc_calle><localidad>OBSERVATORIO</localidad><codigo_postal>5000</codigo_postal><departamento>CORDOBA</departamento><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><cdi_codigo_de_identificacion>20-31443194-5</cdi_codigo_de_identificacion><estado_civil>Soltero</estado_civil><fallecido>NO</fallecido><fallecido_posible></fallecido_posible></row></ExistenciaFisicaEntidad_> <Score_><row><score>763</score></row></Score_> <Domi_xy_><row><nprovincia>CORDOBA</nprovincia><npartido>CAPITAL</npartido><nlocalidad>CORDOBA</nlocalidad><nbarrio></nbarrio><calle>SAN JOSE DE CALAZANS 348 5 D 348 5 5 D</calle><ncp>X5000LHH</ncp><geocordenadas>-64.190000 /  -31.410000</geocordenadas></row></Domi_xy_> <Situacion_BCRA ><row><entidad>HIPOTECARIO S.A.</entidad><fecha_sit>05/12/2019</fecha_sit><situacion>1</situacion><deuda_total>21000.0000000000</deuda_total></row></Situacion_BCRA > <FraudesTarjeta_><row><fraude>                                                                                </fraude></row></FraudesTarjeta_> <CapacidadEndeudamiento_><row><capacidad_endeudamiento>36370.9700000000</capacidad_endeudamiento></row></CapacidadEndeudamiento_> <Ooss_personas_><row><cuil>20314431945</cuil><apenom>CARBALLO EMMANUEL ENRIQUE</apenom><nume_docu>31443194</nume_docu><sexo>M</sexo><domicilio>P 0</domicilio><localidad>CBA</localidad><provincia>CORDOBA</provincia><cod_postal>5000</cod_postal></row></Ooss_personas_> <Obra_Social_Rel_><row><codiobra>0126205</codiobra><descripcion_ooss>OBRA SOCIAL DE LOS EMPLEADOS DE COMERCIO Y ACTIVIDADES CIVILES</descripcion_ooss><sigla_ooss>OSECAC</sigla_ooss><cobertura>RELACION DEPENDENCIA</cobertura><conyuge>NO</conyuge><hijos>0</hijos></row></Obra_Social_Rel_> <domicilio_Empresa_trabaja_><row><direccion>FRAY LUIS BELTRAN Y</direccion><localidad>BARRIO POETA LUGONES</localidad><provincia>CORDOBA</provincia><cp>5008</cp></row></domicilio_Empresa_trabaja_> <domicilio_otros_><row><domicilio>MARTIN GARCIA 838 PA</domicilio><localidad>CORDOBA</localidad><cp>5000</cp><provincia>CORDOBA</provincia></row></domicilio_otros_> <Domicilios_Juicios_><row><domicilio></domicilio></row></Domicilios_Juicios_> <Padronempresasf_><row><ape_nom>CARBALLO EMMANUEL ENRIQUE</ape_nom><cuit>20-31443194-5</cuit><domicilio_fiscal></domicilio_fiscal><provincia></provincia><actividad_especifica>ACTIVIDAD NO CLASIFICADA</actividad_especifica><categoria_autonomo></categoria_autonomo></row></Padronempresasf_> <Gran_contrib_><row><gran_contr></gran_contr></row></Gran_contrib_> <Nise_><row><radio>140140518</radio><nise>3</nise><periodo>2010</periodo></row></Nise_> <Nbi_><row><radio>140140518</radio><nbi>2</nbi><periodo>2010</periodo></row></Nbi_> <Iped_><row><radio>140140518</radio><iped>3</iped><periodo>2010</periodo></row></Iped_> <Ib_><row><radio>140140518</radio><ib>2</ib><periodo>2010</periodo></row></Ib_> <Rel_Dependencia_Trabajador_><row><cuil>20314431945</cuil><apenom>CARBALLO EMMANUEL ENRIQUE</apenom><domicilio>MARTIN GARCIA 838 PA</domicilio><localidad>CORDOBA</localidad><cp>5000</cp><provincia>CORDOBA</provincia><fecha_nac>23/02/1985</fecha_nac><sexo>M</sexo><doc>31443194</doc></row></Rel_Dependencia_Trabajador_> <Rel_Dependencia_Empleador_><row><cuit>30612929455</cuit><razon>LIBERTAD S.A.</razon><domicilio>FRAY LUIS BELTRAN Y 0</domicilio><localidad>BARRIO POETA LUGONES</localidad><cp>5008</cp><provincia>CORDOBA</provincia></row></Rel_Dependencia_Empleador_> <Historia_><row><cuit>30703380618</cuit><razon>CAMARA DE FARMACEUTICOS Y PROPIETARIOS DE FARMACIAS DE LA RE                                                                                </razon><desde>01/09/2005</desde><hasta>01/05/2008</hasta></row><row><cuit>30612929455</cuit><razon>LIBERTAD S.A.                                                                                                                               </razon><desde>01/01/2010</desde><hasta>01/12/2019</hasta></row></Historia_> <Sysem_><row><cant_emp>3212</cant_emp><cuit>30612929455</cuit><razon>LIBERTAD S.A.</razon><domicilio>FRAY LUIS BELTRAN Y 0</domicilio><localidad>BARRIO POETA LUGONES</localidad><cp>5008</cp><provincia>CORDOBA</provincia></row></Sysem_> <Situacionlaboral_><row><situacionlaboral>SITUACION: ACTIVO</situacionlaboral></row></Situacionlaboral_> <Producto_financieros_><row><producto_financieros>ESTA PERSONA POSEE/TUVO PRODUCTOS FINANCIEROS</producto_financieros></row></Producto_financieros_> <Bancarizado_><row><bancarizado>PERSONA BANCARIZADA</bancarizado></row></Bancarizado_> <FechaMaxSistFin_><row><fecha>201912</fecha></row></FechaMaxSistFin_> <SistFinEvolucionDeuda_><row><entidad>HIPOTECARIO S.A.</entidad><m1>1</m1><m2>1</m2><m3>1</m3><m4>1</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>1</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>21000.0000000000</deuda_actual><monto_maximo>34000</monto_maximo><bco>00044</bco></row></SistFinEvolucionDeuda_> <SistFinEvolucionDeudaPropio_><row><entidad>HIPOTECARIO S.A.</entidad><m1>1</m1><m2>1</m2><m3>1</m3><m4>1</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>1</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>21000.0000000000</deuda_actual><monto_maximo>34000</monto_maximo><bco>00044</bco></row></SistFinEvolucionDeudaPropio_> <Consultas_Individual_><row><dia>0</dia><semana>0</semana><mes>0</mes><m24>0</m24></row></Consultas_Individual_> <Consultas_Grupo_><row><dia>0</dia><semana>0</semana><mes>0</mes><m24>0</m24></row></Consultas_Grupo_> <CONSULTADO_POR_><row><fecha>02/09/2003</fecha><consultante>DOCTA</consultante></row></CONSULTADO_POR_> <Bcra_Ultimas_Situaciones ><row><entidad>HIPOTECARIO S.A.</entidad><periodo>03/2018</periodo><situacion>1</situacion><monto>14000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>04/2018</periodo><situacion>1</situacion><monto>21000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>05/2018</periodo><situacion>1</situacion><monto>18000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>06/2018</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>07/2018</periodo><situacion>1</situacion><monto>16000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>08/2018</periodo><situacion>1</situacion><monto>18000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>09/2018</periodo><situacion>1</situacion><monto>18000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>10/2018</periodo><situacion>1</situacion><monto>20000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>11/2018</periodo><situacion>1</situacion><monto>21000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>12/2018</periodo><situacion>1</situacion><monto>34000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>01/2019</periodo><situacion>1</situacion><monto>26000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>02/2019</periodo><situacion>1</situacion><monto>21000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>03/2019</periodo><situacion>1</situacion><monto>17000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>04/2019</periodo><situacion>1</situacion><monto>17000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>05/2019</periodo><situacion>1</situacion><monto>10000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>06/2019</periodo><situacion>1</situacion><monto>19000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>07/2019</periodo><situacion>1</situacion><monto>21000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>08/2019</periodo><situacion>1</situacion><monto>28000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>09/2019</periodo><situacion>1</situacion><monto>19000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>10/2019</periodo><situacion>1</situacion><monto>30000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>11/2019</periodo><situacion>1</situacion><monto>23000.0000000000</monto></row><row><entidad>HIPOTECARIO S.A.</entidad><periodo>12/2019</periodo><situacion>1</situacion><monto>21000.0000000000</monto></row></Bcra_Ultimas_Situaciones > <Bcra_Situaciones_Vigente ><row><entidad>BANCO HIPOTECARIO S.A.</entidad><periodos>12/2019</periodos><situacion>1</situacion><monto>21000.0000000000</monto></row></Bcra_Situaciones_Vigente > <Tipo_Actividad ><row><tipo_actividad>RELACION DEPENDENCIA</tipo_actividad></row></Tipo_Actividad ></RESULTADO>', 'her', 20314431945, 'e7afc46df770b32e308d7b9636c274bc9cea4831115327c610e7bcadd5536505efd8fb87c7d23797a360793d606b46e1a27f18cfa9c15aef6035f0b37899d78f', b'1', NULL, NULL, 5),
(27, 1, '5098271', '20200211094905', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><ExistenciaFisicaEntidad_><row><ape_nom>CERUTTI TERESITA FLORENTINA</ape_nom><sexo>F</sexo><nume_docu>5098271</nume_docu><clase>1945</clase><fecha_nacimiento>06/06/1945</fecha_nacimiento><edad>74</edad><tipo_docu>DN</tipo_docu><ocupacion>SIN OCUPACION       </ocupacion><direc_calle>AV COLON 3860</direc_calle><localidad>LAS PALMAS</localidad><codigo_postal>5000</codigo_postal><departamento>CORDOBA</departamento><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><cdi_codigo_de_identificacion>27-05098271-3</cdi_codigo_de_identificacion><estado_civil>Soltero</estado_civil><fallecido>NO</fallecido><fallecido_posible></fallecido_posible></row></ExistenciaFisicaEntidad_> <Score_><row><score>800</score></row></Score_> <Domi_xy_><row><nprovincia>CORDOBA</nprovincia><npartido>CAPITAL</npartido><nlocalidad>CORDOBA</nlocalidad><nbarrio></nbarrio><calle>AVENIDA COLON 3860</calle><ncp>X5003DDW</ncp><geocordenadas>-64.220000 /  -31.390000</geocordenadas></row></Domi_xy_> <Situacion_BCRA ><row><entidad>BANCO SUPERVIELLE S.A.</entidad><fecha_sit>05/12/2019</fecha_sit><situacion>1</situacion><deuda_total>28000.0000000000</deuda_total></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><fecha_sit>05/12/2019</fecha_sit><situacion>1</situacion><deuda_total>14000.0000000000</deuda_total></row></Situacion_BCRA > <FraudesTarjeta_><row><fraude>                                                                                </fraude></row></FraudesTarjeta_> <CapacidadEndeudamiento_><row><capacidad_endeudamiento>5540.0300000000</capacidad_endeudamiento></row></CapacidadEndeudamiento_> <Pasivos_titular_><row><t_nrobeneficio>15022466700</t_nrobeneficio><t_apellidonombre>CERUTTI TERESITA FLORENTINA</t_apellidonombre><t_tipodoc>DU</t_tipodoc><t_nrodoc>5098271</t_nrodoc><t_sexo>F</t_sexo><t_cuilbenef>27050982713.0000000000</t_cuilbenef><t_fechanac>06/06/1945</t_fechanac><t_calle>AV COLON</t_calle><t_callenro>3860</t_callenro><t_piso></t_piso><t_deptooficina></t_deptooficina><t_localidad>CORDOBA CAPITAL</t_localidad><provincia>CORDOBA</provincia><t_codpos>5000</t_codpos></row></Pasivos_titular_> <Pami_web_><row><apellido_nombre>CERUTTI TERESITA FLORENTINA</apellido_nombre><tipo_beneficiario>JUBILACION</tipo_beneficiario><nro_beneficiario>150224667009</nro_beneficiario><fecha_nacimiento>06/06/1945</fecha_nacimiento><nacionalidad>ARGENTINA</nacionalidad><pais>ARGENTINA</pais><documento>5098271</documento><sexo>F</sexo><estado_civil>CASADO/A</estado_civil><unidad_operativa>NO ASIGNADA</unidad_operativa><fecha_alta>09/08/2007</fecha_alta><otra_obra_social>NO</otra_obra_social><tipo_documento>DNI</tipo_documento><grado>TITULAR</grado></row><row><apellido_nombre>RABOZZI JUAN VICENTE</apellido_nombre><tipo_beneficiario>JUBILACION</tipo_beneficiario><nro_beneficiario>150224667009</nro_beneficiario><fecha_nacimiento>07/01/1944</fecha_nacimiento><nacionalidad>ARGENTINA</nacionalidad><pais>ARGENTINA</pais><documento>7977251</documento><sexo>M</sexo><estado_civil>CASADO/A</estado_civil><unidad_operativa>NO ASIGNADA</unidad_operativa><fecha_alta>05/10/2007</fecha_alta><otra_obra_social>NO</otra_obra_social><tipo_documento>DNI</tipo_documento><grado>PERSONA A CARGO</grado></row></Pami_web_> <Ooss_personas_><row><cuil>27050982713</cuil><apenom>CERUTTI TERESITA FLORENTINA</apenom><nume_docu>5098271</nume_docu><sexo>F</sexo><domicilio>AV COLON 3860</domicilio><localidad>CIUDAD DE CORDOBA NO</localidad><provincia>CORDOBA</provincia><cod_postal>5000</cod_postal></row></Ooss_personas_> <Obra_Social_Rel_><row><codiobra>0500807</codiobra><descripcion_ooss>INSTITUTO NACIONAL DE SERVICIOS SOCIALES PARA JUBILADOS Y PENSIONADOS</descripcion_ooss><sigla_ooss>INSSJYP</sigla_ooss><cobertura>PASIVOS</cobertura><conyuge>NO</conyuge><hijos>0</hijos></row></Obra_Social_Rel_> <domicilio_otros_><row><domicilio>AV COLON 3860 PA</domicilio><localidad>CORDOBA</localidad><cp>5000</cp><provincia>CORDOBA</provincia></row></domicilio_otros_> <Domicilios_Juicios_><row><domicilio></domicilio></row></Domicilios_Juicios_> <Domialter_><row><ape_nom>CERUTTI TERESITA</ape_nom><direc_calle>AV COLON 3860 1     </direc_calle><direc_loca>CORDOBA</direc_loca><cod_postal></cod_postal><provincia>CORDOBA</provincia></row><row><ape_nom>CERUTTI TERESITA F</ape_nom><direc_calle>AV COLON 3860       </direc_calle><direc_loca>CORDOBA</direc_loca><cod_postal></cod_postal><provincia>CORDOBA</provincia></row></Domialter_> <Padronempresasf_><row><ape_nom>CERUTTI TERESITA F</ape_nom><cuit>27-05098271-3</cuit><domicilio_fiscal></domicilio_fiscal><provincia></provincia><actividad_especifica>ACTIVIDAD NO CLASIFICADA</actividad_especifica><categoria_autonomo></categoria_autonomo></row></Padronempresasf_> <Const_inscrip_><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>202001</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201912</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201911</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201910</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201909</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201908</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201907</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201906</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201905</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201904</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201903</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201902</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201901</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201812</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201811</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201810</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201809</periodo></row><row><descrip_imp_ganancias>NO INSCRIPTO</descrip_imp_ganancias><descrip_imp_iva>NO INSCRIPTO</descrip_imp_iva><descrip_monotributo>CATEGORIA C</descrip_monotributo><descrip_integrante_soc>NO INSCRIPTO</descrip_integrante_soc><descrip_empleador>NO INSCRIPTO</descrip_empleador><periodo>201808</periodo></row></Const_inscrip_> <Gran_contrib_><row><gran_contr></gran_contr></row></Gran_contrib_> <Nise_><row><radio>140142005</radio><nise>4</nise><periodo>2010</periodo></row></Nise_> <Nbi_><row><radio>140142005</radio><nbi>2</nbi><periodo>2010</periodo></row></Nbi_> <Iped_><row><radio>140142005</radio><iped>3</iped><periodo>2010</periodo></row></Iped_> <Ib_><row><radio>140142005</radio><ib>2</ib><periodo>2010</periodo></row></Ib_> <Rel_Dependencia_Trabajador_><row><cuil>27050982713</cuil><apenom>CERUTTI TERESITA FLORENTINA</apenom><domicilio>AV COLON 3860 PA</domicilio><localidad>CORDOBA</localidad><cp>5000</cp><provincia>CORDOBA</provincia><fecha_nac>06/06/1945</fecha_nac><sexo>F</sexo><doc>5098271</doc></row></Rel_Dependencia_Trabajador_> <Situacionlaboral_><row><situacionlaboral>SITUACION: NO ACTIVO</situacionlaboral></row></Situacionlaboral_> <Producto_financieros_><row><producto_financieros>ESTA PERSONA POSEE/TUVO PRODUCTOS FINANCIEROS</producto_financieros></row></Producto_financieros_> <Bancarizado_><row><bancarizado>PERSONA BANCARIZADA</bancarizado></row></Bancarizado_> <FechaMaxSistFin_><row><fecha>201912</fecha></row></FechaMaxSistFin_> <SistFinEvolucionDeuda_><row><entidad>DE LA PCIA.DE CORDOBA</entidad><m1>1</m1><m2>1</m2><m3>1</m3><m4>-</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>-</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>14000.0000000000</deuda_actual><monto_maximo>20000</monto_maximo><bco>00020</bco></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><m1>1</m1><m2>1</m2><m3>-</m3><m4>1</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>1</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>28000.0000000000</deuda_actual><monto_maximo>36000</monto_maximo><bco>00027</bco></row></SistFinEvolucionDeuda_> <SistFinEvolucionDeudaPropio_><row><entidad>DE LA PCIA.DE CORDOBA</entidad><m1>1</m1><m2>1</m2><m3>1</m3><m4>-</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>-</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>14000.0000000000</deuda_actual><monto_maximo>20000</monto_maximo><bco>00020</bco></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><m1>1</m1><m2>1</m2><m3>-</m3><m4>1</m4><m5>1</m5><m6>1</m6><m7>1</m7><m8>1</m8><m9>1</m9><m10>1</m10><m11>1</m11><m12>1</m12><m13>1</m13><m14>1</m14><m15>1</m15><m16>1</m16><m17>1</m17><m18>1</m18><m19>1</m19><m20>1</m20><m21>1</m21><m22>1</m22><m23>-</m23><m24>-</m24><deuda_actual>28000.0000000000</deuda_actual><monto_maximo>36000</monto_maximo><bco>00027</bco></row></SistFinEvolucionDeudaPropio_> <Propiedades_><row><titular>CERUTTI TERESITA FLORENTINA</titular><folio>100425</folio><ano_inscripcion>0</ano_inscripcion><ph>00000</ph><condom>50%</condom><codigo_departamento>11</codigo_departamento><pedania>00</pedania><pueblo>00</pueblo><circuncripcion>06</circuncripcion><seccion>15</seccion><manzana>005</manzana><parcela>015</parcela><ph>000</ph><escrituro>14/09/1976</escrituro></row></Propiedades_> <Consultas_Individual_><row><dia>0</dia><semana>0</semana><mes>0</mes><m24>0</m24></row></Consultas_Individual_> <Consultas_Grupo_><row><dia>0</dia><semana>0</semana><mes>0</mes><m24>0</m24></row></Consultas_Grupo_> <CONSULTADO_POR_><row><fecha>31/05/2016</fecha><consultante>CMR-FALABELLA</consultante></row><row><fecha>12/10/2016</fecha><consultante>CMR - GUILLERMO FERREYRA CORDOBA</consultante></row><row><fecha>12/10/2016</fecha><consultante>CMR-FALABELLA</consultante></row></CONSULTADO_POR_> <Bcra_Ultimas_Situaciones ><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>03/2018</periodo><situacion>1</situacion><monto>1000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>04/2018</periodo><situacion>1</situacion><monto>1000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>05/2018</periodo><situacion>1</situacion><monto>1000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>06/2018</periodo><situacion>1</situacion><monto>5000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>07/2018</periodo><situacion>1</situacion><monto>5000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>08/2018</periodo><situacion>1</situacion><monto>4000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>09/2018</periodo><situacion>1</situacion><monto>9000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>10/2018</periodo><situacion>1</situacion><monto>9000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>11/2018</periodo><situacion>1</situacion><monto>8000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>12/2018</periodo><situacion>1</situacion><monto>10000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>01/2019</periodo><situacion>1</situacion><monto>9000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>02/2019</periodo><situacion>1</situacion><monto>9000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>03/2019</periodo><situacion>1</situacion><monto>10000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>04/2019</periodo><situacion>1</situacion><monto>8000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>05/2019</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>06/2019</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>07/2019</periodo><situacion>1</situacion><monto>12000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>08/2019</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>09/2019</periodo><situacion>1</situacion><monto>28000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>09/2019</periodo><situacion>1</situacion><monto>28000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>11/2019</periodo><situacion>1</situacion><monto>36000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodo>12/2019</periodo><situacion>1</situacion><monto>28000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>03/2018</periodo><situacion>1</situacion><monto>3000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>04/2018</periodo><situacion>1</situacion><monto>6000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>05/2018</periodo><situacion>1</situacion><monto>5000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>06/2018</periodo><situacion>1</situacion><monto>3000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>07/2018</periodo><situacion>1</situacion><monto>20000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>08/2018</periodo><situacion>1</situacion><monto>18000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>09/2018</periodo><situacion>1</situacion><monto>16000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>10/2018</periodo><situacion>1</situacion><monto>14000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>11/2018</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>12/2018</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>12/2018</periodo><situacion>1</situacion><monto>13000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>02/2019</periodo><situacion>1</situacion><monto>7000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>03/2019</periodo><situacion>1</situacion><monto>6000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>04/2019</periodo><situacion>1</situacion><monto>6000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>05/2019</periodo><situacion>1</situacion><monto>7000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>06/2019</periodo><situacion>1</situacion><monto>6000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>07/2019</periodo><situacion>1</situacion><monto>4000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>07/2019</periodo><situacion>1</situacion><monto>4000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>08/2019</periodo><situacion>1</situacion><monto>10000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>10/2019</periodo><situacion>1</situacion><monto>20000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>11/2019</periodo><situacion>1</situacion><monto>16000.0000000000</monto></row><row><entidad>DE LA PCIA.DE CORDOBA</entidad><periodo>12/2019</periodo><situacion>1</situacion><monto>14000.0000000000</monto></row></Bcra_Ultimas_Situaciones > <Bcra_Situaciones_Vigente ><row><entidad>BANCO DE LA PROVINCIA DE CORDOBA S.A.</entidad><periodos>12/2019</periodos><situacion>1</situacion><monto>14000.0000000000</monto></row><row><entidad>BANCO SUPERVIELLE S.A.</entidad><periodos>12/2019</periodos><situacion>1</situacion><monto>28000.0000000000</monto></row></Bcra_Situaciones_Vigente > <Tipo_Actividad ><row><tipo_actividad> MONOTRIBUTO  JUBILADO</tipo_actividad></row></Tipo_Actividad ></RESULTADO>', 'her', 27050982713, '15d06e4d0e91bb73806851b6899dbc93bda866e39390c118828fd82792b1a80c79e6e88122e4b005b477408d52cd578d7515208f9c15fb35f1644b2797d54f32', b'0', NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_ejecucion_procesos`
--

CREATE TABLE IF NOT EXISTS `control_ejecucion_procesos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_proceso` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `control_ejecucion_procesos`
--

INSERT INTO `control_ejecucion_procesos` (`id`, `fecha`, `tipo_proceso`) VALUES
(15, '20191120000017', 1),
(16, '20191120000017', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE IF NOT EXISTS `credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cantidad_cuotas` int(11) NOT NULL,
  `monto_compra` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  `interes_fijo_plan_credito` int(11) NOT NULL,
  `monto_credito_original` int(11) NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `abona_primera_cuota` bit(1) NOT NULL,
  `minimo_entrega` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=63 ;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id`, `cantidad_cuotas`, `monto_compra`, `id_plan_credito`, `interes_fijo_plan_credito`, `monto_credito_original`, `estado`, `abona_primera_cuota`, `minimo_entrega`) VALUES
(4, 3, 160000, 4, 10, 176000, 'Cancelada', b'0', 0),
(27, 3, 63320, 4, 10, 69652, 'Pendiente', b'0', 0),
(28, 6, 36000, 5, 20, 43200, 'Pendiente', b'0', 0),
(29, 3, 150000, 4, 10, 165000, 'Pendiente', b'0', 0),
(30, 3, 200000, 4, 10, 220000, 'Pagada', b'0', 0),
(37, 1, 123456, 13, 45, 179011, 'Cancelada', b'0', 14815),
(38, 1, 145025, 13, 45, 210286, 'Pendiente', b'1', 0),
(42, 1, 30800, 13, 45, 44660, 'Pendiente', b'0', 4200),
(43, 1, 31239, 13, 45, 45298, 'Pendiente', b'0', 4260),
(44, 1, 35600, 13, 45, 51620, 'Pendiente', b'1', 0),
(45, 1, 31327, 13, 45, 45426, 'Pagada', b'0', 4272),
(46, 1, 32560, 13, 45, 47212, 'Pagada', b'0', 4440),
(47, 3, 30000, 4, 10, 33000, 'Cancelada', b'0', 0),
(48, 6, 150000, 5, 20, 180000, 'Pagada', b'1', 0),
(49, 6, 30000, 5, 20, 36000, 'Pendiente', b'0', 0),
(50, 3, 250000, 4, 10, 275000, 'Pagada', b'0', 0),
(51, 6, 150000, 5, 20, 180000, 'Pagada', b'1', 0),
(52, 3, 250000, 4, 10, 275000, 'Pendiente', b'0', 0),
(53, 3, 300000, 4, 10, 330000, 'Pagada', b'0', 0),
(54, 3, 200000, 4, 10, 220000, 'Cancelada', b'0', 0),
(55, 6, 200000, 5, 20, 240000, 'Cancelada', b'1', 0),
(56, 3, 600000, 4, 10, 660000, 'Pendiente', b'0', 0),
(57, 3, 120000, 4, 10, 132000, 'Pendiente', b'0', 0),
(58, 10, 600000, 15, 50, 900000, 'Pendiente', b'0', 150000),
(59, 10, 180000, 15, 50, 270000, 'Pendiente', b'0', 45000),
(60, 3, 95000, 4, 10, 104500, 'Pendiente', b'0', 5000),
(61, 3, 95000, 4, 10, 104500, 'Pendiente', b'0', 5000),
(62, 3, 100000, 4, 10, 110000, 'Pendiente', b'1', 0);

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
  `tipo_documento_adicional` int(11) DEFAULT NULL,
  `documento_adicional` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_credito`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `tipo_documento_titular` (`tipo_documento_adicional`),
  KEY `fk_foreign_key_credito_cliente` (`tipo_documento`,`documento`),
  KEY `fk_foreign_key_credito_cliente_adicional` (`tipo_documento_adicional`,`documento_adicional`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `credito_cliente`
--

INSERT INTO `credito_cliente` (`id_credito`, `fecha`, `tipo_documento`, `documento`, `id_usuario`, `id_sucursal`, `tipo_documento_adicional`, `documento_adicional`) VALUES
(4, '20190712174145', 1, '87654321', 'her', 2, NULL, NULL),
(27, '20190714172330', 1, '87654321', 'her', 2, NULL, NULL),
(28, '20190821160202', 1, '87654321', 'her', 2, NULL, NULL),
(29, '20190126103229', 1, '41443194', 'her', 2, 1, '50443194'),
(30, '20190829165100', 1, '32443194', 'her', 2, NULL, NULL),
(37, '20190901231540', 1, '32443194', 'supervisor', 2, NULL, NULL),
(38, '20190926151224', 1, '32443194', 'supervisor', 2, NULL, NULL),
(42, '20190926152811', 1, '32443194', 'supervisor', 2, NULL, NULL),
(43, '20190926152929', 1, '32443194', 'supervisor', 2, NULL, NULL),
(44, '20190926152947', 1, '32443194', 'supervisor', 2, NULL, NULL),
(45, '20190926153323', 1, '32443194', 'supervisor', 2, NULL, NULL),
(46, '20190926153455', 1, '32443194', 'supervisor', 2, NULL, NULL),
(47, '20191003110729', 1, '30443194', 'supervisor', 2, NULL, NULL),
(48, '20191009122913', 1, '25000458', 'her', 2, 1, '38292091'),
(49, '20191203083840', 1, '28934582', 'her', 2, NULL, NULL),
(50, '20191209095501', 1, '25000458', 'her', 2, NULL, NULL),
(51, '20191216172737', 1, '25000458', 'her', 2, NULL, NULL),
(52, '20191220105642', 1, '16731731', 'her', 2, NULL, NULL),
(53, '20191220163001', 1, '16731731', 'her', 2, NULL, NULL),
(54, '20191221090209', 1, '16731731', 'her', 2, NULL, NULL),
(55, '20191226195105', 1, '25000458', 'her', 2, NULL, NULL),
(56, '20191227090443', 1, '30443194', 'her', 2, 1, '22774142'),
(57, '20191227090556', 1, '30443194', 'her', 2, 1, '22774142'),
(58, '20191227101553', 1, '30443194', 'her', 2, 1, '22774142'),
(59, '20191227112018', 1, '30443194', 'her', 2, 1, '22774142'),
(60, '20191227141915', 1, '30443194', 'her', 2, 1, '22774142'),
(61, '20191227141954', 1, '30443194', 'her', 2, 1, '22774142'),
(62, '20191227143203', 1, '30443194', 'her', 2, 1, '22774142');

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
  `fecha_pago` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `monto_pago` int(11) DEFAULT NULL,
  `usuario_registro_pago` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`),
  KEY `usuario_registro_pago` (`usuario_registro_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=181 ;

--
-- Volcado de datos para la tabla `cuota_credito`
--

INSERT INTO `cuota_credito` (`id`, `id_credito`, `numero_cuota`, `fecha_vencimiento`, `monto_cuota_original`, `estado`, `fecha_pago`, `monto_pago`, `usuario_registro_pago`) VALUES
(10, 4, 1, '20190812235959', 58667, 'Condonada', NULL, NULL, NULL),
(11, 4, 2, '20190911235959', 58667, 'En Mora', NULL, NULL, NULL),
(12, 4, 3, '20191011235959', 58666, 'En Mora', NULL, NULL, NULL),
(76, 27, 1, '20190813235959', 23217, 'Pagada', '20190731145915', 23217, 'her'),
(77, 27, 2, '20190702235959', 23217, 'En Mora', NULL, NULL, NULL),
(78, 27, 3, '20191014235959', 23218, 'En Mora', NULL, NULL, NULL),
(79, 28, 1, '20190920235959', 7200, 'En Mora', NULL, NULL, NULL),
(80, 28, 2, '20191021235959', 7200, 'En Mora', NULL, NULL, NULL),
(81, 28, 3, '20191120235959', 7200, 'Pendiente', NULL, NULL, NULL),
(82, 28, 4, '20191220235959', 7200, 'Pendiente', NULL, NULL, NULL),
(83, 28, 5, '20200120235959', 7200, 'Pendiente', NULL, NULL, NULL),
(84, 28, 6, '20200219235959', 7200, 'Pendiente', NULL, NULL, NULL),
(85, 29, 1, '20191002235959', 55000, 'En Mora', NULL, NULL, NULL),
(86, 29, 2, '20191101235959', 55000, 'En Mora', NULL, NULL, NULL),
(87, 29, 3, '20191202235959', 55000, 'Pendiente', NULL, NULL, NULL),
(88, 30, 1, '20191002235959', 73333, 'Pagada', '20190917125824', 73333, 'supervisor'),
(89, 30, 2, '20191101235959', 73333, 'Pagada', '20191203154424', 74800, 'her'),
(90, 30, 3, '20191202235959', 73334, 'Pagada', '20191209095238', 73334, 'her'),
(97, 37, 1, '20191001235959', 179011, 'Cancelada', NULL, NULL, NULL),
(98, 38, 1, '20191028235959', 210286, 'Pagada', '20190926151224', 210286, 'supervisor'),
(102, 42, 1, '20191028235959', 44660, 'En Mora', NULL, NULL, NULL),
(103, 43, 1, '20191028235959', 45298, 'En Mora', NULL, NULL, NULL),
(104, 44, 1, '20191028235959', 51620, 'Pagada', '20190926152947', 51620, 'supervisor'),
(105, 45, 1, '20191028235959', 45426, 'Pagada', '20191209095059', 45426, 'her'),
(106, 46, 1, '20191028235959', 47212, 'Pagada', '20191013194738', 47212, 'supervisor'),
(107, 47, 1, '20191104235959', 11000, 'Cancelada', NULL, NULL, NULL),
(108, 47, 2, '20191204235959', 11000, 'Cancelada', NULL, NULL, NULL),
(109, 47, 3, '20200103235959', 11000, 'Cancelada', NULL, NULL, NULL),
(110, 48, 1, '20191108235959', 30000, 'Pagada', '20191009122913', 30000, 'her'),
(111, 48, 2, '20191209235959', 30000, 'Pagada', '20191009142134', 30000, 'her'),
(112, 48, 3, '20200108235959', 30000, 'Pagada', '20191009142201', 30000, 'her'),
(113, 48, 4, '20200207235959', 30000, 'Pagada', '20191009142201', 30000, 'her'),
(114, 48, 5, '20200309235959', 30000, 'Pagada', '20191009142201', 30000, 'her'),
(115, 48, 6, '20200408235959', 30000, 'Pagada', '20191009142201', 30000, 'her'),
(116, 49, 1, '20200102235959', 6000, 'Pendiente', NULL, NULL, NULL),
(117, 49, 2, '20200203235959', 6000, 'Pendiente', NULL, NULL, NULL),
(118, 49, 3, '20200304235959', 6000, 'Pendiente', NULL, NULL, NULL),
(119, 49, 4, '20200403235959', 6000, 'Pendiente', NULL, NULL, NULL),
(120, 49, 5, '20200504235959', 6000, 'Pendiente', NULL, NULL, NULL),
(121, 49, 6, '20200603235959', 6000, 'Pendiente', NULL, NULL, NULL),
(122, 50, 1, '20200108235959', 91667, 'Pagada', '20191209095728', 91667, 'her'),
(123, 50, 2, '20200207235959', 91667, 'Pagada', '20191226200119', 91667, 'her'),
(124, 50, 3, '20200309235959', 91666, 'Pagada', '20191226200119', 91666, 'her'),
(125, 51, 1, '20200115235959', 30000, 'Pagada', '20191216172737', 30000, 'her'),
(126, 51, 2, '20200214235959', 30000, 'Pagada', '20191216173751', 30000, 'her'),
(127, 51, 3, '20200316235959', 30000, 'Pagada', '20191216174322', 30000, 'her'),
(128, 51, 4, '20200415235959', 30000, 'Pagada', '20191220103249', 30000, 'her'),
(129, 51, 5, '20200515235959', 30000, 'Pagada', '20191220103249', 30000, 'her'),
(130, 51, 6, '20200615235959', 30000, 'Pagada', '20191220103249', 30000, 'her'),
(131, 52, 1, '20200120235959', 91667, 'Pendiente', NULL, NULL, NULL),
(132, 52, 2, '20200219235959', 91667, 'Pendiente', NULL, NULL, NULL),
(133, 52, 3, '20200320235959', 91666, 'Pendiente', NULL, NULL, NULL),
(134, 53, 1, '20200120235959', 110000, 'Pagada', '20191220180725', 110000, 'her'),
(135, 53, 2, '20200219235959', 110000, 'Pagada', '20191220180725', 110000, 'her'),
(136, 53, 3, '20200320235959', 110000, 'Pagada', '20191220180725', 110000, 'her'),
(137, 54, 1, '20200203235959', 73333, 'Cancelada', NULL, NULL, NULL),
(138, 54, 2, '20200304235959', 73333, 'Cancelada', NULL, NULL, NULL),
(139, 54, 3, '20200403235959', 73334, 'Cancelada', NULL, NULL, NULL),
(140, 55, 1, '20200127235959', 40000, 'Pagada', '20191226195105', 40000, 'her'),
(141, 55, 2, '20200226235959', 40000, 'Cancelada', NULL, NULL, NULL),
(142, 55, 3, '20200327235959', 40000, 'Cancelada', NULL, NULL, NULL),
(143, 55, 4, '20200427235959', 40000, 'Cancelada', NULL, NULL, NULL),
(144, 55, 5, '20200527235959', 40000, 'Cancelada', NULL, NULL, NULL),
(145, 55, 6, '20200626235959', 40000, 'Cancelada', NULL, NULL, NULL),
(146, 56, 1, '20200203235959', 220000, 'Pendiente', NULL, NULL, NULL),
(147, 56, 2, '20200304235959', 220000, 'Pendiente', NULL, NULL, NULL),
(148, 56, 3, '20200403235959', 220000, 'Pendiente', NULL, NULL, NULL),
(149, 57, 1, '20200203235959', 44000, 'Pendiente', NULL, NULL, NULL),
(150, 57, 2, '20200304235959', 44000, 'Pendiente', NULL, NULL, NULL),
(151, 57, 3, '20200403235959', 44000, 'Pendiente', NULL, NULL, NULL),
(152, 58, 1, '20200127235959', 90000, 'Pendiente', NULL, NULL, NULL),
(153, 58, 2, '20200226235959', 90000, 'Pendiente', NULL, NULL, NULL),
(154, 58, 3, '20200327235959', 90000, 'Pendiente', NULL, NULL, NULL),
(155, 58, 4, '20200427235959', 90000, 'Pendiente', NULL, NULL, NULL),
(156, 58, 5, '20200527235959', 90000, 'Pendiente', NULL, NULL, NULL),
(157, 58, 6, '20200626235959', 90000, 'Pendiente', NULL, NULL, NULL),
(158, 58, 7, '20200727235959', 90000, 'Pendiente', NULL, NULL, NULL),
(159, 58, 8, '20200826235959', 90000, 'Pendiente', NULL, NULL, NULL),
(160, 58, 9, '20200925235959', 90000, 'Pendiente', NULL, NULL, NULL),
(161, 58, 10, '20201026235959', 90000, 'Pendiente', NULL, NULL, NULL),
(162, 59, 1, '20200127235959', 27000, 'Pendiente', NULL, NULL, NULL),
(163, 59, 2, '20200226235959', 27000, 'Pendiente', NULL, NULL, NULL),
(164, 59, 3, '20200327235959', 27000, 'Pendiente', NULL, NULL, NULL),
(165, 59, 4, '20200427235959', 27000, 'Pendiente', NULL, NULL, NULL),
(166, 59, 5, '20200527235959', 27000, 'Pendiente', NULL, NULL, NULL),
(167, 59, 6, '20200626235959', 27000, 'Pendiente', NULL, NULL, NULL),
(168, 59, 7, '20200727235959', 27000, 'Pendiente', NULL, NULL, NULL),
(169, 59, 8, '20200826235959', 27000, 'Pendiente', NULL, NULL, NULL),
(170, 59, 9, '20200925235959', 27000, 'Pendiente', NULL, NULL, NULL),
(171, 59, 10, '20201026235959', 27000, 'Pendiente', NULL, NULL, NULL),
(172, 60, 1, '20200203235959', 34833, 'Pendiente', NULL, NULL, NULL),
(173, 60, 2, '20200304235959', 34833, 'Pendiente', NULL, NULL, NULL),
(174, 60, 3, '20200403235959', 34834, 'Pendiente', NULL, NULL, NULL),
(175, 61, 1, '20200203235959', 34833, 'Pendiente', NULL, NULL, NULL),
(176, 61, 2, '20200304235959', 34833, 'Pendiente', NULL, NULL, NULL),
(177, 61, 3, '20200403235959', 34834, 'Pendiente', NULL, NULL, NULL),
(178, 62, 1, '20200203235959', 36667, 'Pagada', '20191227143203', 36667, 'her'),
(179, 62, 2, '20200304235959', 36667, 'Pendiente', NULL, NULL, NULL),
(180, 62, 3, '20200403235959', 36666, 'Pendiente', NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=103 ;

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
(54, 'ferr', 1212, 1, 'lklñksss', '---', 2, '---', '---', '---'),
(56, 'asas1', 902, 2, 'asas123', 'z', 2, 'ferg', '---', '---'),
(61, 'ASSAS', 2323, 1, 'SDSD', '---', 0, '---', '---', '---'),
(76, 'Jujuy', 456, 1, 'RIO CUARTO', 'D', 2, '5000', 'Saravia', 'Gracias'),
(77, 'Jere', 345, 1, 'FILLO', '---', 0, '---', '---', '---'),
(79, 'fere', 112, 1, 'asas', '---', 0, '---', '---', '---'),
(80, 'asasa', 4545, 1, 'sdsd', '---', 0, '---', '---', '---'),
(83, 'asasa', 4545, 1, 'asas', '---', 0, '---', '---', '---'),
(84, 'ASAS', 2323, 1, 'ASAS', '---', 0, '---', '---', '---'),
(85, 'asas', 4343, 1, 'asas', '---', 0, '---', '---', '---'),
(86, 'asas', 234, 1, 'asas', '---', 0, '---', '---', '---'),
(87, 'Arruabarrena', 1860, 1, 'CORDOBA', '---', 0, '5000', '---', '---'),
(88, 'Sinbawe', 123, 1, 'Kirko', '---', 0, '5324', '---', '---'),
(89, 'asas', 123, 1, 'asasas', '---', 0, '---', '---', '---'),
(92, 'qwqwqw', 123, 1, 'asasas', '---', 0, '---', '---', '---'),
(93, 'asas', 123, 1, 'asasas', '---', 0, '---', '---', '---'),
(94, 'colon', 6860, 1, 'cba', 'cna', 1, '---', '---', '---'),
(95, 'gral campos', 169, 1, 'pilar', '---', 0, '5976', 'san juan', 'san luis'),
(96, 'gral paz', 285, 1, 'pilar', '---', 0, '5976', 'roble', 'maipu'),
(97, 'sada', 123, 1, 'sadasd', '---', 0, '---', '---', '---'),
(98, 'asdsa', 3232, 1, 'sada', '---', 0, '---', '---', '---'),
(99, 'ghfghgf', 435, 1, 'fdgfdg', '---', 0, '---', '---', '---'),
(100, 'Cura Brochero', 447, 1, 'Laguna Larga', '---', 0, '5974', '---', '---'),
(101, 'corrientes', 1363, 1, 'pilar', 'rio segund', 3, '5972', 'mitre', '25 de mayo'),
(102, 'los ponis', 3584, 1, 'piar', 'a5', 1, '5972', 'las flores', 'arcoiris');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejecucion_procesos_auto`
--

CREATE TABLE IF NOT EXISTS `ejecucion_procesos_auto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=284 ;

--
-- Volcado de datos para la tabla `ejecucion_procesos_auto`
--

INSERT INTO `ejecucion_procesos_auto` (`id`, `fecha`, `comentario`, `tipo`) VALUES
(91, '20190902142639', 'El proceso automatico se ejecuto correctamente!!', 1),
(92, '20191003000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(93, '20191003000020', 'El proceso automatico se ejecuto correctamente!!', 2),
(94, '20191003120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(95, '20191003120019', 'El proceso automatico se ejecuto correctamente!!', 2),
(96, '20191004000017', 'No hay avisos para procesar!!', 2),
(97, '20191004000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(98, '20191004120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(99, '20191004120021', 'El proceso automatico se ejecuto correctamente!!', 2),
(100, '20191005000015', 'El proceso automatico se ejecuto correctamente!!', 1),
(101, '20191005000019', 'El proceso automatico se ejecuto correctamente!!', 2),
(102, '20191005120017', 'No hay avisos para procesar!!', 2),
(103, '20191005120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(104, '20191006000017', 'No hay avisos para procesar!!', 2),
(105, '20191006000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(106, '20191006120017', 'No hay avisos para procesar!!', 2),
(107, '20191006120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(108, '20191007000016', 'No hay avisos para procesar!!', 2),
(109, '20191007000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(110, '20191007120017', 'No hay avisos para procesar!!', 2),
(111, '20191007120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(112, '20191008000017', 'No hay avisos para procesar!!', 2),
(113, '20191008000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(114, '20191008120016', 'No hay avisos para procesar!!', 2),
(115, '20191008120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(116, '20191009000016', 'No hay avisos para procesar!!', 2),
(117, '20191009000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(118, '20191009120017', 'No hay avisos para procesar!!', 2),
(119, '20191009120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(120, '20191010000017', 'No hay avisos para procesar!!', 2),
(121, '20191010000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(122, '20191010120017', 'No hay avisos para procesar!!', 2),
(123, '20191010120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(124, '20191011000017', 'No hay avisos para procesar!!', 2),
(125, '20191011000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(126, '20191011120017', 'No hay avisos para procesar!!', 2),
(127, '20191011120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(128, '20191012000016', 'No hay avisos para procesar!!', 2),
(129, '20191012000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(130, '20191012120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(131, '20191012120018', 'El proceso automatico se ejecuto correctamente!!', 2),
(132, '20191013000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(133, '20191013000020', 'El proceso automatico se ejecuto correctamente!!', 2),
(134, '20191013120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(135, '20191013120018', 'El proceso automatico se ejecuto correctamente!!', 2),
(136, '20191014000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(137, '20191014000018', 'El proceso automatico se ejecuto correctamente!!', 2),
(138, '20191014120015', 'No hay avisos para procesar!!', 2),
(139, '20191014120015', 'El proceso automatico se ejecuto correctamente!!', 1),
(140, '20191015000018', 'No hay avisos para procesar!!', 2),
(141, '20191015000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(142, '20191015120017', 'No hay avisos para procesar!!', 2),
(143, '20191015120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(144, '20191016000015', 'No hay avisos para procesar!!', 2),
(145, '20191016000015', 'El proceso automatico se ejecuto correctamente!!', 1),
(146, '20191016120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(147, '20191016120021', 'El proceso automatico se ejecuto correctamente!!', 2),
(148, '20191017000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(149, '20191017000018', 'El proceso automatico se ejecuto correctamente!!', 2),
(150, '20191017120016', 'No hay avisos para procesar!!', 2),
(151, '20191017120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(152, '20191018000018', 'No hay avisos para procesar!!', 2),
(153, '20191018000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(154, '20191018120018', 'No hay avisos para procesar!!', 2),
(155, '20191018120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(156, '20191019000017', 'No hay avisos para procesar!!', 2),
(157, '20191019000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(158, '20191019120017', 'No hay avisos para procesar!!', 2),
(159, '20191019120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(160, '20191020000016', 'No hay avisos para procesar!!', 2),
(161, '20191020000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(162, '20191020120016', 'No hay avisos para procesar!!', 2),
(163, '20191020120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(164, '20191021000017', 'No hay avisos para procesar!!', 2),
(165, '20191021000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(166, '20191021120017', 'No hay avisos para procesar!!', 2),
(167, '20191021120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(168, '20191022000017', 'No hay avisos para procesar!!', 2),
(169, '20191022000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(170, '20191022120012', 'No hay avisos para procesar!!', 2),
(171, '20191022120013', 'El proceso automatico se ejecuto correctamente!!', 1),
(172, '20191023000015', 'No hay avisos para procesar!!', 2),
(173, '20191023000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(174, '20191023120018', 'No hay avisos para procesar!!', 2),
(175, '20191023120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(176, '20191024000017', 'No hay avisos para procesar!!', 2),
(177, '20191024000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(178, '20191024120017', 'No hay avisos para procesar!!', 2),
(179, '20191024120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(180, '20191025000017', 'No hay avisos para procesar!!', 2),
(181, '20191025000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(182, '20191025120015', 'No hay avisos para procesar!!', 2),
(183, '20191025120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(184, '20191026000016', 'No hay avisos para procesar!!', 2),
(185, '20191026000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(186, '20191026120016', 'No hay avisos para procesar!!', 2),
(187, '20191026120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(188, '20191027000017', 'No hay avisos para procesar!!', 2),
(189, '20191027000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(190, '20191027120017', 'No hay avisos para procesar!!', 2),
(191, '20191027120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(192, '20191028000013', 'No hay avisos para procesar!!', 2),
(193, '20191028120017', 'No hay avisos para procesar!!', 2),
(194, '20191028120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(195, '20191029000018', 'No hay avisos para procesar!!', 2),
(196, '20191029000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(197, '20191029120017', 'No hay avisos para procesar!!', 2),
(198, '20191029120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(199, '20191030000017', 'No hay avisos para procesar!!', 2),
(200, '20191030000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(201, '20191030120017', 'No hay avisos para procesar!!', 2),
(202, '20191030120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(203, '20191031000016', 'No hay avisos para procesar!!', 2),
(204, '20191031000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(205, '20191031120017', 'No hay avisos para procesar!!', 2),
(206, '20191031120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(207, '20191101000017', 'No hay avisos para procesar!!', 2),
(208, '20191101000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(209, '20191101120016', 'No hay avisos para procesar!!', 2),
(210, '20191101120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(211, '20191102000017', 'No hay avisos para procesar!!', 2),
(212, '20191102000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(213, '20191102120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(214, '20191102120020', 'El proceso automatico se ejecuto correctamente!!', 2),
(215, '20191103000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(216, '20191103000022', 'El proceso automatico se ejecuto correctamente!!', 2),
(217, '20191103120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(218, '20191103120023', 'El proceso automatico se ejecuto correctamente!!', 2),
(219, '20191104000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(220, '20191104000021', 'El proceso automatico se ejecuto correctamente!!', 2),
(221, '20191104120017', 'No hay avisos para procesar!!', 2),
(222, '20191104120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(223, '20191105000018', 'No hay avisos para procesar!!', 2),
(224, '20191105000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(225, '20191105120015', 'No hay avisos para procesar!!', 2),
(226, '20191105120015', 'El proceso automatico se ejecuto correctamente!!', 1),
(227, '20191106000017', 'No hay avisos para procesar!!', 2),
(228, '20191106000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(229, '20191106120018', 'No hay avisos para procesar!!', 2),
(230, '20191106120019', 'El proceso automatico se ejecuto correctamente!!', 1),
(231, '20191107000017', 'No hay avisos para procesar!!', 2),
(232, '20191107000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(233, '20191107120018', 'No hay avisos para procesar!!', 2),
(234, '20191107120019', 'El proceso automatico se ejecuto correctamente!!', 1),
(235, '20191108000017', 'No hay avisos para procesar!!', 2),
(236, '20191108000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(237, '20191108120016', 'No hay avisos para procesar!!', 2),
(238, '20191108120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(239, '20191109000017', 'No hay avisos para procesar!!', 2),
(240, '20191109000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(241, '20191109120016', 'No hay avisos para procesar!!', 2),
(242, '20191109120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(243, '20191110000015', 'No hay avisos para procesar!!', 2),
(244, '20191110000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(245, '20191110120016', 'No hay avisos para procesar!!', 2),
(246, '20191110120016', 'El proceso automatico se ejecuto correctamente!!', 1),
(247, '20191111000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(248, '20191111000018', 'El proceso automatico se ejecuto correctamente!!', 2),
(249, '20191111120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(250, '20191111120023', 'El proceso automatico se ejecuto correctamente!!', 2),
(251, '20191112000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(252, '20191112000019', 'El proceso automatico se ejecuto correctamente!!', 2),
(253, '20191112120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(254, '20191112120020', 'El proceso automatico se ejecuto correctamente!!', 2),
(255, '20191113000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(256, '20191113000019', 'El proceso automatico se ejecuto correctamente!!', 2),
(257, '20191113120014', 'El proceso automatico se ejecuto correctamente!!', 1),
(258, '20191114000017', 'No hay avisos para procesar!!', 2),
(259, '20191114000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(260, '20191114120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(261, '20191114120020', 'El proceso automatico se ejecuto correctamente!!', 2),
(262, '20191115000016', 'El proceso automatico se ejecuto correctamente!!', 1),
(263, '20191115000018', 'El proceso automatico se ejecuto correctamente!!', 2),
(264, '20191115120018', 'No hay avisos para procesar!!', 2),
(265, '20191115120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(266, '20191116000017', 'No hay avisos para procesar!!', 2),
(267, '20191116000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(268, '20191116120019', 'No hay avisos para procesar!!', 2),
(269, '20191116120019', 'El proceso automatico se ejecuto correctamente!!', 1),
(270, '20191117000017', 'No hay avisos para procesar!!', 2),
(271, '20191117000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(272, '20191117120018', 'No hay avisos para procesar!!', 2),
(273, '20191117120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(274, '20191118000016', 'No hay avisos para procesar!!', 2),
(275, '20191118000017', 'El proceso automatico se ejecuto correctamente!!', 1),
(276, '20191118120017', 'No hay avisos para procesar!!', 2),
(277, '20191118120018', 'El proceso automatico se ejecuto correctamente!!', 1),
(278, '20191119000018', 'No hay avisos para procesar!!', 2),
(279, '20191119000018', 'El proceso automatico se ejecuto correctamente!!', 1),
(280, '20191119120017', 'No hay avisos para procesar!!', 2),
(281, '20191119120017', 'El proceso automatico se ejecuto correctamente!!', 1),
(282, '20191120000017', 'No hay avisos para procesar!!', 2),
(283, '20191120000018', 'El proceso automatico se ejecuto correctamente!!', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio_sms`
--

CREATE TABLE IF NOT EXISTS `envio_sms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_aviso_x_mora` bigint(20) NOT NULL,
  `id_telefono` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigo_respuesta` int(11) NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_modificacion` char(14) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad_reintentos` int(11) NOT NULL,
  `id_sms` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_aviso_x_mora` (`id_aviso_x_mora`),
  KEY `id_telefono` (`id_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `envio_sms`
--

INSERT INTO `envio_sms` (`id`, `id_aviso_x_mora`, `id_telefono`, `estado`, `comentario`, `codigo_respuesta`, `fecha`, `fecha_modificacion`, `cantidad_reintentos`, `id_sms`) VALUES
(1, 4, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190816120750', '20190816165730', 3, '1565984370.5673'),
(2, 7, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190819123404', '20190819123535', 0, '1566228697.9553'),
(3, 8, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190819124602', '20190819124648', 1, '1566229415.4304'),
(4, 9, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191003000020', '20191003120019', 0, '1570071619.8325'),
(5, 10, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191004120019', '20191005000017', 0, '1570201219.6298'),
(6, 11, 52, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191004120021', '20191005000019', 0, '1570201221.5874'),
(7, 12, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191012120018', '20191013000020', 0, '1570892418.6262'),
(8, 13, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191013120018', '20191014000018', 0, '1570978817.8414'),
(9, 14, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191016120021', '20191017000017', 0, '1571238021.1725'),
(10, 15, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191102120020', '20191103000020', 0, '1572706819.9051'),
(11, 16, 52, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191103000022', '20191103120019', 0, '1572750022.3718'),
(12, 17, 61, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191103120021', '20191104000019', 0, '1572793220.9978'),
(13, 18, 52, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191103120023', '20191104000021', 0, '1572793223.0956'),
(14, 19, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191111000018', '20191111120020', 0, '1573441217.8163'),
(15, 20, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191111120022', '20191112000019', 0, '1573484422.3551'),
(16, 21, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191112120020', '20191113000019', 0, '1573570820.1112'),
(17, 22, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20191114120020', '20191115000018', 0, '1573743620.1852');

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
  `usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_documento_adicional` int(11) DEFAULT NULL,
  `documento_adicional` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `nro_telefono` bigint(20) DEFAULT NULL,
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`,`documento`,`id_motivo`),
  KEY `documento` (`documento`),
  KEY `id_motivo` (`id_motivo`),
  KEY `usuario` (`usuario`),
  KEY `usuario_supervisor` (`usuario_supervisor`),
  KEY `fk_foreign_key_estado_cliente_adicional` (`tipo_documento_adicional`,`documento_adicional`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=182 ;

--
-- Volcado de datos para la tabla `estado_cliente`
--

INSERT INTO `estado_cliente` (`id`, `tipo_documento`, `documento`, `fecha`, `id_motivo`, `comentario`, `usuario`, `usuario_supervisor`, `tipo_documento_adicional`, `documento_adicional`, `nro_telefono`, `token`) VALUES
(5, 1, '89443194', '20190829103810', 37, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'f5fd07bbccfcb61c345ac8aadf426fbaa1a9a1070196ec8bee30c2ece49c28c4a20539358e9b4f1e8bf22540887744864d340295336918b4897014b37f2556a4'),
(6, 1, '32443194', '20190829163907', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '699e09cc06fadad07fa120867d842595d2ec6f184640b7331c314b94c853d406b32229d26b43b6d7735b7f81b882a4cc2c9b7d71101531a3fa4cda40769ad715'),
(7, 1, '32443194', '20190829165042', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'ca0f739f397047b2b4be51f9b2f9b49bb3f877bd5f61b4ae6b7b3d1c98d2ff78783c6c7b538fa30fa2ce7970b1b1a14a77ad3a53647e44246fab4a870554fbd0'),
(8, 1, '32443194', '20190830180159', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '6b461984ee437803cf35b3aec50d86a9541d21e7c1a3a562424c74d2fa2344e49d2e1e230c11c5a193599463742e45ad7cb4d01a5a5b51760b0c0aa3f319c68e'),
(9, 1, '32443194', '20190830180634', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'e911a8ce259a308a89f3694c3383362686cd0d5b54aa7a7ac34e403b57da7c283cd06d60a6dcfa56fea82ca36f5488d37e04a53aa6557aac73f51fb707bfe37f'),
(10, 1, '32443194', '20190901172221', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'f7982c70855dd120266f8395955375b66abc424b62ba562470ad5e8be336ddb760906e6fd249096c7a7e1b5cb727180d4bc2acc0c3e8ff9c3f6d3bba23327195'),
(11, 1, '32443194', '20190901172319', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'af5d8d163dc7c9d59ab958fbfa730cb75431c59d9c6ee1e6d382b78479b744d07e38c0e862c5b607490a0f3f07a119f4595b07250ae1a22035bd16820af3bbd3'),
(12, 1, '32443194', '20190901172417', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'b58c7fbb5f94509cc296221bfbad6aa32cb56816da51a2677531116c6b40ee4c515d37bbe3a15c34db52df44ab4217e060430a2d97c9259d5351bdbeb894aa1e'),
(13, 1, '32443194', '20190901172724', 58, NULL, 'admin_sys', NULL, NULL, NULL, NULL, '4768b8926cddeb27f3b4e5340c0d26e1917cb70b1bd0bd93295b22ea2026545a63f3acc2495ae11e94be3ee3297c71fc4a11dd03193823acc40fd9423313952a'),
(14, 1, '32443194', '20190901172819', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '035117e98f8fef52684de5c4ef1d3579a738b790c873f467f5e25e0316d240f773ba0f687c5d5bdf011ad94e8e7613f8e261af7e8f82db5b8ec57ad28a8fd323'),
(15, 1, '32443194', '20190901173214', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'ea033d78cf5bc0abcd88f5284625fa3576425c98f035a1eb14ce844b6af02649d7b0c0ca6db316a582b5d948400fa0f0a96580d54380d8d29dd3625985db00e4'),
(16, 1, '32443194', '20190901173525', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '06930cc70363d2e7dcc954539cf39de7d7e886e585708713655cb8983555e207fe0aec6a3b12549f41512571f40771ae3cb2e3ee586027950415521503454ecb'),
(17, 1, '32443194', '20190901174301', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '4dad3b927eebbc63973aaf8eb7f45b232aa8b9e9c1ed09e926494cdaab35c128da3375848b4c7d9b047d46aa964b7ee7262d9472759e949919ddcc9e607f1e80'),
(18, 1, '32443194', '20190901174507', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '58b11b19a8fbbb92741c04f72fff0c53eafcfabe3cbd376145f69756be6582d392f89ad461b64f8bbd2377c375aaa0e920ca05db22893429d079e8fc0a496e5a'),
(19, 1, '32443194', '20190901174902', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'b76c1c4b2be7cd3f5c3188e2cf0b9f051c7b599be64d2ea7499b42c31545822ef18a29e9158dc09e9cf31fe0106fb9ddf08fbe48183df45bcc5c9968b0e87c14'),
(20, 1, '32443194', '20190901175405', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '7289a295e943086c26b26266cd2a83517d798c380eb74a7d8fd5891857b9a4bb62ddabefdf3aeec40dd832504face04927790e12a903937d9ab7b2827f947f70'),
(21, 1, '32443194', '20190901180755', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '51007d4e969abd5e2605cbecb878fe9701b1ec51a9bdef897a6cb623c8ab90d7ae68cc85783015b7fa006b320143e80d92dfe76748eda56d1bdca6f97a5d70ab'),
(22, 1, '32443194', '20190901184737', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '3383aab3defd0b67b0c2335e48875f79c5c37a4a3cd3218780cf5164833c12dfcc32019c374355583e565a95bdd3aa98f643caf7b14473b3ebebd6deabc34d3a'),
(23, 1, '32443194', '20190901185229', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '764700da9ec5819b894451ef3486d661e83c400de21e2809a00c83ffc6a099c74c9745e9dadc6c634485b3d0dca26ec8adb05e0f9a3dcfaef22f7302d6bdcb48'),
(24, 1, '32443194', '20190901185417', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '6ea27c670e6416706efc4fdb905ca4384096148db93a4b2bce9f21aa3c4c0dd6773ea66e10536707155ba88191f00ac09c7d338bc0b45b44b1b270e57548ae43'),
(25, 1, '32443194', '20190901221824', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '8de876d25d90b511929d6a3a793c99a5ab3495493014a47807a70ccc4bd8ab588038fd133d04350576756b8d2219e1a0cc64ba51b5100c019214c135daa0ccff'),
(26, 1, '32443194', '20190901222212', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '981b7a6783e599192f384a8dfd788a36fab8ae47248d7bf1e8d96d595c48d792c3297b68aaeebb29429628fe6ce6e90dbad3a9ef26201a9876b9566cc7f44405'),
(27, 1, '32443194', '20190901223422', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '0a63e5af0713d6cb312e602f2b6e9762f3460e45abdea04105c04c7457eb99c8b7a6b6ad7381205c37f1488c45b29e5da6f53cfb98832e821a332522cdd44115'),
(28, 1, '32443194', '20190901224055', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '93a93bc787c2925b7662b2ef4ce355080b6e91a54ee1e48a89ad30025a4f33fab4e330bec5c82cb81b551d6436e463e00913b6f21f23a68e8a93906144ecf101'),
(29, 1, '32443194', '20190901224150', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'a791547d90103bde572ed298c097657a812b06ac26d18e8be37420d256a61cda8da7277b8fd0cc95ccce28e9fdc0e93faa2b547147be7b98b17fc6f0ec00a5f9'),
(30, 1, '32443194', '20190901225721', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'cfc356bf2cb152d84a97276a8219f5b7ae614cd8e1e45e373ec7f88d2987af31108bf42872be154c99046025e2f6f723d2e3ccbb421c4243f7bd23d30af77404'),
(31, 1, '32443194', '20190901230432', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '7dc65ff0817cb7f752b6feb1325ffb6a7d395f28eb1b92ad7b108190344b373fe6feb33dba9a4feafda8ba6ffc5784ee81282a11fe9ac473cd3977bce2e2170b'),
(32, 1, '32443194', '20190901230648', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '4c5b786f80500fe4b8d65f457ebc0ce7e69520f2b6b0438a493ab1cdaed0365d599197c297d3c88c562bfded669be4cfda92c96cf8ce40e76055d6d73a1df7c7'),
(33, 1, '32443194', '20190901231006', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'c4db51d31ada9fb31b720802ad813dbb8a210530255eb38b9dea6389e897e5dd6e77da8c5795bfccb5457eceb210aa35324721c9966cc9593d6dbaa3f13d7d11'),
(34, 1, '32443194', '20190901231144', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '367e298859e027d5b1ac04671374e6d69ac5e14b8980f3f2b3cdf12283c3876a689a2f7eaef2bd35968f0be6046b12a5ec34edb7ee45aac179dc7b54d260598a'),
(35, 1, '32443194', '20190901231422', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'f03a8e0605cae49746835250c04722bce2855bb0ed3ea5308d0a5cce5d15f8415080eed3f4115141fcd702b8e55cfc0c9ae63aa9a1dea21489e7d003cefa1791'),
(36, 1, '32443194', '20190901231531', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'ce33c2bb5dfdf19bc28374eb41561a3f1a03f007777b3ccfd39f5d9a6a7ac8c8e7a43b852db7ddf0192eeb2dc484affbe0344b7380e49532bf5831d9abfe1198'),
(37, 1, '32443194', '20190926111750', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '8526ec6813daa5817b5b24490f24f6875dcfcc0b8cfdd3e9915e750983e4b9a4214d32f41a84c9b3c4f916f771a7102d44fe94865d2fa0fbd79b5955486589c2'),
(38, 1, '32443194', '20190926113302', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'bf098c5e1763ec02dd7fa93375c7bb4a5cb833b76e6a16867ce6fcacd5c343e436b8dd425276d16520e82231f226f9b4986c71358d60847180089f9f09f51eb4'),
(39, 1, '32443194', '20190926113450', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '800e194c7f5352665ef7831a7147a39f5370a838582d3f64b0a155655a219ae2a1f055fa7a3759a236fd90761c4c9455d8a61302fb085dbeab715422f322a54a'),
(40, 1, '32443194', '20190926114518', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '3eb7d469c64209a45670a37e3edced2c0bbb3bf6f406b75197770e475211315b14f070e7b1d00000ab7bcc0a7695ef526309b617e4ad71b9ebdfa9886f34854c'),
(41, 1, '32443194', '20190926141648', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '85e9b3d4b0199fd086a16bb3304d6eebea36e4fb9822b66d4c2f67c40a8ce70a5881cc0f8051b780d2b42f269a7b43b16a469e9e6d3fd0b20af9bef30e3b1043'),
(42, 1, '32443194', '20190926142340', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'd31479a8ee0dcce59b8de774046d8469263167a80016f876661f85a36205d2f5a243dc558ad0275716034da61ef55c26e40f2a22efbd0978f9cb0eebacdeb853'),
(43, 1, '32443194', '20190926142859', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'ecab0fd7422d81c0dd72cc2e80ee29b92dde88b34e2032519ff0c12c73391f12dd123d5a5d2fe0af3949e3685f44603e6bc103d2843685098286f90a440b74b4'),
(44, 1, '32443194', '20190926143039', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '086382acfc99431b3b73253ff48acb0113c0275684fff0c3bba8ffb0b689e01fae71f598732baed76178b419662b1ec26d019ca3c54aa30cdaa0ad498187355d'),
(45, 1, '32443194', '20190926143158', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'aae0212ed7161cdaf387ff7e1121770a9bb5d634ec0e0966ff95ee601b53b4a1a514279822e6afe45c881c42f1f708e5cb63326f62699b3cc7cec1cc1becd018'),
(46, 1, '32443194', '20190926143738', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'd3969434bedea3a865d3232e7a4bbad82d8d34901f782620ac075a579ce29d1ad61fe2b19a2d49183108f3c7d70266d4192450e9cb304616aa73dc37013979a9'),
(47, 1, '32443194', '20190926144122', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'db7a21a7c2845e3ad71f64aab264ef0106893b9315a787de76960963f264eed939a956766207f693aad842f9f2a1063f5a08f7a099bf368c87ef1c3fd63ae6bc'),
(48, 1, '32443194', '20190926144246', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '473b7edbe0c6ae0deedb091488f1182719042a40358bde652e9b1d2d0e1fae90b7306f396b24d42e081f0e85b675128340afe94335c41107228bb25253ad9ebf'),
(49, 1, '32443194', '20190926144349', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'f4900a127cb53aff718c16af075fd83c1922e7035dd3a73e89196ae4cf4eb91f5f226542ae685e6db5368c90489c53a141afac68ae4ac344e04ba727d5414e1c'),
(50, 1, '32443194', '20190926144758', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '408f7c143d86b261da0994071969d07e5756d1bdac24481e4525b12949f16ba35d0e13d0019b927c1dcbffca1adad246de42857c0ff223268f35b508051124c9'),
(51, 1, '32443194', '20190926144955', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '8d4694db744f5fb1cc408e00645dfe5577ababc9abcb7f7853beb23d1ab7f3bd292ac2f738dc59d3d22d732a3b0895fac55573f9c71119b9a3939c624be71a4f'),
(52, 1, '32443194', '20190926145757', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '083f7a81f27233db84e3182e150b55d95eedd19df500321d8b3c81d670cfef370ad1922b410d61c425a804212a13794ad98649369c18a38ce8dcab83b57fbbbd'),
(53, 1, '32443194', '20190926150138', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '835eeb3d0bee4f6af870cd222e336e3661c41bfc995b2f1df87d8e3b72aac93f1d02ef5a43607a35e0dbc1f84463e34aa92e58b85758650e1d1e99f4768f411d'),
(54, 1, '32443194', '20190926151242', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'dbbbc7365f5a58873c88efcc0c0ec917125ae3e79c6bb90c6dc60c5d3d316f35f796ffdb4fe45c66337a3ac24ba837d44c854b53fc6716bf3cf71a9022d3d58a'),
(55, 1, '32443194', '20190926151540', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '5bce984512590ee0b02a022b4adce2a102437cb2c74f311b9cf1dcc8b655ff9c5ea4f3c95e54dafcce68ab740c9ec73cf811fe046a358613bcb290bbf544c56a'),
(56, 1, '32443194', '20190926152342', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '467313d4a83cf7168a11b494b1774f41f1a86e54879922d5b39e214534b1ae008af0e62d86106f92126b43bbd7764391ccee27cf9d057d75a71c4ffcc2e818d1'),
(57, 1, '32443194', '20190926152759', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '1df1871ddc09ff62d2a43a89c43ad3190654594503f563955cd481a2614601223fefe5dc6204d9b54f11172b2b8fbb05f36f75db643c3c6f534ec5632b08673f'),
(58, 1, '32443194', '20190926152922', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '96a319878ab876a993016378f1a265bd94727d935b2c47efd7fe8854becf869ffdf35fd2c7d97117447169ea58a4265eec3010ebfdeb81f3b05dc7ab0f8044be'),
(59, 1, '32443194', '20190926152938', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '1db6b32cd60db72f7f055aafd0407808964e1a84f52368ad549208b875a567937b8e32a065c8216190d703c12d2c53607ff229b737b3f579553ed5d5b0a2c2db'),
(60, 1, '32443194', '20190926153312', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'b6765b9c22a0ff2cb6286b3956fe57870b89a70a3265021f5f35212f6f5175dd600172dbbb4927d991b084786aa5fabb35d7dcfc520049de1c1d6b44eac38499'),
(61, 1, '32443194', '20190926153450', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '6b92632d4c294543386fea1c6c9d6684298ddca58afd6f68758076b133698f2f302964698da653ccfba232cb3457805906711b5a5e284e6789e5fff1e44d93a9'),
(62, 1, '30443194', '20191003110230', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '711f67604dee83584a65665a8c7c406cdb0b0b5d7b71543a6cf7c9e5721de039372402e2f5feadcdc70644e40e35cf06ab7950f6f318e9b981e7ed2a06caa456'),
(63, 1, '30443194', '20191003110306', 64, NULL, 'her', NULL, NULL, NULL, NULL, '9882f0eedad14cd93b8fc1b8732e895196536ebcbb784b6e4dc018f1d3a80a41efba77bb7c2e2fc76598dc8f40631d20600c56530214f602583f9acc8afef8ed'),
(64, 1, '30443194', '20191003110653', 50, NULL, 'supervisor', NULL, NULL, NULL, NULL, '7e2261d118940974c23044ae54c52f4ad85cffa2ae92826da69742ce225c2fac95bfd91efe1832a9caa28a8d6e2a3a1cd7128dbd532b4571e1ceef74a22e1986'),
(65, 1, '30443194', '20191003110715', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '2ce59463c77ec743465767dad17e2bb7c470ef666959192479baa6d83d26f722f1ed3a0713caf8b0006e8b7d22bb4bb6df0a59aed03a1a98e1764eb6942d351b'),
(66, 1, '22774142', '20191003123716', 36, NULL, 'her', 'supervisor', 1, '22774142', NULL, '50ccc1db3b6e449d59e3796caefca627b3c5ed11fbf6f5cd339542de29d2c25975b6690e8033d9cf17913494b23eb4858522b85b876357c7ba575b53cec6252f'),
(67, 1, '30443194', '20191003123722', 37, NULL, 'her', 'supervisor', 1, '22774142', NULL, '6ee5387218c440ebbd4641e267c3a386ed8153a7615cc47ad1cbfcdbf80233c6c0c41e5e748262d43a871aff8cfa61e606415309bc91f7330f1beaec5f8dcdd1'),
(68, 1, '30443194', '20191003123752', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '598eb5bca8277077047b61cc0a8010b88509317261e7afc2ba1e6c55f153d117d137d3e296ecd769ea962a757fdc357b8a3fb1d987d947e1108dec66c3b1c699'),
(69, 1, '30443194', '20191003123834', 64, NULL, 'her', NULL, 1, '22774142', NULL, '01fdef5d37317f7506cdf2ed66a1f3da633d3fc1ee8da8009c2c8bc391dbaaae889adfc8571833c95d94e2fa74e1f641a8bdca1feb65cc8e175e43a1251d4ffe'),
(70, 1, '30443194', '20191003124139', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '863837f24faba4ce7d61a020d61efe535b28ec42a3682db1fe363498b714cf1ccdcb886eef369c1ee6bc3fe5041ba08a8436ba42cb2827ea6e3b60316ab148f9'),
(71, 1, '30443194', '20191003124148', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'b680906f727fc4ea6902237e33b5009910c8775ce3b121875b37b0260c6d2f21b6f3b24279a695a2a4f4ef2c1fc4c5d7eec88cef882e8572321bdf95d0b3a254'),
(72, 1, '30443194', '20191003124319', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, '3cf9c56b3b9d344ee880718be9be74e9937ce4cc5f620009827fb1265e34443b37a041151d92cf83703b6ad8946befd8e6992081d44db597871a391d4953c1ba'),
(73, 1, '30443194', '20191003124454', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, '1109ec09e2b0a81afa24219425d3fe3730339099ed324d9ef4479288cedfe87b4bc70a565b809feaa050b50218d900810e75d5052148548b798b2626e21c61c3'),
(74, 1, '30443194', '20191003124503', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '42eba097f04b3eccc74b530b91ed9f375f4b9bdb90336400312b399816a64cc2758e8b70b6b0209e1e377697729e18fb6256d1c9d49c26942992e89dd63d4cad'),
(75, 1, '30443194', '20191003124510', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, 'e0e36bb2b961bb8c93b968c9229ab95590fb4c3a759903fea4796d0587333b9ca10e778782059c7cd358f3e168af7e8166089079e8df26a956b813d19fd5821e'),
(76, 1, '30443194', '20191003124520', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '37f90c72e1042b43e80696ef21c78aa22e331a6dd434e154cff6b824b729656ccd080c9ea3f074a4d26cd506163029919f873864b34377d45e7677bcab5ab28d'),
(77, 1, '30443194', '20191003124539', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '08522cfa93fe0670e944b78c026fecb1521d6bf21966eb808b5a5a9280de3ca620d014d54bb522ae7b0617003ded17e0003622e0f1cf6ebe2fa8b9500465a712'),
(78, 1, '32443194', '20191003124602', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'd6915fa34124831dd874831a906caf7392e9eb7bcba42c3d4622f2d9c4abf594b76a66e647c7f7792acafee09e92dfd0b93740e53fb050213d7b8736f0c3a8d5'),
(79, 1, '22774142', '20191003124946', 54, NULL, 'supervisor', NULL, NULL, NULL, 351156727305, '286bd3c3c583115ada0ab154dd79641a2fda46965753ae254f11cfa18a803418dde1b3a5f6275b9c0a39a50d671b3d162eb8f8047baf62474499930d39246a1f'),
(80, 1, '25000458', '20191009121927', 37, NULL, 'her', 'supervisor', NULL, NULL, NULL, '6476314a96d9315fede302d3f08b7b13ad5eebc4dbc77a8a2b1b6a58758ed18e383014a4f03947aa5f1ba65f6759066960d23bf3e1b5000ba0d44e400f1fcd14'),
(81, 1, '25000458', '20191009122419', 37, NULL, 'her', 'supervisor', 1, '38292091', NULL, '9e2097925ee88fa427f5bfb91a25e8434f8f203ec43bd5fb2771384594700116cdab739e839edbe85679a086787a5b6a8182d7a47769b704cbf9ecb8ed30273b'),
(82, 1, '25000458', '20191009122557', 59, NULL, 'her', 'supervisor', 1, '38292091', NULL, '3440ba889bd2d36329926cf21ea1f13fcf31772b3a6173fa738811f2070d55b312d964690a81346acd4c55176692945312d412df4d93e8a34a3eeb29bf91f1ee'),
(83, 1, '25000458', '20191009123253', 60, NULL, 'her', NULL, NULL, NULL, NULL, '86d5f7711e1e77ffc29d4aa512e89f7b04b6137d7a064109a78418473258b5acbdd907b8ab7096b6eede18e7b71e6f9b0306ad0aeb7aa37831b19c04978b3c66'),
(84, 1, '25000458', '20191009142724', 52, NULL, 'her', 'SUPERVISOR', NULL, NULL, NULL, '1a1b52c63fbcc9ba5a07ad87b5eec927431c21265121e717a0aced5ea0df922c2a1d48682c6ed771521b0a7822ce38df108de788616472c35e15ef8413637100'),
(85, 1, '43256789', '20191014110128', 36, NULL, 'supervisor', NULL, NULL, NULL, NULL, '4e11b644145d3f3d8119a9ec2e975417fd2fa52becc404703962d1dd288e85f63619b4a98d256468988364525c057c03743266436ec8d585ebfdd9d42eb8259d'),
(86, 1, '43256789', '20191014110130', 37, NULL, 'supervisor', NULL, NULL, NULL, NULL, '7a8cd5e25d01c2be9a9888819a4d0873c95d469c38486e196e5e31e37725805d831ee8dc957094d38df37b9d811a5cb7ee05eccac4a814f822e1bfa82621bd86'),
(87, 1, '67892222', '20191014111013', 36, NULL, 'supervisor', NULL, NULL, NULL, NULL, '4663869a31192af558ead87c19719b6494c109c9921fc30d5ae4bb5cf9d37ba56be35684bd535dab978598f530e5c493a1416ce92579dd37a0a4b89c4c25b182'),
(88, 1, '67892222', '20191014111014', 37, NULL, 'supervisor', NULL, NULL, NULL, NULL, '65aa338b45fe9d89f66ecc9af871e636c1f1380e38eff2753c047786e0d7626e1b928f0fa8f9034878846cb52c05f6ac30937a823d286c492c8fd1074fdcd09b'),
(89, 1, '9874561', '20191014111533', 36, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'c69254e2f2bf60a8b6ed868c519fa08890b5f7b6c45805eb8efb847143e679d6656aedd3201291e4edbf6486c62af02849fa36bda64c80f695e7dd12b65307c1'),
(90, 1, '9874561', '20191014111534', 37, NULL, 'supervisor', NULL, NULL, NULL, NULL, '3cac17c3a111ad7d10ced4b4dc90f56fbc942315e0b831dd5709e2e13cf8ba8e6f01c92581b840c15a81152718db12cf772b4bd546732654097234a8170423be'),
(91, 1, '9874561', '20191014111611', 50, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'a229bbc8d3562a05ad242265baee0eb196833a5e267e9e294e2e72cabf2366f4bd9a4afc77c32373989a879c8b6a345fa50f1888616325caa33904a5fb3a1200'),
(92, 1, '32443194', '20191014111842', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'e2244c76136a08f25c05e1bb37a441a7c859caa5ab23dd822bedee4e764e6e83193b6bc8cb02d8195a30085f02b6192734ca89257ac33e031a48b2c12b4daae8'),
(93, 1, '22774142', '20191025161631', 56, NULL, 'her', 'supervisor', NULL, NULL, 351156147241, '3f2aa1a48a57058b244dcfd5f3c532bd193b5b2cc92e926886a18045e1f3d4de84ae0716215a45643accecb69a3f6bd1c3140a09c1852189b845268865119d6c'),
(94, 1, '22774142', '20191025161702', 56, NULL, 'her', 'supervisor', NULL, NULL, 351156727304, '0621094c0c74867d3318c609ca2cab097d95967d7829250a2d2be6fb547043b2668d17e81de067a5b3b6ec87a4d5e5303696b8f725e4e947b77f971844e5b9d3'),
(95, 1, '22774142', '20191025161848', 56, NULL, 'her', 'supervisor', NULL, NULL, 351156727305, '2621910518b583c955766aff8a757490e3a1d8ea2219618876c05799efb7f016aeb0a2274d1b6ee51de924698dbad1f29299c43f23229a2b64279a63c5243807'),
(96, 1, '28934582', '20191203082229', 37, NULL, 'her', 'Supervisor', NULL, NULL, NULL, '5a16cfb3348dea52dd53a41c3120cb90911d3b38e9427564dbc4720c0e56a8df230072d64a8ac3a6dadb4777e468dadc32333a9a0a86a1e59f91ac260abc21cc'),
(97, 1, '28934582', '20191203082445', 50, NULL, 'her', 'Supervisor', NULL, NULL, NULL, 'f4c4e9bf157a6b3685d582ae35282fed36142c0c1fead31f6f3b66808216875bea17742e900ee86143d221e7f42b63d3b4bfa768ede3cfbd1b2eec9833c0d566'),
(98, 1, '28934582', '20191203083455', 58, NULL, 'her', 'Supervisor', NULL, NULL, NULL, '934fac2568801791490a32f4d01e185ab275acfaf3701dd00e38e0a67c2cbdd5afd56150061673535508bf6b6134a806a414e9c6d65d08f879533707f4d9006f'),
(99, 1, '28934582', '20191203083629', 64, NULL, 'her', NULL, NULL, NULL, NULL, '3dca9822bd82019152710332cf7ae36c3b4bf9ce222038b24aa1dc6cdfff4b7e08ef6996ea6a157c7934c29d97e87a578f7eeaa723f6da54012141c934bb9c0e'),
(100, 1, '39173040', '20191204211840', 37, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'acb181f1a41e0e4c385c58c1aecaf01f5261d7fb9feecf075152bbe0ed07002273876d785d92d7f688f9e71f88740de4267c8672a50ad2b45baf9c58c8d1bc6a'),
(101, 1, '25000458', '20191209095451', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'c961a8dff7a4e9f6decb4105f514e652fa796dde379731688b5eede8d0076453367b6bff2076034bf7533b4fbfb0efe7175cae30af6c12d3b7669ea1a9e0af9e'),
(102, 1, '25000458', '20191216172717', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '9fa6344648da50d8003971f29213e6919c9152facfef31060775341b77d64363897eff61643e09d0721c55d9a26e4ffba3eae86d7040b049c3b6c75cf6475f9d'),
(103, 1, '25000458', '20191220103134', 59, NULL, 'her', 'supervisor', 1, '38292091', NULL, 'd960634034cecf6f27f338c271044d791957baf0216c35e1640a7c8fb3a48f55f36385c7118009caa7711a01cb53cf25f2033ff149e411f5950c7c1f5a30ece5'),
(104, 1, '16731731', '20191220105543', 37, NULL, 'her', 'supervisor', NULL, NULL, NULL, '097fbb0eda22a031ab5409dc8470c3c105ba3269414447f94391596cf85d2ec0854450f27ed5aa3aeff6a904a4f886752bf9393c1abfd26b6fb4f6a7a6623c75'),
(105, 1, '16731731', '20191220105622', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '3d4bb29c57b2ea6fe776ad556b41a486066ae962b90bc901449d9ff81467d900b10d9fedfa2d5f54bb0ab1b4abd76cf8ffc87f6ea010699e159521833aed5d07'),
(106, 1, '16731731', '20191220105711', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'c851c0f9cc8458f1990863ce4cebb096085592422a203fb69ad929fd190a238724d57721ccfbb794fb1b3d354077a4359c839efe26dc893179326a0cbc50478c'),
(107, 1, '16731731', '20191220105731', 64, NULL, 'her', NULL, NULL, NULL, NULL, '9ac7ab316fc993ef9ffda9abf33342cc811aca4e2ef559082ca4d5de84ca7feb82796e8b2aedb1ef42c8f0bed968546b238cb2824bf8a2ce654b5dd09a33e886'),
(108, 1, '16731731', '20191220105842', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '3e42ec4943c0949986dad3965bf8504eaef7e0cbd4b6637f8e663197778afbc6464c7b3b3437b50f924f56fbbace79d3ee7b45469f2c2614f8610860ed53e5bb'),
(109, 1, '16731731', '20191220105942', 64, NULL, 'her', NULL, NULL, NULL, NULL, 'ef2c301a2067a089d90acd50d7ab1feb7b56376e267eaae90a3c70d7592e8973bdf8e7e31416020ba9289b559160d4da64bf93a98772e778e33f39ea8e175939'),
(110, 1, '16731731', '20191220113941', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '1ea3fe7a9848ac77ad17669fda8c1d4eb365bf4ab5dd1ae745fea222a730d78497d6fcda413ec3beace5f1eee5cb38801d1910f719902d36011dc849c5b8b93b'),
(111, 1, '16731731', '20191220114006', 64, NULL, 'her', NULL, NULL, NULL, NULL, '31341e3ffd9164b3777ca5e01446685068fb5618c960179072303e08deb17e280e21f1248d8b563e78dec4eb61e4e58ede12776aa2026cf23a89c95e8854f2e4'),
(112, 1, '16731731', '20191220134052', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'f2ab4fa4896783ea6fa07e83305ec01d36cba0e212ef49fd8eb53d3423837ff4ce61b33b550c4d0550b2b44ab00ed29d61c1a9c884dc6e93c2475dd66240219c'),
(113, 1, '16731731', '20191220134102', 64, NULL, 'her', NULL, NULL, NULL, NULL, '951aabea7813d4fbf393b5ffeb45308d77627ad567e50d7f35997b38479ca1e4a4fa68b07ff21e3cd6b006cc996d14f0a3188fcb8ff88e4604216e90c87d3d55'),
(114, 1, '16731731', '20191220162451', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'b83341c9a55b666d5197063402e8ce01c5a6b0233d1f3166b7197990376af7b88a3e08e71ad0dc45b4ab6eec5b8d7f496c23726d818e83923549c5597e96f93a'),
(115, 1, '16731731', '20191220162500', 64, NULL, 'her', NULL, NULL, NULL, NULL, '3d77fef9fcf595dca66b38cfd7362f14700abf801dcae5b8ffcf7f193bd9fa11d639642f0a26597676ec76fa1254d22f780fc240dedcdcddc9f35882395b7056'),
(116, 1, '16731731', '20191220162948', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'd6b45392c6f4d3eb8a61b0cd6ddfe35dd4fb32f6a2814356bd0efb08bd7f344e8ae60a5313ccb0272ab73dbe8ed0a258562318699d58e67a24df850adbd40b6b'),
(117, 1, '16731731', '20191220162957', 64, NULL, 'her', NULL, NULL, NULL, NULL, '8abbe3bce4977ddebcbc954efc91f81cda5d01133270c48fc7208c4c4e5611f6a4904c44c088b73a0de0af98aa3752e028cac6ca04f0a017bc2366b4ca3ca765'),
(118, 1, '16731731', '20191220180545', 58, NULL, 'her', 'SUPERVISOR', NULL, NULL, NULL, '8b3cd355a6f51cf8383c26736e7babba64e7d381d28a0ab7777030d049db752798a079f4fd3854254ada3d769e1095e02816cd808b932a65af1701e86fa1997a'),
(119, 1, '16731731', '20191220180815', 58, NULL, 'her', 'SUPERVISOR', NULL, NULL, NULL, '7234a428ce24fe8ce2a69be66f9842ac0b7a82dfc2fc44ada3ec1a56cd09296e6f1d3fd09daa1d5709ce85e31c8d4036d1d51a81ad1ab13fcbd9c06ef7261617'),
(120, 1, '16731731', '20191221090132', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '4c1b3e018b84d294862998de2281c97d0ef13f9e461b453aef5e34a112614c6091a18b5691405ebe71c3ab32074ffaebc49905afa14cffa80e47c437ccb2ab2d'),
(121, 1, '25000458', '20191221090447', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '869c7e8ee087884604118588df3a7b8665b9b2ae622ae7373b1b4b4a3ad4654f0853e30be9be90f0ed554a08cef3799c0d93135678534020d3474ff860ccca68'),
(122, 1, '25000458', '20191221090509', 64, NULL, 'her', NULL, NULL, NULL, NULL, '7b2e5d057fcb1c50992c6bc252d518525f9676c1187bfe048d5adfb0d878711261b3575e841163ef3a01275ab739f4fde4826ce88935695dfe3bf94bdffa9278'),
(123, 1, '25000458', '20191221093457', 60, NULL, 'her', NULL, NULL, NULL, NULL, '64fefb5a1df8795cc07d652098013ac40179fca180fe7904284c085834591410827fb021a859932568dfea14cb327b0da71727ebc42d18ccae568de43a38e4d3'),
(124, 1, '25000458', '20191221093531', 64, NULL, 'her', NULL, NULL, NULL, NULL, '7639574066238d91c43efff6af11e04127fa88f27d1b9b35f3cf305e52938c675502d24dae5d7638219a81ef6a44da1502c3c8875ccd760b1e14d42df138b8a4'),
(125, 1, '25000458', '20191226194905', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '07a224c0183896860b389f5f7ac8662c19dc45288de3ce963df547bdc3405f906d3d6a80398863371e53a687769ee4f3e3fde5b8670bff42ee86bdb16fd808f2'),
(126, 1, '25000458', '20191226195047', 64, NULL, 'her', NULL, NULL, NULL, NULL, '206b8bb7da64c774f94f457d05d99bb0d0e42887c17e524f8242ff57aa85d07293ab52b2603c0c34c0cba81066c5815b057bfb71a32c851c49b1f1eb35fee6b8'),
(127, 1, '22774142', '20191227090358', 50, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'e59362e5469d0c75afbbc7b710bfeac5135cebcac6389794bd1d17e8d56aa9e4ffcd6bfee5c24bf2210ef955f22d3204b93977a809bd473aca110d3e6ec4bced'),
(128, 1, '30443194', '20191227090425', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '7632e3ad48a4fdcbe332933ce8f49ee905554ca6c284ebea56cb9598441277fd5be6d2952b616d082edf0bf092fc01e67d05f2bf8d01f6041008b351c744360a'),
(129, 1, '30443194', '20191227090441', 64, NULL, 'her', NULL, 1, '22774142', NULL, '69b0086ee616a99ce3476cc2795f14919231dfdf15eeb1a1d44acfba6ace96cfa75f1a6e1ed217302576838b0e00b717d160598949544555bb0ad26440fb0581'),
(130, 1, '30443194', '20191227090539', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'ccf031f31c0cf4fcf74d2d38822208a8ba7fc7a401705e5aeb23a8d7c60a8b8f5fd445d7ae15c26e2a498eb29bd52914ccb014b6c049a508251018701b7e1950'),
(131, 1, '30443194', '20191227090553', 64, NULL, 'her', NULL, 1, '22774142', NULL, '6db9656f0973875d4d65ebbcb086e449663d386c9fd1f265f7d6d76f305b6fbfd50850116bb5d486bca0bc5d0746f19a71051cee180b401bbbe8dece524555a4'),
(132, 1, '30443194', '20191227090625', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '5d22661e06052ccf1e711222fe65917551b7a3ee123856af82cce78107a0ece9a2551fae6750cce16abe5c9e2472688328e143f91b18129fa40607e152db082d'),
(133, 1, '30443194', '20191227090649', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'fd4ce8da33f9b3cb0dbee7dfaef143e0400a1dad780fea398c84e4d1a2ef2cf3d701d78245bd5f05641b6a91f9eca5b7a8c644a81c01aaeb2f8a58cee9442c8f'),
(134, 1, '30443194', '20191227090830', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '7d8fb1d37bc0d9f075ccf35183aa3e7f82a6305d93f4850a5e66d3d37ad8c8824f40bca6f40ba6e4c18cecd5ebb44526d0b88e7d97945b6df063c87f67047d45'),
(135, 1, '30443194', '20191227090939', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, '0991b7be59450a98d974ef08d14ed7e044663d7d6a0b59fc4d66e82e8baa5a39a5d0f644261a962af35e5aa35ed37c7ff807b821ca8dff0be063bf844ca2fb1a'),
(136, 1, '30443194', '20191227091250', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, '66a14569a919c421c1c662bcf787a9c7ff84c3e082b873c6667ba182e03305cb151e3f540d38af59c56ef0fc6bc207155032ce55f4841f80d834ceb36fe286aa'),
(137, 1, '25000458', '20191227101231', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '45228f9469b538bf369465911b7ca617f76223d1caf6ca0ad504aa582e98d3043e602d8b4d18372f9ac4b805b18d4e60479619c3384f0376352b846be37bbe21'),
(138, 1, '30443194', '20191227101501', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'f4f52daee4bb0152fdccc8b3c66f9ace3601b7064d783808cb60ca758bab8d1ce71ea3a58717fed4d7572439cb745fa687960d3ab7a8f3a8677b3aa8c733d869'),
(139, 1, '30443194', '20191227101522', 64, NULL, 'her', NULL, 1, '22774142', NULL, '3d90c5d12c110c463c912353ee803c3a3044f859dbc7abc41b6f6064c753642e04b97776e6575bf01a000cf7723b42ad97e9d8708db3e279aca18d743243e34a'),
(140, 1, '30443194', '20191227101623', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '6aeb74122c70be5ce2a115742008039cfedeafca4ba8a49c190b3e136631cf17fc62d3e7f4e89da8706a9abd96cd7e60394a4bdbf956308846afc81d34849506'),
(141, 1, '30443194', '20191227101639', 64, NULL, 'her', NULL, 1, '22774142', NULL, '5639717a9516b735bac6807ed80762a17610d649778c082ffdd79d4c6b6b65ca68e47557debcda9d26a8b056d27444b3b3543d74e99bb02c05f98c319e82a689'),
(142, 1, '30443194', '20191227101730', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, 'd6ce4b2342902d7410d8d67282317d4697caa6f8c700a2b019f97a63a77718b8157e2758a6c6e90228ee9a6995dc8124f4663f092e00a6d02b26dcbc07bfb203'),
(143, 1, '30443194', '20191227101745', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '80c7bb56f074af36ccb99b707fe4301ea76195a9d3790011d1191617186a01d4d7bcd428aac499540f3953547bce474038114f643e54602d8eed7ddd8c91b0ba'),
(144, 1, '30443194', '20191227101803', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '35a62939504c864b81860f1bb185a007db33105a284b700f0edc71640605f95ddf49c1f344b2f39d5ecea33659903290f5a1a3c9b6919400bff81d2431162b40'),
(145, 1, '30443194', '20191227101813', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, 'd3690ad927549f10468cf9387b23bf9dd0b96f5c80dd2922d536c83e568655118e0b78903a29add486e6df312006ed8cb6f10eaccd6b74714da406229f1e69a8'),
(146, 1, '30443194', '20191227101833', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '69efea9d842b15f2d6e57ac13e6d4d6a9f58edd87fb65bd70e1a7f1086ad943cfccfdaf8acafd79b9747bb6036ed05d317b13d145d78548f79ad07daa310e39e'),
(147, 1, '30443194', '20191227101840', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '215560e2483df63c253356f9d68f6687bbac0dc0ad22b86f8242d893430e1a3172c40803af80473fe40967fc6e87f9fd948bf566be1fdc4a5bbb08b29fd5d1d4'),
(148, 1, '30443194', '20191227101852', 58, NULL, 'supervisor', NULL, 1, '22774142', NULL, '5186505a02cf88c8070dbc0b7571b70c05fc82b105dec4cd0ef4cbd9ed9ba9414bd95e4b72f34a2fdc0d9205cc2830945e094932b49ddf5b888fed333224d163'),
(149, 1, '30443194', '20191227101902', 64, NULL, 'supervisor', NULL, 1, '22774142', NULL, '6e033c6260e84646974c7b1e19b697c9b51e23fc44451fd870cf8f87447fca1dd081d3b666506093e7625addf1d636286556ba92e71e9c584d9b7396467cb2c4'),
(150, 1, '30443194', '20191227111939', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '8e1637a638f7a9838b009016d4f0e24045b24c54ab67d8d39df01a73b3d45159428b34191f8ea8a41fd448693d24fb5bac4bebbe8b157509cd774ee6d118b515'),
(151, 1, '30443194', '20191227112000', 64, NULL, 'her', NULL, 1, '22774142', NULL, '411d4d6e100ea09b81f09c6723bf708ef663df1edcd9285981f3479ffbba527f00ac4260d8edbaab9c6aa92d84421de87c34f112fec191533112f5af801daa74'),
(152, 1, '30443194', '20191227112102', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '5a14249f777bef0c19aaa567df43ae75afe876bdd3788dbbd36a2d49cf0708ace7a4efc199b8f9bafb8a4068cf786a82bada45f426e2a8d6ad63735618fe54e2'),
(153, 1, '30443194', '20191227112117', 64, NULL, 'her', NULL, 1, '22774142', NULL, '053a395af76f74044a150b013fabf35c0d8106d66b1c147a286955af2d40778f1689bba7bdda58d1cf2fb4cd9b9204bb1c686623acf82b007f3f3890aa0fed25'),
(154, 1, '30443194', '20191227133855', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '2aeaa1d427d68e9a35e138a4e44594ef02621a80acfe219f0d57525dd39e3803a2e9d3e82b53104a4302b9fcd9808272977ab94e10ffe431e38dbf705f1e314a'),
(155, 1, '30443194', '20191227133905', 64, NULL, 'her', NULL, 1, '22774142', NULL, '17e3779aebe0fee7522eef12cb75a3e032658a9fdab4d608c2e8048fb2cd7a6a25cfb8698e6e39342d0a80ba8f857ec2505d599506bcdee3256a9f8628591d7d'),
(156, 1, '30443194', '20191227134058', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '44e48883c5f7d6a6160e13b71d8179dc8eba6a30279e03360da9ea49a6282a126f0f1322f59cd949cc9f8048e9793f87a070b66607d4b41b2bf89f71d61dcb08'),
(157, 1, '30443194', '20191227134117', 64, NULL, 'her', NULL, 1, '22774142', NULL, '329e978df5c2f543df3bcc716f2f3afdc5ecf0e530e004c3c9533f3ad7995711383a899e60ba207b41175ba66eaa8f91a2c155aa3d62eb6d57f436532c492d70'),
(158, 1, '30443194', '20191227134535', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'bd875b3c5e768dbaf84949a1583e0911f9b6220c4fb7c28e954425f0b51da5b64770d054aa4645931c531687dc469f539e0ba79595f6140f050e4279700b257e'),
(159, 1, '30443194', '20191227134545', 64, NULL, 'her', NULL, 1, '22774142', NULL, '6a903a2c80906bd5a951371cee8ceff63cb898a806ade6521513c3892e785a79ce1b451c1fe2ab582fbfd70050cbec50ab12982bcc03a1f171961d66d2e8703a'),
(160, 1, '30443194', '20191227134622', 64, NULL, 'her', NULL, 1, '22774142', NULL, '87d4fdd26735298566ad968ffbc67d01684df080899ed23bbc6615ab43a9bb7d6f8bc37bb21476756d069ae8257757d0d66eff7795d3f8cd5667208a6981fe25'),
(161, 1, '30443194', '20191227134837', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'e838ee7f376c286bcb0c607fff376bf60117d8dea06a823a7768562c8028900ac267c68f4711d5b22bcc7276b2e17fb1d00c4a42a4bf33bdebff726ffdbe890e'),
(162, 1, '30443194', '20191227135040', 64, NULL, 'her', NULL, 1, '22774142', NULL, '74e970c03007406ee754f45902a180d8a7e666fa9cc4596379e5e6544398bf80658f1b8664be43ece7b95cd33845ee91ca3e5153e5f988227b26214a0f043f0c'),
(163, 1, '25000458', '20191227135240', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '887ee8f74d19662966c6a4ad0668840dc5e48390450196f47393ed708d47ce4e66a585026e0864d2d8f99abd6a04e870572c32712480f0bfafd49c457110b7bb'),
(164, 1, '30443194', '20191227135306', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '6dcb535dedab0f28cf55c2dc5d3ac35a19843e86e712d7ece28541db19e1b1009ab6c950170145e70f5bc4dc2444b650db635f5d4b9474d1d439f7949be69067'),
(165, 1, '30443194', '20191227135323', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'bf0ef1f455ccc197692e7b68f261d8784931283e68ecd7703329cd68ea26abbe58b02ec3b56de798d5130b7149d7f2e76e0c2be2c9601dc2cf6dedf6b3ee1dd7'),
(166, 1, '30443194', '20191227135529', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'e06046239c1e281eaa6be24328a876bda2ac7e1ff7d091f0e33a6d89d46c2519b1f01110e507fb1c918f2944ea8db6693ce89f288142cdaf05f191ad43884d9a'),
(167, 1, '30443194', '20191227135539', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'dc5a3a9a6d07e90a117ab1c4ffde81bf64fd88ecf6b5f0ba7cba352247c064ce265ebb91561c842b00ade58806efd4350d37c494388eb16e5632672b24791fc9'),
(168, 1, '25000458', '20191227135829', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'b517955d807c620407fd6b49ab51730901b8713e6f6efad42ae2001bab69fdf1201c0f4eda928a36ad305bf5bbb0b904a95fd639862a284e55137fd7544a6b4e'),
(169, 1, '30443194', '20191227141648', 58, NULL, 'her', 'SUPERVISOR', 1, '22774142', NULL, '72e9696de223c760cbba9f901797cf8e483cc7346afe66c7e39793fc3a81c23249ed95fabdd7ad3ac6a9decd0b252beb99ee4db02a697523d90dca61dbf9d0ee'),
(170, 1, '30443194', '20191227141700', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'f0683c0ca113386a1230bea08013030d6ce00b3cc6f9176a0dddfc57ebb85a7206f1314aba38e0cf803db6547ba8aed3fd36c8b2002bfc66ba0608be97f56875'),
(171, 1, '30443194', '20191227141901', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '46885724dfa24836bf711d72bd745f8ac2a215d61ff2fb18f028e11e1561a1f2af1a7937e4a955f19fb1625eee8eb365b929b21bbc86771800650fe4a58ceab7'),
(172, 1, '30443194', '20191227141911', 64, NULL, 'her', NULL, 1, '22774142', NULL, '26f4761361a1b43f62c339383e3a9a8509cbf5097b168d12dbd46997e191c8dd3891ae456d8cedce3bf4d32f215db9900bf2b11e55a8cbc44e2321e054cfc775'),
(173, 1, '30443194', '20191227141928', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'f6792345847a46df830ec9f1b6390750c230043e51ff94ba330cf17df54b07d94c9a5af3970947d4d40bad0ea1eed9982538debb6d1eaf7c9e0d6938fe0a414b'),
(174, 1, '30443194', '20191227141944', 64, NULL, 'her', NULL, 1, '22774142', NULL, '42abe0b0c8747031a48889f89504aa7ad1101d6f69e164905a247a9ecaa7aee4f7df456bec0b3e7f1bbdb43fe2c69c065986d065f924a6fdf52172b1953673e1'),
(175, 1, '30443194', '20191227142054', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, 'd5577f0bf8b78a1ae75c69366ed48f974286dfd2a16769409484490465adb3bf988ba1ee99ec4ab7e73f1579151503293a5dc4b89f7f1c2aa88389483d15414f'),
(176, 1, '30443194', '20191227142104', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'fd32cdda033691fa3813db78f3a6c0116e04faaae2b857bb5378ae09b6fa08b091aabe96a0fd7cbde0b29b2c17240993b93e69e9015c0bada8edf91450bdef1c'),
(177, 1, '30443194', '20191227143128', 58, NULL, 'her', 'supervisor', 1, '22774142', NULL, '4d8c459f9b26b464a85da120df32ccb3e2d9bf5567accd6a455f5fe8a112ad9a654f1b1b43b48afca1c25eedaa958fb2f7ea88bca39719ffc98be0a1338d1359'),
(178, 1, '30443194', '20191227143141', 64, NULL, 'her', NULL, 1, '22774142', NULL, 'd7d9274eeae25ee376e12aadd11b6b421544d31fc2695ebeb58ce2b5f5960f470b79be5f73cb1d095bbec2296d03b2b2ca0c85b6c3b61d3b62932262aef33d7c'),
(179, 1, '31443194', '20200210174121', 36, NULL, 'her', 'SUPERVISOR', NULL, NULL, NULL, '46b867e2b70eccd05551d97f022dd2b593982e8aeee550d8e8166121156a3560f76154e171185cf470fbd0e14998144a7fbbf4cab0208b1d3d1d88d22c1ff8b9'),
(180, 1, '31443194', '20200210174341', 38, NULL, 'her', 'SUPERVISOR', NULL, NULL, NULL, '3071b37c94c4ac656950eb9c2dfe3d673f4db8cecea9c629383896a8a814138bd3c1061e1b0a70881f874a4d49aca61e8ef031f7f36d0fe8da3e10b6ca0a2a0d'),
(181, 1, '5098271', '20200211095148', 36, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'd4d92b6afdc5666cbe8d2176d091383b53b9771fc802fa0bdede1ee31d7c38775b7e31096810155dd0efe4951f041c0b4f109eec0d1f5ca4e47998118dad4d0d');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE IF NOT EXISTS `genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_laboral_x_usuario`
--

CREATE TABLE IF NOT EXISTS `horario_laboral_x_usuario` (
  `id_usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `horario_ingreso` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `horario_salida` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `lunes` bit(1) NOT NULL,
  `martes` bit(1) NOT NULL,
  `miercoles` bit(1) NOT NULL,
  `jueves` bit(1) NOT NULL,
  `viernes` bit(1) NOT NULL,
  `sabado` bit(1) NOT NULL,
  `domingo` bit(1) NOT NULL,
  `cambio_dia` bit(1) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `horario_laboral_x_usuario`
--

INSERT INTO `horario_laboral_x_usuario` (`id_usuario`, `horario_ingreso`, `horario_salida`, `lunes`, `martes`, `miercoles`, `jueves`, `viernes`, `sabado`, `domingo`, `cambio_dia`) VALUES
('aassad', '20190904164300', '20190904024300', b'1', b'1', b'1', b'1', b'1', b'0', b'0', b'1'),
('asa', '20190904100000', '20190904150000', b'1', b'1', b'1', b'1', b'1', b'1', b'1', b'1'),
('her', '20190904080000', '20190904210000', b'1', b'1', b'1', b'1', b'1', b'1', b'0', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes_x_mora`
--

CREATE TABLE IF NOT EXISTS `interes_x_mora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan_credito` int(11) NOT NULL,
  `interes` int(11) NOT NULL,
  `cantidad_dias` int(11) NOT NULL,
  `recurrente` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `interes_x_mora`
--

INSERT INTO `interes_x_mora` (`id`, `id_plan_credito`, `interes`, `cantidad_dias`, `recurrente`) VALUES
(3, 4, 10, 60, b'1'),
(4, 4, 5, 30, b'1'),
(8, 4, 2, 1, b'0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes_x_mora_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `interes_x_mora_cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  `cantidad_dias_mora` int(11) NOT NULL,
  `interes_x_mora` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  `cantidad_dias_en_mora` int(11) NOT NULL,
  `recurrente` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `interes_x_mora_cuota_credito`
--

INSERT INTO `interes_x_mora_cuota_credito` (`id`, `fecha`, `id_cuota_credito`, `cantidad_dias_mora`, `interes_x_mora`, `id_plan_credito`, `cantidad_dias_en_mora`, `recurrente`) VALUES
(9, '20190802151839', 77, 30, 5, 4, 30, b'1'),
(10, '20190802162146', 77, 30, 5, 4, 30, b'1'),
(11, '20190902142639', 77, 30, 5, 4, 61, b'1'),
(12, '20190813143009', 77, 60, 10, 4, 61, b'0'),
(13, '20190813150207', 77, 30, 5, 4, 61, b'0'),
(14, '20191003000017', 77, 30, 5, 4, 92, b'1'),
(15, '20191004000017', 77, 1, 2, 4, 93, b'0'),
(16, '20191004000017', 85, 1, 2, 4, 1, b'0'),
(17, '20191012000016', 11, 30, 5, 4, 30, b'1'),
(18, '20191013000017', 11, 1, 2, 4, 31, b'0'),
(19, '20191013000017', 12, 1, 2, 4, 1, b'0'),
(20, '20191016000015', 78, 1, 2, 4, 1, b'0'),
(21, '20191103000017', 77, 30, 5, 4, 123, b'1'),
(22, '20191103000017', 85, 30, 5, 4, 31, b'1'),
(23, '20191103000018', 89, 1, 2, 4, 1, b'0'),
(24, '20191103000018', 86, 1, 2, 4, 1, b'0'),
(25, '20191112000017', 11, 30, 5, 4, 61, b'1'),
(26, '20191112000017', 12, 30, 5, 4, 31, b'1'),
(27, '20191115000016', 78, 30, 5, 4, 31, b'1');

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
('admin_sys', '1575895124', '186.111.160.120'),
('admin_sys', '1575895129', '186.111.160.120'),
('admin_sys', '1577451202', '181.9.163.130'),
('her', '1570656699', '190.136.129.17'),
('her', '1574342003', '186.111.162.207'),
('Her', '1575371279', '190.105.216.218'),
('her', '1577452396', '181.95.74.179'),
('her', '1581425295', '10.146.80.20'),
('her', '1581425301', '10.146.80.20'),
('her', '1581425304', '10.146.80.20'),
('supervisor', '1570050166', '190.225.246.244'),
('supervisor', '1571002961', '186.138.31.241'),
('supervisor', '1571003357', '186.138.31.241');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=2543 ;

--
-- Volcado de datos para la tabla `log_usuario`
--

INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(1134, 'supervisor', '20190916160506', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916160506, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1135, 'supervisor', '20190916160506', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916160506, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1136, 'supervisor', '20190916160527', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916160506, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1137, 'supervisor', '20190916160527', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1138, 'supervisor', '20190916161149', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916160506, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1139, 'supervisor', '20190916161149', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 3, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1140, 'supervisor', '20190916161221', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916160506, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1141, 'supervisor', '20190916161221', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 3, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1142, 'supervisor', '20190916161221', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916160506, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1143, 'supervisor', '20190916161221', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 3, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1144, 'supervisor', '20190916161221', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 3, id_credito = 30, fecha = 20190916160506, monto = 220000, usuario = supervisor, supervisor = , token = f6ca512da2c26d67dfc5d5cf11526b1bd618a59dfd678f3d36f8c7aec74a96eab2c01e43ebc9c20287828d09ba7dfa2cd57eedf6d9af1bdd340e6ca5e033a538 WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1145, 'supervisor', '20190916161221', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1146, 'supervisor', '20190916164202', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916164202, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1147, 'supervisor', '20190916164202', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916164202, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1148, 'supervisor', '20190916164202', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916164202, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1149, 'supervisor', '20190916164202', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1150, 'supervisor', '20190916164244', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916164202, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1151, 'supervisor', '20190916164244', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 4, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1152, 'supervisor', '20190916164244', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916164202, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1153, 'supervisor', '20190916164244', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 4, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1154, 'supervisor', '20190916164244', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916164202, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1155, 'supervisor', '20190916164244', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 4, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1156, 'supervisor', '20190916164244', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 4, id_credito = 30, fecha = 20190916164202, monto = 220000, usuario = supervisor, supervisor = , token = be3a4e968b01c7706c93b00fb5d4344bd0d098e1cc207038ebef87bd6e739fd439ea579293283a40335edc5496e9b6b5b77b670883c273567521eb88fdefe46c WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1157, 'supervisor', '20190916164244', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1158, 'supervisor', '20190916165156', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165156, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1159, 'supervisor', '20190916165156', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165156, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1160, 'supervisor', '20190916165156', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165156, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1161, 'supervisor', '20190916165156', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1162, 'supervisor', '20190916165635', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165156, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1163, 'supervisor', '20190916165635', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 5, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1164, 'supervisor', '20190916165635', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165156, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1165, 'supervisor', '20190916165635', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 5, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1166, 'supervisor', '20190916165635', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165156, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1167, 'supervisor', '20190916165635', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 5, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1168, 'supervisor', '20190916165635', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 5, id_credito = 30, fecha = 20190916165156, monto = 220000, usuario = supervisor, supervisor = , token = 4fc0be296a1c1e1a4fa1a35c7baee0dcccbaca0fd9489fc19dfdc5e812adeb57067c4e0b92ed602f69a8eca9432119717ec695a9a2a1e3bed44cc6187cc69bf0 WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1169, 'supervisor', '20190916165635', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1170, 'supervisor', '20190916165701', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165700, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1171, 'supervisor', '20190916165701', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165700, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1172, 'supervisor', '20190916165701', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190916165700, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1173, 'supervisor', '20190916165701', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1174, 'supervisor', '20190916180746', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-16 18:07:46'),
(1175, 'supervisor', '20190917112835', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-17 11:28:35'),
(1176, 'supervisor', '20190917113005', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-17 11:30:05'),
(1177, 'her', '20190917113008', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-17 11:30:08'),
(1178, 'her', '20190917113008', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-17 11:30:08'),
(1179, 'her', '20190917113151', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-17 11:31:51'),
(1180, 'supervisor', '20190917113158', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-17 11:31:58'),
(1181, 'supervisor', '20190917113222', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165700, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1182, 'supervisor', '20190917113222', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 6, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1183, 'supervisor', '20190917113223', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165700, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1184, 'supervisor', '20190917113223', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 6, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1185, 'supervisor', '20190917113223', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190916165700, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1186, 'supervisor', '20190917113223', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 6, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1187, 'supervisor', '20190917113223', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 6, id_credito = 30, fecha = 20190916165700, monto = 220000, usuario = supervisor, supervisor = , token = 8f8362318096324739f0c3108f08ea607599d34ff2c9957a6d2b242ccb4447f63b54c9b82529bd9dd4b7269bd8ea2ddcac21c658935fe8d79fbd68068d08478c WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1188, 'supervisor', '20190917113223', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1189, 'supervisor', '20190917113334', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917113334, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1190, 'supervisor', '20190917113334', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917113334, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1191, 'supervisor', '20190917113804', 95, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917113334, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1192, 'supervisor', '20190917113804', 96, 'ANTERIOR: finan_cli.pago_seleccion_cuotas_credito SET id_cuota_credito = , fecha = 20190917113334, monto = 73333, usuario = supervisor WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = 88'),
(1193, 'supervisor', '20190917113804', 95, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917113334, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1194, 'supervisor', '20190917113804', 96, 'ANTERIOR: finan_cli.pago_seleccion_cuotas_credito SET id_cuota_credito = , fecha = 20190917113334, monto = 73333, usuario = supervisor WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = 89'),
(1195, 'supervisor', '20190917120859', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917120859, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1196, 'supervisor', '20190917120859', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917120859, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1197, 'supervisor', '20190917120859', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917120859, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1198, 'supervisor', '20190917120859', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1199, 'supervisor', '20190917120911', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917120859, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1200, 'supervisor', '20190917120911', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 7, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1201, 'supervisor', '20190917120911', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917120859, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1202, 'supervisor', '20190917120911', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 7, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1203, 'supervisor', '20190917120911', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917120859, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1204, 'supervisor', '20190917120911', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 7, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1205, 'supervisor', '20190917120911', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 7, id_credito = 30, fecha = 20190917120859, monto = 220000, usuario = supervisor, supervisor = , token = a5fbbc0ed0b7f3e913e54ce282f3b9d70e1c287a4a0b5ef929ceebaae31d5d1fa58ac32bfc0401952d6b02999d3305e948d73dccbf3916503189cb8383f43bca WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1206, 'supervisor', '20190917120911', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1207, 'supervisor', '20190917121009', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121009, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1208, 'supervisor', '20190917121009', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121009, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1209, 'supervisor', '20190917121009', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121009, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1210, 'supervisor', '20190917121010', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1211, 'supervisor', '20190917121018', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121009, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1212, 'supervisor', '20190917121018', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 8, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1213, 'supervisor', '20190917121018', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121009, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1214, 'supervisor', '20190917121018', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 8, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1215, 'supervisor', '20190917121018', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121009, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1216, 'supervisor', '20190917121018', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 8, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1217, 'supervisor', '20190917121018', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 8, id_credito = 30, fecha = 20190917121009, monto = 220000, usuario = supervisor, supervisor = , token = 4a72bd1c17eabdf30a39f0649cba24b53c5b5411b5dd5b29133472049c62688ac03f66646d2f5350069b4560dfccce3c26c686fc631696372ed0f78164e7caf2 WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1218, 'supervisor', '20190917121018', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1219, 'supervisor', '20190917121149', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121149, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1220, 'supervisor', '20190917121149', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121149, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1221, 'supervisor', '20190917121149', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121149, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1222, 'supervisor', '20190917121149', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1223, 'supervisor', '20190917121157', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121149, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1224, 'supervisor', '20190917121157', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 9, id_cuota_credito = 88 WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 88'),
(1225, 'supervisor', '20190917121157', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121149, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1226, 'supervisor', '20190917121157', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 9, id_cuota_credito = 89 WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 89'),
(1227, 'supervisor', '20190917121157', 90, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121149, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1228, 'supervisor', '20190917121157', 92, 'ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = 9, id_cuota_credito = 90 WHERE id_cuota_credito = 90 -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = 90'),
(1229, 'supervisor', '20190917121157', 93, 'ANTERIOR: finan_cli.pago_total_credito SET id = 9, id_credito = 30, fecha = 20190917121149, monto = 220000, usuario = supervisor, supervisor = , token = ba1695e2f7e2a682d8b289db245daf94d10fe55343a3b4d00c9b3260b5d0e71f43156a5bfab25a97b464c5d1d2ad4464c363d8d52e5aa9d2c88069959eabc74f WHERE id_credito = 30 -- DELETE finan_cli.pago_total_credito WHERE id_credito = 30'),
(1230, 'supervisor', '20190917121157', 94, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1231, 'supervisor', '20190917121303', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121303, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1232, 'supervisor', '20190917121303', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917121303, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1233, 'supervisor', '20190917121404', 95, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121303, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1234, 'supervisor', '20190917121404', 96, 'ANTERIOR: finan_cli.pago_seleccion_cuotas_credito SET id_cuota_credito = , fecha = 20190917121303, monto = 73333, usuario = supervisor WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = 88'),
(1235, 'supervisor', '20190917121404', 95, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917121303, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1236, 'supervisor', '20190917121404', 96, 'ANTERIOR: finan_cli.pago_seleccion_cuotas_credito SET id_cuota_credito = , fecha = 20190917121303, monto = 73333, usuario = supervisor WHERE id_cuota_credito = 89 -- DELETE finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = 89'),
(1237, 'supervisor', '20190917125445', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917125445, monto_pago = 73330, estado = Pagada WHERE id = 88'),
(1238, 'supervisor', '20190917125739', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917125445, monto_pago = 73330, usuario_registro_pago = supervisor WHERE id = 88 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 88'),
(1239, 'supervisor', '20190917125739', 91, 'ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = 1, fecha = 20190917125445, monto = 73330, usuario = supervisor, supervisor = , token = 4f6966a36712234e02204355f91bdf88da179dace53c10b351ffd5747a3673fb85e9509106bb27151836699482e49810024827fa3ce772ba38b04dae4e7bd6ac WHERE id_cuota_credito = 88 -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = 88'),
(1240, 'supervisor', '20190917125824', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917125824, monto_pago = 73333, estado = Pagada WHERE id = 88'),
(1241, 'supervisor', '20190917125845', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917125845, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1242, 'supervisor', '20190917125856', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917125845, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1243, 'supervisor', '20190917130738', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917130738, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1244, 'supervisor', '20190917130747', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917130738, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1245, 'supervisor', '20190917130810', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917130810, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1246, 'supervisor', '20190917130817', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917130817, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1247, 'supervisor', '20190917130817', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1248, 'supervisor', '20190917130915', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917130817, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1249, 'supervisor', '20190917130915', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1250, 'supervisor', '20190917141403', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917141403, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1251, 'supervisor', '20190917141403', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1252, 'supervisor', '20190917141423', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917141403, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1253, 'supervisor', '20190917141423', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1254, 'supervisor', '20190917141601', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917141601, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1255, 'supervisor', '20190917141601', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1256, 'supervisor', '20190917141622', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917141601, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1257, 'supervisor', '20190917141622', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1258, 'supervisor', '20190917143318', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917143318, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1259, 'supervisor', '20190917143318', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1260, 'supervisor', '20190917143341', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917143318, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1261, 'supervisor', '20190917143341', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1262, 'supervisor', '20190917143437', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917143437, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1263, 'supervisor', '20190917143437', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1264, 'supervisor', '20190917143740', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917143437, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1265, 'supervisor', '20190917143740', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1266, 'supervisor', '20190917143926', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917143926, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1267, 'supervisor', '20190917143926', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1268, 'supervisor', '20190917143937', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917143926, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1269, 'supervisor', '20190917143937', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1270, 'supervisor', '20190917144032', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917144032, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1271, 'supervisor', '20190917144032', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1272, 'supervisor', '20190917144039', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917144032, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1273, 'supervisor', '20190917144039', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1274, 'supervisor', '20190917144048', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917130810, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1275, 'supervisor', '20190917144123', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917144123, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1276, 'supervisor', '20190917144127', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917144127, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1277, 'supervisor', '20190917144127', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1278, 'supervisor', '20190917144601', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917144127, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1279, 'supervisor', '20190917144601', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1280, 'supervisor', '20190917145620', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917145620, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(1281, 'supervisor', '20190917145620', 69, 'UPDATE finan_cli.credito SET estado = Pagada WHERE id = 30'),
(1282, 'supervisor', '20190917145630', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917145620, monto_pago = 73334, usuario_registro_pago = supervisor WHERE id = 90 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 90'),
(1283, 'supervisor', '20190917145630', 99, 'ANTERIOR: finan_cli.credito SET estado = Pagada WHERE id = 30 -- UPDATE finan_cli.credito SET estado = Pendiente WHERE id = 30'),
(1284, 'supervisor', '20190917145650', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917144123, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1285, 'supervisor', '20190917150103', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917150103, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1286, 'supervisor', '20190917150721', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917150103, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1287, 'supervisor', '20190917150840', 68, 'UPDATE finan_cli.cuota_credito SET fecha_pago = 20190917150840, monto_pago = 73333, estado = Pagada WHERE id = 89'),
(1288, 'supervisor', '20190917150901', 98, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190917150840, monto_pago = 73333, usuario_registro_pago = supervisor WHERE id = 89 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 89'),
(1289, 'supervisor', '20190918115627', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-18 11:56:27'),
(1290, 'supervisor', '20190918154838', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3513897854,4)'),
(1291, 'supervisor', '20190918154847', 12, 'ANTERIOR: id = 64, tipo_telefono = 1, numero = 3513897854, digitos_prefijo = 4  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897854, digitos_prefijo = 4 WHERE id =64'),
(1292, 'supervisor', '20190918154852', 11, 'DELETE finan_cli.telefono --> id: 64 - Tipo Telefono: 1 - Nro. Telefono: 3513897854'),
(1293, 'supervisor', '20190918154900', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3513456878,4)'),
(1294, 'supervisor', '20190918154911', 10, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (2,3514742046,3)'),
(1295, 'supervisor', '20190918155013', 6, 'ANTERIOR: id = 54, calle = ferr, nro_calle = 1212, provincia = CORDOBA, localidad = lklñk, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---  -- NUEVO: UPDATE finan_cli.domicilio SET calle = ferr, nro_calle = 1212, id_provincia = 1, localidad = lklñk, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- WHERE id =54'),
(1296, 'supervisor', '20190918155022', 6, 'ANTERIOR: id = 54, calle = ferr, nro_calle = 1212, provincia = CORDOBA, localidad = lklñk, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---  -- NUEVO: UPDATE finan_cli.domicilio SET calle = ferr, nro_calle = 1212, id_provincia = 1, localidad = lklñksss, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- WHERE id =54'),
(1297, 'supervisor', '20190918155035', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (aas,2,1,asas,---,NULL,---,---,---)'),
(1298, 'supervisor', '20190918155040', 5, 'DELETE finan_cli.domicilio --> id: 94 - Calle: aas - Nro. Calle: 2 - Provincia: CORDOBA - Localidad: asas - Departamento: --- - Piso: --- - Codigo Postal: --- - Entre Calle 1: --- - Entre Calle 2: ---'),
(1299, 'supervisor', '20190918174645', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-18 17:46:45'),
(1300, 'supervisor', '20190920172226', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-20 17:22:26'),
(1301, 'supervisor', '20190920172238', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-20 17:22:38'),
(1302, 'admin_sys', '20190920172245', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-20 17:22:45'),
(1303, 'admin_sys', '20190920173208', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-20 17:32:08'),
(1304, 'admin_sys', '20190924144509', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-24 14:45:09'),
(1305, 'admin_sys', '20190924175523', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-24 17:55:23'),
(1306, 'supervisor', '20190925105601', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-25 10:56:01'),
(1307, 'supervisor', '20190925153619', 46, 'El cliente: 2, fue deshabilitado el: 2019-09-25 15:36:19, por el usuario: supervisor!!'),
(1308, 'supervisor', '20190925153630', 47, 'El cliente: 2, fue habilitado el: 2019-09-25 15:36:30, por el usuario: supervisor!!'),
(1309, 'supervisor', '20190925153643', 46, 'El cliente: 2, fue deshabilitado el: 2019-09-25 15:36:43, por el usuario: supervisor!!'),
(1310, 'supervisor', '20190925153646', 47, 'El cliente: 2, fue habilitado el: 2019-09-25 15:36:46, por el usuario: supervisor!!'),
(1311, 'supervisor', '20190925180609', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-25 18:06:09'),
(1312, 'supervisor', '20190926111729', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-26 11:17:29'),
(1313, 'supervisor', '20190926151224', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,145025,13,45,210286,Pendiente,1,0)'),
(1314, 'supervisor', '20190926151224', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (38,20190926151224,1,32443194,supervisor,2)'),
(1315, 'supervisor', '20190926151224', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (38,1,20191028235959,210286,Pendiente)'),
(1316, 'supervisor', '20190926151224', 86, 'UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190926151224, monto_pago = 210286, usuario_registro_pago = supervisor WHERE id_credito = 38 AND numero_cuota = 1'),
(1317, 'supervisor', '20190926151300', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,136126,13,45,197383,Pendiente,0,18563)'),
(1318, 'supervisor', '20190926151300', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (39,20190926151300,1,32443194,supervisor,2)'),
(1319, 'supervisor', '20190926151300', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (39,1,20191028235959,197383,Pendiente)'),
(1320, 'supervisor', '20190926151659', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,30800,13,45,44660,Pendiente,0,4200)'),
(1321, 'supervisor', '20190926151659', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (40,20190926151659,1,32443194,supervisor,2)'),
(1322, 'supervisor', '20190926151659', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (40,1,20191028235959,44660,Pendiente)'),
(1323, 'supervisor', '20190926152355', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,30800,13,45,44660,Pendiente,0,4200)'),
(1324, 'supervisor', '20190926152355', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (41,20190926152355,1,32443194,supervisor,2)'),
(1325, 'supervisor', '20190926152355', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (41,1,20191028235959,44660,Pendiente)'),
(1326, 'supervisor', '20190926152811', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,30800,13,45,44660,Pendiente,0,4200)'),
(1327, 'supervisor', '20190926152811', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (42,20190926152811,1,32443194,supervisor,2)'),
(1328, 'supervisor', '20190926152812', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (42,1,20191028235959,44660,Pendiente)'),
(1329, 'supervisor', '20190926152903', 65, 'Reimpresión de Crédito: 42'),
(1330, 'supervisor', '20190926152929', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,31239.999999999996,13,45,45298,Pendiente,0,4260)'),
(1331, 'supervisor', '20190926152929', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (43,20190926152929,1,32443194,supervisor,2)'),
(1332, 'supervisor', '20190926152929', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (43,1,20191028235959,45298,Pendiente)'),
(1333, 'supervisor', '20190926152947', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,35600,13,45,51620,Pendiente,1,0)'),
(1334, 'supervisor', '20190926152947', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (44,20190926152947,1,32443194,supervisor,2)'),
(1335, 'supervisor', '20190926152947', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (44,1,20191028235959,51620,Pendiente)'),
(1336, 'supervisor', '20190926152947', 86, 'UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190926152947, monto_pago = 51620, usuario_registro_pago = supervisor WHERE id_credito = 44 AND numero_cuota = 1'),
(1337, 'supervisor', '20190926153323', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,31327.999999999996,13,45,45426,Pendiente,0,4272)'),
(1338, 'supervisor', '20190926153323', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (45,20190926153323,1,32443194,supervisor,2)'),
(1339, 'supervisor', '20190926153323', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (45,1,20191028235959,45426,Pendiente)'),
(1340, 'supervisor', '20190926153455', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,32560.000000000004,13,45,47212,Pendiente,0,4440)'),
(1341, 'supervisor', '20190926153455', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (46,20190926153455,1,32443194,supervisor,2)'),
(1342, 'supervisor', '20190926153455', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (46,1,20191028235959,47212,Pendiente)'),
(1343, 'supervisor', '20190926153556', 65, 'Reimpresión de Crédito: 46'),
(1344, 'supervisor', '20190926153725', 65, 'Reimpresión de Crédito: 46'),
(1345, 'supervisor', '20190926153834', 65, 'Reimpresión de Crédito: 46'),
(1346, 'supervisor', '20190927123320', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-27 12:33:20'),
(1347, 'supervisor', '20190927174354', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-27 17:43:54'),
(1348, 'supervisor', '20190930104130', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-30 10:41:30'),
(1349, 'supervisor', '20190930175738', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-30 17:57:38'),
(1350, 'supervisor', '20191001174629', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-01 17:46:29'),
(1351, 'supervisor', '20191001181340', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-01 18:13:40'),
(1352, 'supervisor', '20191002115602', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 11:56:02'),
(1353, 'supervisor', '20191002132430', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 13:24:30'),
(1354, 'supervisor', '20191002132430', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 13:24:30'),
(1355, 'supervisor', '20191002134709', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 13:47:09'),
(1356, 'supervisor', '20191002134709', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 13:47:09'),
(1357, 'supervisor', '20191002161751', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 16:17:51'),
(1358, 'supervisor', '20191002180250', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:02:50'),
(1359, 'supervisor', '20191002180313', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:03:13'),
(1360, 'supervisor', '20191002180412', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 18:04:12'),
(1361, 'supervisor', '20191002180452', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:04:52'),
(1362, 'her', '20191002180458', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:04:58'),
(1363, 'her', '20191002180458', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:04:58'),
(1364, 'supervisor', '20191002180501', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:05:01'),
(1365, 'supervisor', '20191002180510', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 18:05:10'),
(1366, 'supervisor', '20191002180802', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:08:02'),
(1367, 'supervisor', '20191002180802', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:08:02'),
(1368, 'supervisor', '20191002180805', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 18:08:05'),
(1369, 'supervisor', '20191002181005', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 18:10:05'),
(1370, 'supervisor', '20191002181020', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 18:10:20'),
(1371, 'admin_sys', '20191003000017', 85, 'ANTERIOR: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $313,44. WHERE id = 9 -- NUEVO: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $313,44. WHERE id = 9'),
(1372, 'admin_sys', '20191003000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1373, 'admin_sys', '20191003000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1374, 'admin_sys', '20191003000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1375, 'her', '20191003110110', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 11:01:10'),
(1376, 'her', '20191003110115', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 11:01:15'),
(1377, 'her', '20191003110115', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 11:01:15'),
(1378, 'her', '20191003110514', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-03 11:05:14'),
(1379, 'supervisor', '20191003110517', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 11:05:17');
INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(1380, 'supervisor', '20191003110653', 53, 'ANTERIOR: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 30443194, nombres = Fernando, apellidos = Budasi, cuil_cuit = 20304431945, fecha_nacimiento = 19880501000000, email = fer@gmail.com, observaciones = ---, monto_maximo_credito = 5000, id_perfil_credito = 1, id_genero = 1 WHERE id = 1 -- NUEVO: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 30443194, nombres = Fernando, apellidos = Budasi, cuil_cuit = 20304431945, fecha_nacimiento = 19880501000000, email = fer@gmail.com, observaciones = ---, monto_maximo_credito = 500000, id_perfil_credito = 1, id_genero = 1 WHERE id = 1'),
(1381, 'supervisor', '20191003110729', 61, 'INSERT INTO b9000224_prode.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,30000,4,10,33000,Pendiente,0,0)'),
(1382, 'supervisor', '20191003110729', 62, 'INSERT INTO b9000224_prode.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (47,20191003110729,1,30443194,supervisor,2)'),
(1383, 'supervisor', '20191003110729', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (47,1,20191104235959,11000,Pendiente)'),
(1384, 'supervisor', '20191003110729', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (47,2,20191204235959,11000,Pendiente)'),
(1385, 'supervisor', '20191003110729', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (47,3,20200103235959,11000,Pendiente)'),
(1386, 'admin_sys', '20191003120016', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 77 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 77'),
(1387, 'admin_sys', '20191003120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1388, 'admin_sys', '20191003120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1389, 'admin_sys', '20191003120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1390, 'supervisor', '20191003121403', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:14:03'),
(1391, 'supervisor', '20191003121423', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:14:23'),
(1392, 'supervisor', '20191003121432', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-03 12:14:32'),
(1393, 'supervisor', '20191003121446', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:14:46'),
(1394, 'supervisor', '20191003121553', 43, 'ANTERIOR: id = 63, tipo_telefono = 1, numero = 3513827932, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE b9000224_prode.telefono SET tipo_telefono = 1, numero = 3513827932, digitos_prefijo = 3, preferido = 1 WHERE id =63'),
(1395, 'her', '20191003122957', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:29:57'),
(1396, 'her', '20191003123007', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:30:07'),
(1397, 'her', '20191003123722', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (colon,6860,1,cba,cna,1,---,---,---,)'),
(1398, 'her', '20191003123722', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,0351156147241,4,)'),
(1399, 'her', '20191003123722', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,22774142,rabozzi,marcelo,20227741423,19720622000000,marcelo.rabozzi@gmail.com,20191003123722,Habilitado,1,nada,6000,1,1)'),
(1400, 'her', '20191003124224', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-03 12:42:24'),
(1401, 'supervisor', '20191003124226', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:42:26'),
(1402, 'supervisor', '20191003124226', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:42:26'),
(1403, 'supervisor', '20191003124304', 28, 'INSERT INTO b9000224_prode.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena,minimo_entrega) VALUES (PLAN Z NARANJA,s/d,6,0,5,5,0)'),
(1404, 'supervisor', '20191003124632', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 12:46:32'),
(1405, 'supervisor', '20191003124947', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,0351156727305,4,false)'),
(1406, 'admin_sys', '20191004000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1407, 'admin_sys', '20191004000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1408, 'admin_sys', '20191004000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1409, 'admin_sys', '20191004120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 77 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 77'),
(1410, 'admin_sys', '20191004120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1411, 'admin_sys', '20191004120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1412, 'admin_sys', '20191004120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1413, 'admin_sys', '20191004120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 85 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 85'),
(1414, 'admin_sys', '20191005000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1415, 'admin_sys', '20191005000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1416, 'admin_sys', '20191005000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1417, 'admin_sys', '20191005120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1418, 'admin_sys', '20191005120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1419, 'admin_sys', '20191005120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1420, 'admin_sys', '20191006000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1421, 'admin_sys', '20191006000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1422, 'admin_sys', '20191006000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1423, 'admin_sys', '20191006000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1424, 'admin_sys', '20191006000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1425, 'admin_sys', '20191006120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1426, 'admin_sys', '20191006120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1427, 'admin_sys', '20191006120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1428, 'admin_sys', '20191006120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1429, 'admin_sys', '20191006120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1430, 'admin_sys', '20191007000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1431, 'admin_sys', '20191007000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1432, 'admin_sys', '20191007000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1433, 'admin_sys', '20191007000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1434, 'admin_sys', '20191007000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1435, 'admin_sys', '20191007120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1436, 'admin_sys', '20191007120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1437, 'admin_sys', '20191007120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1438, 'admin_sys', '20191007120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1439, 'admin_sys', '20191007120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1440, 'admin_sys', '20191008000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1441, 'admin_sys', '20191008000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1442, 'admin_sys', '20191008000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1443, 'admin_sys', '20191008000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1444, 'admin_sys', '20191008000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1445, 'admin_sys', '20191008120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1446, 'admin_sys', '20191008120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1447, 'admin_sys', '20191008120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1448, 'admin_sys', '20191008120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1449, 'admin_sys', '20191008120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1450, 'admin_sys', '20191009000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1451, 'admin_sys', '20191009000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1452, 'admin_sys', '20191009000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1453, 'admin_sys', '20191009000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1454, 'admin_sys', '20191009000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1455, 'her', '20191009112442', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 11:24:42'),
(1456, 'admin_sys', '20191009120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1457, 'admin_sys', '20191009120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1458, 'admin_sys', '20191009120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1459, 'admin_sys', '20191009120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1460, 'admin_sys', '20191009120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1461, 'her', '20191009122040', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (gral campos,169,1,pilar,---,NULL,5976,san juan,san luis,)'),
(1462, 'her', '20191009122040', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,3572586036,4,)'),
(1463, 'her', '20191009122040', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,25000458,mariela sandra,negrete,23250004583,19760729000000,---,20191009122040,Habilitado,NULL,---,500000,1,2)'),
(1464, 'her', '20191009122419', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (gral paz,285,1,pilar,---,NULL,5976,roble,maipu,)'),
(1465, 'her', '20191009122419', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,3572586036,4,)'),
(1466, 'her', '20191009122419', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,38292091,rocio de los milagros,diaz,27382920916,19940322000000,---,20191009122419,Habilitado,28,---,300000,1,2)'),
(1467, 'her', '20191009122913', 61, 'INSERT INTO b9000224_prode.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (6,150000,5,20,180000,Pendiente,1,0)'),
(1468, 'her', '20191009122913', 62, 'INSERT INTO b9000224_prode.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (48,20191009122913,1,25000458,her,2,1,38292091)'),
(1469, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,1,20191108235959,30000,Pendiente)'),
(1470, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,2,20191209235959,30000,Pendiente)'),
(1471, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,3,20200108235959,30000,Pendiente)'),
(1472, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,4,20200207235959,30000,Pendiente)'),
(1473, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,5,20200309235959,30000,Pendiente)'),
(1474, 'her', '20191009122913', 63, 'INSERT INTO b9000224_prode.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (48,6,20200408235959,30000,Pendiente)'),
(1475, 'her', '20191009122913', 86, 'UPDATE b9000224_prode.cuota_credito SET estado = Pagada, fecha_pago = 20191009122913, monto_pago = 30000, usuario_registro_pago = her WHERE id_credito = 48 AND numero_cuota = 1'),
(1476, 'supervisor', '20191009135942', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 13:59:42'),
(1477, 'supervisor', '20191009135942', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 13:59:42'),
(1478, 'supervisor', '20191009140002', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 14:00:02'),
(1479, 'supervisor', '20191009140241', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-09 14:02:41'),
(1480, 'her', '20191009140247', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 14:02:47'),
(1481, 'her', '20191009141642', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 14:16:42'),
(1482, 'her', '20191009141710', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 14:17:10'),
(1483, 'her', '20191009142134', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009142134, monto_pago = 30000, estado = Pagada WHERE id = 111'),
(1484, 'her', '20191009142201', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009142201, monto_pago = 30000, estado = Pagada WHERE id = 112'),
(1485, 'her', '20191009142201', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009142201, monto_pago = 30000, estado = Pagada WHERE id = 113'),
(1486, 'her', '20191009142201', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009142201, monto_pago = 30000, estado = Pagada WHERE id = 114'),
(1487, 'her', '20191009142201', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009142201, monto_pago = 30000, estado = Pagada WHERE id = 115'),
(1488, 'her', '20191009142201', 69, 'UPDATE b9000224_prode.credito SET estado = Pagada WHERE id = 48'),
(1489, 'her', '20191009142355', 48, 'El cliente: 27, fue deshabilitado el: 2019-10-09 14:23:55, por el usuario: her!!'),
(1490, 'her', '20191009142408', 49, 'El cliente: 27, fue habilitado el: 2019-10-09 14:24:08, por el usuario: her!!'),
(1491, 'her', '20191009142417', 48, 'El cliente: 27, fue deshabilitado el: 2019-10-09 14:24:17, por el usuario: her!!'),
(1492, 'her', '20191009142724', 53, 'ANTERIOR: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 25000458, nombres = mariela sandra, apellidos = negrete, cuil_cuit = 23250004583, fecha_nacimiento = 19760729000000, email = ---, observaciones = ---, monto_maximo_credito = 500000, id_perfil_credito = 1, id_genero = 2 WHERE id = 28 -- NUEVO: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 25000458, nombres = mariela sandra, apellidos = negrete, cuil_cuit = 23250004583, fecha_nacimiento = 19760729000000, email = ---, observaciones = ---, monto_maximo_credito = 500000, id_perfil_credito = 1, id_genero = 2 WHERE id = 28'),
(1493, 'her', '20191009143701', 80, 'UPDATE b9000224_prode.cuota_credito SET estado = Condonada WHERE id = 10'),
(1494, 'supervisor', '20191009173744', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 17:37:44'),
(1495, 'supervisor', '20191009174521', 89, 'ANTERIOR: UPDATE b9000224_prode.credito SET estado = Pendiente WHERE id = 47 -- NUEVO: UPDATE b9000224_prode.credito SET estado = Cancelada WHERE id = 47'),
(1496, 'her', '20191009183143', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 18:31:43'),
(1497, 'her', '20191009183143', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 18:31:43'),
(1498, 'her', '20191009183154', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 18:31:54'),
(1499, 'her', '20191009183154', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 18:31:54'),
(1500, 'her', '20191009183203', 65, 'Reimpresión de Crédito: 46'),
(1501, 'her', '20191009185511', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191009185511, monto_pago = 47212, estado = Pagada WHERE id = 106'),
(1502, 'her', '20191009185511', 69, 'UPDATE b9000224_prode.credito SET estado = Pagada WHERE id = 46'),
(1503, 'her', '20191009190309', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-09 19:03:09'),
(1504, 'supervisor', '20191009190320', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 19:03:20'),
(1505, 'supervisor', '20191009190427', 98, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = Pagada, fecha_pago = 20191009185511, monto_pago = 47212, usuario_registro_pago = her WHERE id = 106 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 106'),
(1506, 'supervisor', '20191009190427', 99, 'ANTERIOR: b9000224_prode.credito SET estado = Pagada WHERE id = 46 -- UPDATE b9000224_prode.credito SET estado = Pendiente WHERE id = 46'),
(1507, 'supervisor', '20191009190957', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-09 19:09:57'),
(1508, 'her', '20191009191005', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 19:10:05'),
(1509, 'her', '20191009191343', 46, 'El cliente: 28, fue deshabilitado el: 2019-10-09 19:13:43, por el usuario: her!!'),
(1510, 'her', '20191009191540', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-09 19:15:40'),
(1511, 'supervisor', '20191009191549', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-09 19:15:49'),
(1512, 'supervisor', '20191009193042', 66, 'Generación PDF de Crédito: 46'),
(1513, 'supervisor', '20191009193233', 78, 'Reimpresión de Pago Total Deuda Crédito: 48'),
(1514, 'supervisor', '20191009193233', 79, 'Generación PDF de Pago Total Deuda Crédito: 48'),
(1515, 'supervisor', '20191009193311', 71, 'Generación PDF de Pago Cuota Crédito: 111'),
(1516, 'supervisor', '20191009195044', 65, 'Reimpresión de Crédito: 45'),
(1517, 'admin_sys', '20191010000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1518, 'admin_sys', '20191010000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1519, 'admin_sys', '20191010000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1520, 'admin_sys', '20191010000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1521, 'admin_sys', '20191010120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1522, 'admin_sys', '20191010120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1523, 'admin_sys', '20191010120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1524, 'admin_sys', '20191010120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1525, 'admin_sys', '20191011000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1526, 'admin_sys', '20191011000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1527, 'admin_sys', '20191011000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1528, 'admin_sys', '20191011000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1529, 'admin_sys', '20191011120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1530, 'admin_sys', '20191011120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1531, 'admin_sys', '20191011120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1532, 'admin_sys', '20191011120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1533, 'admin_sys', '20191012000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1534, 'admin_sys', '20191012000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1535, 'admin_sys', '20191012000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1536, 'admin_sys', '20191012120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1537, 'admin_sys', '20191012120016', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 11 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 11'),
(1538, 'admin_sys', '20191012120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1539, 'admin_sys', '20191012120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1540, 'admin_sys', '20191013000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1541, 'admin_sys', '20191013000017', 85, 'ANTERIOR: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 4, tiene una deuda pendiente de $616,00. WHERE id = 12 -- NUEVO: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 4, tiene una deuda pendiente de $627,73. WHERE id = 12'),
(1542, 'admin_sys', '20191013000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1543, 'admin_sys', '20191013000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1544, 'admin_sys', '20191013120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1545, 'admin_sys', '20191013120015', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 11 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 11'),
(1546, 'admin_sys', '20191013120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1547, 'admin_sys', '20191013120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1548, 'admin_sys', '20191013120015', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 12 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 12'),
(1549, 'supervisor', '20191013184142', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:41:42'),
(1550, 'supervisor', '20191013184142', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:41:42'),
(1551, 'supervisor', '20191013184157', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:41:57'),
(1552, 'supervisor', '20191013184245', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:42:45'),
(1553, 'supervisor', '20191013184509', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:45:09'),
(1554, 'supervisor', '20191013184556', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:45:56'),
(1555, 'supervisor', '20191013184556', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:45:56'),
(1556, 'supervisor', '20191013184921', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:49:21'),
(1557, 'supervisor', '20191013184921', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:49:21'),
(1558, 'supervisor', '20191013185007', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:50:07'),
(1559, 'supervisor', '20191013185007', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:50:07'),
(1560, 'supervisor', '20191013185045', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:50:45'),
(1561, 'supervisor', '20191013185237', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:52:37'),
(1562, 'supervisor', '20191013185410', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:54:10'),
(1563, 'supervisor', '20191013185410', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:54:10'),
(1564, 'supervisor', '20191013185656', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:56:56'),
(1565, 'supervisor', '20191013185656', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:56:56'),
(1566, 'supervisor', '20191013185901', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:59:01'),
(1567, 'supervisor', '20191013185951', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:59:51'),
(1568, 'supervisor', '20191013185951', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 18:59:51'),
(1569, 'supervisor', '20191013190108', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:01:08'),
(1570, 'supervisor', '20191013190108', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:01:08'),
(1571, 'supervisor', '20191013191021', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:10:21'),
(1572, 'supervisor', '20191013191021', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:10:21'),
(1573, 'supervisor', '20191013191224', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:12:24'),
(1574, 'supervisor', '20191013191224', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:12:24'),
(1575, 'supervisor', '20191013191357', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:13:57'),
(1576, 'supervisor', '20191013191357', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:13:57'),
(1577, 'supervisor', '20191013191456', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:14:56'),
(1578, 'supervisor', '20191013191456', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:14:56'),
(1579, 'supervisor', '20191013191545', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:15:45'),
(1580, 'supervisor', '20191013191545', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:15:45'),
(1581, 'supervisor', '20191013191620', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:16:20'),
(1582, 'supervisor', '20191013191620', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:16:20'),
(1583, 'supervisor', '20191013191814', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:18:14'),
(1584, 'supervisor', '20191013191814', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:18:14'),
(1585, 'supervisor', '20191013192109', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:21:09'),
(1586, 'supervisor', '20191013192109', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:21:09'),
(1587, 'supervisor', '20191013192337', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:23:37'),
(1588, 'supervisor', '20191013192337', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:23:37'),
(1589, 'supervisor', '20191013192434', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:24:34'),
(1590, 'supervisor', '20191013192434', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:24:34'),
(1591, 'supervisor', '20191013192450', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:24:50'),
(1592, 'supervisor', '20191013192451', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:24:51'),
(1593, 'supervisor', '20191013193012', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:12'),
(1594, 'supervisor', '20191013193012', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:12'),
(1595, 'supervisor', '20191013193026', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:26'),
(1596, 'supervisor', '20191013193026', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:26'),
(1597, 'supervisor', '20191013193044', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:44'),
(1598, 'supervisor', '20191013193044', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:30:44'),
(1599, 'supervisor', '20191013193348', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:33:48'),
(1600, 'supervisor', '20191013194010', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:40:10'),
(1601, 'supervisor', '20191013194010', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-13 19:40:10'),
(1602, 'supervisor', '20191013194320', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191013194320, monto_pago = 47212, estado = Pagada WHERE id = 106'),
(1603, 'supervisor', '20191013194320', 69, 'UPDATE b9000224_prode.credito SET estado = Pagada WHERE id = 46'),
(1604, 'supervisor', '20191013194335', 98, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = Pagada, fecha_pago = 20191013194320, monto_pago = 47212, usuario_registro_pago = supervisor WHERE id = 106 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente, fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = 106'),
(1605, 'supervisor', '20191013194335', 99, 'ANTERIOR: b9000224_prode.credito SET estado = Pagada WHERE id = 46 -- UPDATE b9000224_prode.credito SET estado = Pendiente WHERE id = 46'),
(1606, 'supervisor', '20191013194738', 68, 'UPDATE b9000224_prode.cuota_credito SET fecha_pago = 20191013194738, monto_pago = 47212, estado = Pagada WHERE id = 106'),
(1607, 'supervisor', '20191013194738', 69, 'UPDATE b9000224_prode.credito SET estado = Pagada WHERE id = 46'),
(1608, 'supervisor', '20191013200643', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-13 20:06:43'),
(1609, 'admin_sys', '20191014000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1610, 'admin_sys', '20191014000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1611, 'admin_sys', '20191014000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1612, 'supervisor', '20191014104319', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-14 10:43:19'),
(1613, 'supervisor', '20191014104322', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-14 10:43:22'),
(1614, 'supervisor', '20191014104335', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-14 10:43:35'),
(1615, 'supervisor', '20191014110130', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (sada,123,1,sadasd,---,NULL,---,---,---,)'),
(1616, 'supervisor', '20191014110130', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,35134123,3,)'),
(1617, 'supervisor', '20191014110130', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,43256789,sadasd,sadasd,212312123,19840110000000,asas@gmail.com,20191014110130,Habilitado,NULL,---,12222,1,1)'),
(1618, 'supervisor', '20191014111113', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (asdsa,3232,1,sada,---,NULL,---,---,---,)'),
(1619, 'supervisor', '20191014111113', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,345212345,4)'),
(1620, 'supervisor', '20191014111113', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,67854321,3)'),
(1621, 'supervisor', '20191014111113', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,67892222,asas,dfdfdf,324324324,19910619000000,sdsd@gmail.com,20191014111113,Habilitado,NULL,---,12211,1,1)'),
(1622, 'supervisor', '20191014111534', 39, 'INSERT INTO b9000224_prode.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (ghfghgf,435,1,fdgfdg,---,NULL,---,---,---,)'),
(1623, 'supervisor', '20191014111534', 42, 'INSERT INTO b9000224_prode.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,45612348,3)'),
(1624, 'supervisor', '20191014111534', 45, 'INSERT INTO b9000224_prode.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,9874561,ahghg,ghgh,4354354,19750128000000,ssdsd@hotmail.com,20191014111534,Habilitado,NULL,---,34322,1,1)'),
(1625, 'supervisor', '20191014111611', 53, 'ANTERIOR: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 9874561, nombres = ahghg, apellidos = ghgh, cuil_cuit = 4354354, fecha_nacimiento = 19750128000000, email = ssdsd@hotmail.com, observaciones = ---, monto_maximo_credito = 34322, id_perfil_credito = 1, id_genero = 1 WHERE id = 32 -- NUEVO: UPDATE b9000224_prode.cliente SET tipo_documento = 1, documento = 9874561, nombres = ahghg, apellidos = ghgh, cuil_cuit = 4354354, fecha_nacimiento = 19750128000000, email = ssdsd@hotmail.com, observaciones = ---, monto_maximo_credito = 34322, id_perfil_credito = 1, id_genero = 1 WHERE id = 32'),
(1626, 'supervisor', '20191014111927', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-14 11:19:27'),
(1627, 'admin_sys', '20191014120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1628, 'admin_sys', '20191014120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1629, 'admin_sys', '20191014120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1630, 'admin_sys', '20191015000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1631, 'admin_sys', '20191015000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1632, 'admin_sys', '20191015000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1633, 'admin_sys', '20191015000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1634, 'admin_sys', '20191015000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1635, 'admin_sys', '20191015120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1636, 'admin_sys', '20191015120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1637, 'admin_sys', '20191015120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1638, 'admin_sys', '20191015120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1639, 'admin_sys', '20191015120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1640, 'admin_sys', '20191016000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1641, 'admin_sys', '20191016000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1642, 'admin_sys', '20191016000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1643, 'admin_sys', '20191016000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1644, 'admin_sys', '20191016000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1645, 'admin_sys', '20191016120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1646, 'admin_sys', '20191016120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1647, 'admin_sys', '20191016120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1648, 'admin_sys', '20191016120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1649, 'admin_sys', '20191016120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1650, 'admin_sys', '20191016120018', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 78 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 78'),
(1651, 'admin_sys', '20191017000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1652, 'admin_sys', '20191017000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1653, 'admin_sys', '20191017000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1654, 'admin_sys', '20191017000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1655, 'admin_sys', '20191017000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1656, 'admin_sys', '20191017120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1657, 'admin_sys', '20191017120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1658, 'admin_sys', '20191017120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1659, 'admin_sys', '20191017120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1660, 'admin_sys', '20191017120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1661, 'her', '20191017124658', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-17 12:46:58'),
(1662, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1663, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1664, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1665, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1666, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1667, 'admin_sys', '20191018000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1668, 'admin_sys', '20191018120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1669, 'admin_sys', '20191018120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1670, 'admin_sys', '20191018120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1671, 'admin_sys', '20191018120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1672, 'admin_sys', '20191018120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1673, 'admin_sys', '20191018120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1674, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1675, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1676, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1677, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1678, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1679, 'admin_sys', '20191019000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1680, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1681, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1682, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1683, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1684, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1685, 'admin_sys', '20191019120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1686, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1687, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1688, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1689, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1690, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1691, 'admin_sys', '20191020000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1692, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1693, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1694, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1695, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1696, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1697, 'admin_sys', '20191020120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1698, 'admin_sys', '20191021000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1699, 'admin_sys', '20191021000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1700, 'admin_sys', '20191021000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1701, 'admin_sys', '20191021000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1702, 'admin_sys', '20191021000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1703, 'admin_sys', '20191021000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1704, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1705, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1706, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1707, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1708, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1709, 'admin_sys', '20191021120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1710, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1711, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1712, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1713, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1714, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1715, 'admin_sys', '20191022000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1716, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1717, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1718, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1719, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1720, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1721, 'admin_sys', '20191022120012', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1722, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1723, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1724, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1725, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!');
INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(1726, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1727, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1728, 'admin_sys', '20191023000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1729, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1730, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1731, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1732, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1733, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1734, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1735, 'admin_sys', '20191023120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1736, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1737, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1738, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1739, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1740, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1741, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1742, 'admin_sys', '20191024000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1743, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1744, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1745, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1746, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1747, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1748, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1749, 'admin_sys', '20191024120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1750, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1751, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1752, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1753, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1754, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1755, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1756, 'admin_sys', '20191025000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1757, 'admin_sys', '20191025120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1758, 'admin_sys', '20191025120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1759, 'admin_sys', '20191025120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1760, 'admin_sys', '20191025120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1761, 'admin_sys', '20191025120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1762, 'admin_sys', '20191025120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1763, 'admin_sys', '20191025120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1764, 'supervisor', '20191025161335', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:13:35'),
(1765, 'supervisor', '20191025161401', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:14:01'),
(1766, 'supervisor', '20191025161526', 100, 'INSERT INTO b9000224_prode.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191025161526,0,38011e0c33199643bf847ab1159a29bf2b5f6c386d566fefa806272a9efbab5f7642eefe412081937875eea3eb4d655c9843fb1695b1d7e659a7e0ed2e1cb822,15)'),
(1767, 'supervisor', '20191025161553', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 16:15:53'),
(1768, 'her', '20191025161559', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:15:59'),
(1769, 'her', '20191025161631', 43, 'ANTERIOR: id = 67, tipo_telefono = 1, numero = 351156147241, digitos_prefijo = 4, preferido = 1  -- NUEVO: UPDATE b9000224_prode.telefono SET tipo_telefono = 1, numero = 351156147241, digitos_prefijo = 4, preferido = 1 WHERE id =67'),
(1770, 'her', '20191025161702', 43, 'ANTERIOR: id = 68, tipo_telefono = 1, numero = 351156727305, digitos_prefijo = 4, preferido = 0  -- NUEVO: UPDATE b9000224_prode.telefono SET tipo_telefono = 1, numero = 351156727304, digitos_prefijo = 4, preferido = 1 WHERE id =68'),
(1771, 'her', '20191025161715', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 16:17:15'),
(1772, 'supervisor', '20191025161751', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:17:51'),
(1773, 'supervisor', '20191025161757', 100, 'INSERT INTO b9000224_prode.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191025161757,0,ad6c905b19b76d6a6804e84f1116fd509f90dc74e195d0a18dc6bd38f1ca3c541973275c4d742dd8cbc514a3e9ee94a78fbeec19bb0e017840d4ba3b1d57e989,15)'),
(1774, 'supervisor', '20191025161807', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 16:18:07'),
(1775, 'her', '20191025161811', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:18:11'),
(1776, 'her', '20191025161811', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 16:18:11'),
(1777, 'her', '20191025161848', 43, 'ANTERIOR: id = 68, tipo_telefono = 1, numero = 351156727304, digitos_prefijo = 4, preferido = 1  -- NUEVO: UPDATE b9000224_prode.telefono SET tipo_telefono = 1, numero = 351156727305, digitos_prefijo = 4, preferido = 1 WHERE id =68'),
(1778, 'her', '20191025161854', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 16:18:54'),
(1779, 'supervisor', '20191025201958', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 20:19:58'),
(1780, 'supervisor', '20191025202004', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 20:20:04'),
(1781, 'supervisor', '20191025202004', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 20:20:04'),
(1782, 'supervisor', '20191025202022', 100, 'INSERT INTO b9000224_prode.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191025202022,0,a67661c67bdb9d13196e774410c97d104c71c7b76245ca82b8243b8e1db874aa97ff2d8bef815b1cde4a5ad9fdd268210438fdf4b316f81aa26df9a093209c38,15)'),
(1783, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1784, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1785, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1786, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1787, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1788, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1789, 'admin_sys', '20191026000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1790, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1791, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1792, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1793, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1794, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1795, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1796, 'admin_sys', '20191026120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1797, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1798, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1799, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1800, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1801, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1802, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1803, 'admin_sys', '20191027000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1804, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1805, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1806, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1807, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1808, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1809, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1810, 'admin_sys', '20191027120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1811, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1812, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1813, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1814, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1815, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1816, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1817, 'admin_sys', '20191028120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1818, 'supervisor', '20191028124609', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-28 12:46:09'),
(1819, 'supervisor', '20191028124620', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-28 12:46:20'),
(1820, 'supervisor', '20191028124620', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-28 12:46:20'),
(1821, 'supervisor', '20191028124629', 100, 'INSERT INTO b9000224_prode.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191028124629,0,235cbbaaeb0803b80cbaffc77554958f45ba5f4a34764bd6b04b41fc91fad358e4477fb57b738111367e082e71fd6e34234fe3feec8cca05f1bfff46b3585c4c,15)'),
(1822, 'supervisor', '20191028124643', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-28 12:46:43'),
(1823, 'admin_sys', '20191029000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1824, 'admin_sys', '20191029000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1825, 'admin_sys', '20191029000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1826, 'admin_sys', '20191029000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1827, 'admin_sys', '20191029000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1828, 'admin_sys', '20191029000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1829, 'admin_sys', '20191029000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1830, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1831, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1832, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1833, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1834, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1835, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1836, 'admin_sys', '20191029120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1837, 'admin_sys', '20191030000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1838, 'admin_sys', '20191030000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1839, 'admin_sys', '20191030000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1840, 'admin_sys', '20191030000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1841, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1842, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1843, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1844, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1845, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1846, 'admin_sys', '20191030000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1847, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1848, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1849, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1850, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1851, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1852, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1853, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1854, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1855, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1856, 'admin_sys', '20191030120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1857, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1858, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1859, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1860, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1861, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1862, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1863, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1864, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1865, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1866, 'admin_sys', '20191031000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1867, 'admin_sys', '20191031120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1868, 'admin_sys', '20191031120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1869, 'admin_sys', '20191031120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1870, 'admin_sys', '20191031120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1871, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1872, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1873, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1874, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1875, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1876, 'admin_sys', '20191031120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1877, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1878, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1879, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1880, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1881, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1882, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1883, 'admin_sys', '20191101000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1884, 'admin_sys', '20191101000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1885, 'admin_sys', '20191101000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1886, 'admin_sys', '20191101000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1887, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1888, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1889, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1890, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1891, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1892, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1893, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1894, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1895, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1896, 'admin_sys', '20191101120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1897, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1898, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1899, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1900, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1901, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1902, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1903, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1904, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1905, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1906, 'admin_sys', '20191102000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1907, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1908, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1909, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1910, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1911, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1912, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1913, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1914, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1915, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1916, 'admin_sys', '20191102120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1917, 'admin_sys', '20191103000017', 85, 'ANTERIOR: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: le recuerda que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $318,08. WHERE id = 15 -- NUEVO: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $329,69. WHERE id = 15'),
(1918, 'admin_sys', '20191103000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1919, 'admin_sys', '20191103000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1920, 'admin_sys', '20191103000017', 85, 'ANTERIOR: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: le recuerda que la cuota número: 1 del credito: 29, tiene una deuda pendiente de $561,00. WHERE id = 16 -- NUEVO: UPDATE b9000224_prode.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 1 del credito: 29, tiene una deuda pendiente de $588,50. WHERE id = 16'),
(1921, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1922, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1923, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1924, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1925, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1926, 'admin_sys', '20191103000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1927, 'admin_sys', '20191103120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 77 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 77'),
(1928, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1929, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1930, 'admin_sys', '20191103120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 85 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 85'),
(1931, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1932, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1933, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1934, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1935, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1936, 'admin_sys', '20191103120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1937, 'admin_sys', '20191103120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 86 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 86'),
(1938, 'admin_sys', '20191103120017', 83, 'ANTERIOR: UPDATE b9000224_prode.cuota_credito SET estado = En Mora WHERE id = 89 -- NUEVO: UPDATE b9000224_prode.cuota_credito SET estado = Pendiente WHERE id = 89'),
(1939, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1940, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1941, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1942, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1943, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1944, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1945, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1946, 'admin_sys', '20191104000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1947, 'admin_sys', '20191104120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1948, 'admin_sys', '20191104120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1949, 'admin_sys', '20191104120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1950, 'admin_sys', '20191104120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1951, 'admin_sys', '20191104120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1952, 'admin_sys', '20191104120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1953, 'admin_sys', '20191104120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1954, 'admin_sys', '20191104120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1955, 'admin_sys', '20191105000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1956, 'admin_sys', '20191105000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1957, 'admin_sys', '20191105000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1958, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1959, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1960, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1961, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1962, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1963, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1964, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1965, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(1966, 'admin_sys', '20191105000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(1967, 'admin_sys', '20191105120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1968, 'admin_sys', '20191105120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1969, 'admin_sys', '20191105120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1970, 'admin_sys', '20191105120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1971, 'admin_sys', '20191105120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1972, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1973, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1974, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1975, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1976, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1977, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(1978, 'admin_sys', '20191105120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(1979, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1980, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1981, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1982, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1983, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1984, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1985, 'admin_sys', '20191106000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1986, 'admin_sys', '20191106000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1987, 'admin_sys', '20191106000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(1988, 'admin_sys', '20191106000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(1989, 'admin_sys', '20191106000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(1990, 'admin_sys', '20191106000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(1991, 'admin_sys', '20191106120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1992, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(1993, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(1994, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(1995, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(1996, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(1997, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(1998, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(1999, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2000, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2001, 'admin_sys', '20191106120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2002, 'admin_sys', '20191106120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2003, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2004, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2005, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2006, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2007, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2008, 'admin_sys', '20191107000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2009, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2010, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2011, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2012, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2013, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2014, 'admin_sys', '20191107000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2015, 'admin_sys', '20191107120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2016, 'admin_sys', '20191107120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2017, 'admin_sys', '20191107120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2018, 'admin_sys', '20191107120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2019, 'admin_sys', '20191107120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2020, 'admin_sys', '20191107120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2021, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2022, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2023, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2024, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2025, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2026, 'admin_sys', '20191107120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2027, 'her', '20191107170152', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-07 17:01:52'),
(2028, 'her', '20191107170247', 6, 'ANTERIOR: id = 54, calle = ferr, nro_calle = 1212, provincia = CORDOBA, localidad = lklñksss, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---  -- NUEVO: UPDATE b9000224_fincli.domicilio SET calle = ferr, nro_calle = 1212, id_provincia = 1, localidad = lklñksss, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- WHERE id =54'),
(2029, 'her', '20191107170257', 6, 'ANTERIOR: id = 54, calle = ferr, nro_calle = 1212, provincia = CORDOBA, localidad = lklñksss, departamento = ---, piso = 1, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---  -- NUEVO: UPDATE b9000224_fincli.domicilio SET calle = ferr, nro_calle = 1212, id_provincia = 1, localidad = lklñksss, departamento = ---, piso = 2, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- WHERE id =54'),
(2030, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2031, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2032, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2033, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2034, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2035, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2036, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2037, 'admin_sys', '20191108000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2038, 'admin_sys', '20191108000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2039, 'admin_sys', '20191108000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2040, 'admin_sys', '20191108000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2041, 'admin_sys', '20191108000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2042, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2043, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2044, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2045, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2046, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2047, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2048, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2049, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2050, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2051, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2052, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2053, 'admin_sys', '20191108120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2054, 'admin_sys', '20191109000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2055, 'admin_sys', '20191109000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2056, 'admin_sys', '20191109000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2057, 'admin_sys', '20191109000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2058, 'admin_sys', '20191109000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2059, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2060, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2061, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2062, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2063, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2064, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2065, 'admin_sys', '20191109000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2066, 'admin_sys', '20191109120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2067, 'admin_sys', '20191109120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2068, 'admin_sys', '20191109120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2069, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2070, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2071, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2072, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2073, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!');
INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(2074, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2075, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2076, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2077, 'admin_sys', '20191109120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2078, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2079, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2080, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2081, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2082, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2083, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2084, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2085, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2086, 'admin_sys', '20191110000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2087, 'admin_sys', '20191110000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2088, 'admin_sys', '20191110000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2089, 'admin_sys', '20191110000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2090, 'admin_sys', '20191110120015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2091, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2092, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2093, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2094, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2095, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2096, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2097, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2098, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2099, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2100, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2101, 'admin_sys', '20191110120016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2102, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2103, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2104, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2105, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2106, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2107, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2108, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2109, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2110, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2111, 'admin_sys', '20191111000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2112, 'admin_sys', '20191111000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2113, 'admin_sys', '20191111000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2114, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2115, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2116, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2117, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2118, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2119, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2120, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2121, 'admin_sys', '20191111120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2122, 'admin_sys', '20191111120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2123, 'admin_sys', '20191111120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2124, 'admin_sys', '20191111120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2125, 'admin_sys', '20191111120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2126, 'admin_sys', '20191112000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2127, 'admin_sys', '20191112000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2128, 'admin_sys', '20191112000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2129, 'admin_sys', '20191112000017', 85, 'ANTERIOR: UPDATE b9000224_fincli.aviso_x_mora SET mensaje = PRUEBA: le recuerda que la cuota número: 3 del credito: 4, tiene una deuda pendiente de $598,39. WHERE id = 20 -- NUEVO: UPDATE b9000224_fincli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 3 del credito: 4, tiene una deuda pendiente de $627,72. WHERE id = 20'),
(2130, 'admin_sys', '20191112000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2131, 'admin_sys', '20191112000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2132, 'admin_sys', '20191112000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2133, 'admin_sys', '20191112000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2134, 'admin_sys', '20191112000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2135, 'admin_sys', '20191112000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2136, 'admin_sys', '20191112000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2137, 'admin_sys', '20191112120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2138, 'admin_sys', '20191112120017', 83, 'ANTERIOR: UPDATE b9000224_fincli.cuota_credito SET estado = En Mora WHERE id = 11 -- NUEVO: UPDATE b9000224_fincli.cuota_credito SET estado = Pendiente WHERE id = 11'),
(2139, 'admin_sys', '20191112120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2140, 'admin_sys', '20191112120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2141, 'admin_sys', '20191112120017', 83, 'ANTERIOR: UPDATE b9000224_fincli.cuota_credito SET estado = En Mora WHERE id = 12 -- NUEVO: UPDATE b9000224_fincli.cuota_credito SET estado = Pendiente WHERE id = 12'),
(2142, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2143, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2144, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2145, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2146, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2147, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2148, 'admin_sys', '20191112120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2149, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2150, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2151, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2152, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2153, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2154, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2155, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2156, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2157, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2158, 'admin_sys', '20191113000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2159, 'admin_sys', '20191113120013', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2160, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2161, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2162, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2163, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2164, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2165, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2166, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2167, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2168, 'admin_sys', '20191113120014', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2169, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2170, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2171, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2172, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2173, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2174, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2175, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2176, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2177, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2178, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2179, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2180, 'admin_sys', '20191114000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2181, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2182, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2183, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2184, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2185, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2186, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2187, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2188, 'admin_sys', '20191114120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2189, 'admin_sys', '20191114120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2190, 'admin_sys', '20191114120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2191, 'admin_sys', '20191114120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2192, 'admin_sys', '20191114120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2193, 'admin_sys', '20191115000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2194, 'admin_sys', '20191115000015', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2195, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2196, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2197, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2198, 'admin_sys', '20191115000016', 85, 'ANTERIOR: UPDATE b9000224_fincli.aviso_x_mora SET mensaje = PRUEBA: le recuerda que la cuota número: 3 del credito: 27, tiene una deuda pendiente de $236,82. WHERE id = 22 -- NUEVO: UPDATE b9000224_fincli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 3 del credito: 27, tiene una deuda pendiente de $248,43. WHERE id = 22'),
(2199, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2200, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2201, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2202, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2203, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2204, 'admin_sys', '20191115000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2205, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2206, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2207, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2208, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2209, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2210, 'admin_sys', '20191115120018', 83, 'ANTERIOR: UPDATE b9000224_fincli.cuota_credito SET estado = En Mora WHERE id = 78 -- NUEVO: UPDATE b9000224_fincli.cuota_credito SET estado = Pendiente WHERE id = 78'),
(2211, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2212, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2213, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2214, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2215, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2216, 'admin_sys', '20191115120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2217, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2218, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2219, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2220, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2221, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2222, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2223, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2224, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2225, 'admin_sys', '20191116000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2226, 'admin_sys', '20191116000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2227, 'admin_sys', '20191116000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2228, 'admin_sys', '20191116120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2229, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2230, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2231, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2232, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2233, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2234, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2235, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2236, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2237, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2238, 'admin_sys', '20191116120019', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2239, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2240, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2241, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2242, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2243, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2244, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2245, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2246, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2247, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2248, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2249, 'admin_sys', '20191117000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2250, 'admin_sys', '20191117000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2251, 'admin_sys', '20191117120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2252, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2253, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2254, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2255, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2256, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2257, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2258, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2259, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2260, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2261, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2262, 'admin_sys', '20191117120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2263, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2264, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2265, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2266, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2267, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2268, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2269, 'admin_sys', '20191118000016', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2270, 'admin_sys', '20191118000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2271, 'admin_sys', '20191118000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2272, 'admin_sys', '20191118000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2273, 'admin_sys', '20191118000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2274, 'admin_sys', '20191118000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2275, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2276, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2277, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2278, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2279, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2280, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2281, 'admin_sys', '20191118120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2282, 'admin_sys', '20191118120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2283, 'admin_sys', '20191118120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2284, 'admin_sys', '20191118120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2285, 'admin_sys', '20191118120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2286, 'admin_sys', '20191118120018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2287, 'admin_sys', '20191119000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2288, 'admin_sys', '20191119000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2289, 'admin_sys', '20191119000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2290, 'admin_sys', '20191119000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2291, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2292, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2293, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2294, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2295, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2296, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2297, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2298, 'admin_sys', '20191119000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2299, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2300, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2301, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2302, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2303, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2304, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2305, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2306, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2307, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2308, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2309, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2310, 'admin_sys', '20191119120017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2311, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(2312, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:11, con 1 dias de mora!!'),
(2313, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:79, con 1 dias de mora!!'),
(2314, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:85, con 1 dias de mora!!'),
(2315, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:12, con 1 dias de mora!!'),
(2316, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:78, con 1 dias de mora!!'),
(2317, 'admin_sys', '20191120000017', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:80, con 1 dias de mora!!'),
(2318, 'admin_sys', '20191120000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:102, con 1 dias de mora!!'),
(2319, 'admin_sys', '20191120000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:105, con 1 dias de mora!!'),
(2320, 'admin_sys', '20191120000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:103, con 1 dias de mora!!'),
(2321, 'admin_sys', '20191120000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:89, con 1 dias de mora!!'),
(2322, 'admin_sys', '20191120000018', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:86, con 1 dias de mora!!'),
(2323, 'her', '20191121101017', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:10:17'),
(2324, 'her', '20191121101017', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:10:17'),
(2325, 'her', '20191121101024', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:10:24'),
(2326, 'her', '20191121101030', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:10:30'),
(2327, 'her', '20191121101030', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:10:30'),
(2328, 'her', '20191121101327', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:13:27'),
(2329, 'her', '20191121101327', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:13:27'),
(2330, 'her', '20191121101347', 2, 'Cierre de Sesion en Fecha y Hora: 2019-11-21 10:13:47'),
(2331, 'her', '20191121101431', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:14:31'),
(2332, 'her', '20191121101431', 1, 'Inicio de Sesion en Fecha y Hora: 2019-11-21 10:14:31'),
(2333, 'her', '20191203081005', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-03 08:10:05'),
(2334, 'her', '20191203082230', 39, 'INSERT INTO c1651231_fincli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (Cura Brochero,447,1,Laguna Larga,---,NULL,5974,---,---,)'),
(2335, 'her', '20191203082230', 42, 'INSERT INTO c1651231_fincli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3572583298,4)'),
(2336, 'her', '20191203082230', 42, 'INSERT INTO c1651231_fincli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (2,3572481357,4)'),
(2337, 'her', '20191203082230', 45, 'INSERT INTO c1651231_fincli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,28934582,Gisela,Sarale,27289345820,19811210000000,---,20191203082230,Habilitado,NULL,\n,100,1,2)'),
(2338, 'her', '20191203082535', 53, 'ANTERIOR: UPDATE c1651231_fincli.cliente SET tipo_documento = 1, documento = 28934582, nombres = Gisela, apellidos = Sarale, cuil_cuit = 27289345820, fecha_nacimiento = 19811210000000, email = ---, observaciones = \n, monto_maximo_credito = 100, id_perfil_credito = 1, id_genero = 2 WHERE id = 33 -- NUEVO: UPDATE c1651231_fincli.cliente SET tipo_documento = 1, documento = 28934582, nombres = Gisela, apellidos = Sarale, cuil_cuit = 27289345820, fecha_nacimiento = 19811210000000, email = ---, observaciones = \n, monto_maximo_credito = 500000, id_perfil_credito = 1, id_genero = 2 WHERE id = 33'),
(2339, 'her', '20191203083840', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (6,30000,5,20,36000,Pendiente,0,NaN)'),
(2340, 'her', '20191203083840', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (49,20191203083840,1,28934582,her,2)'),
(2341, 'her', '20191203083840', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,1,20200102235959,6000,Pendiente)'),
(2342, 'her', '20191203083841', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,2,20200203235959,6000,Pendiente)'),
(2343, 'her', '20191203083841', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,3,20200304235959,6000,Pendiente)'),
(2344, 'her', '20191203083841', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,4,20200403235959,6000,Pendiente)'),
(2345, 'her', '20191203083841', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,5,20200504235959,6000,Pendiente)'),
(2346, 'her', '20191203083841', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (49,6,20200603235959,6000,Pendiente)'),
(2347, 'her', '20191203153900', 15, 'La sesión expiró: 2019-12-03 15:39:00'),
(2348, 'her', '20191203153908', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-03 15:39:08'),
(2349, 'her', '20191203154424', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191203154424, monto_pago = 74800, estado = Pagada WHERE id = 89'),
(2350, 'her', '20191204211210', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-04 21:12:10'),
(2351, 'her', '20191204211841', 39, 'INSERT INTO c1651231_fincli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (corrientes,1363,1,pilar,rio segund,3,5972,mitre,25 de mayo,)'),
(2352, 'her', '20191204211841', 42, 'INSERT INTO c1651231_fincli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3572546889,4)'),
(2353, 'her', '20191204211841', 42, 'INSERT INTO c1651231_fincli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3572604735,4)'),
(2354, 'her', '20191204211841', 45, 'INSERT INTO c1651231_fincli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,39173040,julieta,sarale,23544412589,19951108000000,saralejulieta@gmail.com,20191204211841,Habilitado,NULL,---,300000,2,2)'),
(2355, 'her', '20191204223038', 49, 'El cliente: 27, fue habilitado el: 2019-12-04 22:30:38, por el usuario: her!!'),
(2356, 'her', '20191204223045', 46, 'El cliente: 33, fue deshabilitado el: 2019-12-04 22:30:45, por el usuario: her!!'),
(2357, 'supervisor', '20191209092939', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-09 09:29:39'),
(2358, 'supervisor', '20191209092956', 65, 'Reimpresión de Crédito: 49'),
(2359, 'supervisor', '20191209093033', 65, 'Reimpresión de Crédito: 49'),
(2360, 'supervisor', '20191209093529', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-09 09:35:29'),
(2361, 'supervisor', '20191209093917', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-09 09:39:17'),
(2362, 'supervisor', '20191209093937', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE c1651231_fincli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(2363, 'supervisor', '20191209093937', 88, 'ANTERIOR: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904013000, horario_salida = 20190904030000, lunes = 0, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her -- NUEVO: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904013000, horario_salida = 20190904030000, lunes = 1, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her'),
(2364, 'supervisor', '20191209093943', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-09 09:39:43'),
(2365, 'her', '20191209093957', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-09 09:39:57'),
(2366, 'her', '20191209094032', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-09 09:40:32'),
(2367, 'her', '20191209095059', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191209095059, monto_pago = 45426, estado = Pagada WHERE id = 105'),
(2368, 'her', '20191209095059', 69, 'UPDATE c1651231_fincli.credito SET estado = Pagada WHERE id = 45'),
(2369, 'her', '20191209095238', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191209095238, monto_pago = 73334, estado = Pagada WHERE id = 90'),
(2370, 'her', '20191209095238', 69, 'UPDATE c1651231_fincli.credito SET estado = Pagada WHERE id = 30'),
(2371, 'her', '20191209095422', 47, 'El cliente: 28, fue habilitado el: 2019-12-09 09:54:22, por el usuario: her!!'),
(2372, 'her', '20191209095501', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,250000,4,10,275000,Pendiente,0,0)'),
(2373, 'her', '20191209095501', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (50,20191209095501,1,25000458,her,2)'),
(2374, 'her', '20191209095501', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (50,1,20200108235959,91667,Pendiente)'),
(2375, 'her', '20191209095501', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (50,2,20200207235959,91667,Pendiente)'),
(2376, 'her', '20191209095501', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (50,3,20200309235959,91666,Pendiente)'),
(2377, 'her', '20191209095728', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191209095728, monto_pago = 91667, estado = Pagada WHERE id = 122'),
(2378, 'supervisor', '20191210095433', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-10 09:54:33'),
(2379, 'supervisor', '20191210095517', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE c1651231_fincli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(2380, 'supervisor', '20191210095517', 88, 'ANTERIOR: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904013000, horario_salida = 20190904030000, lunes = 1, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her -- NUEVO: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904080000, horario_salida = 20190904210000, lunes = 1, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her'),
(2381, 'her', '20191212105035', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-12 10:50:35'),
(2382, 'her', '20191212105035', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-12 10:50:35'),
(2383, 'supervisor', '20191216122019', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-16 12:20:19'),
(2384, 'supervisor', '20191216122040', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE c1651231_fincli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(2385, 'supervisor', '20191216122040', 88, 'ANTERIOR: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904080000, horario_salida = 20190904210000, lunes = 1, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her -- NUEVO: UPDATE c1651231_fincli.horario_laboral_x_usuario SET horario_ingreso = 20190904080000, horario_salida = 20190904210000, lunes = 1, martes = 1, miercoles = 1, jueves = 1, viernes = 1, sabado = 1, domingo = 0, cambio_dia = 1 WHERE id_usuario = her'),
(2386, 'her', '20191216172226', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-16 17:22:26'),
(2387, 'her', '20191216172737', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (6,150000,5,20,180000,Pendiente,1,0)'),
(2388, 'her', '20191216172737', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (51,20191216172737,1,25000458,her,2)'),
(2389, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,1,20200115235959,30000,Pendiente)'),
(2390, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,2,20200214235959,30000,Pendiente)'),
(2391, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,3,20200316235959,30000,Pendiente)'),
(2392, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,4,20200415235959,30000,Pendiente)'),
(2393, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,5,20200515235959,30000,Pendiente)'),
(2394, 'her', '20191216172737', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (51,6,20200615235959,30000,Pendiente)'),
(2395, 'her', '20191216172737', 86, 'UPDATE c1651231_fincli.cuota_credito SET estado = Pagada, fecha_pago = 20191216172737, monto_pago = 30000, usuario_registro_pago = her WHERE id_credito = 51 AND numero_cuota = 1'),
(2396, 'her', '20191216173751', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191216173751, monto_pago = 30000, estado = Pagada WHERE id = 126'),
(2397, 'her', '20191216174322', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191216174322, monto_pago = 30000, estado = Pagada WHERE id = 127'),
(2398, 'her', '20191216174344', 65, 'Reimpresión de Crédito: 51'),
(2399, 'her', '20191220102602', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-20 10:26:02'),
(2400, 'her', '20191220103249', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220103249, monto_pago = 30000, estado = Pagada WHERE id = 128'),
(2401, 'her', '20191220103249', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220103249, monto_pago = 30000, estado = Pagada WHERE id = 129'),
(2402, 'her', '20191220103249', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220103249, monto_pago = 30000, estado = Pagada WHERE id = 130'),
(2403, 'her', '20191220103249', 69, 'UPDATE c1651231_fincli.credito SET estado = Pagada WHERE id = 51'),
(2404, 'her', '20191220105543', 39, 'INSERT INTO c1651231_fincli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (los ponis,3584,1,piar,a5,1,5972,las flores,arcoiris,)');
INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(2405, 'her', '20191220105543', 42, 'INSERT INTO c1651231_fincli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3572581701,4)'),
(2406, 'her', '20191220105543', 45, 'INSERT INTO c1651231_fincli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,16731731,lis edith,soria,27167317311,19640331000000,---,20191220105543,Habilitado,NULL,---,500000,2,2)'),
(2407, 'her', '20191220105642', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,250000,4,10,275000,Pendiente,0,0)'),
(2408, 'her', '20191220105642', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (52,20191220105642,1,16731731,her,2)'),
(2409, 'her', '20191220105642', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (52,1,20200120235959,91667,Pendiente)'),
(2410, 'her', '20191220105642', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (52,2,20200219235959,91667,Pendiente)'),
(2411, 'her', '20191220105642', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (52,3,20200320235959,91666,Pendiente)'),
(2412, 'her', '20191220113637', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-20 11:36:37'),
(2413, 'her', '20191220134031', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-20 13:40:31'),
(2414, 'her', '20191220162405', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-20 16:24:05'),
(2415, 'her', '20191220163001', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,300000,4,10,330000,Pendiente,0,0)'),
(2416, 'her', '20191220163001', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (53,20191220163001,1,16731731,her,2)'),
(2417, 'her', '20191220163001', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (53,1,20200120235959,110000,Pendiente)'),
(2418, 'her', '20191220163001', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (53,2,20200219235959,110000,Pendiente)'),
(2419, 'her', '20191220163001', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (53,3,20200320235959,110000,Pendiente)'),
(2420, 'her', '20191220180425', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-20 18:04:25'),
(2421, 'her', '20191220180725', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220180725, monto_pago = 110000, estado = Pagada WHERE id = 134'),
(2422, 'her', '20191220180725', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220180725, monto_pago = 110000, estado = Pagada WHERE id = 135'),
(2423, 'her', '20191220180725', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191220180725, monto_pago = 110000, estado = Pagada WHERE id = 136'),
(2424, 'her', '20191220180725', 69, 'UPDATE c1651231_fincli.credito SET estado = Pagada WHERE id = 53'),
(2425, 'her', '20191221090015', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-21 09:00:15'),
(2426, 'her', '20191221090209', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,200000,4,10,220000,Pendiente,0,0)'),
(2427, 'her', '20191221090209', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (54,20191221090209,1,16731731,her,2)'),
(2428, 'her', '20191221090209', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (54,1,20200203235959,73333,Pendiente)'),
(2429, 'her', '20191221090209', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (54,2,20200304235959,73333,Pendiente)'),
(2430, 'her', '20191221090209', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (54,3,20200403235959,73334,Pendiente)'),
(2431, 'her', '20191221094836', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-21 09:48:36'),
(2432, 'supervisor', '20191221094849', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-21 09:48:49'),
(2433, 'supervisor', '20191221094939', 89, 'ANTERIOR: UPDATE c1651231_fincli.credito SET estado = Pendiente WHERE id = 54 -- NUEVO: UPDATE c1651231_fincli.credito SET estado = Cancelada WHERE id = 54'),
(2434, 'her', '20191226194837', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-26 19:48:37'),
(2435, 'her', '20191226195105', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (6,200000,5,20,240000,Pendiente,1,0)'),
(2436, 'her', '20191226195105', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (55,20191226195105,1,25000458,her,2)'),
(2437, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,1,20200127235959,40000,Pendiente)'),
(2438, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,2,20200226235959,40000,Pendiente)'),
(2439, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,3,20200327235959,40000,Pendiente)'),
(2440, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,4,20200427235959,40000,Pendiente)'),
(2441, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,5,20200527235959,40000,Pendiente)'),
(2442, 'her', '20191226195105', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (55,6,20200626235959,40000,Pendiente)'),
(2443, 'her', '20191226195105', 86, 'UPDATE c1651231_fincli.cuota_credito SET estado = Pagada, fecha_pago = 20191226195105, monto_pago = 40000, usuario_registro_pago = her WHERE id_credito = 55 AND numero_cuota = 1'),
(2444, 'her', '20191226200004', 65, 'Reimpresión de Crédito: 55'),
(2445, 'her', '20191226200119', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191226200119, monto_pago = 91667, estado = Pagada WHERE id = 123'),
(2446, 'her', '20191226200119', 68, 'UPDATE c1651231_fincli.cuota_credito SET fecha_pago = 20191226200119, monto_pago = 91666, estado = Pagada WHERE id = 124'),
(2447, 'her', '20191226200119', 69, 'UPDATE c1651231_fincli.credito SET estado = Pagada WHERE id = 50'),
(2448, 'her', '20191226205548', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-26 20:55:48'),
(2449, 'supervisor', '20191226205556', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-26 20:55:56'),
(2450, 'supervisor', '20191226205645', 89, 'ANTERIOR: UPDATE c1651231_fincli.credito SET estado = Pendiente WHERE id = 55 -- NUEVO: UPDATE c1651231_fincli.credito SET estado = Cancelada WHERE id = 55'),
(2451, 'her', '20191227090123', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:01:23'),
(2452, 'her', '20191227090358', 53, 'ANTERIOR: UPDATE c1651231_fincli.cliente SET tipo_documento = 1, documento = 22774142, nombres = rabozzi, apellidos = marcelo, cuil_cuit = 20227741423, fecha_nacimiento = 19720622000000, email = marcelo.rabozzi@gmail.com, observaciones = nada, monto_maximo_credito = 6000, id_perfil_credito = 1, id_genero = 1 WHERE id = 27 -- NUEVO: UPDATE c1651231_fincli.cliente SET tipo_documento = 1, documento = 22774142, nombres = rabozzi, apellidos = marcelo, cuil_cuit = 20227741423, fecha_nacimiento = 19720622000000, email = marcelo.rabozzi@gmail.com, observaciones = nada, monto_maximo_credito = 500000, id_perfil_credito = 1, id_genero = 1 WHERE id = 27'),
(2453, 'her', '20191227090443', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,600000,4,10,660000,Pendiente,0,0)'),
(2454, 'her', '20191227090443', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (56,20191227090443,1,30443194,her,2,1,22774142)'),
(2455, 'her', '20191227090443', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (56,1,20200203235959,220000,Pendiente)'),
(2456, 'her', '20191227090443', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (56,2,20200304235959,220000,Pendiente)'),
(2457, 'her', '20191227090443', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (56,3,20200403235959,220000,Pendiente)'),
(2458, 'her', '20191227090556', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,120000,4,10,132000,Pendiente,0,0)'),
(2459, 'her', '20191227090556', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (57,20191227090556,1,30443194,her,2,1,22774142)'),
(2460, 'her', '20191227090556', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (57,1,20200203235959,44000,Pendiente)'),
(2461, 'her', '20191227090556', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (57,2,20200304235959,44000,Pendiente)'),
(2462, 'her', '20191227090556', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (57,3,20200403235959,44000,Pendiente)'),
(2463, 'her', '20191227090703', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 09:07:03'),
(2464, 'supervisor', '20191227090706', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:07:06'),
(2465, 'supervisor', '20191227090810', 28, 'INSERT INTO c1651231_fincli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena,minimo_entrega) VALUES (Plan con Entrega,-,10,50,6,5,25)'),
(2466, 'supervisor', '20191227090814', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 09:08:14'),
(2467, 'her', '20191227090817', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:08:17'),
(2468, 'her', '20191227090850', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 09:08:50'),
(2469, 'supervisor', '20191227090854', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:08:54'),
(2470, 'supervisor', '20191227091238', 29, 'NUEVO: UPDATE c1651231_fincli.plan_credito SET nombre = Plan con Entrega, descripcion = -, cantidad_cuotas = 10, interes_fijo = 50, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 25 WHERE id = 15 -- ANTERIOR: UPDATE c1651231_fincli.plan_credito SET nombre = Plan con Entrega, descripcion = -, cantidad_cuotas = 10, interes_fijo = 50, id_tipo_diferimiento_cuota = 6, id_cadena = 5, minimo_entrega = 25 WHERE id = 15'),
(2471, 'supervisor', '20191227091418', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 09:14:18'),
(2472, 'supervisor', '20191227091421', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:14:21'),
(2473, 'supervisor', '20191227095259', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 09:52:59'),
(2474, 'admin_sys', '20191227095340', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 09:53:40'),
(2475, 'her', '20191227101152', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 10:11:52'),
(2476, 'her', '20191227101321', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 10:13:21'),
(2477, 'admin_sys', '20191227101435', 31, 'DELETE c1651231_fincli.perfil_credito_x_plan WHERE id_plan_credito = 4 -- nombre = Plan 3 Cuotas Clasico, cantidad_cuotas = 3, interes_fijo = 10, tipo_diferimiento_cuota = 6, cadena = PRUEBA'),
(2478, 'admin_sys', '20191227101435', 31, 'DELETE c1651231_fincli.perfil_credito_x_plan WHERE id_plan_credito = 5 -- nombre = Plan 6 Cuotas Clasico, cantidad_cuotas = 6, interes_fijo = 20, tipo_diferimiento_cuota = 5, cadena = PRUEBA'),
(2479, 'admin_sys', '20191227101436', 32, 'INSERT INTO c1651231_fincli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (1,)'),
(2480, 'admin_sys', '20191227101436', 32, 'INSERT INTO c1651231_fincli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (1,)'),
(2481, 'admin_sys', '20191227101436', 32, 'INSERT INTO c1651231_fincli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (1,)'),
(2482, 'admin_sys', '20191227101445', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 10:14:45'),
(2483, 'her', '20191227101447', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 10:14:47'),
(2484, 'her', '20191227101553', 61, 'INSERT INTO c1651231_fincli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (10,600000,15,50,900000,Pendiente,0,150000)'),
(2485, 'her', '20191227101553', 62, 'INSERT INTO c1651231_fincli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (58,20191227101553,1,30443194,her,2,1,22774142)'),
(2486, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,1,20200127235959,90000,Pendiente)'),
(2487, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,2,20200226235959,90000,Pendiente)'),
(2488, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,3,20200327235959,90000,Pendiente)'),
(2489, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,4,20200427235959,90000,Pendiente)'),
(2490, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,5,20200527235959,90000,Pendiente)'),
(2491, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,6,20200626235959,90000,Pendiente)'),
(2492, 'her', '20191227101553', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,7,20200727235959,90000,Pendiente)'),
(2493, 'her', '20191227101554', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,8,20200826235959,90000,Pendiente)'),
(2494, 'her', '20191227101554', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,9,20200925235959,90000,Pendiente)'),
(2495, 'her', '20191227101554', 63, 'INSERT INTO c1651231_fincli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (58,10,20201026235959,90000,Pendiente)'),
(2496, 'her', '20191227101654', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 10:16:54'),
(2497, 'supervisor', '20191227101657', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 10:16:57'),
(2498, 'her', '20191227111702', 1, 'Inicio de Sesion en Fecha y Hora: 2019-12-27 11:17:02'),
(2499, 'her', '20191227112018', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (10,180000,15,50,270000,Pendiente,0,45000)'),
(2500, 'her', '20191227112018', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (59,20191227112018,1,30443194,her,2,1,22774142)'),
(2501, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,1,20200127235959,27000,Pendiente)'),
(2502, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,2,20200226235959,27000,Pendiente)'),
(2503, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,3,20200327235959,27000,Pendiente)'),
(2504, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,4,20200427235959,27000,Pendiente)'),
(2505, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,5,20200527235959,27000,Pendiente)'),
(2506, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,6,20200626235959,27000,Pendiente)'),
(2507, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,7,20200727235959,27000,Pendiente)'),
(2508, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,8,20200826235959,27000,Pendiente)'),
(2509, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,9,20200925235959,27000,Pendiente)'),
(2510, 'her', '20191227112018', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (59,10,20201026235959,27000,Pendiente)'),
(2511, 'her', '20191227141915', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,95000,4,10,104500,Pendiente,0,5000)'),
(2512, 'her', '20191227141915', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (60,20191227141915,1,30443194,her,2,1,22774142)'),
(2513, 'her', '20191227141915', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (60,1,20200203235959,34833,Pendiente)'),
(2514, 'her', '20191227141915', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (60,2,20200304235959,34833,Pendiente)'),
(2515, 'her', '20191227141915', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (60,3,20200403235959,34834,Pendiente)'),
(2516, 'her', '20191227141954', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,95000,4,10,104500,Pendiente,0,5000)'),
(2517, 'her', '20191227141954', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (61,20191227141954,1,30443194,her,2,1,22774142)'),
(2518, 'her', '20191227141954', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (61,1,20200203235959,34833,Pendiente)'),
(2519, 'her', '20191227141954', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (61,2,20200304235959,34833,Pendiente)'),
(2520, 'her', '20191227141954', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (61,3,20200403235959,34834,Pendiente)'),
(2521, 'her', '20191227143203', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (3,100000,4,10,110000,Pendiente,1,0)'),
(2522, 'her', '20191227143203', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (62,20191227143203,1,30443194,her,2,1,22774142)'),
(2523, 'her', '20191227143203', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (62,1,20200203235959,36667,Pendiente)'),
(2524, 'her', '20191227143203', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (62,2,20200304235959,36667,Pendiente)'),
(2525, 'her', '20191227143203', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (62,3,20200403235959,36666,Pendiente)'),
(2526, 'her', '20191227143203', 86, 'UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20191227143203, monto_pago = 36667, usuario_registro_pago = her WHERE id_credito = 62 AND numero_cuota = 1'),
(2527, 'her', '20191227143316', 71, 'Generación PDF de Pago Cuota Crédito: 178'),
(2528, 'her', '20191227143350', 2, 'Cierre de Sesion en Fecha y Hora: 2019-12-27 14:33:50'),
(2529, 'her', '20200108140303', 1, 'Inicio de Sesion en Fecha y Hora: 2020-01-08 14:03:03'),
(2530, 'her', '20200108143413', 2, 'Cierre de Sesion en Fecha y Hora: 2020-01-08 14:34:13'),
(2531, 'her', '20200109105840', 1, 'Inicio de Sesion en Fecha y Hora: 2020-01-09 10:58:40'),
(2532, 'her', '20200109110014', 2, 'Cierre de Sesion en Fecha y Hora: 2020-01-09 11:00:14'),
(2533, 'her', '20200207144146', 1, 'Inicio de Sesion en Fecha y Hora: 2020-02-07 14:41:46'),
(2534, 'her', '20200207172413', 2, 'Cierre de Sesion en Fecha y Hora: 2020-02-07 17:24:13'),
(2535, 'her', '20200210172647', 1, 'Inicio de Sesion en Fecha y Hora: 2020-02-10 17:26:47'),
(2536, 'her', '20200210174342', 39, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (San Jose De Calazans,348,1,CORDOBA,A,5,5000,---,---,)'),
(2537, 'her', '20200210174342', 42, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (1,3513827932,3)'),
(2538, 'her', '20200210174342', 45, 'INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,31443194,Emmanuel Enrique,Carballo,20314431945,19850223000000,ecarb@gmail.com,20200210174342,Habilitado,NULL,---,200000,1,1)'),
(2539, 'her', '20200210175450', 2, 'Cierre de Sesion en Fecha y Hora: 2020-02-10 17:54:50'),
(2540, 'her', '20200211094825', 1, 'Inicio de Sesion en Fecha y Hora: 2020-02-11 09:48:25'),
(2541, 'her', '20200212095710', 1, 'Inicio de Sesion en Fecha y Hora: 2020-02-12 09:57:10'),
(2542, 'her', '20200212115308', 2, 'Cierre de Sesion en Fecha y Hora: 2020-02-12 11:53:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mora_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `mora_cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha_interes` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_interes` int(11) NOT NULL,
  `porcentaje_interes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `mora_cuota_credito`
--

INSERT INTO `mora_cuota_credito` (`id`, `id_cuota_credito`, `fecha_interes`, `monto_interes`, `porcentaje_interes`) VALUES
(1, 77, '20190813143009', 2322, 10),
(2, 77, '20190813150207', 1161, 5),
(4, 77, '20190902141839', 1161, 5),
(5, 77, '20190902142146', 1161, 5),
(6, 77, '20190902142639', 1161, 5),
(7, 77, '20191003000017', 1161, 5),
(8, 77, '20191004000017', 464, 2),
(9, 85, '20191004000017', 1100, 2),
(10, 11, '20191012000016', 2933, 5),
(11, 11, '20191013000017', 1173, 2),
(12, 12, '20191013000017', 1173, 2),
(13, 78, '20191016000015', 464, 2),
(14, 77, '20191103000017', 1161, 5),
(15, 85, '20191103000017', 2750, 5),
(16, 89, '20191103000018', 1467, 2),
(17, 86, '20191103000018', 1100, 2),
(18, 11, '20191112000017', 2933, 5),
(19, 12, '20191112000017', 2933, 5),
(20, 78, '20191115000016', 1161, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo`
--

CREATE TABLE IF NOT EXISTS `motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=101 ;

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
(30, 'Borrar Plan de Credito', 'Cuando se borra un plan de crédito.'),
(31, 'Desasingar Plan de Perfil Credito', 'Cuando se desasigna un plan de un perfil de crédito.'),
(32, 'Asignar Plan a Perfil de Credito', 'Cuando se asigna un plan a un perfil de crédito.'),
(33, 'Nuevo Interes X Mora', 'Cuando se registra un nuevo interés x mora.'),
(34, 'Borrar Interes X Mora', 'Cuando se borra un interés x mora.'),
(35, 'Modificar Interes X Mora', 'Cuando se modifica un interés x mora.'),
(36, 'Registrar Cliente Sin Validar Telefono', 'Cuando se autoriza el registro de un nuevo cliente sin validar el teléfono móvil.'),
(37, 'Registrar Cliente Sin Validar Estado Crediticio', 'Cuando se registra un nuevo cliente sin validar el estado crediticio.'),
(38, 'Registrar Cliente Con Estado Crediticio Validado Por Supervisor', 'Cuando se registra un cliente, y el estado crediticio es validado por el supervisor.'),
(39, 'Nuevo Domicilio Cliente', 'Cuando se registra un nuevo domicilio de un cliente.'),
(40, 'Modificar Domicilio Cliente', 'Cuando se modifica un domicilio de un cliente.'),
(41, 'Borrar Domicilio Cliente', 'Cuando se borra el domicilio de un cliente.'),
(42, 'Nuevo Teléfono de Cliente', 'Cuando se registra un nuevo teléfono a un cliente.'),
(43, 'Modificar Teléfono de Cliente', 'Cuando se modifica un teléfono de un cliente.'),
(44, 'Borrar Teléfono de Cliente', 'Cuando se borra un teléfono de un cliente.'),
(45, 'Nuevo Cliente', 'Cuando se registra un nuevo cliente en el sistema.'),
(46, 'Deshabilitar Cliente Titular', 'Cuando se deshabilita un cliente titular junto con los adicionales de cuenta.'),
(47, 'Habilitar Cliente Titular', 'Cuando se habilita un cliente titular junto con los adicionales de cuenta.'),
(48, 'Deshabilitar Cliente Adicional', 'Cuando se deshabilita un cliente adicional.'),
(49, 'Habilitar Cliente Adicional', 'Cuando se habilita un cliente adicional.'),
(50, 'Modificar Cliente Sin Validar Estado Crediticio', 'Cuando se modifica un cliente sin validar el estado crediticio.'),
(51, 'Modificar Cliente Con Estado Crediticio Validado Por Supervisor', 'Cuando se modifica un cliente, y el estado crediticio es validado por el supervisor.'),
(52, 'Modificar Cliente Con Estado Crediticio Validado Por Supervisor', 'Cuando se modifica un cliente, y el estado crediticio es validado por el supervisor.'),
(53, 'Modificar Cliente', 'Cuando se modifican los datos principales de un cliente.'),
(54, 'Nuevo Telefono Movil Sin Validar', 'Cuando se registra un teléfono móvil sin validar.'),
(55, 'Nuevo Telefono No Movil Sin Validar', 'Cuando se registra un teléfono que no es móvil sin validar.'),
(56, 'Modificar Telefono Movil Sin Validar', 'Cuando se modifica un teléfono móvil sin validar.'),
(57, 'Modificar Telefono No Movil Sin Validar', 'Cuando se modifica un teléfono no móvil sin validar.'),
(58, 'Registrar Credito Sin Validar Estado Crediticio', 'Cuando se registra un crédito Sin Validar Estado Crediticio'),
(59, 'Registrar Credito Con Estado Crediticio Validado Por Supervisor', 'Cuando se registra un crédito y el estado financiero del cliente es validado por un supervisor.'),
(60, 'Registrar Credito Con Estado Crediticio Sin Validacion de Supervisor', 'Cuando se registra un crédito y el estado crediticio no es validado por un supervisor.'),
(61, 'Nuevo Credito', 'Cuando se registra un nuevo crédito.'),
(62, 'Nuevo Credito Cliente', 'Cuando se registra un nuevo crédito en la tabla crédito_cliente.'),
(63, 'Nueva Cuota Credito Cliente', 'Cuando se registra una cuota de un crédito de cliente.'),
(64, 'Registrar Crédito Con Monto Máximo Excedido', 'Cuando se registra un crédito con monto máximo excedido de un cliente con autorización de supervisor.'),
(65, 'Reimpresion de Credito', 'Cuando se reimprime un crédito.'),
(66, 'Generación PDF de Crédito', 'Cuando se genera un PDF de un crédito.'),
(67, 'Autorización Supervisor Pago Cuota', 'Cuando un supervisor autoriza un cambio en el monto de pago de una cuota.'),
(68, 'Pago Cuota Crédito Datos', 'Cuando se registra el pago de una cuota de un crédito.'),
(69, 'Pago Ultima Cuota Crédito', 'Cuando se registra el pago de la ultima cuota de un crédito y se actualiza el estado general del mismo.'),
(70, 'Reimpresión de Pago Cuota Crédito', 'Cuando se reimprime el comprobante de pago de una cuota.'),
(71, 'Generación PDF de Pago Cuota Crédito', 'Cuando se genera un PDF del comprobante de pago de una cuota.'),
(72, 'Supervisor Pago Cuota', 'Cuando un usuario con permisos de supervisor realiza el cobro de una cuota.'),
(73, 'Pago Cuota Crédito Sin Supervisor', 'Cuando se registra el pago de una cuota sin necesidad de autorización de supervisor.'),
(74, 'Pago Cuota Crédito Selección', 'Cuando se registra el pago de una cuota por selección de dos o mas.'),
(75, 'Pago Total Deuda Crédito', 'Cuando se registra el pago total de la deuda de un crédito.'),
(76, 'Supervisor Pago Total Deuda Crédito', 'Cuando un usuario con permisos de supervisor realiza el cobro del total de la deuda de un crédito.'),
(77, 'Pago Total Deuda Crédito Sin Supervisor', 'Cuando se registra el pago total de la de deuda de un crédito sin necesidad de autorización de supervisor.'),
(78, 'Reimpresión Pago Total Deuda Crédito', 'Cuando se reimprime el comprobante de pago total deuda de un crédito.'),
(79, 'Generación PDF de Pago Total Deuda Crédito', 'Cuando se genera un PDF como comprobante de pago total deuda de un crédito.'),
(80, 'Cambio Estado Cuota Credito', 'Cuando se modifica el estado de una cuota a través de un usuario.'),
(81, 'Cambio Estado Crédito', 'Cuando se realiza el cambio de estado de la ultima cuota de un crédito y se actualiza el estado general del mismo.'),
(82, 'Cambio Estado Cuota Token', 'Cuando se realiza un cambio de estado de una cuota a través de un token.'),
(83, 'Cambio Estado Inconsistente', 'Cuando se setea el estado de una cuota que se encuentra inconsistente en la ejecución de un proceso automático.'),
(84, 'Sin Recargo Cuota En Mora', 'Cuando no se realiza el recargo de ningún interés a una cuota que se encuentra en mora. '),
(85, 'Cambio Mensaje Aviso X Mora', 'Cuando se reemplaza el mensaje de un aviso por mora que se encuentra pendiente.'),
(86, 'Pago Primer Cuota Credito', 'Cuando se paga la primer cuota al generar el credito.'),
(87, 'Nuevo Horario Laboral Usuario', 'Cuando se registra un nuevo horario laboral de un usuario.'),
(88, 'Modificar Horario Laboral Usuario', 'Cuando se modifica el horario laboral de un usuario.'),
(89, 'Cambio Estado Credito', 'Cuando se cambia el estado del crédito a cancelado.'),
(90, 'Anular Pago Cuota Tipo Total', 'Cuando se anula el pago total realizado en un crédito.'),
(91, 'Borrar Pago Parcial Cuota', 'Cuando se anula el pago de una cuota y se borra el registro de pago parcial.'),
(92, 'Borrar Pago Total X Cuota Credito', 'Cuando se borra el registro de pago de una cuota asociada a un pago total de crédito.'),
(93, 'Borrar Pago Total Crédito', 'Cuando se borra un pago de la deuda total de un crédito.'),
(94, 'Modificar Estado Crédito Anulación Total', 'Cuando se cambia el estado de un crédito por anulación del pago total de deuda.'),
(95, 'Anular Pago Cuota Selección', 'Cuando se cancela un pago de una cuota seleccionada con otras en el pago original.'),
(96, 'Borrar Pago Selección de Cuota', 'Cuando se borrar el registro de pago de una cuota seleccionada junto a otras en el pago original.'),
(97, 'Modificar Estado Crédito Anulación Selección', 'Cuando se modifica el estado de un crédito debido a la anulación de dos mas cuotas pagadas por selección del usuario.'),
(98, 'Anular Pago Cuota', 'Cuando se anula el pago de una sola cuota.'),
(99, 'Modificar Estado Crédito Anulación Pago Cuota', 'Cuando se modifica el estado de un crédito debido a la anulación de pago de una cuota.'),
(100, 'Generación Token de Supervisor', 'Para la generación de un token para autorización de supervisor.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_parcial_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `pago_parcial_cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `monto` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `usuario` (`usuario`),
  KEY `supervisor` (`supervisor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_seleccion_cuotas_credito`
--

CREATE TABLE IF NOT EXISTS `pago_seleccion_cuotas_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `monto` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `pago_seleccion_cuotas_credito`
--

INSERT INTO `pago_seleccion_cuotas_credito` (`id`, `id_cuota_credito`, `fecha`, `monto`, `usuario`) VALUES
(1, 128, '20191220103249', 30000, 'her'),
(2, 129, '20191220103249', 30000, 'her'),
(3, 130, '20191220103249', 30000, 'her'),
(4, 134, '20191220180725', 110000, 'her'),
(5, 135, '20191220180725', 110000, 'her'),
(6, 136, '20191220180725', 110000, 'her'),
(7, 123, '20191226200119', 91667, 'her'),
(8, 124, '20191226200119', 91666, 'her');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_total_credito`
--

CREATE TABLE IF NOT EXISTS `pago_total_credito` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `id_credito` bigint(11) NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `monto` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`),
  KEY `usuario` (`usuario`),
  KEY `supervisor` (`supervisor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `pago_total_credito`
--

INSERT INTO `pago_total_credito` (`id`, `id_credito`, `fecha`, `monto`, `usuario`, `supervisor`, `token`) VALUES
(1, 48, '20191009142201', 120000, 'her', NULL, '5f85441f32e035950452e690c797dc3096df7ebc531eb915a30f35e14ca9b72c7150ac7e00fb3107f72441118aa59e42ff41290aaf233502a50898bfdb00036f');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_total_credito_x_cuota`
--

CREATE TABLE IF NOT EXISTS `pago_total_credito_x_cuota` (
  `id_pago_total_credito` bigint(20) NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  PRIMARY KEY (`id_pago_total_credito`,`id_cuota_credito`),
  KEY `id_cuota_credito` (`id_cuota_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pago_total_credito_x_cuota`
--

INSERT INTO `pago_total_credito_x_cuota` (`id_pago_total_credito`, `id_cuota_credito`) VALUES
(1, 112),
(1, 113),
(1, 114),
(1, 115);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=32 ;

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
(7, 'maxima_cantidad_cuotas_plan_credito', 'Es la cantidad máxima de cuotas permitidas para un plan de crédito.', '12'),
(8, 'maxima_cantidad_dias_interes_x_mora', 'Es la máxima cantidad de días permitidos para aplicar un interés.', '365'),
(9, 'maximo_interes_x_mora', 'Es el interes máximo permitido para aplicar por mora.', '150'),
(10, 'limite_adicionales_sin_supervisor', 'Es la cantidad de adicionales que se permite cargar sin autorización de supervisor.', '0'),
(11, 'limite_clientes_adicionales', 'Es la cantidad máxima de clientes adicionales que se permite cargar en el sistema.', '5'),
(12, 'validar_estado_financiero_clientes_supervisor', 'Cuando se solicita autorización de supervisor al mostrar un estado financiero de un cliente.', '1'),
(13, 'cantidad_días_consulta_db_estado_financiero_clientes', 'Es la cantidad de días en el cual el sistema va utilizar el estado financiero del cliente desde la base de datos en el caso que este exista. ', '15'),
(14, 'edad_permitida_cliente_titular', 'Es la cantidad de años mayor o igual permitida para la registración de un cliente titular. Si el valor es cero, no se valida la misma.', '18'),
(15, 'edad_permitida_cliente_adicional', 'Es la cantidad de años mayor o igual permitida para la registración de un cliente adicional. Si el valor es cero, no se valida la misma.', '12'),
(16, 'monto_maximo_credito_cliente', 'Es el monto máximo de crédito otorgado para un cliente.', '6000.00'),
(17, 'monto_minimo_credito_cliente', 'Es el monto minimo de crédito otorgado para un cliente.', '1000.00'),
(18, 'monto_maximo_perfil_credito', 'Es el monto máximo permitido de crédito para un perfil.', '6000.00'),
(19, 'monto_minimo_perfil_credito', 'Es el monto minimo permitido de crédito para un perfil.', '500.00'),
(20, 'monto_minimo_compra_para_credito', 'Es el monto mínimo de compra permitido para un registrar un crédito.', '30000'),
(21, 'token_proceso_automatico', 'Es el token necesario para ejecutar un proceso automático.', '66a1ef7be2a558d7f414fa942dc17c5b6023c02afa083ddf03c84e1a3a5786410777ebf672f0ecd27125677a23c695bc6359e267888666ec0ac403b2fc500839'),
(22, 'cantidad_horas_entre_procesos_auto', 'Es la cantidad de horas entre ejecución de procesos automáticos.', '8'),
(23, 'cantidad_dias_x_mora', 'Son la cantidad de días definidos para que una cuota de un crédito entre en estado de morosidad.', '1'),
(24, 'cantidad_dias_cuota_incobrable', 'Es la cantidad de días necesarios para cambiar el estado de una cuota a incobrable.', '366'),
(25, 'cantidad_dias_avisos_x_mora', 'Es la cantidad de días permitidos desde su creación o ultima modificación hasta dar por cerrado el aviso por mora.', '180'),
(26, 'cantidad_reintentos_envio_sms', 'Es la cantidad de veces que se reintenta enviar un mensaje SMS.', '3'),
(27, 'cantidad_dias_reactivacion_avisos_x_mora', 'Es la cantidad de días para reactivar un aviso por mora para una cuota con estado pendiente o en mora.', '200'),
(28, 'cantidad_dias_actualizacion_datos_cliente', 'Es la cantidad de días desde el ultimo crédito en el cual se informa que los datos del cliente deben ser actualizados.', '180'),
(29, 'duracion_token_15', 'Es la duración de un token generado por un supervisor. (15 minutos)', '15'),
(30, 'duracion_token_30', 'Es la duración de un token generado por un supervisor. (30 minutos)', '30'),
(31, 'duracion_token_60', 'Es la duración de un token generado por un supervisor. (60 minutos)', '60');

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
(1, 'Clasico', 'Perfil con condiciones crediticias clásicas.', 500000),
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

--
-- Volcado de datos para la tabla `perfil_credito_x_plan`
--

INSERT INTO `perfil_credito_x_plan` (`id_perfil_credito`, `id_plan_credito`) VALUES
(1, 4),
(2, 4),
(1, 5),
(2, 5),
(2, 13),
(1, 15);

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
  `minimo_entrega` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_diferimiento_cuota` (`id_tipo_diferimiento_cuota`),
  KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `plan_credito`
--

INSERT INTO `plan_credito` (`id`, `nombre`, `descripcion`, `cantidad_cuotas`, `interes_fijo`, `id_tipo_diferimiento_cuota`, `id_cadena`, `minimo_entrega`) VALUES
(4, 'Plan 3 Cuotas Clasico', 'Es un plan de 3 cuotas clásico con diferimiento de cuota estricto.', 3, 10, 6, 5, 5),
(5, 'Plan 6 Cuotas Clasico', 'Es un plan de 6 cuotas clásico con un diferimiento estricto.', 6, 20, 5, 5, 0),
(10, 'PLAN 2 PAGOS', '2 pagos en tiempo estricto.', 2, 3, 5, 1, 0),
(13, 'Plan 12', 'asas', 1, 45, 5, 5, 12),
(14, 'PLAN Z NARANJA', 's/d', 6, 0, 5, 5, 0),
(15, 'Plan con Entrega', '-', 10, 50, 5, 5, 25);

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
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE IF NOT EXISTS `reportes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Reporte de Créditos Otorgados X Sucursal', 'Se muestra la cantidad y monto de créditos otorgados por sucursal de la cadena correspondiente al usuario logueado.'),
(2, 'Créditos Otorgados X Tipo de Plan', 'Se muestran los créditos otorgados por tipo de plan, sucursal y fechas.  '),
(3, 'Promedio Créditos X Sucursal', 'Es el monto promedio de créditos otorgados por sucursal.'),
(4, 'Promedio Créditos X Cliente', 'El monto promedio de créditos otorgados a clientes por sucursal.'),
(5, 'Cantidad Créditos X Cliente', 'Es la cantidad de créditos por cliente y sucursal.'),
(6, 'Créditos Cancelados X Cliente', 'Cantidad de créditos cancelados por cliente.'),
(7, 'Cuotas Pagadas X Cliente', 'Detalle de Cuotas Pagadas por cliente.'),
(8, 'Cuotas Pendientes X Cliente', 'Detalle de cuotas pendientes por cliente.'),
(9, 'Promedio de Compra X Cliente', 'Es el monto promedio de Compra X Cliente y/o sucursal.'),
(10, 'Cantidad SMS X Sucursal', 'Es la cantidad de SMS enviados por sucursal y cadena.'),
(11, 'Cantidad Informes X Sucursal', 'Cantidad de informes financieros por sucursal.');

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
(2, 345, 'assas', 1, '---', 5),
(3, 456, 'fgrty', 1, NULL, 5),
(8, 457, 'GIRF', 61, '---', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=80 ;

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
(34, 1, 2323231, 2),
(49, 1, 3514321121, 3),
(50, 1, 3456202535, 4),
(52, 1, 3513827932, 3),
(53, 2, 3513827932, 3),
(56, 1, 3452421211, 4),
(57, 1, 3513343434, 3),
(58, 1, 2322232323, 3),
(59, 1, 351567890, 3),
(60, 1, 3513827932, 4),
(61, 1, 3513897898, 3),
(62, 1, 3516434185, 3),
(63, 1, 3513827932, 3),
(65, 1, 3513456878, 4),
(66, 2, 3514742046, 3),
(67, 1, 351156147241, 4),
(68, 1, 351156727305, 4),
(69, 1, 3572586036, 4),
(70, 1, 3572586036, 4),
(71, 1, 35134123, 3),
(72, 1, 345212345, 4),
(73, 1, 67854321, 3),
(74, 1, 45612348, 3),
(75, 1, 3572583298, 4),
(76, 2, 3572481357, 4),
(77, 1, 3572546889, 4),
(78, 1, 3572604735, 4),
(79, 1, 3572581701, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_aviso`
--

CREATE TABLE IF NOT EXISTS `tipo_aviso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_aviso`
--

INSERT INTO `tipo_aviso` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Mensaje SMS', 'Son mensajes de texto enviados a través de la red d telefonía celular.'),
(2, 'E-Mail', 'Son mensajes enviados a través de un correo electrónico.');

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
-- Estructura de tabla para la tabla `token_adicional_cuenta`
--

CREATE TABLE IF NOT EXISTS `token_adicional_cuenta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `documento_titular` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `tipo_documento_titular` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `tipo_documento_titular` (`tipo_documento_titular`),
  KEY `usuario` (`usuario`),
  KEY `usuario_supervisor` (`usuario_supervisor`),
  KEY `fk_foreign_key_token_adicional_cuenta` (`tipo_documento`,`documento`),
  KEY `fk_foreign_key_token_adicional_cuenta_titular` (`tipo_documento_titular`,`documento_titular`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `token_adicional_cuenta`
--

INSERT INTO `token_adicional_cuenta` (`id`, `fecha`, `documento`, `documento_titular`, `token`, `usuario`, `usuario_supervisor`, `tipo_documento`, `tipo_documento_titular`) VALUES
(1, '20191003123708', '22774142', '30443194', 'ccb323f6afc56b59cd198d87b2e05341512192c8453a26f1eea23688a26d25d5c3875b213ce2107dff4ef9352c41d19751b19b77bedbb7fba3d70e4b07d68f6b', 'her', 'supervisor', 1, 1),
(2, '20191009122336', '38292091', '25000458', '673fa92f48c4d49525004c47b52b31874afb4cc7758b08d658f6e55f3d79dda37fbc053693ea9d06acc2a5dc0b06b63398c00d081c268f90dd31be9adad6021d', 'her', 'supervisor', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_anulacion_credito`
--

CREATE TABLE IF NOT EXISTS `token_anulacion_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `id_credito` bigint(20) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` varchar(2000) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`),
  KEY `usuario` (`usuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `token_anulacion_credito`
--

INSERT INTO `token_anulacion_credito` (`id`, `fecha`, `id_credito`, `usuario`, `token`, `comentario`) VALUES
(5, '20190906234038', 37, 'supervisor', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'llñkñlkñl'),
(6, '20191009174521', 47, 'supervisor', '7c7fb511ba15ecb518d92185f171ea3c4c4b605c7f10b89db41322903187d5509c6a949fd76f0914f0f8b7a06b7fa329ea8e6fce658028328a2fd196c187921e', 'mal cargado'),
(7, '20191221094939', 54, 'supervisor', '7e1704d8e57c090e7fbb7a84afc3139b093773947e25e1d5b7b04698e6e106c6323119fa2b929a6ad3cc39b615a624ba2d6325ab1c570e347e9f95cfa702aac0', 'mal cargada la compra'),
(8, '20191226205645', 55, 'supervisor', 'f6958f3edc88c113fa88fadf2672218d965447e7477cbf50197f06ceda0b16fc46dcb23c84bc560466e83e5b4ce324d3476ca7c1716619303e685afce51b093a', 'la clienta cambio el producto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_anulacion_cuota_credito`
--

CREATE TABLE IF NOT EXISTS `token_anulacion_cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` varchar(2000) COLLATE utf8_spanish_ci NOT NULL,
  `forma_pago_original` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=43 ;

--
-- Volcado de datos para la tabla `token_anulacion_cuota_credito`
--

INSERT INTO `token_anulacion_cuota_credito` (`id`, `fecha`, `id_cuota_credito`, `usuario`, `token`, `comentario`, `forma_pago_original`) VALUES
(12, '20190917113804', 88, 'supervisor', 'c92dbf88934ea27d323ab978d2581ef0addb4b3e9dc37b167ae9067b01d046dbbbd0e8caef558340f8b16e05404f2a9bd6b420af8366fcbdc508d6dba7d001d7', 'NOSE..', 'S'),
(13, '20190917113804', 89, 'supervisor', '5a589d1e8f172f266cf701890cb59f5094c280a2b6062fc9d87c911c5942adcd9d30da55661e5bdfc9b67f5e3adf456de56fec4d08749026b53133903b7a6733', 'NOSE..', 'S'),
(14, '20190917120911', 88, 'supervisor', 'd8051ad0c109de84a9f928ac1bb2e74dc232e14f12c55f1889ab5fa0eceddf32c25e886f020979ab9b43c926dee0089de3f58c8065e62559e70b2cfbe11e345e', 'zxzxzx', 'T'),
(15, '20190917120911', 89, 'supervisor', '8d7796924dd0ab32b742f1cbaa17bafaaab4482b50d14d17c9678cf3466e59ae3a56c001600393a5d38b4a6165b37c6d18cc5e8c88e4ea5429f09519330c5203', 'zxzxzx', 'T'),
(16, '20190917120911', 90, 'supervisor', 'e4089e43736024f9c44b51253a7c5d34fc5c5fbdbbc96afa80bbc73acbeacb15f26f2d0174fdebc30d19de37b1139c3b46b69f076e2f0f7f989599c34a14524d', 'zxzxzx', 'T'),
(17, '20190917121018', 88, 'supervisor', 'cfd2b1ba749459bdb930e97cafa2f2afdfda71318ab26e0b319b8ebb071a72f035cbabcc5e4794fcd03b30b1c13e8e2a07d79de684547e6aa4e565be84dd7c7f', 'ssdasd', 'T'),
(18, '20190917121018', 89, 'supervisor', 'd89d33862cedbb7f8c05c83f779b8146e049ae32c5bb2adfe13cb64f7e82146b209e0970bb059ce5760a16f7e3117e68b3d7b4a212c3fb3d135cb12cb4a4ff92', 'ssdasd', 'T'),
(19, '20190917121018', 90, 'supervisor', '860e3cf4b43b0d8f7c9bb712d042047b02f35db24490eb5ab9921305cac6525d47a7b895979d509ffffb80415bbe7118cf125887496d849f254ceae820af6732', 'ssdasd', 'T'),
(20, '20190917121157', 88, 'supervisor', '59ff219a26992954504ef80e54fce2280c577be594d6f7f0e0d3b6631e22c8d4f37b7c15af7ef411816f420f951bc46e699316e850c5647f79e40597da9068d8', 'sdsdsd', 'T'),
(21, '20190917121157', 89, 'supervisor', '01a2d8f86fb3425472e5cc462a75ae900b75829bee850c0556c8925ef35e961342381501594a36d410455ebf85883505c6297b7853cde4b94d18fe33debf4eef', 'sdsdsd', 'T'),
(22, '20190917121157', 90, 'supervisor', 'ecbff61b51d4f4217ff54df65cb52cc21a08fefbb8b17ca742fc7f170eb125399d65445a0c0e6e374bfc51e2808e5c441624c39a7ba808097af4007c306f03fa', 'sdsdsd', 'T'),
(23, '20190917121404', 88, 'supervisor', 'ed1680c440285b850c7c47bc6baa2e0586a4fa9fc56b8a0efb92285fa56d6e22748a53a7c4b34b8a78ba2cc7ce4df9e941c9fa73b8cd8538087e690ec600846b', 'lkjlkjlk', 'S'),
(24, '20190917121404', 89, 'supervisor', 'a483d26d1f30e2d51fb099071c9bb142a6a2d23c4768635e19b802b3514478bc50e29167077f035996f3efb91be850fa6aa14622bc42b6de3d4099281e57b50d', 'lkjlkjlk', 'S'),
(25, '20190917125739', 88, 'supervisor', '1dfe5ee5d48798fb1af30f24f1f6a482ba26a4f195c6c06b716882d179d328dfef39570aaa7a235bc2c2a4e52d4e8b16e3e0c32c5c5998b360ee19f55b638991', 'sasas', 'S'),
(26, '20190917125856', 89, 'supervisor', '882a77fc470e7854ae4ff207bd155adb609bceb8db32607f6936973ee5533c6a4a6ffc75b12ad455c12111a37098d331f07efe7c048ed6fb63473effac9aacb8', 'asasa', 'S'),
(27, '20190917130747', 89, 'supervisor', '1c72d83c03cf241197ba85bf5e8cc0b42ed5552005f1bf962885010861415e9fce1441cc72280b6cc06504b354133a88e24590d1c0225fc67263ef3bac59b7bd', 'asasas', 'S'),
(28, '20190917130915', 90, 'supervisor', '192b44cc8f9bd13e727fcd4b93cc97153b585ca74d1248020c4bf5eb48d96a516243da75263b6eb2034adc1f6bb4f1396d639a12eb47bd7b12c0343c894ff3eb', 'asasda', 'S'),
(29, '20190917141423', 90, 'supervisor', '4ba3bf4a501905337590502b7481a70be8c1dc5fbfa085957d24aa82f9c0ea26e167c957df8c6c1c39bcf536204f5ce6f7d01731b590a33ccdc9d52eeaa41f98', 'erewrew', 'S'),
(30, '20190917141622', 90, 'supervisor', '9dc5b9a42ff86c19d8fd082f39fd847e09e2cfeb28407586d8d7172e2937a792c4e052cb7b015b55c42124c91ff10be325da45121a2195fd28271734f29d6e88', 'ssdsd', 'S'),
(31, '20190917143341', 90, 'supervisor', 'fa5df0e96403d56cba0a52270d7a2bc1552d8de42c5be65eb34bd3f5c417dce4b2a5f02497ae51f37910aaa80f72838c299d02a9df2f12ad6c33492cf76ff330', 'asasa', 'S'),
(32, '20190917143740', 90, 'supervisor', '6632a070f2450260eab61dc7b0f83c6d5a6822fcf23cb3a7348ff8357e431347a75171eb1859fa701800b7ae61338bebf16ebbefd446c304593ae2c91d386660', 'asasa', 'S'),
(33, '20190917143937', 90, 'supervisor', '5dc371e2545780f527e1fd2d9f5e398315f98f5e9829eab420d51099a47706085efbbd38444cfadcaa9a928dc9338a837623f68ed7253e37ac98d74134768f65', 'asasas', 'S'),
(34, '20190917144039', 90, 'supervisor', 'cfe8fa5efb6ec7d3da278170b076d92f35a52b02a212bf090609d30e1ec05676f96582859bc533b1f9d59088616173f8e1279c7f10a9f5d9b7428e04901c4430', 'fdfsd', 'S'),
(35, '20190917144048', 89, 'supervisor', '160f3b0153e3a9b44b331f43ce18eb755787c5f9dd0c3d67a20b31c8081a0801c7649fc0f654ca9fc3b640960c3e8b4f5b23216a5d10cbfe7aaa4c02b54fb781', 'dasdasd', 'S'),
(36, '20190917144601', 90, 'supervisor', 'b5cc068e4233864e3a19d8ad4797353e0f6a4ca7c2950565bdda07d8eff7e121344379dcecb5c08862dacc8f0ea86e0bc125236237c8cbea86143716659e9ae1', 'asas', 'S'),
(37, '20190917145630', 90, 'supervisor', '55b47b99680b3656f4d28eebd9fd991e21981791ccb80cba97a82f8afc07abdbed0c10792aa853dc8d2245dba61494749cff8da6e3456b98e99b5fdb0d5ae9e7', 'asasa', 'S'),
(38, '20190917145650', 89, 'supervisor', 'bf7ff74cd528c2443f3086b0e7822bc9eae1b11b18c6c0450d079db98b95739bcb8a0df2029cd86e811b4afc6bb766d414e712bc23c3c407675554aa11ff914a', 'asasa', 'S'),
(39, '20190917150721', 89, 'supervisor', 'e1d4050697b06c2d7ab8778ef7e6f390bc7756a39051c3195603ee3e4dd6c41ba70171ccf7dafa10d5ec6357af9be41401438d2c2658975074be865318dc7118', 'asas', 'S'),
(40, '20190917150901', 89, 'supervisor', 'ef95df0ef5a031b05b8f2aeea7de5508cfdb932bc81f20f419a642bb1e83960799e46c4d0df803a47fb1f5ec9bf18f403d0eeaf503023fe337257c10d7b764bb', 'asasa', 'S'),
(41, '20191009190427', 106, 'supervisor', '102f2feb7fb911f665a284215185d289d2c0128ae1af8d55b56c27bc655deb23d135aff6e61409988324dbd879b22680049b2b97af779c8da63b28f87f864f36', 'klklkllk', 'S'),
(42, '20191013194335', 106, 'supervisor', '9fd63e361e04cb8a201d1669115fd713b781a82fd4ef43eb4e3902b1d40fdc8943e5e9fc500a4a2347308275192966b7aea86a0cc1c4defc36d112fd95c2db2c', 'adasdas', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_autorizacion_supervisor`
--

CREATE TABLE IF NOT EXISTS `token_autorizacion_supervisor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `autorizante` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `autorizado` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_utilizacion` char(14) COLLATE utf8_spanish_ci DEFAULT NULL,
  `utilizado` bit(1) NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `id_motivo` int(11) DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `autorizante` (`autorizante`),
  KEY `autorizado` (`autorizado`),
  KEY `id_motivo` (`id_motivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=33 ;

--
-- Volcado de datos para la tabla `token_autorizacion_supervisor`
--

INSERT INTO `token_autorizacion_supervisor` (`id`, `autorizante`, `autorizado`, `fecha`, `fecha_utilizacion`, `utilizado`, `token`, `id_motivo`, `duracion`) VALUES
(27, 'supervisor', 'her', '20191024164106', '20191024165341', b'1', '072e43e36f41f264ea1ea346ef2e76716d98590f315402346566437c8eb965c7eecab710b39c2e1ec4a03d236975a6981b0122d5c9a3ae14469c5fd54cc9aa6a', 56, 15),
(28, 'supervisor', 'her', '20191025135136', '20191025135253', b'1', '18fc94de84258e097a8ef77eae0c94964cc147e4d880a665aaf8086ee118cfb0d8dca086d595b6426c1e002067f0cb518c4440cd646b9e0568935333f358e4c1', 56, 15),
(29, 'supervisor', 'her', '20191025161526', '20191025161631', b'1', '38011e0c33199643bf847ab1159a29bf2b5f6c386d566fefa806272a9efbab5f7642eefe412081937875eea3eb4d655c9843fb1695b1d7e659a7e0ed2e1cb822', 56, 15),
(30, 'supervisor', 'her', '20191025161757', '20191025161848', b'1', 'ad6c905b19b76d6a6804e84f1116fd509f90dc74e195d0a18dc6bd38f1ca3c541973275c4d742dd8cbc514a3e9ee94a78fbeec19bb0e017840d4ba3b1d57e989', 56, 15),
(31, 'supervisor', 'her', '20191025202022', NULL, b'0', 'a67661c67bdb9d13196e774410c97d104c71c7b76245ca82b8243b8e1db874aa97ff2d8bef815b1cde4a5ad9fdd268210438fdf4b316f81aa26df9a093209c38', NULL, 15),
(32, 'supervisor', 'her', '20191028124629', NULL, b'0', '235cbbaaeb0803b80cbaffc77554958f45ba5f4a34764bd6b04b41fc91fad358e4477fb57b738111367e082e71fd6e34234fe3feec8cca05f1bfff46b3585c4c', NULL, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_cambio_cuenta`
--

CREATE TABLE IF NOT EXISTS `token_cambio_cuenta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `validado` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `usuario_supervisor` (`usuario_supervisor`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `fk_foreign_key_token_cambio_cuenta` (`tipo_documento`,`documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `token_cambio_cuenta`
--

INSERT INTO `token_cambio_cuenta` (`id`, `fecha`, `documento`, `tipo_documento`, `token`, `usuario`, `usuario_supervisor`, `validado`) VALUES
(3, '20190621155126', '37443194', 1, '52df1c95cfa96c158254834fcb9df1d7b75119175d26067c05946164a3162f8179662a793eb22ed7baf93760f7f60c598eb07e552188d264326df524c40ff676', 'her', 'SUPERVISOR', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_cambio_estado_cuota`
--

CREATE TABLE IF NOT EXISTS `token_cambio_estado_cuota` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `validado` bit(1) NOT NULL,
  `id_motivo` int(11) NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  `estado_anterior` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `estado_nuevo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `usuario` (`usuario`),
  KEY `usuario_supervisor` (`usuario_supervisor`),
  KEY `id_motivo` (`id_motivo`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `documento` (`documento`),
  KEY `tipo_documento_2` (`tipo_documento`),
  KEY `usuario_2` (`usuario`),
  KEY `usuario_supervisor_2` (`usuario_supervisor`),
  KEY `id_motivo_2` (`id_motivo`),
  KEY `id_cuota_credito_2` (`id_cuota_credito`),
  KEY `fk_foreign_key_token_cambio_estado_cuota` (`tipo_documento`,`documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `token_cambio_estado_cuota`
--

INSERT INTO `token_cambio_estado_cuota` (`id`, `fecha`, `documento`, `tipo_documento`, `token`, `usuario`, `usuario_supervisor`, `validado`, `id_motivo`, `id_cuota_credito`, `estado_anterior`, `estado_nuevo`) VALUES
(4, '20190807124035', '87654321', 1, '66a1ef7be2a558d7f414fa942dc17c5b6023c02afa083ddf03c84e1a3a5786410777ebf672f0ecd27125677a23c695bc6359e267888666ec0ac403b2fc500839', 'her', NULL, b'1', 82, 77, 'En Mora', 'Incobrable'),
(9, '20190807144732', '87654321', 1, '1d487cbd778e47d16f34499f5b9847125e0a5ac340b0005f51aa57c4bab9036f66328d3460330d04e915767f6564a34a3ba298e6145b81ee3c9964e8671540c8', 'her', 'supervisor', b'1', 82, 78, 'En Mora', 'Condonada'),
(10, '20191009143657', '87654321', 1, 'be716012b9f5f17c69d162feb2ec60e9f61be29f4050f794048676332ab75b41ea068dedcc9e96047672d5e4122042419af3f8426cab6d4e8ccf8039fe058888', 'her', 'SUPERVISOR', b'1', 82, 10, 'En Mora', 'Condonada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_pago_cuota`
--

CREATE TABLE IF NOT EXISTS `token_pago_cuota` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `validado` bit(1) NOT NULL,
  `id_motivo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `usuario` (`usuario`),
  KEY `usuario_supervisor` (`usuario_supervisor`),
  KEY `usuario_2` (`usuario`),
  KEY `id_motivo` (`id_motivo`),
  KEY `fk_foreign_key_token_pago_cuota` (`tipo_documento`,`documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=51 ;

--
-- Volcado de datos para la tabla `token_pago_cuota`
--

INSERT INTO `token_pago_cuota` (`id`, `fecha`, `documento`, `tipo_documento`, `token`, `usuario`, `usuario_supervisor`, `validado`, `id_motivo`) VALUES
(1, '20190915235844', '32443194', 1, 'cbfdffc09ae3bcdf1b7e3ac3353ce8c6d7345b5a6a7b65c3c26039bb20aec37d7222fe88944719d4c7baa06b7f5e660fc6fa9e478dae933bdcc304769482eca0', 'supervisor', NULL, b'1', 77),
(2, '20190916003440', '32443194', 1, '6d235f793ba005d7af9ea09ebf797debae57011ed92f70533ebd4bac2ea3cc7cd5c9e390f6fa53c90125e349373d340a1a44138d257d497dba609a4d56d4d6db', 'supervisor', NULL, b'1', 77),
(3, '20190916160506', '32443194', 1, '0b1974abbf88edca4c6c133015e76bc27062592d58f9337fa14e102a51577aa1320110dca1a55075305eda465c0beb344a8dc9b65ddc213df7fedad8b2c820d4', 'supervisor', NULL, b'1', 77),
(4, '20190916164202', '32443194', 1, 'e4db7e5557442ba6487ef3465b3bc4577886d1c1d9a5277aa155fede1ae7d577e648b529fb7aa16bca62c0003d92eecd446c813b209c54f2c51cd1ff848cfa7d', 'supervisor', NULL, b'1', 77),
(5, '20190916165156', '32443194', 1, '53a79f15f37d1e367521e54010bd748634d5a9931aca84b28e049eff9da2ddc1b3d309bb00ef78b15b70917ccc0f2e6e4322f36fc5e494260810789d9d05fc53', 'supervisor', NULL, b'1', 77),
(6, '20190916165700', '32443194', 1, 'd10997665bb8ecfbae21f9afc5bd1e2f5fa536c60085d5e4828efc54cab074879a3bc01ff06e019c60a8dd9b0f39bee2901e99896c232e313eee92dcd4c1f825', 'supervisor', NULL, b'1', 77),
(7, '20190917113334', '32443194', 1, 'febc0f18b9089dfefd34e840d6d7677eee4b96f851e31596a06355b40881b2a8da31b9a86f709d25c35277da1699c00ca4f95137107dfaf85cd5bd7f556d1d8c', 'supervisor', NULL, b'1', 74),
(8, '20190917113334', '32443194', 1, '6de401bb22895e737a84f1272b64caba47efcdc6c127e8fd3a5cb10cbdc693552fe9bfbba85461caf641fa95c143aee0790f1e6ee6a7791b53ec4f466bc891bb', 'supervisor', NULL, b'1', 74),
(9, '20190917120859', '32443194', 1, '27b33cb875e7d232cae27062a86b7797037c207b2e30ac97ca5f4fdb3607a22db78031d7e4971e78ba938a51117d2be57cf46b722dfcd249e4f168d8e8fae636', 'supervisor', NULL, b'1', 77),
(10, '20190917121009', '32443194', 1, '06dfc146531abdbe548b6c38fbe1a83541d42bb5a2cf5d0f3857c2f2a002ea675110861bffeee48d5a963fde5d6a8a9475310e6c66ef0412765da141fd60a3c0', 'supervisor', NULL, b'1', 77),
(11, '20190917121149', '32443194', 1, 'a94f27ce8a4881097265a119943f54ab4e63c46a329661de237cfaf32d2e1cf825b6a9728946c24e32ef6c1c07221b4bb443362661f4b3bd5191cdfec1d41056', 'supervisor', NULL, b'1', 77),
(12, '20190917121303', '32443194', 1, 'd383bf7ff6fcfc4a30ab9d5c785d2bc1b4c5d51b8253b5480a7684b7d0bd45b0ec59889a2509fc1756a0c71b3d3c1eec0db40161e4abf9919e9affbbb10235e1', 'supervisor', NULL, b'1', 74),
(13, '20190917121303', '32443194', 1, '8c092c52fd3c3d90ed650b9ebf87ea5a32679257c92d67da54dea8a5e8ed5fc5423aeefb278a2707cb2e91f72a662e1bc3ae1e8d89f0c0fdee4cb623cb897bc0', 'supervisor', NULL, b'1', 74),
(14, '20190917125445', '32443194', 1, '8de3a98a3a2f7c3fdb1db8b8d6cac667afa632e1bf0d7a9484601abc367b82fcf92fd8d6bbecc8527d37ce0fac9e3a9556accbb765b104848038e80aca59f20d', 'supervisor', NULL, b'1', 72),
(15, '20190917125824', '32443194', 1, 'e86089612fe347f2d3360ed03658b0ae4dc11979e1d73b062c94be1aade5ca5f1cd7b70fff486665a72081ceffb52a7c99b8496a61fd53281dd9db3422c0b67b', 'supervisor', NULL, b'1', 73),
(16, '20190917125845', '32443194', 1, '77a6f8d1a2069113921cd95f469a3ac075b656875b5934301a29c4592a91a4720c6db109fcf94b774c511edc2303fad512b97943a05c76b0cb5bd69c814c9d6f', 'supervisor', NULL, b'1', 73),
(17, '20190917130738', '32443194', 1, 'ac7d50817f633a4c451c6183980d1ca0d766c88a8893e245ef2881e13bf409de3b15cf9fb915f0a618e8dd60be04d5067947efec6a772917c65aae2e01b79a34', 'supervisor', NULL, b'1', 73),
(18, '20190917130810', '32443194', 1, '12123dfe580ad3262904e12e884706ac524314ba86aa6a255eff0f846831be1742ca0078555316f2816e9e7768fd6bc1cbda540637e8b57515ebfd68de73053c', 'supervisor', NULL, b'1', 73),
(19, '20190917130817', '32443194', 1, 'e09c0529856ba8652f80d163e7e5c7995ebc153c281fac4b38cc2a44e9ff831e5b36abd4d4ab9786cd9e6416cd454c640bbd5faa594cb4719b213995841afd43', 'supervisor', NULL, b'1', 73),
(20, '20190917141403', '32443194', 1, 'e77e1a94ef4f86d516ecad2501d732bf070b30c986dfec9b6df930bb72e91b28b8a5763c391d6ac65f7b5d178a9897f7a6982be45279875c498d5bbc81bdd567', 'supervisor', NULL, b'1', 73),
(21, '20190917141601', '32443194', 1, '1ca3849b0b4c28e2b2ddf099985017de296487fc8effdefd805794ef3bddeab43637bffbcbeae81a6efc9b05e9ea085d1936420485853692d7a6f0e1586bd6cd', 'supervisor', NULL, b'1', 73),
(22, '20190917143318', '32443194', 1, '9479556065868893d3f0ea7e56981aa16062f9081898e752903d5cd2b7cd84abffbea4d2ffbae53ddf342deee5791da55151a00fc20a6ac6ab39563eefc38042', 'supervisor', NULL, b'1', 73),
(23, '20190917143437', '32443194', 1, 'f3415d7327ff4ade3d89712e5ea741a1dbc6ebe2bc3bf5e07d5aa3664e5776c42b4b88432cb6925452fdc28d855b7bbc353806580ae98e831ec398951cbe6c11', 'supervisor', NULL, b'1', 73),
(24, '20190917143926', '32443194', 1, '8486fd2385dad4a7da0aa0ab026ff0e4eeb08eb32ab3ae98ceb658797dc3c763b7d30cb6b1f61426337e533bbfe5c144d1218029c9ca7cdee3e40b7b86fbfe2e', 'supervisor', NULL, b'1', 73),
(25, '20190917144032', '32443194', 1, '530d092a25ef503fe97dbf0a9d5b93648c6f4cc4c714f7b3fa3f6bd8303fcc596e10f5bba6e3010db6b30bde1a29056a30de9b91d42fbf1a3b0d6fac925eab23', 'supervisor', NULL, b'1', 73),
(26, '20190917144123', '32443194', 1, '6cb97e2a54fa6e7493d99886a5b4cdc6f5a948e66ae85df3fb5c76ead8b5af7d2d758cfcbf69fad62cea184c2c718e18371fb8234f74bb262fb28cdd7b956039', 'supervisor', NULL, b'1', 73),
(27, '20190917144127', '32443194', 1, 'd0cb1c9552a6159b6b7809ad8378e186d87992f66cdb4e47f4d186022e782b3c2fd9f87760b912cb639027d77ff8341451a2888a9def873826cb4db5246b99ba', 'supervisor', NULL, b'1', 73),
(28, '20190917145620', '32443194', 1, '0e26a225845e25c466ada5c5d2494076f5209945842e4fed8e8989d58d6816cd39eea0b45ab430b04b8e43dbcc647db7f822d1423181d7b869573fd481475272', 'supervisor', NULL, b'1', 73),
(29, '20190917150103', '32443194', 1, 'a56e4065c3e0ad08a9b6cbea4003b9584d585372df0ebdf49846288e52b07857666e33e4c96a4e460e2038c37fa9ae480dd0311ac493edf3da7aad62a77ebec6', 'supervisor', NULL, b'1', 73),
(30, '20190917150840', '32443194', 1, '9986e4faef3301c4bbde81e2346d4b5054e41353328483af70b2ede0252ec3d4813775d1d79eb30bb3e7fb90793b9888b16ee2131f18c547581bbcd91bbec2cc', 'supervisor', NULL, b'1', 73),
(31, '20191009142134', '25000458', 1, 'e8a99b568e17810998afe0da0e726d6bfc667ca987c7553c2bf3a43a168f1006aaa9df8ddf3d73f6c3a1e7b63fc8e36106267bb995bdc29615d6dff34100e76f', 'her', NULL, b'1', 73),
(32, '20191009142201', '25000458', 1, '09528fd9769319fa7e6b558beeb696e7543e7d835acb8069621844dfa0ad33b2f4a1c6cb98e68c6b8ece1470f873e26fac712355b47ca8035bfc7b237802096e', 'her', NULL, b'1', 77),
(33, '20191009185511', '32443194', 1, '509e308e977aef3223f88566d47a583b3fc4cb9b5c58e01a01b8c0828150e1c204132e51405b9f916c9f68873ac881aaefe51e1fb3bb4626e199fe44b2a33318', 'her', NULL, b'1', 73),
(34, '20191009190008', '32443194', 1, 'dbe2a5318a2bbe928110e7218df9609e48e67da9325cc9b8429028138787212c1a2933352ed4a2914bed52787854541438d537704451f871c013f9cb35761d82', 'her', NULL, b'0', 67),
(35, '20191013194320', '32443194', 1, 'dd4987c9f167992ff1e9d988d1f474c03262d846915a9049caba6d07022ab60770d3c8719842b4215e9585a48b1a29001e6695d70f9b9237cc2eff298e01caa8', 'supervisor', NULL, b'1', 73),
(36, '20191013194738', '32443194', 1, '04ea2e54d50c411c3978c58549b44c041744ad28f7f67cb1b9d8990e7f32c3db57c2b696ddc9a441c5769318af86a6ef12436e756f142f053aee8ca113fb458b', 'supervisor', NULL, b'1', 73),
(37, '20191203154424', '32443194', 1, '627b6118b95580fe465ab1d5d35a5a39be37b584e423dfcd777eee4aabf87130c2e41899c43570ed90034f5c6139fda0340e4bb34388fcdfa4bb2c12202dbd0c', 'her', NULL, b'1', 73),
(38, '20191209095059', '32443194', 1, 'd9e49e5eb18750e35afc234939c3d2ee1d91a9ac663616d0478345412add0bc44762c4e945019f608faf80af937f9a2e941f0a5cffbf91615da1b398ca4491ad', 'her', NULL, b'1', 73),
(39, '20191209095238', '32443194', 1, 'a9b1406b084a4c907161d2c799bea8af1e436ac51fdc059980f49f4b0509e3f4be4b9f44339031f0bfe573f1b91ece7a387d2bf71f27dc5f4bb063209572b28b', 'her', NULL, b'1', 73),
(40, '20191209095728', '25000458', 1, '28ccd3dd75d3ba96d51556ce7f4e67fc614ac5603804f0ab05a2e46824671a089c692e0e9f7914f505aba4b98eb5d378a1df2e063c4b3944259eef7b1a861263', 'her', NULL, b'1', 73),
(41, '20191216173751', '25000458', 1, '298df55372e150fde29cba1a30da8cf1fb87c2ec0342856f3e232835946a86912102fa8026865e49bd4413e3eb107f57213aa1fde2fdee98d27ac71817bd5121', 'her', NULL, b'1', 73),
(42, '20191216174322', '25000458', 1, '1a0c1cfbfab10bf016bc97464173251187f5233801824c959b78b8a963d82bfaaba02fec6ab9e71094ea55748b31a3927b2f707698cdb1f9158da4c1578325b4', 'her', NULL, b'1', 73),
(43, '20191220103249', '25000458', 1, 'd3b11d5f7c0c7fcba105d259c6dfd334955553fb7581f5e58e431cca3469c90d4fe75ed27ee0e90e6c994ade8fbe6d5211391fe80b660b798a14a931b5677f60', 'her', NULL, b'1', 74),
(44, '20191220103249', '25000458', 1, '147b93f09f4962a6ab53c1a62732d9de22eff784af42a008bc69a40c02ab2011d9f50fc76610bfa16d13a4e47b62b9c00b432db278373159c51e53418be9868d', 'her', NULL, b'1', 74),
(45, '20191220103249', '25000458', 1, 'e02505a696344cf2806604df551fc0eac8918854f39c68ddbe5b50432c8f9c0259b82d1079fd216d50fe7eee320228d832539cffaa1daa50a328ab15ba6a2648', 'her', NULL, b'1', 74),
(46, '20191220180725', '16731731', 1, 'a2f13a7aa9db46445b2f93ac6970362bdbe2bc25cd554bff2cd0c3b79b514ee8f0ae761ef7d4fa2933809f81c81d439c2f75470f7d4597d4a0aec31ba78339ef', 'her', NULL, b'1', 74),
(47, '20191220180725', '16731731', 1, '5463764709cc664bf87829ce0f3bc6e34a772526a85fd59257d8339661ae810f2f681e9b76d4fd91f6316cc23e963707436d398b2a7149c432697f7030ba1e7a', 'her', NULL, b'1', 74),
(48, '20191220180725', '16731731', 1, 'f039fabec341a336c59ce1fa9ebf07446e67a860e95375c48ccf624d8fa54b2282e05ba4c7bbc2b2039696038c215472ec234d13550a0e78cb97ba9c3fcd6087', 'her', NULL, b'1', 74),
(49, '20191226200119', '25000458', 1, '22f8b454986b8936af0f7f64eefbc8b0cc5b30ee6ca03315d8bede77d79088b37b43ae001308846fb9234657d4f77e15cefd2b9d35404c970995313833ca6738', 'her', NULL, b'1', 74),
(50, '20191226200119', '25000458', 1, '963086f07ad223da345863f5310b297cc545af5197a3b8c9ef73e1761dc336ec4bc972827473cd395bfc63a4098c1e8514b6b3eeadf67061c75ad0cc7e639a52', 'her', NULL, b'1', 74);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_validacion_celular`
--

CREATE TABLE IF NOT EXISTS `token_validacion_celular` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `codigo` char(4) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `validado` bit(1) NOT NULL,
  `nro_telefono` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `fk_foreign_key_token_validacion_celular` (`tipo_documento`,`documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=33 ;

--
-- Volcado de datos para la tabla `token_validacion_celular`
--

INSERT INTO `token_validacion_celular` (`id`, `Fecha`, `tipo_documento`, `documento`, `token`, `codigo`, `usuario`, `validado`, `nro_telefono`) VALUES
(18, '20190703123017', 1, '87654321', 'e31ea8d4524787260fe99af89edaf669d2cc51f84b684484a25f0e9064c63c090039ad2bca438bb8473935cc81a321277c0f32cafd19bdbcebc0779b872dbcd7', '0274', 'her', b'1', NULL),
(20, '20190704214649', 1, '87654321', '86ac66801d194a03492f867193181c8ef35528c9e4409b92050de053e9b3d96078891e873371c329b9223687264c8b105dc079113c66fa16164a0a9cf9b3c066', '4802', 'her', b'1', 3513827932),
(21, '20190705125929', 1, '87654321', 'f924c62e712167f431a1c387112587baab51c38e127fc866f8b40a64a8c822d5f78e143c05a87f59bcfddf65882b424632d05b36a84bc91a6e738b386ebd259e', '7282', 'her', b'1', 3513827932),
(22, '20190708104817', 1, '87654321', '87f687a3e6eb030b9757f098df3eb7cb950ea8edf14f889152dae3934bb303acfb234d6936914341be25f5b3f9f37994663aae73851419adaf2cea6fc9413b13', '8096', 'her', b'1', NULL),
(24, '20190829102811', 1, '89443194', 'bc85226786ced3d7520ccfe3ae91a0f5d849b1394bde02c559e8b25c8c8659e4ef2a4bd8bf9b3992574c73ceacd4aa77fcd8095958bf49f88b48ff5dbdede15c', '9511', 'her', b'1', NULL),
(25, '20191003121524', 1, '89443194', '7ffa6ef3c0fa249d8446d862ed4cc7b6df1c01a343777e02f5eafe7971619deb3a5f2e83008a1899d2044a26f33ce7cbb5362a50c31787f0d9824db611b12e67', '5431', 'supervisor', b'1', 3513827932),
(26, '20191003124856', 1, '22774142', '39adc3d9837998c336ecc3a8eec215f40560c07f5aec800a185f9edecda5b85e41fb6154caf6b1ad2b2c337aa9743115e2da3661090f92f721eacbaef173036f', '0934', 'supervisor', b'0', 351156727305),
(27, '20191009121545', 1, '25000458', '0680f79deb09cba0947c0ea3d629a3470adde9fb8412a0e3f65be709892011fb5d05b28a2473c33aa66e4faa75b32984af6dace10a3bf51a7f7b50b9d606691b', '6510', 'her', b'1', NULL),
(28, '20191009122339', 1, '38292091', 'b0e2ee773e8461de8545dd14e44ce210e917f00bba3b809841c02d5f193e3fe676c0fcd16f6fcdce3477c1dfa4f1a8d8e97d6c93cfd8b0a1ab3bb63ed8b29762', '7072', 'her', b'1', NULL),
(29, '20191203082123', 1, '28934582', '5cd9f5cd4ff1cfe21806edf8d082d840e98bc5fc6e344e1f4f7a103f4621274641f55cb04c3f2da15313338df10027ac853c7f58936d222cf51553d53c81409d', '0891', 'her', b'1', NULL),
(30, '20191204211731', 1, '39173040', 'd3d98e8484b008e29db45acdb5e0edb544b63606d281a33576ec385f3841ed16a74b32ac6b8ea1ec269bfe734c385b65125826b0367b01dd8755dfe656e59e9b', '3214', 'her', b'1', NULL),
(31, '20191220105438', 1, '16731731', '528c7d0ac11ab14a36862d4aa8e0b067ad2c59212f73c20bbdbb3776993f625b34ff2a912ed8e53e7b3a745b9efa66d4509d1135168fa5dc5482ad74273a7474', '9621', 'her', b'1', NULL),
(32, '20200211095009', 1, '5098271', 'f9210d91378119e68b6f1fdae0f84bb31c4f7bddd7618f68291be22338eadf632036c8fbd4d86c31dc26702b497dc816ac26d49fbac6edb3f92293ad7b3033e7', '7192', 'her', b'0', NULL);

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
('aassad', 'adsd', 'sdsd', 1, '232434', 'asa@ger.com', 2, 2, 'Habilitado', 'fbb050bd9acd20648196c215b92438101c4298c6a6a0b04f314556e617a8bf6c5721a39abd969278b509a15e7dfcdb07616a86493b399944e3206384c578c75d', '09b67511d2d0fea7672e4dd3cb3ee4b9da3d3c3f4430b304ae77e932ec3830ba7453be894b49632f66d7ff6651abd1db446d47fa4958e6dd3d8ae1c225524ec3'),
('admin_sys', 'SISTEMAS', 'ROOT', 1, '9999999', 'nada@sistemas.com.ar', 1, 1, 'Habilitado', '01cb65f711ac7c7a3f23c197684d71acdf4773f1639f536ec54d0712bb6f56176a303b058d455a3a554297f1d9478272993838282d1f9ccc1b97244d38c1a065', '2d5101134a3866e11d6a576250d613f7f799d44cf2763b71c77033febcedf9124f7a216da9c88193385d31ba113b078e79a5574855166b16f9b6b0deb864f14f'),
('asa', 'asas', 'asas', 1, '32232', 'asas@her.com.ar', 2, 2, 'Habilitado', '6d802c651048cebc1a66605c72520727af68cf548f8a316866700b55ba83076561e354b4895e2c8b37f4fad14f53d0372e9ba2da2bcc35472df5a2245334a990', '1ed2e410a906633d67b3f78cb750c90f4765510bbf6724312b344cc4e40504295ddf4a38b53c8adfd3409e8286f134d7afe21b374652a81ae29432b412231acc'),
('her', 'herbasio', 'serio', 1, '2323211', 'ferd1@gmail.com', 2, 2, 'Habilitado', 'f39a333b451ab6ca4443271b18426e7e1df15d29d860cccea80d90c59aa6a989aa934548e08e0c7fd493c41a3af381f5b6d8802e398d15c7b3f5387095ab052b', '65f03e90f3a7d64a5c219c0f6e7ba8acc14d81fc1ff016d3c6f2041f8e5a5fcd1954e645f5068654603e32e742cce0f10e3a2ecc2cd069c35eaaeaa952eff2a7'),
('secup', 'Nestor', 'Trion', 1, '56443194', 'ser@gmail.com', 2, 1, 'Habilitado', '2448d477c747dc0efff0bca93b4e1d2c4491790e32efb3f7d5a38cf0c1b8aaf991dc2557bae74a8c85ca39a71c1356f1cd1ce6a879db971529dcaf4ddf77a467', '4c49f8a40d5e67cb6eb988a0cce5dc837efdde172f7e5ea1babeef096d3c7e03541e407d9fc525963fb242a39988dfe1b50f19a87b186e26ba07b91a809541eb'),
('servi', 'gerad', 'jjkk', 1, '1234', 'ger@lib.com.ar', 2, 1, 'Deshabilitado', '41dc55d092a712bdd817a1091bae5118fbb4d8322bca0c002cfe63204412d5afb854ba348df0fa5f56b78b9d4bcab496e0a6b8e21d290cb6742e6097fd82d32e', '7a3d4996840a580e4ec6637e4df842726dc4cbb3666b675be9564410a17ba838e7061b4cad3132b9124ae228da7d153206b43ba4b05a95e13100641b2c8b45b2'),
('supervisor', 'Supervisa', 'TODO', 1, '1234123', 'teestamosobservando@segu.com', 3, 2, 'Habilitado', '30664284d8506bf354ba109da9e56c78bf038867db8a55fca72d2a1021ee5473a6d76d56c2d59f2e53825a6919a4fb3da0f057bca8c9fce3b6ad9c1a2cc1424e', 'fcbd814ca4f11378c0b2f38c30265e6ce290ff3d82a1dac1afa9d98fda501e49aab1365f4e634bc9db4df9a04651caae211233601860819efa052bf5a8bfe29c');

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
('servi', 56),
('secup', 88),
('asa', 92),
('aassad', 93);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_telefono`
--

CREATE TABLE IF NOT EXISTS `usuario_x_telefono` (
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_telefono` int(11) NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_telefono`),
  KEY `id_telefono` (`id_telefono`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_x_telefono`
--

INSERT INTO `usuario_x_telefono` (`id_usuario`, `id_telefono`, `descripcion`) VALUES
('admin_sys', 24, NULL),
('admin_sys', 25, NULL),
('admin_sys', 30, NULL),
('her', 65, NULL),
('her', 66, NULL),
('servi', 31, NULL),
('servi', 33, NULL),
('servi', 34, NULL),
('supervisor', 11, NULL),
('supervisor', 13, NULL),
('supervisor', 14, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_datos_cliente`
--

CREATE TABLE IF NOT EXISTS `verificacion_datos_cliente` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` char(8) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`,`documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `verificacion_datos_cliente`
--

INSERT INTO `verificacion_datos_cliente` (`id`, `tipo_documento`, `documento`, `fecha`) VALUES
(1, 1, '50443194', '20191009');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aviso_x_mora`
--
ALTER TABLE `aviso_x_mora`
  ADD CONSTRAINT `aviso_x_mora_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `aviso_x_mora_ibfk_2` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `aviso_x_mora_ibfk_3` FOREIGN KEY (`id_tipo_aviso`) REFERENCES `tipo_aviso` (`id`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_perfil_credito`) REFERENCES `perfil_credito` (`id`),
  ADD CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id`),
  ADD CONSTRAINT `cliente_ibfk_4` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `cliente_ibfk_5` FOREIGN KEY (`id_titular`) REFERENCES `cliente` (`id`);

--
-- Filtros para la tabla `cliente_x_domicilio`
--
ALTER TABLE `cliente_x_domicilio`
  ADD CONSTRAINT `cliente_x_domicilio_ibfk_1` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`),
  ADD CONSTRAINT `fk_foreign_key_cliente_x_domicilio` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`);

--
-- Filtros para la tabla `cliente_x_telefono`
--
ALTER TABLE `cliente_x_telefono`
  ADD CONSTRAINT `cliente_x_telefono_ibfk_1` FOREIGN KEY (`id_telefono`) REFERENCES `telefono` (`id`),
  ADD CONSTRAINT `fk_foreign_key_cliente_x_telefono` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`);

--
-- Filtros para la tabla `consulta_estado_financiero`
--
ALTER TABLE `consulta_estado_financiero`
  ADD CONSTRAINT `consulta_estado_financiero_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_foreign_key_consulta_estado_financiero_adicional` FOREIGN KEY (`tipo_documento_adicional`, `documento_adicional`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `fk_foreign_key_consulta_estado_financiero_cadena` FOREIGN KEY (`id_cadena`) REFERENCES `cadena` (`id`);

--
-- Filtros para la tabla `credito`
--
ALTER TABLE `credito`
  ADD CONSTRAINT `credito_ibfk_1` FOREIGN KEY (`id_plan_credito`) REFERENCES `plan_credito` (`id`);

--
-- Filtros para la tabla `credito_cliente`
--
ALTER TABLE `credito_cliente`
  ADD CONSTRAINT `credito_cliente_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_5` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_6` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_foreign_key_credito_cliente` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `fk_foreign_key_credito_cliente_adicional` FOREIGN KEY (`tipo_documento_adicional`, `documento_adicional`) REFERENCES `cliente` (`tipo_documento`, `documento`);

--
-- Filtros para la tabla `cuota_credito`
--
ALTER TABLE `cuota_credito`
  ADD CONSTRAINT `cuota_credito_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `cuota_credito_ibfk_2` FOREIGN KEY (`usuario_registro_pago`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `dato_laboral_x_cliente`
--
ALTER TABLE `dato_laboral_x_cliente`
  ADD CONSTRAINT `dato_laboral_x_cliente_ibfk_2` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id`),
  ADD CONSTRAINT `fk_foreign_key_dato_laboral_x_cliente` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`);

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
-- Filtros para la tabla `envio_sms`
--
ALTER TABLE `envio_sms`
  ADD CONSTRAINT `envio_sms_ibfk_1` FOREIGN KEY (`id_aviso_x_mora`) REFERENCES `aviso_x_mora` (`id`),
  ADD CONSTRAINT `envio_sms_ibfk_2` FOREIGN KEY (`id_telefono`) REFERENCES `telefono` (`id`);

--
-- Filtros para la tabla `estado_cliente`
--
ALTER TABLE `estado_cliente`
  ADD CONSTRAINT `estado_cliente_ibfk_2` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`),
  ADD CONSTRAINT `estado_cliente_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `estado_cliente_ibfk_4` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `horario_laboral_x_usuario`
--
ALTER TABLE `horario_laboral_x_usuario`
  ADD CONSTRAINT `horario_laboral_x_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `interes_x_mora`
--
ALTER TABLE `interes_x_mora`
  ADD CONSTRAINT `interes_x_mora_ibfk_1` FOREIGN KEY (`id_plan_credito`) REFERENCES `plan_credito` (`id`);

--
-- Filtros para la tabla `interes_x_mora_cuota_credito`
--
ALTER TABLE `interes_x_mora_cuota_credito`
  ADD CONSTRAINT `interes_x_mora_cuota_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `interes_x_mora_cuota_credito_ibfk_2` FOREIGN KEY (`id_plan_credito`) REFERENCES `plan_credito` (`id`);

--
-- Filtros para la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_n1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`);

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
-- Filtros para la tabla `pago_parcial_cuota_credito`
--
ALTER TABLE `pago_parcial_cuota_credito`
  ADD CONSTRAINT `pago_parcial_cuota_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `pago_parcial_cuota_credito_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `pago_parcial_cuota_credito_ibfk_3` FOREIGN KEY (`supervisor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `pago_seleccion_cuotas_credito`
--
ALTER TABLE `pago_seleccion_cuotas_credito`
  ADD CONSTRAINT `pago_seleccion_cuotas_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `pago_seleccion_cuotas_credito_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `pago_total_credito`
--
ALTER TABLE `pago_total_credito`
  ADD CONSTRAINT `pago_total_credito_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `pago_total_credito_ibfk_2` FOREIGN KEY (`supervisor`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `pago_total_credito_ibfk_3` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`);

--
-- Filtros para la tabla `pago_total_credito_x_cuota`
--
ALTER TABLE `pago_total_credito_x_cuota`
  ADD CONSTRAINT `pago_total_credito_x_cuota_ibfk_1` FOREIGN KEY (`id_pago_total_credito`) REFERENCES `pago_total_credito` (`id`),
  ADD CONSTRAINT `pago_total_credito_x_cuota_ibfk_2` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`);

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
-- Filtros para la tabla `token_adicional_cuenta`
--
ALTER TABLE `token_adicional_cuenta`
  ADD CONSTRAINT `fk_foreign_key_token_adicional_cuenta_titular` FOREIGN KEY (`tipo_documento_titular`, `documento_titular`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `token_adicional_cuenta_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_adicional_cuenta_ibfk_2` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `token_anulacion_cuota_credito`
--
ALTER TABLE `token_anulacion_cuota_credito`
  ADD CONSTRAINT `token_anulacion_cuota_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `token_anulacion_cuota_credito_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `token_autorizacion_supervisor`
--
ALTER TABLE `token_autorizacion_supervisor`
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_1` FOREIGN KEY (`autorizante`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_2` FOREIGN KEY (`autorizado`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_3` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`);

--
-- Filtros para la tabla `token_cambio_cuenta`
--
ALTER TABLE `token_cambio_cuenta`
  ADD CONSTRAINT `fk_foreign_key_token_cambio_cuenta` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `token_cambio_cuenta_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_cambio_cuenta_ibfk_2` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `token_cambio_estado_cuota`
--
ALTER TABLE `token_cambio_estado_cuota`
  ADD CONSTRAINT `fk_foreign_key_token_cambio_estado_cuota` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_3` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_4` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_5` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`);

--
-- Filtros para la tabla `token_pago_cuota`
--
ALTER TABLE `token_pago_cuota`
  ADD CONSTRAINT `fk_foreign_key_token_pago_cuota` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`),
  ADD CONSTRAINT `token_pago_cuota_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_pago_cuota_ibfk_3` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_pago_cuota_ibfk_4` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`);

--
-- Filtros para la tabla `token_validacion_celular`
--
ALTER TABLE `token_validacion_celular`
  ADD CONSTRAINT `token_validacion_celular_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`);

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

--
-- Filtros para la tabla `usuario_x_telefono`
--
ALTER TABLE `usuario_x_telefono`
  ADD CONSTRAINT `usuario_x_telefono_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `usuario_x_telefono_ibfk_2` FOREIGN KEY (`id_telefono`) REFERENCES `telefono` (`id`);

--
-- Filtros para la tabla `verificacion_datos_cliente`
--
ALTER TABLE `verificacion_datos_cliente`
  ADD CONSTRAINT `verificacion_datos_cliente_ibfk_1` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
