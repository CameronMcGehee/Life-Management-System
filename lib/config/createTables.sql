	-- Adds all tables needed for UltiScape to function. Not in any particular order, but related tables are generally put together.

	--
	-- Table structure for table `admin`
	--

	CREATE TABLE IF NOT EXISTS `admin` (
	`adminId` varchar(17) NOT NULL,
	`username` varchar(200) NOT NULL,
	`password` varchar(64) NOT NULL,
	`email` varchar(200) NOT NULL,
	`firstName` text NOT NULL,
	`lastName` text NOT NULL,
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
	`clientIp` text NOT NULL,
	`result` varchar(20) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminLoginAttemptId`),
	KEY `adminLoginAttemptAdminId` (`adminId`),
	CONSTRAINT `adminLoginAttemptAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE
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
	CONSTRAINT `adminSavedLoginAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `business`
	--

	CREATE TABLE IF NOT EXISTS `business` (
	`businessId` varchar(17) NOT NULL,
	`displayName` text NOT NULL,
	`adminDisplayName` text NOT NULL,
	`fullLogoFile` varchar(17) DEFAULT NULL,
	`address1` text DEFAULT NULL,
	`address2` text DEFAULT NULL,
	`state` text DEFAULT NULL,
	`city` text DEFAULT NULL,
	`zipCode` text DEFAULT NULL,
	`phonePrefix` text DEFAULT NULL,
	`phone1` text DEFAULT NULL,
	`phone2` text DEFAULT NULL,
	`phone3` text DEFAULT NULL,
	`email` text DEFAULT NULL,
	`currencySymbol` varchar(1) NOT NULL DEFAULT '$',
	`areaSymbol` varchar(5) NOT NULL DEFAULT 'ft',
	`distanceSymbol` varchar(5) NOT NULL DEFAULT 'mi',
	`timeZone` text NOT NULL,
	`plan` varchar(20) NOT NULL DEFAULT 'free',
	`planUntilDateTime` datetime NULL DEFAULT NULL,
	`modCust` tinyint(1) NOT NULL DEFAULT 0,
	`modEmail` tinyint(1) NOT NULL DEFAULT 0,
	`modInv` tinyint(1) NOT NULL DEFAULT 0,
	`modInvIncludePastBal` tinyint(1) NOT NULL DEFAULT 0,
	`modEst` tinyint(1) NOT NULL DEFAULT 0,
	`modProp` tinyint(1) NOT NULL DEFAULT 0,
	`modJobs` tinyint(1) NOT NULL DEFAULT 0,
	`modEquip` tinyint(1) NOT NULL DEFAULT 0,
	`modChem` tinyint(1) NOT NULL DEFAULT 0,
	`modStaff` tinyint(1) NOT NULL DEFAULT 0,
	`modCrews` tinyint(1) NOT NULL DEFAULT 0,
	`modPayr` tinyint(1) NOT NULL DEFAULT 0,
	`modPayrSatLinkedToDue` tinyint(1) NOT NULL DEFAULT 0,
	`modPayrSalDefaultType` varchar(10) NOT NULL DEFAULT 'none',
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
	`CPModHomeBodyFile` tinyint(1) DEFAULT NULL,
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
	PRIMARY KEY (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	--
	-- Table structure for table `businessPlanPayment`
	--

	CREATE TABLE IF NOT EXISTS `businessPlanPayment` (
	`businessPlanPaymentId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`method` varchar(20) NOT NULL,
	`amount` float NOT NULL,
	`notes` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`businessPlanPaymentId`),
	KEY `businessPlanPaymentBusinessId` (`businessId`),
	KEY `businessPlanPaymentAdminId` (`adminId`),
	CONSTRAINT `businessPlanPaymentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `businessPlanPaymentAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `adminBusinessBridge`
	--

	CREATE TABLE IF NOT EXISTS `adminBusinessBridge` (
	`adminBusinessId` int(11) NOT NULL AUTO_INCREMENT,
	`adminId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`adminIsOwner` tinyint(1) NOT NULL DEFAULT 0,
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
	CONSTRAINT `adminBusinessBridgeAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE,
	CONSTRAINT `adminBusinessBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `authToken`
	--

	CREATE TABLE IF NOT EXISTS `authToken` (
	`authTokenId` varchar(17) NOT NULL,
	`businessId` varchar(17) NULL,
	`authName` varchar(50) NULL,
	`clientIp` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`authTokenId`),
	KEY `authTokenBusinessId` (`businessId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crew`
	--

	CREATE TABLE IF NOT EXISTS `crew` (
	`crewId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewId`),
	KEY `crewBusinessId` (`businessId`),
	CONSTRAINT `crewBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewTag`
	--

	CREATE TABLE IF NOT EXISTS `crewTag` (
	`crewTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewTagId`),
	KEY `crewTagBusinessId` (`businessId`),
	CONSTRAINT `crewTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewCrewTag`
	--

	CREATE TABLE IF NOT EXISTS `crewCrewTag` (
	`crewCrewTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`crewTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewCrewTagId`),
	KEY `crewCrewTagBusinessId` (`businessId`),
	KEY `crewCrewTagCrewId` (`crewId`),
	KEY `crewCrewTagCrewTagId` (`crewTagId`),
	CONSTRAINT `crewCrewTagCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewCrewTagCrewTagId` FOREIGN KEY (`crewTagId`) REFERENCES `crewTag` (`crewTagId`) ON DELETE CASCADE,
	CONSTRAINT `crewCrewTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customer`
	--

	CREATE TABLE IF NOT EXISTS `customer` (
	`customerId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`firstName` text NOT NULL,
	`lastName` text NULL,
	`nameIndex` varchar(3) NOT NULL,
	`billAddress1` text NULL,
	`billAddress2` text NULL,
	`billCity` text NULL,
	`billState` text NULL,
	`billZipCode` text NULL,
	`creditCache` float NOT NULL DEFAULT 0,
	`overrideCreditAlertIsEnabled` tinyint(1) NULL DEFAULT NULL,
	`overrideCreditAlertAmount` float NULL DEFAULT NULL,
	`overrideAutoApplyCredit` tinyint(1) NULL DEFAULT NULL,
	`balanceCache` float NOT NULL DEFAULT 0,
	`overrideBalanceAlertIsEnabled` tinyint(1) NULL DEFAULT NULL,
	`overrideBalanceAlertAmount` float NULL DEFAULT NULL,
	`allowCZSignIn` tinyint(1) NOT NULL DEFAULT 0,
	`password` text NOT NULL,
	`discountPercent` int(11) NULL,
	`overridePaymentTerm` int(11) NULL,
	`notes` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerId`),
	KEY `customerBusinessId` (`businessId`),
	CONSTRAINT `customerBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `customerPhoneNumber` (
	`customerPhoneNumberId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`phonePrefix` text NULL DEFAULT NULL,
	`phone1` text NOT NULL,
	`phone2` text NULL,
	`phone3` text NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerPhoneNumberId`),
	KEY `customerPhoneNumberBusinessId` (`businessId`),
	KEY `customerPhoneNumberCustomerId` (`customerId`),
	CONSTRAINT `customerPhoneNumberBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerPhoneNumberCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `customerEmailAddress` (
	`customerEmailAddressId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`email` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerEmailAddressId`),
	KEY `customerEmailAddressBusinessId` (`businessId`),
	KEY `customerEmailAddressCustomerId` (`customerId`),
	CONSTRAINT `customerEmailAddressBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerEmailAddressCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `customerLoginAttempt` (
	`customerLoginAttemptId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NULL DEFAULT NULL,
	`clientIp` text NOT NULL,
	`result` varchar(5) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerLoginAttemptId`),
	KEY `customerLoginAttemptBusinessId` (`businessId`),
	KEY `customerLoginAttemptCustomerId` (`customerId`),
	CONSTRAINT `customerLoginAttemptBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerLoginAttemptCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
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
	CONSTRAINT `customerSavedLoginBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerSavedLoginCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerTag`
	--

	CREATE TABLE IF NOT EXISTS `customerTag` (
	`customerTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerTagId`),
	KEY `customerTagBusinessId` (`businessId`),
	CONSTRAINT `customerTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerCustomerTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `customerCustomerTagBridge` (
	`customerCustomerTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`customerTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerCustomerTagId`),
	KEY `customerCustomerTagBusinessId` (`businessId`),
	KEY `customerCustomerTagCustomerId` (`customerId`),
	KEY `customerCustomerTagCustomerTagId` (`customerTagId`),
	CONSTRAINT `customerCustomerTagCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE,
	CONSTRAINT `customerCustomerTagCustomerTagId` FOREIGN KEY (`customerTagId`) REFERENCES `customerTag` (`customerTagId`) ON DELETE CASCADE,
	CONSTRAINT `customerCustomerTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `quoteRequest`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequest` (
	`quoteRequestId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NULL,
	`email` text NULL,
	`address1` text NULL,
	`address2` text NULL,
	`state` text NULL,
	`zipCode` int(11) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`quoteRequestId`),
	KEY `quoteRequestBusinessId` (`businessId`),
	KEY `quoteRequestlinkedToCustomerId` (`linkedToCustomerId`),
	CONSTRAINT `quoteRequestBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `quoteRequestlinkedToCustomerId` FOREIGN KEY (`linkedToCustomerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `quoteRequestService`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequestService` (
	`quoteRequestServiceId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`quoteRequestId` varchar(17) NOT NULL,
	`linkedToServiceListingId` varchar(17) NULL COMMENT 'Optional FK',
	`currentName` text NOT NULL,
	`currentDescription` text NULL,
	`currentImgFile` varchar(17) NULL,
	`currentPrice` int(11) NULL,
	`currentMinPrice` int(11) NULL,
	`currentMaxPrice` int(11) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`quoteRequestServiceId`),
	KEY `quoteRequestServiceBusinessId` (`businessId`),
	KEY `quoteRequestServiceQuoteRequestId` (`quoteRequestId`),
	KEY `quoteRequestServicelinkedToServiceListingId` (`linkedToServiceListingId`),
	CONSTRAINT `quoteRequestServiceBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `quoteRequestServiceQuoteRequestId` FOREIGN KEY (`quoteRequestId`) REFERENCES `quoteRequest` (`quoteRequestId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `serviceListing`
	--

	CREATE TABLE IF NOT EXISTS `serviceListing` (
	`serviceListingId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL,
	`imgFile` varchar(17) NULL,
	`price` int(11) NULL,
	`minPrice` int(11) NULL,
	`maxPrice` int(11) NULL,
	`isRequestable` tinyint(1) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`serviceListingId`),
	KEY `serviceListingBusinessId` (`businessId`),
	CONSTRAINT `serviceListingBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemical`
	--

	CREATE TABLE IF NOT EXISTS `chemical` (
	`chemicalId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NOT NULL,
	`epa` text NULL,
	`ingeredients` text NULL,
	`manufacturer` text NULL,
	`dilution` text NULL,
	`targets` text NULL,
	`applicationMethod` text NULL,
	`applicationRate` float NULL,
	`defaultAmountApplied` float NULL,
	`defaultAmountAppliedUnit` varchar(10) NOT NULL DEFAULT 'ml/ft²',
	`amountInStock` float NULL,
	`amountInStockUnit` varchar(10) NOT NULL DEFAULT 'ml',
	`notesToCustomer` text NULL,
	`notesToStaff` text NULL,
	`description` text NULL,
	`condition` text NULL,
	`purchaseDate` datetime NULL,
	`purchasePrice` float NULL,
	`storageLocation` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalId`),
	KEY `chemicalBusinessId` (`businessId`),
	KEY `chemicalLinkedToCrewId` (`linkedToCrewId`),
	KEY `chemicalLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `chemicalBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalImage`
	--

	CREATE TABLE IF NOT EXISTS `chemicalImage` (
	`chemicalImageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` text NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalImageId`),
	KEY `chemicalImageBusinessId` (`businessId`),
	KEY `chemicalImageChemicalId` (`chemicalId`),
	CONSTRAINT `chemicalImageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalImageChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalTag`
	--

	CREATE TABLE IF NOT EXISTS `chemicalTag` (
	`chemicalTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalTagId`),
	KEY `chemicalTagBusinessId` (`businessId`),
	CONSTRAINT `chemicalTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `chemicalChemicalTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `chemicalChemicalTagBridge` (
	`chemicalChemicalTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`chemicalTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalChemicalTagId`),
	KEY `chemicalChemicalTagBusinessId` (`businessId`),
	KEY `chemicalChemicalTagChemicalId` (`chemicalId`),
	KEY `chemicalChemicalTagChemicalTagId` (`chemicalTagId`),
	CONSTRAINT `chemicalChemicalTagChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalChemicalTagChemicalTagId` FOREIGN KEY (`chemicalTagId`) REFERENCES `chemicalTag` (`chemicalTagId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalChemicalTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipment`
	--

	CREATE TABLE IF NOT EXISTS `equipment` (
	`equipmentId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NOT NULL,
	`description` text NULL,
	`condition` text NULL,
	`model` text NULL,
	`serialNumber` text NULL,
	`purchaseDate` datetime NULL,
	`purchasePrice` float NULL,
	`storageLocation` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentId`),
	KEY `equipmentBusinessId` (`businessId`),
	KEY `equipmentLinkedToCrewId` (`linkedToCrewId`),
	KEY `equipmentLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `equipmentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentChemicalBridge`
	--

	CREATE TABLE IF NOT EXISTS `equipmentChemicalBridge` (
	`equipmentChemicalId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentChemicalId`),
	KEY `equipmentChemicalBridgeBusinessId` (`businessId`),
	KEY `equipmentChemicalBridgeEquipmentId` (`equipmentId`),
	KEY `equipmentChemicalBridgeChemicalId` (`chemicalId`),
	CONSTRAINT `equipmentChemicalBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentChemicalBridgeEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentChemicalBridgeChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentImage`
	--

	CREATE TABLE IF NOT EXISTS `equipmentImage` (
	`equipmentImageId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentImageId`),
	KEY `equipmentImageBusinessId` (`businessId`),
	KEY `equipmentImageEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentImageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentImageEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentMaintenanceLog`
	--

	CREATE TABLE IF NOT EXISTS `equipmentMaintenanceLog` (
	`equipmentMaintenanceLogId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`title` text NOT NULL,
	`details` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentMaintenanceLogId`),
	KEY `equipmentMaintenanceLogBusinessId` (`businessId`),
	KEY `equipmentMaintenanceLogEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentMaintenanceLogBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentMaintenanceLogEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentTag`
	--

	CREATE TABLE IF NOT EXISTS `equipmentTag` (
	`equipmentTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentTagId`),
	KEY `equipmentTagBusinessId` (`businessId`),
	CONSTRAINT `equipmentTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `equipmentEquipmentTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `equipmentEquipmentTagBridge` (
	`equipmentEquipmentTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`equipmentTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentEquipmentTagId`),
	KEY `equipmentEquipmentTagBusinessId` (`businessId`),
	KEY `equipmentEquipmentTagEquipmentId` (`equipmentId`),
	KEY `equipmentEquipmentTagEquipmentTagId` (`equipmentTagId`),
	CONSTRAINT `equipmentEquipmentTagEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentEquipmentTagEquipmentTagId` FOREIGN KEY (`equipmentTagId`) REFERENCES `equipmentTag` (`equipmentTagId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentEquipmentTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `docId`
	--

	CREATE TABLE IF NOT EXISTS `docId` (
	`docIdId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`incrementalId` int(11) NOT NULL,
	`randomId` int(11) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`docIdId`),
	KEY `docIdBusinessId` (`businessId`),
	CONSTRAINT `docIdBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
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
	`customerName` text NULL COMMENT 'NULL if linkedToCustomerId',
	`customerEmail` text NULL COMMENT 'NULL if linkedToCustomerId',
	`subject` text NOT NULL,
	`isResolved` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerServiceTicketId`),
	KEY `customerServiceTicketBusinessId` (`businessId`),
	KEY `customerServiceTicketDocIdId` (`docIdId`),
	KEY `customerServiceTicketLinkedToCustomerId` (`linkedToCustomerId`),
	KEY `customerServiceTicketLinkedToInvoiceId` (`linkedToInvoiceId`),
	KEY `customerServiceTicketLinkedToEstimateId` (`linkedToEstimateId`),
	KEY `customerServiceTicketLinkedToQuoteRequestId` (`linkedToQuoteRequestId`),
	CONSTRAINT `customerServiceTicketBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerServiceTicketDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE,
	CONSTRAINT `customerServiceTicketLinkedToCustomerId` FOREIGN KEY (`linkedToCustomerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
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
	CONSTRAINT `adminCustomerServiceMessageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `adminCustomerServiceMessageAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE,
	CONSTRAINT `adminCustomerServiceMessageCustomerServiceTicketId` FOREIGN KEY (`customerServiceTicketId`) REFERENCES `customerServiceTicket` (`customerServiceTicketId`) ON DELETE CASCADE
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
	CONSTRAINT `customerCustomerServiceMessageBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerCustomerServiceMessageCustomerServiceTicketId` FOREIGN KEY (`customerServiceTicketId`) REFERENCES `customerServiceTicket` (`customerServiceTicketId`) ON DELETE CASCADE
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
	CONSTRAINT `fileUploadBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `fileUploadDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
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
	CONSTRAINT `estimateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `estimateCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE,
	CONSTRAINT `estimateDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `estimateItem`
	--

	CREATE TABLE IF NOT EXISTS `estimateItem` (
	`estimateItemId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`estimateId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`estimateItemId`),
	KEY `estimateItemBusinessId` (`businessId`),
	KEY `estimateItemEstimateId` (`estimateId`),
	CONSTRAINT `estimateItemBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `estimateItemEstimateId` FOREIGN KEY (`estimateId`) REFERENCES `estimate` (`estimateId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `estimateApproval`
	--

	CREATE TABLE IF NOT EXISTS `estimateApproval` (
	`estimateApprovalId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`estimateId` varchar(17) NOT NULL,
	`approvedByAdminId` varchar(17) NULL COMMENT 'Optional FK',
	`adminReason` text NOT NULL,
	`dateTimeApproved` datetime NOT NULL,
	PRIMARY KEY (`estimateApprovalId`),
	KEY `estimateApprovalBusinessId` (`businessId`),
	KEY `estimateApprovalEstimateId` (`estimateId`),
	KEY `estimateApprovalapprovedByAdminId` (`approvedByAdminId`),
	CONSTRAINT `estimateApprovalBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `estimateApprovalEstimateId` FOREIGN KEY (`estimateId`) REFERENCES `estimate` (`estimateId`) ON DELETE CASCADE,
	CONSTRAINT `estimateApprovalapprovedByAdminId` FOREIGN KEY (`approvedByAdminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE
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
	CONSTRAINT `invoiceBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `invoiceItem`
	--

	CREATE TABLE IF NOT EXISTS `invoiceItem` (
	`invoiceItemId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`invoiceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`invoiceItemId`),
	KEY `invoiceItemBusinessId` (`businessId`),
	KEY `invoiceItemInvoiceId` (`invoiceId`),
	CONSTRAINT `invoiceItemBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceItemInvoiceId` FOREIGN KEY (`invoiceId`) REFERENCES `invoice` (`invoiceId`) ON DELETE CASCADE
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
	`notes` text NULL,
	`excessWasAddedToCredit` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`paymentId`),
	KEY `paymentBusinessId` (`businessId`),
	KEY `paymentCustomerId` (`customerId`),
	KEY `paymentLinkedToInvoiceId` (`linkedToInvoiceId`),
	CONSTRAINT `paymentBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `paymentCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `property`
	--

	CREATE TABLE IF NOT EXISTS `property` (
	`propertyId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`customerId` varchar(17) NOT NULL,
	`address1` text NOT NULL,
	`address2` text NULL,
	`city` text NULL,
	`state` text NULL,
	`zipCode` int(11) NULL,
	`lawnSize` int(11) NULL,
	`mulchQuantity`int(11) NULL,
	`pricePerMow` float NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`propertyId`),
	KEY `propertyBusinessId` (`businessId`),
	KEY `propertyCustomerId` (`customerId`),
	CONSTRAINT `propertyBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `propertyCustomerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE
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
	`weatherDescription` text NULL,
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
	CONSTRAINT `chemicalApplicationBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalApplicationChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalApplicationPropertyId` FOREIGN KEY (`propertyId`) REFERENCES `property` (`propertyId`) ON DELETE CASCADE
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
	CONSTRAINT `jobCancellationBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
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
	`name` text NOT NULL,
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
	CONSTRAINT `jobCompletedBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobRecurring`
	--

	CREATE TABLE IF NOT EXISTS `jobRecurring` (
	`jobRecurringId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToCustomerId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NOT NULL,
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
	CONSTRAINT `jobRecurringBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
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
	`name` text NOT NULL,
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
	CONSTRAINT `jobSingularBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobSingularCrewBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobSingularCrewBridge` (
	`jobSingularCrewId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`jobSingularId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobSingularCrewId`),
	KEY `jobSingularCrewBridgeBusinessId` (`businessId`),
	KEY `jobSingularCrewBridgeJobSingularId` (`jobSingularId`),
	KEY `jobSingularCrewBridgecrewId` (`crewId`),
	CONSTRAINT `jobSingularCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobSingularCrewBridgeJobSingularId` FOREIGN KEY (`jobSingularId`) REFERENCES `jobSingular` (`jobSingularId`) ON DELETE CASCADE,
	CONSTRAINT `jobSingularCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE
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
	CONSTRAINT `jobRecurringCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobRecurringCrewBridgeJobRecurringId` FOREIGN KEY (`jobRecurringId`) REFERENCES `jobRecurring` (`jobRecurringId`) ON DELETE CASCADE,
	CONSTRAINT `jobRecurringCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE
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
	CONSTRAINT `jobCompletedCrewBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobCompletedCrewBridgeJobCompletedId` FOREIGN KEY (`jobCompletedId`) REFERENCES `jobCompleted` (`jobCompletedId`) ON DELETE CASCADE,
	CONSTRAINT `jobCompletedCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staff`
	--

	CREATE TABLE IF NOT EXISTS `staff` (
	`staffId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`firstName` text NOT NULL,
	`lastName` text NULL,
	`profilePicture` varchar(17) NULL,
	`jobTitle` text NULL,
	`bio` text NULL,
	`payrollAddress1` text NULL,
	`payrollAddress2` text NULL,
	`payrollState` text NULL,
	`payrollCity` text NULL,
	`payrollZipCode` text NULL,
	`overridePayrollType` varchar(10) NULL,
	`overrideHourlyRate` float NULL,
	`overridePerJobRate` float NULL,
	`overrideJobPercentage` int(11) NULL,
	`payrollDueCache` float NOT NULL,
	`advancePaymentCache` float NOT NULL,
	`allowSignIn` tinyint(1) NOT NULL,
	`password` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffId`),
	KEY `staffBusinessId` (`businessId`),
	CONSTRAINT `staffBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `staffPhoneNumber` (
	`staffPhoneNumberId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`phonePrefix` text NULL DEFAULT NULL,
	`phone1` text NOT NULL,
	`phone2` text NOT NULL,
	`phone3` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffPhoneNumberId`),
	KEY `staffPhoneNumberBusinessId` (`businessId`),
	KEY `staffPhoneNumberStaffId` (`staffId`),
	CONSTRAINT `staffPhoneNumberBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `staffPhoneNumberStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `staffEmailAddress` (
	`staffEmailAddressId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`email` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffEmailAddressId`),
	KEY `staffEmailAddressBusinessId` (`businessId`),
	KEY `staffEmailAddressStaffId` (`staffId`),
	CONSTRAINT `staffEmailAddressBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `staffEmailAddressStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewLeaderBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewLeaderBridge` (
	`crewLeaderId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewLeaderId`),
	KEY `crewLeaderBridgeBusinessId` (`businessId`),
	KEY `crewLeaderBridgeCrewId` (`crewId`),
	KEY `crewLeaderBridgeStaffId` (`staffId`),
	CONSTRAINT `crewLeaderBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `crewLeaderBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewLeaderBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `crewStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewStaffBridge` (
	`crewStaffId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewStaffId`),
	KEY `crewStaffBridgeBusinessId` (`businessId`),
	KEY `crewStaffBridgeCrewId` (`crewId`),
	KEY `crewStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `crewStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `crewStaffBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `staffLoginAttempt` (
	`staffLoginAttemptId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) DEFAULT NULL,
	`clientIp` text NOT NULL,
	`result` varchar(5) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffLoginAttemptId`),
	KEY `staffLoginAttemptBusinessId` (`businessId`),
	KEY `staffLoginAttemptStaffId` (`staffId`),
	CONSTRAINT `staffLoginAttemptBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `staffLoginAttemptStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
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
	CONSTRAINT `staffSavedLoginBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `staffSavedLoginStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffTag`
	--

	CREATE TABLE IF NOT EXISTS `staffTag` (
	`staffTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffTagId`),
	KEY `staffTagBusinessId` (`businessId`),
	CONSTRAINT `staffTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `staffStaffTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `staffStaffTagBridge` (
	`staffStaffTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`staffTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffStaffTagId`),
	KEY `staffStaffTagBusinessId` (`businessId`),
	KEY `staffStaffTagStaffId` (`staffId`),
	KEY `staffStaffTagStaffTagId` (`staffTagId`),
	CONSTRAINT `staffStaffTagStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE,
	CONSTRAINT `staffStaffTagStaffTagId` FOREIGN KEY (`staffTagId`) REFERENCES `staffTag` (`staffTagId`) ON DELETE CASCADE,
	CONSTRAINT `staffStaffTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `jobSingularStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `jobSingularStaffBridge` (
	`jobSingularStaffId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`jobSingularId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`jobSingularStaffId`),
	KEY `jobSingularStaffBridgeBusinessId` (`businessId`),
	KEY `jobSingularStaffBridgeJobSingularId` (`jobSingularId`),
	KEY `jobSingularStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `jobSingularStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobSingularStaffBridgeJobSingularId` FOREIGN KEY (`jobSingularId`) REFERENCES `jobSingular` (`jobSingularId`) ON DELETE CASCADE,
	CONSTRAINT `jobSingularStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
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
	CONSTRAINT `jobRecurringStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobRecurringStaffBridgeJobRecurringId` FOREIGN KEY (`jobRecurringId`) REFERENCES `jobRecurring` (`jobRecurringId`) ON DELETE CASCADE,
	CONSTRAINT `jobRecurringStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
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
	CONSTRAINT `jobCompletedStaffBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `jobCompletedStaffBridgeJobCompletedId` FOREIGN KEY (`jobCompletedId`) REFERENCES `jobCompleted` (`jobCompletedId`) ON DELETE CASCADE,
	CONSTRAINT `jobCompletedStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
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
	CONSTRAINT `timeLogBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `timeLogStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `payrollDue`
	--

	CREATE TABLE IF NOT EXISTS `payrollDue` (
	`payrollDueId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`linkedToTimeLogId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToJobCompletedId` varchar(17) NULL COMMENT 'Optional FK',
	`amount` float NOT NULL,
	`notes` text NULL,
	`isManualPaid` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollDueId`),
	KEY `payrollDueBusinessId` (`businessId`),
	KEY `payrollDueStaffId` (`staffId`),
	KEY `payrollDueLinkedToTimeLogId` (`linkedToTimeLogId`),
	KEY `payrollDueLinkedToJobCompletedId` (`linkedToJobCompletedId`),
	CONSTRAINT `payrollDueBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `payrollDueStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
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
	`excessWasAddedToAdvancePay` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollSatisfactionId`),
	KEY `payrollSatisfactionBusinessId` (`businessId`),
	KEY `payrollSatisfactionStaffId` (`staffId`),
	KEY `payrollSatisfactionLinkedToPayrollDueId` (`linkedToPayrollDueId`),
	CONSTRAINT `payrollSatisfactionBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `payrollSatisfactionStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `mailoutCampaignTemplate`
	--

	CREATE TABLE IF NOT EXISTS `mailoutCampaignTemplate` (
	`mailoutCampaignTemplateId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`campaignName` text NOT NULL,
	`subject` text NOT NULL,
	`headerFile` varchar(17) NOT NULL,
	`bodyFile` varchar(17) NOT NULL,
	`footerFile` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`mailoutCampaignTemplateId`),
	KEY `mailoutCampaignTemplateBusinessId` (`businessId`),
	CONSTRAINT `mailoutCampaignTemplateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `mailoutSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `mailoutSubscriptionBridge` (
	`mailoutSubscriptionId` int(11) NOT NULL AUTO_INCREMENT,
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
	CONSTRAINT `mailoutSubscriptionBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `mailoutSubscriptionBridgeCustomerEmailAddressId` FOREIGN KEY (`customerEmailAddressId`) REFERENCES `customerEmailAddress` (`customerEmailAddressId`) ON DELETE CASCADE,
	CONSTRAINT `mailoutSubscriptionBridgeMailoutCampaignTemplateId` FOREIGN KEY (`mailoutCampaignTemplateId`) REFERENCES `mailoutCampaignTemplate` (`mailoutCampaignTemplateId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `emailSend`
	--

	CREATE TABLE IF NOT EXISTS `emailSend` (
	`emailSendId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`linkedToMailoutSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToMailoutCampaignTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`subject` text NOT NULL,
	`headerFile` varchar(17) NOT NULL,
	`bodyFile` varchar(17) NOT NULL,
	`footerFile` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailSendId`),
	KEY `emailSendBusinessId` (`businessId`),
	KEY `emailSendLinkedToMailoutSubscriptionId` (`linkedToMailoutSubscriptionId`),
	KEY `emailSendLinkedToMailoutCampaignTemplateId` (`linkedToMailoutCampaignTemplateId`),
	CONSTRAINT `emailSendBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerEmailAddressEmailSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `customerEmailAddressEmailSendBridge` (
	`customerEmailAddressEmailSendId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`customerEmailAddressId` varchar(17) NOT NULL,
	`emailSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerEmailAddressEmailSendId`),
	KEY `customerEmailAddressEmailSendBridgeBusinessId` (`businessId`),
	KEY `customerEmailAddressEmailSendBridgeCustomerEmailAddressId` (`customerEmailAddressId`),
	KEY `customerEmailAddressEmailSendBridgeEmailSendId` (`emailSendId`),
	CONSTRAINT `customerEmailAddressEmailSendBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerEmailAddressEmailSendBridgeCustomerEmailAddressId` FOREIGN KEY (`customerEmailAddressId`) REFERENCES `customerEmailAddress` (`customerEmailAddressId`) ON DELETE CASCADE,
	CONSTRAINT `customerEmailAddressEmailSendBridgeEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`) ON DELETE CASCADE
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
	`clientIpRead` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailPixelId`),
	KEY `emailPixelBusinessId` (`businessId`),
	KEY `emailPixelEmailSendId` (`emailSendId`),
	CONSTRAINT `emailPixelBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `emailPixelEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`) ON DELETE CASCADE
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
	CONSTRAINT `smsCampaignTemplateBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `smsSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `smsSubscriptionBridge` (
	`smsSubscriptionId` int(11) NOT NULL AUTO_INCREMENT,
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
	CONSTRAINT `smsSubscriptionBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `smsSubscriptionBridgeCustomerPhoneNumberId` FOREIGN KEY (`customerPhoneNumberId`) REFERENCES `customerPhoneNumber` (`customerPhoneNumberId`) ON DELETE CASCADE,
	CONSTRAINT `smsSubscriptionBridgeMailoutCampaignTemplateId` FOREIGN KEY (`smsCampaignTemplateId`) REFERENCES `smsCampaignTemplate` (`smsCampaignTemplateId`) ON DELETE CASCADE
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
	CONSTRAINT `smsSendBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `customerPhoneNumberSmsSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `customerPhoneNumberSmsSendBridge` (
	`customerPhoneNumberSmsSendId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`customerPhoneNumberId` varchar(17) NOT NULL,
	`smsSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`customerPhoneNumberSmsSendId`),
	KEY `customerPhoneNumberSmsSendBridgeBusinessId` (`businessId`),
	KEY `customerPhoneNumberSmsSendBridgeCustomerPhoneNumberId` (`customerPhoneNumberId`),
	KEY `customerPhoneNumberSmsSendBridgeSmsSendId` (`smsSendId`),
	CONSTRAINT `customerPhoneNumberSmsSendBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE,
	CONSTRAINT `customerPhoneNumberSmsSendBridgeCustomerPhoneNumberId` FOREIGN KEY (`customerPhoneNumberId`) REFERENCES `customerPhoneNumber` (`customerPhoneNumberId`) ON DELETE CASCADE,
	CONSTRAINT `customerPhoneNumberSmsSendBridgeSmsSendId` FOREIGN KEY (`smsSendId`) REFERENCES `smsSend` (`smsSendId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogPost`
	--

	CREATE TABLE IF NOT EXISTS `blogPost` (
	`blogPostId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`author` text DEFAULT NULL,
	`title` text NOT NULL,
	`description` text DEFAULT NULL,
	`bodyFile` varchar(17) NOT NULL,
	`imgFile` varchar(17) DEFAULT NULL,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`numViewsCache` int(11) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	`dateTimeEdited` datetime DEFAULT NULL,
	PRIMARY KEY (`blogPostId`),
	KEY `blogPostBusinessId` (`businessId`),
	CONSTRAINT `blogPostBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogTag`
	--

	CREATE TABLE IF NOT EXISTS `blogTag` (
	`blogTagId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogTagId`),
	KEY `blogTagBusinessId` (`businessId`),
	CONSTRAINT `blogTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogPostBlogTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `blogPostBlogTagBridge` (
	`blogPostBlogTagId` int(11) NOT NULL AUTO_INCREMENT,
	`businessId` varchar(17) NOT NULL,
	`blogPostId` varchar(17) NOT NULL,
	`blogTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogPostBlogTagId`),
	KEY `blogPostBlogTagBridgeBusinessId` (`businessId`),
	KEY `blogPostBlogTagBridgeBlogPostId` (`blogPostId`),
	KEY `blogPostBlogTagBridgeBlogTagId` (`blogTagId`),
	CONSTRAINT `blogPostBlogTagBridgeBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostBlogTagBridgeBlogTagId` FOREIGN KEY (`blogTagId`) REFERENCES `blogTag` (`blogTagId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostBlogTagBridgeBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	--
	-- Table structure for table `blogPostReadToken`
	--

	CREATE TABLE IF NOT EXISTS `blogPostReadToken` (
	`blogPostReadTokenId` varchar(17) NOT NULL,
	`businessId` varchar(17) NOT NULL,
	`blogPostId` varchar(17) NOT NULL,
	`clientIP` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogPostReadTokenId`),
	KEY `blogPostReadTokenBusinessId` (`businessId`),
	KEY `blogPostReadTokenBlogPostId` (`blogPostId`),
	CONSTRAINT `blogPostReadTokenBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostReadTokenBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
