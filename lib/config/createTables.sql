<?php

    // Update the STRUCTURE ONLY of all the tables in the ultiscape database

    CREATE DATABASE  IF NOT EXISTS `ultiscape`;
    USE `ultiscape`;

    --
    -- Table structure for table `admin`
    --

    CREATE TABLE IF NOT EXISTS `admin` (
    `adminId` varchar(17) NOT NULL,
    `username` varchar(20) NOT NULL,
    `password` varchar(64) NOT NULL,
    `email` varchar(64) NOT NULL,
    `surname` varchar(15) DEFAULT NULL,
    `firstName` varchar(25) NOT NULL,
    `lastName` varchar(25) NOT NULL,
    `profilePicture` varchar(17) DEFAULT NULL,
    `allowSignIn` tinyint(1) NOT NULL,
    `dateTimeJoined` datetime NOT NULL,
    `dateTimeLeft` datetime DEFAULT NULL,
    PRIMARY KEY (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `adminBusinessBridge`
    --

    CREATE TABLE IF NOT EXISTS `adminBusinessBridge` (
    `adminBusinessId` varchar(17) NOT NULL,
    `adminId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `adminCanManageTag` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanUploadDocument` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageBlog` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageSMS` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageEmail` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageServiceListing` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageQuoteRequest` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageCustomerService` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageTimeLog` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManagePayrollDue` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManagePayrollSatisfaction` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageCustomer` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageStaff` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageCrew` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageEquipment` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageChemical` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageJob` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageInvoice` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManagePayment` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanManageEstimate` tinyint(1) NOT NULL DEFAULT 1,
    `adminCanApproveEstimate` tinyint(1) NOT NULL DEFAULT 1,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`adminBusinessId`),
    KEY `adminBusinessBridgeAdminId` (`adminId`),
    KEY `adminBusinessBridgeBusinessId` (`businessId`),
    CONSTRAINT `adminBusinessBridgeAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`),
    CONSTRAINT `adminBusinessBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `adminLoginAttempt`
    --

    CREATE TABLE IF NOT EXISTS `adminLoginAttempt` (
    `adminLoginAttemptId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `adminId` varchar(17) DEFAULT NULL,
    `loginCode` varchar(17) NOT NULL,
    `dateTimeCodeUsed` datetime DEFAULT NULL,
    `clientIp` varchar(20) NOT NULL,
    `enteredUsername` varchar(25) NOT NULL,
    `enteredPassword` varchar(64) NOT NULL,
    `result` varchar(5) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`adminLoginAttemptId`),
    KEY `adminLoginAttemptBusinessId` (`businessId`),
    KEY `adminLoginAttemptAdminId` (`adminId`),
    CONSTRAINT `adminLoginAttemptAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`),
    CONSTRAINT `adminLoginAttemptBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `adminSavedLogin`
    --

    CREATE TABLE IF NOT EXISTS `adminSavedLogin` (
    `adminSavedLoginAttemptId` varchar(17) NOT NULL,
    `adminId` varchar(17) NOT NULL,
    `code` varchar(17) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`adminSavedLoginAttemptId`),
    KEY `adminSavedLoginAdminId` (`adminId`),
    CONSTRAINT `adminSavedLoginAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `blogPost`
    --

    CREATE TABLE IF NOT EXISTS `blogPost` (
    `blogPostId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `createdByAdminId` varchar(17) NOT NULL,
    `author` varchar(50) DEFAULT NULL,
    `title` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `bodyFile` varchar(17) NOT NULL,
    `imgFile` varchar(17) DEFAULT NULL,
    `published` tinyint(1) NOT NULL DEFAULT 0,
    `numViewsCache` int(11) NOT NULL DEFAULT 0,
    `dateTimeAdded` datetime NOT NULL,
    `dateTimeEdited` datetime DEFAULT NULL,
    PRIMARY KEY (`blogPostId`),
    KEY `blogPostBusinessId` (`businessId`),
    KEY `blogPostCreatedByAdminId` (`createdByAdminId`),
    CONSTRAINT `blogPostBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `blogPostCreatedByAdminId` FOREIGN KEY (`createdByAdminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `blogPostBlogTagBridge`
    --

    CREATE TABLE IF NOT EXISTS `blogPostBlogTagBridge` (
    `blogPostBlogTagId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `blogPostId` varchar(17) NOT NULL,
    `blogTagId` varchar(17) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`blogPostBlogTagId`),
    KEY `blogPostBlogTagBridgeBusinessId` (`businessId`),
    KEY `blogPostBlogTagBridgeBlogPostId` (`blogPostId`),
    KEY `blogPostBlogTagBridgeBlogTagId` (`blogTagId`),
    CONSTRAINT `blogPostBlogTagBridgeBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`),
    CONSTRAINT `blogPostBlogTagBridgeBlogTagId` FOREIGN KEY (`blogTagId`) REFERENCES `blogTag` (`blogTagId`),
    CONSTRAINT `blogPostBlogTagBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `blogPostReadToken`
    --

    CREATE TABLE IF NOT EXISTS `blogPostReadToken` (
    `blogPostReadTokenId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `blogPostId` varchar(17) NOT NULL,
    `token` varchar(17) NOT NULL,
    `dateTimeUsed` datetime NOT NULL,
    `clientIP` varchar(20) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`blogPostReadTokenId`),
    KEY `blogPostReadTokenBusinessId` (`businessId`),
    KEY `blogPostReadTokenBlogPostId` (`blogPostId`),
    CONSTRAINT `blogPostReadTokenBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`),
    CONSTRAINT `blogPostReadTokenBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `blogTag`
    --

    CREATE TABLE IF NOT EXISTS `blogTag` (
    `blogTagId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `createdByAdminId` varchar(17) NOT NULL,
    `name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `color` varchar(15) DEFAULT NULL,
    `imgFile` varchar(17) DEFAULT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`blogTagId`),
    KEY `blogTagBusinessId` (`businessId`),
    KEY `blogTagCreatedByAdminId` (`createdByAdminId`),
    CONSTRAINT `blogTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `blogTagCreatedByAdminId` FOREIGN KEY (`createdByAdminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `business`
    --

    CREATE TABLE IF NOT EXISTS `business` (
    `businessId` varchar(17) NOT NULL,
    `ownerAdminId` varchar(17) NOT NULL COMMENT 'FK',
    `displayName` varchar(100) NOT NULL,
    `adminDisplayName` varchar(100) NOT NULL,
    `fullLogoFile` varchar(17) DEFAULT NULL,
    `address` varchar(100) DEFAULT NULL,
    `state` varchar(50) DEFAULT NULL,
    `city` varchar(50) DEFAULT NULL,
    `zipCode` int(11) DEFAULT NULL,
    `phonePrefix` int(11) DEFAULT NULL,
    `phone1` int(11) DEFAULT NULL,
    `phone2` int(11) DEFAULT NULL,
    `phone3` int(11) DEFAULT NULL,
    `email` varchar(64) DEFAULT NULL,
    `currencySymbol` varchar(1) NOT NULL DEFAULT '$',
    `timeZone` varchar(30) NOT NULL,
    `modCust` tinyint(1) NOT NULL DEFAULT 0,
    `modInv` tinyint(1) NOT NULL DEFAULT 0,
    `modInvIncludePastBal` tinyint(1) NOT NULL DEFAULT 0,
    `modEst` tinyint(1) NOT NULL DEFAULT 0,
    `modEstExtName` varchar(20) DEFAULT NULL,
    `modProp` tinyint(1) NOT NULL DEFAULT 0,
    `modPropExtName` varchar(20) DEFAULT NULL,
    `modJobs` tinyint(1) NOT NULL DEFAULT 0,
    `modEquip` tinyint(1) NOT NULL DEFAULT 0,
    `modChem` tinyint(1) NOT NULL DEFAULT 0,
    `modStaff` tinyint(1) NOT NULL DEFAULT 0,
    `modStaffExtName` varchar(20) DEFAULT NULL,
    `modCrews` tinyint(1) NOT NULL DEFAULT 0,
    `modCrewsExtName` varchar(20) DEFAULT NULL,
    `modPayr` tinyint(1) NOT NULL DEFAULT 0,
    `modPayrSatLinkedToDue` tinyint(1) NOT NULL DEFAULT 0,
    `modPayrSalDefaultType` varchar(5) NOT NULL DEFAULT 'none',
    `modPayrSalBaseHourlyRate` float NOT NULL DEFAULT 0,
    `modPayrSalBaseJobPercent` int(11) NOT NULL,
    `modPayrSalBasePerJob` float NOT NULL,
    `docIdMin` int(11) NOT NULL DEFAULT 1,
    `docIdIsRandom` tinyint(1) NOT NULL DEFAULT 0,
    `invoiceTerm` int(11) DEFAULT NULL,
    `estimateValidity` int(11) DEFAULT NULL,
    `creditAlertIsEnabled` tinyint(1) NOT NULL DEFAULT 0,
    `creditAlertAmount` float NOT NULL DEFAULT 0,
    `autoApplyCredit` tinyint(1) NOT NULL DEFAULT 0,
    `balanceAlertIsEnabled` tinyint(1) NOT NULL DEFAULT 0,
    `balanceAlertAmount` float NOT NULL DEFAULT 0,
    `SZEnabled` tinyint(1) NOT NULL,
    `SZModInfoForStaffPage` tinyint(1) NOT NULL DEFAULT 0,
    `SZModInfoForStaffPageShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `SZModInfoForStaffPageBodyFile` varchar(17) DEFAULT NULL,
    `SZModPersInfo` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditName` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditPhone` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditEmail` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditAddress` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditUsername` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPersInfoAllowEditPassword` tinyint(1) NOT NULL DEFAULT 0,
    `SZModCrews` tinyint(1) NOT NULL DEFAULT 0,
    `SZModJobs` tinyint(1) NOT NULL DEFAULT 0,
    `SZModJobsShowCrewJobs` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPayr` tinyint(1) NOT NULL DEFAULT 0,
    `SZModPayrShowDetails` tinyint(1) NOT NULL DEFAULT 0,
    `SZModContactAdmin` tinyint(1) NOT NULL DEFAULT 0,
    `SZModQuit` tinyint(1) NOT NULL DEFAULT 0,
    `SZModQuitNoticeTerm` int(11) NOT NULL DEFAULT 0,
    `CPEnabled` tinyint(1) NOT NULL DEFAULT 0,
    `CPModHomeShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModHomeBodyFile` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTopBar` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTopBarShowLogo` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTopBarLogoFile` varchar(17) DEFAULT NULL,
    `CPModTopBarShowQuote` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTopBarShowNav` tinyint(1) NOT NULL DEFAULT 0,
    `CPModServices` tinyint(1) NOT NULL DEFAULT 0,
    `CPModServicesShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModServicesBodyFile` varchar(17) DEFAULT NULL,
    `CPModServicesShowList` tinyint(1) NOT NULL DEFAULT 0,
    `CPModContact` tinyint(1) NOT NULL DEFAULT 0,
    `CPModContactShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModContactBodyFile` varchar(17) DEFAULT NULL,
    `CPModContactShowForm` tinyint(1) NOT NULL DEFAULT 0,
    `CPModContactShowInfo` tinyint(1) NOT NULL DEFAULT 0,
    `CPModAbout` tinyint(1) NOT NULL DEFAULT 0,
    `CPModAboutShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModAboutBodyFile` varchar(17) DEFAULT NULL,
    `CPModQuote` tinyint(1) NOT NULL DEFAULT 0,
    `CPModQuoteShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModQuoteBodyFile` varchar(17) DEFAULT NULL,
    `CPModQuoteShowForm` tinyint(1) NOT NULL DEFAULT 0,
    `CPModBlog` tinyint(1) NOT NULL DEFAULT 0,
    `CPModBlogShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModBlogBodyFile` varchar(17) DEFAULT NULL,
    `CPModBlogShowPosts` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTOS` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTOSShowBody` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTOSBodyFile` varchar(17) DEFAULT NULL,
    `CPModTOSShowInvTerm` tinyint(1) NOT NULL DEFAULT 0,
    `CPModTOSShowEstTerm` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZ` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZJobs` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZInvoices` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZEstimates` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfo` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditName` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditPhone` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditEmail` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditAddress` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditUsername` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZPersInfoAllowEditPassword` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZContactStaff` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZContactStaffAllowOwnerContact` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZContactStaffAllowAdminContact` tinyint(1) NOT NULL DEFAULT 0,
    `CPModCZServiceRequest` tinyint(1) NOT NULL DEFAULT 0,
    `isArchived` tinyint(1) NOT NULL DEFAULT 0,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`businessId`),
    KEY `businessOwnerAdminId` (`ownerAdminId`),
    CONSTRAINT `businessOwnerAdminId` FOREIGN KEY (`ownerAdminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `customer`
    --

    CREATE TABLE IF NOT EXISTS `customer` (
    `customerId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `addedByAdminId` varchar(17) NOT NULL,
    `surname` varchar(10) NOT NULL,
    `firstName` varchar(20) NOT NULL,
    `lastName` varchar(20) NOT NULL,
    `billAddress` int(11) DEFAULT NULL,
    `billCity` int(11) DEFAULT NULL,
    `billState` int(11) DEFAULT NULL,
    `billZipCode` int(11) DEFAULT NULL,
    `creditCache` float NOT NULL DEFAULT 0,
    `overrideCreditAlertIsEnabled` tinyint(1) DEFAULT NULL,
    `overrideCreditAlertAmount` float DEFAULT NULL,
    `overrideAutoApplyCredit` tinyint(1) DEFAULT NULL,
    `balanceCache` float NOT NULL DEFAULT 0,
    `overrideBalanceAlertIsEnabled` tinyint(1) DEFAULT NULL,
    `overrideBalanceAlertAmount` float DEFAULT NULL,
    `allowCZSignIn` tinyint(1) NOT NULL DEFAULT 0,
    `password` varchar(64) NOT NULL,
    `discountPercent` int(11) DEFAULT NULL,
    `overridePaymentTerm` int(11) DEFAULT NULL,
    `notes` text NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`customerId`),
    UNIQUE KEY `customerPassword` (`password`) USING BTREE,
    KEY `customerBusinessId` (`businessId`),
    KEY `customerAddedByAdminId` (`addedByAdminId`),
    CONSTRAINT `customerAddedByAdminId` FOREIGN KEY (`addedByAdminId`) REFERENCES `admin` (`adminId`),
    CONSTRAINT `customerBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `customerLoginAttempt`
    --

    CREATE TABLE IF NOT EXISTS `customerLoginAttempt` (
    `customerLoginAttemptId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `customerId` varchar(17) DEFAULT NULL,
    `loginCode` varchar(17) NOT NULL,
    `dateTimeCodeUsed` datetime DEFAULT NULL,
    `clientIp` varchar(20) NOT NULL,
    `enteredPassword` varchar(64) NOT NULL,
    `result` varchar(5) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`customerLoginAttemptId`),
    KEY `customerLoginAttemptBusinessId` (`businessId`),
    KEY `customerLoginAttemptCustomerId` (`customerId`),
    CONSTRAINT `customerLoginAttemptBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `customerLoginAttemptCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `customerSavedLogin`
    --

    CREATE TABLE IF NOT EXISTS `customerSavedLogin` (
    `customerSavedLoginAttemptId` varchar(17) NOT NULL,
    `adminId` varchar(17) NOT NULL,
    `code` varchar(17) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`customerSavedLoginAttemptId`),
    KEY `customerSavedLoginAdminId` (`adminId`),
    CONSTRAINT `customerSavedLoginAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `customerServiceTicket`
    --

    CREATE TABLE IF NOT EXISTS `customerServiceTicket` (
    `customerServiceTicketId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `fromCustomerId` varchar(17) NOT NULL,
    `linkedToInvoiceId` varchar(17) DEFAULT NULL COMMENT 'Optional FK',
    `linkedToEstimateId` varchar(17) DEFAULT NULL COMMENT 'Optional FK',
    `linkedToQuoteRequestId` varchar(17) DEFAULT NULL COMMENT 'Optional FK',
    `docIdId` varchar(17) NOT NULL,
    `subject` varchar(100) NOT NULL,
    `isResolved` tinyint(1) NOT NULL DEFAULT 0,
    `dateTimeAdded` datetime NOT NULL,
    KEY `customerServiceTicketBusinessId` (`businessId`),
    KEY `customerServiceTicketFromCustomerId` (`fromCustomerId`),
    CONSTRAINT `customerServiceTicketBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `customerServiceTicketFromCustomerId` FOREIGN KEY (`fromCustomerId`) REFERENCES `customer` (`customerId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `customerTag`
    --

    CREATE TABLE IF NOT EXISTS `customerTag` (
    `customerTagId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `customerId` varchar(17) NOT NULL,
    `addedByAdminId` varchar(17) NOT NULL,
    `tagName` varchar(50) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`customerTagId`),
    KEY `customerTagBusinessId` (`businessId`),
    KEY `customerTagCustomerId` (`customerId`),
    KEY `customerTagAddedByAdminId` (`addedByAdminId`),
    CONSTRAINT `customerTagAddedByAdminId` FOREIGN KEY (`addedByAdminId`) REFERENCES `admin` (`adminId`),
    CONSTRAINT `customerTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `customerTagCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `staff`
    --

    CREATE TABLE IF NOT EXISTS `staff` (
    `staffId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `addedByAdminId` varchar(17) NOT NULL,
    `surname` varchar(10) NOT NULL,
    `firstName` varchar(25) NOT NULL,
    `lastName` varchar(25) NOT NULL,
    `jobTitle` varchar(150) NOT NULL,
    `payrollAddress` varchar(150) NOT NULL,
    `payrollState` varchar(50) NOT NULL,
    `payrollCity` varchar(50) NOT NULL,
    `payrollZipCode` int(11) NOT NULL,
    `payrollType` varchar(10) NOT NULL,
    `hourlyRate` float NOT NULL,
    `perJobRate` float NOT NULL,
    `payrollDueCache` float NOT NULL,
    `advancePaymentCache` float NOT NULL,
    `allowSZSignIn` tinyint(1) NOT NULL,
    `SZPassword` varchar(25) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    `payrollJobPercentage` int(11) NOT NULL,
    PRIMARY KEY (`staffId`),
    KEY `staffBusinessId` (`businessId`),
    KEY `staffAddedByAdminId` (`addedByAdminId`),
    CONSTRAINT `staffAddedByAdminId` FOREIGN KEY (`addedByAdminId`) REFERENCES `admin` (`adminId`),
    CONSTRAINT `staffBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `staffLoginAttempt`
    --

    CREATE TABLE IF NOT EXISTS `staffLoginAttempt` (
    `staffLoginAttemptId` varchar(17) NOT NULL,
    `businessId` varchar(17) NOT NULL,
    `staffId` varchar(17) DEFAULT NULL,
    `loginCode` varchar(17) NOT NULL,
    `dateTimeCodeUsed` datetime DEFAULT NULL,
    `clientIp` varchar(20) NOT NULL,
    `enteredPassword` varchar(64) NOT NULL,
    `result` varchar(5) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`staffLoginAttemptId`),
    KEY `staffLoginAttemptBusinessId` (`businessId`),
    KEY `staffLoginAttemptStaffId` (`staffId`),
    CONSTRAINT `staffLoginAttemptBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
    CONSTRAINT `staffLoginAttemptStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    --
    -- Table structure for table `staffSavedLogin`
    --

    CREATE TABLE IF NOT EXISTS `staffSavedLogin` (
    `staffSavedLoginAttemptId` varchar(17) NOT NULL,
    `adminId` varchar(17) NOT NULL,
    `code` varchar(17) NOT NULL,
    `dateTimeAdded` datetime NOT NULL,
    PRIMARY KEY (`staffSavedLoginAttemptId`),
    KEY `staffSavedLoginAdminId` (`adminId`),
    CONSTRAINT `staffSavedLoginAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

?>
