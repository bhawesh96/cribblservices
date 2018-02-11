SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `moto3_install`
--

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

CREATE TABLE IF NOT EXISTS `fonts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `family` varchar(100) NOT NULL,
  `category` varchar(14) NOT NULL,
  `variants` text NOT NULL,
  `subsets` text NOT NULL,
  `version` varchar(10) NOT NULL,
  `last_modified` varchar(10) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `active_variants` text NOT NULL,
  `active_subsets` text NOT NULL,
  `provider` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `del` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

--
-- Dumping data for table `fonts`
--

INSERT IGNORE INTO `fonts` (`id`, `name`, `family`, `category`, `variants`, `subsets`, `version`, `last_modified`, `active`, `active_variants`, `active_subsets`, `provider`, `created`, `modified`, `del`) VALUES
(1, 'Arial', 'arial, helvetica, sans-serif', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(2, 'Courier New', 'courier new, courier', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(3, 'Georgia', 'georgia, palatino', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(4, 'Helvetica', 'helvetica', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(5, 'Tahoma', 'tahoma, arial, helvetica, sans-serif', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(6, 'Times New Roman', 'times new roman, times', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(7, 'Trebuchet MS', 'trebuchet ms, geneva', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0),
(8, 'Verdana', 'verdana, geneva', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-09-29 16:24:05', 0);