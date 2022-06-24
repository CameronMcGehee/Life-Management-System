<?php

	class staffLoginAttempt {

		private string $setType;
		private database $db;

		private string $dbCustomerLoginAttemptId; // Used when updating the table incase the staffLoginAttemptId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $staffLoginAttemptId;
		public $businessId;
		public $staffId;
		public $clientIp;
		public $result;
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
			$this->clientIp = '';
			$this->result = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $staffLoginAttemptId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('staffLoginAttempt', '*', "WHERE staffLoginAttemptId ='".$this->db->sanitize($staffLoginAttemptId)."'");

			// If staffLoginAttemptId already exists then set the set method type to UPDATE and fetch the values for the staffLoginAttempt
			if ($fetch) {
				$this->staffLoginAttemptId = $staffLoginAttemptId;
				$this->businessId = $fetch[0]['businessId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->clientIp = $fetch[0]['clientIp'];
				$this->result = $fetch[0]['result'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If staffLoginAttemptId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new staffLoginAttemptId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('staffLoginAttempt', 'staffLoginAttemptId');
				$this->staffLoginAttemptId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerLoginAttemptId = $this->staffLoginAttemptId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'staffLoginAttemptId' => $this->db->sanitize($this->dbCustomerLoginAttemptId),
				'businessId' => $this->db->sanitize($this->businessId),
				'staffId' => $this->db->sanitize($this->staffId),
				'clientIp' => $this->db->sanitize($this->clientIp),
				'result' => $this->db->sanitize($this->result),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('staffLoginAttempt', $attributes, "WHERE staffLoginAttemptId = ".$this->db->sanitize($this->dbCustomerLoginAttemptId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('staffLoginAttempt', $attributes)) {
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
			if (!$this->db->delete('staffLoginAttempt', "WHERE staffLoginAttemptId = '".$this->db->sanitize($this->dbCustomerLoginAttemptId)."'", 1)) {
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
