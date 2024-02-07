<?php

	class contactEmailAddress {

		private string $setType;
		private database $db;

		private string $dbContactEmailAddressId; // Used when updating the table incase the contactEmailAddressId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('email', 'description');

		// Main database attributes
		public $contactEmailAddressId;
		public $workspaceId;
		public $contactId;
		public $email;
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
			$this->email = '';
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

		function __construct(string $contactEmailAddressId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('contactEmailAddress', '*', "WHERE contactEmailAddressId ='".$this->db->sanitize($contactEmailAddressId)."'");

			// If contactEmailAddressId already exists then set the set method type to UPDATE and fetch the values for the contactEmailAddress
			if ($fetch) {
				$this->contactEmailAddressId = $contactEmailAddressId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->contactId = $fetch[0]['contactId'];
				$this->email = $fetch[0]['email'];
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

			// If contactEmailAddressId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new contactEmailAddressId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('contactEmailAddress', 'contactEmailAddressId');
				$this->contactEmailAddressId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbContactEmailAddressId = $this->contactEmailAddressId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attr = array(
				'contactEmailAddressId' => $this->dbContactEmailAddressId,
				'workspaceId' => $this->workspaceId,
				'contactId' => $this->contactId,
				'email' => $this->email,
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
				'contactEmailAddressId' => $this->db->sanitize($this->dbContactEmailAddressId),
				'workspaceId' => $this->db->sanitize($attr['workspaceId']),
				'contactId' => $this->db->sanitize($attr['contactId']),
				'email' => $this->db->sanitize($attr['email']),
				'description' => $this->db->sanitize($attr['description']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('contactEmailAddress', $attributes, "WHERE contactEmailAddressId = '".$this->db->sanitize($this->dbContactEmailAddressId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('contactEmailAddress', $attributes)) {
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
			if (!$this->db->delete('contactEmailAddress', "WHERE contactEmailAddressId = '".$this->db->sanitize($this->dbContactEmailAddressId)."'", 1)) {
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
