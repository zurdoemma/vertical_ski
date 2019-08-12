-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-08-2019 a las 01:51:53
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `finan_cli`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviso_x_mora`
--

DROP TABLE IF EXISTS `aviso_x_mora`;
CREATE TABLE IF NOT EXISTS `aviso_x_mora` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_credito` bigint(20) NOT NULL,
  `fecha` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  `mensaje` varchar(1020) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado_sms` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_tipo_aviso` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_credito` (`id_credito`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `id_tipo_aviso` (`id_tipo_aviso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `aviso_x_mora`
--

INSERT INTO `aviso_x_mora` (`id`, `id_credito`, `fecha`, `estado`, `id_cuota_credito`, `mensaje`, `estado_sms`, `id_tipo_aviso`) VALUES
(1, 27, '20190806141410', 'Finalizado', 77, 'Regularice su situación.', 'Enviado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cadena`
--

DROP TABLE IF EXISTS `cadena`;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `cliente`;
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
  PRIMARY KEY (`tipo_documento`,`documento`,`id`),
  KEY `id` (`id`,`id_titular`,`id_perfil_credito`),
  KEY `id_titular` (`id_titular`),
  KEY `id_perfil_credito` (`id_perfil_credito`),
  KEY `id_genero` (`id_genero`),
  KEY `id_titular_2` (`id_titular`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo_documento`, `documento`, `nombres`, `apellidos`, `cuil_cuit`, `fecha_nacimiento`, `email`, `fecha_alta`, `estado`, `id_titular`, `observaciones`, `monto_maximo_credito`, `id_perfil_credito`, `id_genero`) VALUES
(1, 1, '30443194', 'Fernando', 'Budasi', 20304431945, '19880501000000', 'fer@gmail.com', '20190520095955', 'Habilitado', NULL, NULL, 5000, 1, 1),
(2, 1, '32443194', 'Bernardo', 'Arenga', 20324431945, '19780213000000', 'ferareng@gmail.com', '20190522175000', 'Habilitado', NULL, 'Ninguna', 1000000, 2, 1),
(14, 1, '35443194', 'Pedro', 'Decara', 20354431948, '19650305000000', 'pdecara@decarasa.com.ar', '20190606163942', 'Deshabilitado', NULL, 'Presenta documento borroso.', 450000, 2, 1),
(15, 1, '37443194', 'Adivino', 'Vividor', 20374431946, '19850131000000', '---', '20190606170045', 'Deshabilitado', 14, 'Nada', 245600, 1, 1),
(17, 1, '41443194', 'Servian', 'Juere', 20414431945, '19260730000000', '---', '20190607110919', 'Habilitado', NULL, 'asas', 550000, 1, 1),
(18, 1, '42443194', 'asas', 'asas', 20424431948, '19910818000000', '---', '20190607111212', 'Deshabilitado', 17, 'asas', 122222, 1, 1),
(21, 1, '45443194', 'ASASAS', 'asasas', 20454431944, '19870404000000', '---', '20190607121436', 'Deshabilitado', 17, 'asasas', 100000, 1, 1),
(22, 1, '47898452', 'asas', 'JKLJKL', 23478984525, '19860307000000', '---', '20190607124036', 'Habilitado', NULL, 'ASASAS', 544444, 1, 1),
(23, 1, '50443194', 'asa', 'asas', 121212, '19870404000000', '---', '20190607124532', 'Habilitado', 17, 'asas', 499999, 1, 1),
(24, 1, '51443194', 'asas', 'klkl', 20514431945, '19900719000000', '---', '20190607140124', 'Habilitado', NULL, 'asas', 495000, 1, 1),
(25, 1, '87654321', 'Pruebas Errores', 'Ejemplo', 20876543215, '19880101000000', 'linkinlinkin@gmail.com', '20190708104913', 'Habilitado', NULL, 'Ker.', 250010, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_domicilio`
--

DROP TABLE IF EXISTS `cliente_x_domicilio`;
CREATE TABLE IF NOT EXISTS `cliente_x_domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `preferido` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_domicilio`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`)
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
(87, 1, '87654321', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_x_telefono`
--

DROP TABLE IF EXISTS `cliente_x_telefono`;
CREATE TABLE IF NOT EXISTS `cliente_x_telefono` (
  `id_telefono` int(11) NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `documento` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `preferido` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_telefono`,`tipo_documento`,`documento`),
  KEY `tipo_documento` (`tipo_documento`)
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
(60, 1, '87654321', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_estado_financiero`
--

DROP TABLE IF EXISTS `consulta_estado_financiero`;
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
  KEY `tipo_documento_adicional` (`tipo_documento_adicional`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `consulta_estado_financiero`
--

INSERT INTO `consulta_estado_financiero` (`id`, `tipo_documento`, `documento`, `fecha`, `resultado_xml`, `usuario`, `cuit_cuil`, `token`, `validado`, `tipo_documento_adicional`, `documento_adicional`) VALUES
(5, 1, '87654321', '20180607111439', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>07/06/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad>ICBC - INDUSTRIAL AND COMMERCIONAL BANK OF CHINA</entidad><situacion>3</situacion><monto_maximo>10000.0000000000</monto_maximo><deuda_actual>10000.0000000000</deuda_actual><fecha>05/04/2017</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'admin_sys', 20876543215, 'ab05eaab9673f62d016d86e125ecf9ddcef363d2f197034caca97b304029d9333a28555a6cb838e754d6179ddabdd9c9500139893f3c51583665fb0b40bfb34d', b'1', NULL, NULL),
(6, 1, '87654321', '20190710104717', '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><RESULTADO><Existencia_Fisica_Resu ><row><ape_nom>EJEMPLO PRUEBAS ERRORES</ape_nom><nume_docu>87654321</nume_docu><cdi>20876543215</cdi><fecha_nacimiento>01/01/1988</fecha_nacimiento><direc_calle>ARRUABARRENA 1860</direc_calle><localidad>CORDOBA</localidad><codigo_postal>5000</codigo_postal><provincia>CORDOBA</provincia><apellido_materno></apellido_materno><t_docu>DNI</t_docu><clase>1988</clase><edad>31</edad><fallecido>NO</fallecido></row></Existencia_Fisica_Resu > <predictor_ingreso ><row><predictor_ingresos>R01</predictor_ingresos></row></predictor_ingreso > <TIENE_JUI_QUI_EJEC ><row><tiene_juicio>NO</tiene_juicio></row></TIENE_JUI_QUI_EJEC > <DEUDA_SISTEMA_FINANCIERO_6M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_6M > <DEUDA_SISTEMA_FINANCIERO_12M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_12M > <DEUDA_SISTEMA_FINANCIERO_24M ><row><entidad></entidad><situacion>0</situacion><monto_maximo>0.0000000000</monto_maximo><deuda_actual>0.0000000000</deuda_actual><fecha>08/07/2019</fecha></row></DEUDA_SISTEMA_FINANCIERO_24M > <RELACION_DEPENDENCIA ><row><ult_periodo>201703</ult_periodo><alta_trabajo_ultimo>201205</alta_trabajo_ultimo><cuit>30999032083</cuit><razon_social>BANCO DE LA CIUDAD DE BUENOS AIRES</razon_social><situacion_laboral_actual>SITUACION: NO ACTIVO</situacion_laboral_actual></row></RELACION_DEPENDENCIA > <CONSTANCIA_DE_INSCRIPCION_AFIP ><row><cuit>20876543215</cuit><denominacion>EJEMPLO PRUEBAS ERRORES</denominacion><fecha_contrato_social></fecha_contrato_social><mes_cierre>12</mes_cierre><categoria>I</categoria><fecha_inicio_actividades>01/01/2012</fecha_inicio_actividades><descripcion>DOMICILIO FISCAL</descripcion><direccion>PASAJE ESCUTIL 955 - BARRIO : GUEMES</direccion><localidad>CORDOBA</localidad><provincia>CORDOBA</provincia><cp>5000</cp><antiguedad_meses>0</antiguedad_meses></row></CONSTANCIA_DE_INSCRIPCION_AFIP > <Tipo_Actividad ><row><tipo_actividad> JUBILADO</tipo_actividad></row></Tipo_Actividad > <Moviles_posee ><row><posee_autos>SI</posee_autos><cantidad_autos>31</cantidad_autos></row></Moviles_posee > <automotores ><row><dominio>QAA367XP</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA367XR</dominio><modelo_auto>03/08/2016</modelo_auto></row><row><dominio>QAA005EV</dominio><modelo_auto>14/04/2016</modelo_auto></row><row><dominio>QAA003HD</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QAA003WB</dominio><modelo_auto>01/04/2016</modelo_auto></row><row><dominio>QPFP707</dominio><modelo_auto>23/09/2015</modelo_auto></row><row><dominio>QOOK570</dominio><modelo_auto>23/04/2015</modelo_auto></row><row><dominio>QNVQ558</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QNVQ559</dominio><modelo_auto>30/04/2014</modelo_auto></row><row><dominio>QLXD070</dominio><modelo_auto>23/01/2013</modelo_auto></row><row><dominio>QLBF675</dominio><modelo_auto>23/03/2012</modelo_auto></row><row><dominio>QKWN259</dominio><modelo_auto>30/01/2012</modelo_auto></row><row><dominio>QLMI646</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR773</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR772</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QLKR774</dominio><modelo_auto>01/01/2012</modelo_auto></row><row><dominio>QKPJ787</dominio><modelo_auto>23/11/2011</modelo_auto></row><row><dominio>QKKV496</dominio><modelo_auto>26/10/2011</modelo_auto></row><row><dominio>QGSN306</dominio><modelo_auto>23/10/2007</modelo_auto></row><row><dominio>QDZT828</dominio><modelo_auto>11/09/2002</modelo_auto></row><row><dominio>QBTC009</dominio><modelo_auto>07/01/1998</modelo_auto></row><row><dominio>QBNA913</dominio><modelo_auto>11/08/1997</modelo_auto></row><row><dominio>QRPW233</dominio><modelo_auto>02/10/1986</modelo_auto></row><row><dominio>QTPC515</dominio><modelo_auto>01/12/1981</modelo_auto></row><row><dominio>QXAE068</dominio><modelo_auto>12/04/1977</modelo_auto></row><row><dominio>QXHB731</dominio><modelo_auto>05/03/1976</modelo_auto></row><row><dominio>QXBI402</dominio><modelo_auto>10/09/1971</modelo_auto></row><row><dominio>QTAN253</dominio><modelo_auto>31/10/1969</modelo_auto></row><row><dominio>QXMB968</dominio><modelo_auto>08/10/1969</modelo_auto></row><row><dominio>QSCU125</dominio><modelo_auto>30/09/1969</modelo_auto></row><row><dominio>QJPC755</dominio></row></automotores > <Telef_2_><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></Telef_2_> <Celulares_><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532318</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1132532385</celular><fecha_activacion>30/11/2007</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133103378</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133144721</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133153898</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133173591</celular><fecha_activacion>18/05/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133194243</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133249394</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133341116</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133358153</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133590443</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1133648551</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137874055</celular><fecha_activacion>23/09/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137899544</celular><fecha_activacion>24/04/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970031</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970387</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970401</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970483</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970543</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970567</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970596</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970653</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970654</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970688</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970699</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970720</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970813</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970833</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970885</celular><fecha_activacion>03/12/2009</fecha_activacion></row><row><cuit></cuit><documento>87654321</documento><empresa>CLARO</empresa><celular>1137970914</celular><fecha_activacion>03/12/2009</fecha_activacion></row></Celulares_> <inf_lab_hist_fecha_><row><inf_lab_cuit_>30590360763</inf_lab_cuit_><inf_lab_razon_>CENCOSUD S.A.</inf_lab_razon_><relacion_desde_>01/01/2011</relacion_desde_><relacion_hasta_>01/03/2012</relacion_hasta_></row><row><inf_lab_cuit_>30999032083</inf_lab_cuit_><inf_lab_razon_>BANCO DE LA CIUDAD DE BUENOS AIRES</inf_lab_razon_><relacion_desde_>01/05/2012</relacion_desde_><relacion_hasta_>01/03/2017</relacion_hasta_></row></inf_lab_hist_fecha_> <consultas ><row><consultas>0</consultas><consultas_6>0</consultas_6></row></consultas > <Juicios_Posee_Embargo ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Embargo > <Juicios_Posee_Inhabilitacion ><row><juicios_posee_tipo>0</juicios_posee_tipo></row></Juicios_Posee_Inhabilitacion > <Telefono_laboral_cuit ><row><titular></titular><telefono>(011)-4222-4822</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4292-3828</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-1603</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3444</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3468</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-3749</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4301-4192</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1361</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4303-1562</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row><row><titular></titular><telefono>(011)-4320-6200</telefono><direccion></direccion><codigo_postal></codigo_postal><localidad>AMBA</localidad><provincia></provincia></row></Telefono_laboral_cuit > <JUBILADO ><row><numero_beneficiario>15041528850</numero_beneficiario><beneficio_clase>01</beneficio_clase><estado>M</estado></row></JUBILADO > <Moras_Valida ><row><valida>NO</valida><entidades></entidades><max_atraso>0</max_atraso></row></Moras_Valida > <EMAILS ><row><email>LINKINLINKIN@GMAIL.COM</email></row></EMAILS > <TELEFONO_PARTICULAR ><row><telefono>(0351)-421-0521</telefono></row><row><telefono>(0351)-421-0522</telefono></row></TELEFONO_PARTICULAR > <CELULARES ><row><celular>(011)-1532532318</celular></row></CELULARES > <Score ><row><score>10</score></row></Score > <tipo_empleador ><row><tipo_empleador></tipo_empleador></row></tipo_empleador ></RESULTADO>', 'her', 20876543215, '7fedcceafd3333a489c7f9c34d8db9bb25bc9c8987338c116129757c9d82a3e8b1fb0b8f0b698d3f2128e404e7ba112cd02172271eb1d77e5cd658adabb387f1', b'1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

DROP TABLE IF EXISTS `credito`;
CREATE TABLE IF NOT EXISTS `credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cantidad_cuotas` int(11) NOT NULL,
  `monto_compra` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  `interes_fijo_plan_credito` int(11) NOT NULL,
  `monto_credito_original` int(11) NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id`, `cantidad_cuotas`, `monto_compra`, `id_plan_credito`, `interes_fijo_plan_credito`, `monto_credito_original`, `estado`) VALUES
(4, 3, 160000, 4, 10, 176000, 'Pendiente'),
(27, 3, 63320, 4, 10, 69652, 'Pagada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito_cliente`
--

DROP TABLE IF EXISTS `credito_cliente`;
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
  KEY `tipo_documento_titular` (`tipo_documento_adicional`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `credito_cliente`
--

INSERT INTO `credito_cliente` (`id_credito`, `fecha`, `tipo_documento`, `documento`, `id_usuario`, `id_sucursal`, `tipo_documento_adicional`, `documento_adicional`) VALUES
(4, '20190712174145', 1, '87654321', 'her', 2, NULL, NULL),
(27, '20190714172330', 1, '87654321', 'her', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_credito`
--

DROP TABLE IF EXISTS `cuota_credito`;
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
  KEY `id_credito` (`id_credito`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cuota_credito`
--

INSERT INTO `cuota_credito` (`id`, `id_credito`, `numero_cuota`, `fecha_vencimiento`, `monto_cuota_original`, `estado`, `fecha_pago`, `monto_pago`, `usuario_registro_pago`) VALUES
(10, 4, 1, '20190812235959', 58667, 'Pendiente', NULL, NULL, NULL),
(11, 4, 2, '20190911235959', 58667, 'Pendiente', NULL, NULL, NULL),
(12, 4, 3, '20191011235959', 58666, 'Pendiente', NULL, NULL, NULL),
(76, 27, 1, '20190813235959', 23217, 'Pagada', '20190731145915', 23217, 'her'),
(77, 27, 2, '20190912235959', 23217, 'Incobrable', NULL, NULL, NULL),
(78, 27, 3, '20191014235959', 23218, 'Condonada', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_laboral_x_cliente`
--

DROP TABLE IF EXISTS `dato_laboral_x_cliente`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_laboral_x_telefono`
--

DROP TABLE IF EXISTS `dato_laboral_x_telefono`;
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

DROP TABLE IF EXISTS `domicilio`;
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
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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
(87, 'Arruabarrena', 1860, 1, 'CORDOBA', '---', 0, '5000', '---', '---');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejecucion_procesos_auto`
--

DROP TABLE IF EXISTS `ejecucion_procesos_auto`;
CREATE TABLE IF NOT EXISTS `ejecucion_procesos_auto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ejecucion_procesos_auto`
--

INSERT INTO `ejecucion_procesos_auto` (`id`, `fecha`, `comentario`) VALUES
(1, '20190811131020', 'Todo OK!');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cliente`
--

DROP TABLE IF EXISTS `estado_cliente`;
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
  KEY `id_motivo` (`id_motivo`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `estado_cliente`
--

INSERT INTO `estado_cliente` (`id`, `tipo_documento`, `documento`, `fecha`, `id_motivo`, `comentario`, `usuario`, `usuario_supervisor`, `tipo_documento_adicional`, `documento_adicional`, `nro_telefono`, `token`) VALUES
(34, 1, '41443194', '20190607110919', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(35, 1, '42443194', '20190607111208', 36, NULL, 'admin_sys', NULL, 1, '42443194', NULL, ''),
(36, 1, '41443194', '20190607111212', 37, NULL, 'admin_sys', NULL, 1, '42443194', NULL, ''),
(37, 1, '87654321', '20190607111436', 36, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(38, 1, '87654321', '20190607111439', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(39, 1, '87654321', '20190607111910', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(40, 1, '87654321', '20190607111937', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(41, 1, '44443194', '20190607115729', 36, NULL, 'admin_sys', NULL, 1, '44443194', NULL, ''),
(42, 1, '87654321', '20190607115731', 37, NULL, 'admin_sys', NULL, 1, '44443194', NULL, ''),
(43, 1, '45443194', '20190607121414', 36, NULL, 'admin_sys', NULL, 1, '45443194', NULL, ''),
(44, 1, '41443194', '20190607121416', 37, NULL, 'admin_sys', NULL, 1, '45443194', NULL, ''),
(45, 1, '47898452', '20190607123800', 36, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(46, 1, '47898452', '20190607123802', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(47, 1, '50443194', '20190607124324', 36, NULL, 'admin_sys', NULL, 1, '50443194', NULL, ''),
(48, 1, '41443194', '20190607124325', 37, NULL, 'admin_sys', NULL, 1, '50443194', NULL, ''),
(49, 1, '51443194', '20190607140051', 36, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(50, 1, '51443194', '20190607140053', 37, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(53, 1, '32443194', '20190625164922', 50, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(56, 1, '87654321', '20190627165551', 52, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(57, 1, '87654321', '20190628095409', 51, NULL, 'admin_sys', NULL, NULL, NULL, NULL, ''),
(58, 1, '87654321', '20190630180941', 50, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(60, 1, '87654321', '20190703145828', 55, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(61, 1, '87654321', '20190704171524', 54, NULL, 'her', 'supervisor', NULL, NULL, 3512927984, ''),
(62, 1, '87654321', '20190704172411', 54, NULL, 'her', 'SUPERVISOR', NULL, NULL, 34312344444, ''),
(63, 1, '87654321', '20190704215023', 56, NULL, 'her', 'supervisor', NULL, NULL, 35132837932, ''),
(64, 1, '87654321', '20190704233629', 57, NULL, 'her', 'supervisor', NULL, NULL, 3512927984, ''),
(65, 1, '87654321', '20190705124906', 56, NULL, 'her', 'supervisor', NULL, NULL, 34312344444, ''),
(66, 1, '87654321', '20190705125823', 57, NULL, 'her', 'supervisor', NULL, NULL, 3512927984, ''),
(67, 1, '64443194', '20190705172026', 36, NULL, 'her', 'supervisor', 1, '64443194', NULL, ''),
(68, 1, '87654321', '20190708104913', 38, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(71, 1, '87654321', '20190710160308', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '5f6e1dd03ef804806042f7f33847570920638e7da7b994bd480c23545fdac3f46389acd83ddaaa50fb09e12d3d3f7214a4fe5ac2dbf9e72e840460bd0dc8e00a'),
(72, 1, '35443194', '20190710160456', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '55197c11a5c97384c7171b400920fdcbc32b59a2141f0c3dd8f5c7c201b7ef3a6feb659ed8604491050e7200548e9815650270b58169c523b34c603b6cd5dc41'),
(73, 1, '87654321', '20190710160550', 50, NULL, 'her', 'supervisor', NULL, NULL, NULL, ''),
(74, 1, '87654321', '20190710160630', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'b8ae3723048d0656807e1ed202b4809c493333941d10ef4889b8784a4400b18f6f12ccc0361365c582e074807ac64fec15d762a5146ab3b11966ce9e7eae89da'),
(75, 1, '87654321', '20190710161759', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, '40c22b5993006379fc0dd286b3c1d26be90347617aea1ef7b27b440bd7a2c7af642a96cb9828232a981cf513a14382810c332f0e613dece2331ddeeb4d888dd3'),
(76, 1, '87654321', '20190710161941', 58, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'e9e7004d0013259cb649cd5f6e1865be43f1197d04347408e0457c42d4caae68a6145e2a0c4722370fb21532f33e80910973d20045660fa4c08b17b66d0cc013'),
(79, 1, '87654321', '20190711115355', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'b2d63bc0d6200318dfe2fe5d6f7110117cd784cd1b2f8d65febca1cc01eba17fedba6660993936d9490cfb75cea90579b6d5d6e477bb00daccfad9a339f25149'),
(80, 1, '87654321', '20190711115909', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(81, 1, '87654321', '20190711164157', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(82, 1, '87654321', '20190711165036', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(83, 1, '87654321', '20190711165929', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(84, 1, '87654321', '20190711171049', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(85, 1, '87654321', '20190711171626', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(86, 1, '87654321', '20190711171901', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(87, 1, '87654321', '20190711172048', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(88, 1, '87654321', '20190711172529', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(89, 1, '87654321', '20190711172552', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(90, 1, '87654321', '20190711172618', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(91, 1, '87654321', '20190711174435', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(92, 1, '87654321', '20190711175020', 60, NULL, 'her', NULL, NULL, NULL, NULL, ''),
(93, 1, '87654321', '20190721175944', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '29edfd172099acbd0a3904172917ab480ce9d4849840b001880cf286d4d3620de1175e93f1c516a094e814dcee7a530587ef5b8efd01d30f1bb3ad56df47caff'),
(99, 1, '87654321', '20190712125516', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '19945ab0ef16646bc2f54b0c91e21424afdfa250fed30c2e67d4167542bc7a0d88f38baa5f9ad6cb8174be4eae6f7c7408971b0e0f45b2957e56db6e9aeff0b9'),
(100, 1, '87654321', '20190712130111', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'd28a49dd5bcc80818c6bd166eeedd2a8ee3f34bb0712c5729cee3f2a0c7c783410462ee24fe51b1c506603a3049715cd28ffe21efb89436999bfb88d151b734a'),
(101, 1, '87654321', '20190721130303', 60, NULL, 'her', NULL, NULL, NULL, NULL, '8b29a32f537f15e6163bd7fe48786aa4772e8901f3e535973d40490167c633196e01077cab6e2d6084ce07fc393662a406f16fd9dcaba675fd5443101e7f529b'),
(102, 1, '87654321', '20190712130624', 60, NULL, 'her', NULL, NULL, NULL, NULL, '54a1009357653df7844b5153d51aa1556dfe962afd9810ca6f59c7dde647e48b47ad36501145f4ba35367c6c4bc0b3ffdf0b66ba0d4b326ce5e8afa5b8b2f0f7'),
(103, 1, '87654321', '20190721131301', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'd023df333bbc520e734eb0ff389342b4c565d8a062a3c0bcd7ff8a570966bc550187339b1d51c880dba93897b967cd17f1c71ebc26518f5c7ea44be4eb5a5204'),
(104, 1, '87654321', '20190712171252', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'beab37a735ea85849c194e0ce44ec7c7fd7f934575a889d9baac4df6679e412f50b68b99fcc279bed9f7a164644bdbc080abb622128c54ac1f3cdfe36f745c45'),
(105, 1, '87654321', '20190712171315', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'a9832cd1038fbaa87579c0787045142933a7148c12505e822f59e24eaf6b373e28a675725335aa96a86f6f0f3233837fccd86ea7679dd68b278b2784c5993ce1'),
(106, 1, '87654321', '20190712171626', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'be73a03a7fa2c86678a19c652f0593dad7b4052e122206b15a22c336307e6b5239da65d27289afebe2f31b647dbe633e66022e36937bc7c62842d0eb78339252'),
(107, 1, '87654321', '20190712172217', 60, NULL, 'her', NULL, NULL, NULL, NULL, '72fd4f75d554478c127ebb75291894a7faab0d3aac4986b18fbc634d3fb80cacf52f9c00c00d908fba415726ed36a8b0fbebc2bd531806a3d6ead790f6c29664'),
(108, 1, '87654321', '20190712172653', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'd80c0843175c7d2d72f28dba017cc1126dfff5b5eeecf9c8fdce8f91a3d7fcdffded2c15df4d3b06af9517e42dc91e738a840c7aaf8e097bf8ac9ff50a7f2cd9'),
(109, 1, '87654321', '20190712173548', 60, NULL, 'her', NULL, NULL, NULL, NULL, '641c797e32d6c54e5352cebdf07703f8fe5428d8eb039d31683cade4385336eb7e4a34f7852aacb56b0633d600aa619ed303606900685ce4ce9b082d22224112'),
(110, 1, '87654321', '20190712174110', 60, NULL, 'her', NULL, NULL, NULL, NULL, '77b52ce10620b061e793cb2d47f3fef4d0a84b3492e40f01e9534beb443cd8c2bc0de055606218bf18fb35af144f09252699fce1d1ca052b30af485ba1f840a2'),
(111, 1, '87654321', '20190712174141', 60, NULL, 'her', NULL, NULL, NULL, NULL, '008787a175ccfa972a9f616ea440ee0dfff6c56abee6cf237f6026af0e058bd166ae7b931557526871bbaa3a8cb7cae010f142f0f93b8e8846de65ec06bc217e'),
(112, 1, '87654321', '20190713173423', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, 'c508195c7f33605802847e0202caa6f70b37e781594e50ccdfac45ba3d589ddbe4c425c3351ad0732547149d6f9153f1aa2880a9e3d105c6a082875d8f5163b7'),
(113, 1, '87654321', '20190713173539', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'f64353113b3826de307a69f5c921b3c339171400ff709d9e76ece12b1dda7dadbce92c273cc10a471261982db641b51bbb57277421d9453bdd38a930c986c9a7'),
(114, 1, '87654321', '20190713173704', 64, NULL, 'her', NULL, NULL, NULL, NULL, '3d258a6a220139a6dcabdaebe4581687c508481e240db0a2ddb3e678c097242a66830587c491279ab765eacce262982882fae6563b97aae21d08df4cbe32b8c0'),
(115, 1, '87654321', '20190713182242', 64, NULL, 'her', NULL, NULL, NULL, NULL, 'ecf8f5ad438922b99d3b130c36ab9971fb0d643db35466afd53e1960132aef79bb0ecf91a9e1b1cc5e1b5597f9e6c8da7e9863b537ce27a7ea98eba245fda3aa'),
(116, 1, '87654321', '20190713182753', 64, NULL, 'her', NULL, NULL, NULL, NULL, 'dce298b411656673d53284a35f224a30ac39a67ed1edad076bd8aa8ab6325d3c65bcc2f082501e1c6c0893cb93b3584dcdf1fe6e6879c5d53c597580f0db99d4'),
(117, 1, '87654321', '20190713184454', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'f0d596931438ee0ba755c3d32d4b12f9ac034a98736ea928832805f32cd63d2998d3a15eae78d01b67671a646d11f0df5c51f1325e34d810e7aa610251529601'),
(118, 1, '87654321', '20190713214036', 60, NULL, 'her', NULL, NULL, NULL, NULL, '42ca8f76d799dc2bad9b11c7fe7ffe9f45586d27e2f809d614a9cbf5bfbc8c92c05bf900dd7790104fd09984182b25e8fd2c4e7ecad09acabc5bdd74856919fc'),
(119, 1, '87654321', '20190713214306', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'c5b97a1982d26442b8ac2142e8d5280ba65829f6da5aef30f95aa8c13b7b83d46445ab90cabafce1650c3a7cb024c357cd4b26fab0329a0496c97e71b5d1c5b0'),
(120, 1, '87654321', '20190713214606', 60, NULL, 'her', NULL, NULL, NULL, NULL, '00ff961280219646a595ec600bcd682fe3d86064df2f1ce15e77797788ece4054b6d1e4eb88f095c0b9282441312bd384f11c62da99efaf0c9be46aa099c947b'),
(121, 1, '87654321', '20190713215037', 60, NULL, 'her', NULL, NULL, NULL, NULL, '4515ff019cc7897b498932f43fe76cf3f90ac404e11c951b6acb4df66a91d99e2502a18a845ef6c85f1f6f86a9cbb86e65eebb48693de596aa4387255568abd2'),
(122, 1, '87654321', '20190713215219', 60, NULL, 'her', NULL, NULL, NULL, NULL, '4135a2cc3422981feecbf794c089fbecd8368e79d00dddc632251187c29c6991ed892bcebed3824c729945c0607bd3c96dacfc019651244b9e8fb3cacce591a6'),
(123, 1, '87654321', '20190713215649', 60, NULL, 'her', NULL, NULL, NULL, NULL, '4ab54dd8a720f622fda8b5eae38039fc09590b0701ee5f291fc6f1ab872cefb74834b17b40431527068ad313235b3aae6d178c0dc6e1f4ac662b03118d42b266'),
(124, 1, '87654321', '20190713215938', 60, NULL, 'her', NULL, NULL, NULL, NULL, '011f1583e8656e851609acc496ed7879060974ba23df71e3a7257937b944d800a12d51705ab27ab299036b6dfe762491e021cc4aa9d4fd679e168a2ae18a2ae7'),
(125, 1, '87654321', '20190713220152', 60, NULL, 'her', NULL, NULL, NULL, NULL, '3e013197243120eea3a86918e11545b1ad09624924822dd6001b4565d0ecd1ab447a9d757ee0537295be3510eafcf270d0db10cee5c2dacd823da889596ee9e7'),
(126, 1, '87654321', '20190713220357', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'ba591591bca796930497cd96b92c30390cc4fd45ef26746d9fd59c08e5fd79289dded8b3a535247256a0f09a307d6218ce8c58583aff48836fb619b90996c77c'),
(127, 1, '87654321', '20190713221240', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'a50fcc3555a623f4a2be6f231658bceef9127385fc7753208308ac365b9ba8315e1d3e0bbb74d4e254b64dfd98125aa50e981146b418f2a2d28643c3cf5573c9'),
(128, 1, '87654321', '20190713221542', 60, NULL, 'her', NULL, NULL, NULL, NULL, '3ea916172a6e73e3878c2fc87b65a6e9cc4304c3576a14ff6bbc82524831c52e6191427de7c1833d5667c48b3c85f24bdbc237437844597452a95be147023d59'),
(129, 1, '87654321', '20190713221849', 60, NULL, 'her', NULL, NULL, NULL, NULL, '45ba466153e1a44bfea86788df266191f4165b429febeb2aa63d926c791ed99a41b6fa36e5dd79d10f9bc2373932b35f4f3085e199bb6f7e368b1fe8dea953f9'),
(130, 1, '87654321', '20190713222305', 60, NULL, 'her', NULL, NULL, NULL, NULL, '8000c5aafdd42c1c516b2b92f0fa16a32a057462bb21ea4ffac2d2aee6dae3ff2e4fcfe76dfe09f1a73bff88567cf8a6f853cdc4253abefaca9252343e07aeed'),
(131, 1, '87654321', '20190713223941', 60, NULL, 'her', NULL, NULL, NULL, NULL, '419d55debea86b292203ba3a9130bd009595a82c184fe6f5ed0bc47a1abafa3f5ef306fe61fe76e9a5ce5b8fb80f7a16f66e62a77aec947ee24f62916c6ede89'),
(132, 1, '87654321', '20190713224244', 60, NULL, 'her', NULL, NULL, NULL, NULL, '33229817e157f637bcb89f81ea80371dd56fcf7ed8f6b6d6c91851ebc87e8c92c8c9ea2c5eb43fe7facb7f8684cc870d64cbf92ce2b70f8dd2a4d30beb2a36a2'),
(133, 1, '87654321', '20190713224354', 60, NULL, 'her', NULL, NULL, NULL, NULL, '2cec652e83c475485262d7ebb0610d9ad13c9011947d8f1b66b7f1fdf2bff15139f48138f0aefd26440e9f07ac99291172d316c563f657d799a2fb8531cb3830'),
(134, 1, '87654321', '20190713224709', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'c80fd24d4cb201ef7b7521a4bc48493b62739620ab42cca6901541836d02ab439968edd499f724a81b11d14cca09f35248b60a02df6cb898db7118a9f4d2f384'),
(135, 1, '87654321', '20190714163558', 59, NULL, 'her', 'supervisor', NULL, NULL, NULL, '645b77d2fcb7a80c2dcb6bce1ab3e15405220df69c7e1360c11079c7a2d8f08136d443840397edf19398f616212f96066f99d6f21147853adf1687275899a70f'),
(136, 1, '87654321', '20190714163730', 60, NULL, 'her', NULL, NULL, NULL, NULL, '949f39ac9adf93cf2a8fa2e08eefa0d38d81680d00860cd955144746b13098f5761420c07b1ac74f9892e9802a8de783ffe2e01229e837f3dc62d9d23536f77f'),
(137, 1, '87654321', '20190714164109', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'd6771289d2d70fcb5e66dc02c8ed86de883187e408ac82484f9eb9e133ba2a7bd771feb02a90e3fc1ee04152b4680b858c979dec3000255b228ee5e0720f8dab'),
(138, 1, '87654321', '20190714164214', 60, NULL, 'her', NULL, NULL, NULL, NULL, '8606ab31dc5a068d2dff2dd454afdd16e85af76c22cad9901c9782770019db3625f997f606732fcd77cd33ef5e8be3449ef273f2bee449d06a822d46a7313522'),
(139, 1, '87654321', '20190714164546', 60, NULL, 'her', NULL, NULL, NULL, NULL, '78d6a5e73516102f1ee5eff8179c8c47e71e128ef00a9f282e48f6b07b4da2bf156cfc542859e152fb3c6741a91e55ae12ec1303e112dabb2cf3e9599afb2192'),
(140, 1, '87654321', '20190714164735', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'a53eec2c86e407a812c0c36edb5c3f71d111b8e13024ec7371a4c5e7475dc3d5ff94a9c5bee91868d08c5a9c1d057b56d3ede16c0f324ff453eb2d372dac53d9'),
(141, 1, '87654321', '20190714165108', 60, NULL, 'her', NULL, NULL, NULL, NULL, '64654855af4641b6793b6463d9d32f36eed878fd193ae2426e27c30422634db84ce11c5c0b18dffe1a20f7d96481536a9af92769a0dfb549ac282377bf47a678'),
(142, 1, '87654321', '20190714165202', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'bc974616a27e6b6be94866a3a6509168f1c4e50b188ef20d0ed3442f25243799ed0f0f40752f7efebb68382f72076a0ce888e4bad537b273427ace55eb75b0e8'),
(143, 1, '87654321', '20190714165347', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'f489d27032705e4a05c93aa4d84d60dca48ac2c6dcfd41a7e0954e19c1ffaf762110b477ea60a5ed64ef4ebf37a260b14b5eb8097c9b195e12ab071f8d332efe'),
(144, 1, '87654321', '20190714165417', 60, NULL, 'her', NULL, NULL, NULL, NULL, '47d44c2443e107c607affbf24db4312af81ccb79f7d50b054045d8f35d094ee5e31ca866e086110be2c8954e25867762800535796aa4d52679cd6f06cfb4f936'),
(145, 1, '87654321', '20190714170344', 60, NULL, 'her', NULL, NULL, NULL, NULL, '8eb673679fd31ee1228d7825b94ca0463098604974488fdc825195dada6b82221f175ed15637429ea7f9669329eb56bb5f08d439432fa3af8bc422ed699ea657'),
(146, 1, '87654321', '20190714170541', 60, NULL, 'her', NULL, NULL, NULL, NULL, '65ea6837e1a3a34041ace99343b707571485ff2283fd352a3bc58b0b8d24493ecc737efc57400cbe094b87589e8d2c13908d46b380544efc11d427f48bb1afc9'),
(147, 1, '87654321', '20190714170600', 60, NULL, 'her', NULL, NULL, NULL, NULL, '87e41656e0a2a9a5dfa862bc947dd062d43c533311c9a8904fd2c8d23f24ced4fa8b4e75b1a32e3c0c9e7c4fb1761ff0638170751a5678a8ba9e22ccb73cd269'),
(148, 1, '87654321', '20190714170852', 60, NULL, 'her', NULL, NULL, NULL, NULL, '8faf74b066c9316f2cec2efd48e9529174ddeffe531431e46e12d45cbd370987cd2dc978676dfd403e3ad4deb52569da01578a13b7eb78fb1575b82529a1faa1'),
(149, 1, '87654321', '20190714170953', 60, NULL, 'her', NULL, NULL, NULL, NULL, '1fe8ff7d6e4ec8a029eb2729e1b633bde2fb0027a74b6400700c03c52d8ac9d2eff801f88d2f425c7912a4f8e638c05910d927caa32a7f3dd9f266dec48a2e95'),
(150, 1, '87654321', '20190714171445', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'f9c900e398bcb3af2deabbed5151e8dd442eb76c6b75479b13f0e3c672aa325cf7c5d29e2af29ae8ec8becccee53b0b52ff2c1eaee0c5ee7e8bbf2393820b071'),
(151, 1, '87654321', '20190714172245', 60, NULL, 'her', NULL, NULL, NULL, NULL, 'e8840ab289c8099fb2dc9c095d47fefd79895aac8f729e3c2af556c355f6d79d06e4eb6f75d5c894f55fd82e9e0dea32e1ff11e9b71fed06362ff9ca8d6144f5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

DROP TABLE IF EXISTS `genero`;
CREATE TABLE IF NOT EXISTS `genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes_x_mora`
--

DROP TABLE IF EXISTS `interes_x_mora`;
CREATE TABLE IF NOT EXISTS `interes_x_mora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan_credito` int(11) NOT NULL,
  `interes` int(11) NOT NULL,
  `cantidad_dias` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plan_credito` (`id_plan_credito`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `interes_x_mora`
--

INSERT INTO `interes_x_mora` (`id`, `id_plan_credito`, `interes`, `cantidad_dias`) VALUES
(2, 4, 10, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes_x_mora_cuota_credito`
--

DROP TABLE IF EXISTS `interes_x_mora_cuota_credito`;
CREATE TABLE IF NOT EXISTS `interes_x_mora_cuota_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `id_cuota_credito` bigint(20) NOT NULL,
  `cantidad_dias_mora` int(11) NOT NULL,
  `interes_x_mora` int(11) NOT NULL,
  `id_plan_credito` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_cuota_credito` (`id_cuota_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
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
('admin_sys', '1557514483', '10.146.127.125'),
('admin_sys', '1558382940', '10.146.127.125'),
('admin_sys', '1558443870', '10.146.127.125');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_usuario`
--

DROP TABLE IF EXISTS `log_usuario`;
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
) ENGINE=InnoDB AUTO_INCREMENT=797 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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
(796, 'her', '20190809170609', 2, 'Cierre de Sesion en Fecha y Hora: 2019-08-09 17:06:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mora_cuota_credito`
--

DROP TABLE IF EXISTS `mora_cuota_credito`;
CREATE TABLE IF NOT EXISTS `mora_cuota_credito` (
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha_interes` char(14) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_interes` int(11) NOT NULL,
  `porcentaje_interes` int(11) NOT NULL,
  PRIMARY KEY (`id_cuota_credito`,`fecha_interes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `mora_cuota_credito`
--

INSERT INTO `mora_cuota_credito` (`id_cuota_credito`, `fecha_interes`, `monto_interes`, `porcentaje_interes`) VALUES
(77, '20190705142205', 2555, 10),
(77, '20190806142210', 2010, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo`
--

DROP TABLE IF EXISTS `motivo`;
CREATE TABLE IF NOT EXISTS `motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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
(82, 'Cambio Estado Cuota Token', 'Cuando se realiza un cambio de estado de una cuota a través de un token.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_parcial_cuota_credito`
--

DROP TABLE IF EXISTS `pago_parcial_cuota_credito`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_seleccion_cuotas_credito`
--

DROP TABLE IF EXISTS `pago_seleccion_cuotas_credito`;
CREATE TABLE IF NOT EXISTS `pago_seleccion_cuotas_credito` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cuota_credito` bigint(20) NOT NULL,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `monto` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cuota_credito` (`id_cuota_credito`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_total_credito`
--

DROP TABLE IF EXISTS `pago_total_credito`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_total_credito_x_cuota`
--

DROP TABLE IF EXISTS `pago_total_credito_x_cuota`;
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

DROP TABLE IF EXISTS `parametros`;
CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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
(22, 'cantidad_horas_entre_procesos_auto', 'Es la cantidad de horas entre ejecución de procesos automáticos.', '8');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `perfil_credito`;
CREATE TABLE IF NOT EXISTS `perfil_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `monto_maximo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `perfil_credito_x_plan`;
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
(2, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_credito`
--

DROP TABLE IF EXISTS `plan_credito`;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `provincia`;
CREATE TABLE IF NOT EXISTS `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `sucursal`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `telefono`;
CREATE TABLE IF NOT EXISTS `telefono` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_telefono` int(11) NOT NULL,
  `numero` bigint(20) NOT NULL,
  `digitos_prefijo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_telefono` (`tipo_telefono`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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
(60, 1, 3513827932, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_aviso`
--

DROP TABLE IF EXISTS `tipo_aviso`;
CREATE TABLE IF NOT EXISTS `tipo_aviso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

DROP TABLE IF EXISTS `tipo_documento`;
CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `tipo_telefono`;
CREATE TABLE IF NOT EXISTS `tipo_telefono` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

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

DROP TABLE IF EXISTS `token_adicional_cuenta`;
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
  KEY `tipo_documento_titular` (`tipo_documento_titular`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `token_adicional_cuenta`
--

INSERT INTO `token_adicional_cuenta` (`id`, `fecha`, `documento`, `documento_titular`, `token`, `usuario`, `usuario_supervisor`, `tipo_documento`, `tipo_documento_titular`) VALUES
(7, '20190606163419', '31443194', '87654321', 'f3581ddc9e09c407b09f95851bdbd70ad29b06c285fe14aeaeb80c8185d93eed4355fa9771ff58b3b398b835956cea98e5f4d98d9c3119fa0eda571b9d0b0e57', 'her', 'supervisor', 1, 1),
(8, '20190606164318', '37443194', '35443194', '1070135afef163ac1b30354780652fbeeb6f359c322fc89f649ad01beef6d6b6578225d3ed7f10afcb0160887477c7e39b7b63d3f939cec3f3e6667e03f9c4ac', 'her', 'supervisor', 1, 1),
(9, '20190705171956', '64443194', '87654321', '94491a6b8ec4ae631db3b5d7c838f3484646f0d4452e631d80e6f7c2f968d7789fb3c27067fd4286d2111af01c8d8c0c35f680c535b11d0b9e2610e1c2af26ed', 'her', 'supervisor', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_cambio_cuenta`
--

DROP TABLE IF EXISTS `token_cambio_cuenta`;
CREATE TABLE IF NOT EXISTS `token_cambio_cuenta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `token` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_supervisor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `validado` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `token_cambio_cuenta`
--

INSERT INTO `token_cambio_cuenta` (`id`, `fecha`, `documento`, `tipo_documento`, `token`, `usuario`, `usuario_supervisor`, `validado`) VALUES
(3, '20190621155126', '37443194', 1, '52df1c95cfa96c158254834fcb9df1d7b75119175d26067c05946164a3162f8179662a793eb22ed7baf93760f7f60c598eb07e552188d264326df524c40ff676', 'her', 'SUPERVISOR', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_cambio_estado_cuota`
--

DROP TABLE IF EXISTS `token_cambio_estado_cuota`;
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
  KEY `id_cuota_credito` (`id_cuota_credito`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

DROP TABLE IF EXISTS `token_pago_cuota`;
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
  KEY `usuario_supervisor` (`usuario_supervisor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_validacion_celular`
--

DROP TABLE IF EXISTS `token_validacion_celular`;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `token_validacion_celular`
--

INSERT INTO `token_validacion_celular` (`id`, `Fecha`, `tipo_documento`, `documento`, `token`, `codigo`, `usuario`, `validado`, `nro_telefono`) VALUES
(15, '20190607110911', 1, '41443194', '9e2e426928dd54388368484d0a37c4fbd544a1db10d500557de00e1189a5f668f687baa6f84b44d19d45edf03e41c47b761e8d4d56dca58a84fa339be8eed7fc', '5759', 'admin_sys', b'1', NULL),
(16, '20190703121608', 0, '', '9773219c243898cafda9045b177121c234a560d2cf95064fd6c41b591bdb7e7396ca52c654c308a2d32f117cc8c21c257a6767bb76742d5d2b37fbcff58aacc5', '3250', 'her', b'0', NULL),
(17, '20190703122159', 0, '', '52d70c26b1af1a799ff1875a45af50b3fdfb57f01d6da9f5903bc0a9fe18c48d032dc8917d48330bcbcaf025d98822c4f37548b28db28fc0e65562848f19a0c9', '5133', 'her', b'0', NULL),
(18, '20190703123017', 1, '87654321', 'e31ea8d4524787260fe99af89edaf669d2cc51f84b684484a25f0e9064c63c090039ad2bca438bb8473935cc81a321277c0f32cafd19bdbcebc0779b872dbcd7', '0274', 'her', b'1', NULL),
(20, '20190704214649', 1, '87654321', '86ac66801d194a03492f867193181c8ef35528c9e4409b92050de053e9b3d96078891e873371c329b9223687264c8b105dc079113c66fa16164a0a9cf9b3c066', '4802', 'her', b'1', 3513827932),
(21, '20190705125929', 1, '87654321', 'f924c62e712167f431a1c387112587baab51c38e127fc866f8b40a64a8c822d5f78e143c05a87f59bcfddf65882b424632d05b36a84bc91a6e738b386ebd259e', '7282', 'her', b'1', 3513827932),
(22, '20190708104817', 1, '87654321', '87f687a3e6eb030b9757f098df3eb7cb950ea8edf14f889152dae3934bb303acfb234d6936914341be25f5b3f9f37994663aae73851419adaf2cea6fc9413b13', '8096', 'her', b'1', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
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
('her', 'herbasio', 'serio', 1, '2323211', 'ferd1@gmail.com', 2, 2, 'Habilitado', 'f39a333b451ab6ca4443271b18426e7e1df15d29d860cccea80d90c59aa6a989aa934548e08e0c7fd493c41a3af381f5b6d8802e398d15c7b3f5387095ab052b', '65f03e90f3a7d64a5c219c0f6e7ba8acc14d81fc1ff016d3c6f2041f8e5a5fcd1954e645f5068654603e32e742cce0f10e3a2ecc2cd069c35eaaeaa952eff2a7'),
('servi', 'gerad', 'jjkk', 1, '1234', 'ger@lib.com.ar', 2, 1, 'Deshabilitado', '41dc55d092a712bdd817a1091bae5118fbb4d8322bca0c002cfe63204412d5afb854ba348df0fa5f56b78b9d4bcab496e0a6b8e21d290cb6742e6097fd82d32e', '7a3d4996840a580e4ec6637e4df842726dc4cbb3666b675be9564410a17ba838e7061b4cad3132b9124ae228da7d153206b43ba4b05a95e13100641b2c8b45b2'),
('supervisor', 'Supervisa', 'TODO', 1, '1234123', 'teestamosobservando@segu.com', 3, 1, 'Habilitado', '30664284d8506bf354ba109da9e56c78bf038867db8a55fca72d2a1021ee5473a6d76d56c2d59f2e53825a6919a4fb3da0f057bca8c9fce3b6ad9c1a2cc1424e', 'fcbd814ca4f11378c0b2f38c30265e6ce290ff3d82a1dac1afa9d98fda501e49aab1365f4e634bc9db4df9a04651caae211233601860819efa052bf5a8bfe29c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_x_domicilio`
--

DROP TABLE IF EXISTS `usuario_x_domicilio`;
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

DROP TABLE IF EXISTS `usuario_x_telefono`;
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
  ADD CONSTRAINT `aviso_x_mora_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `aviso_x_mora_ibfk_2` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`),
  ADD CONSTRAINT `aviso_x_mora_ibfk_3` FOREIGN KEY (`id_tipo_aviso`) REFERENCES `tipo_aviso` (`id`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_perfil_credito`) REFERENCES `perfil_credito` (`id`),
  ADD CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id`);

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
  ADD CONSTRAINT `consulta_estado_financiero_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `consulta_estado_financiero_ibfk_2` FOREIGN KEY (`tipo_documento_adicional`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `credito_cliente`
--
ALTER TABLE `credito_cliente`
  ADD CONSTRAINT `credito_cliente_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_5` FOREIGN KEY (`id_credito`) REFERENCES `credito` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_6` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `credito_cliente_ibfk_7` FOREIGN KEY (`tipo_documento_adicional`) REFERENCES `tipo_documento` (`id`);

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
-- Filtros para la tabla `interes_x_mora_cuota_credito`
--
ALTER TABLE `interes_x_mora_cuota_credito`
  ADD CONSTRAINT `interes_x_mora_cuota_credito_ibfk_1` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`);

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
  ADD CONSTRAINT `token_adicional_cuenta_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `token_adicional_cuenta_ibfk_2` FOREIGN KEY (`tipo_documento_titular`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `token_cambio_estado_cuota`
--
ALTER TABLE `token_cambio_estado_cuota`
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_3` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_4` FOREIGN KEY (`id_motivo`) REFERENCES `motivo` (`id`),
  ADD CONSTRAINT `token_cambio_estado_cuota_ibfk_5` FOREIGN KEY (`id_cuota_credito`) REFERENCES `cuota_credito` (`id`);

--
-- Filtros para la tabla `token_pago_cuota`
--
ALTER TABLE `token_pago_cuota`
  ADD CONSTRAINT `token_pago_cuota_ibfk_1` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`id`),
  ADD CONSTRAINT `token_pago_cuota_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `token_pago_cuota_ibfk_3` FOREIGN KEY (`usuario_supervisor`) REFERENCES `usuario` (`id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
