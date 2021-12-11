<?php

	class customerPhoneNumber {

		private string $setType;
		private database $db;

		private string $dbCustomerPhoneNumberId; // Used when updating the table incase the customerPhoneNumberId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('phonePrefix', 'phone1', 'phone2', 'phone3', 'description');

		// Main database attributes
		public $customerPhoneNumberId;
		public $businessId;
		public $customerId;
		public $phonePrefix;
		public $phone1;
		public $phone2;
		public $phone3;
		public $description;
		public $dateTimeAdded;

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
			$this->customerId = '';
			$this->phonePrefix = NULL;
			$this->phone1 = '';
			$this->phone2 = '';
			$this->phone3 = '';
			$this->description = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerPhoneNumberId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customerPhoneNumber', '*', "WHERE customerPhoneNumberId ='".$this->db->sanitize($customerPhoneNumberId)."'");

			// If customerPhoneNumberId already exists then set the set method type to UPDATE and fetch the values for the customerPhoneNumber
			if ($fetch) {
				$this->customerPhoneNumberId = $customerPhoneNumberId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->phonePrefix = $fetch[0]['phonePrefix'];
				$this->phone1 = $fetch[0]['phone1'];
				$this->phone2 = $fetch[0]['phone2'];
				$this->phone3 = $fetch[0]['phone3'];
				$this->description = $fetch[0]['description'];
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

			// If customerPhoneNumberId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerPhoneNumberId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerPhoneNumber', 'customerPhoneNumberId');
				$this->customerPhoneNumberId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerPhoneNumberId = $this->customerPhoneNumberId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attr = array(
				'customerPhoneNumberId' => $this->dbCustomerPhoneNumberId,
				'businessId' => $this->businessId,
				'customerId' => $this->customerId,
				'phonePrefix' => $this->phonePrefix,
				'phone1' => $this->phone1,
				'phone2' => $this->phone2,
				'phone3' => $this->phone3,
				'description' => $this->description,
				'dateTimeAdded' => $this->dateTimeAdded
			);

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if ($attr[$field] != NULL) {
					$attr[$field] = encryptString((string)$attr[$field], $this->cryptoKey);
				}
			}

			$attributes = array(
				'customerPhoneNumberId' => $this->db->sanitize($this->dbCustomerPhoneNumberId),
				'businessId' => $this->db->sanitize($attr['businessId']),
				'customerId' => $this->db->sanitize($attr['customerId']),
				'phonePrefix' => $this->db->sanitize($attr['phonePrefix']),
				'phone1' => $this->db->sanitize($attr['phone1']),
				'phone2' => $this->db->sanitize($attr['phone2']),
				'phone3' => $this->db->sanitize($attr['phone3']),
				'description' => $this->db->sanitize($attr['description']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerPhoneNumber', $attributes, "WHERE customerPhoneNumberId = '".$this->db->sanitize($this->dbCustomerPhoneNumberId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerPhoneNumber', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('customerPhoneNumber', "WHERE customerPhoneNumberId = '".$this->db->sanitize($this->dbCustomerPhoneNumberId)."'", 1)) {
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
