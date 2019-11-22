-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-12-2017 a las 16:07:10
-- Versión del servidor: 10.0.32-MariaDB-0+deb8u1
-- Versión de PHP: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `grupo24`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_salud`
--

CREATE TABLE IF NOT EXISTS `control_salud` (
`id` int(11) NOT NULL,
  `edad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `peso` int(11) NOT NULL,
  `vacunas_completas` tinyint(1) NOT NULL,
  `maduracion_acorde` tinyint(1) NOT NULL,
  `maduracion_observaciones` text NOT NULL,
  `ex_fisico_normal` tinyint(1) NOT NULL,
  `ex_fisico_observaciones` varchar(255) NOT NULL,
  `pc` int(11) NOT NULL,
  `ppc` int(11) NOT NULL,
  `talla` int(11) NOT NULL,
  `alimentacion` varchar(255) NOT NULL,
  `observaciones_generales` varchar(255) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrado` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `control_salud`
--

INSERT INTO `control_salud` (`id`, `edad`, `fecha`, `peso`, `vacunas_completas`, `maduracion_acorde`, `maduracion_observaciones`, `ex_fisico_normal`, `ex_fisico_observaciones`, `pc`, `ppc`, `talla`, `alimentacion`, `observaciones_generales`, `paciente_id`, `user_id`, `borrado`) VALUES
(1, 15, '2017-12-04', 65, 1, 1, 'todo bien', 1, 'en buena forma', 30, 18, 165, 'muy equilibrada', 'esta persona tiene muy buena salud', 2, 2, 0),
(2, 64, '2017-12-04', 72, 1, 1, 'pareciera estar bien', 0, 'dolores abdominales productos de una lesion', 34, 27, 180, 'dieta estricta', 'tiene sus problemas, pero podria estar peor', 3, 2, 0),
(3, 5, '2017-12-04', 30, 0, 1, 'todo bien', 0, 'pie plano', 15, 12, 70, 'alta en hierro', 'todo ok', 1, 2, 0),
(4, 100, '2017-12-04', 95, 1, 1, 'todo bien', 1, 'artritis', 30, 30, 170, 'liquida', 'debe estar bajo cuidados', 5, 2, 0),
(5, 44, '2017-12-04', 79, 1, 1, 'todo bien', 1, 'todo ok', 23, 23, 150, 'normal', 'todo bien', 2, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_demograficos`
--

