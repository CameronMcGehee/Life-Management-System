<?php

	class business {

		private string $setType;
		private databaseManager $db;

		private string $dbBusinessId; // Used when updating the table incase the adminId has been changed after instantiation.

		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
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

		// Arrays to store linked data
		public $admins = array();
		public $customers = array();
		public $authTokens = array();
		public $customerPhoneNumbers = array();
		public $customerEmailAddresses = array();
		public $customerLoginAttempts = array();
		public $customerSavedLogins = array();
		public $customerTags = array();
		public $crews = array();
		public $quoteRequests = array();
		public $quoteRequestServices = array();
		public $serviceListings = array();
		public $customerServiceTickets = array();
		public $adminCustomerServiceMessages = array();
		public $customerCustomerServiceMessages = array();
		public $chemicals = array();
		public $chemicalImages = array();
		public $chemicalTags = array();
		public $equipment = array();
		public $equipmentImages = array();
		public $equipmentTags = array();
		public $equipmentMaintenanceLogs = array();
		public $equipmentMaintenanceLogImages = array();
		public $docIds = array();
		public $fileUploads = array();
		public $estimates = array();
		public $estimateItems = array();
		public $estimateApprovals = array();
		public $invoices = array();
		public $invoiceItems = array();
		public $payments = array();
		public $properties = array();
		public $jobCancellations = array();
		public $jobSingulars = array();
		public $jobRecurrings = array();
		public $jobCompleteds = array();
		public $staff = array();
		public $staffPhoneNumbers = array();
		public $staffEmailAddresses = array();
		public $staffLoginAttempts = array();
		public $staffSavedLogins = array();
		public $staffTags = array();
		public $timeLogs = array();
		public $payrollDues = array();
		public $payrollSatisfactions = array();
		public $mailoutCampaignTemplates = array();
		public $emailSends = array();
		public $emailPixels = array();
		public $smsCampaignTemplates = array();
		public $smsSends = array();
		public $blogPosts = array();
		public $blogTags = array();
		public $blogPostReadTokens = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

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
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('business', 'businessId');
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

			$this->dbBusinessId = $this->businessId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// admins
		public function pullAdmins ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', 'adminId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->admins, $row['adminId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// customers
		public function pullCustomers ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customer', 'customerId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customers, $row['customerId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
				return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// authTokens
		public function pullAuthTokens ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('authToken', 'authTokenId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->authTokens, $row['authTokenId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerPhoneNumbers
		public function pullCustomerPhoneNumbers ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerPhoneNumber', 'customerPhoneNumberId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerPhoneNumbers, $row['customerPhoneNumberId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerEmailAddresses
		public function pullCustomerEmailAddresses ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerEmailAddress', 'customerEmailAddressId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerEmailAddresses, $row['customerEmailAddressId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerLoginAttempts
		public function pullCustomerLoginAttempts ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerLoginAttempt', 'customerLoginAttemptId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerLoginAttempts, $row['customerLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerSavedLogins
		public function pullCustomerSavedLogins ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerSavedLogin', 'customerSavedLoginId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerSavedLogins, $row['customerSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerTags
		public function pullCustomerTags ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerTag', 'customerTagId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerTags, $row['customerTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// crews
		public function pullCrews ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crew', 'crewId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->crews, $row['crewId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// quoteRequests
		public function pullQuoteRequest ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('quoteRequest', 'quoteRequestId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->quoteRequests, $row['quoteRequestId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// quoteRequestServices
		public function pullQuoteRequestServices ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('quoteRequestService', 'quoteRequestServiceId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->quoteRequestServices, $row['quoteRequestServiceId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// serviceListings
		public function pullServiceListings ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('serviceListing', 'serviceListingId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->serviceListings, $row['serviceListingId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerServiceTickets
		public function pullCustomerServiceTickets ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerServiceTicket', 'customerServiceTicketId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerServiceTickets, $row['customerServiceTicketId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// adminCustomerServiceMessages
		public function pullAdminCustomerServiceMessages ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminCustomerServiceMessage', 'adminCustomerServiceMessageId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->adminCustomerServiceMessages, $row['adminCustomerServiceMessageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// customerCustomerServiceMessages
		public function pullCustomerCustomerServiceMessages ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerCustomerServiceMessage', 'customerCustomerServiceMessageId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerCustomerServiceMessages, $row['customerCustomerServiceMessageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// chemicals
		public function pullChemicals ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemical', 'chemicalId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicals, $row['chemicalId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// chemicalImages
		public function pullChemicalImages ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalImage', 'chemicalImageId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicalImages, $row['chemicalImageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// chemicalTags
		public function pullChemicalTags ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalTag', 'chemicalTagId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicalTags, $row['chemicalTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipment
		public function pullEquipment ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipment', 'equipmentId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipment, $row['equipmentId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipmentImages
		public function pullEquipmentImages ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentImage', 'equipmentImageId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipmentImages, $row['equipmentImageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipmentTags
		public function pullEquipmentTags ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentTag', 'equipmentTagId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipmentTags, $row['equipmentTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipmentMaintenanceLogs
		public function pullEquipmentMaintenanceLogs ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentMaintenanceLog', 'equipmentMaintenanceLogId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipmentMaintenanceLogs, $row['equipmentMaintenanceLogId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipmentMaintenanceLogImages
		public function pullEquipmentMaintenanceLogImages ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentMaintenanceLogImage', 'equipmentMaintenanceLogImageId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipmentMaintenanceLogImages, $row['equipmentMaintenanceLogImageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// docIds
		public function pullDocIds ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('docId', 'docIdId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->docIds, $row['docIdId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// fileUploads
		public function pullFileUploads ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('fileUpload', 'fileUploadId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->fileUploads, $row['fileUploadId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// estimates
		public function pullEstimates ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimate', 'estimateId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->estimates, $row['estimateId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// estimateItems
		public function pullEstimateItems ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateItem', 'estimateItemId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->estimateItems, $row['estimateItemId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// estimateApprovals
		public function pullEstimateApprovals ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateApproval', 'estimateApprovalId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->estimateApprovals, $row['estimateApprovalId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// invoices
		public function pullInvoices ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoice', 'invoiceId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->invoices, $row['invoiceId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// invoiceItems
		public function pullInvoiceItems ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoiceItem', 'invoiceItemId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->invoiceItems, $row['invoiceItemId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payments
		public function pullPayments ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payment', 'paymentId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payments, $row['paymentId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// properties
		public function pullProperties ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('property', 'propertyId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->properties, $row['propertyId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobCancellations
		public function pullJobCancellations ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCancellation', 'jobCancellationId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobCancellations, $row['jobCancellationId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobSingulars
		public function pullJobSingulars ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobSingular', 'jobSingularId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobSingulars, $row['jobSingularId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobRecurrings
		public function pullJobRecurrings ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobRecurring', 'jobRecurringId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobRecurrings, $row['jobRecurringId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobCompleteds
		public function pullJobCompleteds ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCompleted', 'jobCompletedId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobCompleteds, $row['jobCompletedId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staff
		public function pullStaff ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staff', 'staffId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staff, $row['staffId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffPhoneNumbers
		public function pullStaffPhoneNumbers ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffPhoneNumber', 'staffPhoneNumberId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staffPhoneNumbers, $row['staffPhoneNumberId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffEmailAddresses
		public function pullStaffEmailAddresses ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffEmailAddress', 'staffEmailAddressId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staffEmailAddresses, $row['staffEmailAddressId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffLoginAttempts
		public function pullStaffLoginAttempts ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffLoginAttempt', 'staffLoginAttemptId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staffLoginAttempts, $row['staffLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffSavedLogins
		public function pullStaffSavedLogins ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffSavedLogin', 'staffSavedLoginId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staffSavedLogins, $row['staffSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffTags
		public function pullStaffTags ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffTag', 'staffTagId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staffTags, $row['staffTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// timeLogs
		public function pullTimeLogs ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('timeLog', 'timeLogId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->timeLogs, $row['timeLogId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payrollDues
		public function pullPayrollDues ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollDue', 'payrollDueId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payrollDues, $row['payrollDueId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payrollSatisfactions
		public function pullPayrollSatisfactions ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollSatisfaction', 'payrollSatisfactionId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payrollSatisfactions, $row['payrollSatisfactionId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// mailoutCampaignTemplates
		public function pullMailoutCampaignTemplates ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('mailoutCampaignTemplate', 'mailoutCampaignTemplateId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->mailoutCampaignTemplates, $row['mailoutCampaignTemplateId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// emailSends
		public function pullEmailSends ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('emailSend', 'emailSendId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->emailSends, $row['emailSendId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// emailPixels
		public function pullEmailPixels ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('emailPixel', 'emailPixelId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->emailPixels, $row['emailPixelId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// smsCampaignTemplates
		public function pullSmsCampaignTemplates ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('smsCampaignTemplate', 'smsCampaignTemplateId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->smsCampaignTemplates, $row['smsCampaignTemplateId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// smsSends
		public function pullSmsSends ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('smsSend', 'smsSendId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->smsSends, $row['smsSendId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// blogPosts
		public function pullBlogPosts ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogPost', 'blogPostId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->blogPosts, $row['blogPostId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// blogTags
		public function pullBlogTags ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogTag', 'blogTagId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->blogTags, $row['blogTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// blogPostReadTokens
		public function pullBlogPostReadTokens ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogPostReadToken', 'blogPostReadTokenId', "WHERE businessId = '$this->dbBusinessId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->blogPostReadTokens, $row['blogPostReadTokenId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'businessId' => $this->db->sanitize($this->dbBusinessId),
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
				if ($this->db->update('business', $attributes, "WHERE businessId = '".$this->db->sanitize($this->dbBusinessId)."'", 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('business', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('business', "WHERE businessId = '".$this->db->sanitize($this->dbBusinessId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('business', 'businessId');
			$this->businessId = $uuid->generatedId;

			// Reset all variables
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

			// Clear arrays
			$this->admins = array();
			$this->customers = array();
			$this->authTokens = array();
			$this->customerPhoneNumbers = array();
			$this->customerEmailAddresses = array();
			$this->customerLoginAttempts = array();
			$this->customerSavedLogins = array();
			$this->customerTags = array();
			$this->crews = array();
			$this->quoteRequests = array();
			$this->quoteRequestServices = array();
			$this->serviceListings = array();
			$this->customerServiceTickets = array();
			$this->adminCustomerServiceMessages = array();
			$this->customerCustomerServiceMessages = array();
			$this->chemicals = array();
			$this->chemicalImages = array();
			$this->chemicalTags = array();
			$this->equipment = array();
			$this->equipmentImages = array();
			$this->equipmentTags = array();
			$this->equipmentMaintenanceLogs = array();
			$this->equipmentMaintenanceLogImages = array();
			$this->docIds = array();
			$this->fileUploads = array();
			$this->estimates = array();
			$this->estimateItems = array();
			$this->estimateApprovals = array();
			$this->invoices = array();
			$this->invoiceItems = array();
			$this->payments = array();
			$this->properties = array();
			$this->jobCancellations = array();
			$this->jobSingulars = array();
			$this->jobRecurrings = array();
			$this->jobCompleteds = array();
			$this->staff = array();
			$this->staffPhoneNumbers = array();
			$this->staffEmailAddresses = array();
			$this->staffLoginAttempts = array();
			$this->staffSavedLogins = array();
			$this->staffTags = array();
			$this->timeLogs = array();
			$this->payrollDues = array();
			$this->payrollSatisfactions = array();
			$this->mailoutCampaignTemplates = array();
			$this->emailSends = array();
			$this->emailPixels = array();
			$this->smsCampaignTemplates = array();
			$this->smsSends = array();
			$this->blogPosts = array();
			$this->blogTags = array();
			$this->blogPostReadTokens = array();

			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
