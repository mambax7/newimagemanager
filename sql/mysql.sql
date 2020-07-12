#
# Table structure for table `newimage`
#

CREATE TABLE `newimage` (
  `image_id`            mediumint(8) unsigned NOT NULL auto_increment,
  `image_name`          varchar(30)           NOT NULL default '',
  `image_nicename`      varchar(255)          NOT NULL default '',
  `image_alternative`   varchar(255)          NOT NULL default '',
  `image_mimetype`      varchar(30)           NOT NULL default '',
  `image_created`       int(10) unsigned      NOT NULL default '0',
  `image_display`       tinyint(1) unsigned   NOT NULL default '0',
  `image_weight`        smallint(5) unsigned  NOT NULL default '0',
  `imgcat_id`           smallint(5) unsigned  NOT NULL default '0',
  `image_description`   text,
  PRIMARY KEY  (`image_id`),
  KEY `imgcat_id` (`imgcat_id`),
  KEY `image_display` (`image_display`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `newimagebody`
#

CREATE TABLE `newimagebody` (
  `image_id`            mediumint(8) unsigned NOT NULL default '0',
  `image_body`          mediumblob,
  KEY `image_id` (`image_id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `newimagecategory`
#

CREATE TABLE `newimagecategory` (
  `imgcat_id`           smallint(5) unsigned  NOT NULL auto_increment,
  `imgcat_name`         varchar(100)          NOT NULL default '',
  `imgcat_maxsize`      int(8) unsigned       NOT NULL default '0',
  `imgcat_maxwidth`     smallint(3) unsigned  NOT NULL default '0',
  `imgcat_maxheight`    smallint(3) unsigned  NOT NULL default '0',
  `imgcat_display`      tinyint(1) unsigned   NOT NULL default '0',
  `imgcat_weight`       smallint(3) unsigned  NOT NULL default '0',
  `imgcat_type`         char(1)               NOT NULL default '',
  `imgcat_storetype`    varchar(5)            NOT NULL default '',
  `imgcat_relativepath` varchar(255)          default '',
  `imgcat_description`  text,
  `imgcat_cattype`      varchar(5)            NOT NULL default '',
  `imgcat_user_id`      smallint(5) unsigned  NOT NULL default '0',
  `imgcat_module_id`    smallint(5) unsigned  NOT NULL default '0',
  `imgcat_mimetypes`    text,
  PRIMARY KEY  (`imgcat_id`),
  KEY `imgcat_display` (`imgcat_display`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `newimgoptions`
#

CREATE TABLE `newimgoptions` (
  `option_id`           mediumint(8) unsigned NOT NULL auto_increment,
  `option_name`         varchar(30)           NOT NULL default '',
  `option_value`        varchar(255)          NOT NULL default '',
  `option_description`  text,
  PRIMARY KEY  (`option_id`),
  KEY `option_id` (`option_id`)
) TYPE=MyISAM;
