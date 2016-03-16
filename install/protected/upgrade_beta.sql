DROP TABLE IF EXISTS `AlertDaysInAdvance`;
CREATE TABLE  `AlertDaysInAdvance` (
  `alertDaysInAdvanceID` int(11) NOT NULL auto_increment,
  `daysInAdvanceNumber` int(11) default NULL,
  PRIMARY KEY  (`alertDaysInAdvanceID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `AlertEmailAddress`;
CREATE TABLE  `AlertEmailAddress` (
  `alertEmailAddressID` int(11) NOT NULL auto_increment,
  `emailAddress` varchar(200) default NULL,
  PRIMARY KEY  (`alertEmailAddressID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `Contact` DROP COLUMN `state`,
 DROP COLUMN `country`,
 ADD COLUMN `addressText` VARCHAR(300) AFTER `title`;

DROP TABLE IF EXISTS `Country`;

DROP TABLE IF EXISTS `Currency`;
CREATE TABLE  `Currency` (
  `currencyCode` varchar(3) NOT NULL,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`currencyCode`),
  UNIQUE KEY `currencyCode` (`currencyCode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Fund`;
CREATE TABLE `Fund` (
  `fundID` int(11) NOT NULL auto_increment,
  `fundCode` varchar(20) default NULL,
  `shortName` varchar(200) default NULL,
  `archived` boolean default NULL,
  PRIMARY KEY (`fundID`),
  UNIQUE `fundCode` (`fundCode`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ImportConfig`;
CREATE TABLE `ImportConfig` (
  `importConfigID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  `configuration` varchar(1000) default NULL,
  PRIMARY KEY (`importConfigID`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `OrgNameMapping`;
CREATE TABLE `OrgNameMapping` (
  `orgNameMappingID` int(11) NOT NULL auto_increment,
  `importConfigID` int(11) NOT NULL,
  `orgNameImported` varchar(200) default NULL,
  `orgNameMapped` varchar(200) default NULL,
  PRIMARY KEY (`orgNameMappingID`),
  KEY (`importConfigID`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

ALTER TABLE `Resource` ADD COLUMN `archiveDate` DATE AFTER `updateLoginID`,
 ADD COLUMN `archiveLoginID` VARCHAR(45) AFTER `archiveDate`,
 ADD COLUMN `workflowRestartDate` DATE AFTER `archiveLoginID`,
 ADD COLUMN `workflowRestartLoginID` VARCHAR(45) AFTER `workflowRestartDate`,
 ADD COLUMN `providerText` VARCHAR(200) AFTER `accessMethodID`;

DROP TABLE IF EXISTS `ResourceAlert`;
CREATE TABLE  `ResourceAlert` (
  `resourceAlertID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `loginID` varchar(45) default NULL,
  `sendDate` date default NULL,
  `alertText` text,
  PRIMARY KEY  (`resourceAlertID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `ResourcePayment` ADD COLUMN `currencyCode` VARCHAR(3) AFTER `orderTypeID`;

DROP TABLE IF EXISTS `State`;

DROP TABLE IF EXISTS `ResourceStep`;
CREATE TABLE  `ResourceStep` (
  `resourceStepID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `stepID` int(11) default NULL,
  `stepStartDate` date default NULL,
  `stepEndDate` date default NULL,
  `endLoginID` varchar(200) default NULL,
  `priorStepID` int(11) default NULL,
  `stepName` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  `displayOrderSequence` int(10) unsigned default NULL,
  PRIMARY KEY  (`resourceStepID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Step`;
CREATE TABLE  `Step` (
  `stepID` int(11) NOT NULL auto_increment,
  `priorStepID` int(11) default NULL,
  `stepName` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  `workflowID` int(11) default NULL,
  `displayOrderSequence` int(10) unsigned default NULL,
  PRIMARY KEY  (`stepID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `User` ADD COLUMN `emailAddress` VARCHAR(200) AFTER `accountTabIndicator`;

DROP TABLE IF EXISTS `UserGroup`;
CREATE TABLE  `UserGroup` (
  `userGroupID` int(11) NOT NULL auto_increment,
  `groupName` varchar(200) default NULL,
  `emailAddress` varchar(200) default NULL,
  `emailText` varchar(2000) default NULL,
  PRIMARY KEY  (`userGroupID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `UserGroupLink`;
CREATE TABLE  `UserGroupLink` (
  `userGroupLinkID` int(11) NOT NULL auto_increment,
  `loginID` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  PRIMARY KEY  (`userGroupLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Workflow`;
CREATE TABLE  `Workflow` (
  `workflowID` int(11) NOT NULL auto_increment,
  `workflowName` varchar(200) default NULL,
  `resourceFormatIDValue` varchar(45) default NULL,
  `resourceTypeIDValue` varchar(45) default NULL,
  `acquisitionTypeIDValue` varchar(45) default NULL,
  PRIMARY KEY  (`workflowID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `Alias` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_aliasTypeID`(`aliasTypeID`),
 ADD INDEX `Index_All`(`resourceID`, `aliasTypeID`);
 
ALTER TABLE `Resource` ADD INDEX `Index_createDate`(`createDate`),
 ADD INDEX `Index_createLoginID`(`createLoginID`),
 ADD INDEX `Index_titleText`(`titleText`),
 ADD INDEX `Index_isbnOrISSN`(`isbnOrISSN`),
 ADD INDEX `Index_statusID`(`statusID`),
 ADD INDEX `Index_resourceTypeID`(`resourceTypeID`),
 ADD INDEX `Index_resourceFormatID`(`resourceFormatID`),
 ADD INDEX `Index_acquisitionTypeID`(`authenticationTypeID`),
 ADD INDEX `Index_All`(`createDate`, `createLoginID`, `titleText`, `isbnOrISSN`, `statusID`, `resourceTypeID`, `resourceFormatID`, `acquisitionTypeID`);
 
ALTER TABLE `ResourcePayment` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_fundID`(`fundID`),
 ADD INDEX `Index_All`(`resourceID`, `fundID:`); 
 
ALTER TABLE `ResourceNote` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_noteTypeID`(`noteTypeID`),
 ADD INDEX `Index_All`(`resourceID`, `noteTypeID`);
 
ALTER TABLE `ResourceOrganizationLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_organizationID`(`organizationID`),
 ADD INDEX `Index_All`(`resourceID`, `organizationID`);

ALTER TABLE `ResourcePurchaseSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_purchaseSiteID`(`purchaseSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `purchaseSiteID`);

ALTER TABLE `ResourceAdministeringSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_administeringSiteID`(`administeringSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `administeringSiteID`);
 
ALTER TABLE `ResourceAuthorizedSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_authorizedSiteID`(`authorizedSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `authorizedSiteID`); 
 
ALTER TABLE `ResourceRelationship` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_relatedResourceID`(`relatedResourceID`),
 ADD INDEX `Index_All`(`resourceID`, `relatedResourceID`);

INSERT INTO `Currency` (currencyCode, shortName) values ('USD', 'United States Dollar');
INSERT INTO `Currency` (currencyCode, shortName) values ('EUR', 'Euro');
INSERT INTO `Currency` (currencyCode, shortName) values ('GBP', 'Great Britain (UK) Pound');
INSERT INTO `Currency` (currencyCode, shortName) values ('CAD', 'Canadian Dollar');
INSERT INTO `Currency` (currencyCode, shortName) values ('ARS', 'Argentine Peso');
INSERT INTO `Currency` (currencyCode, shortName) values ('AUD', 'Australian Dollar');
INSERT INTO `Currency` (currencyCode, shortName) values ('SEK', 'Swedish Krona');

INSERT INTO `NoteType` (shortName) values ('Initial Note');

DELETE FROM `Status`;
INSERT INTO `Status` (shortName) values ('In Progress');
INSERT INTO `Status` (shortName) values ('Completed');
INSERT INTO `Status` (shortName) values ('Saved');
INSERT INTO `Status` (shortName) values ('Archived');


INSERT INTO `Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (1, '2', '','1');

INSERT INTO `Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (2, '2', '','2');

INSERT INTO `UserGroup` (userGroupID, groupName) VALUES (1, 'Access');
INSERT INTO `UserGroup` (userGroupID, groupName) VALUES (2, 'Licensing');
INSERT INTO `UserGroup` (userGroupID, groupName) VALUES (3, 'Funding Approval');
INSERT INTO `UserGroup` (userGroupID, groupName) VALUES (4, 'Acquisitions');
INSERT INTO `UserGroup` (userGroupID, groupName) VALUES (5, 'Receipt');

INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (1, NULL, 'Funding Approval', 3, 1, 1);
INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (2, NULL, 'Licensing', 2, 1, 2);
INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (3, 2, 'Order Processing', 4, 1, 3);
INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (4, 3, 'Activation', 1, 1, 4);

INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (5, NULL, 'Licensing', 2, 2, 1);
INSERT INTO `Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (6, NULL, 'Activation', 1, 2, 2);

INSERT INTO `Fund` (shortName) SELECT DISTINCT `fundName` FROM `ResourcePayment`;
UPDATE `Fund` SET fundCode = fundID;

ALTER TABLE `ResourcePayment` ADD COLUMN `fundID` int(10) AFTER `resourceID`;

UPDATE `ResourcePayment`
INNER JOIN `Fund`
    ON `ResourcePayment`.fundName = `Fund`.shortName
SET `ResourcePayment`.fundID = `Fund`.fundID;

ALTER TABLE `ResourcePayment` DROP COLUMN `fundName`;
