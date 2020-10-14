-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-10-2020 a las 02:18:47
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `collabdata`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Areas`
--

CREATE TABLE `Areas` (
  `id` int(11) NOT NULL,
  `bloquesId` int(11) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `valMax` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Audiences`
--

CREATE TABLE `Audiences` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `projectsId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Bloques`
--

CREATE TABLE `Bloques` (
  `id` int(11) NOT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `encabezado` tinyint(4) DEFAULT NULL,
  `tipoProm` tinyint(4) DEFAULT NULL,
  `valMax` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CalculosVisita`
--

CREATE TABLE `CalculosVisita` (
  `id` int(11) NOT NULL,
  `visitasId` int(11) DEFAULT NULL,
  `total` varchar(20) DEFAULT NULL,
  `bloque` varchar(255) DEFAULT NULL,
  `bloqueCalif` varchar(20) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `areaCalif` varchar(20) DEFAULT NULL,
  `bloqueNom` varchar(255) DEFAULT NULL,
  `areaNom` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambiarPwd`
--

CREATE TABLE `cambiarPwd` (
  `id` int(11) NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `clientesId` int(11) DEFAULT NULL,
  `expira` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Categories`
--

CREATE TABLE `Categories` (
  `id` int(11) NOT NULL,
  `preguntasId` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Checklist`
--

CREATE TABLE `Checklist` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `siglas` varchar(20) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `tipoProm` tinyint(4) DEFAULT NULL,
  `tipoAnalisis` tinyint(4) DEFAULT NULL,
  `listaFotos` text DEFAULT NULL,
  `photos` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ChecklistEst`
--

CREATE TABLE `ChecklistEst` (
  `id` int(11) NOT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `estructura` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ChecklistImagenes`
--

CREATE TABLE `ChecklistImagenes` (
  `id` int(11) NOT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `archivo` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ComplainsStructure`
--

CREATE TABLE `ComplainsStructure` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Complaints`
--

CREATE TABLE `Complaints` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `reviewDate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `adminId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ComplaintsHistory`
--

CREATE TABLE `ComplaintsHistory` (
  `id` int(11) NOT NULL,
  `complaintsId` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `adminId` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ComplaintsTracking`
--

CREATE TABLE `ComplaintsTracking` (
  `id` int(11) NOT NULL,
  `way` tinyint(4) DEFAULT NULL,
  `complainsId` int(11) DEFAULT NULL,
  `usersId` int(11) DEFAULT NULL,
  `adminUserId` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Condicionales`
--

CREATE TABLE `Condicionales` (
  `id` int(11) NOT NULL,
  `aplicacion` varchar(10) DEFAULT NULL,
  `eleId` int(11) DEFAULT NULL,
  `condicion` text DEFAULT NULL,
  `accion` int(11) DEFAULT NULL,
  `valor` text DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Consultations`
--

CREATE TABLE `Consultations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `initDate` date DEFAULT NULL,
  `finishDate` date DEFAULT NULL,
  `projectsId` int(11) DEFAULT NULL,
  `complainsStructureId` int(11) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `poll` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ConsultationsAudiences`
--

CREATE TABLE `ConsultationsAudiences` (
  `id` int(11) NOT NULL,
  `audiencesId` int(11) DEFAULT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL,
  `levelType` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ConsultationsAudiencesCache`
--

CREATE TABLE `ConsultationsAudiencesCache` (
  `id` int(11) NOT NULL,
  `audiencesId` int(11) DEFAULT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL,
  `consultationAudiencesId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ConsultationsChecklist`
--

CREATE TABLE `ConsultationsChecklist` (
  `id` int(11) NOT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `multiple` tinyint(4) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dimensiones`
--

CREATE TABLE `Dimensiones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT NULL,
  `elemId` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Dimensiones`
--

INSERT INTO `Dimensiones` (`id`, `nombre`, `nivel`, `elemId`, `type`) VALUES
(1, 'sdsd', 1, 1, 'structure');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DimensionesElem`
--

CREATE TABLE `DimensionesElem` (
  `id` int(11) NOT NULL,
  `padre` int(11) DEFAULT NULL,
  `dimensionesId` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `DimensionesElem`
--

INSERT INTO `DimensionesElem` (`id`, `padre`, `dimensionesId`, `nombre`) VALUES
(24, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Documents`
--

CREATE TABLE `Documents` (
  `id` int(11) NOT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `file` text DEFAULT NULL,
  `name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DocumentsComments`
--

CREATE TABLE `DocumentsComments` (
  `id` int(11) NOT NULL,
  `documentsId` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `usersId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estatus`
--

CREATE TABLE `Estatus` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `tabla` varchar(255) NOT NULL,
  `code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Estatus`
--

INSERT INTO `Estatus` (`id`, `name`, `color`, `tabla`, `code`) VALUES
(1, 'received', NULL, 'Complaints', 10),
(2, 'read', NULL, 'Complaints', 20),
(3, 'channeled', NULL, 'Complaints', 30),
(4, 'attended', NULL, 'Complaints', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatusHist`
--

CREATE TABLE `estatusHist` (
  `id` int(11) NOT NULL,
  `clientesId` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `usuarioId` int(11) DEFAULT NULL,
  `visitasId` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Frequencies`
--

CREATE TABLE `Frequencies` (
  `id` int(11) NOT NULL,
  `code` varchar(15) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `General`
--

CREATE TABLE `General` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `texto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GeometriesAttributes`
--

CREATE TABLE `GeometriesAttributes` (
  `id` int(11) NOT NULL,
  `geometriesId` int(11) DEFAULT NULL,
  `attributeId` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KML`
--

CREATE TABLE `KML` (
  `id` int(11) NOT NULL,
  `projectsId` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `idField` varchar(100) DEFAULT NULL,
  `north` double DEFAULT NULL,
  `south` double DEFAULT NULL,
  `east` double DEFAULT NULL,
  `west` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KMLAttributes`
--

CREATE TABLE `KMLAttributes` (
  `id` int(11) NOT NULL,
  `KMLId` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KMLGeometries`
--

CREATE TABLE `KMLGeometries` (
  `id` int(11) NOT NULL,
  `KMLId` int(11) DEFAULT NULL,
  `identifier` varchar(100) DEFAULT NULL,
  `geometry` geometry DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Multimedia`
--

CREATE TABLE `Multimedia` (
  `id` int(11) NOT NULL,
  `visitasId` int(11) DEFAULT NULL,
  `tipo` varchar(31) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `News`
--

CREATE TABLE `News` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `news` text DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `img` text DEFAULT NULL,
  `header` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Points`
--

CREATE TABLE `Points` (
  `id` int(11) NOT NULL,
  `problemsId` int(11) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Preguntas`
--

CREATE TABLE `Preguntas` (
  `id` int(11) NOT NULL,
  `areasId` int(11) DEFAULT NULL,
  `pregunta` text DEFAULT NULL,
  `comShopper` text DEFAULT NULL,
  `comVerif` text DEFAULT NULL,
  `tiposId` int(11) DEFAULT NULL,
  `puntos` double DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `subareasId` int(11) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `influyeValor` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `justif` tinyint(4) DEFAULT NULL,
  `grafica` tinyint(4) DEFAULT NULL,
  `fichaTec` tinyint(4) DEFAULT NULL,
  `datTec` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Problems`
--

CREATE TABLE `Problems` (
  `id` int(11) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categoriesId` int(11) DEFAULT NULL,
  `respuestasVisitaId` int(11) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `geometry` geometry DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Projects`
--

CREATE TABLE `Projects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Projects`
--

INSERT INTO `Projects` (`id`, `name`, `description`, `inactive`, `code`) VALUES
(1, 'asd', 'asd', 0, 'asd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PublicConsultations`
--

CREATE TABLE `PublicConsultations` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `projectsId` int(11) DEFAULT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `emailReq` tinyint(4) DEFAULT NULL,
  `oneAns` tinyint(4) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PublicConsultationsUsers`
--

CREATE TABLE `PublicConsultationsUsers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `publicConsultationsId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pwdRecover`
--

CREATE TABLE `pwdRecover` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `used` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Respuestas`
--

CREATE TABLE `Respuestas` (
  `id` int(11) NOT NULL,
  `preguntasId` int(11) DEFAULT NULL,
  `respuesta` varchar(255) DEFAULT NULL,
  `valor` varchar(6) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `justif` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RespuestasVisita`
--

CREATE TABLE `RespuestasVisita` (
  `id` int(11) NOT NULL,
  `visitasId` int(11) DEFAULT NULL,
  `preguntasId` int(11) DEFAULT NULL,
  `respuesta` text DEFAULT NULL,
  `justificacion` text DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Studyarea`
--

CREATE TABLE `Studyarea` (
  `id` int(11) NOT NULL,
  `preguntasId` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometry` geometry DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `StudyareaPoints`
--

CREATE TABLE `StudyareaPoints` (
  `id` int(11) NOT NULL,
  `studyareaId` int(11) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Targets`
--

CREATE TABLE `Targets` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `projectsId` int(11) DEFAULT NULL,
  `addStructure` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Targets`
--

INSERT INTO `Targets` (`id`, `name`, `description`, `projectsId`, `addStructure`) VALUES
(1, 'asd', 'asd', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TargetsChecklist`
--

CREATE TABLE `TargetsChecklist` (
  `id` int(11) NOT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `targetsId` int(11) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TargetsElems`
--

CREATE TABLE `TargetsElems` (
  `id` int(11) NOT NULL,
  `targetsId` int(11) DEFAULT NULL,
  `usersTargetsId` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `usersId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipos`
--

CREATE TABLE `Tipos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(63) DEFAULT NULL,
  `siglas` varchar(10) DEFAULT NULL,
  `tabla` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Tipos`
--

INSERT INTO `Tipos` (`id`, `nombre`, `siglas`, `tabla`) VALUES
(1, 'Abierta', 'ab', 'Preguntas'),
(2, 'Múltiple', 'mult', 'Preguntas'),
(3, 'Subárea', 'sub', 'Preguntas'),
(4, 'Numérica', 'num', 'Preguntas'),
(5, 'Collabmap', 'cm', 'Preguntas'),
(6, 'spatial', 'spatial', 'Preguntas'),
(7, 'One point', 'op', 'Preguntas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `hashConf` varchar(255) DEFAULT NULL,
  `confirmed` tinyint(4) DEFAULT NULL,
  `validated` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Users`
--

INSERT INTO `Users` (`id`, `username`, `name`, `lastname`, `email`, `pwd`, `gender`, `age`, `hashConf`, `confirmed`, `validated`) VALUES
(1, 'juanma', 'juan manuel', 'gomez', 'xpektro@hotmail.com', '$2y$10$rNQNf3PqPZ8yX5ByqHIMB.pt0TAvFpYIRNRXgnkjFxzPZzgcofybu', 'M', 38, NULL, 1, 1),
(2, 'juanma2', 'juan manuel', 'gomez Perez figueroa', 'xpektro3@hotmail.com', '$2y$10$FJ.DUpNxZR4x2GB6H7v.D.abIuVS1re/8y5dJT1KsiAlY735JLrGW', 'M', 38, NULL, NULL, NULL),
(3, 'Daniela', 'Daniela', 'Evia ', 'daniela.evia@capsus.mx', '$2y$10$xxB9X37wIWffFpk2xy1WcetW.c9Jz6W25DDsKRZV7Hm4K4Xlk6d8q', 'F', 41, NULL, 1, 1),
(4, 'dante', 'Dante', 'Zayas', 'dante.zayas@capsus.mx', '$2y$10$5qS.s3mLETYLfS8.8/aj8u6FHmPK.tbw38ozDl/UxjPkVnAR/bwmG', 'M', 22, NULL, 1, 1),
(9, 'juanma3', 'juanma', '123', 'xpektro@hotmail.com', '$2y$10$r6C9jUJGgsYvrwC2yVju7.CtQg/XPfHA32zYIgU1fx1/BquGjsTvG', 'M', 38, 'YL9pM5Bb6lSUyiP157VCeeB5Ey15fjiMQjZazEYN0tbazRrQERZNW', NULL, NULL),
(14, 'facilitador1', 'facilitador', 'uno', 'facilitador@capsus.mx', '$2y$10$YXnCr6ot2sKkXWzda4dVPODpVnNGqkJZV4UGOLzB8Xi7l2O7XV/GG', 'M', 23, NULL, 1, 1),
(24, 'luis', 'Luis', 'García', 'luis.garcia@capsus.mx', '$2y$10$.Mom5S97iHX4RnpvYW6cquFFYExGLcZMGO11Iz/6fO.asVOYNhC66', 'M', 25, NULL, 1, 1),
(25, 'jose', 'Jose', 'Diaz', 'jd@capsus.mx', '$2y$10$CHfY25iHyIUKqyhsFiRyOuAiDPrc8/Ru/nwtKTYsxcVWvPiXsi2.6', 'M', 26, NULL, 1, 1),
(30, 'pruebas', 'Pruebin', 'De la Prueba', 'prueba@otraprueba.comprueba', '$2y$10$nTp.xSWnrYbPW.u9VMTusOx7Gdk2/zRRWQxVHYke2NmT27G/dE0rq', 'M', 25, NULL, 1, 1),
(31, 'fernando', 'Fernando', 'Pérez', 'fernando', '$2y$10$YQMwteqerlOWL/z92opLE.8r7M8aArsRUwE8uAwoFuJLnwAI6NgQS', 'M', 22, NULL, 1, 1),
(32, 'jsilva', 'Jorge', 'Silva', 'jorgesilva.py@gmail.com', '$2y$10$hEkDQrOHHwMHEiAmKkI7AO31H7EKQn2O2WT3aVtzYj/ytqO9RQfs2', 'M', 0, NULL, 1, 1),
(33, 'epereira', 'Enrique', 'Pereira', '.', '$2y$10$JOKuvG9D/MO0jHox3KS9peH3X9qD/5P1fMjoVxFspEDAOAHXMscWC', 'M', 0, NULL, 1, 1),
(34, 'gcane', 'Guillermo', 'Cane', '.', '$2y$10$L85dE6E5.pHopIelJ/56COVtFBtXbgadVLQugFMHlC2Bt.WQ6GiBm', 'M', 0, NULL, 1, 1),
(35, 'rortigoza', 'Richard', 'Ortigoza', '.', '$2y$10$W5hywc9PIkCcb4/4iXG0zOhNlSlc373wsPRIorI1bc1Auw/yUV7g2', 'M', 0, NULL, 1, 1),
(36, 'malarcon', 'Marcos', 'Alarcon', '.', '$2y$10$trGvLb.kdC8sV2lTyozkN.TwOxMfxBYySKFQ2CIKmoF3phVHTYNSC', 'M', 0, NULL, 1, 1),
(37, 'inuñez', 'Idalina', 'Nuñez', '.', '$2y$10$/W2nIdtekgibWFkwQEj7SuhhuXf9EWF3jGAiqNbt3hLkkUW19Ue4.', 'F', 0, NULL, 1, 1),
(38, 'emendoza', 'Emilia', 'Mendoza León', '.', '$2y$10$r.ESXBh85Fqm3vk49h4eZ.LwK67g.wzqaeMyIIbTh6zyAMSMAOacO', 'F', 0, NULL, 1, 1),
(39, 'elafarja', 'Eugenia', 'Lafarja', '.', '$2y$10$WiZkiKO1D9fORxaoGHBuOuexBsxht6gVWZ5RKbLZqOek0uwCRNvoa', 'F', 0, NULL, 1, 1),
(40, 'Bruja1', 'Bruja', 'Escaldufa', 'bruja@noimporta.com', '$2y$10$I2UVocWQKO3NuQKVsNKZPOoiwcrgald3Z02iOZn/RSL80bMj.tBLS', 'F', 46, 'sjECUKjWiJ1OgFa.JTCI5.FS9G9j9A9VGVy5OGps/Ije30ze7iB66', 1, 1),
(41, 'Cap1', 'Usuario', 'Prueba 1', 'usuario@prueba.com', '$2y$10$kexEJshWsRu9JizHVkzbE.5y/efeY/ekuCqoXWSm4qtwsM9GTjePS', 'F', 30, 'gp8L9x/A0y5ZfT7KRWZPde8G1BpRedse6GXa8grUQM6gFzNc5QaJa', 1, 1),
(42, 'Cap2', 'Usuario', 'Prueba 2', 'usuario2@prueba.com', '$2y$10$K4cBnimMKd5.G5boNopa5eUJWu1Dwi.iwC26QfrtigNOSvA4aWwqy', 'M', 25, 'uRHNCcxSNy0knPoWHN3gb.NY8m0xCWw77O8YSHIDB01U.UueEmSTK', 1, 1),
(43, 'Cap3', 'Usuario', 'Prueba 3', 'usuario3@prueba.com', '$2y$10$mIaq4kDnYOXvhBlAfFza1eyi7r8Ta/NjK5lv.oosWW4QyOhyjxBEC', 'F', 35, 'wkEG5e.16/SiL/eIkU9Roux7NKFkqXdZ5tMfNgoly1.Y/rSANU/Se', 1, 1),
(44, 'Cap4', 'Usuario', 'Prueba 4', 'usuario4@prueba.com', '$2y$10$BXTAGS71YzbFG98eMUV7/.GzykpKvz7eTNzhdOaLMDAYtU1HxU9vm', 'M', 41, 'stdR1YTduzJydHy90YvzRe3jEy/C7IDQIDW1klV2j0pyyZ.uGCm0S', 1, 1),
(45, 'juan', 'Juan', 'Díaz', 'juandiaz@gmail.com', '$2y$10$XRtNcF9a6Nz6CwMVKk8rLOKFNOqopEgqjmNOm.OQZR8WPsPGSzLNy', 'M', 33, NULL, 1, 1),
(46, 'jgarcia', 'Judá', 'García', 'juda.garcia@capsus.mx', '$2y$10$ulph90ZO0N3./YvW1w0W9O5iJgaQRjqHdypdHG8aqntvgU9HCnAg6', 'M', 26, 'ob/Eme/1VGKhbhOncyd3Qeq9DqJeBjnHlP/J0iAV05gcfywNZqOU2', 1, 1),
(48, 'pedro', 'pedro', 'pedro', 'acosta.aleoli@gmail.com', '$2y$10$SVP3umcMb6vjrJ6QddSN1.JYQaDN5C5LZZh8qMkx6EwmCiOuRtEFi', 'M', 0, NULL, 1, 1),
(49, 'valeria', 'Valeria', 'Jensen-Buhl', 'vjensen@muvh.gov.py', '$2y$10$SdB7WJExmLg65bxKtuWu.OhrU/T9uvyImVaPPurxt2Ehg0BPkqz0W', 'F', 0, NULL, 1, 1),
(50, 'patriciag', 'Patricia', 'Gimenez Franco', 'pgimfran@muvh.gov.py', '$2y$10$4o20YwYS/XeJE1P7baKbSus5EEnNPWXoz35XeiCIybFQ1fADEpLk.', 'F', 0, NULL, 1, 1),
(51, 'patriciaf', 'Patricia ', 'Filippini', 'filipatty1706@gmail.com>', '$2y$10$IZABzMOKRKSOljsm8c.cR.7ZUb4nVtoWLJ4.Ew9UttqWCmSEyyvwi', 'F', 0, NULL, 1, 1),
(52, 'rosa', 'Rosa', 'Ugarte', 'roshayd@gmail.com', '$2y$10$T7dZ9mgFcyi9z9S5ouV6Y.iiK6iLHo0PpZbhFgMPeFMoGVPv6ECqq', 'F', 0, NULL, 1, 1),
(53, 'beatriz', 'Beatriz', 'Bogado', 'boggloria@gmail.com', '$2y$10$yR7vkfo9bvQ82f03nFSF8uvCYRa5O.trgFe6oapYj9UJLbxCSIbBW', 'F', 0, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersAudiences`
--

CREATE TABLE `UsersAudiences` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `audiencesId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersConsultationsChecklist`
--

CREATE TABLE `UsersConsultationsChecklist` (
  `id` int(11) NOT NULL,
  `consultationsChecklistId` int(11) DEFAULT NULL,
  `usersId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersLikes`
--

CREATE TABLE `UsersLikes` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `newsId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersQuickPoll`
--

CREATE TABLE `UsersQuickPoll` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `consultationsId` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UsersTargets`
--

CREATE TABLE `UsersTargets` (
  `id` int(11) NOT NULL,
  `usersId` int(11) DEFAULT NULL,
  `targetsId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usrAdmin`
--

CREATE TABLE `usrAdmin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usrAdmin`
--

INSERT INTO `usrAdmin` (`id`, `name`, `lastname`, `mail`, `pwd`, `username`, `ext`, `telefono`, `nivel`) VALUES
(1, 'Superuser', 'superuser', 'superuser@domain.com', '$2y$10$uX//vVjOnolFVWA/L.GMjeCnMsPfdFmFP8f/jqIQEmZ7XeA3MTPj2', 'admin', NULL, NULL, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Visitas`
--

CREATE TABLE `Visitas` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `estatus` varchar(45) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `finishDate` datetime DEFAULT NULL,
  `finalizada` tinyint(4) DEFAULT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `elemId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Areas`
--
ALTER TABLE `Areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Areas_Bloques1_idx` (`bloquesId`),
  ADD KEY `aIdentificadorIndex` (`identificador`);

--
-- Indices de la tabla `Audiences`
--
ALTER TABLE `Audiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Audiences_Projects1_idx` (`projectsId`);

--
-- Indices de la tabla `Bloques`
--
ALTER TABLE `Bloques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Bloques_Checklist1_idx` (`checklistId`),
  ADD KEY `bIdentificadorIndex` (`identificador`);

--
-- Indices de la tabla `CalculosVisita`
--
ALTER TABLE `CalculosVisita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_CalculosVisita_Visitas1_idx` (`visitasId`);

--
-- Indices de la tabla `cambiarPwd`
--
ALTER TABLE `cambiarPwd`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Categorias_Preguntas1_idx` (`preguntasId`);

--
-- Indices de la tabla `Checklist`
--
ALTER TABLE `Checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ChecklistEst`
--
ALTER TABLE `ChecklistEst`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chkEstUniq` (`checklistId`);

--
-- Indices de la tabla `ChecklistImagenes`
--
ALTER TABLE `ChecklistImagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_CheklistsImagenes_Checklist1_idx` (`checklistId`);

--
-- Indices de la tabla `ComplainsStructure`
--
ALTER TABLE `ComplainsStructure`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Complaints`
--
ALTER TABLE `Complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Complains_Users1_idx` (`usersId`),
  ADD KEY `fk_Complains_usrAdmin1_idx` (`adminId`),
  ADD KEY `fk_Complains_DimensionesElem1_idx` (`dimensionesElemId`),
  ADD KEY `fk_Complains_Consultations1_idx` (`consultationsId`);

--
-- Indices de la tabla `ComplaintsHistory`
--
ALTER TABLE `ComplaintsHistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ComplaintsHistory_Complaints1_idx` (`complaintsId`),
  ADD KEY `fk_ComplaintsHistory_usrAdmin1_idx` (`adminId`);

--
-- Indices de la tabla `ComplaintsTracking`
--
ALTER TABLE `ComplaintsTracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_table1_Complains1_idx` (`complainsId`),
  ADD KEY `fk_table1_Users1_idx` (`usersId`),
  ADD KEY `fk_table1_usrAdmin1_idx` (`adminUserId`);

--
-- Indices de la tabla `Condicionales`
--
ALTER TABLE `Condicionales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Consultations`
--
ALTER TABLE `Consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Consultations_Projects1_idx` (`projectsId`),
  ADD KEY `fk_Consultations_ComplainsStructure1_idx` (`complainsStructureId`);

--
-- Indices de la tabla `ConsultationsAudiences`
--
ALTER TABLE `ConsultationsAudiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ConsultationsAudiences_Audiences1_idx` (`audiencesId`),
  ADD KEY `fk_ConsultationsAudiences_Consultations1_idx` (`consultationsId`),
  ADD KEY `fk_ConsultationsAudiences_DimensionesElem1_idx` (`dimensionesElemId`);

--
-- Indices de la tabla `ConsultationsAudiencesCache`
--
ALTER TABLE `ConsultationsAudiencesCache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ConsultationsAudiencesCache_Audiences1_idx` (`audiencesId`),
  ADD KEY `fk_ConsultationsAudiencesCache_Consultations1_idx` (`consultationsId`),
  ADD KEY `fk_ConsultationsAudiencesCache_DimensionesElem1_idx` (`dimensionesElemId`),
  ADD KEY `fk_ConsultationsAudiencesCache_ConsultationsAudiences1_idx` (`consultationAudiencesId`);

--
-- Indices de la tabla `ConsultationsChecklist`
--
ALTER TABLE `ConsultationsChecklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ConsultationsChecklist_Consultations1_idx` (`consultationsId`),
  ADD KEY `fk_ConsultationsChecklist_Checklist1_idx` (`checklistId`),
  ADD KEY `fk_ConsultationsChecklist_Frequencies1_idx` (`frequency`);

--
-- Indices de la tabla `Dimensiones`
--
ALTER TABLE `Dimensiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `DimensionesElem`
--
ALTER TABLE `DimensionesElem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_DimensionesElem_DimensionesElem1_idx` (`padre`),
  ADD KEY `fk_DimensionesElem_Dimensiones1_idx` (`dimensionesId`);

--
-- Indices de la tabla `Documents`
--
ALTER TABLE `Documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Documents_Consultations1_idx` (`consultationsId`);

--
-- Indices de la tabla `DocumentsComments`
--
ALTER TABLE `DocumentsComments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_DocumentsComments_Users1_idx` (`usersId`),
  ADD KEY `fk_DocumentsComments_Documents1_idx` (`documentsId`),
  ADD KEY `fk_DocumentsComments_DimensionesElem1_idx` (`dimensionesElemId`);

--
-- Indices de la tabla `Estatus`
--
ALTER TABLE `Estatus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estatusHist`
--
ALTER TABLE `estatusHist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estatusHist_usrAdmin1_idx` (`usuarioId`),
  ADD KEY `fk_estatusHist_Estatus1_idx` (`estatus`);

--
-- Indices de la tabla `Frequencies`
--
ALTER TABLE `Frequencies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `General`
--
ALTER TABLE `General`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `generalName` (`name`);

--
-- Indices de la tabla `GeometriesAttributes`
--
ALTER TABLE `GeometriesAttributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_GeometriesAttributes_KMLGeometries1_idx` (`geometriesId`),
  ADD KEY `fk_GeometriesAttributes_KMLAttibutes1_idx` (`attributeId`);

--
-- Indices de la tabla `KML`
--
ALTER TABLE `KML`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_KML_Projects1_idx` (`projectsId`);

--
-- Indices de la tabla `KMLAttributes`
--
ALTER TABLE `KMLAttributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_KMLAttibutes_KML1_idx` (`KMLId`);

--
-- Indices de la tabla `KMLGeometries`
--
ALTER TABLE `KMLGeometries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexIdentificador` (`identifier`),
  ADD KEY `fk_KMLGeometries_KML1_idx` (`KMLId`);

--
-- Indices de la tabla `Multimedia`
--
ALTER TABLE `Multimedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_table1_Visitas1_idx` (`visitasId`);

--
-- Indices de la tabla `News`
--
ALTER TABLE `News`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Points`
--
ALTER TABLE `Points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Puntos_Problems1_idx` (`problemsId`);

--
-- Indices de la tabla `Preguntas`
--
ALTER TABLE `Preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Preguntas_Areas1_idx` (`areasId`),
  ADD KEY `fk_Preguntas_Tipos1_idx` (`tiposId`),
  ADD KEY `identificador` (`identificador`),
  ADD KEY `graficar` (`grafica`);

--
-- Indices de la tabla `Problems`
--
ALTER TABLE `Problems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Problems_RespuestasVisita1_idx` (`respuestasVisitaId`),
  ADD KEY `fk_Problems_Categorias1_idx` (`categoriesId`);

--
-- Indices de la tabla `Projects`
--
ALTER TABLE `Projects`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `PublicConsultations`
--
ALTER TABLE `PublicConsultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PublicConsultations_Checklist1_idx` (`checklistId`),
  ADD KEY `fk_PublicConsultations_Projects1_idx` (`projectsId`);

--
-- Indices de la tabla `PublicConsultationsUsers`
--
ALTER TABLE `PublicConsultationsUsers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PublicConsultationsUsers_PublicConsultations1_idx` (`publicConsultationsId`);

--
-- Indices de la tabla `pwdRecover`
--
ALTER TABLE `pwdRecover`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pwdRecover_Users1_idx` (`usersId`);

--
-- Indices de la tabla `Respuestas`
--
ALTER TABLE `Respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Respuestas_Preguntas1_idx` (`preguntasId`),
  ADD KEY `indexRespuesta_Valor` (`valor`),
  ADD KEY `indexRespuesta_Respuesta` (`respuesta`);

--
-- Indices de la tabla `RespuestasVisita`
--
ALTER TABLE `RespuestasVisita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `visitaPregunta` (`visitasId`,`preguntasId`),
  ADD KEY `fk_RespuestasVisita_Preguntas1_idx` (`preguntasId`),
  ADD KEY `fk_RespuestasVisita_Visitas1_idx` (`visitasId`),
  ADD KEY `RespIdentif` (`identificador`);

--
-- Indices de la tabla `Studyarea`
--
ALTER TABLE `Studyarea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Studyarea_Preguntas1_idx` (`preguntasId`);

--
-- Indices de la tabla `StudyareaPoints`
--
ALTER TABLE `StudyareaPoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_StudiareaPoints_Studyarea1_idx` (`studyareaId`);

--
-- Indices de la tabla `Targets`
--
ALTER TABLE `Targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Targets_Projects1_idx` (`projectsId`);

--
-- Indices de la tabla `TargetsChecklist`
--
ALTER TABLE `TargetsChecklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_CommunitiesChecklist_Checklist1_idx` (`checklistId`),
  ADD KEY `fk_CommunitiesChecklist_Tergets1_idx` (`targetsId`);

--
-- Indices de la tabla `TargetsElems`
--
ALTER TABLE `TargetsElems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_TargetsElems_Tergets1_idx` (`targetsId`),
  ADD KEY `fk_TargetsElems_UsersTargets1_idx` (`usersTargetsId`),
  ADD KEY `fk_TargetsElems_Users1_idx` (`usersId`),
  ADD KEY `fk_TargetsElems_DimensionesElem1_idx` (`dimensionesElemId`);

--
-- Indices de la tabla `Tipos`
--
ALTER TABLE `Tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indices de la tabla `UsersAudiences`
--
ALTER TABLE `UsersAudiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usersAudiences_Users1_idx` (`usersId`),
  ADD KEY `fk_usersAudiences_DimensionesElem1_idx` (`dimensionesElemId`),
  ADD KEY `fk_usersAudiences_Audiences1_idx` (`audiencesId`);

--
-- Indices de la tabla `UsersConsultationsChecklist`
--
ALTER TABLE `UsersConsultationsChecklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UsersConsultationsChecklist_Users1_idx` (`usersId`),
  ADD KEY `fk_UsersConsultationsChecklist_ConsultationsChecklist1_idx` (`consultationsChecklistId`);

--
-- Indices de la tabla `UsersLikes`
--
ALTER TABLE `UsersLikes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UsersLikes_Users1_idx` (`usersId`),
  ADD KEY `fk_UsersLikes_Notices1_idx` (`newsId`);

--
-- Indices de la tabla `UsersQuickPoll`
--
ALTER TABLE `UsersQuickPoll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UsersQuickPoll_Users1_idx` (`usersId`),
  ADD KEY `fk_UsersQuickPoll_Consultations1_idx` (`consultationsId`);

--
-- Indices de la tabla `UsersTargets`
--
ALTER TABLE `UsersTargets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UsersComunities_Users1_idx` (`usersId`),
  ADD KEY `fk_UsersTargets_Tergets1_idx` (`targetsId`);

--
-- Indices de la tabla `usrAdmin`
--
ALTER TABLE `usrAdmin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Visitas`
--
ALTER TABLE `Visitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Visitas_Checklist1_idx` (`checklistId`),
  ADD KEY `visElemId` (`elemId`),
  ADD KEY `visType` (`type`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Areas`
--
ALTER TABLE `Areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Audiences`
--
ALTER TABLE `Audiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Bloques`
--
ALTER TABLE `Bloques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `CalculosVisita`
--
ALTER TABLE `CalculosVisita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cambiarPwd`
--
ALTER TABLE `cambiarPwd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Categories`
--
ALTER TABLE `Categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Checklist`
--
ALTER TABLE `Checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ChecklistEst`
--
ALTER TABLE `ChecklistEst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ChecklistImagenes`
--
ALTER TABLE `ChecklistImagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ComplainsStructure`
--
ALTER TABLE `ComplainsStructure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Complaints`
--
ALTER TABLE `Complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ComplaintsHistory`
--
ALTER TABLE `ComplaintsHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Condicionales`
--
ALTER TABLE `Condicionales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Consultations`
--
ALTER TABLE `Consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ConsultationsAudiences`
--
ALTER TABLE `ConsultationsAudiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ConsultationsAudiencesCache`
--
ALTER TABLE `ConsultationsAudiencesCache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ConsultationsChecklist`
--
ALTER TABLE `ConsultationsChecklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Dimensiones`
--
ALTER TABLE `Dimensiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `DimensionesElem`
--
ALTER TABLE `DimensionesElem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `Documents`
--
ALTER TABLE `Documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DocumentsComments`
--
ALTER TABLE `DocumentsComments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Estatus`
--
ALTER TABLE `Estatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estatusHist`
--
ALTER TABLE `estatusHist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Frequencies`
--
ALTER TABLE `Frequencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `General`
--
ALTER TABLE `General`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `GeometriesAttributes`
--
ALTER TABLE `GeometriesAttributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `KML`
--
ALTER TABLE `KML`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `KMLAttributes`
--
ALTER TABLE `KMLAttributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `KMLGeometries`
--
ALTER TABLE `KMLGeometries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Multimedia`
--
ALTER TABLE `Multimedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `News`
--
ALTER TABLE `News`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Points`
--
ALTER TABLE `Points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Preguntas`
--
ALTER TABLE `Preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Problems`
--
ALTER TABLE `Problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Projects`
--
ALTER TABLE `Projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `PublicConsultations`
--
ALTER TABLE `PublicConsultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `PublicConsultationsUsers`
--
ALTER TABLE `PublicConsultationsUsers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pwdRecover`
--
ALTER TABLE `pwdRecover`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Respuestas`
--
ALTER TABLE `Respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RespuestasVisita`
--
ALTER TABLE `RespuestasVisita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Studyarea`
--
ALTER TABLE `Studyarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `StudyareaPoints`
--
ALTER TABLE `StudyareaPoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Targets`
--
ALTER TABLE `Targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `TargetsChecklist`
--
ALTER TABLE `TargetsChecklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TargetsElems`
--
ALTER TABLE `TargetsElems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Tipos`
--
ALTER TABLE `Tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `UsersAudiences`
--
ALTER TABLE `UsersAudiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `UsersConsultationsChecklist`
--
ALTER TABLE `UsersConsultationsChecklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `UsersLikes`
--
ALTER TABLE `UsersLikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `UsersQuickPoll`
--
ALTER TABLE `UsersQuickPoll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `UsersTargets`
--
ALTER TABLE `UsersTargets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usrAdmin`
--
ALTER TABLE `usrAdmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `Visitas`
--
ALTER TABLE `Visitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Areas`
--
ALTER TABLE `Areas`
  ADD CONSTRAINT `fk_Areas_Bloques1` FOREIGN KEY (`bloquesId`) REFERENCES `Bloques` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Audiences`
--
ALTER TABLE `Audiences`
  ADD CONSTRAINT `fk_Audiences_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Bloques`
--
ALTER TABLE `Bloques`
  ADD CONSTRAINT `fk_Bloques_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `CalculosVisita`
--
ALTER TABLE `CalculosVisita`
  ADD CONSTRAINT `fk_CalculosVisita_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Categories`
--
ALTER TABLE `Categories`
  ADD CONSTRAINT `fk_Categorias_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ChecklistEst`
--
ALTER TABLE `ChecklistEst`
  ADD CONSTRAINT `fk_ChecklistEst_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ChecklistImagenes`
--
ALTER TABLE `ChecklistImagenes`
  ADD CONSTRAINT `fk_CheklistsImagenes_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Complaints`
--
ALTER TABLE `Complaints`
  ADD CONSTRAINT `fk_Complains_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Complains_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Complains_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Complains_usrAdmin1` FOREIGN KEY (`adminId`) REFERENCES `usrAdmin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ComplaintsHistory`
--
ALTER TABLE `ComplaintsHistory`
  ADD CONSTRAINT `fk_ComplaintsHistory_Complaints1` FOREIGN KEY (`complaintsId`) REFERENCES `Complaints` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ComplaintsHistory_usrAdmin1` FOREIGN KEY (`adminId`) REFERENCES `usrAdmin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ComplaintsTracking`
--
ALTER TABLE `ComplaintsTracking`
  ADD CONSTRAINT `fk_table1_Complains1` FOREIGN KEY (`complainsId`) REFERENCES `Complaints` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_usrAdmin1` FOREIGN KEY (`adminUserId`) REFERENCES `usrAdmin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Consultations`
--
ALTER TABLE `Consultations`
  ADD CONSTRAINT `fk_Consultations_ComplainsStructure1` FOREIGN KEY (`complainsStructureId`) REFERENCES `ComplainsStructure` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Consultations_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ConsultationsAudiences`
--
ALTER TABLE `ConsultationsAudiences`
  ADD CONSTRAINT `fk_ConsultationsAudiences_Audiences1` FOREIGN KEY (`audiencesId`) REFERENCES `Audiences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsAudiences_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsAudiences_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ConsultationsAudiencesCache`
--
ALTER TABLE `ConsultationsAudiencesCache`
  ADD CONSTRAINT `fk_ConsultationsAudiencesCache_Audiences1` FOREIGN KEY (`audiencesId`) REFERENCES `Audiences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsAudiencesCache_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsAudiencesCache_ConsultationsAudiences1` FOREIGN KEY (`consultationAudiencesId`) REFERENCES `ConsultationsAudiences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsAudiencesCache_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ConsultationsChecklist`
--
ALTER TABLE `ConsultationsChecklist`
  ADD CONSTRAINT `fk_ConsultationsChecklist_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsChecklist_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ConsultationsChecklist_Frequencies1` FOREIGN KEY (`frequency`) REFERENCES `Frequencies` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `DimensionesElem`
--
ALTER TABLE `DimensionesElem`
  ADD CONSTRAINT `fk_DimensionesElem_Dimensiones1` FOREIGN KEY (`dimensionesId`) REFERENCES `Dimensiones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DimensionesElem_DimensionesElem1` FOREIGN KEY (`padre`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Documents`
--
ALTER TABLE `Documents`
  ADD CONSTRAINT `fk_Documents_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `DocumentsComments`
--
ALTER TABLE `DocumentsComments`
  ADD CONSTRAINT `fk_DocumentsComments_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DocumentsComments_Documents1` FOREIGN KEY (`documentsId`) REFERENCES `Documents` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DocumentsComments_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estatusHist`
--
ALTER TABLE `estatusHist`
  ADD CONSTRAINT `fk_estatusHist_Estatus1` FOREIGN KEY (`estatus`) REFERENCES `Estatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_estatusHist_usrAdmin1` FOREIGN KEY (`usuarioId`) REFERENCES `usrAdmin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `GeometriesAttributes`
--
ALTER TABLE `GeometriesAttributes`
  ADD CONSTRAINT `fk_GeometriesAttributes_KMLAttibutes1` FOREIGN KEY (`attributeId`) REFERENCES `KMLAttributes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_GeometriesAttributes_KMLGeometries1` FOREIGN KEY (`geometriesId`) REFERENCES `KMLGeometries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `KML`
--
ALTER TABLE `KML`
  ADD CONSTRAINT `fk_KML_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `KMLAttributes`
--
ALTER TABLE `KMLAttributes`
  ADD CONSTRAINT `fk_KMLAttibutes_KML1` FOREIGN KEY (`KMLId`) REFERENCES `KML` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `KMLGeometries`
--
ALTER TABLE `KMLGeometries`
  ADD CONSTRAINT `fk_KMLGeometries_KML1` FOREIGN KEY (`KMLId`) REFERENCES `KML` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Multimedia`
--
ALTER TABLE `Multimedia`
  ADD CONSTRAINT `fk_table1_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Points`
--
ALTER TABLE `Points`
  ADD CONSTRAINT `fk_Puntos_Problems1` FOREIGN KEY (`problemsId`) REFERENCES `Problems` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Preguntas`
--
ALTER TABLE `Preguntas`
  ADD CONSTRAINT `fk_Preguntas_Areas1` FOREIGN KEY (`areasId`) REFERENCES `Areas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Preguntas_Tipos1` FOREIGN KEY (`tiposId`) REFERENCES `Tipos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Problems`
--
ALTER TABLE `Problems`
  ADD CONSTRAINT `fk_Problems_Categorias1` FOREIGN KEY (`categoriesId`) REFERENCES `Categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Problems_RespuestasVisita1` FOREIGN KEY (`respuestasVisitaId`) REFERENCES `RespuestasVisita` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `PublicConsultations`
--
ALTER TABLE `PublicConsultations`
  ADD CONSTRAINT `fk_PublicConsultations_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PublicConsultations_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `PublicConsultationsUsers`
--
ALTER TABLE `PublicConsultationsUsers`
  ADD CONSTRAINT `fk_PublicConsultationsUsers_PublicConsultations1` FOREIGN KEY (`publicConsultationsId`) REFERENCES `PublicConsultations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pwdRecover`
--
ALTER TABLE `pwdRecover`
  ADD CONSTRAINT `fk_pwdRecover_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Respuestas`
--
ALTER TABLE `Respuestas`
  ADD CONSTRAINT `fk_Respuestas_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `RespuestasVisita`
--
ALTER TABLE `RespuestasVisita`
  ADD CONSTRAINT `fk_RespuestasVisita_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RespuestasVisita_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Studyarea`
--
ALTER TABLE `Studyarea`
  ADD CONSTRAINT `fk_Studyarea_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `StudyareaPoints`
--
ALTER TABLE `StudyareaPoints`
  ADD CONSTRAINT `fk_StudiareaPoints_Studyarea1` FOREIGN KEY (`studyareaId`) REFERENCES `Studyarea` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Targets`
--
ALTER TABLE `Targets`
  ADD CONSTRAINT `fk_Targets_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `TargetsChecklist`
--
ALTER TABLE `TargetsChecklist`
  ADD CONSTRAINT `fk_CommunitiesChecklist_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CommunitiesChecklist_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `TargetsElems`
--
ALTER TABLE `TargetsElems`
  ADD CONSTRAINT `fk_TargetsElems_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TargetsElems_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TargetsElems_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TargetsElems_UsersTargets1` FOREIGN KEY (`usersTargetsId`) REFERENCES `UsersTargets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `UsersAudiences`
--
ALTER TABLE `UsersAudiences`
  ADD CONSTRAINT `fk_usersAudiences_Audiences1` FOREIGN KEY (`audiencesId`) REFERENCES `Audiences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usersAudiences_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usersAudiences_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `UsersConsultationsChecklist`
--
ALTER TABLE `UsersConsultationsChecklist`
  ADD CONSTRAINT `fk_UsersConsultationsChecklist_ConsultationsChecklist1` FOREIGN KEY (`consultationsChecklistId`) REFERENCES `ConsultationsChecklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UsersConsultationsChecklist_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `UsersLikes`
--
ALTER TABLE `UsersLikes`
  ADD CONSTRAINT `fk_UsersLikes_Notices1` FOREIGN KEY (`newsId`) REFERENCES `News` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UsersLikes_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `UsersQuickPoll`
--
ALTER TABLE `UsersQuickPoll`
  ADD CONSTRAINT `fk_UsersQuickPoll_Consultations1` FOREIGN KEY (`consultationsId`) REFERENCES `Consultations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UsersQuickPoll_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `UsersTargets`
--
ALTER TABLE `UsersTargets`
  ADD CONSTRAINT `fk_UsersComunities_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UsersTargets_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Visitas`
--
ALTER TABLE `Visitas`
  ADD CONSTRAINT `fk_Visitas_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
