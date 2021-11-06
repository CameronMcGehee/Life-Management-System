<?php

	class authToken {

		private string $setType;
		private database $db;

		private string $dbAuthTokenId; // Used when updating the table incase the authTokenId has been changed after instantiation

		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $authTokenId;
		public $businessId;
		public $authName;
		public $dateTimeUsed;
		public $clientIpUsed;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $authTokenId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('authToken', '*', "WHERE authTokenId ='".$this->db->sanitize($authTokenId)."'");

			// If authTokenId already exists then set the set method type to UPDATE and fetch the values for the authToken
			if ($fetch) {
				$this->authTokenId = $authTokenId;
				$this->businessId = $fetch[0]['businessId'];
				$this->authName = $fetch[0]['authName'];
				$this->dateTimeUsed = $fetch[0]['dateTimeUsed'];
				$this->clientIpUsed = $fetch[0]['clientIpUsed'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If authTokenId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new authTokenId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('authToken', 'authTokenId');
				$this->authTokenId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->authName = NULL;
				$this->dateTimeUsed = NULL;
				$this->clientIpUsed = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbAuthTokenId = $this->authTokenId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'authTokenId' => $this->db->sanitize($this->dbAuthTokenId),
				'businessId' => $this->db->sanitize($this->businessId),
				'authName' => $this->db->sanitize($this->authName),
				'dateTimeUsed' => $this->db->sanitize($this->dateTimeUsed),
				'clientIpUsed' => $this->db->sanitize($this->clientIpUsed),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('authToken', $attributes, "WHERE authTokenId = '".$this->db->sanitize($this->dbAuthTokenId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('authToken', $attributes)) {
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
			if (!$this->db->delete('authToken', "WHERE authTokenId = '".$this->db->sanitize($this->dbAuthTokenId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('authToken', 'authTokenId');
			$this->authTokenId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->authName = NULL;
			$this->dateTimeUsed = NULL;
			$this->clientIpUsed = '';
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
