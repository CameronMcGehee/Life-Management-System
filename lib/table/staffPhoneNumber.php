<?php

	class staffPhoneNumber {

		private string $setType;
		private database $db;

		private string $dbCustomerPhoneNumberId; // Used when updating the table incase the staffPhoneNumberId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $staffPhoneNumberId;
		public $businessId;
		public $staffId;
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
			$this->staffId = '';
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

		function __construct(string $staffPhoneNumberId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('staffPhoneNumber', '*', "WHERE staffPhoneNumberId ='".$this->db->sanitize($staffPhoneNumberId)."'");

			// If staffPhoneNumberId already exists then set the set method type to UPDATE and fetch the values for the staffPhoneNumber
			if ($fetch) {
				$this->staffPhoneNumberId = $staffPhoneNumberId;
				$this->businessId = $fetch[0]['businessId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->phonePrefix = $fetch[0]['phonePrefix'];
				$this->phone1 = $fetch[0]['phone1'];
				$this->phone2 = $fetch[0]['phone2'];
				$this->phone3 = $fetch[0]['phone3'];
				$this->description = $fetch[0]['description'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If staffPhoneNumberId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new staffPhoneNumberId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('staffPhoneNumber', 'staffPhoneNumberId');
				$this->staffPhoneNumberId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerPhoneNumberId = $this->staffPhoneNumberId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'staffPhoneNumberId' => $this->db->sanitize($this->dbCustomerPhoneNumberId),
				'businessId' => $this->db->sanitize($this->businessId),
				'staffId' => $this->db->sanitize($this->staffId),
				'phonePrefix' => $this->db->sanitize($this->phonePrefix),
				'phone1' => $this->db->sanitize($this->phone1),
				'phone2' => $this->db->sanitize($this->phone2),
				'phone3' => $this->db->sanitize($this->phone3),
				'description' => $this->db->sanitize($this->description),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('staffPhoneNumber', $attributes, "WHERE staffPhoneNumberId = ".$this->db->sanitize($this->dbCustomerPhoneNumberId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('staffPhoneNumber', $attributes)) {
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
			if (!$this->db->delete('staffPhoneNumber', "WHERE staffPhoneNumberId = '".$this->db->sanitize($this->dbCustomerPhoneNumberId)."'", 1)) {
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
