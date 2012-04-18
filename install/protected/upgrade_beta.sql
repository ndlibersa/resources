
DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AlertDaysInAdvance`;
CREATE TABLE  `_DATABASE_NAME_`.`AlertDaysInAdvance` (
  `alertDaysInAdvanceID` int(11) NOT NULL auto_increment,
  `daysInAdvanceNumber` int(11) default NULL,
  PRIMARY KEY  (`alertDaysInAdvanceID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AlertEmailAddress`;
CREATE TABLE  `_DATABASE_NAME_`.`AlertEmailAddress` (
  `alertEmailAddressID` int(11) NOT NULL auto_increment,
  `emailAddress` varchar(200) default NULL,
  PRIMARY KEY  (`alertEmailAddressID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `_DATABASE_NAME_`.`Contact` DROP COLUMN `state`,
 DROP COLUMN `country`,
 ADD COLUMN `addressText` VARCHAR(300) AFTER `title`;
 
 
 
DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Country`;




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Currency`;
CREATE TABLE  `_DATABASE_NAME_`.`Currency` (
  `currencyCode` varchar(3) NOT NULL,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`currencyCode`),
  UNIQUE KEY `currencyCode` (`currencyCode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



ALTER TABLE `_DATABASE_NAME_`.`Resource` ADD COLUMN `archiveDate` DATE AFTER `updateLoginID`,
 ADD COLUMN `archiveLoginID` VARCHAR(45) AFTER `archiveDate`,
 ADD COLUMN `workflowRestartDate` DATE AFTER `archiveLoginID`,
 ADD COLUMN `workflowRestartLoginID` VARCHAR(45) AFTER `workflowRestartDate`,
 ADD COLUMN `providerText` VARCHAR(200) AFTER `accessMethodID`;
 
 
 
DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceAlert`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceAlert` (
  `resourceAlertID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `loginID` varchar(45) default NULL,
  `sendDate` date default NULL,
  `alertText` text,
  PRIMARY KEY  (`resourceAlertID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



ALTER TABLE `_DATABASE_NAME_`.`ResourcePayment` ADD COLUMN `currencyCode` VARCHAR(3) AFTER `orderTypeID`;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`State`;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceStep`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceStep` (
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




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Step`;
CREATE TABLE  `_DATABASE_NAME_`.`Step` (
  `stepID` int(11) NOT NULL auto_increment,
  `priorStepID` int(11) default NULL,
  `stepName` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  `workflowID` int(11) default NULL,
  `displayOrderSequence` int(10) unsigned default NULL,
  PRIMARY KEY  (`stepID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `_DATABASE_NAME_`.`User` ADD COLUMN `emailAddress` VARCHAR(200) AFTER `accountTabIndicator`;





DROP TABLE IF EXISTS `_DATABASE_NAME_`.`UserGroup`;
CREATE TABLE  `_DATABASE_NAME_`.`UserGroup` (
  `userGroupID` int(11) NOT NULL auto_increment,
  `groupName` varchar(200) default NULL,
  `emailAddress` varchar(200) default NULL,
  `emailText` varchar(2000) default NULL,
  PRIMARY KEY  (`userGroupID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`UserGroupLink`;
CREATE TABLE  `_DATABASE_NAME_`.`UserGroupLink` (
  `userGroupLinkID` int(11) NOT NULL auto_increment,
  `loginID` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  PRIMARY KEY  (`userGroupLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Workflow`;
CREATE TABLE  `_DATABASE_NAME_`.`Workflow` (
  `workflowID` int(11) NOT NULL auto_increment,
  `workflowName` varchar(200) default NULL,
  `resourceFormatIDValue` varchar(45) default NULL,
  `resourceTypeIDValue` varchar(45) default NULL,
  `acquisitionTypeIDValue` varchar(45) default NULL,
  PRIMARY KEY  (`workflowID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;





ALTER TABLE `_DATABASE_NAME_`.`Alias` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_aliasTypeID`(`aliasTypeID`),
 ADD INDEX `Index_All`(`resourceID`, `aliasTypeID`);
 
 
ALTER TABLE `_DATABASE_NAME_`.`Resource` ADD INDEX `Index_createDate`(`createDate`),
 ADD INDEX `Index_createLoginID`(`createLoginID`),
 ADD INDEX `Index_titleText`(`titleText`),
 ADD INDEX `Index_isbnOrISSN`(`isbnOrISSN`),
 ADD INDEX `Index_statusID`(`statusID`),
 ADD INDEX `Index_resourceTypeID`(`resourceTypeID`),
 ADD INDEX `Index_resourceFormatID`(`resourceFormatID`),
 ADD INDEX `Index_acquisitionTypeID`(`authenticationTypeID`),
 ADD INDEX `Index_All`(`createDate`, `createLoginID`, `titleText`, `isbnOrISSN`, `statusID`, `resourceTypeID`, `resourceFormatID`, `acquisitionTypeID`);
 

ALTER TABLE `_DATABASE_NAME_`.`ResourcePayment` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_fundName`(`fundName`),
 ADD INDEX `Index_All`(`resourceID`, `fundName`); 
 

ALTER TABLE `_DATABASE_NAME_`.`ResourceNote` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_noteTypeID`(`noteTypeID`),
 ADD INDEX `Index_All`(`resourceID`, `noteTypeID`);
 


ALTER TABLE `_DATABASE_NAME_`.`ResourceOrganizationLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_organizationID`(`organizationID`),
 ADD INDEX `Index_All`(`resourceID`, `organizationID`);



ALTER TABLE `_DATABASE_NAME_`.`ResourcePurchaseSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_purchaseSiteID`(`purchaseSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `purchaseSiteID`);

ALTER TABLE `_DATABASE_NAME_`.`ResourceAdministeringSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_administeringSiteID`(`administeringSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `administeringSiteID`);
 

ALTER TABLE `_DATABASE_NAME_`.`ResourceAuthorizedSiteLink` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_authorizedSiteID`(`authorizedSiteID`),
 ADD INDEX `Index_All`(`resourceID`, `authorizedSiteID`); 
 
 

 
ALTER TABLE `_DATABASE_NAME_`.`ResourceRelationship` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_relatedResourceID`(`relatedResourceID`),
 ADD INDEX `Index_All`(`resourceID`, `relatedResourceID`);
 
 





INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('USD', 'United States Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('EUR', 'Euro');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('GBP', 'Great Britain (UK) Pound');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('CAD', 'Canadian Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('ARS', 'Argentine Peso');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('AUD', 'Australian Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('SEK', 'Swedish Krona');


INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Initial Note');


DELETE FROM `_DATABASE_NAME_`.`Status`;
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('In Progress');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Completed');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Saved');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Archived');




INSERT INTO `_DATABASE_NAME_`.`Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (1, '2', '','1');

INSERT INTO `_DATABASE_NAME_`.`Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (2, '2', '','2');



INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (1, 'Access');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (2, 'Licensing');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (3, 'Funding Approval');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (4, 'Acquisitions');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (5, 'Receipt');


INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (1, NULL, 'Funding Approval', 3, 1, 1);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (2, NULL, 'Licensing', 2, 1, 2);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (3, 2, 'Order Processing', 4, 1, 3);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (4, 3, 'Activation', 1, 1, 4);

INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (5, NULL, 'Licensing', 2, 2, 1);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (6, NULL, 'Activation', 1, 2, 2);








 
 
 