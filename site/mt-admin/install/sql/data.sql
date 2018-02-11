--
-- Dumping data for table `fonts`
--

INSERT INTO `fonts` (`id`, `name`, `family`, `category`, `variants`, `subsets`, `version`, `last_modified`, `active`, `active_variants`, `active_subsets`, `provider`, `created`, `modified`, `del`) VALUES
(1, 'Arial', 'arial, helvetica, sans-serif', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(2, 'Courier New', 'courier new, courier', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(3, 'Georgia', 'georgia, palatino', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(4, 'Helvetica', 'helvetica', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(5, 'Tahoma', 'tahoma, arial, helvetica, sans-serif', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(6, 'Times New Roman', 'times new roman, times', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(7, 'Trebuchet MS', 'trebuchet ms, geneva', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0),
(8, 'Verdana', 'verdana, geneva', '', '', '', '', '', 1, '', '', 'system', '2014-09-29 16:24:05', '2014-11-03 17:50:04', 0);

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `locale`, `name`, `priority`, `enabled`, `created`, `modified`, `del`) VALUES
(1, 'en', 'en_US', 'English', 125, 1, '2013-12-18 00:43:26', '2013-12-17 22:43:26', 0),
(2, 'ru', 'ru_RU', 'Русский', 125, 1, '2013-12-18 00:43:46', '2013-12-17 22:43:46', 0),
(3, 'de', 'de_DE', 'Deutsch', 125, 1, '2015-06-18 15:26:46', '2015-06-18 15:26:46', 0),
(4, 'ja', 'ja_JP', '日本語', 125, 1, '2016-04-08 15:26:46', '2016-04-08 15:26:46', 0),
(5, 'sv', 'sv_SE', 'Svenska', 125, 1, '2016-05-06 12:00:00', '2016-05-06 12:00:00', 0),
(6, 'it', 'it_IT', 'Italiano', 125, 1, '2016-06-17 12:00:00', '2016-06-17 12:00:00', 0),
(7, 'nl', 'nl_NL', 'Nederlands', 125, 1, '2016-06-18 12:00:00', '2016-06-18 12:00:00', 0),
(8, 'uk', 'uk_UA', 'Українська', 125, 1, '2016-06-18 12:00:00', '2016-06-18 12:00:00', 0),
(9, 'fr', 'fr_FR', 'Français', 125, 1, '2017-03-02 12:00:00', '2017-03-02 12:00:00', 0),
(10, 'pl', 'pl_PL', 'Polski', 125, 1, '2017-03-06 12:00:00', '2017-03-06 12:00:00', 0),
(11, 'es', 'es_ES', 'Español', 125, 1, '2017-07-06 12:00:00', '2017-07-06 12:00:00', 0),
(12, 'pt', 'pt_BR', 'Português (Brasil)', 125, 1, '2017-08-02 12:00:00', '2017-08-02 12:00:00', 0);

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `parent_id`, `name`, `enabled`, `priority`, `protected`, `hidden`, `created`, `modified`, `del`) VALUES
(1, 0, 'Guest', 1, 255, 1, 1, '2013-12-17 22:45:06', '2013-12-17 21:38:16', 0),
(2, 1, 'User', 1, 250, 1, 0, '2013-12-17 22:45:13', '2013-12-17 21:38:22', 0),
(3, 0, 'Root', 1, 0, 1, 1, '2013-12-17 22:46:55', '2013-12-17 21:37:56', 0),
(4, 2, 'Author', 1, 125, 1, 0, '2013-12-17 22:46:03', '2013-12-17 20:47:48', 0),
(5, 4, 'Editor', 1, 25, 1, 0, '2013-12-17 22:46:21', '2013-12-17 21:38:57', 0),
(6, 3, 'Admin', 1, 1, 1, 0, '2013-12-17 22:46:44', '2013-12-17 21:38:02', 0);

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `type`) VALUES
(1, 'system.version', '3.2.0', ''),
(2, 'system.build', '69', ''),
(3, 'website.main_page', '1', ''),
(4, 'website.title', 'Moto3', ''),
(5, 'website.address', '/', ''),
(6, 'website.timezone', 'UTC', ''),
(7, 'website.language_id', '1', ''),
(8, 'website.language_code', 'en', ''),
(9, 'website.suffix', '0', ''),
(10, 'website.permalinks', '1', ''),
(11, 'website.layouts', '[{"name":"default","label":"MODULE.PAGES.LAYOUTS.DEFAULT.LABEL","title":"MODULE.PAGES.LAYOUTS.DEFAULT.TITLE","sections":[{"name":"header","label":"MODULE.PAGES.LAYOUTS.DEFAULT.SECTIONS.HEADER_LABEL","type":"header"},{"name":"footer","label":"MODULE.PAGES.LAYOUTS.DEFAULT.SECTIONS.FOOTER_LABEL","type":"footer"}]},{"name":"sidebars","label":"MODULE.PAGES.LAYOUTS.SIDEBARS.LABEL","title":"MODULE.PAGES.LAYOUTS.SIDEBARS.TITLE","sections":[{"name":"header","label":"MODULE.PAGES.LAYOUTS.SIDEBARS.SECTIONS.HEADER_LABEL","type":"header"},{"name":"footer","label":"MODULE.PAGES.LAYOUTS.SIDEBARS.SECTIONS.FOOTER_LABEL","type":"footer"},{"name":"sidebar_left","label":"MODULE.PAGES.LAYOUTS.SIDEBARS.SECTIONS.SIDEBAR_LEFT_LABEL","type":"sidebar","properties":{"size":{"default":3,"min":1,"max":12}}},{"name":"sidebar_right","label":"MODULE.PAGES.LAYOUTS.SIDEBARS.SECTIONS.SIDEBAR_RIGHT_LABEL","type":"sidebar","properties":{"size":{"default":3,"min":1,"max":12}}}]}]', ''),
(12, 'website.colors', '{"base":{"1":["#ff7200","#bf5600","#e66700","#ff7200","#ff7200","#ff7200"],"2":["#171925","#000000","#08090d","#171925","#282b40","#3f4466"],"3":["#232635","#000000","#13141c","#232635","#34394f","#4d5575"],"4":["#3e4258","#12131a","#2d2f40","#3e4258","#505573","#6b7299"],"5":["#3e4258","#12131a","#2d2f40","#3e4258","#505573","#6b7299"]},"custom":{"1":"#000000","2":"#ffffff","3":"#00a8ff","4":"#ff0000","5":"#ffde00"}}', 'array'),
(13, 'website.theme', '', ''),
(14, 'website.google_analytics_id', '', ''),
(15, 'website.google_webmaster_tools_tag', '', ''),
(16, 'website.notfound_page_id', '2', ''),
(17, 'website.spacings', '{"desktop":{"small":10,"medium":20,"large":30},"tablet":{"small":10,"medium":20,"large":30},"mobile-h":{"small":10,"medium":20,"large":30},"mobile-v":{"small":10,"medium":20,"large":30}}', ''),
(18, 'website.features', '[]', ''),
(19, 'website.favicon', '', ''),
-- using 'enabled:true' because some theme not has FR for this feature and updater will upgrade value
(20, 'website.back_to_top_button', '{"enabled":true,"topOffset":300,"animationTime":500}', 'object'),
(21, 'website.thumbnails', '[{"name":"s","label":"MODULE.MEDIA_LIBRARY.THUMBNAILS.SMALL_LABEL","width":125,"height":125,"disabled":false,"type":"resize","options":{"scale":true,"quality":80,"compression":7}},{"name":"m","label":"MODULE.MEDIA_LIBRARY.THUMBNAILS.MEDIUM_LABEL","width":300,"height":300,"disabled":false,"type":"resize","options":{"scale":true,"quality":80,"compression":7}},{"name":"l","label":"MODULE.MEDIA_LIBRARY.THUMBNAILS.LARGE_LABEL","width":1200,"height":1200,"disabled":false,"type":"resize","options":{"scale":true,"quality":80,"compression":7}},{"name":"xl","label":"MODULE.MEDIA_LIBRARY.THUMBNAILS.EXTRA_LARGE_LABEL","width":1980,"height":1980,"disabled":false,"type":"resize","options":{"scale":true,"quality":80,"compression":7}}]', 'object'),
(22, 'website.plugins', '[]', 'array'),
(23, 'website.theme_presets', '{}', 'object'),
(24, 'website.widgets', '[]', 'array'),
(25, 'website.custom_code_header', '', 'string'),
(26, 'website.custom_code_footer', '', 'string'),
(27, 'website.default_mail_options', '{"transport":{"class":"Sendmail"}}', 'array'),
(28, 'website.integrations', '[]', 'array'),
(29, 'website.yandex_metrica_counter', '{"id":"","webvisor":false,"track_hash":false}', 'object'),
(30, 'website.yandex_webmaster_code', '', 'string'),
(31, 'website.theme_grid', '{"gutter-width":"30px"}', 'array'),
(32, 'website.title_format', '{{page.title}}', 'string'),
(33, 'website.redirection_rules', '[]', 'array'),
(34, 'website.custom_code_execution', '1', 'bool'),
(35, 'website.password_protection', '{"enabled":false}', 'object'),
(36, 'website.maintenance_mode', '{"message":"The website is currently down for maintenance. We will be back soon!"}', 'object'),
(37, 'website.widget_embed_execution', '1', 'bool'),
(38, 'website.language_locale', 'en_US', 'string'),
(39, 'website.lazy_loading', '{"enabled":true}', 'object'),
(40, 'website.seo_html_attribute_lang', 'en', 'string'),
(41, 'website.popup_preferences', '{"loading_error_message":"The content could not be loaded."}', 'object'),
(42, 'website.notfound_popup_id', '0', 'int');


--
-- Dumping data for table `styles`
--

INSERT INTO `styles` (`id`, `name`, `tag`, `class_name`, `type`, `is_system`, `properties`, `link`, `modified`, `created`) VALUES
(1, 'Medium Text', '', 'moto-text_normal', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#747474","font-size":"18px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7200","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(2, 'Site Title', '', 'moto-text_system_1', 'text', 1, '{"desktop":{"base":{"font-family":"''Russo One'', sans-serif","color":"#000000","font-size":"206px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(3, 'Tagline', '', 'moto-text_system_2', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#d7bfce","font-size":"24px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(4, 'Heading XXL', '', 'moto-text_system_3', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"82px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(5, 'Heading XL', '', 'moto-text_system_4', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"72px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(6, 'Heading L', '', 'moto-text_system_5', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"55px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(7, 'Heading M', '', 'moto-text_system_6', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"48px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(8, 'Heading S', '', 'moto-text_system_7', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"30px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(9, 'Heading XS', '', 'moto-text_system_8', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#000000","font-size":"20px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(10, 'Large Text', '', 'moto-text_system_9', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#747474","font-size":"20px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7200","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(11, 'Small Text', '', 'moto-text_system_10', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#747474","font-size":"16px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7200","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(12, 'Extrasmall Text', '', 'moto-text_system_11', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#747474","font-size":"14px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7200","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(13, 'Custom Text 1', '', 'moto-text_system_12', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#d7bfce","font-size":"30px","line-height":"1.2","letter-spacing":"0px","font-weight":"bold"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#ff7100","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(14, 'Custom Text 2', '', 'moto-text_system_13', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#ff7200","font-size":"30px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#000000","text-decoration":"underline"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(15, 'Custom Text 3', '', 'moto-text_system_14', 'text', 1, '{"desktop":{"base":{"font-family":"''PT Sans'', sans-serif","color":"#ff7200","font-size":"18px","line-height":"1.2","letter-spacing":"0px"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '{"desktop":{"base":{"color":"#000000","text-decoration":"underline","font-weight":"bold"},"hover":{"text-decoration":"none"}},"tablet":{"base":[]},"mobile-h":{"base":[]},"mobile-v":{"base":[]}}', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(16, 'Body', '', 'moto-background', 'background', 1, '{"desktop":{"base":{"background-color":"#ffffff","background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"tablet":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-h":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-v":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}}}', '', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(17, 'Background 1', '', 'moto-background_1', 'background', 1, '{"desktop":{"base":{"background-color":"#f5f4f3","background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"tablet":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-h":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-v":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}}}', '', '2014-11-03 17:50:04', '2014-11-03 17:50:04'),
(18, 'Background 2', '', 'moto-background_2', 'background', 1, '{"desktop":{"base":{"background-color":"@baseColor1_3","background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"tablet":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-h":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}},"mobile-v":{"base":{"background-size":"auto","background-position":"left top","background-repeat":"no-repeat","background-attachment":"scroll"}}}', '', '2014-11-03 17:50:04', '2014-11-03 17:50:04');
