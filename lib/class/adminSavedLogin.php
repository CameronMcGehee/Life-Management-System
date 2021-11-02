<?php

	class adminSavedLogin {

		private string $setType;
		private databaseManager $db;

		public string $dbAdminSavedLoginId; // Used when updating the table incase the adminSavedLoginId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $adminSavedLoginId;
		public $adminId;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminSavedLoginId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminSavedLoginId ='".$this->db->sanitize($adminSavedLoginId)."'");

			// If adminSavedLoginId already exists then set the set method type to UPDATE and fetch the values for the adminSavedLogin
			if ($fetch) {
				$this->adminSavedLoginId = $adminSavedLoginId;
				$this->adminId = $fetch[0]['adminId'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If adminSavedLoginId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new adminSavedLoginId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('adminSavedLogin', 'adminSavedLoginId');
				$this->adminSavedLoginId = $uuid->generatedId;

				$this->adminId = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbAdminSavedLoginId = $this->adminSavedLoginId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'adminSavedLoginId' => $this->db->sanitize($this->dbAdminSavedLoginId),
				'adminId' => $this->db->sanitize($this->adminId),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('adminSavedLogin', $attributes, "WHERE adminSavedLoginId = ".$this->db->sanitize($this->dbAdminSavedLoginId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('adminSavedLogin', $attributes)) {
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
			if (!$this->db->delete('adminSavedLogin', "WHERE adminSavedLoginId = '".$this->db->sanitize($this->dbAdminSavedLoginId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('adminSavedLogin', 'adminSavedLoginId');
			$this->adminSavedLoginId = $uuid->generatedId;

			// Reset all variables
			$this->adminId = '';
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
