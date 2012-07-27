CREATE TABLE `{prefix}_{dirname}_group` (
  `group_id` int(11) unsigned NOT NULL	auto_increment,
  `title` varchar(255) NOT NULL,
  `publicity` int(2) unsigned NOT NULL,
  `approval` int(2) unsigned NOT NULL,
  `description` text NOT NULL,
  `posttime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`group_id`),
  KEY `posttime` (`posttime`, `publicity`)
  ) ENGINE=MyISAM;

CREATE TABLE `{prefix}_{dirname}_member` (
  `member_id` int(11) unsigned NOT NULL  auto_increment,
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `rank` int(2) unsigned NOT NULL,
  `posttime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`member_id`),
  KEY `uid` (`uid`, `status`, `rank`) ,
  KEY `group_id` (`group_id`, `status`, `rank`)
  ) ENGINE=MyISAM;

CREATE TABLE `{prefix}_{dirname}_policy` (
  `policy_id` int(11) unsigned NOT NULL  auto_increment,
  `group_id` int(11) unsigned NOT NULL,
  `dirname` varchar(25) NOT NULL,
  `dataname` varchar(25) NOT NULL,
  `action` varchar(25) NOT NULL,
  `rank` int(2) unsigned NOT NULL,
  PRIMARY KEY  (`policy_id`),
  KEY `data` (`group_id`, `dirname`, `dataname`, `action`)
  ) ENGINE=MyISAM;

