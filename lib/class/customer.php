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

		// Arrays that allow the you to see the linked data. Not used when querying the database, but are useful when retrieving data.
		public $loginAttempts; // (customerLoginAttemptId => ('customerId', 'clientIp', 'enteredUsername', 'result', 'dateTimeAdded'))
		public $savedLogins; // (customerSavedLoginId => ('dateTimeAdded'))
		public $phoneNumbers; // (customerPhoneNumberId => ('phonePrefix', 'phone1', 'phone2', 'phone3', 'description', 'dateTimeAdded'))
		public $emailAddresses; // (customerEmailAddressId => ('email', 'description', 'dateTimeAdded'))
		public $tags; // (customerTagId => ('tagName', 'dateTimeAdded'))

		// Arrays to track possible database updates
		private $addedLoginAttempts; // (customerLoginAttemptId => ('customerId', 'clientIp', 'enteredUsername', 'result', 'dateTimeAdded'))
		private $updatedLoginAttempts; // (customerLoginAttemptId => ('customerId', 'clientIp', 'enteredUsername', 'result', 'dateTimeAdded'))
		private $removedLoginAttempts; // (customerLoginAttemptId)
		
		private $addedSavedLogins; // (customerSavedLoginId => ('dateTimeAdded'))
		private $updatedSavedLogins; // (customerSavedLoginId => ('dateTimeAdded'))
		private $removedSavedLogins; // (customerSavedLoginId)

		private $addedPhoneNumbers; // (customerPhoneNumberId => ('phonePrefix', 'phone1', 'phone2', 'phone3', 'description', 'dateTimeAdded'))
		private $updatedPhoneNumbers; // (customerPhoneNumberId => ('phonePrefix', 'phone1', 'phone2', 'phone3', 'description', 'dateTimeAdded'))
		private $removedPhoneNumbers; // (customerPhoneNumberId)

		private $addedEmailAddresses; // (customerEmailAddressId => ('email', 'description', 'dateTimeAdded'))
		private $updatedEmailAddresses; // (customerEmailAddressId => ('email', 'description', 'dateTimeAdded'))
		private $removedEmailAddresses; // (customerEmailAddressId)

		private $addedTags; // (customerTagId => ('tagName', 'dateTimeAdded'))
		private $updatedTags; // (customerTagId => ('tagName', 'dateTimeAdded'))
		private $removedTags; // (customerTagId)

		function __construct(string $customerId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Init arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();

			$this->addedLoginAttempts = array();
			$this->updatedLoginAttempts = array();
			$this->removedLoginAttempts = array();
			
			$this->addedSavedLogins = array();
			$this->updatedSavedLogins = array();
			$this->removedSavedLogins = array();

			$this->addedPhoneNumbers = array();
			$this->updatedPhoneNumbers = array();
			$this->removedPhoneNumbers = array();

			$this->addedEmailAddresses = array();
			$this->updatedEmailAddresses = array();
			$this->removedEmailAddresses = array();

			$this->addedTags = array();
			$this->updatedTags = array();
			$this->removedTags = array();

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
		// Linked data pull functions. Will get the data from the database and will reset any changes made to the public arrays before calling set().
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts () {
			$this->loginAttempts = array();
			// If there are entries for customerloginAttempt then push them to the array
			$fetch = $this->db->select('customerLoginAttempt', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
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
			$this->savedLogins = array();
			// If there are entries for customerSavedLogin then push them to the array
			$fetch = $this->db->select('customerSavedLogin', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
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

		public function pullPhoneNumbers() {
			$this->phoneNumbers = array();
			// If there are entries for customerPhoneNumber then push them to the array
			$fetch = $this->db->select('customerPhoneNumber', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
				foreach ($fetch as $row) {
					$this->phoneNumbers[$row['customerPhoneNumberId']] = array(
						'phonePrefix' => $row['phonePrefix'],
						'phone1' => $row['phone1'],
						'phone2' => $row['phone2'],
						'phone3' => $row['phone3'],
						'description' => $row['description'],
						'dateTimeAdded' => $row['dateTimeAdded'],
					);
				}
			}
			// Clear change arrays
			$this->addedPhoneNumbers = array();
			$this->updatedPhoneNumbers = array();
			$this->removedPhoneNumbers = array();
		}

		public function pullEmailAddresses() {
			$this->emailAddresses = array();
			// If there are entries for customerEmailAddress then push them to the array
			$fetch = $this->db->select('customerEmailAddress', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
				foreach ($fetch as $row) {
					$this->emailAddresses[$row['customerEmailAddressId']] = array(
						'email' => $row['email'],
						'description' => $row['description'],
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedEmailAddresses = array();
			$this->updatedEmailAddresses = array();
			$this->removedEmailAddresses = array();
		}

		public function pullTags() {
			$this->tags = array();
			// If there are entries for customerTag then push them to the array
			$fetch = $this->db->select('customerTag', '*', "WHERE customerId = '$this->staticCustomerId'");
			if ($fetch) {
				foreach ($fetch as $row) {
					$this->tags[$row['customerTagId']] = array(
						'tagName' => $row['tagName'],
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedTags = array();
			$this->updatedTags = array();
			$this->removedTags = array();
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
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$uuid->generatedId] = array(
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
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$customerLoginAttemptId] = array(
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
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$uuid->generatedId] = array(
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
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$customerSavedLoginId] = array(
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeSavedLogin($customerSavedLoginId) {
			array_push($this->removedSavedLogins, $customerSavedLoginId);
			unset($this->savedLogins[$customerSavedLoginId]);
		}

		// phoneNumber

		public function addPhoneNumber($phonePrefix, $phone1, $phone2, $phone3, $description, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'customerPhoneNumber', 'customerPhoneNumberId');
			
			$this->addedPhoneNumbers[$uuid->generatedId] = array(
				'phonePrefix' => $phonePrefix,
				'phone1' => $phone1,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->phoneNumbers[$uuid->generatedId] = array(
				'phonePrefix' => $phonePrefix,
				'phone1' => $phone1,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updatePhoneNumber($customerPhoneNumberId, $phonePrefix, $phone1, $phone2, $phone3, $description, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedPhoneNumbers[$customerPhoneNumberId] = array(
				'phonePrefix' => $phonePrefix,
				'phone1' => $phone1,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->phoneNumbers[$customerPhoneNumberId] = array(
				'phonePrefix' => $phonePrefix,
				'phone1' => $phone1,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removePhoneNumber($customerPhoneNumberId) {
			array_push($this->removedPhoneNumbers, $customerPhoneNumberId);
			unset($this->phoneNumbers[$customerPhoneNumberId]);
		}

		// emailAddress

		public function addEmailAddress($email, $description, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'customerEmailAddress', 'customerEmailAddressId');
			
			$this->addedEmailAddresses[$uuid->generatedId] = array(
				'email' => $email,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->emailAddresses[$uuid->generatedId] = array(
				'email' => $email,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateEmailAddress($customerEmailAddressId, $email, $description, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedEmailAddresses[$customerEmailAddressId] = array(
				'email' => $email,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->emailAddresses[$customerEmailAddressId] = array(
				'email' => $email,
				'description' => $description,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeEmailAddress($customerEmailAddressId) {
			array_push($this->removedEmailAddresses, $customerEmailAddressId);
			unset($this->emailAddresses[$customerEmailAddressId]);
		}

		// tag

		public function addTag($tagName, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'customerTag', 'customerTagId');
			
			$this->addedTags[$uuid->generatedId] = array(
				'tagName' => $tagName,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->Tags[$uuid->generatedId] = array(
				'tagName' => $tagName,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateTag($customerTagId, $tagName, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedTags[$customerTagId] = array(
				'tagName' => $tagName,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->tags[$customerTagId] = array(
				'tagName' => $tagName,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeTag($customerTagId) {
			array_push($this->removedTags, $customerTagId);
			unset($this->tags[$customerTagId]);
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

			// Linked tables --------------------------------------------------------------------------

			// loginAttempt adds
			foreach ($this->addedLoginAttempts as $id => $attributes) {
				if (!$this->db->insert('customerLoginAttempt', array(
					'customerLoginAttemptId' => $this->db->sanitize($id),
					'businessId' => $this->db->sanitize($this->staticBusinessId),
					'customerId' => $this->db->sanitize($this->staticCustomerId),
					'clientIp' => $this->db->sanitize($attributes['clientIp']),
					'enteredUsername' => $this->db->sanitize($attributes['enteredUsername']),
					'result' => $this->db->sanitize($attributes['result']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt updates
			foreach ($this->updatedLoginAttempts as $id => $attributes) {
				if (!$this->db->update('customerLoginAttempt', array(
					'clientIp' => $this->db->sanitize($attributes['clientIp']),
					'enteredUsername' => $this->db->sanitize($attributes['enteredUsername']),
					'result' => $this->db->sanitize($attributes['result']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE customerLoginAttemptId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt removes
			foreach ($this->removedLoginAttempts as $id) {
				if (!$this->db->delete('customerLoginAttempt', "WHERE customerLoginAttemptId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// -----------------------------------------------------------------------------------------

			// savedLogin adds
			foreach ($this->addedSavedLogins as $id => $attributes) {
				if (!$this->db->insert('customerSavedLogin', array(
					'customerSavedLoginId' => $this->db->sanitize($id),
					'businessId' => $this->db->sanitize($this->staticBusinessId),
					'customerId' => $this->db->sanitize($this->staticCustomerId),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// savedLogin updates
			foreach ($this->updatedSavedLogins as $id => $attributes) {
				if (!$this->db->update('customerSavedLogin', array(
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE customerSavedLoginId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// savedLogin removes
			foreach ($this->removedSavedLogins as $id) {
				if (!$this->db->delete('customerSavedLogin', "WHERE customerSavedLoginId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// -----------------------------------------------------------------------------------------

			// phoneNumber adds

			foreach ($this->addedPhoneNumbers as $id => $attributes) {
				if (!$this->db->insert('customerPhoneNumber', array(
					'customerPhoneNumberId' => $this->db->sanitize($id),
					'businessId' => $this->db->sanitize($this->staticBusinessId),
					'customerId' => $this->db->sanitize($this->staticCustomerId),
					'phonePrefix' => $this->db->sanitize($attributes['phonePrefix']),
					'phone1' => $this->db->sanitize($attributes['phone1']),
					'phone2' => $this->db->sanitize($attributes['phone2']),
					'phone3' => $this->db->sanitize($attributes['phone3']),
					'description' => $this->db->sanitize($attributes['description']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// phoneNumber updates
			foreach ($this->updatedPhoneNumbers as $id => $attributes) {
				if (!$this->db->update('customerPhoneNumber', array(
					'phonePrefix' => $this->db->sanitize($attributes['phonePrefix']),
					'phone1' => $this->db->sanitize($attributes['phone1']),
					'phone2' => $this->db->sanitize($attributes['phone2']),
					'phone3' => $this->db->sanitize($attributes['phone3']),
					'description' => $this->db->sanitize($attributes['description']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE customerPhoneNumberId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// phoneNumber removes
			foreach ($this->removedPhoneNumbers as $id) {
				if (!$this->db->delete('customerPhoneNumber', "WHERE customerPhoneNumberId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// -----------------------------------------------------------------------------------------

			// emailAddress adds

			foreach ($this->addedEmailAddresses as $id => $attributes) {
				if (!$this->db->insert('customerEmailAddress', array(
					'customerEmailAddressId' => $this->db->sanitize($id),
					'businessId' => $this->db->sanitize($this->staticBusinessId),
					'customerId' => $this->db->sanitize($this->staticCustomerId),
					'email' => $this->db->sanitize($attributes['email']),
					'description' => $this->db->sanitize($attributes['description']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// emailAddress updates
			foreach ($this->updatedEmailAddresses as $id => $attributes) {
				if (!$this->db->update('customerEmailAddress', array(
					'email' => $this->db->sanitize($attributes['email']),
					'description' => $this->db->sanitize($attributes['description']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE customerEmailAddressId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// emailAddress removes
			foreach ($this->removedEmailAddresses as $id) {
				if (!$this->db->delete('customerEmailAddress', "WHERE customerEmailAddressId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}
		}
	}

?>
