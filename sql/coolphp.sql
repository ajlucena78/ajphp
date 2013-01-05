-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-07-2012 a las 17:01:49
-- Versión del servidor: 5.1.60-community
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `coolphp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Contacto`
--

CREATE TABLE IF NOT EXISTS `Contacto` (
  `id_usuario` varchar(50) NOT NULL,
  `id_contacto` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_contacto`),
  KEY `id_contacto` (`id_contacto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Contacto`
--

INSERT INTO `Contacto` (`id_usuario`, `id_contacto`) VALUES
('66f3371a', '592c24e3'),
('592a891b', '66f3371a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mensaje`
--

CREATE TABLE IF NOT EXISTS `Mensaje` (
  `id_mensaje` varchar(50) NOT NULL,
  `id_usuario_envia` varchar(50) NOT NULL,
  `id_usuario_destino` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL,
  PRIMARY KEY (`id_mensaje`),
  KEY `id_usuario_envia` (`id_usuario_envia`),
  KEY `id_usuario_destino` (`id_usuario_destino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Mensaje`
--

INSERT INTO `Mensaje` (`id_mensaje`, `id_usuario_envia`, `id_usuario_destino`, `mensaje`, `fecha_envio`) VALUES
('4ff036cf9917d', '592a891b', '66f3371a', 'Hola,¿cómo estás?', '2012-07-01 13:38:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE IF NOT EXISTS `Usuario` (
  `id_usuario` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`id_usuario`, `nombre`, `clave`, `email`) VALUES
('592a891b', 'Jesús', '0816b7f7118a8e80f89583b6a39bbb79', 'jesus@email.es'),
('592c24e3', 'Amparo', '0816b7f7118a8e80f89583b6a39bbb79', 'amparo@email.es'),
('66f3371a', 'Enrica', '0816b7f7118a8e80f89583b6a39bbb79', 'enrica@email.es');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Contacto`
--
ALTER TABLE `Contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contacto_ibfk_2` FOREIGN KEY (`id_contacto`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Mensaje`
--
ALTER TABLE `Mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`id_usuario_envia`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`id_usuario_destino`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
