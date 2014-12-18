-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-12-18 02:38:16
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `exp_address`
--

CREATE TABLE IF NOT EXISTS `exp_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(10) DEFAULT NULL,
  `addr` text,
  `cata` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_client`
--

CREATE TABLE IF NOT EXISTS `exp_client` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(10) DEFAULT NULL,
  `cata` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_hawb`
--

CREATE TABLE IF NOT EXISTS `exp_hawb` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hawb` varchar(12) DEFAULT NULL,
  `mawb` varchar(12) DEFAULT NULL,
  `opdate` date DEFAULT NULL,
  `dest` varchar(10) DEFAULT NULL,
  `fltno` varchar(12) DEFAULT NULL,
  `fltdate` date DEFAULT NULL,
  `forward` varchar(20) DEFAULT NULL,
  `seller` varchar(20) DEFAULT NULL,
  `factory` varchar(20) DEFAULT NULL,
  `carrier` varchar(10) DEFAULT NULL,
  `carriername` varchar(20) DEFAULT NULL,
  `paymt` varchar(10) DEFAULT NULL,
  `arranged` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `gw` int(11) DEFAULT NULL,
  `cw` int(11) DEFAULT NULL,
  `cbm` float DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  `depar` varchar(10) DEFAULT NULL,
  `desti` varchar(20) DEFAULT NULL,
  `consignee` text,
  `notify` text,
  `shipper` text,
  `curr` varchar(10) DEFAULT NULL,
  `nvd` varchar(10) DEFAULT NULL,
  `ncv` varchar(10) DEFAULT NULL,
  `package` varchar(10) DEFAULT NULL,
  `rclass` varchar(10) DEFAULT NULL,
  `special` text,
  `cgodescp` text,
  `agentabbr` varchar(10) DEFAULT NULL,
  `regtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1638 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_mawb`
--

CREATE TABLE IF NOT EXISTS `exp_mawb` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mawb` varchar(12) DEFAULT NULL,
  `oversea` varchar(20) DEFAULT NULL,
  `dest` varchar(10) DEFAULT NULL,
  `desti` varchar(20) DEFAULT NULL,
  `depa` varchar(10) DEFAULT NULL,
  `depar` varchar(20) DEFAULT NULL,
  `shipper` text,
  `consignee` text,
  `agentabbr` varchar(50) DEFAULT NULL,
  `agentcode` varchar(20) DEFAULT NULL,
  `agentaccount` varchar(20) DEFAULT NULL,
  `carrier` varchar(20) DEFAULT NULL,
  `fltno` varchar(12) DEFAULT NULL,
  `fltdate` date DEFAULT NULL,
  `special` text,
  `package` varchar(10) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `gw` int(11) DEFAULT NULL,
  `cw` int(11) DEFAULT NULL,
  `cbm` float DEFAULT NULL,
  `rclass` varchar(10) DEFAULT NULL,
  `up` decimal(10,2) DEFAULT NULL,
  `freight` decimal(10,2) DEFAULT NULL,
  `awn` varchar(5) DEFAULT NULL,
  `myn` varchar(5) DEFAULT NULL,
  `scn` varchar(5) DEFAULT NULL,
  `myup` decimal(10,2) DEFAULT NULL,
  `scup` decimal(10,2) DEFAULT NULL,
  `aw` decimal(10,2) DEFAULT NULL,
  `my` decimal(10,2) DEFAULT NULL,
  `sc` decimal(10,2) DEFAULT NULL,
  `other` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `cgodescp` text,
  `signature` varchar(50) DEFAULT NULL,
  `atplace` varchar(20) DEFAULT NULL,
  `operator` varchar(20) DEFAULT NULL,
  `opdate` date DEFAULT NULL,
  `regtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=829 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_port`
--

CREATE TABLE IF NOT EXISTS `exp_port` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `zone` varchar(10) DEFAULT NULL,
  `m` decimal(12,2) DEFAULT NULL,
  `n` decimal(12,2) DEFAULT NULL,
  `q` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_seller`
--

CREATE TABLE IF NOT EXISTS `exp_seller` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `forward` varchar(20) DEFAULT NULL,
  `seller` varchar(20) DEFAULT NULL,
  `remark` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `exp_user`
--

CREATE TABLE IF NOT EXISTS `exp_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `pass` varchar(64) DEFAULT NULL,
  `nick` varchar(32) DEFAULT NULL,
  `dept` varchar(30) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `access` int(11) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  `lastdate` datetime DEFAULT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `exp_v_manifest`
--
CREATE TABLE IF NOT EXISTS `exp_v_manifest` (
`hawb` varchar(12)
,`mawb` varchar(12)
,`num` int(11)
,`gw` int(11)
,`dest` varchar(10)
,`shipper` text
,`consignee` text
,`cgodescp` text
);
-- --------------------------------------------------------

--
-- 替换视图以便查看 `exp_v_qty`
--
CREATE TABLE IF NOT EXISTS `exp_v_qty` (
`fltdate` date
,`sumgw` decimal(32,0)
,`sumcw` decimal(32,0)
,`sumcbm` double
,`hawb` varchar(12)
,`mawb` varchar(12)
,`forward` varchar(20)
,`seller` varchar(20)
,`carrier` varchar(10)
);
-- --------------------------------------------------------

--
-- 视图结构 `exp_v_manifest`
--
DROP TABLE IF EXISTS `exp_v_manifest`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `exp_v_manifest` AS select `exp_hawb`.`hawb` AS `hawb`,`exp_hawb`.`mawb` AS `mawb`,`exp_hawb`.`num` AS `num`,`exp_hawb`.`gw` AS `gw`,`exp_hawb`.`dest` AS `dest`,`exp_hawb`.`shipper` AS `shipper`,`exp_hawb`.`consignee` AS `consignee`,`exp_hawb`.`cgodescp` AS `cgodescp` from `exp_hawb`;

-- --------------------------------------------------------

--
-- 视图结构 `exp_v_qty`
--
DROP TABLE IF EXISTS `exp_v_qty`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `exp_v_qty` AS select `exp_hawb`.`fltdate` AS `fltdate`,sum(`exp_hawb`.`gw`) AS `sumgw`,sum(`exp_hawb`.`cw`) AS `sumcw`,sum(`exp_hawb`.`cbm`) AS `sumcbm`,`exp_hawb`.`hawb` AS `hawb`,`exp_hawb`.`mawb` AS `mawb`,`exp_hawb`.`forward` AS `forward`,`exp_hawb`.`seller` AS `seller`,`exp_hawb`.`carrier` AS `carrier` from `exp_hawb` where (`exp_hawb`.`hawb` <> '') group by `exp_hawb`.`hawb` order by `exp_hawb`.`fltdate`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
