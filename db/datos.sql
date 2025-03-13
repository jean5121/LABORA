-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-04-2024 a las 02:29:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lab`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `idtipo_usuario` tinyint(3) UNSIGNED NOT NULL,
  `ctipouser` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`idtipo_usuario`, `ctipouser`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'USUARIO'),
(3, 'aaasdfasdfd'),
(4, 'aaasdfasdfd'),
(5, NULL),
(6, 'entreeee'),
(7, 'entreeee'),
(8, 'entreeee'),
(9, 'entreeee');

INSERT INTO `producto` (`idproducto`, `nombre_pro`, `precio_promedio`, `estado_pro`, `cantidad_material`) VALUES
(1, 'CORONA/PUENTE METAL CERAMICA', 90, 1, 1.5),
(2, 'CARILLA DICILICATO', 200, 1, 1.5),
(3, 'CORONA/PUENTE ZIRCONIO', 270, 1, 1.5),
(666, 'asdjfjhgh', 270, 0, 1.5);

INSERT INTO `cliente` (`idcliente`, `nombre_cli`, `telefono_cli`, `direccion`, `estado_cli`,`documento`,`idtipo_cliente`) VALUES
(1, 'FELICIUM CLINIC', '985754213', 'JR. Candelaria 452', 1,'1231231233',1),
(2, 'VITALY', '987456218', 'AV. TACNA 894 SEGUNDO PISO', 1,'1231231234',1),
(666, 'jean carlos carrasco cutisaca', '985754213', 'FERRICARRIL 319', 1,'74067665',2);

INSERT INTO `usuario` (`idusuario`, `nombre_usuario`, `usuario`, `contrasena`, `estado_usu`, `tipo_usuario`) VALUES
(1, 'Jean Carlos Carrasco Cutisaca', 'admin', 'admin', 1, 1),
(2, 'juana magdalena', 'juan', '12345', 1, 2);

--
INSERT into tipo_cliente (`idtipo_cliente`,`ctipo_client`,`vigente_cli`) VALUES
(1,'Clinica',1),
(2,'Odontologo',1)

--
INSERT into tono_color (ctono) VALUES 
('NULO')
('BL1'),
('BL2'),
('BL3'),
('BL4'),

('A1'),
('A2'),
('A3'),
('A3.5'),
('A4'),

('B1'),
('B2'),
('B3'),
('B4'),

('C1'),
('C2'),
('C3'),
('C4'),

('D2'),
('D3'),
('D4'),

('01-110'),
('1A-120'),
('1B-130'),
('1C-140'),

('2B-210'),
('1D-220'),
('2C-240'),

('3A-310'),
('5B-320'),
('2E-330'),

('4A-410'),
('6B-420'),
('4B-430'),
('3E-340'),

('6D-510'),
('4C-520'),
('3C-530'),
('4D-540')

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`idtipo_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `idtipo_usuario` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
