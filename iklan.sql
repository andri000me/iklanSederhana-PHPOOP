-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2015 at 07:17 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iklan`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int(5) NOT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `kategori`) VALUES
(1, 'Komputer'),
(2, 'Internet'),
(3, 'Elektronik'),
(4, 'Lowongan'),
(5, 'Otomotif'),
(6, 'Properti'),
(7, 'Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` varchar(75) NOT NULL,
  `kota` varchar(20) NOT NULL,
  `telpon` varchar(25) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`username`, `password`, `nama`, `email`, `alamat`, `kota`, `telpon`, `level`) VALUES
('erlang', 'd3e31bebc5ed08d70a844ee96b48c226', 'erlang surya', 'elangsurya19@gmail.com', 'Kaver', 'Tangerang', '089643912019', 'admin'),
('user', 'ee32c060ac0caa70b04e25091bbc11ee', 'Elang surya', 'elank76@gmail.com', 'jl murai raya perumnas 1 nomer 34 RT03/05', 'Tangerang', '0894736222', 'user'),
('cindy', '7c92b4ad4d1ed5742f98fefc6602b4cc', 'cindy audyna', 'cindyaudina@gmail.com', 'Cibodas', 'Tangerang', '0957937498', 'admin'),
('kaka', '300e8266eafe5a762b93c60a5b7c1455', 'kaka kiki', 'kakakiki@gmail.com', 'citayam', 'Bogor', '02193748', 'user'),
('kiki', '7a51c1efcaf5c2c58aee5dd6a03116d0', 'khairunisa', 'khairunisa@yahoo.com', 'cibadak', 'sukabumi', '08968272', 'user'),
('doni', '23ef37da5a5c6d6b010c36f356dc9ad2', 'doni peratama', 'elangsurya19@gmail.com', 'pamuang', 'tangerang', '089643912019', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tb_berita`
--

CREATE TABLE IF NOT EXISTS `tb_berita` (
`id_berita` int(4) NOT NULL,
  `jdl_berita` varchar(100) NOT NULL,
  `isi_berita` text NOT NULL,
  `tgl_berita` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_berita`
--

INSERT INTO `tb_berita` (`id_berita`, `jdl_berita`, `isi_berita`, `tgl_berita`) VALUES
(4, 'beli bukasjd', 'jsdasdh', '05-08-2015, 02:47');

-- --------------------------------------------------------

--
-- Table structure for table `tb_email`
--

CREATE TABLE IF NOT EXISTS `tb_email` (
  `username` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `next_post` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_email`
--

INSERT INTO `tb_email` (`username`, `email`, `next_post`) VALUES
('erlang', 'elangsurya19@gmail.com', 0),
('user', 'elank76@gmail.com', 1439330400),
('cindy', 'cindyaudina@gmail.com', 0),
('kiki', 'khairunisa@yahoo.com', 0),
('kaka', 'kakakiki@gmail.com', 0),
('doni', 'elangsurya19@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_iklan`
--

CREATE TABLE IF NOT EXISTS `tb_iklan` (
`id_iklan` int(4) NOT NULL,
  `id_kategori` varchar(15) NOT NULL,
  `username` varchar(16) NOT NULL,
  `jdl_iklan` varchar(100) NOT NULL,
  `isi_iklan` text NOT NULL,
  `tgl_post` varchar(20) NOT NULL,
  `timestamp` int(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_iklan`
--

INSERT INTO `tb_iklan` (`id_iklan`, `id_kategori`, `username`, `jdl_iklan`, `isi_iklan`, `tgl_post`, `timestamp`) VALUES
(10, '3', 'user', 'elektronik', 'ini elektronik ini elektronik', '25-08-2015, 07:49', 1440453600),
(11, '1', 'user', 'ASUS', 'laptop asus laptop asus laptop asus laptop asus laptop asus laptop asus laptop asuslaptop asuslaptop asuslaptop asus laptop asus laptop asus laptop asus laptop asus laptop asus laptop asus v laptop asus laptop asus laptop asus laptop asus laptop asuslaptop asus laptop asus laptop asus laptop asuslaptop asus laptop asus laptop asus laptop asus laptop asus laptop asus .', '25-08-2015, 08:55', 1440453600);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
 ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tb_berita`
--
ALTER TABLE `tb_berita`
 ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `tb_email`
--
ALTER TABLE `tb_email`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tb_iklan`
--
ALTER TABLE `tb_iklan`
 ADD PRIMARY KEY (`id_iklan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_berita`
--
ALTER TABLE `tb_berita`
MODIFY `id_berita` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_iklan`
--
ALTER TABLE `tb_iklan`
MODIFY `id_iklan` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
