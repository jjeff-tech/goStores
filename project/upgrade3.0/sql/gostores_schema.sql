ALTER TABLE goStores_BillingMain ADD vExistDomainFlag int(1) DEFAULT 0 NULL;
ALTER TABLE `goStores_ProductLookup` ADD COLUMN `bluedogdetails` VARCHAR(250) NULL DEFAULT NULL AFTER `nTransactionSession`;
ALTER TABLE `goStores_ProductLookup` ADD COLUMN `inventory_source_status` TINYINT NULL DEFAULT '0' AFTER `bluedogdetails`;

CREATE TABLE `goStores_distributors` (
	`nDistibutorId` INT NOT NULL AUTO_INCREMENT,
	`vDistributorName` VARCHAR(50) NOT NULL DEFAULT '0',
	`vDistributorImage` INT NOT NULL DEFAULT '0',
	`vDistributorLink` VARCHAR(50) NOT NULL DEFAULT '0',
	`vDistributorDesc` TEXT NOT NULL DEFAULT '0',
	`vDistributorStatus` ENUM('1','0') NOT NULL DEFAULT '1',
	PRIMARY KEY (`nDistibutorId`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

CREATE TABLE `goStores_inventorysource_plan` (
	`nInvsPid` INT NOT NULL AUTO_INCREMENT,
	`nPId` INT NOT NULL,
	`nUserId` INT NOT NULL,
	`dateStart` DATE NOT NULL,
	`dateEnd` DATE NOT NULL,
	`createdDate` DATETIME NOT NULL,
	`cronAttempt` TINYINT NULL DEFAULT '0',
	`lastCronDate` DATE NOT NULL,
	`nStatus` TINYINT NULL DEFAULT '0',
	PRIMARY KEY (`nInvsPid`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

ALTER TABLE `goStores_inventorysource_plan` ADD COLUMN `updatedDate` DATETIME NOT NULL AFTER `createdDate`;
CREATE TABLE `goStores_inventorysource_plan_invoice` (
	`nInvsInvId` INT NOT NULL AUTO_INCREMENT,
	`nInvsPid` INT NOT NULL,
	`invDateStart` DATE NOT NULL,
	`invDateEnd` DATE NOT NULL,
	`InvCreatedDate` DATETIME NOT NULL,
	`nAmount` FLOAT NOT NULL,
	`nDuration` INT NOT NULL,
	`nTransactionDetails` TEXT NOT NULL,
	PRIMARY KEY (`nInvsInvId`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;



ALTER TABLE `goStores_inventorysource_plan_invoice` CHANGE COLUMN `nTransactionDetails` `nTransactionId` TEXT NOT NULL AFTER `nDuration`;
ALTER TABLE `goStores_inventorysource_plan_invoice` CHANGE COLUMN `nTransactionId` `nTransactionId` VARCHAR(250) NOT NULL AFTER `nDuration`;
ALTER TABLE `goStores_User` ADD COLUMN `nShopperId` VARCHAR(250) NULL DEFAULT NULL AFTER `nRefId`;
ALTER TABLE `goStores_payments` ADD `webhookdata` TEXT NULL AFTER `vTransactionId`;

