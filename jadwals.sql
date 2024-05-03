-- Adminer 4.8.1 MySQL 5.7.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `genetika` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `genetika`;

DROP TABLE IF EXISTS `jadwals`;
CREATE TABLE `jadwals` (
 
  `teachs_id` int(10) unsigned NOT NULL,
  `days_id` int(10) unsigned NOT NULL,
  `times_id` int(10) unsigned NOT NULL,
  `rooms_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `jadwals` ( `teachs_id`, `days_id`, `times_id`, `rooms_id`, ) VALUES
(	226,	13,	122,	91),
(	227,	14,	133,	91),
(	228,	12,	133,	93),
(	229,	15,	126,	90),
(	230,	14,	119,	91),
(	231,	11,	125,	90),
(	232,	13,	103,	90),
(	279,	15,	138,	90),
(	234,	13,	128,	94),
(	235,	12,	112,	90),
(	236,	11,	135,	94),
(	237,	14,	106,	91),
(	238,	14,	133,	93),
(	239,	12,	138,	93),
(	240,	12,	108,	89),
(	241,	12,	117,	91),
(	242,	12,	106,	90),
(	243,	12,	104,	92),
(	244,	13,	135,	92),
(	245,	13,	105,	92),
(	246,	13,	108,	90),
(	247,	14,	139,	89),
(	248,	11,	140,	92),
(	249,	15,	122,	91),
(	250,	15,	128,	92),
(	251,	11,	111,	92),
(	252,	11,	126,	92),
(	253,	12,	119,	91),
(	254,	12,	136,	94),
(	255,	13,	127,	89),
(	256,	13,	135,	94),
(	257,	14,	134,	89),
(	258,	11,	121,	92),
(	259,	15,	129,	90),
(	260,	15,	125,	94),
(	261,	14,	103,	89),
(	262,	12,	101,	90),
(	263,	14,	113,	89),
(	264,	14,	129,	89),
(	266,	12,	109,	91),
(	267,	14,	126,	90),
(	268,	13,	135,	94),
(	269,	14,	101,	92),
(	271,	13,	128,	89),
(	272,	15,	137,	94),
(	273,	15,	101,	91),
(	274,	12,	125,	93),
(	275,	11,	133,	89),
(	276,	12,	104,	89),
(	277,	11,	122,	90),
(	278,	12,	117,	91),
(	233,	15,	103,	90),
(	280,	14,	126,	92),
(	281,	11,	137,	94),
(	282,	14,	114,	90),
(	283,	11,	126,	90);

-- 2024-01-02 17:11:40
