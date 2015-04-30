DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AccessMethod`;
CREATE TABLE  `_DATABASE_NAME_`.`AccessMethod` (
  `accessMethodID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`accessMethodID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AcquisitionType`;
CREATE TABLE  `_DATABASE_NAME_`.`AcquisitionType` (
  `acquisitionTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`acquisitionTypeID`),
  UNIQUE KEY `acquisitionTypeID` (`acquisitionTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AdministeringSite`;
CREATE TABLE  `_DATABASE_NAME_`.`AdministeringSite` (
  `administeringSiteID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`administeringSiteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AlertDaysInAdvance`;
CREATE TABLE  `_DATABASE_NAME_`.`AlertDaysInAdvance` (
  `alertDaysInAdvanceID` int(11) NOT NULL auto_increment,
  `daysInAdvanceNumber` int(11) default NULL,
  PRIMARY KEY  (`alertDaysInAdvanceID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AlertEmailAddress`;
CREATE TABLE  `_DATABASE_NAME_`.`AlertEmailAddress` (
  `alertEmailAddressID` int(11) NOT NULL auto_increment,
  `emailAddress` varchar(200) default NULL,
  PRIMARY KEY  (`alertEmailAddressID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Alias`;
CREATE TABLE  `_DATABASE_NAME_`.`Alias` (
  `aliasID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `aliasTypeID` int(11) default NULL,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`aliasID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AliasType`;
CREATE TABLE  `_DATABASE_NAME_`.`AliasType` (
  `aliasTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`aliasTypeID`),
  UNIQUE KEY `aliasTypeID` (`aliasTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Attachment`;
CREATE TABLE  `_DATABASE_NAME_`.`Attachment` (
  `attachmentID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `attachmentTypeID` int(11) default NULL,
  `shortName` varchar(200) default NULL,
  `descriptionText` text,
  `attachmentURL` varchar(200) default NULL,
  PRIMARY KEY  (`attachmentID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AttachmentType`;
CREATE TABLE  `_DATABASE_NAME_`.`AttachmentType` (
  `attachmentTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`attachmentTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AuthenticationType`;
CREATE TABLE  `_DATABASE_NAME_`.`AuthenticationType` (
  `authenticationTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  USING BTREE (`authenticationTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`AuthorizedSite`;
CREATE TABLE  `_DATABASE_NAME_`.`AuthorizedSite` (
  `authorizedSiteID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`authorizedSiteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`CatalogingType`;
CREATE TABLE  `_DATABASE_NAME_`.`CatalogingType` (
  `catalogingTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`catalogingTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`CatalogingStatus`;
CREATE TABLE  `_DATABASE_NAME_`.`CatalogingStatus` (
  `catalogingStatusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`catalogingStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Contact`;
CREATE TABLE  `_DATABASE_NAME_`.`Contact` (
  `contactID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) NOT NULL,
  `lastUpdateDate` date default NULL,
  `name` varchar(150) default NULL,
  `title` varchar(150) default NULL,
  `addressText` varchar(300) default NULL,
  `phoneNumber` varchar(50) default NULL,
  `altPhoneNumber` varchar(50) default NULL,
  `faxNumber` varchar(50) default NULL,
  `emailAddress` varchar(100) default NULL,
  `archiveDate` date default NULL,
  `noteText` text,
  PRIMARY KEY  (`contactID`),
  UNIQUE KEY `contactID` (`contactID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ContactRole`;
CREATE TABLE  `_DATABASE_NAME_`.`ContactRole` (
  `contactRoleID` int(11) NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  (`contactRoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ContactRoleProfile`;
CREATE TABLE  `_DATABASE_NAME_`.`ContactRoleProfile` (
  `contactID` int(10) unsigned NOT NULL,
  `contactRoleID` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`contactID`,`contactRoleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Currency`;
CREATE TABLE  `_DATABASE_NAME_`.`Currency` (
  `currencyCode` varchar(3) NOT NULL,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`currencyCode`),
  UNIQUE KEY `currencyCode` (`currencyCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ExternalLogin`;
CREATE TABLE  `_DATABASE_NAME_`.`ExternalLogin` (
  `externalLoginID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `externalLoginTypeID` int(11) default NULL,
  `updateDate` date default NULL,
  `loginURL` varchar(150) default NULL,
  `emailAddress` varchar(150) default NULL,
  `username` varchar(50) default NULL,
  `password` varchar(50) default NULL,
  `noteText` text,
  PRIMARY KEY  (`externalLoginID`),
  UNIQUE KEY `externalLoginID` (`externalLoginID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ExternalLoginType`;
CREATE TABLE  `_DATABASE_NAME_`.`ExternalLoginType` (
  `externalLoginTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`externalLoginTypeID`),
  UNIQUE KEY `externalLoginTypeID` (`externalLoginTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IsbnOrIssn`;
CREATE TABLE  `_DATABASE_NAME_`.`IsbnOrIssn` (
  `isbnOrIssnID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `isbnOrIssn` varchar(45) NOT NULL,
  PRIMARY KEY  (`isbnOrIssnID`),
  KEY `resourceID` (`resourceID`),
  KEY `isbnOrIssn` (`isbnOrIssn`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`LicenseStatus`;
CREATE TABLE  `_DATABASE_NAME_`.`LicenseStatus` (
  `licenseStatusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`licenseStatusID`),
  UNIQUE KEY `licenseStatusID` (`licenseStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`NoteType`;
CREATE TABLE  `_DATABASE_NAME_`.`NoteType` (
  `noteTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`noteTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`OrderType`;
CREATE TABLE  `_DATABASE_NAME_`.`OrderType` (
  `orderTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`orderTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Organization`;
CREATE TABLE  `_DATABASE_NAME_`.`Organization` (
  `organizationID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  USING BTREE (`organizationID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`OrganizationRole`;
CREATE TABLE  `_DATABASE_NAME_`.`OrganizationRole` (
  `organizationRoleID` int(11) NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  (`organizationRoleID`),
  UNIQUE KEY `organizationRoleID` (`organizationRoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Privilege`;
CREATE TABLE  `_DATABASE_NAME_`.`Privilege` (
  `privilegeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  USING BTREE (`privilegeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`PurchaseSite`;
CREATE TABLE  `_DATABASE_NAME_`.`PurchaseSite` (
  `purchaseSiteID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`purchaseSiteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`RelationshipType`;
CREATE TABLE  `_DATABASE_NAME_`.`RelationshipType` (
  `relationshipTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`relationshipTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Resource`;
CREATE TABLE  `_DATABASE_NAME_`.`Resource` (
  `resourceID` int(11) NOT NULL auto_increment,
  `createDate` date default NULL,
  `createLoginID` varchar(45) default NULL,
  `updateDate` date default NULL,
  `updateLoginID` varchar(45) default NULL,
  `archiveDate` date default NULL,
  `archiveLoginID` varchar(45) default NULL,
  `workflowRestartDate` date default NULL,
  `workflowRestartLoginID` varchar(45) default NULL,
  `titleText` varchar(200) default NULL,
  `descriptionText` text,
  `statusID` int(11) default NULL,
  `resourceTypeID` int(11) default NULL,
  `resourceFormatID` int(11) default NULL,
  `orderNumber` varchar(45) default NULL,
  `systemNumber` varchar(45) default NULL,
  `currentStartDate` date default NULL,
  `currentEndDate` date default NULL,
  `subscriptionAlertEnabledInd` int(10) unsigned default NULL,
  `userLimitID` int(11) default NULL,
  `resourceURL` varchar(2000) default NULL,
  `authenticationUserName` varchar(200) default NULL,
  `authenticationPassword` varchar(200) default NULL,
  `storageLocationID` int(11) default NULL,
  `registeredIPAddresses` varchar(200) default NULL,
  `acquisitionTypeID` int(10) unsigned default NULL,
  `authenticationTypeID` int(10) unsigned default NULL,
  `accessMethodID` int(10) unsigned default NULL,
  `providerText` varchar(200) default NULL,
  `recordSetIdentifier` VARCHAR( 45 ) DEFAULT NULL ,
  `hasOclcHoldings` varchar( 10 ) DEFAULT NULL ,
  `numberRecordsAvailable` VARCHAR( 45 ) DEFAULT NULL ,
  `numberRecordsLoaded` VARCHAR( 45 ) DEFAULT NULL ,
  `bibSourceURL` VARCHAR( 2000 ) DEFAULT NULL ,
  `catalogingTypeID` int(11) DEFAULT NULL,
  `catalogingStatusID` int(11) DEFAULT NULL,
  `coverageText` VARCHAR(1000) NULL DEFAULT NULL,
  `resourceAltURL` VARCHAR(2000) NULL DEFAULT NULL,
  PRIMARY KEY  (`resourceID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceAdministeringSiteLink`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceAdministeringSiteLink` (
  `resourceAdministeringSiteLinkID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `administeringSiteID` int(11) default NULL,
  PRIMARY KEY  (`resourceAdministeringSiteLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceAlert`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceAlert` (
  `resourceAlertID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `loginID` varchar(45) default NULL,
  `sendDate` date default NULL,
  `alertText` text,
  PRIMARY KEY  (`resourceAlertID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceAuthorizedSiteLink`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceAuthorizedSiteLink` (
  `resourceAuthorizedSiteLinkID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `authorizedSiteID` int(11) default NULL,
  PRIMARY KEY  (`resourceAuthorizedSiteLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceFormat`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceFormat` (
  `resourceFormatID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  USING BTREE (`resourceFormatID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceLicenseLink`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceLicenseLink` (
  `resourceLicenseLinkID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `licenseID` int(11) default NULL,
  PRIMARY KEY  (`resourceLicenseLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceLicenseStatus`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceLicenseStatus` (
  `resourceLicenseStatusID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `licenseStatusID` int(11) default NULL,
  `licenseStatusChangeDate` datetime default NULL,
  `licenseStatusChangeLoginID` varchar(45) default NULL,
  PRIMARY KEY  (`resourceLicenseStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceNote`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceNote` (
  `resourceNoteID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `noteTypeID` int(11) default NULL,
  `tabName` varchar(45) default NULL,
  `updateDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updateLoginID` varchar(45) default NULL,
  `noteText` text,
  PRIMARY KEY  (`resourceNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceOrganizationLink`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceOrganizationLink` (
  `resourceOrganizationLinkID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `organizationID` int(11) default NULL,
  `organizationRoleID` int(11) default NULL,
  PRIMARY KEY  (`resourceOrganizationLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourcePayment`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourcePayment` (
  `resourcePaymentID` int(11) NOT NULL auto_increment,
  `resourceID` int(10) unsigned NOT NULL,
  `fundName` varchar(200) default NULL,
  `selectorLoginID` varchar(45) default NULL,
  `paymentAmount` int(10) unsigned default NULL,
  `orderTypeID` int(10) unsigned default NULL,
  `currencyCode` varchar(3) NOT NULL,
  `year` varchar(20) default NULL,
  `subscriptionStartDate` date default NULL,
  `subscriptionEndDate` date default NULL,
  `costDetailsID` int(11) default NULL,
  `costNote` text,
  `invoiceNum` varchar(20),
  PRIMARY KEY  (`resourcePaymentID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourcePurchaseSiteLink`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourcePurchaseSiteLink` (
  `resourcePurchaseSiteLinkID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `purchaseSiteID` int(11) default NULL,
  PRIMARY KEY  (`resourcePurchaseSiteLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceRelationship`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceRelationship` (
  `resourceRelationshipID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `relatedResourceID` int(11) default NULL,
  `relationshipTypeID` int(11) default NULL,
  PRIMARY KEY  (`resourceRelationshipID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceType`;
CREATE TABLE  `_DATABASE_NAME_`.`ResourceType` (
  `resourceTypeID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  `includeStats` boolean default NULL,
  PRIMARY KEY  (`resourceTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Status`;
CREATE TABLE  `_DATABASE_NAME_`.`Status` (
  `statusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  (`statusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Step`;
CREATE TABLE  `_DATABASE_NAME_`.`Step` (
  `stepID` int(11) NOT NULL auto_increment,
  `priorStepID` int(11) default NULL,
  `stepName` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  `workflowID` int(11) default NULL,
  `displayOrderSequence` int(10) unsigned default NULL,
  PRIMARY KEY  (`stepID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`StorageLocation`;
CREATE TABLE  `_DATABASE_NAME_`.`StorageLocation` (
  `storageLocationID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`storageLocationID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`User`;
CREATE TABLE  `_DATABASE_NAME_`.`User` (
  `loginID` varchar(50) NOT NULL default '',
  `lastName` varchar(45) default NULL,
  `firstName` varchar(45) default NULL,
  `privilegeID` int(10) unsigned default NULL,
  `accountTabIndicator` int(1) unsigned default '0',
  `emailAddress` varchar(200) default NULL,
  PRIMARY KEY  (`loginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`UserGroup`;
CREATE TABLE  `_DATABASE_NAME_`.`UserGroup` (
  `userGroupID` int(11) NOT NULL auto_increment,
  `groupName` varchar(200) default NULL,
  `emailAddress` varchar(200) default NULL,
  `emailText` varchar(2000) default NULL,
  PRIMARY KEY  (`userGroupID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `_DATABASE_NAME_`.`UserGroupLink`;
CREATE TABLE  `_DATABASE_NAME_`.`UserGroupLink` (
  `userGroupLinkID` int(11) NOT NULL auto_increment,
  `loginID` varchar(200) default NULL,
  `userGroupID` int(11) default NULL,
  PRIMARY KEY  (`userGroupLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `_DATABASE_NAME_`.`UserLimit`;
CREATE TABLE  `_DATABASE_NAME_`.`UserLimit` (
  `userLimitID` int(11) NOT NULL auto_increment,
  `shortName` varchar(45) default NULL,
  PRIMARY KEY  (`userLimitID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Workflow`;
CREATE TABLE  `_DATABASE_NAME_`.`Workflow` (
  `workflowID` int(11) NOT NULL auto_increment,
  `workflowName` varchar(200) default NULL,
  `resourceFormatIDValue` varchar(45) default NULL,
  `resourceTypeIDValue` varchar(45) default NULL,
  `acquisitionTypeIDValue` varchar(45) default NULL,
  PRIMARY KEY  (`workflowID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`GeneralSubject`;
CREATE TABLE `_DATABASE_NAME_`.`GeneralSubject` (
  `generalSubjectID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`generalSubjectID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`DetailedSubject`;
CREATE TABLE `_DATABASE_NAME_`.`DetailedSubject` (
  `detailedSubjectID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`detailedSubjectID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`GeneralDetailSubjectLink`;
CREATE TABLE `_DATABASE_NAME_`.`GeneralDetailSubjectLink` (
  `generalDetailSubjectLinkID` int(11) NOT NULL AUTO_INCREMENT,
  `generalSubjectID` int(11) DEFAULT NULL,
  `detailedSubjectID` int(11) DEFAULT NULL,
  PRIMARY KEY (`generalDetailSubjectLinkID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`ResourceSubject`;
CREATE TABLE `_DATABASE_NAME_`.`ResourceSubject` (
  `resourceSubjectID` int(11) NOT NULL AUTO_INCREMENT,
  `resourceID` int(11) DEFAULT NULL,
  `generalDetailSubjectLinkID` int(11) DEFAULT NULL,
  PRIMARY KEY (`resourceSubjectID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`CostDetails`;
CREATE TABLE `_DATABASE_NAME_`.`CostDetails` (
  `costDetailsID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(200) NOT NULL,
  PRIMARY KEY (`costDetailsID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `_DATABASE_NAME_`.`Alias` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_aliasTypeID`(`aliasTypeID`),
 ADD INDEX `shortName` ( `shortName` ),
 ADD INDEX `Index_All`(`resourceID`, `aliasTypeID`);
 
 
ALTER TABLE `_DATABASE_NAME_`.`Resource` ADD INDEX `Index_createDate`(`createDate`),
 ADD INDEX `Index_createLoginID`(`createLoginID`),
 ADD INDEX `Index_titleText`(`titleText`),
 ADD INDEX `Index_statusID`(`statusID`),
 ADD INDEX `Index_resourceTypeID`(`resourceTypeID`),
 ADD INDEX `Index_resourceFormatID`(`resourceFormatID`),
 ADD INDEX `Index_acquisitionTypeID`(`authenticationTypeID`),
 ADD INDEX `catalogingTypeID` ( `catalogingTypeID` ),
 ADD INDEX `catalogingStatusID` ( `catalogingStatusID` ),
 ADD INDEX `Index_All`(`createDate`, `createLoginID`, `titleText`, `statusID`, `resourceTypeID`, `resourceFormatID`, `acquisitionTypeID`);
 
ALTER TABLE `_DATABASE_NAME_`.`ResourceFormat` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `_DATABASE_NAME_`.`ResourcePayment` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_fundName`(`fundName`),
 ADD INDEX `Index_year`(`year`),
 ADD INDEX `Index_costDetailsID`(`costDetailsID`),
 ADD INDEX `Index_invoiceNum`(`invoiceNum`),
 ADD INDEX `Index_All`(`resourceID`, `fundName`, `year`, `costDetailsID`, `invoiceNum`); 
 

ALTER TABLE `_DATABASE_NAME_`.`ResourceNote` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_noteTypeID`(`noteTypeID`),
 ADD INDEX `Index_All`(`resourceID`, `noteTypeID`);
 
ALTER TABLE `_DATABASE_NAME_`.`ResourceStep` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `_DATABASE_NAME_`.`ResourceType` ADD INDEX `shortName` ( `shortName` );

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
 
ALTER TABLE `_DATABASE_NAME_`.`ResourceLicenseLink` ADD INDEX `resourceID` ( `resourceID` );

ALTER TABLE `_DATABASE_NAME_`.`ResourceLicenseStatus` ADD INDEX `resourceID` ( `resourceID` );
 
ALTER TABLE `_DATABASE_NAME_`.`ResourceRelationship` ADD INDEX `Index_resourceID`(`resourceID`),
 ADD INDEX `Index_relatedResourceID`(`relatedResourceID`),
 ADD INDEX `Index_All`(`resourceID`, `relatedResourceID`);
 
ALTER TABLE `_DATABASE_NAME_`.`Status` ADD INDEX `shortName` ( `shortName` );

ALTER TABLE `_DATABASE_NAME_`.`GeneralSubject` ADD INDEX `generalSubjectID` ( `generalSubjectID` );

ALTER TABLE `_DATABASE_NAME_`.`DetailedSubject` ADD INDEX `detailedSubjectID` ( `detailedSubjectID` );

ALTER TABLE `_DATABASE_NAME_`.`GeneralDetailSubjectLink` ADD INDEX `generalDetailSubjectLinkID` ( `generalDetailSubjectLinkID` ),
 ADD INDEX `Index_All` (`generalSubjectID` ASC, `detailedSubjectID` ASC), 
 ADD INDEX `Index_generalSubject` (`generalSubjectID` ASC), 
 ADD INDEX `Index_detailedSubject` (`detailedSubjectID` ASC) ;

ALTER TABLE `_DATABASE_NAME_`.`ResourceSubject` ADD INDEX `resourceSubjectID` ( `resourceSubjectID` ), 
 ADD INDEX `Index_All` (`resourceID` ASC, `generalDetailSubjectLinkID` ASC), 
 ADD INDEX `Index_ResourceID` (`resourceID` ASC), 
 ADD INDEX `Index_GeneralDetailLink` (`generalDetailSubjectLinkID` ASC) ;

ALTER TABLE `_DATABASE_NAME_`.`CostDetails` ADD INDEX `costDetailsID` ( `costDetailsID` ), 
 ADD INDEX `Index_shortName` (`shortName`),
 ADD INDEX `Index_All`(`costDetailsID`, `shortName`);

