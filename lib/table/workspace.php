<?php

	class workspace {

		private string $setType;
		private database $db;

		private string $dbWorkspaceId; // Used when updating the table incase the adminId has been changed after instantiation.

		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('displayName', 'adminDisplayName', 'address1', 'address2', 'state', 'city', 'zipCode', 'phonePrefix', 'phone1', 'phone2', 'phone3', 'email', 'timeZone');

		// Main database attributes
		public $workspaceId;
		public $displayName;
		public $adminDisplayName;
		public $fullLogoFile;
		public $address1;
		public $address2;
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
		public $modProp;
		public $modCalendarEvents;
		public $modEquip;
		public $modChem;
		public $modStaff;
		public $modCrews;
		public $modPayr;
		public $modPayrSatLinkedToDue;
		public $modPayrSalDefaultType;
		public $modPayrSalBaseHourlyRate;
		public $modPayrSalBaseCalendarEventPercent;
		public $modPayrSalBasePerCalendarEvent;
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
		public $SZModCalendarEvents;
		public $SZModCalendarEventsShowCrewCalendarEvents;
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
		public $CPModCZCalendarEvents;
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
		public $contacts = array();
		public $notes = array();
		public $authTokens = array();
		public $contactPhoneNumbers = array();
		public $contactEmailAddresses = array();
		public $contactLoginAttempts = array();
		public $contactSavedLogins = array();
		public $contactTags = array();
		public $crews = array();
		public $quoteRequests = array();
		public $quoteRequestServices = array();
		public $serviceListings = array();
		public $contactServiceTickets = array();
		public $adminContactServiceMessages = array();
		public $contactContactServiceMessages = array();
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
		public $paymentMethods = array();
		public $payments = array();
		public $properties = array();
		public $calendarEventCancellations = array();
		public $calendarEventSingulars = array();
		public $calendarEventRecurrings = array();
		public $calendarEventCompleteds = array();
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
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			$this->displayName = '';
			$this->adminDisplayName = '';
			$this->fullLogoFile = NULL;
			$this->address1 = NULL;
			$this->address2 = NULL;
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
			$this->timeZone = 'UTC';
			$this->modCust = '0';
			$this->modEmail = '0';
			$this->modInv = '0';
			$this->modInvIncludePastBal = '0';
			$this->modEst = '0';
			$this->modProp = '0';
			$this->modCalendarEvents = '0';
			$this->modEquip = '0';
			$this->modChem = '0';
			$this->modStaff = '0';
			$this->modCrews = '0';
			$this->modPayr = '0';
			$this->modPayrSatLinkedToDue = '0';
			$this->modPayrSalDefaultType = 'none';
			$this->modPayrSalBaseHourlyRate = '0';
			$this->modPayrSalBaseCalendarEventPercent = '0';
			$this->modPayrSalBasePerCalendarEvent = '0';
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
			$this->SZModCalendarEvents = '0';
			$this->SZModCalendarEventsShowCrewCalendarEvents = '0';
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
			$this->CPModCZCalendarEvents = '0';
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

			// Clear arrays
			$this->admins = array();
			$this->contacts = array();
			$this->notes = array();
			$this->authTokens = array();
			$this->contactPhoneNumbers = array();
			$this->contactEmailAddresses = array();
			$this->contactLoginAttempts = array();
			$this->contactSavedLogins = array();
			$this->contactTags = array();
			$this->crews = array();
			$this->quoteRequests = array();
			$this->quoteRequestServices = array();
			$this->serviceListings = array();
			$this->contactServiceTickets = array();
			$this->adminContactServiceMessages = array();
			$this->contactContactServiceMessages = array();
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
			$this->calendarEventCancellations = array();
			$this->calendarEventSingulars = array();
			$this->calendarEventRecurrings = array();
			$this->calendarEventCompleteds = array();
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
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $workspaceId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('workspace', '*', "WHERE workspaceId ='".$this->db->sanitize($workspaceId)."'");

			// If workspaceId already exists then set the set method type to UPDATE and fetch the values for the workspace
			if ($fetch) {
				$this->workspaceId = $workspaceId;
				$this->displayName = $fetch[0]['displayName'];
				$this->adminDisplayName = $fetch[0]['adminDisplayName'];
				$this->fullLogoFile = $fetch[0]['fullLogoFile'];
				$this->address1 = $fetch[0]['address1'];
				$this->address2 = $fetch[0]['address2'];
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
				$this->modProp = $fetch[0]['modProp'];
				$this->modCalendarEvents = $fetch[0]['modCalendarEvents'];
				$this->modEquip = $fetch[0]['modEquip'];
				$this->modChem = $fetch[0]['modChem'];
				$this->modStaff = $fetch[0]['modStaff'];
				$this->modCrews = $fetch[0]['modCrews'];
				$this->modPayr = $fetch[0]['modPayr'];
				$this->modPayrSatLinkedToDue = $fetch[0]['modPayrSatLinkedToDue'];
				$this->modPayrSalDefaultType = $fetch[0]['modPayrSalDefaultType'];
				$this->modPayrSalBaseHourlyRate = $fetch[0]['modPayrSalBaseHourlyRate'];
				$this->modPayrSalBaseCalendarEventPercent = $fetch[0]['modPayrSalBaseCalendarEventPercent'];
				$this->modPayrSalBasePerCalendarEvent = $fetch[0]['modPayrSalBasePerCalendarEvent'];
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
				$this->SZModCalendarEvents = $fetch[0]['SZModCalendarEvents'];
				$this->SZModCalendarEventsShowCrewCalendarEvents = $fetch[0]['SZModCalendarEventsShowCrewCalendarEvents'];
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
				$this->CPModCZCalendarEvents = $fetch[0]['CPModCZCalendarEvents'];
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

				// Decrypt encrypted data
				foreach ($this->fieldsToEncrypt as $field) {
					if (!is_null($this->{$field}) && !empty($this->{$field})) {
						$this->{$field} = decryptString((string)$this->{$field}, $this->cryptoKey);
					}
					if ($this->{$field} === false) {
						$this->{$field} = 'decryptError';
					}
				}

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If workspaceId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new workspaceId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('workspace', 'workspaceId');
				$this->workspaceId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbWorkspaceId = $this->workspaceId;
			
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
			$fetch = $this->db->select('adminWorkspaceBridge', 'adminId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
		
		// contacts
		public function pullContacts ($params = '') {
			$this->contacts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contact', 'contactId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contacts, $row['contactId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
				return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// notes
		public function pullNotes ($params = '') {
			$this->notes = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('note', 'noteId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->notes, $row['noteId']);
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
			$this->authTokens = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('authToken', 'authTokenId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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

		// contactPhoneNumbers
		public function pullContactPhoneNumbers ($params = '') {
			$this->contactPhoneNumbers = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactPhoneNumber', 'contactPhoneNumberId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactPhoneNumbers, $row['contactPhoneNumberId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactEmailAddresses
		public function pullContactEmailAddresses ($params = '') {
			$this->contactEmailAddresses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactEmailAddress', 'contactEmailAddressId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactEmailAddresses, $row['contactEmailAddressId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactLoginAttempts
		public function pullContactLoginAttempts ($params = '') {
			$this->contactLoginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactLoginAttempt', 'contactLoginAttemptId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactLoginAttempts, $row['contactLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactSavedLogins
		public function pullContactSavedLogins ($params = '') {
			$this->contactSavedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactSavedLogin', 'contactSavedLoginId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactSavedLogins, $row['contactSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactTags
		public function pullContactTags ($params = '') {
			$this->contactTags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactTag', 'contactTagId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactTags, $row['contactTagId']);
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
			$this->crews = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crew', 'crewId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->quoteRequests = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('quoteRequest', 'quoteRequestId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->quoteRequestServices = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('quoteRequestService', 'quoteRequestServiceId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->serviceListings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('serviceListing', 'serviceListingId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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

		// contactServiceTickets
		public function pullContactServiceTickets ($params = '') {
			$this->contactServiceTickets = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactServiceTicket', 'contactServiceTicketId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactServiceTickets, $row['contactServiceTicketId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// adminContactServiceMessages
		public function pullAdminContactServiceMessages ($params = '') {
			$this->adminContactServiceMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminContactServiceMessage', 'adminContactServiceMessageId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->adminContactServiceMessages, $row['adminContactServiceMessageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactContactServiceMessages
		public function pullContactContactServiceMessages ($params = '') {
			$this->contactContactServiceMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactContactServiceMessage', 'contactContactServiceMessageId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactContactServiceMessages, $row['contactContactServiceMessageId']);
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
			$this->chemicals = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemical', 'chemicalId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->chemicalImages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalImage', 'chemicalImageId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->chemicalTags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalTag', 'chemicalTagId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->equipment = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipment', 'equipmentId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->equipmentImages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentImage', 'equipmentImageId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->equipmentTags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentTag', 'equipmentTagId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->equipmentMaintenanceLogs = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentMaintenanceLog', 'equipmentMaintenanceLogId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->equipmentMaintenanceLogImages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentMaintenanceLogImage', 'equipmentMaintenanceLogImageId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->docIds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('docId', 'docIdId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->fileUploads = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('fileUpload', 'fileUploadId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->estimates = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimate', 'estimateId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->estimateItems = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateItem', 'estimateItemId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->estimateApprovals = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateApproval', 'estimateApprovalId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->invoices = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoice', 'invoiceId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->invoiceItems = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoiceItem', 'invoiceItemId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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

		// paymentMethods
		public function pullPaymentMethods ($params = '') {
			$this->paymentMethods = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('paymentMethod', 'paymentMethodId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->paymentMethods, $row['paymentMethodId']);
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
			$this->payments = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payment', 'paymentId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->properties = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('property', 'propertyId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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

		// calendarEventCancellations
		public function pullCalendarEventCancellations ($params = '') {
			$this->calendarEventCancellations = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventCancellation', 'calendarEventCancellationId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventCancellations, $row['calendarEventCancellationId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventSingulars
		public function pullCalendarEventSingulars ($params = '') {
			$this->calendarEventSingulars = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventSingular', 'calendarEventSingularId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventSingulars, $row['calendarEventSingularId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventRecurrings
		public function pullCalendarEventRecurrings ($params = '') {
			$this->calendarEventRecurrings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventRecurring', 'calendarEventRecurringId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventRecurrings, $row['calendarEventRecurringId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventCompleteds
		public function pullCalendarEventCompleteds ($params = '') {
			$this->calendarEventCompleteds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventCompleted', 'calendarEventCompletedId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventCompleteds, $row['calendarEventCompletedId']);
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
			$this->staff = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staff', 'staffId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->staffPhoneNumbers = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffPhoneNumber', 'staffPhoneNumberId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->staffEmailAddresses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffEmailAddress', 'staffEmailAddressId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->staffLoginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffLoginAttempt', 'staffLoginAttemptId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->staffSavedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffSavedLogin', 'staffSavedLoginId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->staffTags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffTag', 'staffTagId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->timeLogs = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('timeLog', 'timeLogId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->payrollDues = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollDue', 'payrollDueId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->payrollSatisfactions = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollSatisfaction', 'payrollSatisfactionId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->mailoutCampaignTemplates = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('mailoutCampaignTemplate', 'mailoutCampaignTemplateId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->emailSends = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('emailSend', 'emailSendId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->emailPixels = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('emailPixel', 'emailPixelId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->smsCampaignTemplates = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('smsCampaignTemplate', 'smsCampaignTemplateId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->smsSends = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('smsSend', 'smsSendId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->blogPosts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogPost', 'blogPostId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->blogTags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogTag', 'blogTagId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
			$this->blogPostReadTokens = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('blogPostReadToken', 'blogPostReadTokenId', "WHERE workspaceId = '$this->dbWorkspaceId'".$params);
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
		// Extra bridge data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function getAdminPermissions($adminId) {
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminWorkspaceBridge', '*', "WHERE workspaceId = '$this->dbWorkspaceId' AND adminId = '".$this->db->sanitize($adminId)."'");
			if ($fetch) {
				return array(
					'isOwner' => $fetch[0]['adminIsOwner'],
					'canManageTag' => $fetch[0]['adminCanManageTag'],
					'canUploadDocument' => $fetch[0]['adminCanUploadDocument'],
					'canManageBlog' => $fetch[0]['adminCanManageBlog'],
					'canManageSMS' => $fetch[0]['adminCanManageSMS'],
					'canManageEmail' => $fetch[0]['adminCanManageEmail'],
					'canManageServiceListing' => $fetch[0]['adminCanManageServiceListing'],
					'canManageQuoteRequest' => $fetch[0]['adminCanManageQuoteRequest'],
					'canManageContactService' => $fetch[0]['adminCanManageContactService'],
					'canManageTimeLog' => $fetch[0]['adminCanManageTimeLog'],
					'canManagePayrollDue' => $fetch[0]['adminCanManagePayrollDue'],
					'canManagePayrollSatisfaction' => $fetch[0]['adminCanManagePayrollSatisfaction'],
					'canManageContact' => $fetch[0]['adminCanManageContact'],
					'canManageStaff' => $fetch[0]['adminCanManageStaff'],
					'canManageCrew' => $fetch[0]['adminCanManageCrew'],
					'canManageEquipment' => $fetch[0]['adminCanManageEquipment'],
					'canManageChemical' => $fetch[0]['adminCanManageChemical'],
					'canManageCalendarEvent' => $fetch[0]['adminCanManageCalendarEvent'],
					'canManageInvoice' => $fetch[0]['adminCanManageInvoice'],
					'canManagePayment' => $fetch[0]['adminCanManagePayment'],
					'canManageEstimate' => $fetch[0]['adminCanManageEstimate'],
					'canApproveEstimate' => $fetch[0]['adminCanApproveEstimate']
				);
			} elseif ($this->db->getLastError() === '') {
					return false;
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

			$attr = array(
				'workspaceId' => $this->dbWorkspaceId,
				'displayName' => $this->displayName,
				'adminDisplayName' => $this->adminDisplayName,
				'fullLogoFile' => $this->fullLogoFile,
				'address1' => $this->address1,
				'address2' => $this->address2,
				'state' => $this->state,
				'city' => $this->city,
				'zipCode' => $this->zipCode,
				'phonePrefix' => $this->phonePrefix,
				'phone1' => $this->phone1,
				'phone2' => $this->phone2,
				'phone3' => $this->phone3,
				'email' => $this->email,
				'currencySymbol' => $this->currencySymbol,
				'areaSymbol' => $this->areaSymbol,
				'distanceSymbol' => $this->distanceSymbol,
				'timeZone' => $this->timeZone,
				'modCust' => $this->modCust,
				'modEmail' => $this->modEmail,
				'modInv' => $this->modInv,
				'modInvIncludePastBal' => $this->modInvIncludePastBal,
				'modEst' => $this->modEst,
				'modProp' => $this->modProp,
				'modCalendarEvents' => $this->modCalendarEvents,
				'modEquip'=> $this->modEquip,
				'modChem' => $this->modChem,
				'modStaff' => $this->modStaff,
				'modCrews' => $this->modCrews,
				'modPayr' => $this->modPayr,
				'modPayrSatLinkedToDue' => $this->modPayrSatLinkedToDue,
				'modPayrSalDefaultType' => $this->modPayrSalDefaultType,
				'modPayrSalBaseHourlyRate' => $this->modPayrSalBaseHourlyRate,
				'modPayrSalBaseCalendarEventPercent' => $this->modPayrSalBaseCalendarEventPercent,
				'modPayrSalBasePerCalendarEvent' => $this->modPayrSalBasePerCalendarEvent,
				'docIdMin' => $this->docIdMin,
				'docIdIsRandom' => $this->docIdIsRandom,
				'invoiceTerm' => $this->invoiceTerm,
				'estimateValidity' => $this->estimateValidity,
				'creditAlertIsEnabled' => $this->creditAlertIsEnabled,
				'creditAlertAmount' => $this->creditAlertAmount,
				'autoApplyCredit' => $this->autoApplyCredit,
				'balanceAlertIsEnabled' => $this->balanceAlertIsEnabled,
				'balanceAlertAmount' => $this->balanceAlertAmount,
				'SZEnabled' => $this->SZEnabled,
				'SZModInfoForStaffPage' => $this->SZModInfoForStaffPage,
				'SZModInfoForStaffPageShowBody' => $this->SZModInfoForStaffPageShowBody,
				'SZModInfoForStaffPageBodyFile' => $this->SZModInfoForStaffPageBodyFile,
				'SZModPersInfo' => $this->SZModPersInfo,
				'SZModPersInfoAllowEditName' => $this->SZModPersInfoAllowEditName,
				'SZModPersInfoAllowEditPhone' => $this->SZModPersInfoAllowEditPhone,
				'SZModPersInfoAllowEditEmail' => $this->SZModPersInfoAllowEditEmail,
				'SZModPersInfoAllowEditAddress' => $this->SZModPersInfoAllowEditAddress,
				'SZModPersInfoAllowEditUsername' => $this->SZModPersInfoAllowEditUsername,
				'SZModPersInfoAllowEditPassword' => $this->SZModPersInfoAllowEditPassword,
				'SZModCrews' => $this->SZModCrews,
				'SZModCalendarEvents' => $this->SZModCalendarEvents,
				'SZModCalendarEventsShowCrewCalendarEvents' => $this->SZModCalendarEventsShowCrewCalendarEvents,
				'SZModPayr' => $this->SZModPayr,
				'SZModPayrShowDetails' => $this->SZModPayrShowDetails,
				'SZModContactAdmin' => $this->SZModContactAdmin,
				'SZModQuit' => $this->SZModQuit,
				'SZModQuitNoticeTerm' => $this->SZModQuitNoticeTerm,
				'CPEnabled' => $this->CPEnabled,
				'CPModHomeShowBody' => $this->CPModHomeShowBody,
				'CPModHomeBodyFile' => $this->CPModHomeBodyFile,
				'CPModTopBar' => $this->CPModTopBar,
				'CPModTopBarShowLogo' => $this->CPModTopBarShowLogo,
				'CPModTopBarLogoFile' => $this->CPModTopBarLogoFile,
				'CPModTopBarShowQuote' => $this->CPModTopBarShowQuote,
				'CPModTopBarShowNav' => $this->CPModTopBarShowNav,
				'CPModServices' => $this->CPModServices,
				'CPModServicesShowBody' => $this->CPModServicesShowBody,
				'CPModServicesBodyFile' => $this->CPModServicesBodyFile,
				'CPModServicesShowList' => $this->CPModServicesShowList,
				'CPModContact' => $this->CPModContact,
				'CPModContactShowBody' => $this->CPModContactShowBody,
				'CPModContactBodyFile' => $this->CPModContactBodyFile,
				'CPModContactShowForm' => $this->CPModContactShowForm,
				'CPModContactShowInfo' => $this->CPModContactShowInfo,
				'CPModAbout' => $this->CPModAbout,
				'CPModAboutShowBody' => $this->CPModAboutShowBody,
				'CPModAboutBodyFile' => $this->CPModAboutBodyFile,
				'CPModQuote' => $this->CPModQuote,
				'CPModQuoteShowBody' => $this->CPModQuoteShowBody,
				'CPModQuoteBodyFile' => $this->CPModQuoteBodyFile,
				'CPModQuoteShowForm' => $this->CPModQuoteShowForm,
				'CPModBlog' => $this->CPModBlog,
				'CPModBlogShowBody' => $this->CPModBlogShowBody,
				'CPModBlogBodyFile' => $this->CPModBlogBodyFile,
				'CPModBlogShowPosts' => $this->CPModBlogShowPosts,
				'CPModTOS' => $this->CPModTOS,
				'CPModTOSShowBody' => $this->CPModTOSShowBody,
				'CPModTOSBodyFile' => $this->CPModTOSBodyFile,
				'CPModTOSShowInvTerm' => $this->CPModTOSShowInvTerm,
				'CPModTOSShowEstTerm' => $this->CPModTOSShowEstTerm,
				'CPModCZ' => $this->CPModCZ,
				'CPModCZCalendarEvents' => $this->CPModCZCalendarEvents,
				'CPModCZInvoices' => $this->CPModCZInvoices,
				'CPModCZEstimates' => $this->CPModCZEstimates,
				'CPModCZPersInfo' => $this->CPModCZPersInfo,
				'CPModCZPersInfoAllowEditName' => $this->CPModCZPersInfoAllowEditName,
				'CPModCZPersInfoAllowEditPhone' => $this->CPModCZPersInfoAllowEditPhone,
				'CPModCZPersInfoAllowEditEmail' => $this->CPModCZPersInfoAllowEditEmail,
				'CPModCZPersInfoAllowEditAddress' => $this->CPModCZPersInfoAllowEditAddress,
				'CPModCZPersInfoAllowEditUsername' => $this->CPModCZPersInfoAllowEditUsername,
				'CPModCZPersInfoAllowEditPassword' => $this->CPModCZPersInfoAllowEditPassword,
				'CPModCZContactStaff' => $this->CPModCZContactStaff,
				'CPModCZContactStaffAllowOwnerContact' => $this->CPModCZContactStaffAllowOwnerContact,
				'CPModCZContactStaffAllowAdminContact' => $this->CPModCZContactStaffAllowAdminContact,
				'CPModCZServiceRequest' => $this->CPModCZServiceRequest,
				'isArchived' => $this->isArchived,
				'dateTimeAdded' => $this->dateTimeAdded
			);

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if ($attr[$field] != NULL) {
					$attr[$field] = encryptString((string)$attr[$field], $this->cryptoKey);
				}
			}

			$attributes = array(
				'workspaceId' => $this->db->sanitize($this->dbWorkspaceId),
				'displayName' => $this->db->sanitize($attr['displayName']),
				'adminDisplayName' => $this->db->sanitize($attr['adminDisplayName']),
				'fullLogoFile' => $this->db->sanitize($attr['fullLogoFile']),
				'address1' => $this->db->sanitize($attr['address1']),
				'address2' => $this->db->sanitize($attr['address2']),
				'state' => $this->db->sanitize($attr['state']),
				'city' => $this->db->sanitize($attr['city']),
				'zipCode' => $this->db->sanitize($attr['zipCode']),
				'phonePrefix' => $this->db->sanitize($attr['phonePrefix']),
				'phone1' => $this->db->sanitize($attr['phone1']),
				'phone2' => $this->db->sanitize($attr['phone2']),
				'phone3' => $this->db->sanitize($attr['phone3']),
				'email' => $this->db->sanitize($attr['email']),
				'currencySymbol' => $this->db->sanitize($attr['currencySymbol']),
				'areaSymbol' => $this->db->sanitize($attr['areaSymbol']),
				'distanceSymbol' => $this->db->sanitize($attr['distanceSymbol']),
				'timeZone' => $this->db->sanitize($attr['timeZone']),
				'modCust' => $this->db->sanitize($attr['modCust']),
				'modEmail' => $this->db->sanitize($attr['modEmail']),
				'modInv' => $this->db->sanitize($attr['modInv']),
				'modInvIncludePastBal' => $this->db->sanitize($attr['modInvIncludePastBal']),
				'modEst' => $this->db->sanitize($attr['modEst']),
				'modProp' => $this->db->sanitize($attr['modProp']),
				'modCalendarEvents' => $this->db->sanitize($attr['modCalendarEvents']),
				'modEquip'=> $this->db->sanitize($attr['modEquip']),
				'modChem' => $this->db->sanitize($attr['modChem']),
				'modStaff' => $this->db->sanitize($attr['modStaff']),
				'modCrews' => $this->db->sanitize($attr['modCrews']),
				'modPayr' => $this->db->sanitize($attr['modPayr']),
				'modPayrSatLinkedToDue' => $this->db->sanitize($attr['modPayrSatLinkedToDue']),
				'modPayrSalDefaultType' => $this->db->sanitize($attr['modPayrSalDefaultType']),
				'modPayrSalBaseHourlyRate' => $this->db->sanitize($attr['modPayrSalBaseHourlyRate']),
				'modPayrSalBaseCalendarEventPercent' => $this->db->sanitize($attr['modPayrSalBaseCalendarEventPercent']),
				'modPayrSalBasePerCalendarEvent' => $this->db->sanitize($attr['modPayrSalBasePerCalendarEvent']),
				'docIdMin' => $this->db->sanitize($attr['docIdMin']),
				'docIdIsRandom' => $this->db->sanitize($attr['docIdIsRandom']),
				'invoiceTerm' => $this->db->sanitize($attr['invoiceTerm']),
				'estimateValidity' => $this->db->sanitize($attr['estimateValidity']),
				'creditAlertIsEnabled' => $this->db->sanitize($attr['creditAlertIsEnabled']),
				'creditAlertAmount' => $this->db->sanitize($attr['creditAlertAmount']),
				'autoApplyCredit' => $this->db->sanitize($attr['autoApplyCredit']),
				'balanceAlertIsEnabled' => $this->db->sanitize($attr['balanceAlertIsEnabled']),
				'balanceAlertAmount' => $this->db->sanitize($attr['balanceAlertAmount']),
				'SZEnabled' => $this->db->sanitize($attr['SZEnabled']),
				'SZModInfoForStaffPage' => $this->db->sanitize($attr['SZModInfoForStaffPage']),
				'SZModInfoForStaffPageShowBody' => $this->db->sanitize($attr['SZModInfoForStaffPageShowBody']),
				'SZModInfoForStaffPageBodyFile' => $this->db->sanitize($attr['SZModInfoForStaffPageBodyFile']),
				'SZModPersInfo' => $this->db->sanitize($attr['SZModPersInfo']),
				'SZModPersInfoAllowEditName' => $this->db->sanitize($attr['SZModPersInfoAllowEditName']),
				'SZModPersInfoAllowEditPhone' => $this->db->sanitize($attr['SZModPersInfoAllowEditPhone']),
				'SZModPersInfoAllowEditEmail' => $this->db->sanitize($attr['SZModPersInfoAllowEditEmail']),
				'SZModPersInfoAllowEditAddress' => $this->db->sanitize($attr['SZModPersInfoAllowEditAddress']),
				'SZModPersInfoAllowEditUsername' => $this->db->sanitize($attr['SZModPersInfoAllowEditUsername']),
				'SZModPersInfoAllowEditPassword' => $this->db->sanitize($attr['SZModPersInfoAllowEditPassword']),
				'SZModCrews' => $this->db->sanitize($attr['SZModCrews']),
				'SZModCalendarEvents' => $this->db->sanitize($attr['SZModCalendarEvents']),
				'SZModCalendarEventsShowCrewCalendarEvents' => $this->db->sanitize($attr['SZModCalendarEventsShowCrewCalendarEvents']),
				'SZModPayr' => $this->db->sanitize($attr['SZModPayr']),
				'SZModPayrShowDetails' => $this->db->sanitize($attr['SZModPayrShowDetails']),
				'SZModContactAdmin' => $this->db->sanitize($attr['SZModContactAdmin']),
				'SZModQuit' => $this->db->sanitize($attr['SZModQuit']),
				'SZModQuitNoticeTerm' => $this->db->sanitize($attr['SZModQuitNoticeTerm']),
				'CPEnabled' => $this->db->sanitize($attr['CPEnabled']),
				'CPModHomeShowBody' => $this->db->sanitize($attr['CPModHomeShowBody']),
				'CPModHomeBodyFile' => $this->db->sanitize($attr['CPModHomeBodyFile']),
				'CPModTopBar' => $this->db->sanitize($attr['CPModTopBar']),
				'CPModTopBarShowLogo' => $this->db->sanitize($attr['CPModTopBarShowLogo']),
				'CPModTopBarLogoFile' => $this->db->sanitize($attr['CPModTopBarLogoFile']),
				'CPModTopBarShowQuote' => $this->db->sanitize($attr['CPModTopBarShowQuote']),
				'CPModTopBarShowNav' => $this->db->sanitize($attr['CPModTopBarShowNav']),
				'CPModServices' => $this->db->sanitize($attr['CPModServices']),
				'CPModServicesShowBody' => $this->db->sanitize($attr['CPModServicesShowBody']),
				'CPModServicesBodyFile' => $this->db->sanitize($attr['CPModServicesBodyFile']),
				'CPModServicesShowList' => $this->db->sanitize($attr['CPModServicesShowList']),
				'CPModContact' => $this->db->sanitize($attr['CPModContact']),
				'CPModContactShowBody' => $this->db->sanitize($attr['CPModContactShowBody']),
				'CPModContactBodyFile' => $this->db->sanitize($attr['CPModContactBodyFile']),
				'CPModContactShowForm' => $this->db->sanitize($attr['CPModContactShowForm']),
				'CPModContactShowInfo' => $this->db->sanitize($attr['CPModContactShowInfo']),
				'CPModAbout' => $this->db->sanitize($attr['CPModAbout']),
				'CPModAboutShowBody' => $this->db->sanitize($attr['CPModAboutShowBody']),
				'CPModAboutBodyFile' => $this->db->sanitize($attr['CPModAboutBodyFile']),
				'CPModQuote' => $this->db->sanitize($attr['CPModQuote']),
				'CPModQuoteShowBody' => $this->db->sanitize($attr['CPModQuoteShowBody']),
				'CPModQuoteBodyFile' => $this->db->sanitize($attr['CPModQuoteBodyFile']),
				'CPModQuoteShowForm' => $this->db->sanitize($attr['CPModQuoteShowForm']),
				'CPModBlog' => $this->db->sanitize($attr['CPModBlog']),
				'CPModBlogShowBody' => $this->db->sanitize($attr['CPModBlogShowBody']),
				'CPModBlogBodyFile' => $this->db->sanitize($attr['CPModBlogBodyFile']),
				'CPModBlogShowPosts' => $this->db->sanitize($attr['CPModBlogShowPosts']),
				'CPModTOS' => $this->db->sanitize($attr['CPModTOS']),
				'CPModTOSShowBody' => $this->db->sanitize($attr['CPModTOSShowBody']),
				'CPModTOSBodyFile' => $this->db->sanitize($attr['CPModTOSBodyFile']),
				'CPModTOSShowInvTerm' => $this->db->sanitize($attr['CPModTOSShowInvTerm']),
				'CPModTOSShowEstTerm' => $this->db->sanitize($attr['CPModTOSShowEstTerm']),
				'CPModCZ' => $this->db->sanitize($attr['CPModCZ']),
				'CPModCZCalendarEvents' => $this->db->sanitize($attr['CPModCZCalendarEvents']),
				'CPModCZInvoices' => $this->db->sanitize($attr['CPModCZInvoices']),
				'CPModCZEstimates' => $this->db->sanitize($attr['CPModCZEstimates']),
				'CPModCZPersInfo' => $this->db->sanitize($attr['CPModCZPersInfo']),
				'CPModCZPersInfoAllowEditName' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditName']),
				'CPModCZPersInfoAllowEditPhone' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditPhone']),
				'CPModCZPersInfoAllowEditEmail' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditEmail']),
				'CPModCZPersInfoAllowEditAddress' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditAddress']),
				'CPModCZPersInfoAllowEditUsername' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditUsername']),
				'CPModCZPersInfoAllowEditPassword' => $this->db->sanitize($attr['CPModCZPersInfoAllowEditPassword']),
				'CPModCZContactStaff' => $this->db->sanitize($attr['CPModCZContactStaff']),
				'CPModCZContactStaffAllowOwnerContact' => $this->db->sanitize($attr['CPModCZContactStaffAllowOwnerContact']),
				'CPModCZContactStaffAllowAdminContact' => $this->db->sanitize($attr['CPModCZContactStaffAllowAdminContact']),
				'CPModCZServiceRequest' => $this->db->sanitize($attr['CPModCZServiceRequest']),
				'isArchived' => $this->db->sanitize($attr['isArchived']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('workspace', $attributes, "WHERE workspaceId = '".$this->db->sanitize($this->dbWorkspaceId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('workspace', $attributes)) {
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
			if (!$this->db->delete('workspace', "WHERE workspaceId = '".$this->db->sanitize($this->dbWorkspaceId)."'", 1)) {
				return $this->db->getLastError();
			}

			$this->setToDefaults();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
