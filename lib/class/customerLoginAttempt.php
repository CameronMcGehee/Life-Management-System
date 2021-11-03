<?php

	class customerLoginAttempt {

		private string $setType;
		private databaseManager $db;

		public string $dbCustomerLoginAttemptId; // Used when updating the table incase the customerLoginAttemptId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerLoginAttemptId;
		public $businessId;
		public $customerId;
		public $clientIp;
		public $result;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerLoginAttemptId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('customerLoginAttempt', '*', "WHERE customerLoginAttemptId ='".$this->db->sanitize($customerLoginAttemptId)."'");

			// If customerLoginAttemptId already exists then set the set method type to UPDATE and fetch the values for the customerLoginAttempt
			if ($fetch) {
				$this->customerLoginAttemptId = $customerLoginAttemptId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->clientIp = $fetch[0]['clientIp'];
				$this->result = $fetch[0]['result'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerLoginAttemptId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerLoginAttemptId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerLoginAttempt', 'customerLoginAttemptId');
				$this->customerLoginAttemptId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->customerId = '';
				$this->clientIp = '';
				$this->result = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbCustomerLoginAttemptId = $this->customerLoginAttemptId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerLoginAttemptId' => $this->db->sanitize($this->dbCustomerLoginAttemptId),
				'businessId' => $this->db->sanitize($this->businessId),
				'customerId' => $this->db->sanitize($this->customerId),
				'clientIp' => $this->db->sanitize($this->clientIp),
				'result' => $this->db->sanitize($this->result),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerLoginAttempt', $attributes, "WHERE customerLoginAttemptId = ".$this->db->sanitize($this->dbCustomerLoginAttemptId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerLoginAttempt', $attributes)) {
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
			if (!$this->db->delete('customerLoginAttempt', "WHERE customerLoginAttemptId = '".$this->db->sanitize($this->dbCustomerLoginAttemptId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('customerLoginAttempt', 'customerLoginAttemptId');
			$this->customerLoginAttemptId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->customerId = NULL;
			$this->clientIp = '';
			$this->result = '';
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
