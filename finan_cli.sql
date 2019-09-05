-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2019 a las 22:57:03
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
  PRIMARY KEY (`id`),
  KEY `tipo_documento` (`tipo_documento`),
  KEY `documento` (`documento`),
  KEY `tipo_documento_adicional` (`tipo_documento_adicional`),
  KEY `usuario` (`usuario`),
  KEY `fk_foreign_key_consulta_estado_financiero` (`tipo_documento`,`documento`),
  KEY `fk_foreign_key_consulta_estado_financiero_adicional` (`tipo_documento_adicional`,`documento_adicional`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `consulta_estado_financiero`
--

INSERT INTO `consulta_estado_financiero` (`id`, `tipo_documento`, `documento`, `fecha`, `resultado_xml`, `usuario`, `cuit_cuil`, `token`, `validado`, `tipo_documento_adicional`, `documento_adicional`) VALUES
(5, 1, '87654321', '20180607111439', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>ICBC - INDUSTRIAL AND COMMERCIONAL BANK OF CHINA</entidad><situacion>3</situacion><monto_maximo>10000.0000000000</monto_maximo><deuda_actual>10000.0000000000</deuda_actual><fecha>05/04/2017</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'admin_sys', 20876543215, 'ab05eaab9673f62d016d86e125ecf9ddcef363d2f197034caca97b304029d9333a28555a6cb838e754d6179ddabdd9c9500139893f3c51583665fb0b40bfb34d', b'1', NULL, NULL),
(6, 1, '87654321', '20190710104717', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7fedcceafd3333a489c7f9c34d8db9bb25bc9c8987338c116129757c9d82a3e8b1fb0b8f0b698d3f2128e404e7ba112cd02172271eb1d77e5cd658adabb387f1', b'1', NULL, NULL),
(8, 1, '87654321', '20190821155006', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>21/08/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>1</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7d0a5ee043ad8ee3fdef0726c3990057cc66a7dca615d8dd41cd4548fc19c94c39cd3ad345c14fb43ebdb39e4064b36db7c27e92a59deb5a42fd925eea3b7893', b'1', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=38 ;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id`, `cantidad_cuotas`, `monto_compra`, `id_plan_credito`, `interes_fijo_plan_credito`, `monto_credito_original`, `estado`, `abona_primera_cuota`, `minimo_entrega`) VALUES
(4, 3, 160000, 4, 10, 176000, 'Pendiente', b'0', 0),
(27, 3, 63320, 4, 10, 69652, 'Pendiente', b'0', 0),
(28, 6, 36000, 5, 20, 43200, 'Pendiente', b'0', 0),
(29, 3, 150000, 4, 10, 165000, 'Pendiente', b'0', 0),
(30, 3, 200000, 4, 10, 220000, 'Pendiente', b'1', 0),
(37, 1, 123456, 13, 45, 179011, 'Pendiente', b'0', 14815);

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
(37, '20190901231540', 1, '32443194', 'supervisor', 2, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=98 ;

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
(88, 30, 1, '20191002235959', 73333, 'Pagada', '20190829165100', 73333, 'her'),
(89, 30, 2, '20191101235959', 73333, 'Pendiente', NULL, NULL, NULL),
(90, 30, 3, '20191202235959', 73334, 'Pendiente', NULL, NULL, NULL),
(97, 37, 1, '20191001235959', 179011, 'Pendiente', NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=93 ;

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
(92, 'qwqwqw', 123, 1, 'asasas', '---', 0, '---', '---', '---');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=37 ;

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
(36, 1, '32443194', '20190901231531', 58, NULL, 'supervisor', NULL, NULL, NULL, NULL, 'ce33c2bb5dfdf19bc28374eb41561a3f1a03f007777b3ccfd39f5d9a6a7ac8c8e7a43b852db7ddf0192eeb2dc484affbe0344b7380e49532bf5831d9abfe1198');

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
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `horario_laboral_x_usuario`
--

INSERT INTO `horario_laboral_x_usuario` (`id_usuario`, `horario_ingreso`, `horario_salida`, `lunes`, `martes`, `miercoles`, `jueves`, `viernes`, `sabado`, `domingo`) VALUES
('asa', '20190904100000', '20190904150000', b'1', b'1', b'1', b'1', b'1', b'1', b'1'),
('her', '20190904083000', '20190904180000', b'0', b'1', b'1', b'0', b'1', b'1', b'0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1077 ;

--
-- Volcado de datos para la tabla `log_usuario`
--

INSERT INTO `log_usuario` (`id`, `id_usuario`, `fecha`, `id_motivo`, `valor`) VALUES
(784, 'her', '20190807124743', 80, 'UPDATE finan_cli.cuota_credito SET estado = Incobrable WHERE id = 77'),
(785, 'her', '20190807132843', 80, 'UPDATE finan_cli.cuota_credito SET estado = Condonada WHERE id = 78'),
(786, 'her', '20190807133449', 80, 'UPDATE finan_cli.cuota_credito SET estado = Condonada WHERE id = 78'),
(787, 'her', '20190807133449', 81, 'UPDATE finan_cli.credito SET estado = Condonada WHERE id = 27'),
(788, 'her', '20190807142752', 80, 'UPDATE finan_cli.cuota_credito SET estado = Condonada WHERE id = 78'),
(789, 'her', '20190807142752', 81, 'UPDATE finan_cli.credito SET estado = Condonada WHERE id = 27'),
(790, 'her', '20190807144530', 80, 'UPDATE finan_cli.cuota_credito SET estado = Condonada WHERE id = 78'),
(791, 'her', '20190807144530', 81, 'UPDATE finan_cli.credito SET estado = Condonada WHERE id = 27'),
(792, 'her', '20190807144738', 80, 'UPDATE finan_cli.cuota_credito SET estado = Condonada WHERE id = 78'),
(793, 'her', '20190807144738', 81, 'UPDATE finan_cli.credito SET estado = Condonada WHERE id = 27'),
(794, 'her', '20190807162942', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-07 16:29:42'),
(795, 'her', '20190809162616', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-09 16:26:16'),
(796, 'her', '20190809170609', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-09 17:06:09'),
(824, 'admin_sys', '20190813142816', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-13 14:28:16'),
(825, 'admin_sys', '20190813142816', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-13 14:28:16'),
(826, 'admin_sys', '20190813142858', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (60,10,4)'),
(827, 'admin_sys', '20190813144834', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (30,5,4)'),
(828, 'admin_sys', '20190813145026', 83, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = En Mora WHERE id = 77 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente WHERE id = 77'),
(829, 'admin_sys', '20190813145141', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(830, 'admin_sys', '20190813150207', 85, 'ANTERIOR: UPDATE finan_cli.aviso_x_mora SET mensaje = Se informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $255,39. WHERE id = 4 -- NUEVO: UPDATE finan_cli.aviso_x_mora SET mensaje = Se informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $267,00. WHERE id = 4'),
(831, 'admin_sys', '20190813175911', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-13 17:59:11'),
(832, 'her', '20190820104032', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-20 10:40:32'),
(833, 'her', '20190820113159', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-20 11:31:59'),
(834, 'her', '20190820181411', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-20 18:14:11'),
(835, 'her', '20190821143213', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 14:32:13'),
(836, 'her', '20190821155330', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 15:53:30'),
(837, 'admin_sys', '20190821155336', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 15:53:36'),
(838, 'admin_sys', '20190821155403', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 15:54:03'),
(839, 'her', '20190821155424', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 15:54:24'),
(840, 'her', '20190821160202', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado) VALUES (6,36000,5,20,43200,Pendiente)'),
(841, 'her', '20190821160202', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (28,20190821160202,1,87654321,her,2)'),
(842, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,1,20190920235959,7200,Pendiente)'),
(843, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,2,20191021235959,7200,Pendiente)'),
(844, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,3,20191120235959,7200,Pendiente)'),
(845, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,4,20191220235959,7200,Pendiente)'),
(846, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,5,20200120235959,7200,Pendiente)'),
(847, 'her', '20190821160202', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (28,6,20200219235959,7200,Pendiente)'),
(848, 'her', '20190821160220', 66, 'Generación PDF de Crédito: 28'),
(849, 'her', '20190821164909', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 16:49:09'),
(850, 'admin_sys', '20190821164914', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 16:49:14'),
(851, 'admin_sys', '20190821172919', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:29:19'),
(852, 'supervisor', '20190821172925', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:29:25'),
(853, 'supervisor', '20190821173002', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:30:02'),
(854, 'supervisor', '20190821173018', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:30:18'),
(855, 'supervisor', '20190821173018', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:30:18'),
(856, 'supervisor', '20190821174107', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:41:07'),
(857, 'her', '20190821174113', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:41:13'),
(858, 'her', '20190821174120', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:41:20'),
(859, 'supervisor', '20190821174125', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:41:25'),
(860, 'supervisor', '20190821174813', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:48:13'),
(861, 'admin_sys', '20190821174824', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:48:24'),
(862, 'admin_sys', '20190821174845', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:48:45'),
(863, 'supervisor', '20190821174851', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:48:51'),
(864, 'supervisor', '20190821175054', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:50:54'),
(865, 'admin_sys', '20190821175101', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:51:01'),
(866, 'admin_sys', '20190821175625', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:56:25'),
(867, 'supervisor', '20190821175631', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-21 17:56:31'),
(868, 'supervisor', '20190821175936', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-21 17:59:36'),
(869, 'supervisor', '20190822102413', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-22 10:24:13'),
(870, 'supervisor', '20190822104445', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (Sinbawe,123,1,Kirko,---,NULL,5324,---,---)'),
(871, 'supervisor', '20190822104445', 8, 'INSERT INTO finan_cli.usuario (id,nombre,apellido,tipo_documento,documento,email,id_perfil,id_sucursal,estado) VALUES(secup,Nestor,Trion,1,56443194,ser@gmail.com,2,1,Habilitado)'),
(872, 'supervisor', '20190822110021', 7, 'ANTERIOR: id = secup, nombre = Nestor, apellido = Trion, tipo_documento = DNI, documento = 56443194, email = ser@gmail.com, perfil = Usuario Normal, sucursal = SISTEMAS  -- NUEVO: UPDATE finan_cli.usuario SET nombre = Nestor, apellido = Trion, tipo_documento = 1, documento = 56443194, email = ser@gmail.com, id_perfil = 3, id_sucursal = 1 WHERE id =secup'),
(873, 'supervisor', '20190822110031', 7, 'ANTERIOR: id = secup, nombre = Nestor, apellido = Trion, tipo_documento = DNI, documento = 56443194, email = ser@gmail.com, perfil = Supervisor, sucursal = SISTEMAS  -- NUEVO: UPDATE finan_cli.usuario SET nombre = Nestor, apellido = Trion, tipo_documento = 1, documento = 56443194, email = ser@gmail.com, id_perfil = 2, id_sucursal = 1 WHERE id =secup'),
(874, 'supervisor', '20190822110540', 3, 'El usuario: secup, fue deshabilitado el: 2019-08-22 11:05:40, por el usuario: supervisor!!'),
(875, 'supervisor', '20190822110546', 9, 'El usuario: secup, fue habilitado el: 2019-08-22 11:05:46, por el usuario: supervisor!!'),
(876, 'supervisor', '20190822113552', 22, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (Julio,456,1,Cordoba,---,NULL,4589,---,---)'),
(877, 'supervisor', '20190822113552', 19, 'INSERT INTO finan_cli.sucursal (nombre,codigo,email,id_cadena,id_domicilio) VALUES(Caraffa,234,jer@her.com.ar,1,89)'),
(878, 'supervisor', '20190822114147', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = Julio, nro_calle = 456, provincia = CORDOBA, localidad = Cordoba, departamento = ---, piso = ---, codigo_postal = 4589, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = Julio, nro_calle = 456, provincia = 1, localidad = Cordoba, departamento = ---, piso = NULL, codigo_postal = 4589, entre_calle_1 = ---, entre_calle_2 = ---'),
(879, 'supervisor', '20190822114147', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 9, nombre = Caraffa codigo = 234, email = jer@her.com.ar, id_cadena = 1, id_domicilio = 89 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = Caraffa2 codigo = 234, email = jer@her.com.ar, id_cadena = 1, id_domicilio = 89'),
(880, 'supervisor', '20190822114159', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = Julio, nro_calle = 456, provincia = CORDOBA, localidad = Cordoba, departamento = ---, piso = ---, codigo_postal = 4589, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = Julio, nro_calle = 456, provincia = 1, localidad = Cordoba, departamento = ---, piso = NULL, codigo_postal = 4589, entre_calle_1 = ---, entre_calle_2 = ---'),
(881, 'supervisor', '20190822114159', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 9, nombre = Caraffa2 codigo = 234, email = jer@her.com.ar, id_cadena = 1, id_domicilio = 89 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = Caraffa codigo = 234, email = jer@her.com.ar, id_cadena = 1, id_domicilio = 89'),
(882, 'supervisor', '20190822114615', 20, 'DELETE finan_cli.sucursal --> id: 9 - Codigo: 234 - Nombre: Caraffa - id_domicilio: 89 - Email: jer@her.com.ar - Cadena: 1'),
(883, 'supervisor', '20190822114615', 21, 'DELETE finan_cli.domicilio --> id: 89 - Calle: Julio - Nro. Calle: 456 - Provincia: CORDOBA - Localidad: Cordoba - Departamento: --- - Piso: --- - Codigo Postal: 4589 - Entre Calle 1: --- - Entre Calle 2: ---'),
(884, 'supervisor', '20190822120442', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (PLAN 2 PAGOS,2 CUOTAS en tipo estricto.,2,3,5,1)'),
(885, 'supervisor', '20190822124235', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS1, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6'),
(886, 'supervisor', '20190822124251', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS2, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS1, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6'),
(887, 'supervisor', '20190822124258', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS2, descripcion = 2 CUOTAS en tipo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 6'),
(888, 'supervisor', '20190822124931', 30, 'DELETE finan_cli.plan_credito --> id: 6 - Nombre: PLAN 2 PAGOS - Descripcion: 2 CUOTAS en tipo estricto. - cantidad_cuotas = 2 - interes_fijo = 3 - id_tipo_diferimiento_cuota = 5 - id_cadena = 1 WHERE id = 6'),
(889, 'supervisor', '20190822130327', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (plas,asasa,2,4,5,1)'),
(890, 'supervisor', '20190822130334', 30, 'DELETE finan_cli.plan_credito --> id: 7 - Nombre: plas - Descripcion: asasa - cantidad_cuotas = 2 - interes_fijo = 4 - id_tipo_diferimiento_cuota = 5 - id_cadena = 1 WHERE id = 7'),
(891, 'supervisor', '20190822130349', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (asas,asasas,2,2,5,1)'),
(892, 'supervisor', '20190822130410', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (asas2,asasa,2,3,5,1)'),
(893, 'supervisor', '20190822130415', 30, 'DELETE finan_cli.plan_credito --> id: 9 - Nombre: asas2 - Descripcion: asasa - cantidad_cuotas = 2 - interes_fijo = 3 - id_tipo_diferimiento_cuota = 5 - id_cadena = 1 WHERE id = 9'),
(894, 'supervisor', '20190822130420', 30, 'DELETE finan_cli.plan_credito --> id: 8 - Nombre: asas - Descripcion: asasas - cantidad_cuotas = 2 - interes_fijo = 2 - id_tipo_diferimiento_cuota = 5 - id_cadena = 1 WHERE id = 8'),
(895, 'supervisor', '20190822140441', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (PLAN 2 PAGOS,2 pagos en tiempo estricto.,2,3,5,1)'),
(896, 'supervisor', '20190822140626', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (45,12,10)'),
(897, 'supervisor', '20190822141742', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 45, interes = 12, id_plan_credito = PLAN 2 PAGOS WHERE id = 5 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 45, interes = 12, id_plan_credito = 10 WHERE id = 5'),
(898, 'supervisor', '20190822142703', 34, 'DELETE finan_cli.interes_x_mora --> id: 5 - Cantidad Dias: 45 - Interes: 12 - Plan Credito = PLAN 2 PAGOS WHERE id = 5'),
(899, 'supervisor', '20190822181903', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-22 18:19:03'),
(900, 'supervisor', '20190823113558', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-23 11:35:58'),
(901, 'supervisor', '20190823171336', 15, 'La sesión expiró: 2019-08-23 17:13:36'),
(902, 'her', '20190825164228', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-25 16:42:28'),
(903, 'her', '20190825164228', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-25 16:42:28'),
(904, 'her', '20190825180605', 53, 'ANTERIOR: UPDATE finan_cli.cliente SET tipo_documento = 1, documento = 30443194, nombres = Fernando, apellidos = Budasi, cuil_cuit = 20304431945, fecha_nacimiento = 19880501000000, email = fer@gmail.com, observaciones = , monto_maximo_credito = 5000, id_perfil_credito = 1, id_genero = 1 WHERE id = 1 -- NUEVO: UPDATE finan_cli.cliente SET tipo_documento = 1, documento = 30443194, nombres = Fernando, apellidos = Budasi, cuil_cuit = 20304431945, fecha_nacimiento = 19880501000000, email = fer@gmail.com, observaciones = ---, monto_maximo_credito = 5000, id_perfil_credito = 1, id_genero = 1 WHERE id = 1'),
(905, 'her', '20190825180849', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-25 18:08:49'),
(906, 'supervisor', '20190825180855', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-25 18:08:55'),
(907, 'her', '20190825235244', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-25 23:52:44'),
(908, 'her', '20190825235244', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-25 23:52:44'),
(909, 'her', '20190826103048', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-26 10:30:48'),
(910, 'her', '20190826103229', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado) VALUES (3,150000,4,10,165000,Pendiente)'),
(911, 'her', '20190826103229', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (29,20190826103229,1,41443194,her,2,1,50443194)'),
(912, 'her', '20190826103229', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (29,1,20191002235959,55000,Pendiente)'),
(913, 'her', '20190826103229', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (29,2,20191101235959,55000,Pendiente)'),
(914, 'her', '20190826103229', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (29,3,20191202235959,55000,Pendiente)'),
(915, 'supervisor', '20190829093631', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 09:36:31'),
(916, 'supervisor', '20190829094516', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-29 09:45:16'),
(917, 'her', '20190829094520', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 09:45:20'),
(918, 'her', '20190829094520', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 09:45:20'),
(919, 'her', '20190829103810', 39, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (asas,123,1,asasas,---,NULL,---,---,---,)'),
(920, 'her', '20190829103810', 42, 'INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (1,3513827932,3,)'),
(921, 'her', '20190829103810', 45, 'INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(1,89443194,asas,asas,204433221,19931228000000,asas@gmail.com,20190829103810,Habilitado,NULL,asasas,20000,1,1)'),
(922, 'her', '20190829104255', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-29 10:42:55'),
(923, 'admin_sys', '20190829104300', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 10:43:00'),
(924, 'admin_sys', '20190829104624', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS, descripcion = 2 pagos en tiempo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 10 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = PLAN 2 PAGOS, descripcion = 2 pagos en tiempo estricto., cantidad_cuotas = 2, interes_fijo = 3, id_tipo_diferimiento_cuota = 5, id_cadena = 1 WHERE id = 10'),
(925, 'admin_sys', '20190829105434', 23, 'ANTERIOR: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = CORDOBA, localidad = SIN UBICACION, departamento = ---, piso = ---, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = --- -- NUEVO: UPDATE finan_cli.domicilio SET calle = S/N, nro_calle = 0, provincia = 1, localidad = SIN UBICACION, departamento = ---, piso = NULL, codigo_postal = ---, entre_calle_1 = ---, entre_calle_2 = ---'),
(926, 'admin_sys', '20190829105434', 24, 'ANTERIOR: UPDATE finan_cli.sucursal SET id = 2, nombre = assas codigo = 345, email = ---, id_cadena = 5, id_domicilio = 1 -- NUEVO: UPDATE finan_cli.sucursal SET nombre = assas codigo = 345, email = ---, id_cadena = 5, id_domicilio = 1'),
(927, 'admin_sys', '20190829105459', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4 WHERE id = 4'),
(928, 'admin_sys', '20190829115036', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-29 11:50:36'),
(929, 'her', '20190829115040', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 11:50:40'),
(930, 'her', '20190829115043', 66, 'Generación PDF de Crédito: 29'),
(931, 'her', '20190829115053', 66, 'Generación PDF de Crédito: 29'),
(932, 'her', '20190829165100', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota) VALUES (3,200000,4,10,220000,Pendiente,1)'),
(933, 'her', '20190829165100', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (30,20190829165100,1,32443194,her,2)'),
(934, 'her', '20190829165100', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (30,1,20191002235959,73333,Pendiente)'),
(935, 'her', '20190829165100', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (30,2,20191101235959,73333,Pendiente)'),
(936, 'her', '20190829165100', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (30,3,20191202235959,73334,Pendiente)'),
(937, 'her', '20190829165100', 86, 'UPDATE finan_cli.cuota_credito SET estado = Pagada, fecha_pago = 20190829165100, monto_pago = 73333, usuario_registro_pago = her WHERE id_credito = 30 AND numero_cuota = 1'),
(938, 'her', '20190829165544', 71, 'Generación PDF de Pago Cuota Crédito: 88'),
(939, 'her', '20190829165751', 66, 'Generación PDF de Crédito: 30'),
(940, 'her', '20190829171622', 66, 'Generación PDF de Crédito: 30'),
(941, 'her', '20190829171802', 66, 'Generación PDF de Crédito: 30'),
(942, 'her', '20190829171814', 66, 'Generación PDF de Crédito: 30'),
(943, 'her', '20190829171821', 66, 'Generación PDF de Crédito: 30'),
(944, 'her', '20190829171830', 66, 'Generación PDF de Crédito: 29'),
(945, 'her', '20190829171912', 66, 'Generación PDF de Crédito: 30'),
(946, 'her', '20190829174750', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-29 17:47:50'),
(947, 'supervisor', '20190829174806', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-29 17:48:06'),
(948, 'supervisor', '20190829180114', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-29 18:01:14'),
(949, 'supervisor', '20190830114922', 1, 'Inicio de Sesion en Fecha y Hora: 2019-08-30 11:49:22'),
(950, 'supervisor', '20190830124658', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (Plan 12 Cuotas Cla,asas,11,33,5,5)'),
(951, 'supervisor', '20190830124709', 30, 'DELETE finan_cli.plan_credito --> id: 11 - Nombre: Plan 12 Cuotas Cla - Descripcion: asas - cantidad_cuotas = 11 - interes_fijo = 33 - id_tipo_diferimiento_cuota = 5 - id_cadena = 5 WHERE id = 11'),
(952, 'supervisor', '20190830124826', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (pla,ssd,11,22,5,5)'),
(953, 'supervisor', '20190830124831', 30, 'DELETE finan_cli.plan_credito --> id: 12 - Nombre: pla - Descripcion: ssd - cantidad_cuotas = 11 - interes_fijo = 22 - id_tipo_diferimiento_cuota = 5 - id_cadena = 5 WHERE id = 12'),
(954, 'supervisor', '20190830125139', 28, 'INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena,minimo_entrega) VALUES (Plan 12,asas,12,45,5,5,20)'),
(955, 'supervisor', '20190830141721', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 12, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega =  WHERE id = 13 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 12, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 20 WHERE id = 13'),
(956, 'supervisor', '20190830142256', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 12, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 12 WHERE id = 13 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 12, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 0 WHERE id = 13'),
(957, 'supervisor', '20190830180656', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-30 18:06:56'),
(958, 'her', '20190901171347', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-01 17:13:47'),
(959, 'her', '20190901171347', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-01 17:13:47'),
(960, 'her', '20190901172351', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-01 17:23:51'),
(961, 'supervisor', '20190901172359', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-01 17:23:59'),
(962, 'supervisor', '20190901172546', 29, 'NUEVO: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 1, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 12 WHERE id = 13 -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = Plan 12, descripcion = asas, cantidad_cuotas = 12, interes_fijo = 45, id_tipo_diferimiento_cuota = 5, id_cadena = 5, minimo_entrega = 12 WHERE id = 13'),
(963, 'supervisor', '20190901172557', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-01 17:25:57'),
(964, 'admin_sys', '20190901172603', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-01 17:26:03'),
(965, 'admin_sys', '20190901172651', 31, 'DELETE finan_cli.perfil_credito_x_plan WHERE id_plan_credito = 4 -- nombre = Plan 3 Cuotas Clasico, cantidad_cuotas = 3, interes_fijo = 10, tipo_diferimiento_cuota = 6, cadena = PRUEBA'),
(966, 'admin_sys', '20190901172651', 31, 'DELETE finan_cli.perfil_credito_x_plan WHERE id_plan_credito = 5 -- nombre = Plan 6 Cuotas Clasico, cantidad_cuotas = 6, interes_fijo = 20, tipo_diferimiento_cuota = 5, cadena = PRUEBA'),
(967, 'admin_sys', '20190901172651', 32, 'INSERT INTO finan_cli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (2,)'),
(968, 'admin_sys', '20190901172651', 32, 'INSERT INTO finan_cli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (2,)'),
(969, 'admin_sys', '20190901172651', 32, 'INSERT INTO finan_cli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (2,)'),
(970, 'admin_sys', '20190901172758', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-01 17:27:58'),
(971, 'supervisor', '20190901172809', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-01 17:28:09'),
(972, 'supervisor', '20190901222337', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,115444,13,45,167394,Pendiente,0,13853)'),
(973, 'supervisor', '20190901222337', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (31,20190901222337,1,32443194,supervisor,2)'),
(974, 'supervisor', '20190901222337', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (31,1,20191001235959,167394,Pendiente)'),
(975, 'supervisor', '20190901223438', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,122256,13,45,177271,Pendiente,0,14671)'),
(976, 'supervisor', '20190901223438', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (32,20190901223438,1,32443194,supervisor,2)'),
(977, 'supervisor', '20190901223438', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (32,1,20191001235959,177271,Pendiente)'),
(978, 'supervisor', '20190901230705', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,123456,13,45,179011,Pendiente,0,14815)'),
(979, 'supervisor', '20190901230705', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (33,20190901230705,1,32443194,supervisor,2)'),
(980, 'supervisor', '20190901230705', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (33,1,20191001235959,179011,Pendiente)'),
(981, 'supervisor', '20190901231017', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,123456,13,45,179011,Pendiente,0,14815)'),
(982, 'supervisor', '20190901231017', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (34,20190901231017,1,32443194,supervisor,2)'),
(983, 'supervisor', '20190901231017', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (34,1,20191001235959,179011,Pendiente)'),
(984, 'supervisor', '20190901231159', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,123456,13,45,179011,Pendiente,0,14815)'),
(985, 'supervisor', '20190901231159', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (35,20190901231159,1,32443194,supervisor,2)'),
(986, 'supervisor', '20190901231159', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (35,1,20191001235959,179011,Pendiente)'),
(987, 'supervisor', '20190901231429', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,123456,13,45,179011,Pendiente,0,14815)'),
(988, 'supervisor', '20190901231429', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (36,20190901231429,1,32443194,supervisor,2)'),
(989, 'supervisor', '20190901231429', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (36,1,20191001235959,179011,Pendiente)'),
(990, 'supervisor', '20190901231540', 61, 'INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado,abona_primera_cuota,minimo_entrega) VALUES (1,123456,13,45,179011,Pendiente,0,14815)'),
(991, 'supervisor', '20190901231540', 62, 'INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (37,20190901231540,1,32443194,supervisor,2)'),
(992, 'supervisor', '20190901231540', 63, 'INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (37,1,20191001235959,179011,Pendiente)'),
(993, 'supervisor', '20190901231554', 65, 'Reimpresión de Crédito: 37'),
(994, 'supervisor', '20190901232227', 65, 'Reimpresión de Crédito: 37'),
(995, 'supervisor', '20190901232316', 65, 'Reimpresión de Crédito: 37'),
(996, 'supervisor', '20190901232322', 66, 'Generación PDF de Crédito: 37'),
(997, 'supervisor', '20190901232414', 66, 'Generación PDF de Crédito: 30'),
(998, 'supervisor', '20190902112725', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-02 11:27:25'),
(999, 'supervisor', '20190902113150', 66, 'Generación PDF de Crédito: 30'),
(1000, 'supervisor', '20190902113623', 66, 'Generación PDF de Crédito: 30'),
(1001, 'supervisor', '20190902113632', 66, 'Generación PDF de Crédito: 30'),
(1002, 'supervisor', '20190902113652', 66, 'Generación PDF de Crédito: 37'),
(1003, 'supervisor', '20190902113746', 66, 'Generación PDF de Crédito: 37'),
(1004, 'supervisor', '20190902124305', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 0 WHERE id = 4'),
(1005, 'supervisor', '20190902124358', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 0 WHERE id = 4'),
(1006, 'supervisor', '20190902124608', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 1 WHERE id = 4'),
(1007, 'supervisor', '20190902124623', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 1 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 0 WHERE id = 4'),
(1008, 'supervisor', '20190902124627', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 1 WHERE id = 4'),
(1009, 'supervisor', '20190902125152', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (2,1,4,1)'),
(1010, 'supervisor', '20190902130651', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 1 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 0 WHERE id = 4'),
(1011, 'supervisor', '20190902130656', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 1 WHERE id = 4'),
(1012, 'supervisor', '20190902130701', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 60, interes = 10, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 3 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 60, interes = 10, id_plan_credito = 4, recurrente = 1 WHERE id = 3'),
(1013, 'supervisor', '20190902130706', 34, 'DELETE finan_cli.interes_x_mora --> id: 5 - Cantidad Dias: 2 - Interes: 1 - Plan Credito = Plan 3 Cuotas Clasico WHERE id = 5'),
(1014, 'supervisor', '20190902130713', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (1,2,4,1)'),
(1015, 'supervisor', '20190902130717', 34, 'DELETE finan_cli.interes_x_mora --> id: 6 - Cantidad Dias: 1 - Interes: 2 - Plan Credito = Plan 3 Cuotas Clasico WHERE id = 6'),
(1016, 'supervisor', '20190902130724', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (1,2,4,1)'),
(1017, 'supervisor', '20190902130727', 34, 'DELETE finan_cli.interes_x_mora --> id: 7 - Cantidad Dias: 1 - Interes: 2 - Plan Credito = Plan 3 Cuotas Clasico WHERE id = 7'),
(1018, 'supervisor', '20190902130732', 33, 'INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (1,2,4,0)'),
(1019, 'admin_sys', '20190902140337', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1020, 'admin_sys', '20190902140337', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1021, 'admin_sys', '20190902140611', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1022, 'admin_sys', '20190902140611', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1023, 'admin_sys', '20190902140815', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1024, 'admin_sys', '20190902140815', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1025, 'admin_sys', '20190902141138', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1026, 'supervisor', '20190902141323', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 1 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 0 WHERE id = 4'),
(1027, 'admin_sys', '20190902141500', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1028, 'admin_sys', '20190902141625', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1029, 'admin_sys', '20190902141625', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1030, 'admin_sys', '20190902141839', 85, 'ANTERIOR: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $278,61. WHERE id = 9 -- NUEVO: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $290,22. WHERE id = 9'),
(1031, 'admin_sys', '20190902141839', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1032, 'admin_sys', '20190902141915', 83, 'ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = En Mora WHERE id = 77 -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = Pendiente WHERE id = 77'),
(1033, 'admin_sys', '20190902141915', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1034, 'admin_sys', '20190902141950', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1035, 'admin_sys', '20190902141950', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1036, 'supervisor', '20190902142001', 35, 'ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = Plan 3 Cuotas Clasico, recurrente = 0 WHERE id = 4 - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = 30, interes = 5, id_plan_credito = 4, recurrente = 1 WHERE id = 4'),
(1037, 'admin_sys', '20190902142024', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:77, con 1 dias de mora!!'),
(1038, 'admin_sys', '20190902142024', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1039, 'admin_sys', '20190902142146', 85, 'ANTERIOR: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $290,22. WHERE id = 9 -- NUEVO: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $301,83. WHERE id = 9'),
(1040, 'admin_sys', '20190902142146', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1041, 'admin_sys', '20190902142639', 85, 'ANTERIOR: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $301,83. WHERE id = 9 -- NUEVO: UPDATE finan_cli.aviso_x_mora SET mensaje = PRUEBA: informa que la cuota número: 2 del credito: 27, tiene una deuda pendiente de $313,44. WHERE id = 9'),
(1042, 'admin_sys', '20190902142639', 84, 'El plan de crédito no tiene un recargo definido para el id de cuota:10, con 1 dias de mora!!'),
(1043, 'supervisor', '20190902175411', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-02 17:54:11'),
(1044, 'supervisor', '20190903124243', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-03 12:42:43'),
(1045, 'supervisor', '20190904141413', 15, 'La sesión expiró: 2019-09-04 14:14:13'),
(1046, 'supervisor', '20190904141423', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 14:14:23'),
(1047, 'supervisor', '20190904151829', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE finan_cli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(1048, 'supervisor', '20190904151836', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE finan_cli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(1049, 'supervisor', '20190904155351', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE finan_cli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(1050, 'supervisor', '20190904155351', 88, 'ANTERIOR: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = 20190811093000, horario_salida = 20190811181000, lunes = 1, martes = 1, miercoles = 0, jueves = 1, viernes = 1, sabado = 1, domingo = 0 WHERE id_usuario = her -- NUEVO: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = 20190904093500, horario_salida = 20190904181100, lunes = 1, martes = 0, miercoles = 0, jueves = 1, viernes = 0, sabado = 0, domingo = 1 WHERE id_usuario = her'),
(1051, 'supervisor', '20190904155413', 7, 'ANTERIOR: id = her, nombre = herbasio, apellido = serio, tipo_documento = DNI, documento = 2323211, email = ferd1@gmail.com, perfil = Usuario Normal, sucursal = assas  -- NUEVO: UPDATE finan_cli.usuario SET nombre = herbasio, apellido = serio, tipo_documento = 1, documento = 2323211, email = ferd1@gmail.com, id_perfil = 2, id_sucursal = 2 WHERE id =her'),
(1052, 'supervisor', '20190904155413', 88, 'ANTERIOR: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = 20190904093500, horario_salida = 20190904181100, lunes = 1, martes = 0, miercoles = 0, jueves = 1, viernes = 0, sabado = 0, domingo = 1 WHERE id_usuario = her -- NUEVO: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = 20190904083500, horario_salida = 20190904171100, lunes = 0, martes = 1, miercoles = 1, jueves = 0, viernes = 1, sabado = 1, domingo = 0 WHERE id_usuario = her'),
(1057, 'supervisor', '20190904162915', 4, 'INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (qwqwqw,123,1,asasas,---,NULL,---,---,---)'),
(1058, 'supervisor', '20190904162915', 8, 'INSERT INTO finan_cli.usuario (id,nombre,apellido,tipo_documento,documento,email,id_perfil,id_sucursal,estado) VALUES(asa,asas,asas,1,32232,asas@her.com.ar,2,2,Habilitado)'),
(1059, 'supervisor', '20190904162915', 87, 'INSERT INTO finan_cli.horario_laboral_x_usuario (id_usuario,horario_ingreso,horario_salida,lunes,martes,miercoles,jueves,viernes,sabado,domingo) VALUES (asa,20190904100000,20190904150000,1,1,1,1,1,1,1)'),
(1060, 'supervisor', '20190904163127', 7, 'ANTERIOR: id = supervisor, nombre = Supervisa, apellido = TODO, tipo_documento = DNI, documento = 1234123, email = teestamosobservando@segu.com, perfil = Supervisor, sucursal = assas  -- NUEVO: UPDATE finan_cli.usuario SET nombre = Supervisa, apellido = TODO, tipo_documento = 1, documento = 1234123, email = teestamosobservando@segu.com, id_perfil = 3, id_sucursal = 2 WHERE id =supervisor'),
(1061, 'supervisor', '20190904170413', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:04:13'),
(1062, 'her', '20190904170418', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 17:04:18'),
(1063, 'her', '20190904170426', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:04:26'),
(1064, 'her', '20190904170833', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 17:08:33'),
(1065, 'her', '20190904170835', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:08:35'),
(1066, 'her', '20190904170919', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 17:09:19'),
(1067, 'her', '20190904170921', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:09:21'),
(1068, 'her', '20190904170952', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 17:09:52'),
(1069, 'her', '20190904171002', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:10:02'),
(1070, 'her', '20190904171149', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 17:11:49'),
(1071, 'her', '20190904171153', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 17:11:53'),
(1072, 'supervisor', '20190904230139', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 23:01:39'),
(1073, 'supervisor', '20190904230154', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-04 23:01:54'),
(1074, 'supervisor', '20190904230207', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-04 23:02:07'),
(1075, 'supervisor', '20190905155554', 1, 'Inicio de Sesion en Fecha y Hora: 2019-09-05 15:55:54'),
(1076, 'supervisor', '20190905175640', 2, 'Cierre de Sesion en Fecha y Hora: 2019-09-05 17:56:40');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=89 ;

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
(88, 'Modificar Horario Laboral Usuario', 'Cuando se modifica el horario laboral de un usuario.');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=29 ;

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
(28, 'cantidad_dias_actualizacion_datos_cliente', 'Es la cantidad de días desde el ultimo crédito en el cual se informa que los datos del cliente deben ser actualizados.', '180');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Reporte de Créditos Otorgados X Sucursal', 'Se muestra la cantidad y monto de créditos otorgados por sucursal de la cadena correspondiente al usuario logueado.');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=64 ;

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
(63, 1, 3513827932, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
('asa', 92);

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
  ADD CONSTRAINT `fk_foreign_key_consulta_estado_financiero_adicional` FOREIGN KEY (`tipo_documento_adicional`, `documento_adicional`) REFERENCES `cliente` (`tipo_documento`, `documento`);

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
