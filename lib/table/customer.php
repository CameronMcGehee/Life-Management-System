<?php

	class customer {

		private string $setType;
		private database $db;

		private string $dbCustomerId; // Used when updating the table incase the customerId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('firstName', 'lastName', 'billAddress1', 'billAddress2', 'billCity', 'billState', 'billZipCode', 'notes');

		// Main database attributes
		public $customerId;
		public $businessId;
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
		public $customerServiceTickets = array();
		public $estimates = array();
		public $invoices = array();
		public $payments = array();
		public $properties = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
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

			// Clear arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();
			$this->phoneNumbers = array();
			$this->emailAddresses = array();
			$this->tags = array();
			$this->customerServiceTickets = array();
			$this->estimates = array();
			$this->invoices = array();
			$this->payments = array();
			$this->properties = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customer', '*', "WHERE customerId ='".$this->db->sanitize($customerId)."'");

			// If customerId already exists then set the set method type to UPDATE and fetch the values for the customer
			if ($fetch) {
				$this->customerId = $customerId;
				$this->businessId = $fetch[0]['businessId'];
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

			// If customerId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customer', 'customerId');
				$this->customerId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerId = $this->customerId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// loginAttempts
		public function pullLoginAttempts($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerLoginAttempt', 'customerLoginAttemptId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// savedLogins
		public function pullSavedLogins($params = '') {
			$this->savedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerSavedLogin', 'customerSavedLoginId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// phoneNumbers
		public function pullPhoneNumbers($params = '') {
			$this->phoneNumbers = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerPhoneNumber', 'customerPhoneNumberId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// emailAddresses
		public function pullEmailAddresses($params = '') {
			$this->emailAddresses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerEmailAddress', 'customerEmailAddressId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// tags
		public function pullTags($params = '') {
			$this->tags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerTag', 'customerTagId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// customerServiceTickets
		public function pullCustomerServiceTickets($params = '') {
			$this->customerServiceTickets = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('customerServiceTicket', 'customerServiceTicketId', "WHERE customerId = '$this->dbCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerServiceTicketId, $row['customerServiceTicketId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// estimates
		public function pullEstimates($params = '') {
			$this->estimates = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimate', 'estimateId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// invoices
		public function pullInvoices($params = '') {
			$this->invoices = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoice', 'invoiceId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// payments
		public function pullPayments($params = '') {
			$this->payments = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payments', 'paymentsId', "WHERE customerId = '$this->dbCustomerId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payments, $row['paymentsId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// properties
		public function pullProperties($params = '') {
			$this->properties = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('property', 'propertyId', "WHERE customerId = '$this->dbCustomerId'".$params);
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

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerId' => $this->db->sanitize($this->dbCustomerId),
				'businessId' => $this->db->sanitize($this->businessId),
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

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if (gettype($attributes[$field]) == 'string' && $attributes[$field] != 'NULL') {
					$attributes[$field] = encryptString((string)$attributes[$field], $this->cryptoKey);
				}
			}

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('customer', $attributes, "WHERE customerId = '".$this->db->sanitize($this->dbCustomerId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
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

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('customer', "WHERE customerId = '".$this->db->sanitize($this->dbCustomerId)."'", 1)) {
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
