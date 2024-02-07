<?php

	class contactPhoneNumber {

		private string $setType;
		private database $db;

		private string $dbContactPhoneNumberId; // Used when updating the table incase the contactPhoneNumberId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('phonePrefix', 'phone1', 'phone2', 'phone3', 'description');

		// Main database attributes
		public $contactPhoneNumberId;
		public $workspaceId;
		public $contactId;
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
			// Default workspaceId to the currently selected workspace
			if (isset($_SESSION['lifems_workspaceId'])) {
				$this->workspaceId = $_SESSION['lifems_workspaceId'];
			} else {
				$this->workspaceId = '';
			}
			$this->contactId = '';
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

		function __construct(string $contactPhoneNumberId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('contactPhoneNumber', '*', "WHERE contactPhoneNumberId ='".$this->db->sanitize($contactPhoneNumberId)."'");

			// If contactPhoneNumberId already exists then set the set method type to UPDATE and fetch the values for the contactPhoneNumber
			if ($fetch) {
				$this->contactPhoneNumberId = $contactPhoneNumberId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->contactId = $fetch[0]['contactId'];
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

			// If contactPhoneNumberId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new contactPhoneNumberId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('contactPhoneNumber', 'contactPhoneNumberId');
				$this->contactPhoneNumberId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbContactPhoneNumberId = $this->contactPhoneNumberId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attr = array(
				'contactPhoneNumberId' => $this->dbContactPhoneNumberId,
				'workspaceId' => $this->workspaceId,
				'contactId' => $this->contactId,
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
				'contactPhoneNumberId' => $this->db->sanitize($this->dbContactPhoneNumberId),
				'workspaceId' => $this->db->sanitize($attr['workspaceId']),
				'contactId' => $this->db->sanitize($attr['contactId']),
				'phonePrefix' => $this->db->sanitize($attr['phonePrefix']),
				'phone1' => $this->db->sanitize($attr['phone1']),
				'phone2' => $this->db->sanitize($attr['phone2']),
				'phone3' => $this->db->sanitize($attr['phone3']),
				'description' => $this->db->sanitize($attr['description']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('contactPhoneNumber', $attributes, "WHERE contactPhoneNumberId = '".$this->db->sanitize($this->dbContactPhoneNumberId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('contactPhoneNumber', $attributes)) {
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
			if (!$this->db->delete('contactPhoneNumber', "WHERE contactPhoneNumberId = '".$this->db->sanitize($this->dbContactPhoneNumberId)."'", 1)) {
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
