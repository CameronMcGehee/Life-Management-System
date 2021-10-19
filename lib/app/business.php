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
		public string $zipCode;
		public string $phonePrefix;
		public string $phone1;
		public string $phone2;
		public string $phone3;
		public string $email;
		public string $currencySymbol;
		public string $areaSymbol;
		public string $distanceSymbol;
		public string $timeZone;
		public string $modCust;
		public string $modEmail;
		public string $modInv;
		public string $modInvIncludePastBal;
		public string $modEst;
		public string $modEstExtName;
		public string $modProp;
		public string $modPropExtName;
		public string $modJobs;
		public string $modEquip;
		public string $modChem;
		public string $modStaff;
		public string $modStaffExtName;
		public string $modCrews;
		public string $modCrewsExtName;
		public string $modPayr;
		public string $modPayrSatLinkedToDue;
		public string $modPayrSalDefaultType;
		public string $modPayrSalBaseHourlyRate;
		public string $modPayrSalBaseJobPercent;
		public string $modPayrSalBasePerJob;
		public string $docIdMin;
		public string $docIdIsRandom;
		public string $invoiceTerm;
		public string $estimateValidity;
		public string $creditAlertIsEnabled;
		public string $creditAlertAmount;
		public string $autoApplyCredit;
		public string $balanceAlertIsEnabled;
		public string $balanceAlertAmount;
		public string $SZEnabled;
		public string $SZModInfoForStaffPage;
		public string $SZModInfoForStaffPageShowBody;
		public string $SZModInfoForStaffPageBodyFile;
		public string $SZModPersInfo;
		public string $SZModPersInfoAllowEditName;
		public string $SZModPersInfoAllowEditPhone;
		public string $SZModPersInfoAllowEditEmail;
		public string $SZModPersInfoAllowEditAddress;
		public string $SZModPersInfoAllowEditUsername;
		public string $SZModPersInfoAllowEditPassword;
		public string $SZModCrews;
		public string $SZModJobs;
		public string $SZModJobsShowCrewJobs;
		public string $SZModPayr;
		public string $SZModPayrShowDetails;
		public string $SZModContactAdmin;
		public string $SZModQuit;
		public string $SZModQuitNoticeTerm;
		public string $CPEnabled;
		public string $CPModHomeShowBody;
		public string $CPModHomeBodyFile;
		public string $CPModTopBar;
		public string $CPModTopBarShowLogo;
		public string $CPModTopBarLogoFile;
		public string $CPModTopBarShowQuote;
		public string $CPModTopBarShowNav;
		public string $CPModServices;
		public string $CPModServicesShowBody;
		public string $CPModServicesBodyFile;
		public string $CPModServicesShowList;
		public string $CPModContact;
		public string $CPModContactShowBody;
		public string $CPModContactBodyFile;
		public string $CPModContactShowForm;
		public string $CPModContactShowInfo;
		public string $CPModAbout;
		public string $CPModAboutShowBody;
		public string $CPModAboutBodyFile;
		public string $CPModQuote;
		public string $CPModQuoteShowBody;
		public string $CPModQuoteBodyFile;
		public string $CPModQuoteShowForm;
		public string $CPModBlog;
		public string $CPModBlogShowBody;
		public string $CPModBlogBodyFile;
		public string $CPModBlogShowPosts;
		public string $CPModTOS;
		public string $CPModTOSShowBody;
		public string $CPModTOSBodyFile;
		public string $CPModTOSShowInvTerm;
		public string $CPModTOSShowEstTerm;
		public string $CPModCZ;
		public string $CPModCZJobs;
		public string $CPModCZInvoices;
		public string $CPModCZEstimates;
		public string $CPModCZPersInfo;
		public string $CPModCZPersInfoAllowEditName;
		public string $CPModCZPersInfoAllowEditPhone;
		public string $CPModCZPersInfoAllowEditEmail;
		public string $CPModCZPersInfoAllowEditAddress;
		public string $CPModCZPersInfoAllowEditUsername;
		public string $CPModCZPersInfoAllowEditPassword;
		public string $CPModCZContactStaff;
		public string $CPModCZContactStaffAllowOwnerContact;
		public string $CPModCZContactStaffAllowAdminContact;
		public string $CPModCZServiceRequest;
		public string $isArchived;
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
				$this->zipCode = $fetch[0]['zipCode'];
				$this->phonePrefix = $fetch[0]['phonePrefix'];
				$this->phone1 = $fetch[0]['phone1'];
				$this->phone2 = $fetch[0]['phone2'];
				$this->phone3 = $fetch[0]['phone3'];
				$this->email = $fetch[0]['email'];
				$this->currencySymbol = $fetch[0]['currencySymbol'];
				$this->areaSymbol = $fetch[0]['areaSymbol'];
				$this->distanceSymbol = $fetch[0]['distanceSymbol'];
				$this->timeZone = $fetch[0]['timeZone'];
				$this->modCust = $fetch[0]['modCust'];
				$this->modEmail = $fetch[0]['modEmail'];
				$this->modInv = $fetch[0]['modInv'];
				$this->modInvIncludePastBal = $fetch[0]['modInvIncludePastBal'];
				$this->modEst = $fetch[0]['modEst'];
				$this->modEstExtName = $fetch[0]['modEstExtName'];
				$this->modProp = $fetch[0]['modProp'];
				$this->modPropExtName = $fetch[0]['modPropExtName'];
				$this->modJobs = $fetch[0]['modJobs'];
				$this->modEquip = $fetch[0]['modEquip'];
				$this->modChem = $fetch[0]['modChem'];
				$this->modStaff = $fetch[0]['modStaff'];
				$this->modStaffExtName = $fetch[0]['modStaffExtName'];
				$this->modCrews = $fetch[0]['modCrews'];
				$this->modCrewsExtName = $fetch[0]['modCrewsExtName'];
				$this->modPayr = $fetch[0]['modPayr'];
				$this->modPayrSatLinkedToDue = $fetch[0]['modPayrSatLinkedToDue'];
				$this->modPayrSalDefaultType = $fetch[0]['modPayrSalDefaultType'];
				$this->modPayrSalBaseHourlyRate = $fetch[0]['modPayrSalBaseHourlyRate'];
				$this->modPayrSalBaseJobPercent = $fetch[0]['modPayrSalBaseJobPercent'];
				$this->modPayrSalBasePerJob = $fetch[0]['modPayrSalBasePerJob'];
				$this->docIdMin = $fetch[0]['docIdMin'];
				$this->docIdIsRandom = $fetch[0]['docIdIsRandom'];
				$this->invoiceTerm = $fetch[0]['invoiceTerm'];
				$this->estimateValidity = $fetch[0]['estimateValidity'];
				$this->creditAlertIsEnabled = $fetch[0]['creditAlertIsEnabled'];
				$this->creditAlertAmount = $fetch[0]['creditAlertAmount'];
				$this->autoApplyCredit = $fetch[0]['autoApplyCredit'];
				$this->balanceAlertIsEnabled = $fetch[0]['balanceAlertIsEnabled'];
				$this->balanceAlertAmount = $fetch[0]['balanceAlertAmount'];
				$this->SZEnabled = $fetch[0]['SZEnabled'];
				$this->SZModInfoForStaffPage = $fetch[0]['SZModInfoForStaffPage'];
				$this->SZModInfoForStaffPageShowBody = $fetch[0]['SZModInfoForStaffPageShowBody'];
				$this->SZModInfoForStaffPageBodyFile = $fetch[0]['SZModInfoForStaffPageBodyFile'];
				$this->SZModPersInfo = $fetch[0]['SZModPersInfo'];
				$this->SZModPersInfoAllowEditName = $fetch[0]['SZModPersInfoAllowEditName'];
				$this->SZModPersInfoAllowEditPhone = $fetch[0]['SZModPersInfoAllowEditPhone'];
				$this->SZModPersInfoAllowEditEmail = $fetch[0]['SZModPersInfoAllowEditEmail'];
				$this->SZModPersInfoAllowEditAddress = $fetch[0]['SZModPersInfoAllowEditAddress'];
				$this->SZModPersInfoAllowEditUsername = $fetch[0]['SZModPersInfoAllowEditUsername'];
				$this->SZModPersInfoAllowEditPassword = $fetch[0]['SZModPersInfoAllowEditPassword'];
				$this->SZModCrews = $fetch[0]['SZModCrews'];
				$this->SZModJobs = $fetch[0]['SZModJobs'];
				$this->SZModJobsShowCrewJobs = $fetch[0]['SZModJobsShowCrewJobs'];
				$this->SZModPayr = $fetch[0]['SZModPayr'];
				$this->SZModPayrShowDetails = $fetch[0]['SZModPayrShowDetails'];
				$this->SZModContactAdmin = $fetch[0]['SZModContactAdmin'];
				$this->SZModQuit = $fetch[0]['SZModQuit'];
				$this->SZModQuitNoticeTerm = $fetch[0]['SZModQuitNoticeTerm'];
				$this->CPEnabled = $fetch[0]['CPEnabled'];
				$this->CPModHomeShowBody = $fetch[0]['CPModHomeShowBody'];
				$this->CPModHomeBodyFile = $fetch[0]['CPModHomeBodyFile'];
				$this->CPModTopBar = $fetch[0]['CPModTopBar'];
				$this->CPModTopBarShowLogo = $fetch[0]['CPModTopBarShowLogo'];
				$this->CPModTopBarLogoFile = $fetch[0]['CPModTopBarLogoFile'];
				$this->CPModTopBarShowQuote = $fetch[0]['CPModTopBarShowQuote'];
				$this->CPModTopBarShowNav = $fetch[0]['CPModTopBarShowNav'];
				$this->CPModServices = $fetch[0]['CPModServices'];
				$this->CPModServicesShowBody = $fetch[0]['CPModServicesShowBody'];
				$this->CPModServicesBodyFile = $fetch[0]['CPModServicesBodyFile'];
				$this->CPModServicesShowList = $fetch[0]['CPModServicesShowList'];
				$this->CPModContact = $fetch[0]['CPModContact'];
				$this->CPModContactShowBody = $fetch[0]['CPModContactShowBody'];
				$this->CPModContactBodyFile = $fetch[0]['CPModContactBodyFile'];
				$this->CPModContactShowForm = $fetch[0]['CPModContactShowForm'];
				$this->CPModContactShowInfo = $fetch[0]['CPModContactShowInfo'];
				$this->CPModAbout = $fetch[0]['CPModAbout'];
				$this->CPModAboutShowBody = $fetch[0]['CPModAboutShowBody'];
				$this->CPModAboutBodyFile = $fetch[0]['CPModAboutBodyFile'];
				$this->CPModQuote = $fetch[0]['CPModQuote'];
				$this->CPModQuoteShowBody = $fetch[0]['CPModQuoteShowBody'];
				$this->CPModQuoteBodyFile = $fetch[0]['CPModQuoteBodyFile'];
				$this->CPModQuoteShowForm = $fetch[0]['CPModQuoteShowForm'];
				$this->CPModBlog = $fetch[0]['CPModBlog'];
				$this->CPModBlogShowBody = $fetch[0]['CPModBlogShowBody'];
				$this->CPModBlogBodyFile = $fetch[0]['CPModBlogBodyFile'];
				$this->CPModBlogShowPosts = $fetch[0]['CPModBlogShowPosts'];
				$this->CPModTOS = $fetch[0]['CPModTOS'];
				$this->CPModTOSShowBody = $fetch[0]['CPModTOSShowBody'];
				$this->CPModTOSBodyFile = $fetch[0]['CPModTOSBodyFile'];
				$this->CPModTOSShowInvTerm = $fetch[0]['CPModTOSShowInvTerm'];
				$this->CPModTOSShowEstTerm = $fetch[0]['CPModTOSShowEstTerm'];
				$this->CPModCZ = $fetch[0]['CPModCZ'];
				$this->CPModCZJobs = $fetch[0]['CPModCZJobs'];
				$this->CPModCZInvoices = $fetch[0]['CPModCZInvoices'];
				$this->CPModCZEstimates = $fetch[0]['CPModCZEstimates'];
				$this->CPModCZPersInfo = $fetch[0]['CPModCZPersInfo'];
				$this->CPModCZPersInfoAllowEditName = $fetch[0]['CPModCZPersInfoAllowEditName'];
				$this->CPModCZPersInfoAllowEditPhone = $fetch[0]['CPModCZPersInfoAllowEditPhone'];
				$this->CPModCZPersInfoAllowEditEmail = $fetch[0]['CPModCZPersInfoAllowEditEmail'];
				$this->CPModCZPersInfoAllowEditAddress = $fetch[0]['CPModCZPersInfoAllowEditAddress'];
				$this->CPModCZPersInfoAllowEditUsername = $fetch[0]['CPModCZPersInfoAllowEditUsername'];
				$this->CPModCZPersInfoAllowEditPassword = $fetch[0]['CPModCZPersInfoAllowEditPassword'];
				$this->CPModCZContactStaff = $fetch[0]['CPModCZContactStaff'];
				$this->CPModCZContactStaffAllowOwnerContact = $fetch[0]['CPModCZContactStaffAllowOwnerContact'];
				$this->CPModCZContactStaffAllowAdminContact = $fetch[0]['CPModCZContactStaffAllowAdminContact'];
				$this->CPModCZServiceRequest = $fetch[0]['CPModCZServiceRequest'];
				$this->isArchived = $fetch[0]['isArchived'];
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
				$this->fullLogoFile = 'NULL';
				$this->address = 'NULL';
				$this->state = 'NULL';
				$this->city = 'NULL';
				$this->zipCode = 'NULL';
				$this->phonePrefix = 'NULL';
				$this->phone1 = 'NULL';
				$this->phone2 = 'NULL';
				$this->phone3 = 'NULL';
				$this->email = 'NULL';
				$this->currencySymbol = '$';
				$this->areaSymbol = 'ft';
				$this->distanceSymbol = 'mi';
				$this->timeZone = 'America/New York';
				$this->modCust = '0';
				$this->modEmail = '0';
				$this->modInv = '0';
				$this->modInvIncludePastBal = '0';
				$this->modEst = '0';
				$this->modEstExtName = 'NULL';
				$this->modProp = '0';
				$this->modPropExtName = 'NULL';
				$this->modJobs = '0';
				$this->modEquip = '0';
				$this->modChem = '0';
				$this->modStaff = '0';
				$this->modStaffExtName = 'NULL';
				$this->modCrews = '0';
				$this->modCrewsExtName = 'NULL';
				$this->modPayr = '0';
				$this->modPayrSatLinkedToDue = '0';
				$this->modPayrSalDefaultType = '';
				$this->modPayrSalBaseHourlyRate = '0';
				$this->modPayrSalBaseJobPercent = '0';
				$this->modPayrSalBasePerJob = '0';
				$this->docIdMin = '0';
				$this->docIdIsRandom = '0';
				$this->invoiceTerm = 'NULL';
				$this->estimateValidity = 'NULL';
				$this->creditAlertIsEnabled = '0';
				$this->creditAlertAmount = '0';
				$this->autoApplyCredit = '0';
				$this->balanceAlertIsEnabled = '0';
				$this->balanceAlertAmount = '0';
				$this->SZEnabled = '0';
				$this->SZModInfoForStaffPage = '0';
				$this->SZModInfoForStaffPageShowBody = '0';
				$this->SZModInfoForStaffPageBodyFile = 'NULL';
				$this->SZModPersInfo = '0';
				$this->SZModPersInfoAllowEditName = '0';
				$this->SZModPersInfoAllowEditPhone = '0';
				$this->SZModPersInfoAllowEditEmail = '0';
				$this->SZModPersInfoAllowEditAddress = '0';
				$this->SZModPersInfoAllowEditUsername = '0';
				$this->SZModPersInfoAllowEditPassword = '0';
				$this->SZModCrews = '0';
				$this->SZModJobs = '0';
				$this->SZModJobsShowCrewJobs = '0';
				$this->SZModPayr = '0';
				$this->SZModPayrShowDetails = '0';
				$this->SZModContactAdmin = '0';
				$this->SZModQuit = '0';
				$this->SZModQuitNoticeTerm = '0';
				$this->CPEnabled = '0';
				$this->CPModHomeShowBody = '0';
				$this->CPModHomeBodyFile = 'NULL';
				$this->CPModTopBar = '0';
				$this->CPModTopBarShowLogo = '0';
				$this->CPModTopBarLogoFile = 'NULL';
				$this->CPModTopBarShowQuote = '0';
				$this->CPModTopBarShowNav = '0';
				$this->CPModServices = '0';
				$this->CPModServicesShowBody = '0';
				$this->CPModServicesBodyFile = 'NULL';
				$this->CPModServicesShowList = '0';
				$this->CPModContact = '0';
				$this->CPModContactShowBody = '0';
				$this->CPModContactBodyFile = 'NULL';
				$this->CPModContactShowForm = '0';
				$this->CPModContactShowInfo = '0';
				$this->CPModAbout = '0';
				$this->CPModAboutShowBody = '0';
				$this->CPModAboutBodyFile = 'NULL';
				$this->CPModQuote = '0';
				$this->CPModQuoteShowBody = '0';
				$this->CPModQuoteBodyFile = 'NULL';
				$this->CPModQuoteShowForm = '0';
				$this->CPModBlog = '0';
				$this->CPModBlogShowBody = '0';
				$this->CPModBlogBodyFile = 'NULL';
				$this->CPModBlogShowPosts = '0';
				$this->CPModTOS = '0';
				$this->CPModTOSShowBody = '0';
				$this->CPModTOSBodyFile = 'NULL';
				$this->CPModTOSShowInvTerm = '0';
				$this->CPModTOSShowEstTerm = '0';
				$this->CPModCZ = '0';
				$this->CPModCZJobs = '0';
				$this->CPModCZInvoices = '0';
				$this->CPModCZEstimates = '0';
				$this->CPModCZPersInfo = '0';
				$this->CPModCZPersInfoAllowEditName = '0';
				$this->CPModCZPersInfoAllowEditPhone = '0';
				$this->CPModCZPersInfoAllowEditEmail = '0';
				$this->CPModCZPersInfoAllowEditAddress = '0';
				$this->CPModCZPersInfoAllowEditUsername = '0';
				$this->CPModCZPersInfoAllowEditPassword = '0';
				$this->CPModCZContactStaff = '0';
				$this->CPModCZContactStaffAllowOwnerContact = '0';
				$this->CPModCZContactStaffAllowAdminContact = '0';
				$this->CPModCZServiceRequest = '0';
				$this->isArchived = '0';

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
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
