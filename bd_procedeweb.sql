/*
Navicat MySQL Data Transfer

Source Server         : mySQLConexion
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : bd_procedeweb

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2012-04-26 11:10:07
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `aj_empleado_nucleos`
-- ----------------------------
DROP TABLE IF EXISTS `aj_empleado_nucleos`;
CREATE TABLE `aj_empleado_nucleos` (
  `idEmpleado` int(4) NOT NULL,
  `idNucleo` varchar(10) NOT NULL,
  PRIMARY KEY  (`idNucleo`),
  KEY `idEmpleado` (`idEmpleado`),
  CONSTRAINT `aj_empleado_nucleos_ibfk_1` FOREIGN KEY (`idEmpleado`) REFERENCES `aj_empleados` (`idEmpleado`) ON UPDATE CASCADE,
  CONSTRAINT `aj_empleado_nucleos_ibfk_2` FOREIGN KEY (`idNucleo`) REFERENCES `aj_nucleosagrarios` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_empleado_nucleos
-- ----------------------------
INSERT INTO `aj_empleado_nucleos` VALUES ('2', '100');
INSERT INTO `aj_empleado_nucleos` VALUES ('2', '102');
INSERT INTO `aj_empleado_nucleos` VALUES ('2', '130132');
INSERT INTO `aj_empleado_nucleos` VALUES ('8', '130135');

-- ----------------------------
-- Table structure for `aj_empleados`
-- ----------------------------
DROP TABLE IF EXISTS `aj_empleados`;
CREATE TABLE `aj_empleados` (
  `idEmpleado` int(4) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apePat` varchar(20) NOT NULL,
  `apeMat` varchar(20) NOT NULL,
  `direccion` varchar(70) NOT NULL,
  `profesion` varchar(40) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `activo` int(2) NOT NULL default '0',
  `tieneCuenta` int(2) NOT NULL default '0',
  PRIMARY KEY  (`idEmpleado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_empleados
-- ----------------------------
INSERT INTO `aj_empleados` VALUES ('1', 'Hector', 'Larragoiti', '----', 'Huejutla Hgo.', 'Lic. Rec. Humanos', '778963', '1', '1');
INSERT INTO `aj_empleados` VALUES ('2', 'Antonio de JesÃºs', 'HernÃ¡ndez', 'HernÃ¡ndez', 'Huejutla Hgo.', 'Lic. Informatica', '7711312084', '1', '1');
INSERT INTO `aj_empleados` VALUES ('3', 'HORACIO', 'GIL', 'HERNANDEZ', 'desconocido', 'desconocido', '--------', '0', '1');
INSERT INTO `aj_empleados` VALUES ('4', 'HORTENSIA', 'MELLA', 'PEREZ', 'desconocido', 'desconocido', '------', '0', '1');
INSERT INTO `aj_empleados` VALUES ('5', 'JULIA', 'SANCHEZ', 'SANCHEZ', 'desconocido', 'desconocido', '778899', '0', '0');
INSERT INTO `aj_empleados` VALUES ('6', 'JUSTA', 'IRIGOYEN', 'ORTEGA', 'desconocido', 'desconocido', '8899', '1', '0');
INSERT INTO `aj_empleados` VALUES ('7', 'JUSTINO', 'CELSI', 'GOMEZ', 'desconocido', 'desconocido', '88996333', '1', '0');
INSERT INTO `aj_empleados` VALUES ('8', 'MARIO', 'LEÃ“N', 'CARRIZO', 'desconocido', 'desconocido', '-------', '0', '1');
INSERT INTO `aj_empleados` VALUES ('9', 'BRENDA', 'BARTOLO', 'AVILA', 'Col. Rojo Gomez', 'Lic. Medicina', '789', '0', '0');

-- ----------------------------
-- Table structure for `aj_etapas`
-- ----------------------------
DROP TABLE IF EXISTS `aj_etapas`;
CREATE TABLE `aj_etapas` (
  `idEtapa` int(2) NOT NULL,
  `eta` int(2) NOT NULL,
  `act` int(2) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `activo` int(2) NOT NULL,
  PRIMARY KEY  (`idEtapa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_etapas
-- ----------------------------
INSERT INTO `aj_etapas` VALUES ('1', '0', '1', 'ETA0 etapa 0 actividad 1', '1');
INSERT INTO `aj_etapas` VALUES ('2', '1', '3', 'ETA1 etapa 1 actividad 3', '1');
INSERT INTO `aj_etapas` VALUES ('3', '2', '20', 'ETA2 etapa 2 actividad 20', '1');
INSERT INTO `aj_etapas` VALUES ('4', '3', '30', 'Inicio de los trabajos de medicion (RAN)', '0');
INSERT INTO `aj_etapas` VALUES ('5', '4', '40', 'Termino de los trabajos de medicion (RAN)', '0');
INSERT INTO `aj_etapas` VALUES ('6', '5', '50', 'Planos preliminares', '0');
INSERT INTO `aj_etapas` VALUES ('7', '5', '51', 'Convocatoria a Asamblea de aprobacion de planos 1era. (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('8', '5', '52', 'Convocatoria a Asamblea de aprobacion de planos 2era. (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('9', '6', '60', 'Asamblea de aprobacion de planos (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('10', '7', '70', 'Entrega de planos definitivos (RAN)', '0');
INSERT INTO `aj_etapas` VALUES ('11', '7', '71', 'Convocatoria a ADDAT 1era. (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('12', '7', '72', 'Convocatoria a ADDAT 2era. (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('13', '7', '73', 'Convocatoria a ADDAT ulterior. (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('14', '8', '80', 'ADDAT (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('15', '9', '90', 'Ingreso expediente general RAN (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('16', '9', '91', 'Regresado (RAN)', '0');
INSERT INTO `aj_etapas` VALUES ('17', '9', '92', 'Reingreso (PA)', '1');
INSERT INTO `aj_etapas` VALUES ('18', '10', '100', 'Certificacion (RAN)', '0');

-- ----------------------------
-- Table structure for `aj_municipios`
-- ----------------------------
DROP TABLE IF EXISTS `aj_municipios`;
CREATE TABLE `aj_municipios` (
  `codigo` varchar(10) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_municipios
-- ----------------------------
INSERT INTO `aj_municipios` VALUES ('100', 'Huejutla');
INSERT INTO `aj_municipios` VALUES ('101', 'Atlapexco');
INSERT INTO `aj_municipios` VALUES ('11', 'ATLAPEXCO');
INSERT INTO `aj_municipios` VALUES ('14', 'CALNALI');
INSERT INTO `aj_municipios` VALUES ('20', 'ELOXOCHITLAN');
INSERT INTO `aj_municipios` VALUES ('25', 'HUAUTLA');
INSERT INTO `aj_municipios` VALUES ('26', 'HUAZALINGO');
INSERT INTO `aj_municipios` VALUES ('28', 'HUEJUTLA DE REYES');
INSERT INTO `aj_municipios` VALUES ('32', 'JALTOCAN');
INSERT INTO `aj_municipios` VALUES ('33', 'JUAREZ');
INSERT INTO `aj_municipios` VALUES ('34', 'LOLOTLA');
INSERT INTO `aj_municipios` VALUES ('42', 'LOLOTLA');
INSERT INTO `aj_municipios` VALUES ('46', 'ORIZATLAN');
INSERT INTO `aj_municipios` VALUES ('62', 'TEPEHUACAN');
INSERT INTO `aj_municipios` VALUES ('68', 'TIANGUISTENGO');
INSERT INTO `aj_municipios` VALUES ('73', 'TLANCHINOL');
INSERT INTO `aj_municipios` VALUES ('78', 'XOCHIATIPAN');
INSERT INTO `aj_municipios` VALUES ('79', 'XOCHICOATLAN');
INSERT INTO `aj_municipios` VALUES ('80', 'YAHUALICA');
INSERT INTO `aj_municipios` VALUES ('84', 'nucleo de pruebaxx');

-- ----------------------------
-- Table structure for `aj_nucleosagrarios`
-- ----------------------------
DROP TABLE IF EXISTS `aj_nucleosagrarios`;
CREATE TABLE `aj_nucleosagrarios` (
  `codigo` varchar(10) NOT NULL,
  `nucleoAgrario` varchar(100) NOT NULL,
  `tipoNucleo` varchar(30) NOT NULL,
  `superficie` varchar(40) NOT NULL,
  `codigoMunicipio` varchar(10) NOT NULL,
  `situacion` int(2) NOT NULL,
  PRIMARY KEY  (`codigo`),
  KEY `codigoMunicipio` (`codigoMunicipio`),
  CONSTRAINT `aj_nucleosagrarios_ibfk_1` FOREIGN KEY (`codigoMunicipio`) REFERENCES `aj_municipios` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_nucleosagrarios
-- ----------------------------
INSERT INTO `aj_nucleosagrarios` VALUES ('100', 'nucleo1', 'Ejido', '1000', '11', '1');
INSERT INTO `aj_nucleosagrarios` VALUES ('102', 'nucleo 2', 'Ejido', '1000', '100', '2');
INSERT INTO `aj_nucleosagrarios` VALUES ('103', 'nucleo3', 'Ejido', '100', '100', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130132', 'PAATLA Y SU ANEXO SANTO TOMAS', 'Ejido', '0', '11', '1');
INSERT INTO `aj_nucleosagrarios` VALUES ('130133', 'TECACAHUACO', 'Ejido', '0', '11', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130134', 'TENEXCO, ATLALTIPA HUITZOTLACO Y ANEXOS', 'Ejido', '0', '11', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130135', 'COCHISCUATITLA Y SUS ANEXOS ATLALCO Y TLACHAPA', 'Ejido', '0', '11', '1');
INSERT INTO `aj_nucleosagrarios` VALUES ('130136', 'COCHOTLA Y ANEXOS', 'Ejido', '0', '11', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130137', 'ATLAPEXCO Y SU ANEXO ATLALTIPA', 'Ejido', '0', '11', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130173', 'CENTRAL CAMPESINA CARDENISTA', 'Ejido', '0', '14', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130257', 'ALMOLOYA', 'Comunidad', '0', '20', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130261', 'IXTACAPA', 'Ejido', '0', '20', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130323', 'COAPANTLA', 'Ejido', '0', '25', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130346', 'BARRIO HONDO', 'Comunidad', '0', '25', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130354', 'CUAMONTAX', 'Ejido', '0', '26', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130356', 'SANTO TOMAS', 'Comunidad', '0', '26', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130372', 'SANTA CRUZ', 'Ejido', '0', '28', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130379', 'SAN ANTONIO', 'Ejido', '0', '28', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130488', 'LA CAPILLA', 'Ejido', '0', '32', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('130498', 'TOLTITLA', 'Comunidad', '0', '32', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('131178', 'ATLAPEXCO Y TECOLOTITLA', 'Ejido', '0', '11', '0');
INSERT INTO `aj_nucleosagrarios` VALUES ('131188', 'MAX AGUSTIN CORREA HERNANDEZ', 'Ejido', '0', '14', '0');

-- ----------------------------
-- Table structure for `aj_seguimientos`
-- ----------------------------
DROP TABLE IF EXISTS `aj_seguimientos`;
CREATE TABLE `aj_seguimientos` (
  `idNucleo` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `idEtapa` int(2) NOT NULL,
  `observaciones` varchar(300) NOT NULL,
  `problematica` text NOT NULL,
  `compromisos_situacion_cumplimiento` text NOT NULL,
  `notas` text NOT NULL,
  `estadoActual` int(2) NOT NULL,
  KEY `idNucleo` (`idNucleo`),
  KEY `idEtapa` (`idEtapa`),
  CONSTRAINT `aj_seguimientos_ibfk_3` FOREIGN KEY (`idNucleo`) REFERENCES `aj_nucleosagrarios` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `aj_seguimientos_ibfk_4` FOREIGN KEY (`idEtapa`) REFERENCES `aj_etapas` (`idEtapa`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_seguimientos
-- ----------------------------
INSERT INTO `aj_seguimientos` VALUES ('102', '1986-02-25', '2', 'segundo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '1986-02-26', '3', 'tercer seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '1986-02-27', '7', 'cuarto seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-24', '1', 'primera actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-25', '7', 'cuarta actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-26', '3', 'tercera actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-27', '9', 'sexta actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-28', '2', 'segunda actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-02-29', '8', 'quinta actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-02-28', '8', 'quinto seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-02-29', '9', 'sexto seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '0000-00-00', '11', 'septimo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '0000-00-00', '12', 'octavo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-03-01', '13', 'noveno seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-03-02', '14', 'decimo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-03-04', '15', 'onceavo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-03-05', '17', 'doceavo seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('102', '2012-03-01', '1', 'primer seguimiento', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('100', '2012-04-10', '11', 'septima actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('130132', '2012-04-21', '1', 'esta es la primera actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('130132', '2012-04-22', '2', 'esta es la segunda actividad', 'ninguno', 'ninguno', 'ninguno', '0');
INSERT INTO `aj_seguimientos` VALUES ('130132', '2012-04-23', '3', 'esta es la tercera actividad', 'ninguno', 'ninguno', 'ninguno', '0');

-- ----------------------------
-- Table structure for `aj_usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `aj_usuarios`;
CREATE TABLE `aj_usuarios` (
  `usuario` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `isAdmin` int(1) NOT NULL default '0',
  `idEmpleado` int(4) NOT NULL,
  PRIMARY KEY  (`usuario`),
  KEY `idEmpleado` (`idEmpleado`),
  CONSTRAINT `aj_usuarios_ibfk_1` FOREIGN KEY (`idEmpleado`) REFERENCES `aj_empleados` (`idEmpleado`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_usuarios
-- ----------------------------
INSERT INTO `aj_usuarios` VALUES ('admin', 'adminx', '1', '2');
INSERT INTO `aj_usuarios` VALUES ('hector', 'hectorxx', '0', '1');
INSERT INTO `aj_usuarios` VALUES ('horacio', 'horaciox', '0', '3');
INSERT INTO `aj_usuarios` VALUES ('hortencia', 'hortenciax', '0', '4');
INSERT INTO `aj_usuarios` VALUES ('mario', 'marioxx', '0', '8');
