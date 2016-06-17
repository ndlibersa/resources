DROP TABLE IF EXISTS `AuthorizedSite`;
CREATE TABLE  `AuthorizedSite` (
  `authorizedSiteID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`authorizedSiteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE 'ResourcePayment'
	ADD `includeStats` boolean default NULL;

ALTER TABLE `Resource`
ADD INDEX `catalogingTypeID` ( `catalogingTypeID` ),
ADD INDEX `catalogingStatusID` ( `catalogingStatusID` );