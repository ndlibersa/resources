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
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`downtimeTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;