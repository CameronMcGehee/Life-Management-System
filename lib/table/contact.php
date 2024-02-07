<?php

	class contact {

		private string $setType;
		private database $db;

		private string $dbContactId; // Used when updating the table incase the contactId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('billAddress1', 'billAddress2', 'billCity', 'billState', 'billZipCode', 'notes');

		// Main database attributes
		public $contactId;
		public $workspaceId;
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
		public $contactServiceTickets = array();
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
			// Default workspaceId to the currently selected workspace
			if (isset($_SESSION['lifems_workspaceId'])) {
				$this->workspaceId = $_SESSION['lifems_workspaceId'];
			} else {
				$this->workspaceId = '';
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
			$this->allowCZSignIn = '1';
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
			$this->contactServiceTickets = array();
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

		function __construct(string $contactId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('contact', '*', "WHERE contactId ='".$this->db->sanitize($contactId)."'");

			// If contactId already exists then set the set method type to UPDATE and fetch the values for the contact
			if ($fetch) {
				$this->contactId = $contactId;
				$this->workspaceId = $fetch[0]['workspaceId'];
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

			// If contactId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new contactId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('contact', 'contactId');
				$this->contactId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbContactId = $this->contactId;
			
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
			$fetch = $this->db->select('contactLoginAttempt', 'contactLoginAttemptId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['contactLoginAttemptId']);
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
			$fetch = $this->db->select('contactSavedLogin', 'contactSavedLoginId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['contactSavedLoginId']);
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
			$fetch = $this->db->select('contactPhoneNumber', 'contactPhoneNumberId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->phoneNumbers, $row['contactPhoneNumberId']);
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
			$fetch = $this->db->select('contactEmailAddress', 'contactEmailAddressId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->emailAddresses, $row['contactEmailAddressId']);
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
			$fetch = $this->db->select('contactContactTagBridge', 'contactTagId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->tags, $row['contactTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactServiceTickets
		public function pullContactServiceTickets($params = '') {
			$this->contactServiceTickets = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactServiceTicket', 'contactServiceTicketId', "WHERE contactId = '$this->dbContactId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactServiceTicketId, $row['contactServiceTicketId']);
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
			$fetch = $this->db->select('estimate', 'estimateId', "WHERE contactId = '$this->dbContactId'".$params);
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
			$fetch = $this->db->select('invoice', 'invoiceId', "WHERE contactId = '$this->dbContactId'".$params);
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
			$fetch = $this->db->select('payments', 'paymentsId', "WHERE contactId = '$this->dbContactId'".$params);
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
			$fetch = $this->db->select('property', 'propertyId', "WHERE contactId = '$this->dbContactId'".$params);
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

			$attr = array(
				'contactId' => $this->dbContactId,
				'workspaceId' => $this->workspaceId,
				'firstName' => $this->firstName,
				'nameIndex' => substr($this->firstName, 0, 3),
				'lastName' => $this->lastName,
				'billAddress1' => $this->billAddress1,
				'billAddress2' => $this->billAddress2,
				'billCity' => $this->billCity,
				'billState' => $this->billState,
				'billZipCode' => $this->billZipCode,
				'creditCache' => $this->creditCache,
				'overrideCreditAlertIsEnabled' => $this->overrideCreditAlertIsEnabled,
				'overrideCreditAlertAmount' => $this->overrideCreditAlertAmount,
				'overrideAutoApplyCredit' => $this->overrideAutoApplyCredit,
				'balanceCache' => $this->balanceCache,
				'overrideBalanceAlertIsEnabled' => $this->overrideBalanceAlertIsEnabled,
				'overrideBalanceAlertAmount' => $this->overrideBalanceAlertAmount,
				'allowCZSignIn' => $this->allowCZSignIn,
				'password' => $this->password,
				'discountPercent' => $this->discountPercent,
				'overridePaymentTerm' => $this->overridePaymentTerm,
				'notes' => $this->notes,
				'dateTimeAdded' => $this->dateTimeAdded
			);

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if ($attr[$field] != NULL) {
					$attr[$field] = encryptString((string)$attr[$field], $this->cryptoKey);
				}
			}

			$attributes = array(
				'contactId' => $this->db->sanitize($this->dbContactId),
				'workspaceId' => $this->db->sanitize($attr['workspaceId']),
				'firstName' => $this->db->sanitize($attr['firstName']),
				'nameIndex' => $this->db->sanitize($attr['nameIndex']),
				'lastName' => $this->db->sanitize($attr['lastName']),
				'billAddress1' => $this->db->sanitize($attr['billAddress1']),
				'billAddress2' => $this->db->sanitize($attr['billAddress2']),
				'billCity' => $this->db->sanitize($attr['billCity']),
				'billState' => $this->db->sanitize($attr['billState']),
				'billZipCode' => $this->db->sanitize($attr['billZipCode']),
				'creditCache' => $this->db->sanitize($attr['creditCache']),
				'overrideCreditAlertIsEnabled' => $this->db->sanitize($attr['overrideCreditAlertIsEnabled']),
				'overrideCreditAlertAmount' => $this->db->sanitize($attr['overrideCreditAlertAmount']),
				'overrideAutoApplyCredit' => $this->db->sanitize($attr['overrideAutoApplyCredit']),
				'balanceCache' => $this->db->sanitize($attr['balanceCache']),
				'overrideBalanceAlertIsEnabled' => $this->db->sanitize($attr['overrideBalanceAlertIsEnabled']),
				'overrideBalanceAlertAmount' => $this->db->sanitize($attr['overrideBalanceAlertAmount']),
				'allowCZSignIn' => $this->db->sanitize($attr['allowCZSignIn']),
				'password' => $this->db->sanitize($attr['password']),
				'discountPercent' => $this->db->sanitize($attr['discountPercent']),
				'overridePaymentTerm' => $this->db->sanitize($attr['overridePaymentTerm']),
				'notes' => $this->db->sanitize($attr['notes']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('contact', $attributes, "WHERE contactId = '".$this->db->sanitize($this->dbContactId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('contact', $attributes)) {
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
			if (!$this->db->delete('contact', "WHERE contactId = '".$this->db->sanitize($this->dbContactId)."'", 1)) {
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
