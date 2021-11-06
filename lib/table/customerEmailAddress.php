<?php

	class customerEmailAddress {

		private string $setType;
		private database $db;

		private string $dbCustomerEmailAddressId; // Used when updating the table incase the customerEmailAddressId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerEmailAddressId;
		public $businessId;
		public $customerId;
		public $email;
		public $description;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerEmailAddressId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customerEmailAddress', '*', "WHERE customerEmailAddressId ='".$this->db->sanitize($customerEmailAddressId)."'");

			// If customerEmailAddressId already exists then set the set method type to UPDATE and fetch the values for the customerEmailAddress
			if ($fetch) {
				$this->customerEmailAddressId = $customerEmailAddressId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->email = $fetch[0]['email'];
				$this->description = $fetch[0]['description'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerEmailAddressId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerEmailAddressId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerEmailAddress', 'customerEmailAddressId');
				$this->customerEmailAddressId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->customerId = '';
				$this->email = '';
				$this->description = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerEmailAddressId = $this->customerEmailAddressId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerEmailAddressId' => $this->db->sanitize($this->dbCustomerEmailAddressId),
				'businessId' => $this->db->sanitize($this->businessId),
				'customerId' => $this->db->sanitize($this->customerId),
				'email' => $this->db->sanitize($this->email),
				'description' => $this->db->sanitize($this->description),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerEmailAddress', $attributes, "WHERE customerEmailAddressId = ".$this->db->sanitize($this->dbCustomerEmailAddressId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerEmailAddress', $attributes)) {
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
			if (!$this->db->delete('customerEmailAddress', "WHERE customerEmailAddressId = '".$this->db->sanitize($this->dbCustomerEmailAddressId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('customerEmailAddress', 'customerEmailAddressId');
			$this->customerEmailAddressId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->customerId = '';
			$this->email = '';
			$this->description = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			// (No arrays)

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
