CREATE TABLE `{url_list}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `create` int(10) unsigned NOT NULL,
  `module` varchar(64) NOT NULL,
  `table` varchar(64) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `create` (`create`),
  KEY `module` (`module`),
  KEY `table` (`table`),
  KEY `create_id` (`id`, `create`, `status`),
  KEY `module_table` (`module`, `table`)
); 

CREATE TABLE `{url_top}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `create` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create` (`create`),
  KEY `create_id` (`id`, `create`),
  KEY `order_id` (`id`, `order`)
);

CREATE TABLE `{item}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `module` varchar(64) NOT NULL,
  `table` varchar(64) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  KEY `table` (`table`),
  KEY `module_table` (`module`, `table`)
);

CREATE TABLE `{history}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `file` varchar(64) NOT NULL,
  `module` varchar(64) NOT NULL,
  `table` varchar(64) NOT NULL,
  `create` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file` (`file`),
  KEY `create` (`create`)
);