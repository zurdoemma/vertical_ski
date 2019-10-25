-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2019 a las 21:19:34
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `aviso_x_mora`
--

INSERT INTO `aviso_x_mora` (`id`, `id_credito`, `fecha`, `estado`, `id_cuota_credito`, `mensaje`, `id_tipo_aviso`, `fecha_modificacion`, `comentario`) VALUES
(1, 27, '20190806141410', 'Finalizado', 77, 'Regularice su situación.', 1, NULL, NULL),
(4, 27, '20190813143009', 'Finalizado', 77, 'Se informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190816165730', 'El mensaje fue enviado correctamente!!'),
(6, 27, '20190116104717', 'Error', 77, 'sasas', 1, '20190116121613', 'No hay un envío de SMS relacionado al aviso por mora!!'),
(7, 27, '20190219123226', 'Finalizado', 77, 'Se recuerda que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190219123535', 'El mensaje fue enviado correctamente!!'),
(8, 27, '20190819124203', 'Finalizado', 77, 'Se recuerda que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00.', 1, '20190819124648', 'El mensaje fue enviado correctamente!!'),
(9, 27, '20190902141138', 'Creado', 77, 'PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $313,44.', 1, '20190902142639', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=27 ;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo_documento`, `documento`, `nombres`, `apellidos`, `cuil_cuit`, `fecha_nacimiento`, `email`, `fecha_alta`, `estado`, `id_titular`, `observaciones`, `monto_maximo_credito`, `id_perfil_credito`, `id_genero`) VALUES
(1, 1, '30443194', 'Fernando', 'Budasi', 20304431945, '19880501000000', 'fer@gmail.com', '20190520095955', 'Habilitado', NULL, '---', 5000, 1, 1),
(2, 1, '32443194', 'Bernardo', 'Arenga', 20324431945, '19780213000000', 'ferareng@gmail.com', '20190522175000', 'Habilitado', NULL, 'Ninguna', 1000000, 2, 1),
(14, 1, '35443194', 'Pedro', 'Decara', 20354431948, '19650305000000', 'pdecara@decarasa.com.ar', '20190606163942', 'Deshabilitado', NULL, 'Presenta documento borroso.', 450000, 2, 1),
(15, 1, '37443194', 'Adivino', 'Vividor', 20374431946, '19850131000000', '---', '20190606170045', 'Deshabilitado', 14, 'Nada', 245600, 1, 1),
(17, 1, '41443194', 'Servian', 'Juere', 20414431945, '19260730000000', '---', '20190607110919', 'Habilitado', NULL, 'asas', 550000, 1, 1),
(18, 1, '42443194', 'asas', 'asas', 20424431948, '19910818000000', '---', '20190607111212', 'Deshabilitado', 17, 'asas', 122222, 1, 1),
(21, 1, '45443194', 'ASASAS', 'asasas', 20454431944, '19870404000000', '---', '20190607121436', 'Deshabilitado', 17, 'asasas', 100000, 1, 1),
(22, 1, '47898452', 'asas', 'JKLJKL', 23478984525, '19860307000000', '---', '20190607124036', 'Habilitado', NULL, 'ASASAS', 544444, 1, 1),
(23, 1, '50443194', 'asa', 'asas', 121212, '19870404000000', '---', '20190607124532', 'Habilitado', 17, 'asas', 499999, 1, 1),
(24, 1, '51443194', 'asas', 'klkl', 20514431945, '19900719000000', '---', '20190607140124', 'Habilitado', NULL, 'asas', 495000, 1, 1),
(25, 1, '87654321', 'Pruebas Errores', 'Ejemplo', 20876543215, '19880101000000', 'linkinlinkin@gmail.com', '20190708104913', 'Habilitado', NULL, 'Ker.', 350010, 1, 1),
(26, 1, '89443194', 'asas', 'asas', 204433221, '19931228000000', 'asas@gmail.com', '20190829103810', 'Habilitado', NULL, 'asasas', 20000, 1, 1);

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
(89, 1, '89443194', b'1');

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
(63, 1, '89443194', b'1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `consulta_estado_financiero`
--

INSERT INTO `consulta_estado_financiero` (`id`, `tipo_documento`, `documento`, `fecha`, `resultado_xml`, `usuario`, `cuit_cuil`, `token`, `validado`, `tipo_documento_adicional`, `documento_adicional`, `id_cadena`) VALUES
(5, 1, '87654321', '20180607111439', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>ICBC - INDUSTRIAL AND COMMERCIONAL BANK OF CHINA</entidad><situacion>3</situacion><monto_maximo>10000.0000000000</monto_maximo><deuda_actual>10000.0000000000</deuda_actual><fecha>05/04/2017</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'admin_sys', 20876543215, 'ab05eaab9673f62d016d86e125ecf9ddcef363d2f197034caca97b304029d9333a28555a6cb838e754d6179ddabdd9c9500139893f3c51583665fb0b40bfb34d', b'1', NULL, NULL, 5),
(6, 1, '87654321', '20190710104717', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7fedcceafd3333a489c7f9c34d8db9bb25bc9c8987338c116129757c9d82a3e8b1fb0b8f0b698d3f2128e404e7ba112cd02172271eb1d77e5cd658adabb387f1', b'1', NULL, NULL, NULL),
(8, 1, '87654321', '20190821155006', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>1</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7d0a5ee043ad8ee3fdef0726c3990057cc66a7dca615d8dd41cd4548fc19c94c39cd3ad345c14fb43ebdb39e4064b36db7c27e92a59deb5a42fd925eea3b7893', b'1', NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_ejecucion_procesos`
--

CREATE TABLE IF NOT EXISTS `control_ejecucion_procesos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_proceso` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `control_ejecucion_procesos`
--

INSERT INTO `control_ejecucion_procesos` (`id`, `fecha`, `tipo_proceso`) VALUES
(15, '20190902142639', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=47 ;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id`, `cantidad_cuotas`, `monto_compra`, `id_plan_credito`, `interes_fijo_plan_credito`, `monto_credito_original`, `estado`, `abona_primera_cuota`, `minimo_entrega`) VALUES
(4, 3, 160000, 4, 10, 176000, 'Cancelada', b'0', 0),
(27, 3, 63320, 4, 10, 69652, 'Pendiente', b'0', 0),
(28, 6, 36000, 5, 20, 43200, 'Pendiente', b'0', 0),
(29, 3, 150000, 4, 10, 165000, 'Pendiente', b'0', 0),
(30, 3, 200000, 4, 10, 220000, 'Pendiente', b'0', 0),
(37, 1, 123456, 13, 45, 179011, 'Cancelada', b'0', 14815),
(38, 1, 145025, 13, 45, 210286, 'Pendiente', b'1', 0),
(42, 1, 30800, 13, 45, 44660, 'Pendiente', b'0', 4200),
(43, 1, 31239, 13, 45, 45298, 'Pendiente', b'0', 4260),
(44, 1, 35600, 13, 45, 51620, 'Pendiente', b'1', 0),
(45, 1, 31327, 13, 45, 45426, 'Pendiente', b'0', 4272),
(46, 1, 32560, 13, 45, 47212, 'Pendiente', b'0', 4440);

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
(46, '20190926153455', 1, '32443194', 'supervisor', 2, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=107 ;

--
-- Volcado de datos para la tabla `cuota_credito`
--

INSERT INTO `cuota_credito` (`id`, `id_credito`, `numero_cuota`, `fecha_vencimiento`, `monto_cuota_original`, `estado`, `fecha_pago`, `monto_pago`, `usuario_registro_pago`) VALUES
(10, 4, 1, '20190812235959', 58667, 'En Mora', NULL, NULL, NULL),
(11, 4, 2, '20190911235959', 58667, 'Pendiente', NULL, NULL, NULL),
(12, 4, 3, '20191011235959', 58666, 'Pendiente', NULL, NULL, NULL),
(76, 27, 1, '20190813235959', 23217, 'Pagada', '20190731145915', 23217, 'her'),
(77, 27, 2, '20190702235959', 23217, 'En Mora', NULL, NULL, NULL),
(78, 27, 3, '20191014235959', 23218, 'Pendiente', NULL, NULL, NULL),
(79, 28, 1, '20190920235959', 7200, 'Pendiente', NULL, NULL, NULL),
(80, 28, 2, '20191021235959', 7200, 'Pendiente', NULL, NULL, NULL),
(81, 28, 3, '20191120235959', 7200, 'Pendiente', NULL, NULL, NULL),
(82, 28, 4, '20191220235959', 7200, 'Pendiente', NULL, NULL, NULL),
(83, 28, 5, '20200120235959', 7200, 'Pendiente', NULL, NULL, NULL),
(84, 28, 6, '20200219235959', 7200, 'Pendiente', NULL, NULL, NULL),
(85, 29, 1, '20191002235959', 55000, 'Pendiente', NULL, NULL, NULL),
(86, 29, 2, '20191101235959', 55000, 'Pendiente', NULL, NULL, NULL),
(87, 29, 3, '20191202235959', 55000, 'Pendiente', NULL, NULL, NULL),
(88, 30, 1, '20191002235959', 73333, 'Pagada', '20190917125824', 73333, 'supervisor'),
(89, 30, 2, '20191101235959', 73333, 'Pendiente', NULL, NULL, NULL),
(90, 30, 3, '20191202235959', 73334, 'Pendiente', NULL, NULL, NULL),
(97, 37, 1, '20191001235959', 179011, 'Cancelada', NULL, NULL, NULL),
(98, 38, 1, '20191028235959', 210286, 'Pagada', '20190926151224', 210286, 'supervisor'),
(102, 42, 1, '20191028235959', 44660, 'Pendiente', NULL, NULL, NULL),
(103, 43, 1, '20191028235959', 45298, 'Pendiente', NULL, NULL, NULL),
(104, 44, 1, '20191028235959', 51620, 'Pagada', '20190926152947', 51620, 'supervisor'),
(105, 45, 1, '20191028235959', 45426, 'Pendiente', NULL, NULL, NULL),
(106, 46, 1, '20191028235959', 47212, 'Pendiente', NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=94 ;

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
(54, 'ferr', 1212, 1, 'lklñksss', '---', 1, '---', '---', '---'),
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
(93, 'asas', 123, 1, 'asasas', '---', 0, '---', '---', '---');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=92 ;

--
-- Volcado de datos para la tabla `ejecucion_procesos_auto`
--

INSERT INTO `ejecucion_procesos_auto` (`id`, `fecha`, `comentario`, `tipo`) VALUES
(91, '20190902142639', 'El proceso automatico se ejecuto correctamente!!', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `envio_sms`
--

INSERT INTO `envio_sms` (`id`, `id_aviso_x_mora`, `id_telefono`, `estado`, `comentario`, `codigo_respuesta`, `fecha`, `fecha_modificacion`, `cantidad_reintentos`, `id_sms`) VALUES
(1, 4, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190816120750', '20190816165730', 3, '1565984370.5673'),
(2, 7, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190819123404', '20190819123535', 0, '1566228697.9553'),
(3, 8, 60, 'Finalizado', 'El mensaje fue enviado correctamente!!', 200, '20190819124602', '20190819124648', 1, '1566229415.4304');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=69 ;

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
(62, 1, '32443194', '20191014102710', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, '78e71106d2c004275a5bf01eaaaa54d1b5c4c5d98570f2a46623b226cf50a67a3b44dac9f4a7907705ea8e72913eaad4e7f2d0e09219b73dac77d196845bd936'),
(63, 1, '32443194', '20191014103017', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'e780771547464a3897e7e2c958ac16f160dd1ae072350ba0544071d96106288dc7fb817fa6bce3fd73e57c3441d3ada9782cd6c8145b098ac2d61810193b0add'),
(66, 1, '30443194', '20191024165449', 56, NULL, 'her', 'supervisor', NULL, NULL, 3513897898, 'a53f7fbc6f9f4f9fbef885acc10a9c3c0592a86028b1d074738283453e396281935cd94810389e8352240ec297a80b3316581d870e8abf7b180ece55fce350fc'),
(67, 1, '30443194', '20191025135253', 56, NULL, 'her', 'supervisor', NULL, NULL, 3513897898, '634f9bb76296494cd7fc99465a25b4591bd784223039f317d57d30ba84f310188fe6880c61a4e2d728a487f8da8900dd3994ce8ea0dd2d593251fa77627a62cd'),
(68, 1, '47898452', '20191025135400', 56, NULL, 'her', 'supervisor', NULL, NULL, 3513343434, 'bd0834421cc1347184b385d01d0a82df01c175e4cc0919da0caab651c5d2132d707874fa0bbe845de0a3164be17ed7beb8f36fcbeb8e5c7794917b2ff5ea478c');

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
('her', '20190904013000', '20190904030000', b'0', b'1', b'1', b'1', b'1', b'1', b'0', b'1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `interes_x_mora_cuota_credito`
--

INSERT INTO `interes_x_mora_cuota_credito` (`id`, `fecha`, `id_cuota_credito`, `cantidad_dias_mora`, `interes_x_mora`, `id_plan_credito`, `cantidad_dias_en_mora`, `recurrente`) VALUES
(9, '20190802151839', 77, 30, 5, 4, 30, b'1'),
(10, '20190802162146', 77, 30, 5, 4, 30, b'1'),
(11, '20190902142639', 77, 30, 5, 4, 61, b'1'),
(12, '20190813143009', 77, 60, 10, 4, 61, b'0'),
(13, '20190813150207', 77, 30, 5, 4, 61, b'0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1426 ;

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
(1353, 'supervisor', '20191002123604', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 12:36:04'),
(1354, 'supervisor', '20191002124211', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-02 12:42:11'),
(1355, 'supervisor', '20191002124217', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-02 12:42:17'),
(1356, 'supervisor', '20191003130155', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-03 13:01:55'),
(1357, 'supervisor', '20191014102651', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-14 10:26:51'),
(1358, 'supervisor', '20191014103036', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-14 10:30:36'),
(1359, 'supervisor', '20191022161902', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-22 16:19:02'),
(1360, 'supervisor', '20191022175527', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-22 17:55:27'),
(1361, 'supervisor', '20191023175404', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-23 17:54:04'),
(1362, 'supervisor', '20191023175707', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191023175707,0,6720d38efa70cd2b4da4b47409ffc98620e0c829cb1d1614d829ca5d41a643bcdd836a4786ea15a26870c63b0d9cbbfdf7fcddebc8cb439011637f8ca04076b5,29)'),
(1363, 'supervisor', '20191023180001', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191023180001,0,48b0ab2a6e0b155a5fa6fd1ce603498535961d3c0764e2043acdfec0704a8fd330979dbb753e53246a64e5c1e542e486c5bce352bcfe9798117b315e300cac66,15)'),
(1364, 'supervisor', '20191023180317', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191023180317,0,cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e,15)'),
(1365, 'supervisor', '20191023181227', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-23 18:12:27'),
(1366, 'supervisor', '20191024112713', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 11:27:13'),
(1367, 'supervisor', '20191024113142', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024113142,0,cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e,15)'),
(1368, 'supervisor', '20191024114015', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024114015,0,adf0e9dad9a22e71ec7613e325146fbefb860d0afe94abffeb74fa210d92c94e3ab6f8f08a2fbd0a830cbfa9a113b318c2d0445cbf8368968e762fe0ec33e7ee,15)');
INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(1369, 'supervisor', '20191024114159', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024114159,0,1bf525db8a8ac715cb4f11307939a4ed64bca51714377b418a7778a010e04903f6e1924fce3d12be723158a5a20d9e7d2c99e3be3ec1e487275e257ac8186ed8,15)'),
(1370, 'supervisor', '20191024114304', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024114304,0,f8918c3e39689ee34c986d1cdd4d104b4c1dad978805447f5fd438bbb9d4ebc5574fcdaf1a1b1a21d198ddf045bad817c71b74b16043a9afde14605d63d54492,15)'),
(1371, 'supervisor', '20191024114441', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024114441,0,a11504bdadb551d8901b1b1a752cbab18d68568b56a5b7f502fdb1096edc2672f52e79581c32e30823d018acc858f7b5b136aaedf89c53043eb07f69cd254d43,15)'),
(1372, 'supervisor', '20191024114946', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024114946,0,686d2a53e39cba4e8289455d308f6d615656eab656b0f7548929244878ce0974205dd82f8bbd501b0c959f24db6ac4126254ef4853a83422482b01e811aeee27,15)'),
(1373, 'supervisor', '20191024115729', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024115729,0,9d35fff4db3527a4a1b39d278da3541b76c331a7b443ee4d737ba9974722cc873e04db48747ca81e1887a8bda98d1c1b74b2c26a3cbbabc5e2ccf22d411b7f66,15)'),
(1374, 'supervisor', '20191024115846', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024115846,0,10b2adb7798ebff0cf69c2ef56f6b98c82aad59874e98025f1d86656bc353ad7afed25e82fbdac8841b70995f841964fb70f9cb04617911e3a6f03e346ad2124,15)'),
(1375, 'supervisor', '20191024115932', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024115932,0,363f04c5437141162860bf390bea3baa7090772429fc2a76563c1638d3a837697f38a7e2576605066bc7f62a77e8280467629365f30dadc69133a86a7615a8b4,15)'),
(1376, 'supervisor', '20191024120042', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120042,0,c1ed9506b96594e07667288862ad6767fbe8777aad6384b158f608a62eaecff615a8ce6a8c9f2d9c8ee55bbe43e2a79d97010353d6c61f89ba84b4094d1147b4,15)'),
(1377, 'supervisor', '20191024120131', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120131,0,d805589db7857625f16a75e6eec109065c966b63a599bce53c63862e7a32674fb2f7fc485cf6df6a71989aab08c9cb57b91d0158c2fe9ec4e4555dc3505304ef,15)'),
(1378, 'supervisor', '20191024120523', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120523,0,22a0111bf52dff34557c3c9dd8565e49aa9350230093973ac13e7decdce27cba83ff5893016c45034cdd888e31a2c288e9a7070c8ba5433ac59cdc9839b3af4b,15)'),
(1379, 'supervisor', '20191024120642', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120642,0,4771e763c0e0fa0ef5162a78e0d857e1fd107d55b08311c9c0453bfceb64932f2cc5dd29b36ae1a02ad6921c8c4beb27b1ec2aecb44410d8b1e1dba4e73649dd,15)'),
(1380, 'supervisor', '20191024120704', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120704,0,db1a1812435846ceaca8dfb04d3206f904b97dcbb7b2b8e6ae7b48d4231c5b21850a35d83d5774e48e6ad5a147af5b4d49e31c49734be31b532b37c53e8f1c80,15)'),
(1381, 'supervisor', '20191024120742', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120742,0,90df8ce7afa76f340340ffdc248e89073ed8fa5161c42e3671d3e2bdc69def2782e4a5c342694e8a8bf03122a311c4805a0493bd6ac36cbaa465e824aa8e395f,15)'),
(1382, 'supervisor', '20191024120847', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120847,0,bd81078f8b8961ac07bce03cd9304246263950cdfdc11b7d9ebe43ddea51084241666283c547c4d7dd222b8618a5c6ffda81a4d1729f36abaaefaf72441a9a29,15)'),
(1383, 'supervisor', '20191024120931', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024120931,0,c75c5f2741a41d53e54c3eff7d520756b2703aa6213d2d232eb5ce82fcaf4033b8de840d550be70f85bc625995cdf29cca6612d405eaff26a948f098bc01a8aa,15)'),
(1384, 'supervisor', '20191024121200', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024121200,0,164af2d93238d6d24ca1b84c47b2d6b4093cd1e088c1096d8a8be8541aebf61c992119995b18649c8d7b17c5fcfd14a5f0481ca59edea17f5607fcecce768263,15)'),
(1385, 'supervisor', '20191024145154', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:51:54'),
(1386, 'supervisor', '20191024145200', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024145200,0,cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e,15)'),
(1387, 'supervisor', '20191024145315', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024145315,0,7031b8366c2bcef45cb32304ffcc01df0d7aca873ceff9fce741bcf88e5a76e0971c493d012491cd32d272a1dcb3cf3a23f0d189226041118ac3abfa8e333595,15)'),
(1388, 'supervisor', '20191024145347', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 14:53:47'),
(1389, 'her', '20191024145351', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:53:51'),
(1390, 'her', '20191024145737', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 14:57:37'),
(1391, 'supervisor', '20191024145745', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:57:45'),
(1392, 'supervisor', '20191024145808', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191024145808,0,6f5771176fa833af723b7b8470667b348ab8eed3f52236eff0a6ae941b656a7282ebed6b72d63c9e567d3aecd7f86b3f626a7afbfc37d82be87051f53f9b38a0,15)'),
(1393, 'supervisor', '20191024145823', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 14:58:23'),
(1394, 'supervisor', '20191024145828', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:58:28'),
(1395, 'supervisor', '20191024145835', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 14:58:35'),
(1396, 'her', '20191024145839', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:58:39'),
(1397, 'her', '20191024145839', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 14:58:39'),
(1398, 'her', '20191024163435', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 16:34:35'),
(1399, 'supervisor', '20191024163442', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 16:34:42'),
(1400, 'supervisor', '20191024163447', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,aassad,20191024163447,0,5dc9d73f349b7039d5b4490ea8e594bea627c13bd7ccad7b0b8e6d6b20ed94d4ebe81bfa86a2e2ccbe458675a503fdf163be58d4b4b866762ef16e1505fe5c5c,15)'),
(1401, 'supervisor', '20191024163451', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191024163451,0,9e9c8f37bcf664863de921c9db318c94fe6dddbf5a9fc74e606001e0adeea4895da07f4a7aca98219a4a939797aac6e136d65f81be649c68b34a65265deb8825,15)'),
(1402, 'supervisor', '20191024163459', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 16:34:59'),
(1403, 'her', '20191024163505', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 16:35:05'),
(1404, 'her', '20191024163505', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 16:35:05'),
(1405, 'her', '20191024164002', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 16:40:02'),
(1406, 'supervisor', '20191024164050', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 16:40:50'),
(1407, 'supervisor', '20191024164106', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191024164106,0,072e43e36f41f264ea1ea346ef2e76716d98590f315402346566437c8eb965c7eecab710b39c2e1ec4a03d236975a6981b0122d5c9a3ae14469c5fd54cc9aa6a,15)'),
(1408, 'supervisor', '20191024164117', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 16:41:17'),
(1409, 'her', '20191024164122', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-24 16:41:22'),
(1410, 'her', '20191024165049', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1411, 'her', '20191024165057', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1412, 'her', '20191024165341', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1413, 'her', '20191024165400', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1414, 'her', '20191024165449', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1415, 'her', '20191024165537', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-24 16:55:37'),
(1416, 'supervisor', '20191025135108', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 13:51:08'),
(1417, 'supervisor', '20191025135136', 100, 'INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (supervisor,her,20191025135136,0,18fc94de84258e097a8ef77eae0c94964cc147e4d880a665aaf8086ee118cfb0d8dca086d595b6426c1e002067f0cb518c4440cd646b9e0568935333f358e4c1,15)'),
(1418, 'supervisor', '20191025135151', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 13:51:51'),
(1419, 'her', '20191025135226', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 13:52:26'),
(1420, 'her', '20191025135253', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1421, 'her', '20191025135303', 43, 'ANTERIOR: id = 61, tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513897898, digitos_prefijo = 3, preferido = 1 WHERE id =61'),
(1422, 'her', '20191025135400', 43, 'ANTERIOR: id = 57, tipo_telefono = 1, numero = 3513343434, digitos_prefijo = 3, preferido = 1  -- NUEVO: UPDATE finan_cli.telefono SET tipo_telefono = 1, numero = 3513343434, digitos_prefijo = 3, preferido = 1 WHERE id =57'),
(1423, 'supervisor', '20191025154853', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 15:48:53'),
(1424, 'supervisor', '20191025154853', 1, 'Inicio de Sesion en Fecha y Hora: 2019-10-25 15:48:53'),
(1425, 'supervisor', '20191025154900', 2, 'Cierre de Sesion en Fecha y Hora: 2019-10-25 15:49:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `mora_cuota_credito`
--

INSERT INTO `mora_cuota_credito` (`id`, `id_cuota_credito`, `fecha_interes`, `monto_interes`, `porcentaje_interes`) VALUES
(1, 77, '20190813143009', 2322, 10),
(2, 77, '20190813150207', 1161, 5),
(4, 77, '20190902141839', 1161, 5),
(5, 77, '20190902142146', 1161, 5),
(6, 77, '20190902142639', 1161, 5);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
(2, 13);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `plan_credito`
--

INSERT INTO `plan_credito` (`id`, `nombre`, `descripcion`, `cantidad_cuotas`, `interes_fijo`, `id_tipo_diferimiento_cuota`, `id_cadena`, `minimo_entrega`) VALUES
(4, 'Plan 3 Cuotas Clasico', 'Es un plan de 3 cuotas clásico con diferimiento de cuota estricto.', 3, 10, 6, 5, 0),
(5, 'Plan 6 Cuotas Clasico', 'Es un plan de 6 cuotas clásico con un diferimiento estricto.', 6, 20, 5, 5, 0),
(10, 'PLAN 2 PAGOS', '2 pagos en tiempo estricto.', 2, 3, 5, 1, 0),
(13, 'Plan 12', 'asas', 1, 45, 5, 5, 12);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=67 ;

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
(66, 2, 3514742046, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `token_anulacion_credito`
--

INSERT INTO `token_anulacion_credito` (`id`, `fecha`, `id_credito`, `usuario`, `token`, `comentario`) VALUES
(5, '20190906234038', 37, 'supervisor', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'llñkñlkñl');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=41 ;

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
(40, '20190917150901', 89, 'supervisor', 'ef95df0ef5a031b05b8f2aeea7de5508cfdb932bc81f20f419a642bb1e83960799e46c4d0df803a47fb1f5ec9bf18f403d0eeaf503023fe337257c10d7b764bb', 'asasa', 'S');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=29 ;

--
-- Volcado de datos para la tabla `token_autorizacion_supervisor`
--

INSERT INTO `token_autorizacion_supervisor` (`id`, `autorizante`, `autorizado`, `fecha`, `fecha_utilizacion`, `utilizado`, `token`, `id_motivo`, `duracion`) VALUES
(27, 'supervisor', 'her', '20191024164106', '20191024165341', b'1', '072e43e36f41f264ea1ea346ef2e76716d98590f315402346566437c8eb965c7eecab710b39c2e1ec4a03d236975a6981b0122d5c9a3ae14469c5fd54cc9aa6a', 56, 15),
(28, 'supervisor', 'her', '20191025135136', '20191025135253', b'1', '18fc94de84258e097a8ef77eae0c94964cc147e4d880a665aaf8086ee118cfb0d8dca086d595b6426c1e002067f0cb518c4440cd646b9e0568935333f358e4c1', 56, 15);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `token_cambio_estado_cuota`
--

INSERT INTO `token_cambio_estado_cuota` (`id`, `fecha`, `documento`, `tipo_documento`, `token`, `usuario`, `usuario_supervisor`, `validado`, `id_motivo`, `id_cuota_credito`, `estado_anterior`, `estado_nuevo`) VALUES
(4, '20190807124035', '87654321', 1, '66a1ef7be2a558d7f414fa942dc17c5b6023c02afa083ddf03c84e1a3a5786410777ebf672f0ecd27125677a23c695bc6359e267888666ec0ac403b2fc500839', 'her', NULL, b'1', 82, 77, 'En Mora', 'Incobrable'),
(9, '20190807144732', '87654321', 1, '1d487cbd778e47d16f34499f5b9847125e0a5ac340b0005f51aa57c4bab9036f66328d3460330d04e915767f6564a34a3ba298e6145b81ee3c9964e8671540c8', 'her', 'supervisor', b'1', 82, 78, 'En Mora', 'Condonada');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=31 ;

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
(30, '20190917150840', '32443194', 1, '9986e4faef3301c4bbde81e2346d4b5054e41353328483af70b2ede0252ec3d4813775d1d79eb30bb3e7fb90793b9888b16ee2131f18c547581bbcd91bbec2cc', 'supervisor', NULL, b'1', 73);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `token_validacion_celular`
--

INSERT INTO `token_validacion_celular` (`id`, `Fecha`, `tipo_documento`, `documento`, `token`, `codigo`, `usuario`, `validado`, `nro_telefono`) VALUES
(18, '20190703123017', 1, '87654321', 'e31ea8d4524787260fe99af89edaf669d2cc51f84b684484a25f0e9064c63c090039ad2bca438bb8473935cc81a321277c0f32cafd19bdbcebc0779b872dbcd7', '0274', 'her', b'1', NULL),
(20, '20190704214649', 1, '87654321', '86ac66801d194a03492f867193181c8ef35528c9e4409b92050de053e9b3d96078891e873371c329b9223687264c8b105dc079113c66fa16164a0a9cf9b3c066', '4802', 'her', b'1', 3513827932),
(21, '20190705125929', 1, '87654321', 'f924c62e712167f431a1c387112587baab51c38e127fc866f8b40a64a8c822d5f78e143c05a87f59bcfddf65882b424632d05b36a84bc91a6e738b386ebd259e', '7282', 'her', b'1', 3513827932),
(22, '20190708104817', 1, '87654321', '87f687a3e6eb030b9757f098df3eb7cb950ea8edf14f889152dae3934bb303acfb234d6936914341be25f5b3f9f37994663aae73851419adaf2cea6fc9413b13', '8096', 'her', b'1', NULL),
(24, '20190829102811', 1, '89443194', 'bc85226786ced3d7520ccfe3ae91a0f5d849b1394bde02c559e8b25c8c8659e4ef2a4bd8bf9b3992574c73ceacd4aa77fcd8095958bf49f88b48ff5dbdede15c', '9511', 'her', b'1', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `fk_foreign_key_consulta_estado_financiero` FOREIGN KEY (`tipo_documento`, `documento`) REFERENCES `cliente` (`tipo_documento`, `documento`),
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
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`);

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
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_3` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`),
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_1` FOREIGN KEY (`autorizante`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_autorizacion_supervisor_ibfk_2` FOREIGN KEY (`autorizado`) REFERENCES `usuario` (`id`);

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
