	-- Adds all tables needed for UltiScape to function. Not in any particular order, but related tables are generally put together.

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
	`dateTimeLeft` datetime NULL DEFAULT NULL,
	PRIMARY KEY (`adminId`),
	UNIQUE KEY `adminUsername` (`username`) USING BTREE,
	UNIQUE KEY `adminEmail` (`email`) USING BTREE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `adminLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `adminLoginAttempt` (
	`adminLoginAttemptId` varchar(17) NOT NULL,
	`adminId` varchar(17) NULL DEFAULT NULL,
	`clientIp` varchar(150) NOT NULL,
	`result` varchar(5) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminLoginAttemptId`),
	KEY `adminLoginAttemptAdminId` (`adminId`),
	CONSTRAINT `adminLoginAttemptAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `adminSavedLogin`
	--

	CREATE TABLE IF NOT EXISTS `adminSavedLogin` (
	`adminSavedLoginId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminSavedLoginId`),
	KEY `adminSavedLoginAdminId` (`adminId`),
	CONSTRAINT `adminSavedLoginAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
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
	`address` varchar(150) DEFAULT NULL,
	`state` varchar(50) DEFAULT NULL,
	`city` varchar(50) DEFAULT NULL,
	`zipCode` int(11) DEFAULT NULL,
	`phonePrefix` int(11) DEFAULT NULL,
	`phone1` int(11) DEFAULT NULL,
	`phone2` int(11) DEFAULT NULL,
	`phone3` int(11) DEFAULT NULL,
	`email` varchar(64) DEFAULT NULL,
	`currencySymbol` varchar(1) NOT NULL DEFAULT '$',
	`areaSymbol` varchar(5) NOT NULL DEFAULT 'ft',
	`distanceSymbol` varchar(5) NOT NULL DEFAULT 'mi',
	`timeZone` varchar(30) NOT NULL,
	`modCust` tinyint(1) NOT NULL DEFAULT 0,
	`modEmail` tinyint(1) NOT NULL DEFAULT 0,
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
	-- Table structure for table `businessPlan`
	--

	CREATE TABLE IF NOT EXISTS `businessPlanBridge` (
	`businessPlanId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`plan` varchar(20) NOT NULL,
	`untilDateTime` datetime NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`businessPlanId`),
	KEY `businessPlanBridgeBusinessId` (`businessId`),
	CONSTRAINT `businessPlanBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	--
	-- Table structure for table `businessPlanPayment`
	--

	CREATE TABLE IF NOT EXISTS `businessPlanPayment` (
	`businessPlanPaymentId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`linkedToBusinessPlanId` varchar(17) NULL COMMENT 'Optional FK',
	`method` varchar(20) NOT NULL,
	`amount` float NOT NULL,
	`notes` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`businessPlanPaymentId`),
	KEY `businessPlanPaymentBusinessId` (`businessId`),
	KEY `businessPlanPaymentAdminId` (`adminId`),
	KEY `businessPlanPaymentLinkedToBusinessPlanId` (`linkedToBusinessPlanId`),
	CONSTRAINT `businessPlanPaymentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `businessPlanPaymentAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`)
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
	-- Table structure for table `authToken`
	--

	CREATE TABLE IF NOT EXISTS `authToken` (
	`authTokenId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`authName` varchar(50) NULL,
	`dateTimeUsed` datetime NULL,
	`clientIpUsed` varchar(150) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`authTokenId`),
	KEY `authTokenBusinessId` (`businessId`),
	CONSTRAINT `authTokenBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crew`
	--

	CREATE TABLE IF NOT EXISTS `crew` (
	`crewId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewId`),
	KEY `crewBusinessId` (`businessId`),
	CONSTRAINT `crewBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewTag`
	--

	CREATE TABLE IF NOT EXISTS `crewTag` (
	`crewTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewTagId`),
	KEY `crewTagBusinessId` (`businessId`),
	CONSTRAINT `crewTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customer`
	--

	CREATE TABLE IF NOT EXISTS `customer` (
	`customerId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`surname` varchar(10) NULL,
	`firstName` varchar(20) NOT NULL,
	`lastName` varchar(20) NULL,
	`billAddress1` varchar(150) NULL,
	`billAddress2` varchar(150) NULL,
	`billCity` varchar(50) NULL,
	`billState` varchar(50) NULL,
	`billZipCode` int(11) NULL,
	`creditCache` float NOT NULL DEFAULT 0,
	`overrideCreditAlertIsEnabled` tinyint(1) NULL DEFAULT NULL,
	`overrideCreditAlertAmount` float NULL DEFAULT NULL,
	`overrideAutoApplyCredit` tinyint(1) NULL DEFAULT NULL,
	`balanceCache` float NOT NULL DEFAULT 0,
	`overrideBalanceAlertIsEnabled` tinyint(1) NULL DEFAULT NULL,
	`overrideBalanceAlertAmount` float NULL DEFAULT NULL,
	`allowCZSignIn` tinyint(1) NOT NULL DEFAULT 0,
	`password` varchar(64) NOT NULL,
	`discountPercent` int(11) NULL,
	`overridePaymentTerm` int(11) NULL,
	`notes` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerId`),
	UNIQUE KEY `customerPassword` (`password`) USING BTREE,
	KEY `customerBusinessId` (`businessId`),
	CONSTRAINT `customerBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `customerPhoneNumber` (
	`customerPhoneNumberId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`phonePrefix` int(11) NULL DEFAULT NULL,
	`phone1` int(11) NOT NULL,
	`phone2` int(11) NOT NULL,
	`phone3` int(11) NOT NULL,
	`description` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerPhoneNumberId`),
	KEY `customerPhoneNumberBusinessId` (`businessId`),
	KEY `customerPhoneNumberCustomerId` (`customerId`),
	CONSTRAINT `customerPhoneNumberBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerPhoneNumberCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `customerEmailAddress` (
	`customerEmailAddressId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`email` varchar(64) NOT NULL,
	`description` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerEmailAddressId`),
	KEY `customerEmailAddressBusinessId` (`businessId`),
	KEY `customerEmailAddressCustomerId` (`customerId`),
	CONSTRAINT `customerEmailAddressBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerEmailAddressCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `customerLoginAttempt` (
	`customerLoginAttemptId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NULL DEFAULT NULL,
	`clientIp` varchar(150) NOT NULL,
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
	`customerSavedLoginId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerSavedLoginId`),
	KEY `customerSavedLoginBusinessId` (`businessId`),
	KEY `customerSavedLoginCustomerId` (`customerId`),
	CONSTRAINT `customerSavedLoginBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerSavedLoginCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerTag`
	--

	CREATE TABLE IF NOT EXISTS `customerTag` (
	`customerTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerTagId`),
	KEY `customerTagBusinessId` (`businessId`),
	CONSTRAINT `customerTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `quoteRequest`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequest` (
	`quoteRequestId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(50) NULL,
	`email` varchar(64) NULL,
	`address1` varchar(150) NULL,
	`address2` varchar(100) NULL,
	`state` varchar(50) NULL,
	`zipCode` int(11) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`quoteRequestId`),
	KEY `quoteRequestBusinessId` (`businessId`),
	KEY `quoteRequestlinkedToCustomerId` (`linkedToCustomerId`),
	CONSTRAINT `quoteRequestBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `quoteRequestlinkedToCustomerId` FOREIGN KEY (`linkedToCustomerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `quoteRequestService`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequestService` (
	`quoteRequestServiceId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`quoteRequestId` varchar(17) NOT NULL,
	`linkedToServiceListingId` varchar(17) NULL COMMENT 'Optional FK',
	`currentName` varchar(50) NOT NULL,
	`currentDescription` varchar(200) NULL,
	`currentImgFile` varchar(17) NULL,
	`currentPrice` int(11) NULL,
	`currentMinPrice` int(11) NULL,
	`currentMaxPrice` int(11) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`quoteRequestServiceId`),
	KEY `quoteRequestServiceBusinessId` (`businessId`),
	KEY `quoteRequestServiceQuoteRequestId` (`quoteRequestId`),
	KEY `quoteRequestServicelinkedToServiceListingId` (`linkedToServiceListingId`),
	CONSTRAINT `quoteRequestServiceBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `quoteRequestServiceQuoteRequestId` FOREIGN KEY (`quoteRequestId`) REFERENCES `quoteRequest` (`quoteRequestId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `serviceListing`
	--

	CREATE TABLE IF NOT EXISTS `serviceListing` (
	`serviceListingId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` varchar(200) NULL,
	`imgFile` varchar(17) NULL,
	`price` int(11) NULL,
	`minPrice` int(11) NULL,
	`maxPrice` int(11) NULL,
	`isRequestable` tinyint(1) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`serviceListingId`),
	KEY `serviceListingBusinessId` (`businessId`),
	CONSTRAINT `serviceListingBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemical`
	--

	CREATE TABLE IF NOT EXISTS `chemical` (
	`chemicalId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(50) NOT NULL,
	`epa` varchar(50) NULL,
	`ingeredients` text NULL,
	`manufacturer` varchar(100) NULL,
	`dilution` varchar(100) NULL,
	`targets` varchar(100) NULL,
	`applicationMethod` varchar(100) NULL,
	`applicationRate` float NULL,
	`defaultAmountApplied` float NULL,
	`defaultAmountAppliedUnit` varchar(10) NOT NULL DEFAULT 'ml/ft²',
	`amountInStock` float NULL,
	`amountInStockUnit` varchar(10) NOT NULL DEFAULT 'ml',
	`notesToCustomer` text NULL,
	`notesToStaff` text NULL,
	`description` text NULL,
	`condition` varchar(50) NULL,
	`purchaseDate` datetime NULL,
	`purchasePrice` float NULL,
	`storageLocation` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalId`),
	KEY `chemicalBusinessId` (`businessId`),
	KEY `chemicalLinkedToCrewId` (`linkedToCrewId`),
	KEY `chemicalLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `chemicalBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalImage`
	--

	CREATE TABLE IF NOT EXISTS `chemicalImage` (
	`chemicalImageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` varchar(50) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalImageId`),
	KEY `chemicalImageBusinessId` (`businessId`),
	KEY `chemicalImageChemicalId` (`chemicalId`),
	CONSTRAINT `chemicalImageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `chemicalImageChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalTag`
	--

	CREATE TABLE IF NOT EXISTS `chemicalTag` (
	`chemicalTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalTagId`),
	KEY `chemicalTagBusinessId` (`businessId`),
	CONSTRAINT `chemicalTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipment`
	--

	CREATE TABLE IF NOT EXISTS `equipment` (
	`equipmentId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(50) NOT NULL,
	`description` text NULL,
	`condition` varchar(50) NULL,
	`model` varchar(50) NULL,
	`serialNumber` varchar(50) NULL,
	`purchaseDate` datetime NULL,
	`purchasePrice` float NULL,
	`storageLocation` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentId`),
	KEY `equipmentBusinessId` (`businessId`),
	KEY `equipmentLinkedToCrewId` (`linkedToCrewId`),
	KEY `equipmentLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `equipmentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentChemicalBridge`
	--

	CREATE TABLE IF NOT EXISTS `equipmentChemicalBridge` (
	`equipmentChemicalId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentChemicalId`),
	KEY `equipmentChemicalBridgeBusinessId` (`businessId`),
	KEY `equipmentChemicalBridgeEquipmentId` (`equipmentId`),
	KEY `equipmentChemicalBridgeChemicalId` (`chemicalId`),
	CONSTRAINT `equipmentChemicalBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `equipmentChemicalBridgeEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`),
	CONSTRAINT `equipmentChemicalBridgeChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentImage`
	--

	CREATE TABLE IF NOT EXISTS `equipmentImage` (
	`equipmentImageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` varchar(50) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentImageId`),
	KEY `equipmentImageBusinessId` (`businessId`),
	KEY `equipmentImageEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentImageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `equipmentImageEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentMaintenanceLog`
	--

	CREATE TABLE IF NOT EXISTS `equipmentMaintenanceLog` (
	`equipmentMaintenanceLogId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`title` varchar(50) NOT NULL,
	`details` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentMaintenanceLogId`),
	KEY `equipmentMaintenanceLogBusinessId` (`businessId`),
	KEY `equipmentMaintenanceLogEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentMaintenanceLogBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `equipmentMaintenanceLogEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentTag`
	--

	CREATE TABLE IF NOT EXISTS `equipmentTag` (
	`equipmentTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentTagId`),
	KEY `equipmentTagBusinessId` (`businessId`),
	CONSTRAINT `equipmentTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `docId`
	--

	CREATE TABLE IF NOT EXISTS `docId` (
	`docIdId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`incrementalId` varchar(17) NOT NULL,
	`randomId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`docIdId`),
	KEY `docIdBusinessId` (`businessId`),
	CONSTRAINT `docIdBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerServiceTicket`
	--

	CREATE TABLE IF NOT EXISTS `customerServiceTicket` (
	`customerServiceTicketId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToInvoiceId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToEstimateId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToQuoteRequestId` varchar(17) NULL COMMENT 'Optional FK',
	`customerName` varchar(50) NULL COMMENT 'NULL if linkedToCustomerId',
	`customerEmail` varchar(64) NULL COMMENT 'NULL if linkedToCustomerId',
	`subject` varchar(100) NOT NULL,
	`isResolved` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerServiceTicketId`),
	KEY `customerServiceTicketBusinessId` (`businessId`),
	KEY `customerServiceTicketDocIdId` (`docIdId`),
	KEY `customerServiceTicketLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `customerServiceTicketLinkedToInvoiceId` (`linkedToInvoiceId`),
	KEY `customerServiceTicketLinkedToEstimateId` (`linkedToEstimateId`),
	KEY `customerServiceTicketLinkedToQuoteRequestId` (`linkedToQuoteRequestId`),
	CONSTRAINT `customerServiceTicketBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerServiceTicketDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`),
	CONSTRAINT `customerServiceTicketLinkedToCustomerId` FOREIGN KEY (`linkedToCustomerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `adminCustomerServiceMessage`
	--

	CREATE TABLE IF NOT EXISTS `adminCustomerServiceMessage` (
	`adminCustomerServiceMessageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`customerServiceTicketId` varchar(17) NOT NULL,
	`message` text NOT NULL,
	`isReadByCustomer` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminCustomerServiceMessageId`),
	KEY `adminCustomerServiceMessageBusinessId` (`businessId`),
	KEY `adminCustomerServiceMessageAdminId` (`adminId`),
	KEY `adminCustomerServiceMessageCustomerServiceTicketId` (`customerServiceTicketId`),
	CONSTRAINT `adminCustomerServiceMessageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `adminCustomerServiceMessageAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`),
	CONSTRAINT `adminCustomerServiceMessageCustomerServiceTicketId` FOREIGN KEY (`customerServiceTicketId`) REFERENCES `customerServiceTicket` (`customerServiceTicketId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerCustomerServiceMessage`
	--

	CREATE TABLE IF NOT EXISTS `customerCustomerServiceMessage` (
	`customerCustomerServiceMessageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerServiceTicketId` varchar(17) NOT NULL,
	`message` text NOT NULL,
	`isReadByAdmin` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerCustomerServiceMessageId`),
	KEY `customerCustomerServiceMessageBusinessId` (`businessId`),
	KEY `customerCustomerServiceMessageCustomerServiceTicketId` (`customerServiceTicketId`),
	CONSTRAINT `customerCustomerServiceMessageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerCustomerServiceMessageCustomerServiceTicketId` FOREIGN KEY (`customerServiceTicketId`) REFERENCES `customerServiceTicket` (`customerServiceTicketId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `fileUpload`
	--

	CREATE TABLE IF NOT EXISTS `fileUpload` (
	`fileUploadId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`linkedToStaffId` varchar(17) NULL DEFAULT NULL COMMENT 'Optional FK',
	`linkedToCustomerId` varchar(17) NULL DEFAULT NULL COMMENT 'Optional FK',
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`fileUploadId`),
	KEY `fileUploadBusinessId` (`businessId`),
	KEY `fileUploadLinkedToStaffId` (`linkedToStaffId`),
	KEY `fileUploadLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `fileUploadDocIdId` (`docIdId`),
	CONSTRAINT `fileUploadBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `fileUploadDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `estimate`
	--

	CREATE TABLE IF NOT EXISTS `estimate` (
	`estimateId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`discountIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`discount` float NOT NULL DEFAULT 0,
	`customJobDetails` text NULL,
	`comments` text NULL,
	`privateNotes` text NULL,
	`isViewed` tinyint(1) NOT NULL DEFAULT 0,
	`isEmailed` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`estimateId`),
	KEY `estimateBusinessId` (`businessId`),
	KEY `estimateCustomerId` (`customerId`),
	KEY `estimateDocIdId` (`docIdId`),
	CONSTRAINT `estimateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `estimateCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`),
	CONSTRAINT `estimateDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `estimateItem`
	--

	CREATE TABLE IF NOT EXISTS `estimateItem` (
	`estimateItemId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`estimateId` varchar(17) NOT NULL,
	`name` varchar(100) NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`estimateItemId`),
	KEY `estimateItemBusinessId` (`businessId`),
	KEY `estimateItemEstimateId` (`estimateId`),
	CONSTRAINT `estimateItemBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `estimateItemEstimateId` FOREIGN KEY (`estimateId`) REFERENCES `estimate` (`estimateId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `estimateApproval`
	--

	CREATE TABLE IF NOT EXISTS `estimateApproval` (
	`estimateApprovalId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`estimateId` varchar(17) NOT NULL,
	`approvedByAdminId` varchar(17) NULL COMMENT 'Optional FK',
	`adminReason` varchar(200) NOT NULL,
	`dateTimeApproved` datetime NOT NULL,
	PRIMARY KEY (`estimateApprovalId`),
	KEY `estimateApprovalBusinessId` (`businessId`),
	KEY `estimateApprovalEstimateId` (`estimateId`),
	KEY `estimateApprovalapprovedByAdminId` (`approvedByAdminId`),
	CONSTRAINT `estimateApprovalBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `estimateApprovalEstimateId` FOREIGN KEY (`estimateId`) REFERENCES `estimate` (`estimateId`),
	CONSTRAINT `estimateApprovalapprovedByAdminId` FOREIGN KEY (`approvedByAdminId`) REFERENCES `admin` (`adminId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `invoice`
	--

	CREATE TABLE IF NOT EXISTS `invoice` (
	`invoiceId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`discountIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`discount` float NOT NULL DEFAULT 0,
	`customJobDetails` text NULL,
	`comments` text NULL,
	`privateNotes` text NULL,
	`isManualPaid` tinyint(1) NOT NULL DEFAULT 0,
	`isViewed` tinyint(1) NOT NULL DEFAULT 0,
	`isEmailed` tinyint(1) NOT NULL DEFAULT 0,
	`isOverdueNotified` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`invoiceId`),
	KEY `invoiceBusinessId` (`businessId`),
	KEY `invoiceCustomerId` (`customerId`),
	KEY `invoiceDocIdId` (`docIdId`),
	CONSTRAINT `invoiceBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `invoiceCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`),
	CONSTRAINT `invoiceDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `invoiceItem`
	--

	CREATE TABLE IF NOT EXISTS `invoiceItem` (
	`invoiceItemId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`invoiceId` varchar(17) NOT NULL,
	`name` varchar(100) NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`invoiceItemId`),
	KEY `invoiceItemBusinessId` (`businessId`),
	KEY `invoiceItemInvoiceId` (`invoiceId`),
	CONSTRAINT `invoiceItemBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `invoiceItemInvoiceId` FOREIGN KEY (`invoiceId`) REFERENCES `invoice` (`invoiceId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `payment`
	--

	CREATE TABLE IF NOT EXISTS `payment` (
	`paymentId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToInvoiceId` varchar(17) NULL COMMENT 'Optional FK',
	`customerId` varchar(17) NOT NULL,
	`method` varchar(20) NOT NULL,
	`amount` float NOT NULL,
	`notes` varchar(50) NULL,
	`excessWasAddedToCredit` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`paymentId`),
	KEY `paymentBusinessId` (`businessId`),
	KEY `paymentCustomerId` (`customerId`),
	KEY `paymentLinkedToInvoiceId` (`linkedToInvoiceId`),
	CONSTRAINT `paymentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `paymentCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `property`
	--

	CREATE TABLE IF NOT EXISTS `property` (
	`propertyId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`address1` varchar(150) NOT NULL,
	`address2` varchar(100) NULL,
	`city` varchar(50) NULL,
	`state` varchar(50) NULL,
	`zipCode` int(11) NULL,
	`lawnSize` int(11) NULL,
	`mulchQuantity`int(11) NULL,
	`pricePerMow` float NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`propertyId`),
	KEY `propertyBusinessId` (`businessId`),
	KEY `propertyCustomerId` (`customerId`),
	CONSTRAINT `propertyBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `propertyCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalApplication`
	--

	CREATE TABLE IF NOT EXISTS `chemicalApplication` (
	`chemicalApplicationId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`propertyId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToJobCompletedId` varchar(17) NULL COMMENT 'Optional FK',
	`weatherDescription` varchar(100) NULL,
	`amountApplied` float NULL,
	`wasSubtractedFromStock` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalApplicationId`),
	KEY `chemicalApplicationBusinessId` (`businessId`),
	KEY `chemicalApplicationChemicalId` (`chemicalId`),
	KEY `chemicalApplicationPropertyId` (`propertyId`),
	KEY `chemicalApplicationLinkedToCrewId` (`linkedToCrewId`),
	KEY `chemicalApplicationLinkedToStaffId` (`linkedToStaffId`),
	KEY `chemicalApplicationLinkedToJobCompletedId` (`linkedToJobCompletedId`),
	CONSTRAINT `chemicalApplicationBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `chemicalApplicationChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`),
	CONSTRAINT `chemicalApplicationPropertyId` FOREIGN KEY (`propertyId`) REFERENCES `property` (`propertyId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobCancellation`
	--

	CREATE TABLE IF NOT EXISTS `jobCancellation` (
	`jobCancellationId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToJobSingularId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToJobRecurringId` varchar(17) NULL COMMENT 'Optional FK',
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobCancellationId`),
	KEY `jobCancellationBusinessId` (`businessId`),
	KEY `jobCancellationLinkedToJobSingularId` (`linkedToJobSingularId`),
	KEY `jobCancellationLinkedToJobRecurringId` (`linkedToJobRecurringId`),
	CONSTRAINT `jobCancellationBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	--
	-- Table structure for table `jobCompleted`
	--

	CREATE TABLE IF NOT EXISTS `jobCompleted` (
	`jobCompletedId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToJobRecurringId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(100) NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` int(11) NULL,
	`wasPrepaid` tinyint(1) NOT NULL default 0,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobCompletedId`),
	KEY `jobCompletedBusinessId` (`businessId`),
	KEY `jobCompletedLinkedToJobRecurringId` (`linkedToJobRecurringId`),
	KEY `jobCompletedLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `jobCompletedLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `jobCompletedBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobRecurring`
	--

	CREATE TABLE IF NOT EXISTS `jobRecurring` (
	`jobRecurringId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(100) NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` int(11) NULL,
	`isPrepaid` tinyint(1) NOT NULL default 0,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobRecurringId`),
	KEY `jobRecurringBusinessId` (`businessId`),
	KEY `jobRecurringLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `jobRecurringLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `jobRecurringBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobSingular`
	--

	CREATE TABLE IF NOT EXISTS `jobSingular` (
	`jobSingularId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToJobRecurringId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` varchar(100) NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` int(11) NULL,
	`isPrepaid` tinyint(1) NOT NULL default 0,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobSingularId`),
	KEY `jobSingularBusinessId` (`businessId`),
	KEY `jobSingularLinkedToJobRecurringId` (`linkedToJobRecurringId`),
	KEY `jobSingularLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `jobSingularLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `jobSingularBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobSingularCrewBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobSingularCrewBridge` (
	`jobSingularCrewId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobSingularId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobSingularCrewId`),
	KEY `jobSingularCrewBridgeBusinessId` (`businessId`),
	KEY `jobSingularCrewBridgeJobSingularId` (`jobSingularId`),
	KEY `jobSingularCrewBridgecrewId` (`crewId`),
	CONSTRAINT `jobSingularCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobSingularCrewBridgeJobSingularId` FOREIGN KEY (`jobSingularId`) REFERENCES `jobSingular` (`jobSingularId`),
	CONSTRAINT `jobSingularCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobRecurringCrewBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobRecurringCrewBridge` (
	`jobRecurringCrewId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobRecurringId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobRecurringCrewId`),
	KEY `jobRecurringCrewBridgeBusinessId` (`businessId`),
	KEY `jobRecurringCrewBridgeJobRecurringId` (`jobRecurringId`),
	KEY `jobRecurringCrewBridgecrewId` (`crewId`),
	CONSTRAINT `jobRecurringCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobRecurringCrewBridgeJobRecurringId` FOREIGN KEY (`jobRecurringId`) REFERENCES `jobRecurring` (`jobRecurringId`),
	CONSTRAINT `jobRecurringCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobCompletedCrewBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobCompletedCrewBridge` (
	`jobCompletedCrewId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobCompletedId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobCompletedCrewId`),
	KEY `jobCompletedCrewBridgeBusinessId` (`businessId`),
	KEY `jobCompletedCrewBridgeJobCompletedId` (`jobCompletedId`),
	KEY `jobCompletedCrewBridgecrewId` (`crewId`),
	CONSTRAINT `jobCompletedCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobCompletedCrewBridgeJobCompletedId` FOREIGN KEY (`jobCompletedId`) REFERENCES `jobCompleted` (`jobCompletedId`),
	CONSTRAINT `jobCompletedCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staff`
	--

	CREATE TABLE IF NOT EXISTS `staff` (
	`staffId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`surname` varchar(10) NULL,
	`firstName` varchar(25) NOT NULL,
	`lastName` varchar(25) NULL,
	`profilePicture` varchar(17) NULL,
	`jobTitle` varchar(150) NULL,
	`bio` text NULL,
	`payrollAddress1` varchar(150) NULL,
	`payrollAddress2` varchar(150) NULL,
	`payrollState` varchar(50) NULL,
	`payrollCity` varchar(50) NULL,
	`payrollZipCode` int(11) NULL,
	`overridePayrollType` varchar(10) NULL,
	`overrideHourlyRate` float NULL,
	`overridePerJobRate` float NULL,
	`overrideJobPercentage` int(11) NULL,
	`payrollDueCache` float NOT NULL,
	`advancePaymentCache` float NOT NULL,
	`allowSignIn` tinyint(1) NOT NULL,
	`password` varchar(25) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffId`),
	KEY `staffBusinessId` (`businessId`),
	CONSTRAINT `staffBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `staffPhoneNumber` (
	`staffPhoneNumberId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`phonePrefix` int(11) NULL DEFAULT NULL,
	`phone1` int(11) NOT NULL,
	`phone2` int(11) NOT NULL,
	`phone3` int(11) NOT NULL,
	`description` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffPhoneNumberId`),
	KEY `staffPhoneNumberBusinessId` (`businessId`),
	KEY `staffPhoneNumberStaffId` (`staffId`),
	CONSTRAINT `staffPhoneNumberBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `staffPhoneNumberStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `staffEmailAddress` (
	`staffEmailAddressId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`email` varchar(64) NOT NULL,
	`description` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffEmailAddressId`),
	KEY `staffEmailAddressBusinessId` (`businessId`),
	KEY `staffEmailAddressStaffId` (`staffId`),
	CONSTRAINT `staffEmailAddressBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `staffEmailAddressStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewLeaderBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewLeaderBridge` (
	`crewLeaderId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewLeaderId`),
	KEY `crewLeaderBridgeBusinessId` (`businessId`),
	KEY `crewLeaderBridgeCrewId` (`crewId`),
	KEY `crewLeaderBridgeStaffId` (`staffId`),
	CONSTRAINT `crewLeaderBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `crewLeaderBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`),
	CONSTRAINT `crewLeaderBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewStaffBridge` (
	`crewStaffId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewStaffId`),
	KEY `crewStaffBridgeBusinessId` (`businessId`),
	KEY `crewStaffBridgeCrewId` (`crewId`),
	KEY `crewStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `crewStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `crewStaffBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`),
	CONSTRAINT `crewStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `staffLoginAttempt` (
	`staffLoginAttemptId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) DEFAULT NULL,
	`clientIp` varchar(150) NOT NULL,
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
	`staffSavedLoginId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffSavedLoginId`),
	KEY `staffSavedLoginBusinessId` (`businessId`),
	KEY `staffSavedLoginStaffId` (`staffId`),
	CONSTRAINT `staffSavedLoginBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `staffSavedLoginStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffTag`
	--

	CREATE TABLE IF NOT EXISTS `staffTag` (
	`staffTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffTagId`),
	KEY `staffTagBusinessId` (`businessId`),
	CONSTRAINT `staffTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobSingularStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobSingularStaffBridge` (
	`jobSingularStaffId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobSingularId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobSingularStaffId`),
	KEY `jobSingularStaffBridgeBusinessId` (`businessId`),
	KEY `jobSingularStaffBridgeJobSingularId` (`jobSingularId`),
	KEY `jobSingularStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `jobSingularStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobSingularStaffBridgeJobSingularId` FOREIGN KEY (`jobSingularId`) REFERENCES `jobSingular` (`jobSingularId`),
	CONSTRAINT `jobSingularStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobRecurringStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobRecurringStaffBridge` (
	`jobRecurringStaffId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobRecurringId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobRecurringStaffId`),
	KEY `jobRecurringStaffBridgeBusinessId` (`businessId`),
	KEY `jobRecurringStaffBridgeJobRecurringId` (`jobRecurringId`),
	KEY `jobRecurringStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `jobRecurringStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobRecurringStaffBridgeJobRecurringId` FOREIGN KEY (`jobRecurringId`) REFERENCES `jobRecurring` (`jobRecurringId`),
	CONSTRAINT `jobRecurringStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobCompletedStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobCompletedStaffBridge` (
	`jobCompletedStaffBridge` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`jobCompletedId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobCompletedStaffBridge`),
	KEY `jobCompletedStaffBridgeBusinessId` (`businessId`),
	KEY `jobCompletedStaffBridgeJobCompletedId` (`jobCompletedId`),
	KEY `jobCompletedStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `jobCompletedStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `jobCompletedStaffBridgeJobCompletedId` FOREIGN KEY (`jobCompletedId`) REFERENCES `jobCompleted` (`jobCompletedId`),
	CONSTRAINT `jobCompletedStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `timeLog`
	--

	CREATE TABLE IF NOT EXISTS `timeLog` (
	`timeLogId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeStart` datetime NOT NULL,
	`dateTimeEnd` datetime NULL,
	`notes` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`timeLogId`),
	KEY `timeLogBusinessId` (`businessId`),
	KEY `timeLogStaffId` (`staffId`),
	CONSTRAINT `timeLogBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `timeLogStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `payrollDue`
	--

	CREATE TABLE IF NOT EXISTS `payrollDue` (
	`payrollDueId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`linkedToTimeLogId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToCompletedJobId` varchar(17) NULL COMMENT 'Optional FK',
	`amount` float NOT NULL,
	`notes` text NULL,
	`isManualPaid` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollDueId`),
	KEY `payrollDueBusinessId` (`businessId`),
	KEY `payrollDueStaffId` (`staffId`),
	KEY `payrollDueLinkedToTimeLogId` (`linkedToTimeLogId`),
	KEY `payrollDueLinkedToCompletedJobId` (`linkedToCompletedJobId`),
	CONSTRAINT `payrollDueBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `payrollDueStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `payrollSatisfaction`
	--

	CREATE TABLE IF NOT EXISTS `payrollSatisfaction` (
	`payrollSatisfactionId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`linkedToPayrollDueId` varchar(17) NULL COMMENT 'Optional FK',
	`method` varchar(10) NOT NULL,
	`amount` float NOT NULL,
	`notes` text NULL,
	`excessIsAddedToAdvancePay` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollSatisfactionId`),
	KEY `payrollSatisfactionBusinessId` (`businessId`),
	KEY `payrollSatisfactionStaffId` (`staffId`),
	KEY `payrollSatisfactionLinkedToPayrollDueId` (`linkedToPayrollDueId`),
	CONSTRAINT `payrollSatisfactionBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `payrollSatisfactionStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `mailoutCampaignTemplate`
	--

	CREATE TABLE IF NOT EXISTS `mailoutCampaignTemplate` (
	`mailoutCampaignTemplateId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`campaignName` varchar(200) NOT NULL,
	`subject` varchar(200) NOT NULL,
	`headerFile` varchar(17) NOT NULL,
	`bodyFile` varchar(17) NOT NULL,
	`footerFile` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`mailoutCampaignTemplateId`),
	KEY `mailoutCampaignTemplateBusinessId` (`businessId`),
	CONSTRAINT `mailoutCampaignTemplateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `mailoutSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `mailoutSubscriptionBridge` (
	`mailoutSubscriptionId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerEmailAddressId` varchar(17) NOT NULL,
	`mailoutCampaignTemplateId` varchar(17) NOT NULL,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`mailoutSubscriptionId`),
	KEY `mailoutSubscriptionBridgeBusinessId` (`businessId`),
	KEY `mailoutSubscriptionBridgeCustomerEmailAddressId` (`customerEmailAddressId`),
	KEY `mailoutSubscriptionBridgeMailoutCampaignTemplateId` (`mailoutCampaignTemplateId`),
	CONSTRAINT `mailoutSubscriptionBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `mailoutSubscriptionBridgeCustomerEmailAddressId` FOREIGN KEY (`customerEmailAddressId`) REFERENCES `customerEmailAddress` (`customerEmailAddressId`),
	CONSTRAINT `mailoutSubscriptionBridgeMailoutCampaignTemplateId` FOREIGN KEY (`mailoutCampaignTemplateId`) REFERENCES `mailoutCampaignTemplate` (`mailoutCampaignTemplateId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `emailSend`
	--

	CREATE TABLE IF NOT EXISTS `emailSend` (
	`emailSendId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToMailoutSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToMailoutCampaignTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`subject` varchar(200) NOT NULL,
	`headerFile` varchar(17) NOT NULL,
	`bodyFile` varchar(17) NOT NULL,
	`footerFile` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailSendId`),
	KEY `emailSendBusinessId` (`businessId`),
	KEY `emailSendLinkedToMailoutSubscriptionId` (`linkedToMailoutSubscriptionId`),
	KEY `emailSendLinkedToMailoutCampaignTemplateId` (`linkedToMailoutCampaignTemplateId`),
	CONSTRAINT `emailSendBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerEmailAddressEmailSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `customerEmailAddressEmailSendBridge` (
	`customerEmailAddressEmailSendId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerEmailAddressId` varchar(17) NOT NULL,
	`emailSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerEmailAddressEmailSendId`),
	KEY `customerEmailAddressEmailSendBridgeBusinessId` (`businessId`),
	KEY `customerEmailAddressEmailSendBridgeCustomerEmailAddressId` (`customerEmailAddressId`),
	KEY `customerEmailAddressEmailSendBridgeEmailSendId` (`emailSendId`),
	CONSTRAINT `customerEmailAddressEmailSendBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerEmailAddressEmailSendBridgeCustomerEmailAddressId` FOREIGN KEY (`customerEmailAddressId`) REFERENCES `customerEmailAddress` (`customerEmailAddressId`),
	CONSTRAINT `customerEmailAddressEmailSendBridgeEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `emailPixel`
	--

	CREATE TABLE IF NOT EXISTS `emailPixel` (
	`emailPixelId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`emailSendId` varchar(17) NOT NULL,
	`pixelFile` varchar(17) NOT NULL,
	`dateTimeRead` datetime NULL,
	`clientIpRead` varchar(150) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailPixelId`),
	KEY `emailPixelBusinessId` (`businessId`),
	KEY `emailPixelEmailSendId` (`emailSendId`),
	CONSTRAINT `emailPixelBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `emailPixelEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `smsCampaignTemplate`
	--

	CREATE TABLE IF NOT EXISTS `smsCampaignTemplate` (
	`smsCampaignTemplateId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`campaignName` varchar(200) NOT NULL,
	`message` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsCampaignTemplateId`),
	KEY `smsCampaignTemplateBusinessId` (`businessId`),
	CONSTRAINT `smsCampaignTemplateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `smsSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `smsSubscriptionBridge` (
	`smsSubscriptionId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerPhoneNumberId` varchar(17) NOT NULL,
	`smsCampaignTemplateId` varchar(17) NOT NULL,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsSubscriptionId`),
	KEY `smsSubscriptionBridgeBusinessId` (`businessId`),
	KEY `smsSubscriptionBridgeCustomerPhoneNumberId` (`customerPhoneNumberId`),
	KEY `smsSubscriptionBridgeMailoutCampaignTemplateId` (`smsCampaignTemplateId`),
	CONSTRAINT `smsSubscriptionBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `smsSubscriptionBridgeCustomerPhoneNumberId` FOREIGN KEY (`customerPhoneNumberId`) REFERENCES `customerPhoneNumber` (`customerPhoneNumberId`),
	CONSTRAINT `smsSubscriptionBridgeMailoutCampaignTemplateId` FOREIGN KEY (`smsCampaignTemplateId`) REFERENCES `smsCampaignTemplate` (`smsCampaignTemplateId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `smsSend`
	--

	CREATE TABLE IF NOT EXISTS `smsSend` (
	`smsSendId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToSmsSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToSmsCampaignTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`message` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsSendId`),
	KEY `smsSendBusinessId` (`businessId`),
	KEY `smsSendLinkedToSmsSubscriptionId` (`linkedToSmsSubscriptionId`),
	KEY `smsSendLinkedToSmsCampaignTemplateId` (`linkedToSmsCampaignTemplateId`),
	CONSTRAINT `smsSendBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerPhoneNumberSmsSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `customerPhoneNumberSmsSendBridge` (
	`customerPhoneNumberSmsSendId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerPhoneNumberId` varchar(17) NOT NULL,
	`smsSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerPhoneNumberSmsSendId`),
	KEY `customerPhoneNumberSmsSendBridgeBusinessId` (`businessId`),
	KEY `customerPhoneNumberSmsSendBridgeCustomerPhoneNumberId` (`customerPhoneNumberId`),
	KEY `customerPhoneNumberSmsSendBridgeSmsSendId` (`smsSendId`),
	CONSTRAINT `customerPhoneNumberSmsSendBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`),
	CONSTRAINT `customerPhoneNumberSmsSendBridgeCustomerPhoneNumberId` FOREIGN KEY (`customerPhoneNumberId`) REFERENCES `customerPhoneNumber` (`customerPhoneNumberId`),
	CONSTRAINT `customerPhoneNumberSmsSendBridgeSmsSendId` FOREIGN KEY (`smsSendId`) REFERENCES `smsSend` (`smsSendId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogPost`
	--

	CREATE TABLE IF NOT EXISTS `blogPost` (
	`blogPostId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
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
	CONSTRAINT `blogPostBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogTag`
	--

	CREATE TABLE IF NOT EXISTS `blogTag` (
	`blogTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` varchar(50) NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogTagId`),
	KEY `blogTagBusinessId` (`businessId`),
	CONSTRAINT `blogTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
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
	`clientIP` varchar(150) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogPostReadTokenId`),
	KEY `blogPostReadTokenBusinessId` (`businessId`),
	KEY `blogPostReadTokenBlogPostId` (`blogPostId`),
	CONSTRAINT `blogPostReadTokenBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`),
	CONSTRAINT `blogPostReadTokenBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
