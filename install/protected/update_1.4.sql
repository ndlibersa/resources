--ALTER DATABASE `_DATABASE_NAME_` CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AccessMethod` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AcquisitionType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AdministeringSite` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AlertDaysInAdvance` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AlertEmailAddress` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Alias` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AliasType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Attachment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AttachmentType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AuthenticationType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `AuthorizedSite` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CatalogingType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CatalogingStatus` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Contact` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ContactRole` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ContactRoleProfile` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Currency` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ExternalLogin` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ExternalLoginType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `IsbnOrIssn` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `LicenseStatus` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `NoteType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `OrderType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Organization` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `OrganizationRole` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Privilege` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `PurchaseSite` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `RelationshipType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Resource` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceAdministeringSiteLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceAlert` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceAuthorizedSiteLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceFormat` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceLicenseLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceLicenseStatus` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceNote` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceOrganizationLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourcePayment` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourcePurchaseSiteLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceRelationship` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceStep` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceType` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Status` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Step` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `StorageLocation` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `User` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `UserGroup` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `UserGroupLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `UserLimit` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Workflow` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `GeneralSubject` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `DetailedSubject` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `GeneralDetailSubjectLink` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ResourceSubject` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CostDetails` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `Issue`;
CREATE TABLE `Issue` (
  `issueID` int(11) NOT NULL AUTO_INCREMENT,
  `creatorID` varchar(20) NOT NULL,
  `subjectText` varchar(80) NOT NULL,
  `bodyText` text NOT NULL,
  `reminderInterval` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateClosed` datetime DEFAULT NULL,
  `resolutionText` text,
  PRIMARY KEY (`issueID`),
  KEY `creatorID` (`creatorID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `IssueRelationship`;
CREATE TABLE `IssueRelationship` (
  `issueRelationshipID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `entityID` int(11) NOT NULL,
  `entityTypeID` int(11) NOT NULL,
  PRIMARY KEY (`issueRelationshipID`),
  KEY `issueID` (`issueID`,`entityID`,`entityTypeID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueEntityType`;
CREATE TABLE `IssueEntityType` (
  `entityTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `entityName` varchar(80) NOT NULL,
  PRIMARY KEY (`entityTypeID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueContact`;
CREATE TABLE `IssueContact` (
  `issueContactID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  PRIMARY KEY (`issueContactID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueEmail`;
CREATE TABLE `IssueEmail` (
  `issueEmailID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  PRIMARY KEY (`IssueEmailID`),
  KEY `IssueID` (`IssueID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Downtime`;
CREATE TABLE IF NOT EXISTS `Downtime` (
  `downtimeID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) DEFAULT NULL,
  `entityID` int(11) NOT NULL,
  `entityTypeID` int(11) NOT NULL DEFAULT '2',
  `creatorID` varchar(80) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `downtimeTypeID` int(11) NOT NULL,
  `note` TEXT DEFAULT NULL,
   PRIMARY KEY (`downtimeID`),
   KEY `IssueID` (`IssueID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `DowntimeType`;
CREATE TABLE IF NOT EXISTS `DowntimeType` (
  `downtimeTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(80) NOT NULL,
  PRIMARY KEY (`downtimeTypeID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT=1;

