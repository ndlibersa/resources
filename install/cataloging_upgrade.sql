ALTER TABLE  `Resource` ADD  `recordSetIdentifier` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `hasOclcHoldings` varchar( 10 ) NULL DEFAULT NULL ,
ADD  `numberRecordsAvailable` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `numberRecordsLoaded` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `bibSourceURL` VARCHAR( 2000 ) NULL DEFAULT NULL ,
ADD  `catalogingType` VARCHAR( 45 ) NULL DEFAULT NULL ,
ADD  `catalogingStatus` VARCHAR( 45 ) NULL DEFAULT NULL

ALTER TABLE `Alias` ADD INDEX `shortName` ( `shortName` ) 

ALTER TABLE `ResourceStep` ADD INDEX `resourceID` ( `resourceID` ) 

ALTER TABLE `ResourceFormat` ADD INDEX `shortName` ( `shortName` ) 
ALTER TABLE `ResourceType` ADD INDEX `shortName` ( `shortName` ) 
ALTER TABLE `Status` ADD INDEX `shortName` ( `shortName` ) 