<?php

	class business {

		private string $setType;
		private databaseManager $db;

		public string $originalBusinessId; // Used when updating the table incase the adminId has been changed after instantiation.
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		public $businessId;
		public $ownerAdminId;
		public $displayName;
		public $adminDisplayName;
		public $fullLogoFile;
		public $address;
		public $state;
		public $city;
		public $zipCode;
		public $phonePrefix;
		public $phone1;
		public $phone2;
		public $phone3;
		public $email;
		public $currencySymbol;
		public $areaSymbol;
		public $distanceSymbol;
		public $timeZone;
		public $modCust;
		public $modEmail;
		public $modInv;
		public $modInvIncludePastBal;
		public $modEst;
		public $modEstExtName;
		public $modProp;
		public $modPropExtName;
		public $modJobs;
		public $modEquip;
		public $modChem;
		public $modStaff;
		public $modStaffExtName;
		public $modCrews;
		public $modCrewsExtName;
		public $modPayr;
		public $modPayrSatLinkedToDue;
		public $modPayrSalDefaultType;
		public $modPayrSalBaseHourlyRate;
		public $modPayrSalBaseJobPercent;
		public $modPayrSalBasePerJob;
		public $docIdMin;
		public $docIdIsRandom;
		public $invoiceTerm;
		public $estimateValidity;
		public $creditAlertIsEnabled;
		public $creditAlertAmount;
		public $autoApplyCredit;
		public $balanceAlertIsEnabled;
		public $balanceAlertAmount;
		public $SZEnabled;
		public $SZModInfoForStaffPage;
		public $SZModInfoForStaffPageShowBody;
		public $SZModInfoForStaffPageBodyFile;
		public $SZModPersInfo;
		public $SZModPersInfoAllowEditName;
		public $SZModPersInfoAllowEditPhone;
		public $SZModPersInfoAllowEditEmail;
		public $SZModPersInfoAllowEditAddress;
		public $SZModPersInfoAllowEditUsername;
		public $SZModPersInfoAllowEditPassword;
		public $SZModCrews;
		public $SZModJobs;
		public $SZModJobsShowCrewJobs;
		public $SZModPayr;
		public $SZModPayrShowDetails;
		public $SZModContactAdmin;
		public $SZModQuit;
		public $SZModQuitNoticeTerm;
		public $CPEnabled;
		public $CPModHomeShowBody;
		public $CPModHomeBodyFile;
		public $CPModTopBar;
		public $CPModTopBarShowLogo;
		public $CPModTopBarLogoFile;
		public $CPModTopBarShowQuote;
		public $CPModTopBarShowNav;
		public $CPModServices;
		public $CPModServicesShowBody;
		public $CPModServicesBodyFile;
		public $CPModServicesShowList;
		public $CPModContact;
		public $CPModContactShowBody;
		public $CPModContactBodyFile;
		public $CPModContactShowForm;
		public $CPModContactShowInfo;
		public $CPModAbout;
		public $CPModAboutShowBody;
		public $CPModAboutBodyFile;
		public $CPModQuote;
		public $CPModQuoteShowBody;
		public $CPModQuoteBodyFile;
		public $CPModQuoteShowForm;
		public $CPModBlog;
		public $CPModBlogShowBody;
		public $CPModBlogBodyFile;
		public $CPModBlogShowPosts;
		public $CPModTOS;
		public $CPModTOSShowBody;
		public $CPModTOSBodyFile;
		public $CPModTOSShowInvTerm;
		public $CPModTOSShowEstTerm;
		public $CPModCZ;
		public $CPModCZJobs;
		public $CPModCZInvoices;
		public $CPModCZEstimates;
		public $CPModCZPersInfo;
		public $CPModCZPersInfoAllowEditName;
		public $CPModCZPersInfoAllowEditPhone;
		public $CPModCZPersInfoAllowEditEmail;
		public $CPModCZPersInfoAllowEditAddress;
		public $CPModCZPersInfoAllowEditUsername;
		public $CPModCZPersInfoAllowEditPassword;
		public $CPModCZContactStaff;
		public $CPModCZContactStaffAllowOwnerContact;
		public $CPModCZContactStaffAllowAdminContact;
		public $CPModCZServiceRequest;
		public $isArchived;
		public $dateTimeAdded;

		function __construct(string $businessId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('business', '*', "WHERE businessId ='".$this->db->sanitize($businessId)."'");

			// If businessId already exists then set the set method type to UPDATE and fetch the values for the business
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->businessId = $businessId;
				$this->existed = true;

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
				$this->existed = false;

				// Make a new businessId
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'business', 'businessId');
				$this->businessId = $uuid->generatedId;

				$this->ownerAdminId = '';
				$this->displayName = '';
				$this->adminDisplayName = '';
				$this->fullLogoFile = NULL;
				$this->address = NULL;
				$this->state = NULL;
				$this->city = NULL;
				$this->zipCode = NULL;
				$this->phonePrefix = NULL;
				$this->phone1 = NULL;
				$this->phone2 = NULL;
				$this->phone3 = NULL;
				$this->email = NULL;
				$this->currencySymbol = '$';
				$this->areaSymbol = 'ft';
				$this->distanceSymbol = 'mi';
				$this->timeZone = 'America/New York';
				$this->modCust = '0';
				$this->modEmail = '0';
				$this->modInv = '0';
				$this->modInvIncludePastBal = '0';
				$this->modEst = '0';
				$this->modEstExtName = NULL;
				$this->modProp = '0';
				$this->modPropExtName = NULL;
				$this->modJobs = '0';
				$this->modEquip = '0';
				$this->modChem = '0';
				$this->modStaff = '0';
				$this->modStaffExtName = NULL;
				$this->modCrews = '0';
				$this->modCrewsExtName = NULL;
				$this->modPayr = '0';
				$this->modPayrSatLinkedToDue = '0';
				$this->modPayrSalDefaultType = '';
				$this->modPayrSalBaseHourlyRate = '0';
				$this->modPayrSalBaseJobPercent = '0';
				$this->modPayrSalBasePerJob = '0';
				$this->docIdMin = '0';
				$this->docIdIsRandom = '0';
				$this->invoiceTerm = NULL;
				$this->estimateValidity = NULL;
				$this->creditAlertIsEnabled = '0';
				$this->creditAlertAmount = '0';
				$this->autoApplyCredit = '0';
				$this->balanceAlertIsEnabled = '0';
				$this->balanceAlertAmount = '0';
				$this->SZEnabled = '0';
				$this->SZModInfoForStaffPage = '0';
				$this->SZModInfoForStaffPageShowBody = '0';
				$this->SZModInfoForStaffPageBodyFile = NULL;
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
				$this->CPModHomeBodyFile = NULL;
				$this->CPModTopBar = '0';
				$this->CPModTopBarShowLogo = '0';
				$this->CPModTopBarLogoFile = NULL;
				$this->CPModTopBarShowQuote = '0';
				$this->CPModTopBarShowNav = '0';
				$this->CPModServices = '0';
				$this->CPModServicesShowBody = '0';
				$this->CPModServicesBodyFile = NULL;
				$this->CPModServicesShowList = '0';
				$this->CPModContact = '0';
				$this->CPModContactShowBody = '0';
				$this->CPModContactBodyFile = NULL;
				$this->CPModContactShowForm = '0';
				$this->CPModContactShowInfo = '0';
				$this->CPModAbout = '0';
				$this->CPModAboutShowBody = '0';
				$this->CPModAboutBodyFile = NULL;
				$this->CPModQuote = '0';
				$this->CPModQuoteShowBody = '0';
				$this->CPModQuoteBodyFile = NULL;
				$this->CPModQuoteShowForm = '0';
				$this->CPModBlog = '0';
				$this->CPModBlogShowBody = '0';
				$this->CPModBlogBodyFile = NULL;
				$this->CPModBlogShowPosts = '0';
				$this->CPModTOS = '0';
				$this->CPModTOSShowBody = '0';
				$this->CPModTOSBodyFile = NULL;
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

			$this->originalBusinessId = $this->businessId;
			
		}

		// Adds the business to the database or updates the values
		public function set() {

			$attributes = array(
				'businessId' => $this->db->sanitize($this->businessId),
				'ownerAdminId' => $this->db->sanitize($this->ownerAdminId),
				'displayName' => $this->db->sanitize($this->displayName),
				'adminDisplayName' => $this->db->sanitize($this->adminDisplayName),
				'fullLogoFile' => $this->db->sanitize($this->fullLogoFile),
				'address' => $this->db->sanitize($this->address),
				'state' => $this->db->sanitize($this->state),
				'city' => $this->db->sanitize($this->city),
				'zipCode' => $this->db->sanitize($this->zipCode),
				'phonePrefix' => $this->db->sanitize($this->phonePrefix),
				'phone1' => $this->db->sanitize($this->phone1),
				'phone2' => $this->db->sanitize($this->phone2),
				'phone3' => $this->db->sanitize($this->phone3),
				'email' => $this->db->sanitize($this->email),
				'currencySymbol' => $this->db->sanitize($this->currencySymbol),
				'areaSymbol' => $this->db->sanitize($this->areaSymbol),
				'distanceSymbol' => $this->db->sanitize($this->distanceSymbol),
				'timeZone' => $this->db->sanitize($this->timeZone),
				'modCust' => $this->db->sanitize($this->modCust),
				'modEmail' => $this->db->sanitize($this->modEmail),
				'modInv' => $this->db->sanitize($this->modInv),
				'modInvIncludePastBal' => $this->db->sanitize($this->modInvIncludePastBal),
				'modEst' => $this->db->sanitize($this->modEst),
				'modEstExtName' => $this->db->sanitize($this->modEstExtName),
				'modProp' => $this->db->sanitize($this->modProp),
				'modPropExtName' => $this->db->sanitize($this->modPropExtName),
				'modJobs' => $this->db->sanitize($this->modJobs),
				'modEquip'=> $this->db->sanitize($this->modEquip),
				'modChem' => $this->db->sanitize($this->modChem),
				'modStaff' => $this->db->sanitize($this->modStaff),
				'modStaffExtName' => $this->db->sanitize($this->modStaffExtName),
				'modCrews' => $this->db->sanitize($this->modCrews),
				'modCrewsExtName' => $this->db->sanitize($this->modCrewsExtName),
				'modPayr' => $this->db->sanitize($this->modPayr),
				'modPayrSatLinkedToDue' => $this->db->sanitize($this->modPayrSatLinkedToDue),
				'modPayrSalDefaultType' => $this->db->sanitize($this->modPayrSalDefaultType),
				'modPayrSalBaseHourlyRate' => $this->db->sanitize($this->modPayrSalBaseHourlyRate),
				'modPayrSalBaseJobPercent' => $this->db->sanitize($this->modPayrSalBaseJobPercent),
				'modPayrSalBasePerJob' => $this->db->sanitize($this->modPayrSalBasePerJob),
				'docIdMin' => $this->db->sanitize($this->docIdMin),
				'docIdIsRandom' => $this->db->sanitize($this->docIdIsRandom),
				'invoiceTerm' => $this->db->sanitize($this->invoiceTerm),
				'estimateValidity' => $this->db->sanitize($this->estimateValidity),
				'creditAlertIsEnabled' => $this->db->sanitize($this->creditAlertIsEnabled),
				'creditAlertAmount' => $this->db->sanitize($this->creditAlertAmount),
				'autoApplyCredit' => $this->db->sanitize($this->autoApplyCredit),
				'balanceAlertIsEnabled' => $this->db->sanitize($this->balanceAlertIsEnabled),
				'balanceAlertAmount' => $this->db->sanitize($this->balanceAlertAmount),
				'SZEnabled' => $this->db->sanitize($this->SZEnabled),
				'SZModInfoForStaffPage' => $this->db->sanitize($this->SZModInfoForStaffPage),
				'SZModInfoForStaffPageShowBody' => $this->db->sanitize($this->SZModInfoForStaffPageShowBody),
				'SZModInfoForStaffPageBodyFile' => $this->db->sanitize($this->SZModInfoForStaffPageBodyFile),
				'SZModPersInfo' => $this->db->sanitize($this->SZModPersInfo),
				'SZModPersInfoAllowEditName' => $this->db->sanitize($this->SZModPersInfoAllowEditName),
				'SZModPersInfoAllowEditPhone' => $this->db->sanitize($this->SZModPersInfoAllowEditPhone),
				'SZModPersInfoAllowEditEmail' => $this->db->sanitize($this->SZModPersInfoAllowEditEmail),
				'SZModPersInfoAllowEditAddress' => $this->db->sanitize($this->SZModPersInfoAllowEditAddress),
				'SZModPersInfoAllowEditUsername' => $this->db->sanitize($this->SZModPersInfoAllowEditUsername),
				'SZModPersInfoAllowEditPassword' => $this->db->sanitize($this->SZModPersInfoAllowEditPassword),
				'SZModCrews' => $this->db->sanitize($this->SZModCrews),
				'SZModJobs' => $this->db->sanitize($this->SZModJobs),
				'SZModJobsShowCrewJobs' => $this->db->sanitize($this->SZModJobsShowCrewJobs),
				'SZModPayr' => $this->db->sanitize($this->SZModPayr),
				'SZModPayrShowDetails' => $this->db->sanitize($this->SZModPayrShowDetails),
				'SZModContactAdmin' => $this->db->sanitize($this->SZModContactAdmin),
				'SZModQuit' => $this->db->sanitize($this->SZModQuit),
				'SZModQuitNoticeTerm' => $this->db->sanitize($this->SZModQuitNoticeTerm),
				'CPEnabled' => $this->db->sanitize($this->CPEnabled),
				'CPModHomeShowBody' => $this->db->sanitize($this->CPModHomeShowBody),
				'CPModHomeBodyFile' => $this->db->sanitize($this->CPModHomeBodyFile),
				'CPModTopBar' => $this->db->sanitize($this->CPModTopBar),
				'CPModTopBarShowLogo' => $this->db->sanitize($this->CPModTopBarShowLogo),
				'CPModTopBarLogoFile' => $this->db->sanitize($this->CPModTopBarLogoFile),
				'CPModTopBarShowQuote' => $this->db->sanitize($this->CPModTopBarShowQuote),
				'CPModTopBarShowNav' => $this->db->sanitize($this->CPModTopBarShowNav),
				'CPModServices' => $this->db->sanitize($this->CPModServices),
				'CPModServicesShowBody' => $this->db->sanitize($this->CPModServicesShowBody),
				'CPModServicesBodyFile' => $this->db->sanitize($this->CPModServicesBodyFile),
				'CPModServicesShowList' => $this->db->sanitize($this->CPModServicesShowList),
				'CPModContact' => $this->db->sanitize($this->CPModContact),
				'CPModContactShowBody' => $this->db->sanitize($this->CPModContactShowBody),
				'CPModContactBodyFile' => $this->db->sanitize($this->CPModContactBodyFile),
				'CPModContactShowForm' => $this->db->sanitize($this->CPModContactShowForm),
				'CPModContactShowInfo' => $this->db->sanitize($this->CPModContactShowInfo),
				'CPModAbout' => $this->db->sanitize($this->CPModAbout),
				'CPModAboutShowBody' => $this->db->sanitize($this->CPModAboutShowBody),
				'CPModAboutBodyFile' => $this->db->sanitize($this->CPModAboutBodyFile),
				'CPModQuote' => $this->db->sanitize($this->CPModQuote),
				'CPModQuoteShowBody' => $this->db->sanitize($this->CPModQuoteShowBody),
				'CPModQuoteBodyFile' => $this->db->sanitize($this->CPModQuoteBodyFile),
				'CPModQuoteShowForm' => $this->db->sanitize($this->CPModQuoteShowForm),
				'CPModBlog' => $this->db->sanitize($this->CPModBlog),
				'CPModBlogShowBody' => $this->db->sanitize($this->CPModBlogShowBody),
				'CPModBlogBodyFile' => $this->db->sanitize($this->CPModBlogBodyFile),
				'CPModBlogShowPosts' => $this->db->sanitize($this->CPModBlogShowPosts),
				'CPModTOS' => $this->db->sanitize($this->CPModTOS),
				'CPModTOSShowBody' => $this->db->sanitize($this->CPModTOSShowBody),
				'CPModTOSBodyFile' => $this->db->sanitize($this->CPModTOSBodyFile),
				'CPModTOSShowInvTerm' => $this->db->sanitize($this->CPModTOSShowInvTerm),
				'CPModTOSShowEstTerm' => $this->db->sanitize($this->CPModTOSShowEstTerm),
				'CPModCZ' => $this->db->sanitize($this->CPModCZ),
				'CPModCZJobs' => $this->db->sanitize($this->CPModCZJobs),
				'CPModCZInvoices' => $this->db->sanitize($this->CPModCZInvoices),
				'CPModCZEstimates' => $this->db->sanitize($this->CPModCZEstimates),
				'CPModCZPersInfo' => $this->db->sanitize($this->CPModCZPersInfo),
				'CPModCZPersInfoAllowEditName' => $this->db->sanitize($this->CPModCZPersInfoAllowEditName),
				'CPModCZPersInfoAllowEditPhone' => $this->db->sanitize($this->CPModCZPersInfoAllowEditPhone),
				'CPModCZPersInfoAllowEditEmail' => $this->db->sanitize($this->CPModCZPersInfoAllowEditEmail),
				'CPModCZPersInfoAllowEditAddress' => $this->db->sanitize($this->CPModCZPersInfoAllowEditAddress),
				'CPModCZPersInfoAllowEditUsername' => $this->db->sanitize($this->CPModCZPersInfoAllowEditUsername),
				'CPModCZPersInfoAllowEditPassword' => $this->db->sanitize($this->CPModCZPersInfoAllowEditPassword),
				'CPModCZContactStaff' => $this->db->sanitize($this->CPModCZContactStaff),
				'CPModCZContactStaffAllowOwnerContact' => $this->db->sanitize($this->CPModCZContactStaffAllowOwnerContact),
				'CPModCZContactStaffAllowAdminContact' => $this->db->sanitize($this->CPModCZContactStaffAllowAdminContact),
				'CPModCZServiceRequest' => $this->db->sanitize($this->CPModCZServiceRequest),
				'isArchived' => $this->db->sanitize($this->isArchived),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('business', $attributes, "WHERE businessId = ".$this->db->sanitize($this->originalBusinessId), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('business', $attributes)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}

	}

?>
