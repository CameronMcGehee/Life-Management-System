<?php

	class adminLoginAttempt {

		private string $setType;
		private databaseManager $db;

		public string $dbAdminLoginAttemptId; // Used when updating the table incase the adminLoginAttemptId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $adminLoginAttemptId;
		public $adminId;
		public $loginCode;
		public $dateTimeCodeUsed;
		public $clientIp;
		public $enteredUsername;
		public $enteredPassword;
		public $result;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminLoginAttemptId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminLoginAttemptId ='$adminLoginAttemptId'");

			// If adminLoginAttemptId already exists then set the set method type to UPDATE and fetch the values for the adminLoginAttempt
			if ($fetch) {
				$this->adminLoginAttemptId = $adminLoginAttemptId;
				$this->adminId = (string)$fetch[0]['adminId'];
				$this->loginCode = (string)$fetch[0]['loginCode'];
				$this->dateTimeCodeUsed = (string)$fetch[0]['dateTimeCodeUsed'];
				$this->clientIp = (string)$fetch[0]['clientIp'];
				$this->enteredUsername = (string)$fetch[0]['enteredUsername'];
				$this->enteredPassword = (string)$fetch[0]['enteredPassword'];
				$this->result = (string)$fetch[0]['result'];
				$this->dateTimeAdded = (string)$fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If adminLoginAttemptId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new adminLoginAttemptId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('adminLoginAttempt', 'adminLoginAttemptId');
				$this->adminLoginAttemptId = $uuid->generatedId;

				$this->adminId = NULL;
				$this->loginCode = '';
				$this->dateTimeCodeUsed = NULL;
				$this->clientIp = '';
				$this->enteredUsername = '';
				$this->enteredPassword = '';
				$this->result = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbAdminLoginAttemptId = $this->adminLoginAttemptId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'adminLoginAttemptId' => $this->db->sanitize($this->adminLoginAttemptId),
				'adminId' => $this->db->sanitize($this->adminId),
				'loginCode' => $this->db->sanitize($this->loginCode),
				'dateTimeCodeUsed' => $this->db->sanitize($this->dateTimeCodeUsed),
				'clientIp' => $this->db->sanitize($this->clientIp),
				'enteredUsername' => $this->db->sanitize($this->enteredUsername),
				'enteredPassword' => $this->db->sanitize($this->enteredPassword),
				'result' => $this->db->sanitize($this->result),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('adminLoginAttempt', $attributes, "WHERE adminLoginAttemptId = ".$this->db->sanitize($this->dbAdminLoginAttemptId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('adminLoginAttempt', $attributes)) {
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
			if (!$this->db->delete('adminLoginAttempt', "WHERE adminLoginAttemptId = '".$this->db->sanitize($this->dbAdminLoginAttemptId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('adminLoginAttempt', 'adminLoginAttemptId');
			$this->adminLoginAttemptId = $uuid->generatedId;

			// Reset all variables
			$this->adminId = NULL;
			$this->loginCode = '';
			$this->dateTimeCodeUsed = NULL;
			$this->clientIp = '';
			$this->enteredUsername = '';
			$this->enteredPassword = '';
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
