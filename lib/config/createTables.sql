	-- Adds all tables needed for LifeMS to function. Not in any particular order, but related tables are generally put together.

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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `workspace`
	--

	CREATE TABLE IF NOT EXISTS `workspace` (
	`workspaceId` varchar(17) NOT NULL,
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
	`modCalendarEvents` tinyint(1) NOT NULL DEFAULT 0,
	`modEquip` tinyint(1) NOT NULL DEFAULT 0,
	`modChem` tinyint(1) NOT NULL DEFAULT 0,
	`modStaff` tinyint(1) NOT NULL DEFAULT 0,
	`modCrews` tinyint(1) NOT NULL DEFAULT 0,
	`modPayr` tinyint(1) NOT NULL DEFAULT 0,
	`modPayrSatLinkedToDue` tinyint(1) NOT NULL DEFAULT 0,
	`modPayrSalDefaultType` varchar(10) NOT NULL DEFAULT 'none',
	`modPayrSalBaseHourlyRate` float NOT NULL DEFAULT 0,
	`modPayrSalBaseCalendarEventPercent` int(11) NOT NULL,
	`modPayrSalBasePerCalendarEvent` float NOT NULL,
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
	`SZModCalendarEvents` tinyint(1) NOT NULL DEFAULT 0,
	`SZModCalendarEventsShowCrewCalendarEvents` tinyint(1) NOT NULL DEFAULT 0,
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
	`CPModCZCalendarEvents` tinyint(1) NOT NULL DEFAULT 0,
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
	PRIMARY KEY (`workspaceId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	
	--
	-- Table structure for table `workspacePlanPayment`
	--

	CREATE TABLE IF NOT EXISTS `workspacePlanPayment` (
	`workspacePlanPaymentId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`method` varchar(20) NOT NULL,
	`amount` float NOT NULL,
	`notes` varchar(50) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`workspacePlanPaymentId`),
	KEY `workspacePlanPaymentWorkspaceId` (`workspaceId`),
	KEY `workspacePlanPaymentAdminId` (`adminId`),
	CONSTRAINT `workspacePlanPaymentWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `workspacePlanPaymentAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `adminWorkspaceBridge`
	--

	CREATE TABLE IF NOT EXISTS `adminWorkspaceBridge` (
	`adminWorkspaceId` int(11) NOT NULL AUTO_INCREMENT,
	`adminId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`adminIsOwner` tinyint(1) NOT NULL DEFAULT 0,
	`adminCanManageTag` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanUploadDocument` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageBlog` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageSMS` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageEmail` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageServiceListing` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageQuoteRequest` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageContactService` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageTimeLog` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManagePayrollDue` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManagePayrollSatisfaction` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageContact` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageStaff` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageCrew` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageEquipment` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageChemical` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageCalendarEvent` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageInvoice` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManagePayment` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanManageEstimate` tinyint(1) NOT NULL DEFAULT 1,
	`adminCanApproveEstimate` tinyint(1) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminWorkspaceId`),
	KEY `adminWorkspaceBridgeAdminId` (`adminId`),
	KEY `adminWorkspaceBridgeWorkspaceId` (`workspaceId`),
	CONSTRAINT `adminWorkspaceBridgeAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE,
	CONSTRAINT `adminWorkspaceBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `authToken`
	--

	CREATE TABLE IF NOT EXISTS `authToken` (
	`authTokenId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NULL,
	`authName` varchar(50) NULL,
	`clientIp` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`authTokenId`),
	KEY `authTokenWorkspaceId` (`workspaceId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `crew`
	--

	CREATE TABLE IF NOT EXISTS `crew` (
	`crewId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewId`),
	KEY `crewWorkspaceId` (`workspaceId`),
	CONSTRAINT `crewWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `crewTag`
	--

	CREATE TABLE IF NOT EXISTS `crewTag` (
	`crewTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewTagId`),
	KEY `crewTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `crewTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `crewCrewTag`
	--

	CREATE TABLE IF NOT EXISTS `crewCrewTag` (
	`crewCrewTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`crewTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewCrewTagId`),
	KEY `crewCrewTagWorkspaceId` (`workspaceId`),
	KEY `crewCrewTagCrewId` (`crewId`),
	KEY `crewCrewTagCrewTagId` (`crewTagId`),
	CONSTRAINT `crewCrewTagCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewCrewTagCrewTagId` FOREIGN KEY (`crewTagId`) REFERENCES `crewTag` (`crewTagId`) ON DELETE CASCADE,
	CONSTRAINT `crewCrewTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contact`
	--

	CREATE TABLE IF NOT EXISTS `contact` (
	`contactId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
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
	PRIMARY KEY (`contactId`),
	KEY `contactWorkspaceId` (`workspaceId`),
	CONSTRAINT `contactWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `contactPhoneNumber` (
	`contactPhoneNumberId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`phonePrefix` text NULL DEFAULT NULL,
	`phone1` text NOT NULL,
	`phone2` text NULL,
	`phone3` text NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactPhoneNumberId`),
	KEY `contactPhoneNumberWorkspaceId` (`workspaceId`),
	KEY `contactPhoneNumberContactId` (`contactId`),
	CONSTRAINT `contactPhoneNumberWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactPhoneNumberContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `contactEmailAddress` (
	`contactEmailAddressId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`email` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactEmailAddressId`),
	KEY `contactEmailAddressWorkspaceId` (`workspaceId`),
	KEY `contactEmailAddressContactId` (`contactId`),
	CONSTRAINT `contactEmailAddressWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactEmailAddressContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `contactLoginAttempt` (
	`contactLoginAttemptId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NULL DEFAULT NULL,
	`clientIp` text NOT NULL,
	`result` varchar(5) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactLoginAttemptId`),
	KEY `contactLoginAttemptWorkspaceId` (`workspaceId`),
	KEY `contactLoginAttemptContactId` (`contactId`),
	CONSTRAINT `contactLoginAttemptWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactLoginAttemptContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactSavedLogin`
	--

	CREATE TABLE IF NOT EXISTS `contactSavedLogin` (
	`contactSavedLoginId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactSavedLoginId`),
	KEY `contactSavedLoginWorkspaceId` (`workspaceId`),
	KEY `contactSavedLoginContactId` (`contactId`),
	CONSTRAINT `contactSavedLoginWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactSavedLoginContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactTag`
	--

	CREATE TABLE IF NOT EXISTS `contactTag` (
	`contactTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactTagId`),
	KEY `contactTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `contactTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactContactTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `contactContactTagBridge` (
	`contactContactTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`contactTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactContactTagId`),
	KEY `contactContactTagWorkspaceId` (`workspaceId`),
	KEY `contactContactTagContactId` (`contactId`),
	KEY `contactContactTagContactTagId` (`contactTagId`),
	CONSTRAINT `contactContactTagContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE,
	CONSTRAINT `contactContactTagContactTagId` FOREIGN KEY (`contactTagId`) REFERENCES `contactTag` (`contactTagId`) ON DELETE CASCADE,
	CONSTRAINT `contactContactTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `quoteRequest`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequest` (
	`quoteRequestId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`linkedToContactId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NULL,
	`email` text NULL,
	`address1` text NULL,
	`address2` text NULL,
	`state` text NULL,
	`zipCode` int(11) NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`quoteRequestId`),
	KEY `quoteRequestWorkspaceId` (`workspaceId`),
	KEY `quoteRequestlinkedToContactId` (`linkedToContactId`),
	CONSTRAINT `quoteRequestWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `quoteRequestlinkedToContactId` FOREIGN KEY (`linkedToContactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `quoteRequestService`
	--

	CREATE TABLE IF NOT EXISTS `quoteRequestService` (
	`quoteRequestServiceId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
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
	KEY `quoteRequestServiceWorkspaceId` (`workspaceId`),
	KEY `quoteRequestServiceQuoteRequestId` (`quoteRequestId`),
	KEY `quoteRequestServicelinkedToServiceListingId` (`linkedToServiceListingId`),
	CONSTRAINT `quoteRequestServiceWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `quoteRequestServiceQuoteRequestId` FOREIGN KEY (`quoteRequestId`) REFERENCES `quoteRequest` (`quoteRequestId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `serviceListing`
	--

	CREATE TABLE IF NOT EXISTS `serviceListing` (
	`serviceListingId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL,
	`imgFile` varchar(17) NULL,
	`price` int(11) NULL,
	`minPrice` int(11) NULL,
	`maxPrice` int(11) NULL,
	`isRequestable` tinyint(1) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`serviceListingId`),
	KEY `serviceListingWorkspaceId` (`workspaceId`),
	CONSTRAINT `serviceListingWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `chemical`
	--

	CREATE TABLE IF NOT EXISTS `chemical` (
	`chemicalId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
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
	`notesToContact` text NULL,
	`notesToStaff` text NULL,
	`description` text NULL,
	`condition` text NULL,
	`purchaseDate` datetime NULL,
	`purchasePrice` float NULL,
	`storageLocation` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalId`),
	KEY `chemicalWorkspaceId` (`workspaceId`),
	KEY `chemicalLinkedToCrewId` (`linkedToCrewId`),
	KEY `chemicalLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `chemicalWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `chemicalImage`
	--

	CREATE TABLE IF NOT EXISTS `chemicalImage` (
	`chemicalImageId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` text NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalImageId`),
	KEY `chemicalImageWorkspaceId` (`workspaceId`),
	KEY `chemicalImageChemicalId` (`chemicalId`),
	CONSTRAINT `chemicalImageWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalImageChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `chemicalTag`
	--

	CREATE TABLE IF NOT EXISTS `chemicalTag` (
	`chemicalTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalTagId`),
	KEY `chemicalTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `chemicalTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `chemicalChemicalTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `chemicalChemicalTagBridge` (
	`chemicalChemicalTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`chemicalTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalChemicalTagId`),
	KEY `chemicalChemicalTagWorkspaceId` (`workspaceId`),
	KEY `chemicalChemicalTagChemicalId` (`chemicalId`),
	KEY `chemicalChemicalTagChemicalTagId` (`chemicalTagId`),
	CONSTRAINT `chemicalChemicalTagChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalChemicalTagChemicalTagId` FOREIGN KEY (`chemicalTagId`) REFERENCES `chemicalTag` (`chemicalTagId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalChemicalTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipment`
	--

	CREATE TABLE IF NOT EXISTS `equipment` (
	`equipmentId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
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
	KEY `equipmentWorkspaceId` (`workspaceId`),
	KEY `equipmentLinkedToCrewId` (`linkedToCrewId`),
	KEY `equipmentLinkedToStaffId` (`linkedToStaffId`),
	CONSTRAINT `equipmentWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipmentChemicalBridge`
	--

	CREATE TABLE IF NOT EXISTS `equipmentChemicalBridge` (
	`equipmentChemicalId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentChemicalId`),
	KEY `equipmentChemicalBridgeWorkspaceId` (`workspaceId`),
	KEY `equipmentChemicalBridgeEquipmentId` (`equipmentId`),
	KEY `equipmentChemicalBridgeChemicalId` (`chemicalId`),
	CONSTRAINT `equipmentChemicalBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentChemicalBridgeEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentChemicalBridgeChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipmentImage`
	--

	CREATE TABLE IF NOT EXISTS `equipmentImage` (
	`equipmentImageId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`imageFile` varchar(17) NOT NULL,
	`caption` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentImageId`),
	KEY `equipmentImageWorkspaceId` (`workspaceId`),
	KEY `equipmentImageEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentImageWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentImageEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipmentMaintenanceLog`
	--

	CREATE TABLE IF NOT EXISTS `equipmentMaintenanceLog` (
	`equipmentMaintenanceLogId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`title` text NOT NULL,
	`details` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentMaintenanceLogId`),
	KEY `equipmentMaintenanceLogWorkspaceId` (`workspaceId`),
	KEY `equipmentMaintenanceLogEquipmentId` (`equipmentId`),
	CONSTRAINT `equipmentMaintenanceLogWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentMaintenanceLogEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipmentTag`
	--

	CREATE TABLE IF NOT EXISTS `equipmentTag` (
	`equipmentTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentTagId`),
	KEY `equipmentTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `equipmentTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `equipmentEquipmentTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `equipmentEquipmentTagBridge` (
	`equipmentEquipmentTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`equipmentId` varchar(17) NOT NULL,
	`equipmentTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`equipmentEquipmentTagId`),
	KEY `equipmentEquipmentTagWorkspaceId` (`workspaceId`),
	KEY `equipmentEquipmentTagEquipmentId` (`equipmentId`),
	KEY `equipmentEquipmentTagEquipmentTagId` (`equipmentTagId`),
	CONSTRAINT `equipmentEquipmentTagEquipmentId` FOREIGN KEY (`equipmentId`) REFERENCES `equipment` (`equipmentId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentEquipmentTagEquipmentTagId` FOREIGN KEY (`equipmentTagId`) REFERENCES `equipmentTag` (`equipmentTagId`) ON DELETE CASCADE,
	CONSTRAINT `equipmentEquipmentTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `docId`
	--

	CREATE TABLE IF NOT EXISTS `docId` (
	`docIdId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`incrementalId` int(11) NOT NULL,
	`randomId` int(11) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`docIdId`),
	KEY `docIdWorkspaceId` (`workspaceId`),
	CONSTRAINT `docIdWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactServiceTicket`
	--

	CREATE TABLE IF NOT EXISTS `contactServiceTicket` (
	`contactServiceTicketId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`linkedToContactId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToInvoiceId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToEstimateId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToQuoteRequestId` varchar(17) NULL COMMENT 'Optional FK',
	`contactName` text NULL COMMENT 'NULL if linkedToContactId',
	`contactEmail` text NULL COMMENT 'NULL if linkedToContactId',
	`subject` text NOT NULL,
	`isResolved` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactServiceTicketId`),
	KEY `contactServiceTicketWorkspaceId` (`workspaceId`),
	KEY `contactServiceTicketDocIdId` (`docIdId`),
	KEY `contactServiceTicketLinkedToContactId` (`linkedToContactId`),
	KEY `contactServiceTicketLinkedToInvoiceId` (`linkedToInvoiceId`),
	KEY `contactServiceTicketLinkedToEstimateId` (`linkedToEstimateId`),
	KEY `contactServiceTicketLinkedToQuoteRequestId` (`linkedToQuoteRequestId`),
	CONSTRAINT `contactServiceTicketWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactServiceTicketDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE,
	CONSTRAINT `contactServiceTicketLinkedToContactId` FOREIGN KEY (`linkedToContactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `adminContactServiceMessage`
	--

	CREATE TABLE IF NOT EXISTS `adminContactServiceMessage` (
	`adminContactServiceMessageId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`adminId` varchar(17) NOT NULL,
	`contactServiceTicketId` varchar(17) NOT NULL,
	`message` text NOT NULL,
	`isReadByContact` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`adminContactServiceMessageId`),
	KEY `adminContactServiceMessageWorkspaceId` (`workspaceId`),
	KEY `adminContactServiceMessageAdminId` (`adminId`),
	KEY `adminContactServiceMessageContactServiceTicketId` (`contactServiceTicketId`),
	CONSTRAINT `adminContactServiceMessageWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `adminContactServiceMessageAdminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`) ON DELETE CASCADE,
	CONSTRAINT `adminContactServiceMessageContactServiceTicketId` FOREIGN KEY (`contactServiceTicketId`) REFERENCES `contactServiceTicket` (`contactServiceTicketId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactContactServiceMessage`
	--

	CREATE TABLE IF NOT EXISTS `contactContactServiceMessage` (
	`contactContactServiceMessageId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactServiceTicketId` varchar(17) NOT NULL,
	`message` text NOT NULL,
	`isReadByAdmin` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactContactServiceMessageId`),
	KEY `contactContactServiceMessageWorkspaceId` (`workspaceId`),
	KEY `contactContactServiceMessageContactServiceTicketId` (`contactServiceTicketId`),
	CONSTRAINT `contactContactServiceMessageWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactContactServiceMessageContactServiceTicketId` FOREIGN KEY (`contactServiceTicketId`) REFERENCES `contactServiceTicket` (`contactServiceTicketId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `fileUpload`
	--

	CREATE TABLE IF NOT EXISTS `fileUpload` (
	`fileUploadId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`linkedToStaffId` varchar(17) NULL DEFAULT NULL COMMENT 'Optional FK',
	`linkedToContactId` varchar(17) NULL DEFAULT NULL COMMENT 'Optional FK',
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`fileUploadId`),
	KEY `fileUploadWorkspaceId` (`workspaceId`),
	KEY `fileUploadLinkedToStaffId` (`linkedToStaffId`),
	KEY `fileUploadLinkedToContactId` (`linkedToContactId`),
	KEY `fileUploadDocIdId` (`docIdId`),
	CONSTRAINT `fileUploadWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `fileUploadDocIdId` FOREIGN KEY (`docIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `estimate`
	--

	CREATE TABLE IF NOT EXISTS `estimate` (
	`estimateId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`discountIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`discount` float NOT NULL DEFAULT 0,
	`customCalendarEventDetails` text NULL,
	`comments` text NULL,
	`privateNotes` text NULL,
	`isViewed` tinyint(1) NOT NULL DEFAULT 0,
	`isEmailed` tinyint(1) NOT NULL DEFAULT 0,
	`approvedByAdminId` varchar(17) NULL COMMENT 'Optional FK',
	`adminReason` text NULL,
	`dateTimeApproved` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`estimateId`),
	KEY `estimateWorkspaceId` (`workspaceId`),
	KEY `estimateContactId` (`contactId`),
	KEY `estimateDocIdId` (`docIdId`),
	CONSTRAINT `estimateWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `estimateContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE,
	CONSTRAINT `estimateDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `estimateItem`
	--

	CREATE TABLE IF NOT EXISTS `estimateItem` (
	`estimateItemId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`estimateId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`estimateItemId`),
	KEY `estimateItemWorkspaceId` (`workspaceId`),
	KEY `estimateItemEstimateId` (`estimateId`),
	CONSTRAINT `estimateItemWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `estimateItemEstimateId` FOREIGN KEY (`estimateId`) REFERENCES `estimate` (`estimateId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `invoice`
	--

	CREATE TABLE IF NOT EXISTS `invoice` (
	`invoiceId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`docIdId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`discountIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`discount` float NOT NULL DEFAULT 0,
	`customCalendarEventDetails` text NULL,
	`comments` text NULL,
	`privateNotes` text NULL,
	`isManualPaid` tinyint(1) NOT NULL DEFAULT 0,
	`isViewed` tinyint(1) NOT NULL DEFAULT 0,
	`isEmailed` tinyint(1) NOT NULL DEFAULT 0,
	`isOverdueNotified` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`invoiceId`),
	KEY `invoiceWorkspaceId` (`workspaceId`),
	KEY `invoiceContactId` (`contactId`),
	KEY `invoiceDocIdId` (`docIdId`),
	CONSTRAINT `invoiceWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceDocIdId` FOREIGN KEY (`DocIdId`) REFERENCES `docId` (`docIdId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `invoiceItem`
	--

	CREATE TABLE IF NOT EXISTS `invoiceItem` (
	`invoiceItemId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`invoiceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`price` float NOT NULL DEFAULT 0,
	`taxIsPercent` tinyint(1) NOT NULL DEFAULT 0,
	`tax` float NOT NULL DEFAULT 0,
	`quantity` INT(11) NOT NULL DEFAULT 1,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`invoiceItemId`),
	KEY `invoiceItemWorkspaceId` (`workspaceId`),
	KEY `invoiceItemInvoiceId` (`invoiceId`),
	CONSTRAINT `invoiceItemWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `invoiceItemInvoiceId` FOREIGN KEY (`invoiceId`) REFERENCES `invoice` (`invoiceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `paymentMethod`
	--

	CREATE TABLE IF NOT EXISTS `paymentMethod` (
	`paymentMethodId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` varchar(20) NOT NULL,
	`percentCut` float NOT NULL DEFAULT 0,
	`amountCut` float NOT NULL DEFAULT 0,
	`notes` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`paymentMethodId`),
	KEY `paymentMethodWorkspaceId` (`workspaceId`),
	CONSTRAINT `paymentMethodWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `payment`
	--

	CREATE TABLE IF NOT EXISTS `payment` (
	`paymentId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
	`linkedToInvoiceId` varchar(17) NULL,
	`linkedToPaymentMethodId` varchar(17) NULL,
	`methodName` varchar(20) NOT NULL,
	`methodPercentCut` float NOT NULL DEFAULT 0,
	`methodAmountCut` float NOT NULL DEFAULT 0,
	`amount` float NOT NULL,
	`notes` text NULL,
	`excessWasAddedToCredit` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`paymentId`),
	KEY `paymentWorkspaceId` (`workspaceId`),
	KEY `paymentContactId` (`contactId`),
	KEY `paymentLinkedToInvoiceInvoiceId` (`linkedToInvoiceId`),
	CONSTRAINT `paymentWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `paymentContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `property`
	--

	CREATE TABLE IF NOT EXISTS `property` (
	`propertyId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`contactId` varchar(17) NOT NULL,
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
	KEY `propertyWorkspaceId` (`workspaceId`),
	KEY `propertyContactId` (`contactId`),
	CONSTRAINT `propertyWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `propertyContactId` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `chemicalApplication`
	--

	CREATE TABLE IF NOT EXISTS `chemicalApplication` (
	`chemicalApplicationId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`chemicalId` varchar(17) NOT NULL,
	`propertyId` varchar(17) NOT NULL,
	`linkedToCrewId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToStaffId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToCalendarEventCompletedId` varchar(17) NULL COMMENT 'Optional FK',
	`weatherDescription` text NULL,
	`amountApplied` float NULL,
	`wasSubtractedFromStock` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`chemicalApplicationId`),
	KEY `chemicalApplicationWorkspaceId` (`workspaceId`),
	KEY `chemicalApplicationChemicalId` (`chemicalId`),
	KEY `chemicalApplicationPropertyId` (`propertyId`),
	KEY `chemicalApplicationLinkedToCrewId` (`linkedToCrewId`),
	KEY `chemicalApplicationLinkedToStaffId` (`linkedToStaffId`),
	KEY `chemicalApplicationLinkedToCalendarEventCompletedId` (`linkedToCalendarEventCompletedId`),
	CONSTRAINT `chemicalApplicationWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalApplicationChemicalId` FOREIGN KEY (`chemicalId`) REFERENCES `chemical` (`chemicalId`) ON DELETE CASCADE,
	CONSTRAINT `chemicalApplicationPropertyId` FOREIGN KEY (`propertyId`) REFERENCES `property` (`propertyId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `calendarEvent`
	--

	CREATE TABLE IF NOT EXISTS `calendarEvent` (
	`calendarEventId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`linkedToCalendarEventId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToContactId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` float NULL,
	`isPrepaid` tinyint(1) NOT NULL DEFAULT 0,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`weekday` varchar(20) NULL DEFAULT NULL,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`isCancelled` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`calendarEventId`),
	KEY `calendarEventWorkspaceId` (`workspaceId`),
	KEY `calendarEventLinkedToCalendarEventId` (`linkedToCalendarEventId`),
	KEY `calendarEventLinkedToContactId` (`linkedToContactId`),
	KEY `calendarEventLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `calendarEventWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `calendarEventCancellation`
	--

	CREATE TABLE IF NOT EXISTS `calendarEventInstanceException` (
	`calendarEventInstanceExceptionId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`calendarEventId` varchar(17) NOT NULL,
	`instanceDate` date NOT NULL,
	`isRescheduled` tinyint(1) NOT NULL DEFAULT 0,
	`isCancelled` tinyint(1) NOT NULL DEFAULT 0,
	`isCompleted` tinyint(1) NOT NULL DEFAULT 0,
	`linkedToCompletedCalendarEventId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToContactId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`name` text NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` float NULL,
	`isPrepaid` tinyint(1) NOT NULL DEFAULT 0,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`calendarEventInstanceExceptionId`),
	KEY `calendarEventInstanceExceptionWorkspaceId` (`workspaceId`),
	KEY `calendarEventInstanceExceptionCalendarEventId` (`calendarEventId`),
	KEY `calendarEventInstanceExceptionLinkedToContactId` (`linkedToContactId`),
	KEY `calendarEventInstanceExceptionLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `calendarEventInstanceExceptionWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `calendarEventInstanceExceptionCalendarEventId` FOREIGN KEY (`calendarEventId`) REFERENCES `calendarEvent` (`calendarEventId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `completedCalendarEvent` - lots of plain text as this is meant to be an archive that doesn't get deleted even if related records are changed or removed.
	--

	CREATE TABLE IF NOT EXISTS `completedCalendarEvent` (
	`completedCalendarEventId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`linkedToCalendarEventId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToContactId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToPropertyId` varchar(17) NULL COMMENT 'Optional FK',
	`contactFirstName` text NULL,
	`contactLastName` text NULL,
	`propertyAddress1` text NULL,
	`propertyAddress2` text NULL,
	`propertyCity` text NULL,
	`propertyState` text NULL,
	`propertyZipCode` int(11) NULL,
	`name` text NOT NULL,
	`description` text NULL,
	`privateNotes` text NULL,
	`price` float NULL,
	`estHours` float NULL,
	`isPrepaid` tinyint(1) NOT NULL DEFAULT 0,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`weekday` varchar(20) NULL DEFAULT NULL,
	`startDateTime` datetime NOT NULL,
	`endDateTime` datetime NULL,
	`instanceDate` datetime NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`completedCalendarEventId`),
	KEY `completedCalendarEventWorkspaceId` (`workspaceId`),
	KEY `completedCalendarEventLinkedToCalendarEventId` (`linkedToCalendarEventId`),
	KEY `completedCalendarEventLinkedToContactId` (`linkedToContactId`),
	KEY `completedCalendarEventLinkedToPropertyId` (`linkedToPropertyId`),
	CONSTRAINT `completedCalendarEventWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `calendarEventCrewBridge`
	--

	CREATE TABLE IF NOT EXISTS `calendarEventCrewBridge` (
	`calendarEventCrewId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`calendarEventId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`calendarEventCrewId`),
	KEY `calendarEventCrewBridgeWorkspaceId` (`workspaceId`),
	KEY `calendarEventCrewBridgeCalendarEventId` (`calendarEventId`),
	KEY `calendarEventCrewBridgecrewId` (`crewId`),
	CONSTRAINT `calendarEventCrewBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `calendarEventCrewBridgeCalendarEventId` FOREIGN KEY (`calendarEventId`) REFERENCES `calendarEvent` (`calendarEventId`) ON DELETE CASCADE,
	CONSTRAINT `calendarEventCrewBridgecrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staff`
	--

	CREATE TABLE IF NOT EXISTS `staff` (
	`staffId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`firstName` text NOT NULL,
	`lastName` text NULL,
	`profilePicture` varchar(17) NULL,
	`calendarEventTitle` text NULL,
	`bio` text NULL,
	`payrollAddress1` text NULL,
	`payrollAddress2` text NULL,
	`payrollState` text NULL,
	`payrollCity` text NULL,
	`payrollZipCode` text NULL,
	`overridePayrollType` varchar(10) NULL,
	`overrideHourlyRate` float NULL,
	`overridePerCalendarEventRate` float NULL,
	`overrideCalendarEventPercentage` int(11) NULL,
	`payrollDueCache` float NOT NULL,
	`advancePaymentCache` float NOT NULL,
	`allowSignIn` tinyint(1) NOT NULL,
	`password` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffId`),
	KEY `staffWorkspaceId` (`workspaceId`),
	CONSTRAINT `staffWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffPhoneNumber`
	--

	CREATE TABLE IF NOT EXISTS `staffPhoneNumber` (
	`staffPhoneNumberId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`phonePrefix` text NULL DEFAULT NULL,
	`phone1` text NOT NULL,
	`phone2` text NOT NULL,
	`phone3` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffPhoneNumberId`),
	KEY `staffPhoneNumberWorkspaceId` (`workspaceId`),
	KEY `staffPhoneNumberStaffId` (`staffId`),
	CONSTRAINT `staffPhoneNumberWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `staffPhoneNumberStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffEmailAddress`
	--

	CREATE TABLE IF NOT EXISTS `staffEmailAddress` (
	`staffEmailAddressId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`email` text NOT NULL,
	`description` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffEmailAddressId`),
	KEY `staffEmailAddressWorkspaceId` (`workspaceId`),
	KEY `staffEmailAddressStaffId` (`staffId`),
	CONSTRAINT `staffEmailAddressWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `staffEmailAddressStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `crewLeaderBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewLeaderBridge` (
	`crewLeaderId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewLeaderId`),
	KEY `crewLeaderBridgeWorkspaceId` (`workspaceId`),
	KEY `crewLeaderBridgeCrewId` (`crewId`),
	KEY `crewLeaderBridgeStaffId` (`staffId`),
	CONSTRAINT `crewLeaderBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `crewLeaderBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewLeaderBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `crewStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `crewStaffBridge` (
	`crewStaffId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`crewId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`crewStaffId`),
	KEY `crewStaffBridgeWorkspaceId` (`workspaceId`),
	KEY `crewStaffBridgeCrewId` (`crewId`),
	KEY `crewStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `crewStaffBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `crewStaffBridgeCrewId` FOREIGN KEY (`crewId`) REFERENCES `crew` (`crewId`) ON DELETE CASCADE,
	CONSTRAINT `crewStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffLoginAttempt`
	--

	CREATE TABLE IF NOT EXISTS `staffLoginAttempt` (
	`staffLoginAttemptId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) DEFAULT NULL,
	`clientIp` text NOT NULL,
	`result` varchar(5) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffLoginAttemptId`),
	KEY `staffLoginAttemptWorkspaceId` (`workspaceId`),
	KEY `staffLoginAttemptStaffId` (`staffId`),
	CONSTRAINT `staffLoginAttemptWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `staffLoginAttemptStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffSavedLogin`
	--

	CREATE TABLE IF NOT EXISTS `staffSavedLogin` (
	`staffSavedLoginId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffSavedLoginId`),
	KEY `staffSavedLoginWorkspaceId` (`workspaceId`),
	KEY `staffSavedLoginStaffId` (`staffId`),
	CONSTRAINT `staffSavedLoginWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `staffSavedLoginStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffTag`
	--

	CREATE TABLE IF NOT EXISTS `staffTag` (
	`staffTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffTagId`),
	KEY `staffTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `staffTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `staffStaffTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `staffStaffTagBridge` (
	`staffStaffTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`staffTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`staffStaffTagId`),
	KEY `staffStaffTagWorkspaceId` (`workspaceId`),
	KEY `staffStaffTagStaffId` (`staffId`),
	KEY `staffStaffTagStaffTagId` (`staffTagId`),
	CONSTRAINT `staffStaffTagStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE,
	CONSTRAINT `staffStaffTagStaffTagId` FOREIGN KEY (`staffTagId`) REFERENCES `staffTag` (`staffTagId`) ON DELETE CASCADE,
	CONSTRAINT `staffStaffTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `calendarEventStaffBridge`
	--

	CREATE TABLE IF NOT EXISTS `calendarEventStaffBridge` (
	`calendarEventStaffId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`calendarEventId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`calendarEventStaffId`),
	KEY `calendarEventStaffBridgeWorkspaceId` (`workspaceId`),
	KEY `calendarEventStaffBridgeCalendarEventId` (`calendarEventId`),
	KEY `calendarEventStaffBridgeStaffId` (`staffId`),
	CONSTRAINT `calendarEventStaffBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `calendarEventStaffBridgeCalendarEventId` FOREIGN KEY (`calendarEventId`) REFERENCES `calendarEvent` (`calendarEventId`) ON DELETE CASCADE,
	CONSTRAINT `calendarEventStaffBridgeStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `timeLog`
	--

	CREATE TABLE IF NOT EXISTS `timeLog` (
	`timeLogId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`dateTimeStart` datetime NOT NULL,
	`dateTimeEnd` datetime NULL,
	`notes` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`timeLogId`),
	KEY `timeLogWorkspaceId` (`workspaceId`),
	KEY `timeLogStaffId` (`staffId`),
	CONSTRAINT `timeLogWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `timeLogStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `payrollDue`
	--

	CREATE TABLE IF NOT EXISTS `payrollDue` (
	`payrollDueId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`linkedToTimeLogId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToCalendarEventCompletedId` varchar(17) NULL COMMENT 'Optional FK',
	`amount` float NOT NULL,
	`notes` text NULL,
	`isManualPaid` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollDueId`),
	KEY `payrollDueWorkspaceId` (`workspaceId`),
	KEY `payrollDueStaffId` (`staffId`),
	KEY `payrollDueLinkedToTimeLogId` (`linkedToTimeLogId`),
	KEY `payrollDueLinkedToCalendarEventCompletedId` (`linkedToCalendarEventCompletedId`),
	CONSTRAINT `payrollDueWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `payrollDueStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `payrollSatisfaction`
	--

	CREATE TABLE IF NOT EXISTS `payrollSatisfaction` (
	`payrollSatisfactionId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`staffId` varchar(17) NOT NULL,
	`linkedToPayrollDueId` varchar(17) NULL COMMENT 'Optional FK',
	`method` varchar(10) NOT NULL,
	`amount` float NOT NULL,
	`notes` text NULL,
	`excessWasAddedToAdvancePay` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`payrollSatisfactionId`),
	KEY `payrollSatisfactionWorkspaceId` (`workspaceId`),
	KEY `payrollSatisfactionStaffId` (`staffId`),
	KEY `payrollSatisfactionLinkedToPayrollDueId` (`linkedToPayrollDueId`),
	CONSTRAINT `payrollSatisfactionWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `payrollSatisfactionStaffId` FOREIGN KEY (`staffId`) REFERENCES `staff` (`staffId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `emailTemplate`
	--

	CREATE TABLE IF NOT EXISTS `emailTemplate` (
	`emailTemplateId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`templateName` text NOT NULL,
	`subject` text NOT NULL,
	`contentHtml` text NOT NULL,
	`isSystemTemplate` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailTemplateId`),
	KEY `emailTemplateWorkspaceId` (`workspaceId`),
	CONSTRAINT `emailTemplateWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `emailSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `emailSubscriptionBridge` (
	`emailSubscriptionId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`contactEmailAddressId` varchar(17) NOT NULL,
	`emailTemplateId` varchar(17) NOT NULL,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailSubscriptionId`),
	KEY `emailSubscriptionBridgeWorkspaceId` (`workspaceId`),
	KEY `emailSubscriptionBridgeContactEmailAddressId` (`contactEmailAddressId`),
	KEY `emailSubscriptionBridgeEmailTemplateId` (`emailTemplateId`),
	CONSTRAINT `emailSubscriptionBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `emailSubscriptionBridgeContactEmailAddressId` FOREIGN KEY (`contactEmailAddressId`) REFERENCES `contactEmailAddress` (`contactEmailAddressId`) ON DELETE CASCADE,
	CONSTRAINT `emailSubscriptionBridgeEmailTemplateId` FOREIGN KEY (`emailTemplateId`) REFERENCES `emailTemplate` (`emailTemplateId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `emailSend`
	--

	CREATE TABLE IF NOT EXISTS `emailSend` (
	`emailSendId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`linkedToEmailSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToEmailTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`toEmail` text NOT NULL,
	`subject` text NOT NULL,
	`contentHtmlFile` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailSendId`),
	KEY `emailSendWorkspaceId` (`workspaceId`),
	KEY `emailSendLinkedToEmailSubscriptionId` (`linkedToEmailSubscriptionId`),
	KEY `emailSendLinkedToEmailTemplateId` (`linkedToEmailTemplateId`),
	CONSTRAINT `emailSendWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactEmailAddressEmailSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `contactEmailAddressEmailSendBridge` (
	`contactEmailAddressEmailSendId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`contactEmailAddressId` varchar(17) NOT NULL,
	`emailSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactEmailAddressEmailSendId`),
	KEY `contactEmailAddressEmailSendBridgeWorkspaceId` (`workspaceId`),
	KEY `contactEmailAddressEmailSendBridgeContactEmailAddressId` (`contactEmailAddressId`),
	KEY `contactEmailAddressEmailSendBridgeEmailSendId` (`emailSendId`),
	CONSTRAINT `contactEmailAddressEmailSendBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactEmailAddressEmailSendBridgeContactEmailAddressId` FOREIGN KEY (`contactEmailAddressId`) REFERENCES `contactEmailAddress` (`contactEmailAddressId`) ON DELETE CASCADE,
	CONSTRAINT `contactEmailAddressEmailSendBridgeEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `emailPixel`
	--

	CREATE TABLE IF NOT EXISTS `emailPixel` (
	`emailPixelId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`emailSendId` varchar(17) NOT NULL,
	`pixelFile` varchar(17) NOT NULL,
	`dateTimeRead` datetime NULL,
	`clientIpRead` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailPixelId`),
	KEY `emailPixelWorkspaceId` (`workspaceId`),
	KEY `emailPixelEmailSendId` (`emailSendId`),
	CONSTRAINT `emailPixelWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `emailPixelEmailSendId` FOREIGN KEY (`emailSendId`) REFERENCES `emailSend` (`emailSendId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `smsTemplate`
	--

	CREATE TABLE IF NOT EXISTS `smsTemplate` (
	`smsTemplateId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`templateName` text NOT NULL,
	`message` text NOT NULL,
	`isSystemTemplate` tinyint(1) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsTemplateId`),
	KEY `smsTemplateWorkspaceId` (`workspaceId`),
	CONSTRAINT `smsTemplateWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `smsSubscriptionBridge`
	--

	CREATE TABLE IF NOT EXISTS `smsSubscriptionBridge` (
	`smsSubscriptionId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`contactPhoneNumberId` varchar(17) NOT NULL,
	`smsTemplateId` varchar(17) NOT NULL,
	`frequencyInterval` varchar(10) NOT NULL DEFAULT 'none',
	`frequency` int(11) NOT NULL DEFAULT 0,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsSubscriptionId`),
	KEY `smsSubscriptionBridgeWorkspaceId` (`workspaceId`),
	KEY `smsSubscriptionBridgeContactPhoneNumberId` (`contactPhoneNumberId`),
	KEY `smsSubscriptionBridgeSmsTemplateId` (`smsTemplateId`),
	CONSTRAINT `smsSubscriptionBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `smsSubscriptionBridgeContactPhoneNumberId` FOREIGN KEY (`contactPhoneNumberId`) REFERENCES `contactPhoneNumber` (`contactPhoneNumberId`) ON DELETE CASCADE,
	CONSTRAINT `smsSubscriptionBridgeSmsTemplateId` FOREIGN KEY (`smsTemplateId`) REFERENCES `smsTemplate` (`smsTemplateId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `smsSend`
	--

	CREATE TABLE IF NOT EXISTS `smsSend` (
	`smsSendId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`linkedToSmsSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToSmsCampaignTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`message` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`smsSendId`),
	KEY `smsSendWorkspaceId` (`workspaceId`),
	KEY `smsSendLinkedToSmsSubscriptionId` (`linkedToSmsSubscriptionId`),
	KEY `smsSendLinkedToSmsCampaignTemplateId` (`linkedToSmsCampaignTemplateId`),
	CONSTRAINT `smsSendWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `contactPhoneNumberSmsSendBridge`
	--

	CREATE TABLE IF NOT EXISTS `contactPhoneNumberSmsSendBridge` (
	`contactPhoneNumberSmsSendId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`contactPhoneNumberId` varchar(17) NOT NULL,
	`smsSendId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`contactPhoneNumberSmsSendId`),
	KEY `contactPhoneNumberSmsSendBridgeWorkspaceId` (`workspaceId`),
	KEY `contactPhoneNumberSmsSendBridgeContactPhoneNumberId` (`contactPhoneNumberId`),
	KEY `contactPhoneNumberSmsSendBridgeSmsSendId` (`smsSendId`),
	CONSTRAINT `contactPhoneNumberSmsSendBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE,
	CONSTRAINT `contactPhoneNumberSmsSendBridgeContactPhoneNumberId` FOREIGN KEY (`contactPhoneNumberId`) REFERENCES `contactPhoneNumber` (`contactPhoneNumberId`) ON DELETE CASCADE,
	CONSTRAINT `contactPhoneNumberSmsSendBridgeSmsSendId` FOREIGN KEY (`smsSendId`) REFERENCES `smsSend` (`smsSendId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `blogPost`
	--

	CREATE TABLE IF NOT EXISTS `blogPost` (
	`blogPostId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
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
	KEY `blogPostWorkspaceId` (`workspaceId`),
	CONSTRAINT `blogPostWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `blogTag`
	--

	CREATE TABLE IF NOT EXISTS `blogTag` (
	`blogTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogTagId`),
	KEY `blogTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `blogTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `blogPostBlogTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `blogPostBlogTagBridge` (
	`blogPostBlogTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`blogPostId` varchar(17) NOT NULL,
	`blogTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogPostBlogTagId`),
	KEY `blogPostBlogTagBridgeWorkspaceId` (`workspaceId`),
	KEY `blogPostBlogTagBridgeBlogPostId` (`blogPostId`),
	KEY `blogPostBlogTagBridgeBlogTagId` (`blogTagId`),
	CONSTRAINT `blogPostBlogTagBridgeBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostBlogTagBridgeBlogTagId` FOREIGN KEY (`blogTagId`) REFERENCES `blogTag` (`blogTagId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostBlogTagBridgeWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `blogPostReadToken`
	--

	CREATE TABLE IF NOT EXISTS `blogPostReadToken` (
	`blogPostReadTokenId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`blogPostId` varchar(17) NOT NULL,
	`clientIP` text NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`blogPostReadTokenId`),
	KEY `blogPostReadTokenWorkspaceId` (`workspaceId`),
	KEY `blogPostReadTokenBlogPostId` (`blogPostId`),
	CONSTRAINT `blogPostReadTokenBlogPostId` FOREIGN KEY (`blogPostId`) REFERENCES `blogPost` (`blogPostId`) ON DELETE CASCADE,
	CONSTRAINT `blogPostReadTokenWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `emailQueueMessage`
	--

	CREATE TABLE IF NOT EXISTS `emailQueueMessage` (
	`emailQueueMessageId` varchar(17) NOT NULL,
	`linkedToWorkspaceId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToEmailSubscriptionId` varchar(17) NULL COMMENT 'Optional FK',
	`linkedToEmailTemplateId` varchar(17) NULL COMMENT 'Optional FK',
	`messageType` text NOT NULL,
	`templateVarInputs` text NULL,
	`toEmails` text NOT NULL,
	`ccEmails` text NULL,
	`bccEmails` text NULL,
	`fromName` text NOT NULL,
	`subject` text NULL,
	`contentHtml` text NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`emailQueueMessageId`),
	KEY `emailQueueMessageLinkedToWorkspaceId` (`linkedToWorkspaceId`),
	KEY `emailQueueMessageLinkedToEmailSubscriptionId` (`linkedToEmailSubscriptionId`),
	KEY `emailQueueMessageLinkedToEmailTemplateId` (`linkedToEmailTemplateId`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update 0.1.1b

	CREATE TABLE IF NOT EXISTS `systemInfo` (
	`var` varchar(50) NOT NULL,
	`val` text NOT NULL,
	`lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`var`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update 0.1.3b

	CREATE TABLE IF NOT EXISTS `note` (
	`noteId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NULL DEFAULT NULL,
	`title` text NOT NULL,
	`bodyMarkdown` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`bodyHtml` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`viewPrivacy` varchar(10) NOT NULL DEFAULT 'private',
	`viewPass` varchar(200) NULL DEFAULT NULL,
	`editPrivacy` varchar(10) NOT NULL DEFAULT 'private',
	`editPass` varchar(200) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	`lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`noteId`),
	KEY `noteWorkspaceId` (`workspaceId`),
	CONSTRAINT `noteWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	
	--
	-- Table structure for table `noteTag`
	--

	CREATE TABLE IF NOT EXISTS `noteTag` (
	`noteTagId` varchar(17) NOT NULL,
	`workspaceId` varchar(17) NOT NULL,
	`name` text NOT NULL,
	`description` text NULL DEFAULT NULL,
	`color` varchar(15) NOT NULL DEFAULT 'gray',
	`imgFile` varchar(17) NULL DEFAULT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`noteTagId`),
	KEY `noteTagWorkspaceId` (`workspaceId`),
	CONSTRAINT `noteTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	--
	-- Table structure for table `noteNoteTagBridge`
	--

	CREATE TABLE IF NOT EXISTS `noteNoteTagBridge` (
	`noteNoteTagId` int(11) NOT NULL AUTO_INCREMENT,
	`workspaceId` varchar(17) NOT NULL,
	`noteId` varchar(17) NOT NULL,
	`noteTagId` varchar(17) NOT NULL,
	`dateTimeAdded` datetime NOT NULL,
	PRIMARY KEY (`noteNoteTagId`),
	KEY `noteNoteTagWorkspaceId` (`workspaceId`),
	KEY `noteNoteTagContactId` (`noteId`),
	KEY `noteNoteTagContactTagId` (`noteTagId`),
	CONSTRAINT `noteNoteTagContactId` FOREIGN KEY (`noteId`) REFERENCES `note` (`noteId`) ON DELETE CASCADE,
	CONSTRAINT `noteNoteTagContactTagId` FOREIGN KEY (`noteTagId`) REFERENCES `noteTag` (`noteTagId`) ON DELETE CASCADE,
	CONSTRAINT `noteNoteTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
