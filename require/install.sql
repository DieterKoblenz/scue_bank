CREATE TABLE IF NOT EXISTS `bankaccounts` (
  `username` text collate latin1_bin,
  `userpass` text collate latin1_bin,
  `funds` decimal(10,2) default '0.00',
  `subdivision` text collate latin1_bin,
  `country` text collate latin1_bin,
  `type` text collate latin1_bin NOT NULL,
  `owner` text collate latin1_bin NOT NULL,
  `style` text collate latin1_bin NOT NULL,
  `status` text collate latin1_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='Table for Bank accounts';

CREATE TABLE IF NOT EXISTS `banklog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `instigator` text COLLATE latin1_bin,
  `source` text COLLATE latin1_bin,
  `destination` text COLLATE latin1_bin,
  `funds` decimal(10,2) DEFAULT '0.00',
  `reason` text COLLATE latin1_bin,
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  `userip` char(17) COLLATE latin1_bin NOT NULL DEFAULT 'UNKNOWN',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=1483 ;

CREATE TABLE IF NOT EXISTS `banksubdivisions` (
  `subdivision` text NOT NULL,
  `country` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;