-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2021 a las 02:26:10
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api`
--
DROP DATABASE IF EXISTS `api`;
CREATE DATABASE `api`;
USE `api`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `personas` (`username`, `password`, `token`, `nombre`, `disponible`) VALUES
('nacho', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjUzNTAwODgsImRhdGEiOnsiaWQiOiIxMDUiLCJub21icmVzIjoiRGVuaXMxMjMifX0.85Tr6HHgdMxMKwHKkhMHMKzCNzjbHisLyWBv14-AXZU', 'Nacho', 1),
('', '', '', 'MARTIN', 0),
('', '', '', 'LEON', 1),
('', '', '', 'JORGE', 1),
('', '', '', 'SEGUNDO', 1),
('', '', '', 'JORGE', 0),
('', '', '', 'PABLO FELIPE', 1),
('', '', '', 'NELIDA ANA', 1),
('', '', '', 'MIGUEL JOSE', 1),
('', '', '', 'GUSTAVO SAMUEL', 1),
('', '', '', 'CRISTINA Y ELIDA', 1),
('', '', '', 'ELIDA ROSA', 1),
('', '', '', 'JUAN', 1),
('', '', '', 'SAMUEL VALERIO', 1),
('', '', '', 'ADOLFO', 1),
('', '', '', 'EDUARDO DAVID', 1),
('', '', '', 'DELIA', 1),
('', '', '', 'JOSE LUIS', 1),
('', '', '', 'JOSE', 1),
('', '', '', 'ROSA', 1),
('', '', '', 'OMAR ORLANDO', 1),
('', '', '', 'FELISA', 1),
('', '', '', 'ANASTACIA', 1),
('', '', '', 'MARIA CARINA ROSANA', 1),
('', '', '', 'RUBEN ORLANDO', 1),
('', '', '', 'OMAR DOMINGO', 1),
('', '', '', 'RUBEN ROBERTO', 1),
('', '', '', 'GUSTAVO', 1),
('', '', '', 'RAMON ANDRES', 1),
('', '', '', 'ESTEBAN', 1),
('', '', '', 'MARIA', 1),
('', '', '', 'CARLOS ENRIQUE', 1),
('', '', '', 'EUGENIO', 1),
('', '', '', 'NELIDA', 1),
('', '', '', 'YOLANDA', 1),
('', '', '', 'MARIA E. HARAVISKA', 1),
('', '', '', 'ANDRES', 1),
('', '', '', 'JOSE', 1),
('', '', '', 'MARIA BEATRIZ Y OTROS', 1),
('', '', '', 'HUGO DAVID', 1),
('', '', '', 'OSVALDO ARGENTINO', 1),
('', '', '', 'TEODORA', 1),
('', '', '', 'LUZMILA SUSTER', 1),
('', '', '', 'EMILIA SALAS', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
