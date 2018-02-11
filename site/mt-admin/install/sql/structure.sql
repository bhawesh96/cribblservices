-- --------------------------------------------------------

--
-- Table structure for table `content_blocks`
--

DROP TABLE IF EXISTS `content_blocks`;
CREATE TABLE IF NOT EXISTS `content_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `content` longtext NOT NULL,
  `properties` text NOT NULL,
  `styles` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

DROP TABLE IF EXISTS `fonts`;
CREATE TABLE IF NOT EXISTS `fonts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `family` varchar(100) NOT NULL DEFAULT '',
  `category` varchar(14) NOT NULL DEFAULT '',
  `variants` text NOT NULL,
  `subsets` text NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT '',
  `last_modified` varchar(10) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `is_protected` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `active_variants` text NOT NULL,
  `active_subsets` text NOT NULL,
  `provider` varchar(50) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `del` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL DEFAULT '',
  `locale` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(24) NOT NULL DEFAULT '',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 125,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `del` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
CREATE TABLE IF NOT EXISTS `media_folders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
  `order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `del` tinyint(4) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_items`
--

DROP TABLE IF EXISTS `media_items`;
CREATE TABLE IF NOT EXISTS `media_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(300) NOT NULL DEFAULT '',
  `type` varchar(12) NOT NULL DEFAULT '',
  `filesize` bigint(11) unsigned NOT NULL DEFAULT 0,
  `properties` text NOT NULL,
  `thumbnails` text NOT NULL,
  `title` text NOT NULL,
  `caption` text NOT NULL,
  `alt` text NOT NULL,
  `author_id` int(11) unsigned NOT NULL DEFAULT 1,
  `is_protected` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `del` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned NOT NULL DEFAULT 1,
  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
  `label` varchar(200) NOT NULL DEFAULT '',
  `action` varchar(16) NOT NULL DEFAULT '',
  `properties` text NOT NULL,
  `order` int(11) unsigned NOT NULL DEFAULT 0,
  `status` varchar(10) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `author_id` int(10) unsigned NOT NULL DEFAULT 1,
  `parent_id` int(10) unsigned NOT NULL DEFAULT 0,
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `level` int(11) unsigned NOT NULL DEFAULT 0,
  `content` longtext NOT NULL,
  `type` varchar(24) NOT NULL DEFAULT 'page',
  `status` varchar(16) NOT NULL DEFAULT 'publish',
  `visibility` varchar(16) NOT NULL DEFAULT 'public',
  `properties` text NOT NULL,
  `layout` varchar(10000) NOT NULL DEFAULT '{"name":"default","sections":{"header":{"id":1,"visible":true},"footer":{"id":2,"visible":true}}}',
  `header_id` int(11) unsigned NOT NULL DEFAULT 1,
  `footer_id` int(11) unsigned NOT NULL DEFAULT 2,
  `styles` text NOT NULL,
  `background_id` int(10) unsigned NOT NULL DEFAULT 0,
  `background` varchar(24) NOT NULL DEFAULT '',
  `short_description` text NOT NULL,
  `published` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `_level` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`,`parent_id`),
  KEY `author_id` (`author_id`),
  KEY `parent_id` (`parent_id`),
  KEY `page__type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_treepath`
--

DROP TABLE IF EXISTS `pages_treepath`;
CREATE TABLE IF NOT EXISTS `pages_treepath` (
  `ancestor` int(11) unsigned NOT NULL,
  `descendant` int(11) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL DEFAULT 0,
  UNIQUE KEY `Ancestor_Descendant` (`ancestor`,`descendant`),
  KEY `ancestor` (`ancestor`),
  KEY `descendant` (`descendant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `name` varchar(24) NOT NULL DEFAULT '',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 125,
  `protected` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `del` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` text,
  `type` enum('','int','float','string','bool','object','array') NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

DROP TABLE IF EXISTS `styles`;
CREATE TABLE IF NOT EXISTS `styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `tag` varchar(16) NOT NULL DEFAULT '',
  `class_name` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `is_responsive` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `properties` text NOT NULL,
  `link` text NOT NULL,
  `modified` datetime DEFAULT NULL DEFAULT '2015-01-01 00:00:01',
  `created` datetime DEFAULT NULL DEFAULT '2015-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`type`),
  UNIQUE KEY `class_name` (`class_name`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `role_id` mediumint(8) unsigned NOT NULL DEFAULT 1,
  `language_id` int(10) unsigned NOT NULL DEFAULT 1,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `password` char(60) NOT NULL DEFAULT '',
  `salt` varchar(64) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `del` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `presets`
--

DROP TABLE IF EXISTS `presets`;
CREATE TABLE `presets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `widget_name` varchar(32) NOT NULL DEFAULT '',
  `class_name` varchar(200) NOT NULL DEFAULT '',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `is_responsive` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `properties` text NOT NULL,
  `template` varchar(32) NOT NULL DEFAULT 'default',
  `created` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  `modified` datetime NOT NULL DEFAULT '2015-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`widget_name`),
  UNIQUE KEY `class_name` (`class_name`,`widget_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
