ALTER TABLE  `Resource` ADD  `recordSetIdentifier` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `hasOclcHoldings` varchar( 10 ) NULL DEFAULT NULL ,
ADD  `numberRecordsAvailable` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `numberRecordsLoaded` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `bibSourceURL` VARCHAR( 2000 ) NULL DEFAULT NULL ,
ADD  `catalogingTypeID` int(11) NULL DEFAULT NULL,
ADD  `catalogingStatusID` int(11) NULL DEFAULT NULL;

ALTER TABLE `Alias` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `ResourceStep` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `ResourceFormat` ADD INDEX `shortName` ( `shortName` );
ALTER TABLE `ResourceType` ADD INDEX `shortName` ( `shortName` );
ALTER TABLE `Status` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `ResourceLicenseLink` ADD INDEX `resourceID` ( `resourceID` );
ALTER TABLE `ResourceLicenseStatus` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `Resource` ADD INDEX `catalogingTypeID` ( `catalogingTypeID` );
ALTER TABLE `Resource` ADD INDEX `catalogingStatusID` ( `catalogingStatusID` );

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`CatalogingType`;
CREATE TABLE  `_DATABASE_NAME_`.`CatalogingType` (
  `catalogingTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`catalogingTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`CatalogingStatus`;
CREATE TABLE  `_DATABASE_NAME_`.`CatalogingStatus` (
  `catalogingStatusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`catalogingStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (1, 'Batch');
INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (2, 'Manual');
INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (3, 'MARCit');

INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (1, 'Completed');
INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (2, 'Ongoing');
INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (3, 'Rejected');