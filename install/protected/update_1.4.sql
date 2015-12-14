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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueRelationship`;
CREATE TABLE `IssueRelationship` (
  `issueRelationshipID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `entityID` int(11) NOT NULL,
  `entityTypeID` int(11) NOT NULL,
  PRIMARY KEY (`issueRelationshipID`),
  KEY `issueID` (`issueID`,`entityID`,`entityTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueEntityType`;
CREATE TABLE `IssueEntityType` (
  `entityTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `entityName` varchar(80) NOT NULL,
  PRIMARY KEY (`entityTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueContact`;
CREATE TABLE `IssueContact` (
  `issueContactID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  PRIMARY KEY (`issueContactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `IssueEmail`;
CREATE TABLE `IssueEmail` (
  `issueEmailID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  PRIMARY KEY (`IssueEmailID`),
  KEY `IssueID` (`IssueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `DowntimeType`;
CREATE TABLE IF NOT EXISTS `DowntimeType` (
  `downtimeTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(80) NOT NULL,
  PRIMARY KEY (`downtimeTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;