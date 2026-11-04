-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 04-11-2026 a las 21:11:35
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario_innovacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delivery_records`
--

CREATE TABLE `delivery_records` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_date` date NOT NULL,
  `district` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receives` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_location_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_location_id` bigint UNSIGNED DEFAULT NULL,
  `to_location_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_area_id` bigint UNSIGNED DEFAULT NULL,
  `location_id` bigint UNSIGNED DEFAULT NULL,
  `total_lines` int UNSIGNED NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `annulled_at` timestamp NULL DEFAULT NULL,
  `annulled_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `delivery_records`
--

INSERT INTO `delivery_records` (`id`, `type`, `delivery_date`, `district`, `area_name`, `responsible_name`, `receives`, `from_location_name`, `from_location_id`, `to_location_name`, `assigned_area_id`, `location_id`, `total_lines`, `created_by`, `annulled_at`, `annulled_by`, `created_at`, `updated_at`) VALUES
(1, 'supplies', '2026-03-23', 'Zacatecoluca', 'Secretaria de Innovacion', 'Jerson Ernesto Zelaya Villalta', 'Rene Vasquez', 'Oficina', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, '2026-03-23 21:55:22', '2026-03-23 21:55:22'),
(2, 'supplies', '2026-03-23', 'Zacatecoluca', 'Secretaria de Innovacion', 'Jerson Ernesto Zelaya Villalta', 'Emilio Menchu', 'Oficina', NULL, 'Secretaria de Innovacion', NULL, NULL, 1, 2, NULL, NULL, '2026-03-23 22:01:24', '2026-03-23 22:01:24'),
(3, 'equipments', '2026-03-23', 'Zacatecoluca', 'Almacen', 'Luis Vladimir Nochez Moreno', 'jhkhfkjggkg', NULL, NULL, 'Almacen', NULL, NULL, 1, 2, NULL, NULL, '2026-03-23 22:07:03', '2026-03-23 22:07:03'),
(4, 'supplies', '2026-03-27', 'Zacatecoluca', 'Gerencia Financiera', 'Juan Jose Calero Cruz', 'Juan Jose Calero', 'Oficina', NULL, 'Gerencia Financiera', NULL, NULL, 1, 1, NULL, NULL, '2026-03-27 17:19:15', '2026-03-27 17:19:15'),
(5, 'equipments', '2026-04-07', 'Zacatecoluca', 'Depto Tesoreria', 'Fatima Brendaly Tamayo', 'Fatima Brendaly Tamayo', NULL, NULL, 'Depto Tesoreria', NULL, NULL, 1, 1, NULL, NULL, '2026-04-07 14:54:56', '2026-04-07 14:54:56'),
(6, 'supplies', '2026-04-07', 'San Juan Nonualco', 'Registro Familiar', 'Luisa Jerardina Minero Moscote', 'Cesar Barahona', 'Oficina', NULL, 'Registro Familiar', NULL, NULL, 1, 1, NULL, NULL, '2026-04-07 15:06:26', '2026-04-07 15:06:26'),
(7, 'supplies', '2026-04-14', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Sofia Dalia Villacorta', 'Oficina', NULL, 'Secretaria de Comunicaciones Y Relaciones Publicas', NULL, NULL, 1, 1, NULL, NULL, '2026-04-15 13:44:51', '2026-04-15 13:44:51'),
(8, 'supplies', '2026-04-16', 'Zacatecoluca', 'Depto Activo Fijo', 'Carlos Edilberto Cañas Aguirre', 'Carlos Edilberto Cañas Aguirre', 'Oficina', NULL, 'Depto Activo Fijo', NULL, NULL, 1, 1, NULL, NULL, '2026-04-16 19:08:19', '2026-04-16 19:08:19'),
(9, 'supplies', '2026-04-20', 'Zacatecoluca', 'Depto Tesoreria', 'Fatima Brendaly Tamayo', 'Yhoalmo Rodrigez', 'Oficina', NULL, 'Depto Tesoreria', NULL, NULL, 1, 1, NULL, NULL, '2026-04-20 16:19:03', '2026-04-20 16:19:03'),
(11, 'supplies', '2026-04-22', 'Zacatecoluca', 'Secretario Municipal', 'Guillermo Antonio Villalta Ramos', 'Guillermo Antonio Villalta Ramos', 'Bodega', NULL, 'Secretario Municipal', NULL, NULL, 1, 1, NULL, NULL, '2026-04-22 20:08:59', '2026-04-22 20:08:59'),
(12, 'supplies', '2026-04-22', 'Zacatecoluca', 'Gerente Tributario', 'Wilver Gerardo Parada Garcia', 'Wilver Gerardo Parada Garcia', 'Bodega', NULL, 'Gerente Tributario', NULL, NULL, 1, 1, NULL, NULL, '2026-04-22 20:20:26', '2026-04-22 20:20:26'),
(13, 'supplies', '2026-04-23', 'Zacatecoluca', 'Gerente Tributario', 'Wilver Gerardo Parada Garcia', 'Wilver Gerardo Parada Garcia', 'Bodega', NULL, 'Gerente Tributario', NULL, NULL, 1, 1, NULL, NULL, '2026-04-23 19:45:12', '2026-04-23 19:45:12'),
(14, 'supplies', '2026-04-28', 'Zacatecoluca', 'Gerencia General', 'Sarahi Alejandra Cortez Alfaro', 'Sarahi Alejandra Cortez Alfaro', 'Bodega', 1, 'Gerencia General', 29, NULL, 1, 1, NULL, NULL, '2026-04-28 21:57:06', '2026-04-28 21:57:06'),
(15, 'supplies', '2026-05-04', 'Zacatecoluca', 'Registro Familiar', 'Luisa Jerardina Minero Moscote', 'Luisa Jerardina Minero Moscote', 'Oficina', 2, 'Registro Familiar', 27, NULL, 1, 1, NULL, NULL, '2026-05-04 16:06:50', '2026-05-04 16:06:50'),
(16, 'supplies', '2026-05-05', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Sofia Villacorta', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-05 14:36:41', '2026-05-05 14:36:41'),
(17, 'supplies', '2026-05-07', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Sofia Villacorta', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-07 21:54:51', '2026-05-07 21:54:51'),
(18, 'supplies', '2026-05-12', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Sofia Villacorta', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-12 15:18:11', '2026-05-12 15:18:11'),
(19, 'supplies', '2026-05-08', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Sofia Villacorta', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-12 15:19:02', '2026-05-12 15:19:02'),
(20, 'supplies', '2026-05-13', 'Zacatecoluca', 'Turismo y Cultura', 'Cristian Mauricio Santos Molina', 'Cristian Mauricio Santos Molina', 'Bodega', 1, 'Turismo y Cultura', 69, NULL, 1, 1, NULL, NULL, '2026-05-13 17:44:40', '2026-05-13 17:44:40'),
(21, 'supplies', '2026-05-14', 'Zacatecoluca', 'Depto Tesoreria', 'Fatima Brendaly Tamayo', 'Fatima Brendaly Tamayo', 'Bodega', 1, 'Depto Tesoreria', 35, NULL, 1, 1, NULL, NULL, '2026-05-14 15:17:53', '2026-05-14 15:17:53'),
(22, 'supplies', '2026-05-14', 'Zacatecoluca', 'Depto Tesoreria', 'Fatima Brendaly Tamayo', 'Fatima Brendaly Tamayo', 'Bodega', 1, 'Depto Tesoreria', 35, NULL, 1, 1, NULL, NULL, '2026-05-14 15:21:28', '2026-05-14 15:21:28'),
(23, 'supplies', '2026-05-15', 'Zacatecoluca', 'Secretario Municipal', 'Guillermo Antonio Villalta Ramos', 'Guillermo Antonio Villalta Ramos', 'Oficina', 2, 'Secretario Municipal', 3, NULL, 1, 1, NULL, NULL, '2026-05-15 19:49:33', '2026-05-15 19:49:33'),
(24, 'supplies', '2026-05-18', 'Zacatecoluca', 'Mercados', 'Carla Nere Ortiz Hernandez', 'Carla Nere Ortiz Hernandez', 'Oficina', 2, 'Mercados', 50, NULL, 1, 1, NULL, NULL, '2026-05-18 14:40:34', '2026-05-18 14:40:34'),
(25, 'supplies', '2026-05-18', 'Zacatecoluca', 'Mercados', 'Carla Rene Ortiz Hernandez', 'Mauricio Alberto Yanez', 'Oficina', 2, 'Mercados', 50, NULL, 1, 1, NULL, NULL, '2026-05-18 14:45:26', '2026-05-18 14:45:26'),
(26, 'supplies', '2026-05-21', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-21 17:46:49', '2026-05-21 17:46:49'),
(27, 'supplies', '2026-05-25', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-05-25 14:00:42', '2026-05-25 14:00:42'),
(28, 'supplies', '2026-05-28', 'Zacatecoluca', 'Despacho Municipal', 'Kenia Sarai Miranda', 'Kenia Sarai Miranda', 'Bodega', 1, 'Despacho Municipal', 6, NULL, 1, 1, NULL, NULL, '2026-05-28 16:05:43', '2026-05-28 16:05:43'),
(29, 'supplies', '2026-06-01', 'Zacatecoluca', 'Gerencia de Obras Municipales y Proyectos', 'Walter Alexander Torres Tobar', 'Walter Alexander Torres Tobar', 'Bodega', 1, 'Gerencia de Obras Municipales y Proyectos', 44, NULL, 1, 1, NULL, NULL, '2026-06-01 19:57:50', '2026-06-01 19:57:50'),
(30, 'supplies', '2026-06-02', 'Zacatecoluca', 'Unidad de Compras Publicas', 'Fatima Dolores Rivera de Torres', 'Fatima Dolores Rivera de Torres', 'Bodega', 1, 'Unidad de Compras Publicas', 24, NULL, 1, 1, NULL, NULL, '2026-06-02 14:58:35', '2026-06-02 14:58:35'),
(31, 'supplies', '2026-06-02', 'Zacatecoluca', 'Deportes', 'Raul Alberto Lovato Osorio', 'Raul Alberto Lovato Osorio', 'Oficina', 2, 'Deportes', 62, NULL, 1, 1, NULL, NULL, '2026-06-02 15:16:59', '2026-06-02 15:16:59'),
(32, 'supplies', '2026-06-18', 'Zacatecoluca', 'Depto Mujer', 'Nancy Carolina Cortez Molina', 'Nancy Carolina Cortez Molina', 'Oficina', 2, 'Depto Mujer', 60, NULL, 1, 1, NULL, NULL, '2026-06-18 20:50:08', '2026-06-18 20:50:08'),
(33, 'supplies', '2026-06-19', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-06-19 14:52:51', '2026-06-19 14:52:51'),
(34, 'supplies', '2026-06-22', 'Zacatecoluca', 'Sub-Gerente Legal', 'Ana Guadalupe Menendez de Muñoz', 'Yessy Beatriz Valle García', 'Oficina', 2, 'Sub-Gerente Legal', 26, NULL, 1, 1, '2026-06-22 15:20:05', 1, '2026-06-22 14:57:11', '2026-06-22 15:20:05'),
(35, 'supplies', '2026-06-22', 'Zacatecoluca', 'Sub-Gerente Legal', 'Ana Guadalupe Menendez de Muñoz', 'Yessy Beatriz Valle Garcia', 'Oficina', 2, 'Sub-Gerente Legal', 26, NULL, 1, 1, NULL, NULL, '2026-06-22 14:58:05', '2026-06-22 14:58:05'),
(36, 'supplies', '2026-06-22', 'Zacatecoluca', 'Sub-Gerente Legal', 'Ana Guadalupe Menendez de Muñoz', 'Nohemi Mejia', 'Oficina', 2, 'Sub-Gerente Legal', 26, NULL, 1, 1, NULL, NULL, '2026-06-22 16:39:13', '2026-06-22 16:39:13'),
(37, 'supplies', '2026-06-26', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-06-26 21:10:27', '2026-06-26 21:10:27'),
(38, 'supplies', '2026-06-29', 'Zacatecoluca', 'Secretario Municipal', 'Guillermo Antonio Villalta Ramos', 'Guillermo Antonio Villalta Ramos', 'Oficina', 2, 'Secretario Municipal', 3, NULL, 1, 1, NULL, NULL, '2026-06-29 20:48:47', '2026-06-29 20:48:47'),
(39, 'supplies', '2026-06-29', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-06-29 21:49:12', '2026-06-29 21:49:12'),
(40, 'supplies', '2026-06-30', 'Zacatecoluca', 'Contravencional', 'Pamela Cecilia Diaz', 'Pamela Cecilia Diaz', 'Oficina', 2, 'Contravencional', 28, NULL, 1, 1, NULL, NULL, '2026-06-30 15:39:07', '2026-06-30 15:39:07'),
(41, 'supplies', '2026-06-30', 'Zacatecoluca', 'Gerencia de Obras Municipales y Proyectos', 'Walter Alexander Torres Tobar', 'Walter Alexander Torres Tobar', 'Bodega', 1, 'Gerencia de Obras Municipales y Proyectos', 44, NULL, 1, 1, NULL, NULL, '2026-06-30 20:27:52', '2026-06-30 20:27:52'),
(42, 'supplies', '2026-07-01', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-07-01 21:12:04', '2026-07-01 21:12:04'),
(43, 'supplies', '2026-07-07', 'Zacatecoluca', 'Gerencia General', 'Sarahi Alejandra Cortez Alfaro', 'Sarahi Alejandra Cortez Alfaro', 'Bodega', 1, 'Gerencia General', 29, NULL, 1, 1, NULL, NULL, '2026-07-07 14:38:59', '2026-07-07 14:38:59'),
(44, 'supplies', '2026-07-09', 'Zacatecoluca', 'Gerencia de Obras Municipales y Proyectos', 'Walter Alexander Torres Tobar', 'Walter Alexander Torres Tobar', 'Bodega', 1, 'Gerencia de Obras Municipales y Proyectos', 44, NULL, 1, 1, '2026-07-09 14:17:14', 1, '2026-07-09 14:16:51', '2026-07-09 14:17:14'),
(45, 'supplies', '2026-07-09', 'Zacatecoluca', 'Gerencia de Obras Municipales y Proyectos', 'Walter Alexander Torres Tobar', 'Walter Alexander Torres Tobar', 'Bodega', 1, 'Gerencia de Obras Municipales y Proyectos', 44, NULL, 1, 1, NULL, NULL, '2026-07-09 14:17:42', '2026-07-09 14:17:42'),
(46, 'supplies', '2026-07-09', 'Zacatecoluca', 'Turismo y Cultura', 'Cristian Mauricio Santos Molina', 'Cristian Mauricio Santos Molina', 'Bodega', 1, 'Turismo y Cultura', 69, NULL, 4, 1, NULL, NULL, '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(47, 'supplies', '2026-07-03', 'Zacatecoluca', 'Integral de Riesgos', 'Jose Israel Hernandez Ayala', 'Evelin Margarita Flores', 'Oficina', 2, 'Integral de Riesgos', 20, NULL, 4, 1, NULL, NULL, '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(48, 'supplies', '2026-07-13', 'Zacatecoluca', 'Limpieza Publica e Institucional', 'Marco Antonio Somoza Salomon', 'Marco Antonio Somoza Salomon', 'Oficina', 2, 'Limpieza Publica e Institucional', 53, NULL, 1, 1, NULL, NULL, '2026-07-13 14:40:52', '2026-07-13 14:40:52'),
(49, 'supplies', '2026-07-13', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-07-13 14:47:30', '2026-07-13 14:47:30'),
(50, 'supplies', '2026-06-15', 'Zacatecoluca', 'Gerencia Financiera', 'Juan Jose Calero Cruz', 'Juan Jose Calero Cruz', 'Oficina', 2, 'Gerencia Financiera', 33, NULL, 1, 1, NULL, NULL, '2026-07-13 14:51:00', '2026-07-13 14:51:00'),
(51, 'supplies', '2026-05-06', 'Zacatecoluca', 'Unidad de Compras Publicas', 'Fatima Dolores Rivera de Torres', 'Fatima Dolores Rivera de Torres', 'Bodega', 1, 'Unidad de Compras Publicas', 24, NULL, 1, 1, NULL, NULL, '2026-07-13 15:18:44', '2026-07-13 15:18:44'),
(52, 'supplies', '2026-07-15', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Oficina', 2, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-07-15 20:30:47', '2026-07-15 20:30:47'),
(53, 'supplies', '2026-07-16', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-07-16 14:37:28', '2026-07-16 14:37:28'),
(54, 'supplies', '2026-07-16', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-07-16 15:46:40', '2026-07-16 15:46:40'),
(55, 'supplies', '2026-07-23', 'Zacatecoluca', 'Secretaria de Comunicaciones Y Relaciones Publicas', 'Adela Fernanda Sibrian Bermudez', 'Adela Fernanda Sibrian Bermudez', 'Bodega', 1, 'Secretaria de Comunicaciones Y Relaciones Publicas', 17, NULL, 1, 1, NULL, NULL, '2026-07-23 21:35:46', '2026-07-23 21:35:46'),
(56, 'supplies', '2026-07-23', 'Zacatecoluca', 'Directora Talento Humano', 'Nerys Azucena Beltran', 'Nerys Azucena Beltran', 'Oficina', 2, 'Directora Talento Humano', 15, NULL, 1, 1, NULL, NULL, '2026-07-23 21:37:00', '2026-07-23 21:37:00'),
(57, 'supplies', '2026-07-24', 'Zacatecoluca', 'Sub-Gerente Legal', 'Ana Guadalupe Menendez de Muñoz', 'Ana Guadalupe Menendez de Muñoz', 'Oficina', 2, 'Sub-Gerente Legal', 26, NULL, 1, 1, '2026-07-24 15:04:52', 1, '2026-07-24 15:00:18', '2026-07-24 15:04:52'),
(58, 'supplies', '2026-07-24', 'Zacatecoluca', 'Sub-Gerente Legal', 'Ana Guadalupe Menendez de Muñoz', '.', 'Oficina', 2, 'Sub-Gerente Legal', 26, NULL, 1, 1, NULL, NULL, '2026-07-24 15:05:39', '2026-07-24 15:05:39'),
(59, 'supplies', '2026-07-24', 'Zacatecoluca', 'Depto Presupuesto', 'David Salvador Mancia', 'David Salvador Mancia', 'Bodega', 1, 'Depto Presupuesto', 37, NULL, 1, 1, NULL, NULL, '2026-07-24 17:20:26', '2026-07-24 17:20:26'),
(60, 'supplies', '2026-08-12', 'Zacatecoluca', 'Renovadores', 'Oscar Enrique Guzman', 'Oscar Enrique Guzman', 'Oficina', 2, 'Renovadores', 46, NULL, 4, 1, NULL, NULL, '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(61, 'supplies', '2026-08-17', 'Zacatecoluca', 'Gerencia de Obras Municipales y Proyectos', 'Walter Alexander Torres Tobar', 'Walter Alexander Torres Tobar', 'Bodega', 1, 'Gerencia de Obras Municipales y Proyectos', 44, NULL, 1, 1, NULL, NULL, '2026-08-17 17:40:23', '2026-08-17 17:40:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delivery_record_lines`
--

