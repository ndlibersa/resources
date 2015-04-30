CREATE TABLE  `IsbnOrIssn` (
  `isbnOrIssnID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) default NULL,
  `isbnOrIssn` varchar(45) NOT NULL,
  PRIMARY KEY  (`isbnOrIssnID`),
  KEY `resourceID` (`resourceID`),
  KEY `isbnOrIssn` (`isbnOrIssn`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO IsbnOrIssn (resourceID, isbnOrIssn) SELECT resourceID, isbnOrIssn FROM Resource;

ALTER TABLE `Resource` DROP `isbnOrISSN`;

