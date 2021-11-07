<?php

	class staffEmailAddress {

		private string $setType;
		private database $db;

		private string $dbCustomerEmailAddressId; // Used when updating the table incase the staffEmailAddressId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $staffEmailAddressId;
		public $businessId;
		public $staffId;
		public $email;
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

		function __construct(string $staffEmailAddressId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('staffEmailAddress', '*', "WHERE staffEmailAddressId ='".$this->db->sanitize($staffEmailAddressId)."'");

			// If staffEmailAddressId already exists then set the set method type to UPDATE and fetch the values for the staffEmailAddress
			if ($fetch) {
				$this->staffEmailAddressId = $staffEmailAddressId;
				$this->businessId = $fetch[0]['businessId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->email = $fetch[0]['email'];
				$this->description = $fetch[0]['description'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If staffEmailAddressId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new staffEmailAddressId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('staffEmailAddress', 'staffEmailAddressId');
				$this->staffEmailAddressId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerEmailAddressId = $this->staffEmailAddressId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'staffEmailAddressId' => $this->db->sanitize($this->dbCustomerEmailAddressId),
				'businessId' => $this->db->sanitize($this->businessId),
				'staffId' => $this->db->sanitize($this->staffId),
				'email' => $this->db->sanitize($this->email),
				'description' => $this->db->sanitize($this->description),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('staffEmailAddress', $attributes, "WHERE staffEmailAddressId = ".$this->db->sanitize($this->dbCustomerEmailAddressId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('staffEmailAddress', $attributes)) {
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
			if (!$this->db->delete('staffEmailAddress', "WHERE staffEmailAddressId = '".$this->db->sanitize($this->dbCustomerEmailAddressId)."'", 1)) {
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