CREATE TABLE `delivery_record_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `delivery_record_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED DEFAULT NULL,
  `equipment_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int UNSIGNED DEFAULT NULL,
  `asset_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `delivery_record_lines`
--

INSERT INTO `delivery_record_lines` (`id`, `delivery_record_id`, `description`, `item_id`, `equipment_id`, `quantity`, `asset_code`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 1, 'Teclado Logitech MK120', NULL, NULL, 1, NULL, NULL, '2026-03-23 21:55:22', '2026-03-23 21:55:22'),
(2, 2, 'Teclado Logitech MK120', NULL, NULL, 1, NULL, NULL, '2026-03-23 22:01:24', '2026-03-23 22:01:24'),
(3, 3, 'Impresora CANON MAXIFY GX7110', NULL, NULL, 1, 'N/A', '916880C00492AA21KRWK02151', '2026-03-23 22:07:03', '2026-03-23 22:07:03'),
(4, 4, 'CARTUCHO MANTENIMIENTO CANON MC-G01', NULL, NULL, 1, NULL, NULL, '2026-03-27 17:19:15', '2026-03-27 17:19:15'),
(5, 5, 'Impresora EPSON FX-890 IIN', NULL, NULL, 1, NULL, 'X3YJ015218', '2026-04-07 14:54:56', '2026-04-07 14:54:56'),
(6, 6, 'TONER HP 136A', NULL, NULL, 1, NULL, NULL, '2026-04-07 15:06:26', '2026-04-07 15:06:26'),
(7, 7, 'Teclado Logitech MK120', NULL, NULL, 1, NULL, NULL, '2026-04-15 13:44:51', '2026-04-15 13:44:51'),
(8, 8, 'UPS Orbit TC-7508', NULL, NULL, 1, NULL, NULL, '2026-04-16 19:08:19', '2026-04-16 19:08:19'),
(9, 9, 'Teclado Logitech MK120', NULL, NULL, 1, NULL, NULL, '2026-04-20 16:19:03', '2026-04-20 16:19:03'),
(11, 11, 'TONER HP 202A YELLOW', NULL, NULL, 1, NULL, NULL, '2026-04-22 20:08:59', '2026-04-22 20:08:59'),
(12, 12, 'TONER KONICA TN328K', NULL, NULL, 1, NULL, NULL, '2026-04-22 20:20:26', '2026-04-22 20:20:26'),
(13, 13, 'TONER KONICA TN328C', NULL, NULL, 1, NULL, NULL, '2026-04-23 19:45:12', '2026-04-23 19:45:12'),
(14, 14, 'TONER KONICA TN328K', 21, NULL, 1, NULL, NULL, '2026-04-28 21:57:06', '2026-04-28 21:57:06'),
(15, 15, 'TONER HP 136A', 29, NULL, 1, NULL, NULL, '2026-05-04 16:06:50', '2026-05-04 16:06:50'),
(16, 16, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-05-05 14:36:41', '2026-05-05 14:36:41'),
(17, 17, 'TONER KONICA TN328K', 21, NULL, 1, NULL, NULL, '2026-05-07 21:54:51', '2026-05-07 21:54:51'),
(18, 18, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-05-12 15:18:11', '2026-05-12 15:18:11'),
(19, 19, 'TONER KONICA TN328M', 22, NULL, 1, NULL, NULL, '2026-05-12 15:19:02', '2026-05-12 15:19:02'),
(20, 20, 'TONER HP 410A CYAN', 24, NULL, 1, NULL, NULL, '2026-05-13 17:44:40', '2026-05-13 17:44:40'),
(21, 21, 'TONER HP 26X NEGRO', 28, NULL, 1, NULL, NULL, '2026-05-14 15:17:53', '2026-05-14 15:17:53'),
(22, 22, 'TONER HP 26X NEGRO', 28, NULL, 1, NULL, NULL, '2026-05-14 15:21:28', '2026-05-14 15:21:28'),
(23, 23, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-05-15 19:49:33', '2026-05-15 19:49:33'),
(24, 24, 'Tinta CANON 16BK', 7, NULL, 2, NULL, NULL, '2026-05-18 14:40:34', '2026-05-18 14:40:34'),
(25, 25, 'Tinta CANON 16BK', 7, NULL, 2, NULL, NULL, '2026-05-18 14:45:26', '2026-05-18 14:45:26'),
(26, 26, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-05-21 17:46:49', '2026-05-21 17:46:49'),
(27, 27, 'TONER KONICA TN328M', 22, NULL, 1, NULL, NULL, '2026-05-25 14:00:42', '2026-05-25 14:00:42'),
(28, 28, 'TONER KONICA TN328K', 21, NULL, 1, NULL, NULL, '2026-05-28 16:05:43', '2026-05-28 16:05:43'),
(29, 29, 'TONER HP 26X NEGRO', 28, NULL, 1, NULL, NULL, '2026-06-01 19:57:50', '2026-06-01 19:57:50'),
(30, 30, 'TONER KONICA TN328Y', 20, NULL, 1, NULL, NULL, '2026-06-02 14:58:35', '2026-06-02 14:58:35'),
(31, 31, 'Tinta BROTHER BT5001C', 9, NULL, 1, NULL, NULL, '2026-06-02 15:16:59', '2026-06-02 15:16:59'),
(32, 32, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-06-18 20:50:08', '2026-06-18 20:50:08'),
(33, 33, 'Tarjeta ZEBRA Premier pvc 30ml', 47, NULL, 16, NULL, NULL, '2026-06-19 14:52:51', '2026-06-19 14:52:51'),
(34, 34, 'CARTUCHO MANTENIMIENTO CANON MC-G01', 36, NULL, 1, NULL, NULL, '2026-06-22 14:57:11', '2026-06-22 14:57:11'),
(35, 35, 'CARTUCHO MANTENIMIENTO CANON MC-G01', 36, NULL, 1, NULL, NULL, '2026-06-22 14:58:05', '2026-06-22 14:58:05'),
(36, 36, 'Teclado Logitech MK120', 2, NULL, 1, NULL, NULL, '2026-06-22 16:39:13', '2026-06-22 16:39:13'),
(37, 37, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-06-26 21:10:27', '2026-06-26 21:10:27'),
(38, 38, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-06-29 20:48:47', '2026-06-29 20:48:47'),
(39, 39, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-06-29 21:49:12', '2026-06-29 21:49:12'),
(40, 40, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-06-30 15:39:07', '2026-06-30 15:39:07'),
(41, 41, 'Teclado Logitech MK120', 2, NULL, 1, NULL, NULL, '2026-06-30 20:27:52', '2026-06-30 20:27:52'),
(42, 42, 'CARTUCHO MANTENIMIENTO CANON MC-G01', 36, NULL, 1, NULL, NULL, '2026-07-01 21:12:04', '2026-07-01 21:12:04'),
(43, 43, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-07-07 14:38:59', '2026-07-07 14:38:59'),
(44, 44, 'Toner HP 58A Negro', 16, NULL, 1, NULL, NULL, '2026-07-09 14:16:51', '2026-07-09 14:16:51'),
(45, 45, 'Toner HP 58A Negro', 16, NULL, 1, NULL, NULL, '2026-07-09 14:17:42', '2026-07-09 14:17:42'),
(46, 46, 'TONER HP 410A YELLOW', 23, NULL, 1, NULL, NULL, '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(47, 46, 'TONER HP 410A MAGENTA', 25, NULL, 1, NULL, NULL, '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(48, 46, 'TONER HP 410A BLACK', 26, NULL, 1, NULL, NULL, '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(49, 46, 'TONER HP 410A CYAN', 24, NULL, 1, NULL, NULL, '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(50, 47, 'Tinta BROTHER BT5001C', 9, NULL, 1, NULL, NULL, '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(51, 47, 'Tinta BROTHER BT5001M', 12, NULL, 1, NULL, NULL, '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(52, 47, 'Tinta Brother BT5001Y', 3, NULL, 1, NULL, NULL, '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(53, 47, 'Tinta Brother BTD60BK', 1, NULL, 1, NULL, NULL, '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(54, 48, 'Tinta EPSON 544BK', 4, NULL, 1, NULL, NULL, '2026-07-13 14:40:52', '2026-07-13 14:40:52'),
(55, 49, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-07-13 14:47:30', '2026-07-13 14:47:30'),
(56, 50, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-07-13 14:51:00', '2026-07-13 14:51:00'),
(57, 51, 'TONER KONICA TN328K', 21, NULL, 1, NULL, NULL, '2026-07-13 15:18:44', '2026-07-13 15:18:44'),
(58, 52, 'TONER KONICA TN328C', 35, NULL, 1, NULL, NULL, '2026-07-15 20:30:47', '2026-07-15 20:30:47'),
(59, 53, 'TONER KONICA TN328M', 22, NULL, 1, NULL, NULL, '2026-07-16 14:37:28', '2026-07-16 14:37:28'),
(60, 54, 'Tarjeta ZEBRA Premier pvc 30ml', 47, NULL, 13, NULL, NULL, '2026-07-16 15:46:40', '2026-07-16 15:46:40'),
(61, 55, 'TONER KONICA TN328Y', 20, NULL, 1, NULL, NULL, '2026-07-23 21:35:46', '2026-07-23 21:35:46'),
(62, 56, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-07-23 21:37:00', '2026-07-23 21:37:00'),
(63, 57, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-07-24 15:00:18', '2026-07-24 15:00:18'),
(64, 58, 'Tinta CANON 16BK', 7, NULL, 1, NULL, NULL, '2026-07-24 15:05:39', '2026-07-24 15:05:39'),
(65, 59, 'Teclado Logitech MK120', 2, NULL, 1, NULL, NULL, '2026-07-24 17:20:26', '2026-07-24 17:20:26'),
(66, 60, 'Tinta BROTHER BT5001C', 9, NULL, 1, NULL, NULL, '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(67, 60, 'Tinta BROTHER BT5001M', 12, NULL, 1, NULL, NULL, '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(68, 60, 'Tinta Brother BT5001Y', 3, NULL, 1, NULL, NULL, '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(69, 60, 'Tinta Brother BTD60BK', 1, NULL, 1, NULL, NULL, '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(70, 61, 'Toner HP 58A Negro', 16, NULL, 1, NULL, NULL, '2026-08-17 17:40:23', '2026-08-17 17:40:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipments`
--

CREATE TABLE `equipments` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ram` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operating_system` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `location_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_user_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_area_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_at` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipments`
--

INSERT INTO `equipments` (`id`, `category`, `name`, `brand`, `model`, `serial_number`, `asset_code`, `processor`, `ram`, `storage`, `operating_system`, `status`, `location_id`, `assigned_user_id`, `assigned_area_id`, `assigned_area`, `assigned_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'impresora', 'Impresora CANON MAXIFY GX7110', 'CANON', 'MAXIFY GX7110', '916880C00492AA21KRWK02151', 'N/A', NULL, NULL, NULL, NULL, 'assigned', 2, NULL, 32, 'Almacen', '2026-03-23', 'Acta de entrega de equipo a jhkhfkjggkg - Almacen', '2026-03-12 17:21:12', '2026-03-23 22:07:03'),
(3, 'impresora', 'Impresora EPSON FX-890 IIN', 'EPSON', 'FX-890 IIN', 'X3YJ015218', NULL, NULL, NULL, NULL, NULL, 'assigned', 2, NULL, 35, 'Depto Tesoreria', '2026-04-07', 'Acta de entrega de equipo a Fatima Brendaly Tamayo - Depto Tesoreria', '2026-04-07 14:53:40', '2026-04-07 14:54:56'),
(4, 'impresora', 'Impresora EPSON FX-2190', 'EPSON', 'FX-2190', 'FCTY181930', '0821-0307000200-02-03-03', NULL, NULL, NULL, NULL, 'retired', NULL, NULL, NULL, NULL, NULL, 'Dañada', '2026-04-07 16:14:05', '2026-04-07 16:15:58'),
(5, 'impresora', 'Impresora EPSON LQ-2090', 'EPSON', 'LQ-2090', 'FSZY042459', '04-02-03-01', NULL, NULL, NULL, NULL, 'retired', NULL, NULL, NULL, NULL, NULL, 'Dañada no imprime', '2026-04-07 16:18:35', '2026-04-07 16:19:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipment_categories`
--

CREATE TABLE `equipment_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipment_categories`
--

INSERT INTO `equipment_categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PC', 'pc', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08'),
(2, 'Laptop', 'laptop', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08'),
(3, 'Impresora', 'impresora', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08'),
(4, 'Proyector', 'proyector', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08'),
(5, 'Monitor', 'monitor', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08'),
(6, 'Otro', 'otro', 1, '2026-03-12 17:32:08', '2026-03-12 17:32:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipment_movements`
--

CREATE TABLE `equipment_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `from_location_id` bigint UNSIGNED DEFAULT NULL,
  `to_location_id` bigint UNSIGNED DEFAULT NULL,
  `from_area_id` bigint UNSIGNED DEFAULT NULL,
  `to_area_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_user_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_user_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_area_name` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `performed_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipment_movements`
--

INSERT INTO `equipment_movements` (`id`, `equipment_id`, `type`, `performed_by`, `from_location_id`, `to_location_id`, `from_area_id`, `to_area_id`, `assigned_user_id`, `assigned_user_name`, `assigned_area_name`, `notes`, `performed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'retirement', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dañada', '2026-04-07 10:14:00', '2026-04-07 16:15:58', '2026-04-07 16:15:58'),
(2, 5, 'retirement', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dañada no imprime', '2026-04-07 10:18:00', '2026-04-07 16:19:19', '2026-04-07 16:19:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `delivery_record_id` bigint UNSIGNED DEFAULT NULL,
  `from_location_id` bigint UNSIGNED DEFAULT NULL,
  `to_location_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('entry','exit','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `item_id`, `delivery_record_id`, `from_location_id`, `to_location_id`, `type`, `quantity`, `notes`, `performed_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 1, 'entry', 20, 'Ingreso inicial', '2026-03-11 01:47:39', '2026-03-11 01:47:39', '2026-03-11 01:47:39'),
(3, 2, NULL, NULL, 1, 'entry', 41, 'Compra de oficina', '2026-03-11 01:47:39', '2026-03-11 01:47:39', '2026-03-11 01:47:39'),
(5, 3, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:01:00', '2026-03-11 02:02:42', '2026-03-11 02:02:42'),
(6, 2, NULL, 1, NULL, 'exit', 1, 'Catastro', '2026-03-11 02:04:00', '2026-03-11 02:07:58', '2026-03-11 02:07:58'),
(7, 4, NULL, NULL, 1, 'entry', 60, NULL, '2026-03-11 02:09:00', '2026-03-11 02:12:57', '2026-03-11 02:12:57'),
(8, 5, NULL, NULL, 1, 'entry', 60, NULL, '2026-03-11 02:13:00', '2026-03-11 02:13:37', '2026-03-11 02:13:37'),
(9, 6, NULL, NULL, 1, 'entry', 25, NULL, '2026-03-11 02:16:00', '2026-03-11 02:16:28', '2026-03-11 02:16:28'),
(10, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:17:00', '2026-03-11 02:19:57', '2026-03-11 02:19:57'),
(11, 8, NULL, NULL, 1, 'entry', 60, NULL, '2026-03-11 02:22:00', '2026-03-11 02:23:13', '2026-03-11 02:23:13'),
(12, 9, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:28:00', '2026-03-11 02:28:40', '2026-03-11 02:28:40'),
(13, 9, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:28:00', '2026-03-11 02:29:13', '2026-03-11 02:29:13'),
(14, 6, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:29:00', '2026-03-11 02:30:28', '2026-03-11 02:30:28'),
(15, 10, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:31:00', '2026-03-11 02:31:32', '2026-03-11 02:31:32'),
(16, 6, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:31:00', '2026-03-11 02:34:21', '2026-03-11 02:34:21'),
(17, 11, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:35:00', '2026-03-11 02:35:23', '2026-03-11 02:35:23'),
(18, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:35:00', '2026-03-11 02:36:01', '2026-03-11 02:36:01'),
(19, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:36:00', '2026-03-11 02:36:38', '2026-03-11 02:36:38'),
(20, 10, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:36:00', '2026-03-11 02:37:28', '2026-03-11 02:37:28'),
(21, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:37:00', '2026-03-11 02:38:00', '2026-03-11 02:38:00'),
(22, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:38:00', '2026-03-11 02:38:29', '2026-03-11 02:38:29'),
(23, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:38:00', '2026-03-11 02:39:00', '2026-03-11 02:39:00'),
(24, 1, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:41:00', '2026-03-11 02:42:36', '2026-03-11 02:42:36'),
(25, 1, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:42:00', '2026-03-11 02:43:07', '2026-03-11 02:43:07'),
(26, 1, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:43:00', '2026-03-11 02:43:42', '2026-03-11 02:43:42'),
(27, 11, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:43:00', '2026-03-11 02:45:40', '2026-03-11 02:45:40'),
(28, 11, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:45:00', '2026-03-11 02:46:00', '2026-03-11 02:46:00'),
(29, 10, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:51:00', '2026-03-11 02:52:16', '2026-03-11 02:52:16'),
(30, 11, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:52:00', '2026-03-11 02:53:49', '2026-03-11 02:53:49'),
(31, 10, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:53:00', '2026-03-11 02:54:26', '2026-03-11 02:54:26'),
(32, 10, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 02:54:00', '2026-03-11 02:55:00', '2026-03-11 02:55:00'),
(33, 12, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:57:00', '2026-03-11 02:57:43', '2026-03-11 02:57:43'),
(34, 3, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:58:00', '2026-03-11 02:58:59', '2026-03-11 02:58:59'),
(35, 12, NULL, NULL, 1, 'entry', 20, NULL, '2026-03-11 02:59:00', '2026-03-11 02:59:33', '2026-03-11 02:59:33'),
(36, 7, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 03:04:00', '2026-03-11 03:05:06', '2026-03-11 03:05:06'),
(37, 13, NULL, NULL, 1, 'entry', 6, NULL, '2026-03-11 03:12:00', '2026-03-11 03:13:08', '2026-03-11 03:13:08'),
(38, 2, NULL, NULL, 1, 'entry', 28, NULL, '2026-03-11 03:17:00', '2026-03-11 03:18:46', '2026-03-11 03:18:46'),
(39, 6, NULL, NULL, 1, 'entry', 24, NULL, '2026-03-11 03:23:00', '2026-03-11 03:23:46', '2026-03-11 03:23:46'),
(40, 14, NULL, NULL, 1, 'entry', 14, 'Para HP M254 y M280', '2026-03-10 21:46:00', '2026-03-10 21:46:56', '2026-03-10 21:46:56'),
(41, 15, NULL, NULL, 1, 'entry', 1, NULL, '2026-03-10 21:50:00', '2026-03-10 21:50:16', '2026-03-10 21:50:16'),
(42, 16, NULL, NULL, 1, 'entry', 7, NULL, '2026-03-10 21:51:00', '2026-03-10 21:51:38', '2026-03-10 21:51:38'),
(44, 18, NULL, NULL, 1, 'entry', 36, 'PARA EPSON FX-2190 Y EPSON LQ-2090', '2026-03-10 22:00:00', '2026-03-10 22:00:55', '2026-03-10 22:00:55'),
(45, 19, NULL, NULL, 1, 'entry', 7, 'PARA LX-350/300+II/LX-300+', '2026-03-10 22:02:00', '2026-03-10 22:04:06', '2026-03-10 22:04:06'),
(46, 20, NULL, NULL, 1, 'entry', 9, NULL, '2026-03-10 22:13:00', '2026-03-10 22:13:16', '2026-03-10 22:13:16'),
(47, 21, NULL, NULL, 1, 'entry', 7, NULL, '2026-03-10 22:13:00', '2026-03-10 22:13:57', '2026-03-10 22:13:57'),
(48, 22, NULL, NULL, 1, 'entry', 9, NULL, '2026-03-10 22:14:00', '2026-03-10 22:15:35', '2026-03-10 22:15:35'),
(50, 20, NULL, NULL, 1, 'entry', 1, NULL, '2026-03-10 22:17:00', '2026-03-10 22:17:23', '2026-03-10 22:17:23'),
(51, 21, NULL, NULL, 1, 'entry', 1, NULL, '2026-03-10 22:18:00', '2026-03-10 22:18:35', '2026-03-10 22:18:35'),
(52, 24, NULL, NULL, 1, 'entry', 3, NULL, '2026-03-11 13:50:00', '2026-03-11 13:51:10', '2026-03-11 13:51:10'),
(53, 23, NULL, NULL, 1, 'entry', 4, NULL, '2026-03-11 13:51:00', '2026-03-11 13:52:00', '2026-03-11 13:52:00'),
(54, 25, NULL, NULL, 1, 'entry', 4, NULL, '2026-03-11 13:52:00', '2026-03-11 13:52:38', '2026-03-11 13:52:38'),
(55, 26, NULL, NULL, 1, 'entry', 7, NULL, '2026-03-11 13:52:00', '2026-03-11 13:53:28', '2026-03-11 13:53:28'),
(56, 27, NULL, NULL, 1, 'entry', 10, NULL, '2026-03-11 14:17:00', '2026-03-11 14:18:15', '2026-03-11 14:18:15'),
(57, 28, NULL, NULL, 1, 'entry', 23, 'MFP M426', '2026-03-11 14:35:00', '2026-03-11 14:36:43', '2026-03-11 14:36:43'),
(58, 29, NULL, NULL, 2, 'entry', 4, NULL, '2026-03-11 14:37:00', '2026-03-11 14:37:40', '2026-03-11 14:37:40'),
(59, 30, NULL, NULL, 1, 'entry', 3, NULL, '2026-03-11 14:38:00', '2026-03-11 14:38:42', '2026-03-11 14:38:42'),
(60, 31, NULL, NULL, 1, 'entry', 1, NULL, '2026-03-11 14:39:00', '2026-03-11 14:39:33', '2026-03-11 14:39:33'),
(61, 32, NULL, NULL, 1, 'entry', 2, NULL, '2026-03-11 14:39:00', '2026-03-11 14:40:06', '2026-03-11 14:40:06'),
(62, 2, NULL, NULL, 2, 'entry', 5, NULL, '2026-03-11 18:44:00', '2026-03-11 18:45:28', '2026-03-11 18:45:28'),
(83, 26, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a CRISTIAN MAURICIO SANTO MOLINA - DEPARTAMENTO DE TURISMO Y CULTURA', '2026-03-20 17:13:05', '2026-03-20 17:13:05', '2026-03-20 17:13:05'),
(84, 25, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a CRISTIAN MAURICIO SANTO MOLINA - DEPARTAMENTO DE TURISMO Y CULTURA', '2026-03-20 17:13:05', '2026-03-20 17:13:05', '2026-03-20 17:13:05'),
(85, 23, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a CRISTIAN MAURICIO SANTO MOLINA - DEPARTAMENTO DE TURISMO Y CULTURA', '2026-03-20 17:13:05', '2026-03-20 17:13:05', '2026-03-20 17:13:05'),
(86, 35, NULL, NULL, 1, 'entry', 7, NULL, '2026-03-23 14:28:00', '2026-03-23 14:28:43', '2026-03-23 14:28:43'),
(87, 20, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a Sofia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-03-23 14:30:50', '2026-03-23 14:30:50', '2026-03-23 14:30:50'),
(88, 2, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Rene Vasquez - Secretaria de Innovacion', '2026-03-23 21:55:22', '2026-03-23 21:55:22', '2026-03-23 21:55:22'),
(89, 2, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Emilio Menchu - Secretaria de Innovacion', '2026-03-23 22:01:24', '2026-03-23 22:01:24', '2026-03-23 22:01:24'),
(90, 36, NULL, NULL, 2, 'entry', 7, NULL, '2026-03-27 17:13:00', '2026-03-27 17:14:01', '2026-03-27 17:14:01'),
(91, 36, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Juan Jose Calero - Gerencia Financiera', '2026-03-27 17:19:15', '2026-03-27 17:19:15', '2026-03-27 17:19:15'),
(92, 29, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Cesar Barahona - Registro Familiar', '2026-04-07 15:06:26', '2026-04-07 15:06:26', '2026-04-07 15:06:26'),
(93, 2, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Sofia Dalia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-04-14 13:44:51', '2026-04-15 13:44:51', '2026-04-15 13:44:51'),
(94, 37, NULL, NULL, 2, 'entry', 8, NULL, '2026-04-16 19:06:00', '2026-04-16 19:07:26', '2026-04-16 19:07:26'),
(95, 37, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Carlos Edilberto Cañas Aguirre - Depto Activo Fijo', '2026-04-16 19:08:19', '2026-04-16 19:08:19', '2026-04-16 19:08:19'),
(96, 2, NULL, 2, NULL, 'exit', 1, 'Acta de entrega a Yhoalmo Rodrigez - Depto Tesoreria', '2026-04-20 16:19:03', '2026-04-20 16:19:03', '2026-04-20 16:19:03'),
(98, 32, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a Guillermo Antonio Villalta Ramos - Secretario Municipal', '2026-04-22 20:08:59', '2026-04-22 20:08:59', '2026-04-22 20:08:59'),
(99, 21, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a Wilver Gerardo Parada Garcia - Gerente Tributario', '2026-04-22 20:20:26', '2026-04-22 20:20:26', '2026-04-22 20:20:26'),
(100, 35, NULL, 1, NULL, 'exit', 1, 'Acta de entrega a Wilver Gerardo Parada Garcia - Gerente Tributario', '2026-04-23 19:45:12', '2026-04-23 19:45:12', '2026-04-23 19:45:12'),
(101, 21, 14, 1, NULL, 'exit', 1, 'Acta de entrega a Sarahi Alejandra Cortez Alfaro - Gerencia General', '2026-04-28 21:57:06', '2026-04-28 21:57:06', '2026-04-28 21:57:06'),
(102, 29, 15, 2, NULL, 'exit', 1, 'Acta de entrega a Luisa Jerardina Minero Moscote - Registro Familiar', '2026-05-04 16:06:50', '2026-05-04 16:06:50', '2026-05-04 16:06:50'),
(103, 35, 16, 1, NULL, 'exit', 1, 'Acta de entrega a Sofia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-05 14:36:41', '2026-05-05 14:36:41', '2026-05-05 14:36:41'),
(104, 21, 17, 1, NULL, 'exit', 1, 'Acta de entrega a Sofia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-07 21:54:51', '2026-05-07 21:54:51', '2026-05-07 21:54:51'),
(105, 35, 18, 1, NULL, 'exit', 1, 'Acta de entrega a Sofia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-12 15:18:11', '2026-05-12 15:18:11', '2026-05-12 15:18:11'),
(106, 22, 19, 1, NULL, 'exit', 1, 'Acta de entrega a Sofia Villacorta - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-08 15:19:02', '2026-05-12 15:19:02', '2026-05-12 15:19:02'),
(107, 24, 20, 1, NULL, 'exit', 1, 'Acta de entrega a Cristian Mauricio Santos Molina - Turismo y Cultura', '2026-05-13 17:44:40', '2026-05-13 17:44:40', '2026-05-13 17:44:40'),
(108, 28, 21, 1, NULL, 'exit', 1, 'Acta de entrega a Fatima Brendaly Tamayo - Depto Tesoreria', '2026-05-14 15:17:53', '2026-05-14 15:17:53', '2026-05-14 15:17:53'),
(109, 28, 22, 1, NULL, 'exit', 1, 'Acta de entrega a Fatima Brendaly Tamayo - Depto Tesoreria', '2026-05-14 15:21:28', '2026-05-14 15:21:28', '2026-05-14 15:21:28'),
(110, 7, NULL, 1, 2, 'transfer', 24, NULL, '2026-05-15 19:48:00', '2026-05-15 19:48:51', '2026-05-15 19:48:51'),
(111, 7, 23, 2, NULL, 'exit', 1, 'Acta de entrega a Guillermo Antonio Villalta Ramos - Secretario Municipal', '2026-05-15 19:49:33', '2026-05-15 19:49:33', '2026-05-15 19:49:33'),
(112, 7, 24, 2, NULL, 'exit', 2, 'Acta de entrega a Carla Nere Ortiz Hernandez - Mercados', '2026-05-18 14:40:34', '2026-05-18 14:40:34', '2026-05-18 14:40:34'),
(113, 7, 25, 2, NULL, 'exit', 2, 'Acta de entrega a Mauricio Alberto Yanez - Mercados', '2026-05-18 14:45:26', '2026-05-18 14:45:26', '2026-05-18 14:45:26'),
(114, 7, NULL, 2, NULL, 'exit', 1, NULL, '2026-05-18 16:52:00', '2026-05-18 16:53:10', '2026-05-18 16:53:10'),
(115, 35, 26, 1, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-21 17:46:49', '2026-05-21 17:46:49', '2026-05-21 17:46:49'),
(116, 22, 27, 1, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-05-25 14:00:42', '2026-05-25 14:00:42', '2026-05-25 14:00:42'),
(117, 21, 28, 1, NULL, 'exit', 1, 'Acta de entrega a Kenia Sarai Miranda - Despacho Municipal', '2026-05-28 16:05:43', '2026-05-28 16:05:43', '2026-05-28 16:05:43'),
(118, 28, 29, 1, NULL, 'exit', 1, 'Acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-06-01 19:57:50', '2026-06-01 19:57:50', '2026-06-01 19:57:50'),
(119, 20, 30, 1, NULL, 'exit', 1, 'Acta de entrega a Fatima Dolores Rivera de Torres - Unidad de Compras Publicas', '2026-06-02 14:58:35', '2026-06-02 14:58:35', '2026-06-02 14:58:35'),
(120, 9, NULL, 1, 2, 'transfer', 20, NULL, '2026-06-02 15:14:00', '2026-06-02 15:15:39', '2026-06-02 15:15:39'),
(121, 9, 31, 2, NULL, 'exit', 1, 'Acta de entrega a Raul Alberto Lovato Osorio - Deportes', '2026-06-02 15:16:59', '2026-06-02 15:16:59', '2026-06-02 15:16:59'),
(122, 1, NULL, NULL, 2, 'entry', 11, NULL, '2026-06-16 15:09:00', '2026-06-16 15:10:40', '2026-06-16 15:10:40'),
(123, 1, NULL, NULL, 2, 'entry', 15, NULL, '2026-06-16 15:10:00', '2026-06-16 15:11:17', '2026-06-16 15:11:17'),
(124, 12, NULL, NULL, 2, 'entry', 10, NULL, '2026-06-16 15:11:00', '2026-06-16 15:12:37', '2026-06-16 15:12:37'),
(125, 1, NULL, NULL, 2, 'entry', 4, NULL, '2026-06-16 15:12:00', '2026-06-16 15:13:17', '2026-06-16 15:13:17'),
(126, 1, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-16 15:13:00', '2026-06-16 15:14:35', '2026-06-16 15:14:35'),
(127, 12, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 15:14:00', '2026-06-16 15:15:02', '2026-06-16 15:15:02'),
(128, 1, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 15:15:00', '2026-06-16 15:36:22', '2026-06-16 15:36:22'),
(129, 3, NULL, NULL, 2, 'entry', 10, NULL, '2026-06-16 15:36:00', '2026-06-16 15:37:31', '2026-06-16 15:37:31'),
(130, 8, NULL, NULL, 2, 'entry', 50, NULL, '2026-06-16 15:37:00', '2026-06-16 15:48:25', '2026-06-16 15:48:25'),
(131, 38, NULL, NULL, 2, 'entry', 49, NULL, '2026-06-16 15:52:00', '2026-06-16 15:53:24', '2026-06-16 15:53:24'),
(132, 4, NULL, NULL, 2, 'entry', 30, NULL, '2026-06-16 15:58:00', '2026-06-16 15:59:21', '2026-06-16 15:59:21'),
(133, 5, NULL, NULL, 2, 'entry', 45, NULL, '2026-06-16 15:59:00', '2026-06-16 16:01:31', '2026-06-16 16:01:31'),
(134, 35, NULL, 1, NULL, 'exit', 1, NULL, '2026-06-16 16:01:00', '2026-06-16 16:04:16', '2026-06-16 16:04:16'),
(135, 35, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 16:04:00', '2026-06-16 16:04:34', '2026-06-16 16:04:34'),
(136, 39, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-16 16:05:00', '2026-06-16 16:05:43', '2026-06-16 16:05:43'),
(137, 40, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-16 16:06:00', '2026-06-16 16:06:41', '2026-06-16 16:06:41'),
(138, 41, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 16:07:00', '2026-06-16 16:07:17', '2026-06-16 16:07:17'),
(139, 42, NULL, NULL, 2, 'entry', 5, NULL, '2026-06-16 16:09:00', '2026-06-16 16:09:39', '2026-06-16 16:09:39'),
(140, 11, NULL, NULL, 2, 'entry', 38, NULL, '2026-06-16 16:16:00', '2026-06-16 16:19:23', '2026-06-16 16:19:23'),
(141, 6, NULL, NULL, 2, 'entry', 18, NULL, '2026-06-16 16:19:00', '2026-06-16 16:22:12', '2026-06-16 16:22:12'),
(142, 6, NULL, NULL, 2, 'entry', 3, NULL, '2026-06-16 16:22:00', '2026-06-16 16:23:18', '2026-06-16 16:23:18'),
(143, 6, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-16 16:23:00', '2026-06-16 16:29:33', '2026-06-16 16:29:33'),
(144, 11, NULL, NULL, 2, 'entry', 7, NULL, '2026-06-16 16:29:00', '2026-06-16 16:32:20', '2026-06-16 16:32:20'),
(145, 6, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 16:32:00', '2026-06-16 16:33:35', '2026-06-16 16:33:35'),
(146, 11, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 16:33:00', '2026-06-16 16:33:49', '2026-06-16 16:33:49'),
(147, 10, NULL, NULL, 2, 'entry', 11, NULL, '2026-06-16 16:33:00', '2026-06-16 16:38:14', '2026-06-16 16:38:14'),
(148, 10, NULL, NULL, 2, 'entry', 1, NULL, '2026-06-16 16:38:00', '2026-06-16 16:40:02', '2026-06-16 16:40:02'),
(149, 43, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-18 17:17:00', '2026-06-18 17:19:47', '2026-06-18 17:19:47'),
(150, 44, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-18 17:19:00', '2026-06-18 17:19:56', '2026-06-18 17:19:56'),
(151, 45, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-18 17:19:00', '2026-06-18 17:20:09', '2026-06-18 17:20:09'),
(152, 46, NULL, NULL, 2, 'entry', 2, NULL, '2026-06-18 17:20:00', '2026-06-18 17:20:21', '2026-06-18 17:20:21'),
(153, 7, 32, 2, NULL, 'exit', 1, 'Acta de entrega a Nancy Carolina Cortez Molina - Depto Mujer', '2026-06-18 20:50:08', '2026-06-18 20:50:08', '2026-06-18 20:50:08'),
(154, 47, NULL, NULL, 2, 'entry', 516, NULL, '2026-06-19 14:50:00', '2026-06-19 14:52:14', '2026-06-19 14:52:14'),
(155, 47, 33, 2, NULL, 'exit', 16, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-06-19 14:52:51', '2026-06-19 14:52:51', '2026-06-19 14:52:51'),
(156, 36, 34, 2, NULL, 'exit', 1, 'Acta de entrega a Yessy Beatriz Valle García - Sub-Gerente Legal', '2026-06-22 14:57:11', '2026-06-22 14:57:11', '2026-06-22 14:57:11'),
(157, 36, 35, 2, NULL, 'exit', 1, 'Acta de entrega a Yessy Beatriz Valle Garcia - Sub-Gerente Legal', '2026-06-22 14:58:05', '2026-06-22 14:58:05', '2026-06-22 14:58:05'),
(159, 36, 34, NULL, 2, 'entry', 1, 'Anulacion de acta de entrega a Yessy Beatriz Valle García - Sub-Gerente Legal', '2026-06-22 15:20:05', '2026-06-22 15:20:05', '2026-06-22 15:20:05'),
(160, 2, 36, 2, NULL, 'exit', 1, 'Acta de entrega a Nohemi Mejia - Sub-Gerente Legal', '2026-06-22 16:39:13', '2026-06-22 16:39:13', '2026-06-22 16:39:13'),
(161, 7, NULL, 2, NULL, 'exit', 1, 'Para complementar REF', '2026-06-23 14:21:00', '2026-06-23 14:22:24', '2026-06-23 14:22:24'),
(162, 7, 37, 2, NULL, 'exit', 1, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-06-26 21:10:27', '2026-06-26 21:10:27', '2026-06-26 21:10:27'),
(163, 7, 38, 2, NULL, 'exit', 1, 'Acta de entrega a Guillermo Antonio Villalta Ramos - Secretario Municipal', '2026-06-29 20:48:47', '2026-06-29 20:48:47', '2026-06-29 20:48:47'),
(164, 35, 39, 1, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-06-29 21:49:12', '2026-06-29 21:49:12', '2026-06-29 21:49:12'),
(165, 7, 40, 2, NULL, 'exit', 1, 'Acta de entrega a Pamela Cecilia Diaz - Contravencional', '2026-06-30 15:39:07', '2026-06-30 15:39:07', '2026-06-30 15:39:07'),
(166, 2, 41, 1, NULL, 'exit', 1, 'Acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-06-30 20:27:52', '2026-06-30 20:27:52', '2026-06-30 20:27:52'),
(167, 12, NULL, 2, NULL, 'exit', 1, 'COEN', '2026-07-01 14:40:00', '2026-07-01 14:41:15', '2026-07-01 14:41:15'),
(168, 9, NULL, 2, NULL, 'exit', 1, 'COEN', '2026-07-01 14:41:00', '2026-07-01 14:41:44', '2026-07-01 14:41:44'),
(169, 36, 42, 2, NULL, 'exit', 1, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-07-01 21:12:04', '2026-07-01 21:12:04', '2026-07-01 21:12:04'),
(170, 35, 43, 1, NULL, 'exit', 1, 'Acta de entrega a Sarahi Alejandra Cortez Alfaro - Gerencia General', '2026-07-07 14:38:59', '2026-07-07 14:38:59', '2026-07-07 14:38:59'),
(171, 16, 44, 1, NULL, 'exit', 1, 'Acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-07-09 14:16:51', '2026-07-09 14:16:51', '2026-07-09 14:16:51'),
(172, 16, 44, NULL, 1, 'entry', 1, 'Anulacion de acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-07-09 14:17:14', '2026-07-09 14:17:14', '2026-07-09 14:17:14'),
(173, 16, 45, 1, NULL, 'exit', 1, 'Acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-07-09 14:17:42', '2026-07-09 14:17:42', '2026-07-09 14:17:42'),
(174, 2, NULL, 1, NULL, 'exit', 1, 'Acta de entrega - REF - 27/05/2026', '2026-07-13 14:31:00', '2026-07-13 14:32:39', '2026-07-13 14:32:39'),
(175, 23, 46, 1, NULL, 'exit', 1, 'Acta de entrega a Cristian Mauricio Santos Molina - Turismo y Cultura', '2026-07-09 14:34:32', '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(176, 25, 46, 1, NULL, 'exit', 1, 'Acta de entrega a Cristian Mauricio Santos Molina - Turismo y Cultura', '2026-07-09 14:34:32', '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(177, 26, 46, 1, NULL, 'exit', 1, 'Acta de entrega a Cristian Mauricio Santos Molina - Turismo y Cultura', '2026-07-09 14:34:32', '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(178, 24, 46, 1, NULL, 'exit', 1, 'Acta de entrega a Cristian Mauricio Santos Molina - Turismo y Cultura', '2026-07-09 14:34:32', '2026-07-13 14:34:32', '2026-07-13 14:34:32'),
(179, 9, 47, 2, NULL, 'exit', 1, 'Acta de entrega a Evelin Margarita Flores - Integral de Riesgos', '2026-07-03 14:39:02', '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(180, 12, 47, 2, NULL, 'exit', 1, 'Acta de entrega a Evelin Margarita Flores - Integral de Riesgos', '2026-07-03 14:39:02', '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(181, 3, 47, 2, NULL, 'exit', 1, 'Acta de entrega a Evelin Margarita Flores - Integral de Riesgos', '2026-07-03 14:39:02', '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(182, 1, 47, 2, NULL, 'exit', 1, 'Acta de entrega a Evelin Margarita Flores - Integral de Riesgos', '2026-07-03 14:39:02', '2026-07-13 14:39:02', '2026-07-13 14:39:02'),
(183, 4, 48, 2, NULL, 'exit', 1, 'Acta de entrega a Marco Antonio Somoza Salomon - Limpieza Publica e Institucional', '2026-07-13 14:40:52', '2026-07-13 14:40:52', '2026-07-13 14:40:52'),
(184, 7, 49, 2, NULL, 'exit', 1, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-07-13 14:47:30', '2026-07-13 14:47:30', '2026-07-13 14:47:30'),
(185, 7, 50, 2, NULL, 'exit', 1, 'Acta de entrega a Juan Jose Calero Cruz - Gerencia Financiera', '2026-06-15 14:51:00', '2026-07-13 14:51:00', '2026-07-13 14:51:00'),
(186, 21, 51, 1, NULL, 'exit', 1, 'Acta de entrega a Fatima Dolores Rivera de Torres - Unidad de Compras Publicas', '2026-05-06 15:18:44', '2026-07-13 15:18:44', '2026-07-13 15:18:44'),
(187, 35, 52, 2, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-07-15 20:30:47', '2026-07-15 20:30:47', '2026-07-15 20:30:47'),
(188, 22, 53, 1, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-07-16 14:37:28', '2026-07-16 14:37:28', '2026-07-16 14:37:28'),
(189, 47, 54, 2, NULL, 'exit', 13, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-07-16 15:46:40', '2026-07-16 15:46:40', '2026-07-16 15:46:40'),
(190, 20, 55, 1, NULL, 'exit', 1, 'Acta de entrega a Adela Fernanda Sibrian Bermudez - Secretaria de Comunicaciones Y Relaciones Publicas', '2026-07-23 21:35:46', '2026-07-23 21:35:46', '2026-07-23 21:35:46'),
(191, 7, 56, 2, NULL, 'exit', 1, 'Acta de entrega a Nerys Azucena Beltran - Directora Talento Humano', '2026-07-23 21:37:00', '2026-07-23 21:37:00', '2026-07-23 21:37:00'),
(192, 7, 57, 2, NULL, 'exit', 1, 'Acta de entrega a Ana Guadalupe Menendez de Muñoz - Sub-Gerente Legal', '2026-07-24 15:00:18', '2026-07-24 15:00:18', '2026-07-24 15:00:18'),
(193, 7, 57, NULL, 2, 'entry', 1, 'Anulacion de acta de entrega a Ana Guadalupe Menendez de Muñoz - Sub-Gerente Legal', '2026-07-24 15:04:52', '2026-07-24 15:04:52', '2026-07-24 15:04:52'),
(194, 7, 58, 2, NULL, 'exit', 1, 'Acta de entrega a . - Sub-Gerente Legal', '2026-07-24 15:05:39', '2026-07-24 15:05:39', '2026-07-24 15:05:39'),
(195, 2, 59, 1, NULL, 'exit', 1, 'Acta de entrega a David Salvador Mancia - Depto Presupuesto', '2026-07-24 17:20:26', '2026-07-24 17:20:26', '2026-07-24 17:20:26'),
(196, 9, 60, 2, NULL, 'exit', 1, 'Acta de entrega a Oscar Enrique Guzman - Renovadores', '2026-08-12 14:45:03', '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(197, 12, 60, 2, NULL, 'exit', 1, 'Acta de entrega a Oscar Enrique Guzman - Renovadores', '2026-08-12 14:45:03', '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(198, 3, 60, 2, NULL, 'exit', 1, 'Acta de entrega a Oscar Enrique Guzman - Renovadores', '2026-08-12 14:45:03', '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(199, 1, 60, 2, NULL, 'exit', 1, 'Acta de entrega a Oscar Enrique Guzman - Renovadores', '2026-08-12 14:45:03', '2026-08-12 14:45:03', '2026-08-12 14:45:03'),
(200, 16, 61, 1, NULL, 'exit', 1, 'Acta de entrega a Walter Alexander Torres Tobar - Gerencia de Obras Municipales y Proyectos', '2026-08-17 17:40:23', '2026-08-17 17:40:23', '2026-08-17 17:40:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unidad',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `name`, `brand`, `model`, `unit`, `created_at`, `updated_at`) VALUES
(1, 'Tinta', 'Brother', 'BTD60BK', 'unidad', '2026-03-11 01:47:39', '2026-03-11 01:47:39'),
(2, 'Teclado', 'Logitech', 'MK120', 'unidad', '2026-03-11 01:47:39', '2026-03-11 01:47:39'),
(3, 'Tinta', 'Brother', 'BT5001Y', 'unidad', '2026-03-11 02:01:48', '2026-03-11 02:01:48'),
(4, 'Tinta', 'EPSON', '544BK', 'unidad', '2026-03-11 02:09:26', '2026-03-11 02:09:26'),
(5, 'Tinta', 'EPSON', '544C', 'unidad', '2026-03-11 02:13:25', '2026-03-11 02:13:25'),
(6, 'Tinta', 'CANON', '16M', 'unidad', '2026-03-11 02:16:17', '2026-03-11 02:16:17'),
(7, 'Tinta', 'CANON', '16BK', 'unidad', '2026-03-11 02:17:57', '2026-03-11 02:17:57'),
(8, 'Tinta', 'EPSON', '544M', 'unidad', '2026-03-11 02:22:54', '2026-03-11 02:22:54'),
(9, 'Tinta', 'BROTHER', 'BT5001C', 'unidad', '2026-03-11 02:28:14', '2026-03-11 02:28:14'),
(10, 'Tinta', 'CANON', '16C', 'unidad', '2026-03-11 02:31:18', '2026-03-11 02:31:18'),
(11, 'Tinta', 'CANON', '16Y', 'unidad', '2026-03-11 02:35:12', '2026-03-11 02:35:12'),
(12, 'Tinta', 'BROTHER', 'BT5001M', 'unidad', '2026-03-11 02:57:19', '2026-03-11 02:57:19'),
(13, 'Toner', 'KYOCERA', 'TK-3102', 'unidad', '2026-03-11 03:12:39', '2026-03-11 03:12:39'),
(14, 'TONER', 'HP', '202A NEGRO', 'unidad', '2026-03-10 21:45:10', '2026-03-10 21:45:10'),
(15, 'Toner', 'HP', '30X NEGRO', 'unidad', '2026-03-10 21:49:50', '2026-03-10 21:49:50'),
(16, 'Toner', 'HP', '58A Negro', 'unidad', '2026-03-10 21:50:58', '2026-03-10 21:50:58'),
(18, 'CINTA MATRICIAL', 'EPSON', 'SO15335', 'unidad', '2026-03-10 22:00:17', '2026-03-10 22:00:17'),
(19, 'CINTA MATRICIAL', 'EPSON', 'SO15631', 'unidad', '2026-03-10 22:02:26', '2026-03-10 22:02:26'),
(20, 'TONER', 'KONICA', 'TN328Y', 'unidad', '2026-03-10 22:13:04', '2026-03-10 22:13:04'),
(21, 'TONER', 'KONICA', 'TN328K', 'unidad', '2026-03-10 22:13:47', '2026-03-10 22:13:47'),
(22, 'TONER', 'KONICA', 'TN328M', 'unidad', '2026-03-10 22:14:45', '2026-03-10 22:14:45'),
(23, 'TONER', 'HP', '410A YELLOW', 'unidad', '2026-03-11 13:46:44', '2026-03-11 13:46:44'),
(24, 'TONER', 'HP', '410A CYAN', 'unidad', '2026-03-11 13:49:12', '2026-03-11 13:49:12'),
(25, 'TONER', 'HP', '410A MAGENTA', 'unidad', '2026-03-11 13:49:39', '2026-03-11 13:49:39'),
(26, 'TONER', 'HP', '410A BLACK', 'unidad', '2026-03-11 13:49:59', '2026-03-11 13:49:59'),
(27, 'TONER', 'HP', '30A BLACK', 'unidad', '2026-03-11 14:14:31', '2026-03-11 14:14:31'),
(28, 'TONER', 'HP', '26X NEGRO', 'unidad', '2026-03-11 14:35:54', '2026-03-11 14:35:54'),
(29, 'TONER', 'HP', '136A', 'unidad', '2026-03-11 14:37:25', '2026-03-11 14:37:25'),
(30, 'TONER', 'HP', '202A MAGENTA', 'unidad', '2026-03-11 14:38:26', '2026-03-11 14:38:26'),
(31, 'TONER', 'HP', '202A CYAN', 'unidad', '2026-03-11 14:39:21', '2026-03-11 14:39:21'),
(32, 'TONER', 'HP', '202A YELLOW', 'unidad', '2026-03-11 14:39:53', '2026-03-11 14:39:53'),
(35, 'TONER', 'KONICA', 'TN328C', 'unidad', '2026-03-23 14:28:18', '2026-03-23 14:28:18'),
(36, 'CARTUCHO MANTENIMIENTO', 'CANON', 'MC-G01', 'unidad', '2026-03-27 17:13:37', '2026-03-27 17:13:37'),
(37, 'UPS', 'Orbit', 'TC-7508', 'unidad', '2026-04-16 19:06:50', '2026-04-16 19:06:50'),
(38, 'Tinta', 'EPSON', '544Y', 'unidad', '2026-06-16 15:52:51', '2026-06-16 15:54:03'),
(39, 'Toner', 'KONICA', 'TN321C', 'unidad', '2026-06-16 16:05:32', '2026-06-16 16:05:32'),
(40, 'Toner', 'KONICA', 'TN321M', 'unidad', '2026-06-16 16:06:26', '2026-06-16 16:06:26'),
(41, 'Toner', 'KONICA', 'TN321B', 'unidad', '2026-06-16 16:07:08', '2026-06-16 16:07:08'),
(42, 'Ribbon', 'DASCOM', 'YMCKO(E)', 'unidad', '2026-06-16 16:09:28', '2026-06-16 16:09:28'),
(43, 'Tinta', 'EPSON', 'T49H2 CYAN', 'unidad', '2026-06-18 17:15:01', '2026-06-18 17:15:01'),
(44, 'Tinta', 'EPSON', 'T49H3 MAGENTA', 'unidad', '2026-06-18 17:16:02', '2026-06-18 17:16:02'),
(45, 'Tinta', 'EPSON', 'T49H4 YELLOW', 'unidad', '2026-06-18 17:16:54', '2026-06-18 17:16:54'),
(46, 'Tinta', 'Epson', 'T49H1 BLACK', 'unidad', '2026-06-18 17:17:35', '2026-06-18 17:17:35'),
(47, 'Tarjeta', 'ZEBRA', 'Premier pvc 30ml', 'unidad', '2026-06-19 14:50:29', '2026-06-19 14:50:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `locations`
--

INSERT INTO `locations` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bodega', 'BODEGA', 'Almacen principal', '2026-03-10 22:40:51', '2026-03-10 22:40:51'),
(2, 'Oficina', 'OFICINA', 'Stock de atencion inmediata', '2026-03-10 22:40:51', '2026-03-10 22:40:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_10_000003_create_items_table', 1),
(5, '2026_03_10_000004_create_inventory_movements_table', 1),
(6, '2026_03_10_000005_create_locations_table', 2),
(7, '2026_03_10_000006_update_inventory_movements_for_locations', 2),
(8, '2026_03_11_000007_add_profile_fields_to_users_table', 3),
(9, '2026_03_11_000008_create_equipments_table', 4),
(10, '2026_03_12_000009_create_equipment_categories_table', 5),
(11, '2026_03_12_000010_create_municipal_areas_table', 6),
(12, '2026_03_23_000011_add_role_to_users_table', 7),
(13, '2026_03_23_000012_add_permissions_to_users_table', 8),
(14, '2026_03_23_000013_create_delivery_records_tables', 9),
(15, '2026_04_07_000014_create_equipment_movements_table', 10),
(16, '2026_04_07_000015_add_assignment_snapshot_to_equipment_movements_table', 11),
(17, '2026_04_28_000016_link_delivery_records_to_inventory_and_entities', 12),
(18, '2026_06_22_000017_add_annulment_fields_to_delivery_records_table', 13),
(19, '2026_06_22_000018_add_username_to_users_table', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipal_areas`
--

CREATE TABLE `municipal_areas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institutional_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipal_areas`
--

INSERT INTO `municipal_areas` (`id`, `name`, `slug`, `responsible_name`, `institutional_email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Alcaldesa', 'alcaldesa', 'Marcela Balbina Pineda Erazo', 'mp.alcaldesa@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(2, 'Sindica Municipal', 'sindica-municipal', 'Marcela del Carmen Valencia Lemus', 'sindicatura@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(3, 'Secretario Municipal', 'secretario-municipal', 'Guillermo Antonio Villalta Ramos', 'secretaria@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-04-22 20:03:46'),
(4, 'Gestion de Archivo', 'gestion-de-archivo', 'Digna Isabel Hernandez Vda. de Huezo', 'ugda@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(5, 'Auditor Interno', 'auditor-interno', 'Hugo Alfredo Santillana', 'auditoriainterna@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(6, 'Despacho Municipal', 'despacho-municipal', 'Kenia Sarai Miranda', 'despacho@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(7, 'Oficial de Cumplimiento', 'oficial-de-cumplimiento', 'Hemerson Stanley Serrano Lemus', 'oficial.cumplimiento@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(8, 'Direccion Distrital Zacatecoluca', 'direccion-distrital-zacatecoluca', NULL, NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(9, 'Sub-Direccion Distrital Zacatecoluca', 'sub-direccion-distrital-zacatecoluca', NULL, NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(10, 'Direccion Distrital SJN', 'direccion-distrital-sjn', 'Carlos Humberto Martinez Barahona', 'direcciondistrital.sjn@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(11, 'Sub-Direccion Distrital SJN', 'sub-direccion-distrital-sjn', 'Carmen Beatriz Lopez Martinez', 'subdireccion.sjn@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(12, 'Proyectos de Agua', 'proyectos-de-agua', 'David Antonio Iraheta Martinez', NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(13, 'Direccion Distrital San Rafael Obrajuelo', 'direccion-distrital-san-rafael-obrajuelo', 'Juan Ramon Mejia Mejia', 'direcciondistrital.sro@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(14, 'Sub-Direccion Distrital San Rafael Obrajuelo', 'sub-direccion-distrital-san-rafael-obrajuelo', 'Fernando de Jesus de la Paz Rodriguez', 'subdireccion.sro@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(15, 'Directora Talento Humano', 'directora-talento-humano', 'Nerys Azucena Beltran', 'talentohumano@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(16, 'Secretaria de Innovacion', 'secretaria-de-innovacion', 'Jerson Ernesto Zelaya Villalta', 'innovacion@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(17, 'Secretaria de Comunicaciones Y Relaciones Publicas', 'secretaria-de-comunicaciones-institucional', 'Adela Fernanda Sibrian Bermudez', 'comunicaciones@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-23 14:29:57'),
(18, 'Instituto de la Juventud', 'instituto-de-la-juventud', 'Luis Eliseo Rodriguez Palacios', 'imj@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(19, 'Comision de Proteccion Civil', 'comision-de-proteccion-civil', NULL, 'comisioncivil@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(20, 'Integral de Riesgos', 'integral-de-riesgos', 'Jose Israel Hernandez Ayala', 'depto.iiriesgos@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(21, 'Registro de la Carrera Administrativa', 'registro-de-la-carrera-administrativa', 'Elmer Alexander Alfaro Alfaro', 'lacam@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(22, 'CAM', 'cam', 'Ronan Baltazar Trinidad Melgar', 'cam@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(23, 'Acceso a la Informacion Publica', 'acceso-a-la-informacion-publica', NULL, 'aip@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(24, 'Unidad de Compras Publicas', 'unidad-de-compras-publicas', 'Fatima Dolores Rivera de Torres', 'ucp@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(25, 'Gerente Legal', 'gerente-legal', 'Jorge Enrique Rodriguez', 'gerencia.legal@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(26, 'Sub-Gerente Legal', 'sub-gerente-legal', 'Ana Guadalupe Menendez de Muñoz', 'subgerencialegal@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(27, 'Registro Familiar', 'registro-familiar', 'Luisa Jerardina Minero Moscote', 'ref@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(28, 'Contravencional', 'contravencional', 'Pamela Cecilia Diaz', 'contravencional@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(29, 'Gerencia General', 'gerencia-general', 'Sarahi Alejandra Cortez Alfaro', 'gerenciageneral.admin@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(30, 'Sub-Gerencia General', 'sub-gerencia-general', NULL, 'subgerenciageneral@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(31, 'Sub-Gerencia Administrativa', 'sub-gerencia-administrativa', 'Sonia Guadalupe Pineda', 'subgerenciaadm@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(32, 'Almacen', 'almacen', 'Luis Vladimir Nochez Moreno', 'depto.almacen@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(33, 'Gerencia Financiera', 'gerente-financiero', 'Juan Jose Calero Cruz', 'gerencia.financiera@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-27 17:18:32'),
(34, 'Depto Financiero', 'depto-financiero', 'Israel Antonio Salgado Ramos', 'coordinadorfinanciero@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(35, 'Depto Tesoreria', 'depto-tesoreria', 'Fatima Brendaly Tamayo', 'tesoreria@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(36, 'Depto Contabilidad', 'depto-contabilidad', 'Xiomara Estela Suarez Quinteros', 'contabilidad@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(37, 'Depto Presupuesto', 'depto-presupuesto', 'David Salvador Mancia', 'presupuesto@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(38, 'Depto Activo Fijo', 'depto-activo-fijo', 'Carlos Edilberto Cañas Aguirre', 'activofijo@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(39, 'Gerente Tributario', 'gerente-tributario', 'Wilver Gerardo Parada Garcia', 'tributaria@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(40, 'Cuentas Corrientes', 'cuentas-corrientes', 'Morena Guadalupe Mejia Mejia', 'delegado.cuentascorriente@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(41, 'Catastro', 'catastro', 'Ronal Alexander Lopez Preza', 'delegadosubgerenciatributaria@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(42, 'Recuperacion de Mora', 'recuperacion-de-mora', 'Yessica Alicia Diaz Rodriguez', 'atencioncontribuyente@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(43, 'Planificacion, Ordenamiento y Desarrollo Territorial', 'planificacion-ordenamiento-y-desarrollo-territorial', 'Alexis Ernesto Paniagua Vasquez', 'secretaria.spodt@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(44, 'Gerencia de Obras Municipales y Proyectos', 'gerencia-de-obras-municipales-y-proyectos', 'Walter Alexander Torres Tobar', 'domp@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(45, 'Depto Conectividad de Vias Rurales', 'depto-conectividad-de-vias-rurales', 'Andres Alfonso Campos Garcia', 'conectividadviasrurales@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(46, 'Renovadores', 'renovadores', 'Oscar Enrique Guzman', 'renovadores@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(47, 'Gerencia de Proyectos Institucionales y de Cooperacion Internacional', 'gerencia-de-proyectos-institucionales-y-de-cooperacion-internacional', 'Henry Josue Servellon Santos', 'cooperacion@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(48, 'Escuela de Formacion y Capacitacion', 'escuela-de-formacion-y-capacitacion', NULL, NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(49, 'Gerencia de Servicios Generales y Publicos', 'gerencia-de-servicios-generales-y-publicos', 'Francisco Israel Acevedo Martinez', 'serviciosgenerales@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(50, 'Mercados', 'mercados', 'Carla Rene Ortiz Hernandez', 'mercados@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-05-18 14:43:19'),
(51, 'Cementerios', 'cementerios', 'Porfirio Armando Rodriguez Hernandez', 'cementerios@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(52, 'Alumbrado Publico', 'alumbrado-publico', 'Jose Ricardo Arias Marciaño', 'alumbrado.publico@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(53, 'Limpieza Publica e Institucional', 'limpieza-publica-e-institucional', 'Marco Antonio Somoza Salomon', 'deptolimpieza@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(54, 'Mantenimiento General y Operativo', 'mantenimiento-general-y-operativo', NULL, 'operativosgenerales@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(55, 'Estructuras Metalicas', 'estructuras-metalicas', 'Mariano Salvador Rodriguez', 'dem@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(56, 'Gerencia Logistica y Transporte', 'gerencia-logistica-y-transporte', 'Gerson Eduardo Henriquez Rivas', 'logisticaytransporte@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(57, 'Depto Livianos, Pesados y Maquinaria', 'depto-livianos-pesados-y-maquinaria', 'Wilfredo Balmore Acevedo Rivas', NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(58, 'Gerencia Desarrollo Social', 'gerencia-desarrollo-social', 'Blanca Marisol Villacorta Vides', 'desarrollo.social@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(59, 'Primera Infancia', 'primera-infancia', 'Mallumy Faridy Guillen Sanchez', 'depina@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(60, 'Depto Mujer', 'depto-mujer', 'Nancy Carolina Cortez Molina', 'mujer@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-06-18 20:49:14'),
(61, 'Depto Organizacion Social', 'depto-organizacion-social', 'Blanca Marisol Villacorta Vides', 'organizacion.social@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(62, 'Deportes', 'deportes', 'Raul Alberto Lovato Osorio', 'deportes@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(63, 'Agricultura y Ganaderia', 'agricultura-y-ganaderia', 'Roberto Antonio Muñoz Melendez', 'agriculturayganaderia@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(64, 'Tiangue San Rafael Obrajuelo', 'tiangue-san-rafael-obrajuelo', 'Jose Israel Hernandez Ayala', 'tiangue.sro@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(65, 'Planta de Compostaje San Rafael Obrajuelo', 'planta-de-compostaje-san-rafael-obrajuelo', 'Jose Daniel Solorzano de Leon', NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(66, 'Gerencia de Inversion', 'gerencia-de-inversion', 'Otsmaro Ladislao Aguilar Jovel', 'inversion@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(67, 'Depto Empleabilidad', 'depto-empleabilidad', 'Claudia Mirta Rivera Abarca', 'emprende@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(68, 'Medio Ambiente', 'medio-ambiente', 'Fatima del Rosario Rodriguez Bachez', 'departamentoambiental@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(69, 'Turismo y Cultura', 'turismo-y-cultura', 'Cristian Mauricio Santos Molina', 'turismo@lapazeste.gob.sv', 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56'),
(70, 'Polideportivo SRO', 'polideportivo-sro', 'Levi Sail Melendes Santos', NULL, 1, '2026-03-12 17:41:56', '2026-03-12 17:41:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `permissions` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `position`, `role`, `permissions`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jerson Zelaya', 'admin', 'admin@inventarioinnovacion.local', '7000-0000', 'Administrador', 'admin', '[\"dashboard.view\", \"items.view\", \"items.create\", \"items.edit\", \"items.delete\", \"movements.view\", \"movements.create\", \"equipments.view\", \"equipments.create\", \"equipments.edit\", \"equipments.delete\", \"delivery_acts.view\", \"delivery_acts.create\", \"delivery_acts.edit\", \"users.view\", \"users.create\", \"users.edit\", \"users.delete\", \"catalogs.view\", \"catalogs.create\", \"catalogs.edit\", \"catalogs.delete\", \"exports.view\"]', 1, NULL, '$2y$12$K4sTvCbcrVuN99E2hcnuKev42lD6lPGtRocKczgUQsXPVxZ/Bh0Xi', 'm5tMsfZVdpbJKk4n4j7mlOIPYpKenke4xNvjAh0ZvzBhEgi1KMp84vXw7HpW', '2026-03-11 20:19:01', '2026-06-22 16:39:08'),
(2, 'Gabriel Vela Guidos', 'lapazestesidig', 'lapazeste.sidig@gmail.com', NULL, 'Programador jr', 'supplies_operator', '[\"items.view\", \"items.create\", \"equipments.view\", \"equipments.create\", \"delivery_acts.view\", \"delivery_acts.create\"]', 1, NULL, '$2y$12$yweGnuJTmWbpvv.DnSINcOtGKXVhm14zFr/GCcZbI2YrQmizBnuIe', NULL, '2026-03-23 20:15:57', '2026-04-07 20:28:29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `delivery_records`
--
ALTER TABLE `delivery_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_records_created_by_foreign` (`created_by`),
  ADD KEY `delivery_records_from_location_id_foreign` (`from_location_id`),
  ADD KEY `delivery_records_assigned_area_id_foreign` (`assigned_area_id`),
  ADD KEY `delivery_records_location_id_foreign` (`location_id`),
  ADD KEY `delivery_records_annulled_by_foreign` (`annulled_by`);

--
-- Indices de la tabla `delivery_record_lines`
--
ALTER TABLE `delivery_record_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_record_lines_delivery_record_id_foreign` (`delivery_record_id`),
  ADD KEY `delivery_record_lines_item_id_foreign` (`item_id`),
  ADD KEY `delivery_record_lines_equipment_id_foreign` (`equipment_id`);

--
-- Indices de la tabla `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipments_serial_number_unique` (`serial_number`),
  ADD UNIQUE KEY `equipments_asset_code_unique` (`asset_code`),
  ADD KEY `equipments_location_id_foreign` (`location_id`),
  ADD KEY `equipments_assigned_user_id_foreign` (`assigned_user_id`),
  ADD KEY `equipments_assigned_area_id_foreign` (`assigned_area_id`);

--
-- Indices de la tabla `equipment_categories`
--
ALTER TABLE `equipment_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipment_categories_slug_unique` (`slug`);

--
-- Indices de la tabla `equipment_movements`
--
ALTER TABLE `equipment_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_movements_equipment_id_foreign` (`equipment_id`),
  ADD KEY `equipment_movements_performed_by_foreign` (`performed_by`),
  ADD KEY `equipment_movements_from_location_id_foreign` (`from_location_id`),
  ADD KEY `equipment_movements_to_location_id_foreign` (`to_location_id`),
  ADD KEY `equipment_movements_from_area_id_foreign` (`from_area_id`),
  ADD KEY `equipment_movements_to_area_id_foreign` (`to_area_id`),
  ADD KEY `equipment_movements_assigned_user_id_foreign` (`assigned_user_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_item_id_foreign` (`item_id`),
  ADD KEY `inventory_movements_from_location_id_foreign` (`from_location_id`),
  ADD KEY `inventory_movements_to_location_id_foreign` (`to_location_id`),
  ADD KEY `inventory_movements_delivery_record_id_foreign` (`delivery_record_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_code_unique` (`code`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `municipal_areas`
--
ALTER TABLE `municipal_areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `municipal_areas_slug_unique` (`slug`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `delivery_records`
--
ALTER TABLE `delivery_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `delivery_record_lines`
--
ALTER TABLE `delivery_record_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `equipment_categories`
--
ALTER TABLE `equipment_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipment_movements`
--
ALTER TABLE `equipment_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `municipal_areas`
--
ALTER TABLE `municipal_areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `delivery_records`
--
ALTER TABLE `delivery_records`
  ADD CONSTRAINT `delivery_records_annulled_by_foreign` FOREIGN KEY (`annulled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_records_assigned_area_id_foreign` FOREIGN KEY (`assigned_area_id`) REFERENCES `municipal_areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_records_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_records_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `delivery_record_lines`
--
ALTER TABLE `delivery_record_lines`
  ADD CONSTRAINT `delivery_record_lines_delivery_record_id_foreign` FOREIGN KEY (`delivery_record_id`) REFERENCES `delivery_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_record_lines_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_record_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `equipments`
--
ALTER TABLE `equipments`
  ADD CONSTRAINT `equipments_assigned_area_id_foreign` FOREIGN KEY (`assigned_area_id`) REFERENCES `municipal_areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipments_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipments_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `equipment_movements`
--
ALTER TABLE `equipment_movements`
  ADD CONSTRAINT `equipment_movements_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_movements_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_movements_from_area_id_foreign` FOREIGN KEY (`from_area_id`) REFERENCES `municipal_areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_movements_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_movements_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_movements_to_area_id_foreign` FOREIGN KEY (`to_area_id`) REFERENCES `municipal_areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `equipment_movements_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_delivery_record_id_foreign` FOREIGN KEY (`delivery_record_id`) REFERENCES `delivery_records` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