CREATE TABLE IF NOT EXISTS `datos_demograficos` (
`id` int(11) NOT NULL,
  `heladera` tinyint(1) NOT NULL,
  `electricidad` tinyint(1) NOT NULL,
  `mascota` tinyint(1) NOT NULL,
  `tipo_vivienda_id` int(11) NOT NULL,
  `tipo_calefaccion_id` int(11) NOT NULL,
  `tipo_agua_id` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `datos_demograficos`
--

INSERT INTO `datos_demograficos` (`id`, `heladera`, `electricidad`, `mascota`, `tipo_vivienda_id`, `tipo_calefaccion_id`, `tipo_agua_id`, `borrado`) VALUES
(1, 0, 0, 0, 1, 1, 1, 0),
(2, 0, 0, 1, 2, 1, 1, 0),
(3, 1, 1, 0, 1, 2, 1, 0),
(4, 0, 1, 0, 2, 2, 2, 0),
(5, 0, 0, 0, 1, 1, 1, 0),
(6, 1, 1, 0, 2, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra_social`
--

CREATE TABLE IF NOT EXISTS `obra_social` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `obra_social`
--

INSERT INTO `obra_social` (`id`, `nombre`, `borrado`) VALUES
(1, 'IOMA', 0),
(2, 'OSDE', 0),
(3, 'PMU', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE IF NOT EXISTS `paciente` (
`id` int(11) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `fecha_nac` date NOT NULL,
  `genero` tinytext NOT NULL,
  `datos_demograficos_id` int(11) NOT NULL,
  `obra_social_id` int(11) NOT NULL,
  `tipo_doc_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `borrado` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id`, `apellido`, `nombre`, `domicilio`, `tel`, `fecha_nac`, `genero`, `datos_demograficos_id`, `obra_social_id`, `tipo_doc_id`, `numero`, `borrado`) VALUES
(1, 'Fernandez', 'Francisco', 'La Plata n°1589', '2214568965', '1980-11-06', '1', 1, 1, 1, 20486115, 0),
(2, 'Gomes', 'Julia', 'Berrizo n°45', '2214567896', '1990-11-08', '0', 2, 1, 1, 4568596, 0),
(3, 'Ramirez', 'Julian', 'Tolosa 7859', '54485655', '2000-11-08', '1', 3, 2, 1, 25638995, 0),
(4, 'Martinez', 'Pablo', 'Gonnet 4856', '452158', '1991-11-07', '1', 4, 3, 1, 455822, 0),
(5, 'Lucas', 'Gauna', 'la', '1231', '3000-12-31', '1', 5, 1, 1, 1231, 0),
(6, 'Userr', 'Userr', 'aaa', '21', '1900-01-31', '1', 6, 1, 1, 99, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `nombre`, `borrado`) VALUES
(1, 'add', 0),
(2, 'delete', 0),
(3, 'edit', 0),
(4, 'listar', 0),
(5, 'pedirTurno', 0),
(6, 'onAdd', 0),
(7, 'onEdit', 0),
(8, 'administrar', 0),
(9, 'filtrar', 0),
(10, 'onFiltrar', 0),
(11, 'onChange', 0),
(12, 'onChangeRolActual', 0),
(13, 'cargarInicio', 0),
(14, 'vistaCambiarRoles', 0),
(15, 'cambiadoDeRoles', 0),
(16, 'listarParaListar', 0),
(17, 'listarParaAgregar', 0),
(18, 'graficoTorta', 0),
(19, 'graficoCrecimiento', 0),
(20, 'graficoEstatura', 0),
(21, 'graficoPC', 0),
(22, 'graficoPeso', 0),
(23, 'graficoPPC', 0),
(24, 'graficoTalla', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE IF NOT EXISTS `rol` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`, `borrado`) VALUES
(1, 'administrador', 0),
(2, 'pediatra', 0),
(3, 'recepcionista', 0),
(4, 'paciente', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_tiene_permiso`
--

CREATE TABLE IF NOT EXISTS `rol_tiene_permiso` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol_tiene_permiso`
--

INSERT INTO `rol_tiene_permiso` (`rol_id`, `permiso_id`, `borrado`) VALUES
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(1, 5, 0),
(1, 6, 0),
(1, 7, 0),
(1, 8, 0),
(1, 9, 0),
(1, 10, 0),
(1, 11, 0),
(1, 12, 0),
(1, 13, 0),
(1, 14, 0),
(1, 15, 0),
(1, 16, 0),
(1, 17, 0),
(1, 18, 0),
(1, 19, 0),
(1, 20, 0),
(1, 21, 0),
(1, 22, 0),
(1, 23, 0),
(1, 24, 0),
(2, 1, 0),
(2, 3, 0),
(2, 4, 0),
(2, 5, 0),
(2, 6, 0),
(2, 7, 0),
(2, 9, 0),
(2, 10, 0),
(2, 12, 0),
(2, 13, 0),
(2, 16, 0),
(2, 17, 0),
(2, 18, 0),
(2, 19, 0),
(2, 20, 0),
(2, 21, 0),
(2, 22, 0),
(2, 23, 0),
(2, 24, 0),
(3, 1, 0),
(3, 3, 0),
(3, 4, 0),
(3, 5, 0),
(3, 6, 0),
(3, 7, 0),
(3, 9, 0),
(3, 10, 0),
(3, 12, 0),
(3, 13, 0),
(3, 18, 0),
(4, 3, 0),
(4, 4, 0),
(4, 5, 0),
(4, 7, 0),
(4, 12, 0),
(4, 13, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sitio`
--

CREATE TABLE IF NOT EXISTS `sitio` (
`id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `cantidadElementos` int(11) NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sitio`
--

INSERT INTO `sitio` (`id`, `titulo`, `descripcion`, `mail`, `cantidadElementos`, `habilitado`) VALUES
(1, 'Hospital  Dr. Ricardo gutiÃ©rrez', 'sector de pediatria\r\n                              \r\n                              \r\n                              ', 'lalala@hospital.gutierrez.com.ar', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_agua`
--

CREATE TABLE IF NOT EXISTS `tipo_agua` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_agua`
--

INSERT INTO `tipo_agua` (`id`, `nombre`, `borrado`) VALUES
(1, 'Red de Agua Provincial', 0),
(2, 'Sin Agua', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_calefaccion`
--

CREATE TABLE IF NOT EXISTS `tipo_calefaccion` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_calefaccion`
--

INSERT INTO `tipo_calefaccion` (`id`, `nombre`, `borrado`) VALUES
(1, 'Gas de Linea', 0),
(2, 'Garrafa', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE IF NOT EXISTS `tipo_documento` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `nombre`, `borrado`) VALUES
(1, 'dni', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vivienda`
--

CREATE TABLE IF NOT EXISTS `tipo_vivienda` (
`id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_vivienda`
--

INSERT INTO `tipo_vivienda` (`id`, `nombre`, `borrado`) VALUES
(1, 'Familiar', 0),
(2, 'Departamento', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE IF NOT EXISTS `turnos` (
`id` int(11) NOT NULL,
  `dni` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `activo` tinyint(4) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `username`, `pass`, `activo`, `updated_at`, `created_at`, `first_name`, `last_name`, `borrado`) VALUES
(1, 'agu_coyote@hotmail.com', 'agucoyote', 'coyote666', 0, '2017-10-11 00:00:00', '2017-10-11 00:00:00', 'agustin', 'colla', 0),
(2, 'matias.i.m6@gmail.com', 'matias92', 'matias1992', 0, '2017-10-11 00:00:00', '2017-10-11 00:00:00', 'matias', 'murgia', 0),
(3, 'juan_maizares386@hotmail.com', 'juan_maizares386', 'aseraser', 0, '2017-10-11 00:00:00', '2017-10-11 00:00:00', 'juan', 'maizares', 0),
(5, 'new@gmail.com', 'n', 'n', 0, '2017-12-05 11:52:00', '2017-12-05 11:52:00', 'n', 'n', 0),
(6, 'a@a.com', 'a', 'a', 0, '2017-12-05 18:39:36', '2017-12-05 18:39:36', 'a', 'a', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_tiene_rol`
--

CREATE TABLE IF NOT EXISTS `usuario_tiene_rol` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario_tiene_rol`
--

INSERT INTO `usuario_tiene_rol` (`usuario_id`, `rol_id`, `borrado`) VALUES
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(2, 1, 0),
(2, 2, 0),
(2, 3, 0),
(2, 4, 1),
(3, 1, 0),
(3, 2, 0),
(3, 3, 0),
(3, 4, 0),
(6, 1, 0),
(6, 2, 0),
(6, 3, 0),
(6, 4, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `control_salud`
--
ALTER TABLE `control_salud`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos_demograficos`
--
ALTER TABLE `datos_demograficos`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `obra_social`
--
ALTER TABLE `obra_social`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_tiene_permiso`
--
ALTER TABLE `rol_tiene_permiso`
 ADD PRIMARY KEY (`rol_id`,`permiso_id`);

--
-- Indices de la tabla `sitio`
--
ALTER TABLE `sitio`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_agua`
--
ALTER TABLE `tipo_agua`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_calefaccion`
--
ALTER TABLE `tipo_calefaccion`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_tiene_rol`
--
ALTER TABLE `usuario_tiene_rol`
 ADD PRIMARY KEY (`usuario_id`,`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `control_salud`
--
ALTER TABLE `control_salud`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `datos_demograficos`
--
ALTER TABLE `datos_demograficos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `obra_social`
--
ALTER TABLE `obra_social`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `sitio`
--
ALTER TABLE `sitio`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipo_agua`
--
ALTER TABLE `tipo_agua`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_calefaccion`
--
ALTER TABLE `tipo_calefaccion`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
