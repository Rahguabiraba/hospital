-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-10-2021 a las 12:47:31
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hospital`
--
CREATE DATABASE IF NOT EXISTS `hospital` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `hospital`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_paciente`
--

DROP TABLE IF EXISTS `log_paciente`;
CREATE TABLE `log_paciente` (
  `idlogpaciente` int(11) NOT NULL,
  `accion` char(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `idpaciente` int(11) NOT NULL,
  `nif` char(9) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `fechaingreso` date NOT NULL,
  `fechaalta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

DROP TABLE IF EXISTS `paciente`;
CREATE TABLE `paciente` (
  `idpaciente` int(11) NOT NULL,
  `nif` varchar(10) CHARACTER SET latin1 NOT NULL,
  `nombre` varchar(45) CHARACTER SET latin1 NOT NULL,
  `apellidos` varchar(100) CHARACTER SET latin1 NOT NULL,
  `fechaingreso` date NOT NULL,
  `fechaalta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`idpaciente`, `nif`, `nombre`, `apellidos`, `fechaingreso`, `fechaalta`) VALUES
(19, '40000000A', 'Jose Luis', 'Torrente', '2012-12-15', NULL),
(38, '41000000B', 'Mariana', 'Cuchufletas', '2003-02-01', NULL),
(40, '42000000C', 'Juanita', 'Pepinillo', '2000-02-02', NULL),
(49, '44000000E', 'Perico', 'El de los palotes', '2008-10-10', NULL),
(50, '45000000F', 'Johny', 'Mentero', '2001-04-02', NULL),
(51, '46000000G', 'Doctor', 'Maligno', '2011-01-01', '2020-09-08'),
(56, '47000000H', 'Marianico', 'El Corto', '2000-01-06', '2020-09-07'),
(59, '55556677H', 'Pablo', 'Marmol', '2017-03-01', NULL),
(60, '10000006H', 'Louis', 'Griffin', '2020-08-05', NULL),
(61, '43330014L', 'John', 'Rambo', '2020-09-01', '2020-09-04'),
(63, '40880014L', 'Filomena', 'Pi', '2020-09-29', NULL),
(64, '40888014L', 'Mortadelo', 'Percebe', '2020-09-02', NULL);

--
-- Disparadores `paciente`
--
DROP TRIGGER IF EXISTS `deletepaciente`;
DELIMITER $$
CREATE TRIGGER `deletepaciente` BEFORE DELETE ON `paciente` FOR EACH ROW INSERT INTO log_paciente VALUES (NULL, 'D', CURRENT_TIMESTAMP, OLD.idpaciente, OLD.nif, OLD.nombre, OLD.apellidos, OLD.fechaingreso, OLD.fechaalta)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `insertpaciente`;
DELIMITER $$
CREATE TRIGGER `insertpaciente` AFTER INSERT ON `paciente` FOR EACH ROW INSERT INTO log_paciente VALUES (NULL, 'I', CURRENT_TIMESTAMP, NEW.idpaciente, NEW.nif, NEW.nombre, NEW.apellidos, NEW.fechaingreso, NEW.fechaalta)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updatepaciente`;
DELIMITER $$
CREATE TRIGGER `updatepaciente` BEFORE UPDATE ON `paciente` FOR EACH ROW INSERT INTO log_paciente VALUES (NULL, 'U', CURRENT_TIMESTAMP, OLD.idpaciente, OLD.nif, OLD.nombre, OLD.apellidos, OLD.fechaingreso, OLD.fechaalta)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nif` char(9) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nif`, `nombre`, `apellidos`, `password`) VALUES
(1, '40000001A', 'David', 'Alcolea', 'admin'),
(2, '42000002T', 'Gogo', 'Yubari', 'gogo'),
(3, '42006602T', 'Tucco', 'Beneditto', 'tucco');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `log_paciente`
--
ALTER TABLE `log_paciente`
  ADD PRIMARY KEY (`idlogpaciente`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`idpaciente`),
  ADD UNIQUE KEY `nif_UNIQUE` (`nif`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `nif` (`nif`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `log_paciente`
--
ALTER TABLE `log_paciente`
  MODIFY `idlogpaciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `idpaciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
