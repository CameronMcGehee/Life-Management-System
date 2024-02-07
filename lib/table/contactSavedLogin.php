<?php

	class contactSavedLogin {

		private string $setType;
		private database $db;

		private string $dbContactSavedLoginId; // Used when updating the table incase the contactSavedLoginId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $contactSavedLoginId;
		public $workspaceId;
		public $contactId;
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
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $contactSavedLoginId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('contactSavedLogin', '*', "WHERE contactSavedLoginId ='".$this->db->sanitize($contactSavedLoginId)."'");

			// If contactSavedLoginId already exists then set the set method type to UPDATE and fetch the values for the contactSavedLogin
			if ($fetch) {
				$this->contactSavedLoginId = $contactSavedLoginId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->contactId = $fetch[0]['contactId'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If contactSavedLoginId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new contactSavedLoginId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('contactSavedLogin', 'contactSavedLoginId');
				$this->contactSavedLoginId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbContactSavedLoginId = $this->contactSavedLoginId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'contactSavedLoginId' => $this->db->sanitize($this->dbContactSavedLoginId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'contactId' => $this->db->sanitize($this->contactId),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('contactSavedLogin', $attributes, "WHERE contactSavedLoginId = ".$this->db->sanitize($this->dbContactSavedLoginId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('contactSavedLogin', $attributes)) {
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
			if (!$this->db->delete('contactSavedLogin', "WHERE contactSavedLoginId = '".$this->db->sanitize($this->dbContactSavedLoginId)."'", 1)) {
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
