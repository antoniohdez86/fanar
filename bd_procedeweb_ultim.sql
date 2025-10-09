/*
Navicat MySQL Data Transfer

Source Server         : mySQLConexion
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : bd_procedeweb

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2012-03-23 02:39:38
*/

SET FOREIGN_KEY_CHECKS=0;
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
INSERT INTO `aj_empleados` VALUES ('1', 'hector', 'larragoiti', 'sanchez', 'atlapexco', 'tsu', '7711446639', '0', '0');
INSERT INTO `aj_empleados` VALUES ('2', 'Antonio de Jesus', 'hernandez', 'hernandez', 'huejutla', 'lic. informatica', '7711312084', '0', '1');
INSERT INTO `aj_empleados` VALUES ('3', 'Jose Antonio', 'Garcia', 'Melendez', 'Col.Jerico', 'Lic.Informatica', '775888996', '1', '1');
INSERT INTO `aj_empleados` VALUES ('4', 'Jose Antoniox', 'Garcia', 'Melendezx', 'Col.Jerico', 'Lic.Informaticax', '775888996', '0', '0');
INSERT INTO `aj_empleados` VALUES ('400', 'Maria Guadalupex', 'gomezx', 'Melendezx', 'Col. Cap. Antonio Reyesx', 'Lic.Informaticax', '775888998', '1', '0');
INSERT INTO `aj_empleados` VALUES ('401', 'JoseX', 'ReyesX', 'MelendezX', 'Col.JericoX', 'Lic.InformaticaX', '775888996X', '1', '1');
INSERT INTO `aj_empleados` VALUES ('402', 'Pedro', 'Velazques', 'Ibarra', 'Acayahuatl', 'Ing. Agronomo', '88888', '1', '0');
INSERT INTO `aj_empleados` VALUES ('403', 'CHUCHIN', 'JUAREZ', 'ORTEGA', 'CONOCIDA', 'NINGUNA', '99999', '1', '0');

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
INSERT INTO `aj_municipios` VALUES ('1', 'Atlapexco');
INSERT INTO `aj_municipios` VALUES ('100', 'HUEJUTLA');
INSERT INTO `aj_municipios` VALUES ('2', 'Huejutla');
INSERT INTO `aj_municipios` VALUES ('3', 'Molango');
INSERT INTO `aj_municipios` VALUES ('4', 'Tepehuacan de Guerrero');
INSERT INTO `aj_municipios` VALUES ('5', 'Xochiatipan');

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
  PRIMARY KEY  (`codigo`),
  KEY `codigoMunicipio` (`codigoMunicipio`),
  CONSTRAINT `aj_nucleosagrarios_ibfk_1` FOREIGN KEY (`codigoMunicipio`) REFERENCES `aj_municipios` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_nucleosagrarios
-- ----------------------------
INSERT INTO `aj_nucleosagrarios` VALUES ('1', 'macuxtepetla', 'ejido', '1000', '1');
INSERT INTO `aj_nucleosagrarios` VALUES ('2', 'Atlalco y Tepeolol II', 'comunidad', '1000', '2');
INSERT INTO `aj_nucleosagrarios` VALUES ('3', 'Chililico', 'comunidad', '2000', '2');
INSERT INTO `aj_nucleosagrarios` VALUES ('4', 'Ixcatlan', 'ejido', '1500', '3');
INSERT INTO `aj_nucleosagrarios` VALUES ('5', 'Ixcuicuila', 'ejido', '800', '3');
INSERT INTO `aj_nucleosagrarios` VALUES ('6', 'San Miguel Atoyempa', 'comunidad', '3000', '4');
INSERT INTO `aj_nucleosagrarios` VALUES ('7', 'Ixtaczoquico', 'ejido', '800', '5');
INSERT INTO `aj_nucleosagrarios` VALUES ('8', 'Barrio Arriba', 'comunidad', '1300', '2');

-- ----------------------------
-- Table structure for `aj_seguimientos`
-- ----------------------------
DROP TABLE IF EXISTS `aj_seguimientos`;
CREATE TABLE `aj_seguimientos` (
  `idEmpleado` int(10) NOT NULL,
  `fechaSeguimiento` date NOT NULL,
  `idMunicipio` varchar(10) NOT NULL,
  `idNucleo` varchar(10) NOT NULL,
  `etapaMaxima` varchar(300) NOT NULL,
  `problematica` text NOT NULL,
  `compromisos_situacion_cumplimiento` text NOT NULL,
  `notas` text NOT NULL,
  `estadoActual` int(2) NOT NULL,
  KEY `idEmpleado` (`idEmpleado`),
  KEY `idMunicipio` (`idMunicipio`),
  KEY `idNucleo` (`idNucleo`),
  CONSTRAINT `aj_seguimientos_ibfk_3` FOREIGN KEY (`idNucleo`) REFERENCES `aj_nucleosagrarios` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `aj_seguimientos_ibfk_1` FOREIGN KEY (`idEmpleado`) REFERENCES `aj_empleados` (`idEmpleado`) ON UPDATE CASCADE,
  CONSTRAINT `aj_seguimientos_ibfk_2` FOREIGN KEY (`idMunicipio`) REFERENCES `aj_municipios` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aj_seguimientos
-- ----------------------------

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
INSERT INTO `aj_usuarios` VALUES ('antoniox', 'antoniox', '0', '3');
INSERT INTO `aj_usuarios` VALUES ('josex', 'josexx', '0', '401');
