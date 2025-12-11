-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 10-12-2025 a las 18:24:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eco_hogar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`

--

CREATE DATABASE eco_hogar;
USE eco_hogar;

CREATE TABLE `categoria_producto` (
  `idCat` int(11) NOT NULL,
  `nomCat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_producto`
--

INSERT INTO `categoria_producto` (`idCat`, `nomCat`) VALUES
(1, 'Tecnología'),
(2, 'Ropa'),
(3, 'Hogar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_producto`
--

CREATE TABLE `estado_producto` (
  `idEst` int(3) NOT NULL,
  `nomEst` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_producto`
--

INSERT INTO `estado_producto` (`idEst`, `nomEst`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `idMov` int(11) NOT NULL,
  `idUsuFK` int(11) NOT NULL,
  `idProFK` int(11) NOT NULL,
  `tipMo` int(11) NOT NULL,
  `cantSto` int(11) NOT NULL,
  `fecMov` datetime NOT NULL,
  `razEgre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`idMov`, `idUsuFK`, `idProFK`, `tipMo`, `cantSto`, `fecMov`, `razEgre`) VALUES
(1, 1, 1, 1, 2, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 2 | Después: 4'),
(2, 1, 1, 1, 4, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 4 | Después: 8'),
(3, 1, 1, 1, 2, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 8 | Después: 10'),
(4, 1, 3, 1, 4, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 2 | Después: 6'),
(5, 1, 1, 1, 2, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 10 | Después: 12'),
(6, 1, 3, 1, 4, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 6 | Después: 10'),
(7, 1, 3, 1, 4, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 10 | Después: 14'),
(8, 1, 1, 1, 10, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 12 | Después: 22'),
(9, 1, 1, 1, 10, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 22 | Después: 32'),
(10, 1, 1, 1, 10, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 32 | Después: 42'),
(11, 1, 1, 1, 10, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 42 | Después: 52'),
(12, 1, 1, 1, 12, '2025-12-07 19:38:55', 'Ingreso de stock. Antes: 52 | Después: 64'),
(13, 1, 1, 1, 12, '2025-12-07 20:04:23', 'Ingreso de stock. Antes: 64 | Después: 76'),
(14, 1, 3, 2, 12, '2025-12-07 20:04:23', 'Venta'),
(15, 1, 1, 1, 2, '2025-12-07 20:04:23', 'Ingreso de stock. Antes: 76 | Después: 78'),
(16, 1, 3, 1, 28, '2025-12-07 19:57:09', 'Ingreso de stock. Antes: 2 | Después: 30'),
(17, 1, 1, 2, 10, '2025-12-07 20:04:23', 'prueba'),
(18, 1, 3, 2, 1, '2025-12-08 02:01:25', 'Venta'),
(19, 1, 1, 1, 12, '2025-12-08 18:03:10', 'Ingreso de stock. Antes: 68 | Después: 80'),
(20, 1, 1, 1, 2, '2025-12-08 18:03:18', 'Ingreso de stock. Antes: 80 | Después: 82'),
(21, 1, 3, 2, 3, '2025-12-09 00:04:08', 'venta'),
(22, 1, 1, 1, 13, '2025-12-08 18:24:10', 'Ingreso de stock. Antes: 82 | Después: 95'),
(23, 1, 3, 1, 5, '2025-12-08 18:24:21', 'Ingreso de stock. Antes: 26 | Después: 31'),
(24, 1, 3, 2, 4, '2025-12-09 00:25:23', 'Bono'),
(25, 1, 1, 1, 3, '2025-12-08 18:50:13', 'Ingreso de stock. Antes: 95 | Después: 98'),
(26, 1, 4, 1, 20, '2025-12-08 18:50:25', 'Ingreso de stock. Antes: 2 | Después: 22'),
(27, 1, 3, 2, 7, '2025-12-09 00:52:15', 'Devolucion'),
(28, 1, 1, 1, 20, '2025-12-08 19:02:58', 'Ingreso de stock. Antes: 98 | Después: 118'),
(29, 1, 3, 1, 20, '2025-12-08 19:03:17', 'Ingreso de stock. Antes: 20 | Después: 40'),
(30, 1, 3, 2, 20, '2025-12-09 01:05:01', 'Venta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idPro` int(11) NOT NULL,
  `idCatFK` int(11) NOT NULL,
  `nomPro` varchar(100) NOT NULL,
  `desPro` text NOT NULL,
  `preUni` decimal(10,0) NOT NULL,
  `preVen` decimal(10,0) NOT NULL,
  `FecReg` date NOT NULL,
  `stoAct` int(11) NOT NULL,
  `umbMinSo` int(11) NOT NULL,
  `idEstProEnumFK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idPro`, `idCatFK`, `nomPro`, `desPro`, `preUni`, `preVen`, `FecReg`, `stoAct`, `umbMinSo`, `idEstProEnumFK`) VALUES
(1, 3, 'SALA', 'comoda sala de star eco amigable', 200000, 0, '0000-00-00', 118, 5, 1),
(3, 3, 'Mueble', 'comoda sala de star eco amigable', 20007, 24234, '2025-12-03', 20, 5, 1),
(4, 3, 'Sala Marina', 'Sala Marina', 20, 0, '2025-12-10', 22, 5, 1),
(5, 2, 'Camisa Polo lumina', 'cómoda camisa eco amigable', 123123, 0, '2025-12-02', 23, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idRol` int(1) NOT NULL,
  `nomRol` varchar(50) NOT NULL,
  `desRol` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idRol`, `nomRol`, `desRol`) VALUES
(1, 'admin', 'Administrador del sistema'),
(2, 'usuario', 'Usuario general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsu` int(11) NOT NULL,
  `nomUsu` varchar(50) NOT NULL,
  `email_Usu` varchar(50) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `idRolFK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsu`, `nomUsu`, `email_Usu`, `clave`, `idRolFK`) VALUES
(1, 'Angie', 'angieari97@gmail.com', '123456', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  ADD PRIMARY KEY (`idCat`);

--
-- Indices de la tabla `estado_producto`
--
ALTER TABLE `estado_producto`
  ADD PRIMARY KEY (`idEst`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`idMov`),
  ADD KEY `moviUsu_fk` (`idUsuFK`),
  ADD KEY `movi_fk` (`idProFK`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idPro`),
  ADD KEY `produ_fk` (`idCatFK`),
  ADD KEY `fk_estado_producto` (`idEstProEnumFK`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsu`),
  ADD KEY `relacionRolUsuario_fk` (`idRolFK`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `idCat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_producto`
--
ALTER TABLE `estado_producto`
  MODIFY `idEst` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `idMov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idPro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idRol` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `moviUsu_fk` FOREIGN KEY (`idUsuFK`) REFERENCES `usuarios` (`idUsu`),
  ADD CONSTRAINT `movi_fk` FOREIGN KEY (`idProFK`) REFERENCES `productos` (`idPro`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_estado_producto` FOREIGN KEY (`idEstProEnumFK`) REFERENCES `estado_producto` (`idEst`),
  ADD CONSTRAINT `produ_fk` FOREIGN KEY (`idCatFK`) REFERENCES `categoria_producto` (`idCat`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `relacionRolUsuario_fk` FOREIGN KEY (`idRolFK`) REFERENCES `rol` (`idRol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
