ALTER TABLE `Resource`
  CHANGE COLUMN `subscriptionStartDate` `currentStartDate` date default NULL,
  CHANGE COLUMN `subscriptionEndDate` `currentEndDate` date default NULL;

ALTER TABLE `ResourcePayment`
  ADD COLUMN `year` varchar(20) default NULL,
  ADD COLUMN `subscriptionStartDate` date default NULL,
  ADD COLUMN `subscriptionEndDate` date default NULL,
  ADD COLUMN `costDetailsID` int(11) default NULL,
  ADD COLUMN `costNote` text,
  ADD COLUMN `invoiceNum` varchar(20);

DROP TABLE IF EXISTS `CostDetails`;
CREATE TABLE `CostDetails` (
  `costDetailsID` int(11) NOT NULL AUTO_INCREMENT,
  `shortName` varchar(200) NOT NULL,
  PRIMARY KEY (`costDetailsID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `ResourcePayment`
  DROP INDEX `Index_All`,
  ADD INDEX `Index_year`(`year`),
  ADD INDEX `Index_costDetailsID`(`costDetailsID`),
  ADD INDEX `Index_invoiceNum`(`invoiceNum`),
  ADD INDEX `Index_All`(`resourceID`, `fundName`, `year`, `costDetailsID`, `invoiceNum`);

ALTER TABLE `CostDetails`
  ADD INDEX `costDetailsID`(`costDetailsID`),
  ADD INDEX `Index_shortName`(`shortName`),
  ADD INDEX `Index_All`(`costDetailsID`, `shortName`);

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

