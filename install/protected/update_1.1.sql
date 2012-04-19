ALTER TABLE  `_DATABASE_NAME_`.`Resource` ADD  `recordSetIdentifier` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `hasOclcHoldings` varchar( 10 ) NULL DEFAULT NULL ,
ADD  `numberRecordsAvailable` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `numberRecordsLoaded` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `bibSourceURL` VARCHAR( 2000 ) NULL DEFAULT NULL ,
ADD  `catalogingTypeID` int(11) NULL DEFAULT NULL,
ADD  `catalogingStatusID` int(11) NULL DEFAULT NULL;

ALTER TABLE `_DATABASE_NAME_`.`Alias` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `_DATABASE_NAME_`.`ResourceStep` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `_DATABASE_NAME_`.`ResourceFormat` ADD INDEX `shortName` ( `shortName` );
ALTER TABLE `_DATABASE_NAME_`.`ResourceType` ADD INDEX `shortName` ( `shortName` );
ALTER TABLE `_DATABASE_NAME_`.`Status` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `_DATABASE_NAME_`.`ResourceLicenseLink` ADD INDEX `resourceID` ( `resourceID` );
ALTER TABLE `_DATABASE_NAME_`.`ResourceLicenseStatus` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `_DATABASE_NAME_`.`Resource` ADD INDEX `catalogingTypeID` ( `catalogingTypeID` );
ALTER TABLE `_DATABASE_NAME_`.`Resource` ADD INDEX `catalogingStatusID` ( `catalogingStatusID` );

CREATE TABLE  `_DATABASE_NAME_`.`CatalogingType` (
  `catalogingTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`catalogingTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

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