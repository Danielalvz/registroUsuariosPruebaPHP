-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-05-2025 a las 18:06:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

CREATE DATABASE IF NOT EXISTS cerok_prueba;
USE cerok_prueba;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cerok_prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL,
  `usuario_usuario` varchar(30) NOT NULL,
  `usuario_nombre` varchar(100) NOT NULL,
  `usuario_clave` varchar(70) NOT NULL,
  `usuario_telefono` varchar(10) NOT NULL,
  `usuario_edad` int(11) NOT NULL,
  `usuario_email` varchar(100) NOT NULL,
  `usuario_creado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_foto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_usuario`, `usuario_nombre`, `usuario_clave`, `usuario_telefono`, `usuario_edad`, `usuario_email`, `usuario_creado`, `usuario_foto`) VALUES
(1, 'rosa1', 'Rosario Ramirez', '$2y$10$Br27267KtgSyLrcM.XaQGeJYKfJkz1HZspJJ1AzsRSDA110o2TV7e', '', 0, 'rosar@gmail.com', '2025-05-29 16:03:04', ''),
(2, 'carlosto', 'Carlos Torres', '$2y$10$CF3ISsM3xnFdzBzKqfJNEeW7Wk88ToaU4IU74iq45KLhTNRp7QpUS', '', 0, 'carlosto@gmail.com', '2025-05-29 16:03:57', ''),
(3, 'lauro', 'Laura rojas', '$2y$10$lw8xI2lJ82BUy4bzDY.HJ.DCib6XXQ6G0IFFgSJyaijAMrC4aTFCG', '', 0, 'lauro@gmail.com', '2025-05-29 16:04:28', ''),
(4, 'lalala', 'lalala', '$2y$10$MGZAL4khZEP0TSON3lFVuebs2DjThbw1uYiwELjY1njymBaMxeXWW', '', 0, 'lala@d.com', '2025-05-29 02:00:52', 'lalala_69.png'),
(5, 'lalala3', 'lalal', '$2y$10$1HLPB50v.OKLuWFYHHVtB.LjMHfTJL6BeEDVi5YYfcBjP1Q1Deg9G', '', 0, 'ldkd@g.com', '2025-05-29 02:16:00', 'lalal_96.png'),
(6, 'admin', 'Ariana Gutierrez', '$2y$10$yEf90FjUBq7y4h0eru8B6u/qoCDjqNuI7Miw6JY0p7G4kY8mvQcHu', '', 0, 'admin@h.com', '2025-05-29 16:05:24', ''),
(7, 'andreflo', 'Andres Flores', '$2y$10$CF3ISsM3xnFdzBzKqfJNEeW7Wk88ToaU4IU74iq45KLhTNRp7QpUS', '', 0, 'andreflo@gmail.com', '2025-05-29 16:05:49', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
