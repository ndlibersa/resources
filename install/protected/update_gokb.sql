DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Identifier`; /*replace IsbnOrIssn table which can be deleted*/
CREATE TABLE `_DATABASE_NAME_`.`Identifier` (
  `identifierID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) NOT NULL,
  `identifierTypeID` int(11) default NULL,
  `identifier` varchar(45) NOT NULL,
  PRIMARY KEY (`identifierID`),
  UNIQUE KEY `identifierID` (`identifierID`),
  KEY `resourceID` (`resourceID`),
  KEY `identifierTypeID` (`identifierTypeID`)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `Identifier` (`resourceID`, `identifier`)
SELECT `resourceID`, `isbnOrIssn` FROM `IsbnOrIssn`;
UPDATE `Identifier` SET `identifierTypeID`=1;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IsbnOrIssn`;

DROP TABLE IF EXISTS `_DATABASE_NAME_`.`IdentifierType`;
CREATE TABLE `_DATABASE_NAME_`.`IdentifierType` (
  `identifierTypeID` int(11) NOT NULL auto_increment,
  `identifierName` varchar(45) NOT NULL,
  PRIMARY KEY (`identifierTypeID`),
  UNIQUE KEY `identifierTypeID` (`identifierTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('Isxn');
INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('Issn');
INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('Isbn');
INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('eIssn');
INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('eIsbn');
INSERT INTO `_DATABASE_NAME_`.`IdentifierType` (identifierName) values('Gokb');




