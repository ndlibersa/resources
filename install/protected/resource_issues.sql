DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Issue`;
CREATE TABLE `_DATABASE_NAME_`.`Issue` (
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


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IssueRelationship`;
CREATE TABLE `_DATABASE_NAME_`.`IssueRelationship` (
  `issueRelationshipID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `entityID` int(11) NOT NULL,
  `entityTypeID` int(11) NOT NULL,
  PRIMARY KEY (`issueRelationshipID`),
  KEY `issueID` (`issueID`,`entityID`,`entityTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IssueEntityType`;
CREATE TABLE `_DATABASE_NAME_`.`IssueEntityType` (
  `entityTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `entityName` varchar(80) NOT NULL,
  PRIMARY KEY (`entityTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IssueContact`;
CREATE TABLE `_DATABASE_NAME_`.`IssueContact` (
  `issueContactID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  PRIMARY KEY (`issueContactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IssueEmail`;
CREATE TABLE `_DATABASE_NAME_`.`IssueEmail` (
  `issueEmailID` int(11) NOT NULL AUTO_INCREMENT,
  `issueID` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  PRIMARY KEY (`IssueEmailID`),
  KEY `IssueID` (`IssueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
