<?php

	class customer {

		private string $setType;
		private databaseManager $db;

		public string $staticCustomerId; // Used when updating the table incase the customerId has been changed after instantiation
		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $customerId;
		public $businessId;
		public $addedByAdminId;
		public $surname;
		public $firstName;
		public $lastName;
		public $billAddress1;
		public $billAddress2;
		public $billCity;
		public $billState;
		public $billZipCode;
		public $creditCache;
		public $overrideCreditAlertIsEnabled;
		public $overrideCreditAlertAmount;
		public $overrideAutoApplyCredit;
		public $balanceCache;
		public $overrideBalanceAlertIsEnabled;
		public $overrideBalanceAlertAmount;
		public $allowCZSignIn;
		public $password;
		public $discountPercent;
		public $overridePaymentTerm;
		public $notes;
		public $dateTimeAdded;

		// Arrays that allow the you to see the linked data. Not used when querying the database, but are useful when retrieving data.
		public $loginAttempts; // (customerLoginAttemptId => ('customerId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		public $savedLogins; // (customerSavedLoginId => ('dateTimeAdded' => value))

		// Arrays to track possible database updates
		private $addedLoginAttempts; // (customerLoginAttemptId => ('customerId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $updatedLoginAttempts; // (customerLoginAttemptId => ('customerId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $removedLoginAttempts; // (customerLoginAttemptId)
		
		private $addedSavedLogins; // (customerSavedLoginId => ('dateTimeAdded' => value))
		private $updatedSavedLogins; // (customerSavedLoginId => ('dateTimeAdded' => value))
		private $removedSavedLogins; // (customerSavedLoginId)

		function __construct(string $customerId = '') {

			// Connect to the database
			require_once dirsurname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('customer', '*', "WHERE customerId ='$customerId'");

			// If customerId already exists then set the set method type to UPDATE and fetch the values for the customer
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->customerId = $customerId;
				$this->existed = true;

				$this->businessId = $fetch[0]['businessId'];
				$this->addedByAdminId = $fetch[0]['addedByAdminId'];
				$this->surname = $fetch[0]['surname'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->$billAddress1 = $fetch[0]['billAddress1'];
				$this->$billAddress2 = $fetch[0]['billAddress2'];
				$this->$billCity = $fetch[0]['billCity'];
				$this->$billState = $fetch[0]['billState'];
				$this->$billZipCode = $fetch[0]['billZipCode'];
				$this->$creditCache = $fetch[0]['creditCache'];
				$this->$overrideCreditAlertIsEnabled = $fetch[0]['overrideCreditAlertIsEnabled'];
				$this->$overrideCreditAlertAmount = $fetch[0]['overrideCreditAlertAmount'];
				$this->$overrideAutoApplyCredit = $fetch[0]['overrideAutoApplyCredit'];
				$this->$balanceCache = $fetch[0]['balanceCache'];
				$this->$overrideBalanceAlertIsEnabled = $fetch[0]['overrideBalanceAlertIsEnabled'];
				$this->$overrideBalanceAlertAmount = $fetch[0]['overrideBalanceAlertAmount'];
				$this->$allowCZSignIn = $fetch[0]['allowCZSignIn'];
				$this->$password = $fetch[0]['password'];
				$this->$discountPercent = $fetch[0]['discountPercent'];
				$this->$overridePaymentTerm = $fetch[0]['overridePaymentTerm'];
				$this->$notes = $fetch[0]['notes'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			// If customerId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new customerId
				require_once dirsurname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'customer', 'customerId');
				$this->customerId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}

				// Default addedByAdminId to the currently selected admin
				if (isset($_SESSION['ultiscape_adminId'])) {
					$this->addedByAdminId = $_SESSION['ultiscape_adminId'];
				} else {
					$this->addedByAdminId = '';
				}
				
				$this->surname = NULL;
				$this->firstName = '';
				$this->lastName = NULL;
				$this->$billAddress1 = NULL;
				$this->$billAddress2 = NULL;
				$this->$billCity = NULL;
				$this->$billState = NULL;
				$this->$billZipCode = NULL;
				$this->$creditCache = '0';
				$this->$overrideCreditAlertIsEnabled = NULL;
				$this->$overrideCreditAlertAmount = NULL;
				$this->$overrideAutoApplyCredit = NULL;
				$this->$balanceCache = '0';
				$this->$overrideBalanceAlertIsEnabled = NULL;
				$this->$overrideBalanceAlertAmount = NULL;
				$this->$allowCZSignIn = '0';
				$this->$password = '';
				$this->$discountPercent = NULL;
				$this->$overridePaymentTerm = NULL;
				$this->$notes = NULL;

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			$this->$staticCustomerId = $this->customerId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions. Will get the data from the database and will reset any changes made to the public arrays before calling set().
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts () {
			// If there are entries for customerloginAttempt then push them to the array
			$fetch = $this->db->select('customerLoginAttempt', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
				$this->loginAttempts = array();
				foreach ($fetch as $row) {
					$this->loginAttempts[$row['customerLoginAttemptId']] = array(
						'businessId' => $row['businessId'],
						'clientIp' => $row['clientIp'],
						'enteredUsername' => $row['enteredUsername'],
						'result' => $row['result'],
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedLoginAttempts = array();
			$this->updatedLoginAttempts = array();
			$this->removedLoginAttempts = array();
		}

		public function pullSavedLogins() {
			// If there are entries for customerSavedLogin then push them to the array
			$fetch = $this->db->select('customerSavedLogin', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
				$this->savedLogins = array();
				foreach ($fetch as $row) {
					$this->savedLogins[$row['customerSavedLoginId']] = array(
						'businessId' => $row['businessId'],
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedSavedLogins = array();
			$this->updatedSavedLogins = array();
			$this->removedSavedLogins = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data handling functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// loginAttempt

		public function addLoginAttempt($clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'customerLoginAttempt', 'customerLoginAttemptId');
			
			$this->addedLoginAttempts[$uuid->generatedId] = array(
				'businessId' => $this->staticBusinessId,
				'customerId' => $this->staticCustomerId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$uuid->generatedId] = array(
				'businessId' => $this->staticBusinessId,
				'customerId' => $this->staticCustomerId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateLoginAttempt($customerLoginAttemptId, $clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedLoginAttempts[$customerLoginAttemptId] = array(
				'businessId' => $this->staticBusinessId,
				'customerId' => $this->staticCustomerId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$customerLoginAttemptId] = array(
				'businessId' => $this->staticBusinessId,
				'customerId' => $this->staticCustomerId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeLoginAttempt($customerLoginAttemptId) {
			array_push($this->removedLoginAttempts, $customerLoginAttemptId);
			unset($this->loginAttempts[$customerLoginAttemptId]);
		}

		// savedLogin

		public function addSavedLogin($dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'customerSavedLogin', 'customerSavedLoginId');
			
			$this->addedSavedLogins[$uuid->generatedId] = array(
				'businessId' => $this->staticBusinessId,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$uuid->generatedId] = array(
				'businessId' => $this->staticBusinessId,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateSavedLogin($customerSavedLoginId, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedSavedLogins[$customerSavedLoginId] = array(
				'businessId' => $this->staticBusinessId,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$customerSavedLoginId] = array(
				'businessId' => $this->staticBusinessId,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeSavedLogin($customerSavedLoginId) {
			array_push($this->removedSavedLogins, $customerSavedLoginId);
			unset($this->savedLogins[$customerSavedLoginId]);
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// Adds the customer to the database or updates the values
		public function set() {

			$attributes = array(
				'customerId' => $this->db->sanitize($this->customerId),
				'businessId' => $this->db->sanitize($this->staticBusinessId),
				'addedByAdminId' => $this->db->sanitize($this->addedByAdminId),
				'surname' => $this->db->sanitize($this->surname),
				'firstName' => $this->db->sanitize($this->firstName),
				'lastName' => $this->db->sanitize($this->lastName),
				'billAddress1' => $this->db->sanitize($this->billAddress1),
				'billAddress2' => $this->db->sanitize($this->billAddress2),
				'billCity' => $this->db->sanitize($this->billCity),
				'billState' => $this->db->sanitize($this->billState),
				'billZipCode' => $this->db->sanitize($this->billZipCode),
				'creditCache' => $this->db->sanitize($this->creditCache),
				'overrideCreditAlertIsEnabled' => $this->db->sanitize($this->overrideCreditAlertIsEnabled),
				'overrideCreditAlertAmount' => $this->db->sanitize($this->overrideCreditAlertAmount),
				'overrideAutoApplyCredit' => $this->db->sanitize($this->overrideAutoApplyCredit),
				'balanceCache' => $this->db->sanitize($this->balanceCache),
				'overrideBalanceAlertIsEnabled' => $this->db->sanitize($this->overrideBalanceAlertIsEnabled),
				'overrideBalanceAlertAmount' => $this->db->sanitize($this->overrideBalanceAlertAmount),
				'allowCZSignIn' => $this->db->sanitize($this->allowCZSignIn),
				'password' => $this->db->sanitize($this->password),
				'discountPercent' => $this->db->sanitize($this->discountPercent),
				'overridePaymentTerm' => $this->db->sanitize($this->overridePaymentTerm),
				'notes' => $this->db->sanitize($this->notes),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customer', $attributes, "WHERE customerId = '".$this->db->sanitize($this->staticCustomerId)."'", 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customer', $attributes)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}
	}

?>
