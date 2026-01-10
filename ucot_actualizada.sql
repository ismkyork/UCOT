-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-01-2026 a las 14:25:22
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ucot`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth`
--

CREATE TABLE `auth` (
  `id_auth` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `auth`
--

INSERT INTO `auth` (`id_auth`, `email`, `password`, `rol`) VALUES
(16, 'pruebanumero1@gmail.com', '$2y$10$BLrdFOPHnOSX.2pJjRzoTu555hw6B9.lvs9PZuw08ND3HNnIC72OO', 'cliente'),
(17, 'pruebanumero2@gmail.com', '$2y$10$bp7lYstjDxo72n5ew6qDZOWOzfGCz1DmQ/tYM9aXBi.SBYX.AQd5C', 'cliente'),
(18, 'pruebanumero3@gmail.com', '$2y$10$ZyYWgVTb5DxXJw4HNBYfVOk3qFLHaqp5bEWauXplmpQ5Tw33azAR.', 'cliente'),
(19, 'pruebanumero4@gmail.com', '$2y$10$wdF3oX4vG5MOnt.RREc/TeAlINhmhDeaT6v.cDNoC73l4eAWf3O9u', 'cliente'),
(20, 'pruebanumero5@gmail.com', '$2y$10$PxUpsS9mQnZp4hUMsTJIDeT324GOM/htN7rKvgIL1f2u673Rwf9bS', 'cliente'),
(23, 'Profesor@gmail.com', '$2y$10$hyWNvZYH7ybBuhj7Iqbnuub9XHXr2oPgDXw89zhz4bsk9MXW9bKxa', 'Profesor'),
(24, 'estudiantenuevo@hotmail.com', '$2y$10$XpERpeTunq9UfcuzgJ8XJ.l5sGcWyX/ppfB0DlLTEulIgV2lBxuH6', 'Estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int NOT NULL,
  `id_alumno` int NOT NULL,
  `id_profesor` int NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `duracion_min` int NOT NULL,
  `materia` varchar(100) DEFAULT NULL,
  `estado_cita` varchar(50) DEFAULT 'pendiente',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_alumno`, `id_profesor`, `fecha_hora_inicio`, `duracion_min`, `materia`, `estado_cita`, `created_at`, `updated_at`) VALUES
(7, 18, 23, '2026-01-25 10:15:00', 60, 'fisica', 'aprobado', '2026-01-10 14:15:18', '2026-01-10 14:15:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feedback`
--

CREATE TABLE `feedback` (
  `id_feedback` int NOT NULL,
  `id_cita` int NOT NULL,
  `id_alumno` int NOT NULL,
  `id_profesor` int NOT NULL,
  `puntuacion` tinyint NOT NULL,
  `comentario` text,
  `fecha_evaluacion` datetime NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int NOT NULL,
  `id_profesor` int NOT NULL,
  `week_day` enum('LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('Disponible','Reservado','No_trabaja') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_estatico`
--

CREATE TABLE `pago_estatico` (
  `id_pago` varchar(20) NOT NULL,
  `id_cita` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `estado_pago` enum('pendiente','confirmado','rechazado') NOT NULL,
  `fecha_confirmacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles_estudiantes`
--

CREATE TABLE `perfiles_estudiantes` (
  `id_estudiante` int NOT NULL,
  `id_auth` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `perfiles_estudiantes`
--

INSERT INTO `perfiles_estudiantes` (`id_estudiante`, `id_auth`, `name`, `apellido`) VALUES
(1, 16, 'prueba', 'prueba'),
(2, 18, 'prueba3', 'prueba3'),
(3, 19, 'prueba4', 'prueba4'),
(4, 24, 'estudiante', 'nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_profesor`
--

CREATE TABLE `perfil_profesor` (
  `id_profesor` int NOT NULL,
  `id_auth` int NOT NULL,
  `nombre_profesor` varchar(50) NOT NULL,
  `apellido_profesor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `perfil_profesor`
--

INSERT INTO `perfil_profesor` (`id_profesor`, `id_auth`, `nombre_profesor`, `apellido_profesor`) VALUES
(6, 23, 'Profesor', 'primero');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id_auth`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `fk_citas_alumno_auth` (`id_alumno`),
  ADD KEY `fk_citas_profesor_auth` (`id_profesor`);

--
-- Indices de la tabla `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `id_cita` (`id_cita`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `pago_estatico`
--
ALTER TABLE `pago_estatico`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `perfiles_estudiantes`
--
ALTER TABLE `perfiles_estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_auth` (`id_auth`);

--
-- Indices de la tabla `perfil_profesor`
--
ALTER TABLE `perfil_profesor`
  ADD PRIMARY KEY (`id_profesor`),
  ADD KEY `id_auth` (`id_auth`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auth`
--
ALTER TABLE `auth`
  MODIFY `id_auth` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id_feedback` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfiles_estudiantes`
--
ALTER TABLE `perfiles_estudiantes`
  MODIFY `id_estudiante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perfil_profesor`
--
ALTER TABLE `perfil_profesor`
  MODIFY `id_profesor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_citas_alumno_auth` FOREIGN KEY (`id_alumno`) REFERENCES `auth` (`id_auth`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_profesor_auth` FOREIGN KEY (`id_profesor`) REFERENCES `auth` (`id_auth`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`id_alumno`) REFERENCES `perfiles_estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`id_profesor`) REFERENCES `perfil_profesor` (`id_profesor`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `perfil_profesor` (`id_profesor`);

--
-- Filtros para la tabla `pago_estatico`
--
ALTER TABLE `pago_estatico`
  ADD CONSTRAINT `pago_estatico_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`);

--
-- Filtros para la tabla `perfiles_estudiantes`
--
ALTER TABLE `perfiles_estudiantes`
  ADD CONSTRAINT `perfiles_estudiantes_ibfk_1` FOREIGN KEY (`id_auth`) REFERENCES `auth` (`id_auth`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perfil_profesor`
--
ALTER TABLE `perfil_profesor`
  ADD CONSTRAINT `perfil_profesor_ibfk_1` FOREIGN KEY (`id_auth`) REFERENCES `auth` (`id_auth`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
