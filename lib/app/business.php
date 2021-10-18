<?php

	//BUSINESS FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class business {

		private string $setType;
		private databaseManager $db;

		public string $businessId;
		public string $ownerAdminId;
		public string $displayName;
		public string $adminDisplayName;
		public string $fullLogoFile;
		public string $address;
		public string $state;
		public string $city;
		public int $zipCode;
		public int $phonePrefix;
		public int $phone1;
		public int $phone2;
		public int $phone3;
		public string $email;
		public string $currencySymbol;
		public string $areaSymbol;
		public string $distanceSymbol;
		public string $timeZone;
		public bool $modCust;
		public bool $modEmail;
		public bool $modInv;
		public bool $modInvIncludePastBal;
		public bool $modEst;
		public string $modEstExtName;
		public bool $modProp;
		public string $modPropExtName;
		public bool $modJobs;
		public bool $modEquip;
		public bool $modChem;
		public bool $modStaff;
		public string $modStaffExtName;
		public bool $modCrews;
		public string $modCrewsExtName;
		public bool $modPayr;
		public bool $modPayrSatLinkedToDue;
		public string $modPayrSalDefaultType;
		public float $modPayrSalBaseHourlyRate;
		public int $modPayrSalBaseJobPercent;
		public float $modPayrSalBasePerJob;
		public int $docIdMin;
		public bool $docIdIsRandom;
		public int $invoiceTerm;
		public int $estimateValidity;
		public bool $creditAlertIsEnabled;
		public float $creditAlertAmount;
		public bool $autoApplyCredit;
		public bool $balanceAlertIsEnabled;
		public float $balanceAlertAmount;
		public bool $SZEnabled;
		public bool $SZModInfoForStaffPage;
		public bool $SZModInfoForStaffPageShowBody;
		public string $SZModInfoForStaffPageBodyFile;
		public bool $SZModPersInfo;
		public bool $SZModPersInfoAllowEditName;
		public bool $SZModPersInfoAllowEditPhone;
		public bool $SZModPersInfoAllowEditEmail;
		public bool $SZModPersInfoAllowEditAddress;
		public bool $SZModPersInfoAllowEditUsername;
		public bool $SZModPersInfoAllowEditPassword;
		public bool $SZModCrews;
		public bool $SZModJobs;
		public bool $SZModJobsShowCrewJobs;
		public bool $SZModPayr;
		public bool $SZModPayrShowDetails;
		public bool $SZModContactAdmin;
		public bool $SZModQuit;
		public int $SZModQuitNoticeTerm;
		public bool $CPEnabled;
		public bool $CPModHomeShowBody;
		public string $CPModHomeBodyFile;
		public bool $CPModTopBar;
		public bool $CPModTopBarShowLogo;
		public string $CPModTopBarLogoFile;
		public bool $CPModTopBarShowQuote;
		public bool $CPModTopBarShowNav;
		public bool $CPModServices;
		public bool $CPModServicesShowBody;
		public string $CPModServicesBodyFile;
		public bool $CPModServicesShowList;
		public bool $CPModContact;
		public bool $CPModContactShowBody;
		public string $CPModContactBodyFile;
		public bool $CPModContactShowForm;
		public bool $CPModContactShowInfo;
		public bool $CPModAbout;
		public bool $CPModAboutShowBody;
		public string $CPModAboutBodyFile;
		public bool $CPModQuote;
		public bool $CPModQuoteShowBody;
		public string $CPModQuoteBodyFile;
		public bool $CPModQuoteShowForm;
		public bool $CPModBlog;
		public bool $CPModBlogShowBody;
		public string $CPModBlogBodyFile;
		public bool $CPModBlogShowPosts;
		public bool $CPModTOS;
		public bool $CPModTOSShowBody;
		public string $CPModTOSBodyFile;
		public bool $CPModTOSShowInvTerm;
		public bool $CPModTOSShowEstTerm;
		public bool $CPModCZ;
		public bool $CPModCZJobs;
		public bool $CPModCZInvoices;
		public bool $CPModCZEstimates;
		public bool $CPModCZPersInfo;
		public bool $CPModCZPersInfoAllowEditName;
		public bool $CPModCZPersInfoAllowEditPhone;
		public bool $CPModCZPersInfoAllowEditEmail;
		public bool $CPModCZPersInfoAllowEditAddress;
		public bool $CPModCZPersInfoAllowEditUsername;
		public bool $CPModCZPersInfoAllowEditPassword;
		public bool $CPModCZContactStaff;
		public bool $CPModCZContactStaffAllowOwnerContact;
		public bool $CPModCZContactStaffAllowAdminContact;
		public bool $CPModCZServiceRequest;
		public bool $isArchived;
		public string $dateTimeAdded;

		function __construct(string $businessId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->db = new databaseManager;

			// If businessId is blank then make a new one

			if ($businessId == '') {
				// Make a new business Id from a random id
				require_once dirname(__FILE__)."/uuid.php";
				$newUuid = new uuid('table', 'business', 'businessId');
				$newUuid = $newUuid->generatedId;
			}

			// Fetch from database
			$fetch = $this->db->select('business', '*', "WHERE businessId ='$businessId'");

			// If businessId already exists then set the set method type to UPDATE and fetch the values for the business
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->businessId = $businessId;

				$this->username = $fetch[0]['username'];
				$this->password = $fetch[0]['password'];
				$this->email = $fetch[0]['email'];
				$this->surname = $fetch[0]['surname'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->profilePicture = $fetch[0]['profilePicture'];
				$this->allowSignIn = $fetch[0]['allowSignIn'];

				$this->ownerAdminId = $fetch[0]['ownerAdminId'];
				$this->displayName = $fetch[0]['displayName'];
				$this->adminDisplayName = $fetch[0]['adminDisplayName'];
				$this->fullLogoFile = $fetch[0]['fullLogoFile'];
				$this->address = $fetch[0]['address'];
				$this->state = $fetch[0]['state'];
				$this->city = $fetch[0]['city'];
				$this->zipCode = (int)$fetch[0]['zipCode'];
				$this->phonePrefix = (int)$fetch[0]['phonePrefix'];
				$this->phone1 = (int)$fetch[0]['phone1'];
				$this->phone2 = (int)$fetch[0]['phone2'];
				$this->phone3 = (int)$fetch[0]['phone3'];
				$this->email = $fetch[0]['email'];
				$this->currencySymbol = $fetch[0]['currencySymbol'];
				$this->areaSymbol = $fetch[0]['areaSymbol'];
				$this->distanceSymbol = $fetch[0]['distanceSymbol'];
				$this->timeZone = $fetch[0]['timeZone'];
				$this->modCust = $fetch[0]['modCust'];
				if ($this->modCust == '0') {
					$this->modCust = false;
				} else {
					$this->modCust = true;
				}
				$this->modEmail = $fetch[0]['modEmail'];
				if ($this->modEmail == '0') {
					$this->modEmail = false;
				} else {
					$this->modEmail = true;
				}
				$this->modInv = $fetch[0]['modInv'];
				if ($this->modInv == '0') {
					$this->modInv = false;
				} else {
					$this->modInv = true;
				}
				$this->modInvIncludePastBal = $fetch[0]['modInvIncludePastBal'];
				if ($this->modInvIncludePastBal == '0') {
					$this->modInvIncludePastBal = false;
				} else {
					$this->modInvIncludePastBal = true;
				}
				$this->modEst = $fetch[0]['modEst'];
				if ($this->modEst == '0') {
					$this->modEst = false;
				} else {
					$this->modEst = true;
				}
				$this->modEstExtName = $fetch[0]['modEstExtName'];
				$this->modProp = $fetch[0]['modProp'];
				if ($this->modProp == '0') {
					$this->modProp = false;
				} else {
					$this->modProp = true;
				}
				$this->modPropExtName = $fetch[0]['modPropExtName'];
				$this->modJobs = $fetch[0]['modJobs'];
				if ($this->modJobs == '0') {
					$this->modJobs = false;
				} else {
					$this->modJobs = true;
				}
				$this->modEquip = $fetch[0]['modEquip'];
				if ($this->modEquip == '0') {
					$this->modEquip = false;
				} else {
					$this->modEquip = true;
				}
				$this->modChem = $fetch[0]['modChem'];
				if ($this->modChem == '0') {
					$this->modChem = false;
				} else {
					$this->modChem = true;
				}
				$this->modStaff = $fetch[0]['modStaff'];
				if ($this->modStaff == '0') {
					$this->modStaff = false;
				} else {
					$this->modStaff = true;
				}
				$this->modStaffExtName = $fetch[0]['modStaffExtName'];
				$this->modCrews = $fetch[0]['modCrews'];
				if ($this->modCrews == '0') {
					$this->modCrews = false;
				} else {
					$this->modCrews = true;
				}
				$this->modCrewsExtName = $fetch[0]['modCrewsExtName'];
				$this->modPayr = $fetch[0]['modPayr'];
				if ($this->modPayr == '0') {
					$this->modPayr = false;
				} else {
					$this->modPayr = true;
				}
				$this->modPayrSatLinkedToDue = $fetch[0]['modPayrSatLinkedToDue'];
				if ($this->modPayrSatLinkedToDue == '0') {
					$this->modPayrSatLinkedToDue = false;
				} else {
					$this->modPayrSatLinkedToDue = true;
				}
				$this->modPayrSalDefaultType = $fetch[0]['modPayrSalDefaultType'];
				$this->modPayrSalBaseHourlyRate = (float)$fetch[0]['modPayrSalBaseHourlyRate'];
				$this->modPayrSalBaseJobPercent = (int)$fetch[0]['modPayrSalBaseJobPercent'];
				$this->modPayrSalBasePerJob = (float)$fetch[0]['modPayrSalBasePerJob'];
				$this->docIdMin = (int)$fetch[0]['docIdMin'];
				$this->docIdIsRandom = $fetch[0]['docIdIsRandom'];
				if ($this->docIdIsRandom == '0') {
					$this->docIdIsRandom = false;
				} else {
					$this->docIdIsRandom = true;
				}
				$this->invoiceTerm = (int)$fetch[0]['invoiceTerm'];
				$this->estimateValidity = (int)$fetch[0]['estimateValidity'];
				$this->creditAlertIsEnabled = $fetch[0]['creditAlertIsEnabled'];
				if ($this->creditAlertIsEnabled == '0') {
					$this->creditAlertIsEnabled = false;
				} else {
					$this->creditAlertIsEnabled = true;
				}
				$this->creditAlertAmount = (float)$fetch[0]['creditAlertAmount'];
				$this->autoApplyCredit = $fetch[0]['autoApplyCredit'];
				if ($this->autoApplyCredit == '0') {
					$this->autoApplyCredit = false;
				} else {
					$this->autoApplyCredit = true;
				}
				$this->balanceAlertIsEnabled = $fetch[0]['balanceAlertIsEnabled'];
				if ($this->balanceAlertIsEnabled == '0') {
					$this->balanceAlertIsEnabled = false;
				} else {
					$this->balanceAlertIsEnabled = true;
				}
				$this->balanceAlertAmount = (float)$fetch[0]['balanceAlertAmount'];
				if ($this->balanceAlertAmount == '0') {
					$this->balanceAlertAmount = false;
				} else {
					$this->balanceAlertAmount = true;
				}
				$this->SZEnabled = $fetch[0]['SZEnabled'];
				if ($this->SZEnabled == '0') {
					$this->SZEnabled = false;
				} else {
					$this->SZEnabled = true;
				}
				$this->SZModInfoForStaffPage = $fetch[0]['SZModInfoForStaffPage'];
				if ($this->SZModInfoForStaffPage == '0') {
					$this->SZModInfoForStaffPage = false;
				} else {
					$this->SZModInfoForStaffPage = true;
				}
				$this->SZModInfoForStaffPageShowBody = $fetch[0]['SZModInfoForStaffPageShowBody'];
				if ($this->SZModInfoForStaffPageShowBody == '0') {
					$this->SZModInfoForStaffPageShowBody = false;
				} else {
					$this->SZModInfoForStaffPageShowBody = true;
				}
				$this->SZModInfoForStaffPageBodyFile = $fetch[0]['SZModInfoForStaffPageBodyFile'];
				$this->SZModPersInfo = $fetch[0]['SZModPersInfo'];
				if ($this->SZModPersInfo == '0') {
					$this->SZModPersInfo = false;
				} else {
					$this->SZModPersInfo = true;
				}
				$this->SZModPersInfoAllowEditName = $fetch[0]['SZModPersInfoAllowEditName'];
				if ($this->SZModPersInfoAllowEditName == '0') {
					$this->SZModPersInfoAllowEditName = false;
				} else {
					$this->SZModPersInfoAllowEditName = true;
				}
				$this->SZModPersInfoAllowEditPhone = $fetch[0]['SZModPersInfoAllowEditPhone'];
				if ($this->SZModPersInfoAllowEditPhone == '0') {
					$this->SZModPersInfoAllowEditPhone = false;
				} else {
					$this->SZModPersInfoAllowEditPhone = true;
				}
				$this->SZModPersInfoAllowEditEmail = $fetch[0]['SZModPersInfoAllowEditEmail'];
				if ($this->SZModPersInfoAllowEditEmail == '0') {
					$this->SZModPersInfoAllowEditEmail = false;
				} else {
					$this->SZModPersInfoAllowEditEmail = true;
				}
				$this->SZModPersInfoAllowEditAddress = $fetch[0]['SZModPersInfoAllowEditAddress'];
				if ($this->SZModPersInfoAllowEditAddress == '0') {
					$this->SZModPersInfoAllowEditAddress = false;
				} else {
					$this->SZModPersInfoAllowEditAddress = true;
				}
				$this->SZModPersInfoAllowEditUsername = $fetch[0]['SZModPersInfoAllowEditUsername'];
				if ($this->SZModPersInfoAllowEditUsername == '0') {
					$this->SZModPersInfoAllowEditUsername = false;
				} else {
					$this->SZModPersInfoAllowEditUsername = true;
				}
				$this->SZModPersInfoAllowEditPassword = $fetch[0]['SZModPersInfoAllowEditPassword'];
				if ($this->SZModPersInfoAllowEditPassword == '0') {
					$this->SZModPersInfoAllowEditPassword = false;
				} else {
					$this->SZModPersInfoAllowEditPassword = true;
				}
				$this->SZModCrews = $fetch[0]['SZModCrews'];
				if ($this->SZModCrews == '0') {
					$this->SZModCrews = false;
				} else {
					$this->SZModCrews = true;
				}
				$this->SZModJobs = $fetch[0]['SZModJobs'];
				if ($this->SZModJobs == '0') {
					$this->SZModJobs = false;
				} else {
					$this->SZModJobs = true;
				}
				$this->SZModJobsShowCrewJobs = $fetch[0]['SZModJobsShowCrewJobs'];
				if ($this->SZModJobsShowCrewJobs == '0') {
					$this->SZModJobsShowCrewJobs = false;
				} else {
					$this->SZModJobsShowCrewJobs = true;
				}
				$this->SZModPayr = $fetch[0]['SZModPayr'];
				if ($this->SZModPayr == '0') {
					$this->SZModPayr = false;
				} else {
					$this->SZModPayr = true;
				}
				$this->SZModPayrShowDetails = $fetch[0]['SZModPayrShowDetails'];
				if ($this->SZModPayrShowDetails == '0') {
					$this->SZModPayrShowDetails = false;
				} else {
					$this->SZModPayrShowDetails = true;
				}
				$this->SZModContactAdmin = $fetch[0]['SZModContactAdmin'];
				if ($this->SZModContactAdmin == '0') {
					$this->SZModContactAdmin = false;
				} else {
					$this->SZModContactAdmin = true;
				}
				$this->SZModQuit = $fetch[0]['SZModQuit'];
				if ($this->SZModQuit == '0') {
					$this->SZModQuit = false;
				} else {
					$this->SZModQuit = true;
				}
				$this->SZModQuitNoticeTerm = (int)$fetch[0]['SZModQuitNoticeTerm'];
				$this->CPEnabled = $fetch[0]['CPEnabled'];
				if ($this->CPEnabled == '0') {
					$this->CPEnabled = false;
				} else {
					$this->CPEnabled = true;
				}
				$this->CPModHomeShowBody = $fetch[0]['CPModHomeShowBody'];
				if ($this->CPModHomeShowBody == '0') {
					$this->CPModHomeShowBody = false;
				} else {
					$this->CPModHomeShowBody = true;
				}
				$this->CPModHomeBodyFile = $fetch[0]['CPModHomeBodyFile'];
				$this->CPModTopBar = $fetch[0]['CPModTopBar'];
				if ($this->CPModTopBar == '0') {
					$this->CPModTopBar = false;
				} else {
					$this->CPModTopBar = true;
				}
				$this->CPModTopBarShowLogo = $fetch[0]['CPModTopBarShowLogo'];
				if ($this->CPModTopBarShowLogo == '0') {
					$this->CPModTopBarShowLogo = false;
				} else {
					$this->CPModTopBarShowLogo = true;
				}
				$this->CPModTopBarLogoFile = $fetch[0]['CPModTopBarLogoFile'];
				$this->CPModTopBarShowQuote = $fetch[0]['CPModTopBarShowQuote'];
				if ($this->CPModTopBarShowQuote == '0') {
					$this->CPModTopBarShowQuote = false;
				} else {
					$this->CPModTopBarShowQuote = true;
				}
				$this->CPModTopBarShowNav = $fetch[0]['CPModTopBarShowNav'];
				if ($this->CPModTopBarShowNav == '0') {
					$this->CPModTopBarShowNav = false;
				} else {
					$this->CPModTopBarShowNav = true;
				}
				$this->CPModServices = $fetch[0]['CPModServices'];
				if ($this->CPModServices == '0') {
					$this->CPModServices = false;
				} else {
					$this->CPModServices = true;
				}
				$this->CPModServicesShowBody = $fetch[0]['CPModServicesShowBody'];
				if ($this->CPModServicesShowBody == '0') {
					$this->CPModServicesShowBody = false;
				} else {
					$this->CPModServicesShowBody = true;
				}
				$this->CPModServicesBodyFile = $fetch[0]['CPModServicesBodyFile'];
				$this->CPModServicesShowList = $fetch[0]['CPModServicesShowList'];
				if ($this->CPModServicesShowList == '0') {
					$this->CPModServicesShowList = false;
				} else {
					$this->CPModServicesShowList = true;
				}
				$this->CPModContact = $fetch[0]['CPModContact'];
				if ($this->CPModContact == '0') {
					$this->CPModContact = false;
				} else {
					$this->CPModContact = true;
				}
				$this->CPModContactShowBody = $fetch[0]['CPModContactShowBody'];
				if ($this->CPModContactShowBody == '0') {
					$this->CPModContactShowBody = false;
				} else {
					$this->CPModContactShowBody = true;
				}
				$this->CPModContactBodyFile = $fetch[0]['CPModContactBodyFile'];
				$this->CPModContactShowForm = $fetch[0]['CPModContactShowForm'];
				if ($this->CPModContactShowForm == '0') {
					$this->CPModContactShowForm = false;
				} else {
					$this->CPModContactShowForm = true;
				}
				$this->CPModContactShowInfo = $fetch[0]['CPModContactShowInfo'];
				if ($this->CPModContactShowInfo == '0') {
					$this->CPModContactShowInfo = false;
				} else {
					$this->CPModContactShowInfo = true;
				}
				$this->CPModAbout = $fetch[0]['CPModAbout'];
				if ($this->CPModAbout == '0') {
					$this->CPModAbout = false;
				} else {
					$this->CPModAbout = true;
				}
				$this->CPModAboutShowBody = $fetch[0]['CPModAboutShowBody'];
				if ($this->CPModAboutShowBody == '0') {
					$this->CPModAboutShowBody = false;
				} else {
					$this->CPModAboutShowBody = true;
				}
				$this->CPModAboutBodyFile = $fetch[0]['CPModAboutBodyFile'];
				$this->CPModQuote = $fetch[0]['CPModQuote'];
				if ($this->CPModQuote == '0') {
					$this->CPModQuote = false;
				} else {
					$this->CPModQuote = true;
				}
				$this->CPModQuoteShowBody = $fetch[0]['CPModQuoteShowBody'];
				if ($this->CPModQuoteShowBody == '0') {
					$this->CPModQuoteShowBody = false;
				} else {
					$this->CPModQuoteShowBody = true;
				}
				$this->CPModQuoteBodyFile = $fetch[0]['CPModQuoteBodyFile'];
				$this->CPModQuoteShowForm = $fetch[0]['CPModQuoteShowForm'];
				if ($this->CPModQuoteShowForm == '0') {
					$this->CPModQuoteShowForm = false;
				} else {
					$this->CPModQuoteShowForm = true;
				}
				$this->CPModBlog = $fetch[0]['CPModBlog'];
				if ($this->CPModBlog == '0') {
					$this->CPModBlog = false;
				} else {
					$this->CPModBlog = true;
				}
				$this->CPModBlogShowBody = $fetch[0]['CPModBlogShowBody'];
				if ($this->CPModBlogShowBody == '0') {
					$this->CPModBlogShowBody = false;
				} else {
					$this->CPModBlogShowBody = true;
				}
				$this->CPModBlogBodyFile = $fetch[0]['CPModBlogBodyFile'];
				$this->CPModBlogShowPosts = $fetch[0]['CPModBlogShowPosts'];
				if ($this->CPModBlogShowPosts == '0') {
					$this->CPModBlogShowPosts = false;
				} else {
					$this->CPModBlogShowPosts = true;
				}
				$this->CPModTOS = $fetch[0]['CPModTOS'];
				if ($this->CPModTOS == '0') {
					$this->CPModTOS = false;
				} else {
					$this->CPModTOS = true;
				}
				$this->CPModTOSShowBody = $fetch[0]['CPModTOSShowBody'];
				if ($this->CPModTOSShowBody == '0') {
					$this->CPModTOSShowBody = false;
				} else {
					$this->CPModTOSShowBody = true;
				}
				$this->CPModTOSBodyFile = $fetch[0]['CPModTOSBodyFile'];
				$this->CPModTOSShowInvTerm = $fetch[0]['CPModTOSShowInvTerm'];
				if ($this->CPModTOSShowInvTerm == '0') {
					$this->CPModTOSShowInvTerm = false;
				} else {
					$this->CPModTOSShowInvTerm = true;
				}
				$this->CPModTOSShowEstTerm = $fetch[0]['CPModTOSShowEstTerm'];
				if ($this->CPModTOSShowEstTerm == '0') {
					$this->CPModTOSShowEstTerm = false;
				} else {
					$this->CPModTOSShowEstTerm = true;
				}
				$this->CPModCZ = $fetch[0]['CPModCZ'];
				if ($this->CPModCZ == '0') {
					$this->CPModCZ = false;
				} else {
					$this->CPModCZ = true;
				}
				$this->CPModCZJobs = $fetch[0]['CPModCZJobs'];
				if ($this->CPModCZJobs == '0') {
					$this->CPModCZJobs = false;
				} else {
					$this->CPModCZJobs = true;
				}
				$this->CPModCZInvoices = $fetch[0]['CPModCZInvoices'];
				if ($this->CPModCZInvoices == '0') {
					$this->CPModCZInvoices = false;
				} else {
					$this->CPModCZInvoices = true;
				}
				$this->CPModCZEstimates = $fetch[0]['CPModCZEstimates'];
				if ($this->CPModCZEstimates == '0') {
					$this->CPModCZEstimates = false;
				} else {
					$this->CPModCZEstimates = true;
				}
				$this->CPModCZPersInfo = $fetch[0]['CPModCZPersInfo'];
				if ($this->CPModCZPersInfo == '0') {
					$this->CPModCZPersInfo = false;
				} else {
					$this->CPModCZPersInfo = true;
				}
				$this->CPModCZPersInfoAllowEditName = $fetch[0]['CPModCZPersInfoAllowEditName'];
				if ($this->CPModCZPersInfoAllowEditName == '0') {
					$this->CPModCZPersInfoAllowEditName = false;
				} else {
					$this->CPModCZPersInfoAllowEditName = true;
				}
				$this->CPModCZPersInfoAllowEditPhone = $fetch[0]['CPModCZPersInfoAllowEditPhone'];
				if ($this->CPModCZPersInfoAllowEditPhone == '0') {
					$this->CPModCZPersInfoAllowEditPhone = false;
				} else {
					$this->CPModCZPersInfoAllowEditPhone = true;
				}
				$this->CPModCZPersInfoAllowEditEmail = $fetch[0]['CPModCZPersInfoAllowEditEmail'];
				if ($this->CPModCZPersInfoAllowEditEmail == '0') {
					$this->CPModCZPersInfoAllowEditEmail = false;
				} else {
					$this->CPModCZPersInfoAllowEditEmail = true;
				}
				$this->CPModCZPersInfoAllowEditAddress = $fetch[0]['CPModCZPersInfoAllowEditAddress'];
				if ($this->CPModCZPersInfoAllowEditAddress == '0') {
					$this->CPModCZPersInfoAllowEditAddress = false;
				} else {
					$this->CPModCZPersInfoAllowEditAddress = true;
				}
				$this->CPModCZPersInfoAllowEditUsername = $fetch[0]['CPModCZPersInfoAllowEditUsername'];
				if ($this->CPModCZPersInfoAllowEditUsername == '0') {
					$this->CPModCZPersInfoAllowEditUsername = false;
				} else {
					$this->CPModCZPersInfoAllowEditUsername = true;
				}
				$this->CPModCZPersInfoAllowEditPassword = $fetch[0]['CPModCZPersInfoAllowEditPassword'];
				if ($this->CPModCZPersInfoAllowEditPassword == '0') {
					$this->CPModCZPersInfoAllowEditPassword = false;
				} else {
					$this->CPModCZPersInfoAllowEditPassword = true;
				}
				$this->CPModCZContactStaff = $fetch[0]['CPModCZContactStaff'];
				if ($this->CPModCZContactStaff == '0') {
					$this->CPModCZContactStaff = false;
				} else {
					$this->CPModCZContactStaff = true;
				}
				$this->CPModCZContactStaffAllowOwnerContact = $fetch[0]['CPModCZContactStaffAllowOwnerContact'];
				if ($this->CPModCZContactStaffAllowOwnerContact == '0') {
					$this->CPModCZContactStaffAllowOwnerContact = false;
				} else {
					$this->CPModCZContactStaffAllowOwnerContact = true;
				}
				$this->CPModCZContactStaffAllowAdminContact = $fetch[0]['CPModCZContactStaffAllowAdminContact'];
				if ($this->CPModCZContactStaffAllowAdminContact == '0') {
					$this->CPModCZContactStaffAllowAdminContact = false;
				} else {
					$this->CPModCZContactStaffAllowAdminContact = true;
				}
				$this->CPModCZServiceRequest = $fetch[0]['CPModCZServiceRequest'];
				if ($this->CPModCZServiceRequest == '0') {
					$this->CPModCZServiceRequest = false;
				} else {
					$this->CPModCZServiceRequest = true;
				}
				$this->isArchived = $fetch[0]['isArchived'];
				if ($this->isArchived == '0') {
					$this->isArchived = false;
				} else {
					$this->isArchived = true;
				}
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			// If businessId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->businessId = $newUuid;

				$this->username = '';
				$this->password = '';
				$this->email = '';
				$this->surname = '';
				$this->firstName = '';
				$this->lastName = '';
				$this->profilePicture = '';
				$this->allowSignIn = '';

				$this->ownerAdminId = '';
				$this->displayName = '';
				$this->adminDisplayName = '';
				$this->fullLogoFile = '';
				$this->address = '';
				$this->state = '';
				$this->city = '';
				$this->zipCode = (int)$fetch[0]['zipCode'];
				$this->phonePrefix = (int)$fetch[0]['phonePrefix'];
				$this->phone1 = (int)$fetch[0]['phone1'];
				$this->phone2 = (int)$fetch[0]['phone2'];
				$this->phone3 = (int)$fetch[0]['phone3'];
				$this->email = $fetch[0]['email'];
				$this->currencySymbol = $fetch[0]['currencySymbol'];
				$this->areaSymbol = $fetch[0]['areaSymbol'];
				$this->distanceSymbol = $fetch[0]['distanceSymbol'];
				$this->timeZone = $fetch[0]['timeZone'];
				$this->modCust = $fetch[0]['modCust'];
				if ($this->modCust == '0') {
					$this->modCust = false;
				} else {
					$this->modCust = true;
				}
				$this->modEmail = $fetch[0]['modEmail'];
				if ($this->modEmail == '0') {
					$this->modEmail = false;
				} else {
					$this->modEmail = true;
				}
				$this->modInv = $fetch[0]['modInv'];
				if ($this->modInv == '0') {
					$this->modInv = false;
				} else {
					$this->modInv = true;
				}
				$this->modInvIncludePastBal = $fetch[0]['modInvIncludePastBal'];
				if ($this->modInvIncludePastBal == '0') {
					$this->modInvIncludePastBal = false;
				} else {
					$this->modInvIncludePastBal = true;
				}
				$this->modEst = $fetch[0]['modEst'];
				if ($this->modEst == '0') {
					$this->modEst = false;
				} else {
					$this->modEst = true;
				}
				$this->modEstExtName = $fetch[0]['modEstExtName'];
				$this->modProp = $fetch[0]['modProp'];
				if ($this->modProp == '0') {
					$this->modProp = false;
				} else {
					$this->modProp = true;
				}
				$this->modPropExtName = $fetch[0]['modPropExtName'];
				$this->modJobs = $fetch[0]['modJobs'];
				if ($this->modJobs == '0') {
					$this->modJobs = false;
				} else {
					$this->modJobs = true;
				}
				$this->modEquip = $fetch[0]['modEquip'];
				if ($this->modEquip == '0') {
					$this->modEquip = false;
				} else {
					$this->modEquip = true;
				}
				$this->modChem = $fetch[0]['modChem'];
				if ($this->modChem == '0') {
					$this->modChem = false;
				} else {
					$this->modChem = true;
				}
				$this->modStaff = $fetch[0]['modStaff'];
				if ($this->modStaff == '0') {
					$this->modStaff = false;
				} else {
					$this->modStaff = true;
				}
				$this->modStaffExtName = $fetch[0]['modStaffExtName'];
				$this->modCrews = $fetch[0]['modCrews'];
				if ($this->modCrews == '0') {
					$this->modCrews = false;
				} else {
					$this->modCrews = true;
				}
				$this->modCrewsExtName = $fetch[0]['modCrewsExtName'];
				$this->modPayr = $fetch[0]['modPayr'];
				if ($this->modPayr == '0') {
					$this->modPayr = false;
				} else {
					$this->modPayr = true;
				}
				$this->modPayrSatLinkedToDue = $fetch[0]['modPayrSatLinkedToDue'];
				if ($this->modPayrSatLinkedToDue == '0') {
					$this->modPayrSatLinkedToDue = false;
				} else {
					$this->modPayrSatLinkedToDue = true;
				}
				$this->modPayrSalDefaultType = $fetch[0]['modPayrSalDefaultType'];
				$this->modPayrSalBaseHourlyRate = (float)$fetch[0]['modPayrSalBaseHourlyRate'];
				$this->modPayrSalBaseJobPercent = (int)$fetch[0]['modPayrSalBaseJobPercent'];
				$this->modPayrSalBasePerJob = (float)$fetch[0]['modPayrSalBasePerJob'];
				$this->docIdMin = (int)$fetch[0]['docIdMin'];
				$this->docIdIsRandom = $fetch[0]['docIdIsRandom'];
				if ($this->docIdIsRandom == '0') {
					$this->docIdIsRandom = false;
				} else {
					$this->docIdIsRandom = true;
				}
				$this->invoiceTerm = (int)$fetch[0]['invoiceTerm'];
				$this->estimateValidity = (int)$fetch[0]['estimateValidity'];
				$this->creditAlertIsEnabled = $fetch[0]['creditAlertIsEnabled'];
				if ($this->creditAlertIsEnabled == '0') {
					$this->creditAlertIsEnabled = false;
				} else {
					$this->creditAlertIsEnabled = true;
				}
				$this->creditAlertAmount = (float)$fetch[0]['creditAlertAmount'];
				$this->autoApplyCredit = $fetch[0]['autoApplyCredit'];
				if ($this->autoApplyCredit == '0') {
					$this->autoApplyCredit = false;
				} else {
					$this->autoApplyCredit = true;
				}
				$this->balanceAlertIsEnabled = $fetch[0]['balanceAlertIsEnabled'];
				if ($this->balanceAlertIsEnabled == '0') {
					$this->balanceAlertIsEnabled = false;
				} else {
					$this->balanceAlertIsEnabled = true;
				}
				$this->balanceAlertAmount = (float)$fetch[0]['balanceAlertAmount'];
				if ($this->balanceAlertAmount == '0') {
					$this->balanceAlertAmount = false;
				} else {
					$this->balanceAlertAmount = true;
				}
				$this->SZEnabled = $fetch[0]['SZEnabled'];
				if ($this->SZEnabled == '0') {
					$this->SZEnabled = false;
				} else {
					$this->SZEnabled = true;
				}
				$this->SZModInfoForStaffPage = $fetch[0]['SZModInfoForStaffPage'];
				if ($this->SZModInfoForStaffPage == '0') {
					$this->SZModInfoForStaffPage = false;
				} else {
					$this->SZModInfoForStaffPage = true;
				}
				$this->SZModInfoForStaffPageShowBody = $fetch[0]['SZModInfoForStaffPageShowBody'];
				if ($this->SZModInfoForStaffPageShowBody == '0') {
					$this->SZModInfoForStaffPageShowBody = false;
				} else {
					$this->SZModInfoForStaffPageShowBody = true;
				}
				$this->SZModInfoForStaffPageBodyFile = $fetch[0]['SZModInfoForStaffPageBodyFile'];
				$this->SZModPersInfo = $fetch[0]['SZModPersInfo'];
				if ($this->SZModPersInfo == '0') {
					$this->SZModPersInfo = false;
				} else {
					$this->SZModPersInfo = true;
				}
				$this->SZModPersInfoAllowEditName = $fetch[0]['SZModPersInfoAllowEditName'];
				if ($this->SZModPersInfoAllowEditName == '0') {
					$this->SZModPersInfoAllowEditName = false;
				} else {
					$this->SZModPersInfoAllowEditName = true;
				}
				$this->SZModPersInfoAllowEditPhone = $fetch[0]['SZModPersInfoAllowEditPhone'];
				if ($this->SZModPersInfoAllowEditPhone == '0') {
					$this->SZModPersInfoAllowEditPhone = false;
				} else {
					$this->SZModPersInfoAllowEditPhone = true;
				}
				$this->SZModPersInfoAllowEditEmail = $fetch[0]['SZModPersInfoAllowEditEmail'];
				if ($this->SZModPersInfoAllowEditEmail == '0') {
					$this->SZModPersInfoAllowEditEmail = false;
				} else {
					$this->SZModPersInfoAllowEditEmail = true;
				}
				$this->SZModPersInfoAllowEditAddress = $fetch[0]['SZModPersInfoAllowEditAddress'];
				if ($this->SZModPersInfoAllowEditAddress == '0') {
					$this->SZModPersInfoAllowEditAddress = false;
				} else {
					$this->SZModPersInfoAllowEditAddress = true;
				}
				$this->SZModPersInfoAllowEditUsername = $fetch[0]['SZModPersInfoAllowEditUsername'];
				if ($this->SZModPersInfoAllowEditUsername == '0') {
					$this->SZModPersInfoAllowEditUsername = false;
				} else {
					$this->SZModPersInfoAllowEditUsername = true;
				}
				$this->SZModPersInfoAllowEditPassword = $fetch[0]['SZModPersInfoAllowEditPassword'];
				if ($this->SZModPersInfoAllowEditPassword == '0') {
					$this->SZModPersInfoAllowEditPassword = false;
				} else {
					$this->SZModPersInfoAllowEditPassword = true;
				}
				$this->SZModCrews = $fetch[0]['SZModCrews'];
				if ($this->SZModCrews == '0') {
					$this->SZModCrews = false;
				} else {
					$this->SZModCrews = true;
				}
				$this->SZModJobs = $fetch[0]['SZModJobs'];
				if ($this->SZModJobs == '0') {
					$this->SZModJobs = false;
				} else {
					$this->SZModJobs = true;
				}
				$this->SZModJobsShowCrewJobs = $fetch[0]['SZModJobsShowCrewJobs'];
				if ($this->SZModJobsShowCrewJobs == '0') {
					$this->SZModJobsShowCrewJobs = false;
				} else {
					$this->SZModJobsShowCrewJobs = true;
				}
				$this->SZModPayr = $fetch[0]['SZModPayr'];
				if ($this->SZModPayr == '0') {
					$this->SZModPayr = false;
				} else {
					$this->SZModPayr = true;
				}
				$this->SZModPayrShowDetails = $fetch[0]['SZModPayrShowDetails'];
				if ($this->SZModPayrShowDetails == '0') {
					$this->SZModPayrShowDetails = false;
				} else {
					$this->SZModPayrShowDetails = true;
				}
				$this->SZModContactAdmin = $fetch[0]['SZModContactAdmin'];
				if ($this->SZModContactAdmin == '0') {
					$this->SZModContactAdmin = false;
				} else {
					$this->SZModContactAdmin = true;
				}
				$this->SZModQuit = $fetch[0]['SZModQuit'];
				if ($this->SZModQuit == '0') {
					$this->SZModQuit = false;
				} else {
					$this->SZModQuit = true;
				}
				$this->SZModQuitNoticeTerm = (int)$fetch[0]['SZModQuitNoticeTerm'];
				$this->CPEnabled = $fetch[0]['CPEnabled'];
				if ($this->CPEnabled == '0') {
					$this->CPEnabled = false;
				} else {
					$this->CPEnabled = true;
				}
				$this->CPModHomeShowBody = $fetch[0]['CPModHomeShowBody'];
				if ($this->CPModHomeShowBody == '0') {
					$this->CPModHomeShowBody = false;
				} else {
					$this->CPModHomeShowBody = true;
				}
				$this->CPModHomeBodyFile = $fetch[0]['CPModHomeBodyFile'];
				$this->CPModTopBar = $fetch[0]['CPModTopBar'];
				if ($this->CPModTopBar == '0') {
					$this->CPModTopBar = false;
				} else {
					$this->CPModTopBar = true;
				}
				$this->CPModTopBarShowLogo = $fetch[0]['CPModTopBarShowLogo'];
				if ($this->CPModTopBarShowLogo == '0') {
					$this->CPModTopBarShowLogo = false;
				} else {
					$this->CPModTopBarShowLogo = true;
				}
				$this->CPModTopBarLogoFile = $fetch[0]['CPModTopBarLogoFile'];
				$this->CPModTopBarShowQuote = $fetch[0]['CPModTopBarShowQuote'];
				if ($this->CPModTopBarShowQuote == '0') {
					$this->CPModTopBarShowQuote = false;
				} else {
					$this->CPModTopBarShowQuote = true;
				}
				$this->CPModTopBarShowNav = $fetch[0]['CPModTopBarShowNav'];
				if ($this->CPModTopBarShowNav == '0') {
					$this->CPModTopBarShowNav = false;
				} else {
					$this->CPModTopBarShowNav = true;
				}
				$this->CPModServices = $fetch[0]['CPModServices'];
				if ($this->CPModServices == '0') {
					$this->CPModServices = false;
				} else {
					$this->CPModServices = true;
				}
				$this->CPModServicesShowBody = $fetch[0]['CPModServicesShowBody'];
				if ($this->CPModServicesShowBody == '0') {
					$this->CPModServicesShowBody = false;
				} else {
					$this->CPModServicesShowBody = true;
				}
				$this->CPModServicesBodyFile = $fetch[0]['CPModServicesBodyFile'];
				$this->CPModServicesShowList = $fetch[0]['CPModServicesShowList'];
				if ($this->CPModServicesShowList == '0') {
					$this->CPModServicesShowList = false;
				} else {
					$this->CPModServicesShowList = true;
				}
				$this->CPModContact = $fetch[0]['CPModContact'];
				if ($this->CPModContact == '0') {
					$this->CPModContact = false;
				} else {
					$this->CPModContact = true;
				}
				$this->CPModContactShowBody = $fetch[0]['CPModContactShowBody'];
				if ($this->CPModContactShowBody == '0') {
					$this->CPModContactShowBody = false;
				} else {
					$this->CPModContactShowBody = true;
				}
				$this->CPModContactBodyFile = $fetch[0]['CPModContactBodyFile'];
				$this->CPModContactShowForm = $fetch[0]['CPModContactShowForm'];
				if ($this->CPModContactShowForm == '0') {
					$this->CPModContactShowForm = false;
				} else {
					$this->CPModContactShowForm = true;
				}
				$this->CPModContactShowInfo = $fetch[0]['CPModContactShowInfo'];
				if ($this->CPModContactShowInfo == '0') {
					$this->CPModContactShowInfo = false;
				} else {
					$this->CPModContactShowInfo = true;
				}
				$this->CPModAbout = $fetch[0]['CPModAbout'];
				if ($this->CPModAbout == '0') {
					$this->CPModAbout = false;
				} else {
					$this->CPModAbout = true;
				}
				$this->CPModAboutShowBody = $fetch[0]['CPModAboutShowBody'];
				if ($this->CPModAboutShowBody == '0') {
					$this->CPModAboutShowBody = false;
				} else {
					$this->CPModAboutShowBody = true;
				}
				$this->CPModAboutBodyFile = $fetch[0]['CPModAboutBodyFile'];
				$this->CPModQuote = $fetch[0]['CPModQuote'];
				if ($this->CPModQuote == '0') {
					$this->CPModQuote = false;
				} else {
					$this->CPModQuote = true;
				}
				$this->CPModQuoteShowBody = $fetch[0]['CPModQuoteShowBody'];
				if ($this->CPModQuoteShowBody == '0') {
					$this->CPModQuoteShowBody = false;
				} else {
					$this->CPModQuoteShowBody = true;
				}
				$this->CPModQuoteBodyFile = $fetch[0]['CPModQuoteBodyFile'];
				$this->CPModQuoteShowForm = $fetch[0]['CPModQuoteShowForm'];
				if ($this->CPModQuoteShowForm == '0') {
					$this->CPModQuoteShowForm = false;
				} else {
					$this->CPModQuoteShowForm = true;
				}
				$this->CPModBlog = $fetch[0]['CPModBlog'];
				if ($this->CPModBlog == '0') {
					$this->CPModBlog = false;
				} else {
					$this->CPModBlog = true;
				}
				$this->CPModBlogShowBody = $fetch[0]['CPModBlogShowBody'];
				if ($this->CPModBlogShowBody == '0') {
					$this->CPModBlogShowBody = false;
				} else {
					$this->CPModBlogShowBody = true;
				}
				$this->CPModBlogBodyFile = $fetch[0]['CPModBlogBodyFile'];
				$this->CPModBlogShowPosts = $fetch[0]['CPModBlogShowPosts'];
				if ($this->CPModBlogShowPosts == '0') {
					$this->CPModBlogShowPosts = false;
				} else {
					$this->CPModBlogShowPosts = true;
				}
				$this->CPModTOS = $fetch[0]['CPModTOS'];
				if ($this->CPModTOS == '0') {
					$this->CPModTOS = false;
				} else {
					$this->CPModTOS = true;
				}
				$this->CPModTOSShowBody = $fetch[0]['CPModTOSShowBody'];
				if ($this->CPModTOSShowBody == '0') {
					$this->CPModTOSShowBody = false;
				} else {
					$this->CPModTOSShowBody = true;
				}
				$this->CPModTOSBodyFile = $fetch[0]['CPModTOSBodyFile'];
				$this->CPModTOSShowInvTerm = $fetch[0]['CPModTOSShowInvTerm'];
				if ($this->CPModTOSShowInvTerm == '0') {
					$this->CPModTOSShowInvTerm = false;
				} else {
					$this->CPModTOSShowInvTerm = true;
				}
				$this->CPModTOSShowEstTerm = $fetch[0]['CPModTOSShowEstTerm'];
				if ($this->CPModTOSShowEstTerm == '0') {
					$this->CPModTOSShowEstTerm = false;
				} else {
					$this->CPModTOSShowEstTerm = true;
				}
				$this->CPModCZ = $fetch[0]['CPModCZ'];
				if ($this->CPModCZ == '0') {
					$this->CPModCZ = false;
				} else {
					$this->CPModCZ = true;
				}
				$this->CPModCZJobs = $fetch[0]['CPModCZJobs'];
				if ($this->CPModCZJobs == '0') {
					$this->CPModCZJobs = false;
				} else {
					$this->CPModCZJobs = true;
				}
				$this->CPModCZInvoices = $fetch[0]['CPModCZInvoices'];
				if ($this->CPModCZInvoices == '0') {
					$this->CPModCZInvoices = false;
				} else {
					$this->CPModCZInvoices = true;
				}
				$this->CPModCZEstimates = $fetch[0]['CPModCZEstimates'];
				if ($this->CPModCZEstimates == '0') {
					$this->CPModCZEstimates = false;
				} else {
					$this->CPModCZEstimates = true;
				}
				$this->CPModCZPersInfo = $fetch[0]['CPModCZPersInfo'];
				if ($this->CPModCZPersInfo == '0') {
					$this->CPModCZPersInfo = false;
				} else {
					$this->CPModCZPersInfo = true;
				}
				$this->CPModCZPersInfoAllowEditName = $fetch[0]['CPModCZPersInfoAllowEditName'];
				if ($this->CPModCZPersInfoAllowEditName == '0') {
					$this->CPModCZPersInfoAllowEditName = false;
				} else {
					$this->CPModCZPersInfoAllowEditName = true;
				}
				$this->CPModCZPersInfoAllowEditPhone = $fetch[0]['CPModCZPersInfoAllowEditPhone'];
				if ($this->CPModCZPersInfoAllowEditPhone == '0') {
					$this->CPModCZPersInfoAllowEditPhone = false;
				} else {
					$this->CPModCZPersInfoAllowEditPhone = true;
				}
				$this->CPModCZPersInfoAllowEditEmail = $fetch[0]['CPModCZPersInfoAllowEditEmail'];
				if ($this->CPModCZPersInfoAllowEditEmail == '0') {
					$this->CPModCZPersInfoAllowEditEmail = false;
				} else {
					$this->CPModCZPersInfoAllowEditEmail = true;
				}
				$this->CPModCZPersInfoAllowEditAddress = $fetch[0]['CPModCZPersInfoAllowEditAddress'];
				if ($this->CPModCZPersInfoAllowEditAddress == '0') {
					$this->CPModCZPersInfoAllowEditAddress = false;
				} else {
					$this->CPModCZPersInfoAllowEditAddress = true;
				}
				$this->CPModCZPersInfoAllowEditUsername = $fetch[0]['CPModCZPersInfoAllowEditUsername'];
				if ($this->CPModCZPersInfoAllowEditUsername == '0') {
					$this->CPModCZPersInfoAllowEditUsername = false;
				} else {
					$this->CPModCZPersInfoAllowEditUsername = true;
				}
				$this->CPModCZPersInfoAllowEditPassword = $fetch[0]['CPModCZPersInfoAllowEditPassword'];
				if ($this->CPModCZPersInfoAllowEditPassword == '0') {
					$this->CPModCZPersInfoAllowEditPassword = false;
				} else {
					$this->CPModCZPersInfoAllowEditPassword = true;
				}
				$this->CPModCZContactStaff = $fetch[0]['CPModCZContactStaff'];
				if ($this->CPModCZContactStaff == '0') {
					$this->CPModCZContactStaff = false;
				} else {
					$this->CPModCZContactStaff = true;
				}
				$this->CPModCZContactStaffAllowOwnerContact = $fetch[0]['CPModCZContactStaffAllowOwnerContact'];
				if ($this->CPModCZContactStaffAllowOwnerContact == '0') {
					$this->CPModCZContactStaffAllowOwnerContact = false;
				} else {
					$this->CPModCZContactStaffAllowOwnerContact = true;
				}
				$this->CPModCZContactStaffAllowAdminContact = $fetch[0]['CPModCZContactStaffAllowAdminContact'];
				if ($this->CPModCZContactStaffAllowAdminContact == '0') {
					$this->CPModCZContactStaffAllowAdminContact = false;
				} else {
					$this->CPModCZContactStaffAllowAdminContact = true;
				}
				$this->CPModCZServiceRequest = $fetch[0]['CPModCZServiceRequest'];
				if ($this->CPModCZServiceRequest == '0') {
					$this->CPModCZServiceRequest = false;
				} else {
					$this->CPModCZServiceRequest = true;
				}
				$this->isArchived = $fetch[0]['isArchived'];
				if ($this->isArchived == '0') {
					$this->isArchived = false;
				} else {
					$this->isArchived = true;
				}
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			}
			
		}

		// Adds the business to the database or updates the values
		public function set() {

			if ($this->setType == 'UPDATE') {

				// Update the values in the database
				if ($this->db->update('business', array("username" => $this->username /* etc */), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database
				if ($this->db->insert('business', array("businessId" => $this->businessId /* etc */))) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}

	}

?>
