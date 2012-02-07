ALTER TABLE  `Resource` ADD  `recordSetIdentifier` VARCHAR( 45 ) NOT NULL ,
ADD  `hasOclcHoldings` TINYINT( 1 ) NOT NULL ,
ADD  `numberLoaded` INT( 11 ) NOT NULL ,
ADD  `bibSourceURL` VARCHAR( 2000 ) NOT NULL ,
ADD  `catalogingType` VARCHAR( 45 ) NOT NULL ,
ADD  `catalogingStatus` VARCHAR( 45 ) NOT NULL