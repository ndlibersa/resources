
DROP TABLE IF EXISTS `Downtime`;
CREATE TABLE IF NOT EXISTS `Downtime` (
`downtimeID` int(11) NOT NULL,
  	`issueID` int(11) DEFAULT NULL,
  	`downtimeStartdate` datetime NOT NULL,
  	`downtimeEnddate` datetime DEFAULT NULL,
  	`downtimeType` int(11) NOT NULL,
  	PRIMARY KEY (`downtimeID`),
  	KEY `IssueID` (`IssueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `DowntimeType`;
CREATE TABLE IF NOT EXISTS `DowntimeType` (
	`downtimeTypeID` int(11) NOT NULL,
	`downtimeTypeName` varchar(80) NOT NULL,
	PRIMARY KEY (`downtimeTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;