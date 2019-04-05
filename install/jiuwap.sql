CREATE TABLE IF NOT EXISTS `browser_books` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `uid` int(5) unsigned NOT NULL default '0',
  `title` varchar(120) NOT NULL,
  `url` varchar(250) NOT NULL,
  `nums` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `browser_caches` (
  `keyid` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `type` enum('1','0') NOT NULL default '0',
  `uid` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`keyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `browser_cookies` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  `domain` varchar(255) NOT NULL COMMENT 'æœ‰æ•ˆåŸŸå',
  `path` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL default '0',
  `key` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `host` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `browser_copys` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `uid` int(5) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `browser_dns` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `domain` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `browser_temps` (
  `uid` mediumint(8) unsigned NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  KEY `k` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `browser_temps_file` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL,
  `file` varchar(255) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `browser_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `num_time` int(10) unsigned NOT NULL,
  `num_look` int(10) unsigned NOT NULL,
  `num_size_html` int(10) unsigned NOT NULL,
  `num_size_pic` int(10) unsigned NOT NULL,
  `config_pic` enum('0','1','2','3','4','5','6','7','8') NOT NULL,
  `config_wap2wml` enum('0','1','2','3','4') NOT NULL,
  `config_useragent` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `config_ipagent` varchar(30) NOT NULL,
  `config_cutpage` int(3) NOT NULL,
  `config_pic_wap` enum('0','1','2','3','4','5','6','7','8') NOT NULL default '0',
  `config_ipagent_open` enum('0','1') NOT NULL default '0',
  `template_foot` varchar(90) NOT NULL default '[book]|[menu][br][size]',
  `space_all` int(10) unsigned NOT NULL default '0',
  `space_use` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`name`),
  KEY `name` (`name`),
  KEY `pass` (`pass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `browser_users_quicklogin` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `type` enum('qq','sina') NOT NULL,
  `key` varchar(255) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `disk_dir` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `uid` int(5) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL,
  `oid` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `disk_file` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `uid` int(5) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `mime` varchar(10) default '',
  `file` varchar(50) NOT NULL,
  `oid` int(5) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
