-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.10-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for db_ceria
CREATE DATABASE IF NOT EXISTS `db_ceria` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `db_ceria`;

-- Dumping structure for table db_ceria.alat_terlibat_insiden
CREATE TABLE IF NOT EXISTS `alat_terlibat_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume_insiden` int(10) unsigned NOT NULL,
  `kode_alat_terlibat` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_alat_terlibat_insiden_resume_insiden` (`id_resume_insiden`),
  KEY `FK_alat_terlibat_insiden_ref_alat_terlibat` (`kode_alat_terlibat`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.alat_terlibat_insiden: 29 rows
/*!40000 ALTER TABLE `alat_terlibat_insiden` DISABLE KEYS */;
REPLACE INTO `alat_terlibat_insiden` (`id`, `id_resume_insiden`, `kode_alat_terlibat`) VALUES
	(127, 0, 16),
	(126, 0, 16),
	(125, 0, 16),
	(124, 0, 14),
	(123, 0, 14),
	(122, 0, 14),
	(121, 0, 14),
	(120, 0, 14),
	(119, 0, 14),
	(118, 0, 14),
	(117, 0, 14),
	(116, 0, 14),
	(115, 0, 14),
	(114, 0, 16),
	(113, 0, 15),
	(112, 0, 19),
	(111, 0, 17),
	(110, 38, 9),
	(109, 38, 3),
	(92, 0, 8),
	(91, 0, 4),
	(90, 37, 14),
	(89, 37, 17),
	(88, 37, 18),
	(128, 40, 12),
	(146, 41, 4),
	(145, 41, 6),
	(144, 41, 19),
	(148, 42, 4);
/*!40000 ALTER TABLE `alat_terlibat_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.data_insiden
CREATE TABLE IF NOT EXISTS `data_insiden` (
  `id_insiden` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume` int(10) unsigned DEFAULT NULL,
  `tanggal_insiden` datetime DEFAULT NULL,
  `nama_pelapor` varchar(150) DEFAULT NULL,
  `kode_company` int(11) DEFAULT NULL,
  `lokasi` varchar(250) DEFAULT NULL,
  `jenis_kecelakaan` smallint(6) DEFAULT NULL,
  `jumlah_korban` smallint(6) DEFAULT NULL,
  `tingkat_keparahan` smallint(6) DEFAULT NULL,
  `area_kerja` smallint(6) DEFAULT NULL,
  `bantuan` text DEFAULT NULL,
  `namafile` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(25) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_insiden`) USING BTREE,
  KEY `tanggal_insiden` (`tanggal_insiden`) USING BTREE,
  KEY `nama_pelapor` (`nama_pelapor`) USING BTREE,
  KEY `FK_data_insiden_resume_insiden` (`id_resume`),
  CONSTRAINT `FK_data_insiden_resume_insiden` FOREIGN KEY (`id_resume`) REFERENCES `resume_insiden` (`id_resume`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table db_ceria.data_insiden: ~8 rows (approximately)
/*!40000 ALTER TABLE `data_insiden` DISABLE KEYS */;
REPLACE INTO `data_insiden` (`id_insiden`, `id_resume`, `tanggal_insiden`, `nama_pelapor`, `kode_company`, `lokasi`, `jenis_kecelakaan`, `jumlah_korban`, `tingkat_keparahan`, `area_kerja`, `bantuan`, `namafile`, `created`, `created_by`, `updated`, `updated_by`) VALUES
	(1, NULL, '2021-03-20 07:50:10', 'hasan', NULL, 'site', 1, 1, 1, NULL, 'testing', 'hasan-20210320-075104.jpg', '2021-03-20 00:51:04', 'hasan', NULL, NULL),
	(3, NULL, '2021-04-05 14:41:10', 'hasan', NULL, 'site', 2, 2, 3, NULL, 'dikuburkan', 'hasan-20210405-144218.png', '2021-04-05 14:42:18', 'hasan', NULL, NULL),
	(6, NULL, '2021-04-05 14:43:19', 'hasan', NULL, 'site', 2, 2, 2, NULL, 'dioperasi', 'no-image.png', '2021-04-05 14:43:34', 'hasan', NULL, NULL),
	(18, NULL, '2021-05-20 14:00:12', 'hasan', NULL, 'site', 2, 3, 3, NULL, 'aasd', 'no-image.png', '2021-05-20 14:00:31', 'hasan', NULL, NULL),
	(19, 39, '2021-05-20 14:00:12', 'hasan', NULL, 'site', 2, 3, 3, NULL, 'aasd', 'no-image.png', '2021-05-20 14:00:42', 'hasan', NULL, NULL),
	(38, 38, '2021-05-20 14:57:41', 'hasan', NULL, 'site', 4, 1, 2, NULL, 'tes', NULL, '2021-05-20 14:57:56', 'hasan', '2021-05-21 01:09:51', 'hasan'),
	(39, 42, '2021-05-20 22:56:16', 'hasan', NULL, 'site', 9, 2, 1, NULL, 'tess', NULL, '2021-05-20 22:56:35', 'hasan', '2021-05-21 15:03:09', 'hasan'),
	(40, NULL, '2021-07-30 20:16:50', 'hasan', NULL, 'site', 1, 2, 1, NULL, 'tes', NULL, '2021-07-30 20:18:53', 'hasan', NULL, NULL);
/*!40000 ALTER TABLE `data_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.faktor_pekerjaan_insiden
CREATE TABLE IF NOT EXISTS `faktor_pekerjaan_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume_insiden` int(10) unsigned NOT NULL DEFAULT 0,
  `kode_faktor_pekerjaan` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_faktor_pekerjaan_insiden_resume_insiden` (`id_resume_insiden`),
  KEY `FK_faktor_pekerjaan_insiden_ref_faktor_pekerjaan` (`kode_faktor_pekerjaan`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.faktor_pekerjaan_insiden: 5 rows
/*!40000 ALTER TABLE `faktor_pekerjaan_insiden` DISABLE KEYS */;
REPLACE INTO `faktor_pekerjaan_insiden` (`id`, `id_resume_insiden`, `kode_faktor_pekerjaan`) VALUES
	(1, 41, 1),
	(2, 41, 2),
	(3, 47, 3),
	(4, 48, 2),
	(5, 42, 2);
/*!40000 ALTER TABLE `faktor_pekerjaan_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.faktor_personal_insiden
CREATE TABLE IF NOT EXISTS `faktor_personal_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume_insiden` int(10) unsigned NOT NULL DEFAULT 0,
  `kode_faktor_personal` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_faktor_personal_insiden_resume_insiden` (`id_resume_insiden`),
  KEY `FK_faktor_personal_insiden_ref_faktor_personal` (`kode_faktor_personal`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.faktor_personal_insiden: 5 rows
/*!40000 ALTER TABLE `faktor_personal_insiden` DISABLE KEYS */;
REPLACE INTO `faktor_personal_insiden` (`id`, `id_resume_insiden`, `kode_faktor_personal`) VALUES
	(4, 41, 4),
	(3, 41, 2),
	(5, 47, 3),
	(6, 48, 3),
	(7, 42, 4);
/*!40000 ALTER TABLE `faktor_personal_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.foto_insiden
CREATE TABLE IF NOT EXISTS `foto_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_data_insiden` int(11) NOT NULL,
  `namafile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_foto_insiden_data_insiden` (`id_data_insiden`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.foto_insiden: 5 rows
/*!40000 ALTER TABLE `foto_insiden` DISABLE KEYS */;
REPLACE INTO `foto_insiden` (`id`, `id_data_insiden`, `namafile`) VALUES
	(26, 39, 'hasan-20210521-150244_16367.png'),
	(27, 39, 'hasan-20210521-150309_1492.png'),
	(28, 39, 'hasan-20210521-150309_17277.png'),
	(29, 40, 'hasan-20210730-201853_11055.jpg'),
	(30, 40, 'hasan-20210730-201853_25916.jpeg');
/*!40000 ALTER TABLE `foto_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.kondisi_insiden
CREATE TABLE IF NOT EXISTS `kondisi_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume_insiden` int(10) unsigned NOT NULL DEFAULT 0,
  `kode_kondisi_kerja` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_kondisi_insiden_resume_insiden` (`id_resume_insiden`),
  KEY `FK_kondisi_insiden_kondisi_kerja_tidak_standar` (`kode_kondisi_kerja`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.kondisi_insiden: 6 rows
/*!40000 ALTER TABLE `kondisi_insiden` DISABLE KEYS */;
REPLACE INTO `kondisi_insiden` (`id`, `id_resume_insiden`, `kode_kondisi_kerja`) VALUES
	(1, 41, 2),
	(2, 41, 3),
	(3, 41, 4),
	(4, 47, 5),
	(5, 48, 4),
	(6, 42, 4);
/*!40000 ALTER TABLE `kondisi_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.korban_insiden
CREATE TABLE IF NOT EXISTS `korban_insiden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resume_insiden` int(11) unsigned NOT NULL,
  `nama_korban` varchar(100) NOT NULL,
  `nik` varchar(100) NOT NULL,
  `atasan_langsung` varchar(100) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `kode_jabatan` int(11) unsigned NOT NULL,
  `kode_department` int(11) NOT NULL,
  `kode_masa_kerja` int(11) unsigned NOT NULL,
  `kode_bagian_luka` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_korban_insiden_ref_bagian_luka` (`kode_bagian_luka`),
  KEY `FK_korban_insiden_ref_masa_kerja` (`kode_masa_kerja`),
  KEY `FK_korban_insiden_organizational_structure` (`kode_department`),
  KEY `FK_korban_insiden_ref_jabatan` (`kode_jabatan`),
  KEY `FK_korbain_insiden_resume_insiden` (`id_resume_insiden`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

-- Dumping data for table db_ceria.korban_insiden: 10 rows
/*!40000 ALTER TABLE `korban_insiden` DISABLE KEYS */;
REPLACE INTO `korban_insiden` (`id`, `id_resume_insiden`, `nama_korban`, `nik`, `atasan_langsung`, `umur`, `kode_jabatan`, `kode_department`, `kode_masa_kerja`, `kode_bagian_luka`) VALUES
	(137, 42, 'tes', '123555', 'tes', 12, 2, 3, 2, 4),
	(138, 42, 'tesssss', '5665', 'tes', 32, 4, 1, 4, 3),
	(62, 37, 'tes', '123', 'qwe', 21, 4, 21, 3, 5),
	(61, 37, 'tes', '23434324', 'qwe', 21, 5, 1, 5, 1),
	(60, 37, 'tes', '123', 'tes', 12, 3, 3, 3, 2),
	(135, 41, 'inooooo', '345454', 'qwe', 23, 5, 1, 3, 3),
	(136, 41, 'anoooo', '23434324', 'qwe', 21, 3, 3, 3, 2),
	(90, 38, 'tesssss', '243234', 'tes', 43, 6, 21, 2, 5),
	(126, 0, 'ando', '3333', 'tes', 32, 5, 1, 1, 4),
	(125, 0, 'andi', '5555', 'tes', 23, 3, 21, 3, 3);
/*!40000 ALTER TABLE `korban_insiden` ENABLE KEYS */;

-- Dumping structure for table db_ceria.resume_insiden
CREATE TABLE IF NOT EXISTS `resume_insiden` (
  `id_resume` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_register` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `kode_company` int(11) DEFAULT NULL,
  `kode_jam` int(11) DEFAULT NULL,
  `kode_shift` int(11) DEFAULT NULL,
  `kode_bulan` int(11) DEFAULT NULL,
  `kode_hari` int(11) DEFAULT NULL,
  `kode_area_kerja` int(11) DEFAULT NULL,
  `kode_insiden` int(11) DEFAULT NULL,
  `kode_cara_kerja` int(11) DEFAULT NULL,
  `kode_tidak_standar` int(11) DEFAULT NULL,
  `kode_faktor_pekerjaan` int(11) DEFAULT NULL,
  `kode_faktor_personil` int(11) DEFAULT NULL,
  `kode_tindakan_perbaikan` int(11) DEFAULT NULL,
  `kode_sanksi` int(11) DEFAULT NULL,
  `kode_hari_kerja_hilang` varchar(50) DEFAULT NULL,
  `kode_biaya_perbaikan_unit` int(11) DEFAULT NULL,
  `state` enum('draft','on progress','done') DEFAULT NULL COMMENT 'draft verified done',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `tgl_awal_progress` datetime DEFAULT NULL,
  `tgl_selesai_progress` datetime DEFAULT NULL,
  `step_position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_resume`) USING BTREE,
  KEY `tanggal` (`tanggal`) USING BTREE,
  KEY `no_register` (`no_register`) USING BTREE,
  FULLTEXT KEY `deskripsi` (`deskripsi`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table db_ceria.resume_insiden: ~11 rows (approximately)
/*!40000 ALTER TABLE `resume_insiden` DISABLE KEYS */;
REPLACE INTO `resume_insiden` (`id_resume`, `no_register`, `deskripsi`, `tanggal`, `kode_company`, `kode_jam`, `kode_shift`, `kode_bulan`, `kode_hari`, `kode_area_kerja`, `kode_insiden`, `kode_cara_kerja`, `kode_tidak_standar`, `kode_faktor_pekerjaan`, `kode_faktor_personil`, `kode_tindakan_perbaikan`, `kode_sanksi`, `kode_hari_kerja_hilang`, `kode_biaya_perbaikan_unit`, `state`, `created_date`, `tgl_awal_progress`, `tgl_selesai_progress`, `step_position`) VALUES
	(38, '1', 'qew', '2021-04-20 01:25:23', 157, NULL, 1, 4, 2, 1, 2, 3, 3, 4, 2, 9, NULL, '2', 7, 'done', '2021-06-18 07:26:45', '2021-04-20 01:25:23', '2021-04-20 01:25:23', NULL),
	(39, '00011', 'qew', '2021-04-20 01:25:23', 157, NULL, 1, 4, 2, 1, 2, 3, 3, 4, 2, 9, NULL, '2', 7, 'done', '2021-06-18 07:26:45', '2021-04-20 01:25:23', '2021-04-20 01:25:23', NULL),
	(40, '12', 'tes', '2021-02-03 09:25:23', 161, 2, 1, 2, 3, 1, 2, 15, 3, 3, 3, 10, NULL, '1', 7, 'draft', '2021-08-05 17:55:05', NULL, NULL, NULL),
	(41, '13', 'tesss', '2021-04-20 01:25:23', 160, NULL, 2, 4, 2, 2, 2, 3, 3, 3, 2, 2, NULL, '4', 3, 'done', '2021-08-05 18:00:12', '2021-08-11 03:59:09', '2021-08-12 01:09:31', 4),
	(42, '14', 'tes', '2021-08-05 21:02:00', 160, 6, 2, 8, 4, 3, 3, 3, 3, 4, 3, 3, NULL, '5', 2, 'done', '2021-08-05 18:50:11', '2021-08-05 21:02:00', '2021-08-12 02:35:37', 3),
	(43, '00015', 'tes', '2021-08-12 02:58:00', 165, NULL, 1, 8, 4, 2, 3, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 00:58:44', '2021-08-12 00:58:44', NULL, NULL),
	(44, '00016', 'tes', '2021-08-12 03:09:00', 160, NULL, 1, 8, 4, 2, 3, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 01:09:59', '2021-08-12 01:09:59', NULL, NULL),
	(45, '00017', 'tes', '2021-08-12 03:17:00', 160, NULL, 2, 8, 4, 2, 2, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 01:17:49', '2021-08-12 01:17:49', NULL, 1),
	(46, '00018', 'tes', '2021-08-12 03:18:00', 160, NULL, 1, 8, 4, 2, 2, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 01:18:55', '2021-08-12 01:18:55', NULL, 1),
	(47, '00019', 'tes', '2021-08-12 03:21:00', 160, NULL, 1, 8, 4, 2, 2, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 01:22:06', '2021-08-12 01:22:06', NULL, 1),
	(48, '00020', 'tes', '2021-08-12 04:11:00', 160, NULL, 2, 8, 4, 3, 3, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on progress', '2021-08-12 02:11:32', '2021-08-12 02:11:32', NULL, 1);
/*!40000 ALTER TABLE `resume_insiden` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
