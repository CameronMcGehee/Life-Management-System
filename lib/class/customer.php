<?php

	class customer {

		private string $setType;
		private databaseManager $db;

		private string $staticCustomerId; // Used when updating the table incase the customerId has been changed after instantiation
		private string $staticBusinessId; // Used when updating the table incase the businessId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $customerId;
		public $businessId;
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

		// Arrays to store linked data.
		public $loginAttempts = array();
		public $savedLogins = array();
		public $phoneNumbers = array();
		public $emailAddresses = array();
		public $tags = array();

		function __construct(string $customerId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('customer', '*', "WHERE customerId ='$customerId'");

			// If customerId already exists then set the set method type to UPDATE and fetch the values for the customer
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->customerId = $customerId;
				$this->existed = true;

				$this->businessId = $fetch[0]['businessId'];
				$this->surname = $fetch[0]['surname'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->billAddress1 = $fetch[0]['billAddress1'];
				$this->billAddress2 = $fetch[0]['billAddress2'];
				$this->billCity = $fetch[0]['billCity'];
				$this->billState = $fetch[0]['billState'];
				$this->billZipCode = $fetch[0]['billZipCode'];
				$this->creditCache = $fetch[0]['creditCache'];
				$this->overrideCreditAlertIsEnabled = $fetch[0]['overrideCreditAlertIsEnabled'];
				$this->overrideCreditAlertAmount = $fetch[0]['overrideCreditAlertAmount'];
				$this->overrideAutoApplyCredit = $fetch[0]['overrideAutoApplyCredit'];
				$this->balanceCache = $fetch[0]['balanceCache'];
				$this->overrideBalanceAlertIsEnabled = $fetch[0]['overrideBalanceAlertIsEnabled'];
				$this->overrideBalanceAlertAmount = $fetch[0]['overrideBalanceAlertAmount'];
				$this->allowCZSignIn = $fetch[0]['allowCZSignIn'];
				$this->password = $fetch[0]['password'];
				$this->discountPercent = $fetch[0]['discountPercent'];
				$this->overridePaymentTerm = $fetch[0]['overridePaymentTerm'];
				$this->notes = $fetch[0]['notes'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			// If customerId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new customerId
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'customer', 'customerId');
				$this->customerId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				
				$this->surname = NULL;
				$this->firstName = '';
				$this->lastName = NULL;
				$this->billAddress1 = NULL;
				$this->billAddress2 = NULL;
				$this->billCity = NULL;
				$this->billState = NULL;
				$this->billZipCode = NULL;
				$this->creditCache = '0';
				$this->overrideCreditAlertIsEnabled = NULL;
				$this->overrideCreditAlertAmount = NULL;
				$this->overrideAutoApplyCredit = NULL;
				$this->balanceCache = '0';
				$this->overrideBalanceAlertIsEnabled = NULL;
				$this->overrideBalanceAlertAmount = NULL;
				$this->allowCZSignIn = '0';
				$this->password = '';
				$this->discountPercent = NULL;
				$this->overridePaymentTerm = NULL;
				$this->notes = NULL;

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			$this->staticCustomerId = $this->customerId;
			$this->staticBusinessId = $this->businessId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries for customerloginAttempt then push them to the array
			$fetch = $this->db->select('customerLoginAttempt', 'customerLoginAttemptId', "WHERE customerId = '$this->staticCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['customerLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullSavedLogins($params = '') {
			$this->savedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries for customerSavedLogin then push them to the array
			$fetch = $this->db->select('customerSavedLogin', 'customerSavedLoginId', "WHERE customerId = '$this->staticCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['customerSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullPhoneNumbers($params = '') {
			$this->phoneNumbers = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries for customerPhoneNumber then push them to the array
			$fetch = $this->db->select('customerPhoneNumber', 'customerPhoneNumberId', "WHERE customerId = '$this->staticCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->phoneNumbers, $row['customerPhoneNumberId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullEmailAddresses($params = '') {
			$this->emailAddresses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries for customerEmailAddress then push them to the array
			$fetch = $this->db->select('customerEmailAddress', 'customerEmailAddressId', "WHERE customerId = '$this->staticCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->emailAddresses, $row['customerEmailAddressId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullTags($params = '') {
			$this->tags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries for customerTag then push them to the array
			$fetch = $this->db->select('customerTag', 'customerTagId', "WHERE customerId = '$this->staticCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->tags, $row['customerTagId']);
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

		// Adds the customer to the database or updates the values
		public function set() {

			$attributes = array(
				'customerId' => $this->db->sanitize($this->staticCustomerId),
				'businessId' => $this->db->sanitize($this->staticBusinessId),
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
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}
			}
			return true;
		}
	}

?>
