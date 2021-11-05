<?php

	class customerSavedLogin {

		private string $setType;
		private database $db;

		private string $dbCustomerSavedLoginId; // Used when updating the table incase the customerSavedLoginId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerSavedLoginId;
		public $businessId;
		public $customerId;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerSavedLoginId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customerSavedLogin', '*', "WHERE customerSavedLoginId ='".$this->db->sanitize($customerSavedLoginId)."'");

			// If customerSavedLoginId already exists then set the set method type to UPDATE and fetch the values for the customerSavedLogin
			if ($fetch) {
				$this->customerSavedLoginId = $customerSavedLoginId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerSavedLoginId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerSavedLoginId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerSavedLogin', 'customerSavedLoginId');
				$this->customerSavedLoginId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->customerId = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbCustomerSavedLoginId = $this->customerSavedLoginId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerSavedLoginId' => $this->db->sanitize($this->dbCustomerSavedLoginId),
				'businessId' => $this->db->sanitize($this->businessId),
				'customerId' => $this->db->sanitize($this->customerId),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerSavedLogin', $attributes, "WHERE customerSavedLoginId = ".$this->db->sanitize($this->dbCustomerSavedLoginId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerSavedLogin', $attributes)) {
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
			if (!$this->db->delete('customerSavedLogin', "WHERE customerSavedLoginId = '".$this->db->sanitize($this->dbCustomerSavedLoginId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('customerSavedLogin', 'customerSavedLoginId');
			$this->customerSavedLoginId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->customerId = '';
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
