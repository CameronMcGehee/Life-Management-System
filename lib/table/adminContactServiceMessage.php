<?php

	class adminContactServiceMessage {

		private string $setType;
		private database $db;

		private string $dbAdminContactServiceMessageId; // Used when updating the table incase the adminContactServiceMessageId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $adminContactServiceMessageId;
		public $workspaceId;
		public $adminId;
		public $contactServiceTicketId;
		public $message;
		public $isReadByContact;
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
			$this->adminId = '';
			$this->contactServiceTicketId = '';
			$this->message = '';
			$this->isReadByContact = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminContactServiceMessageId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('adminContactServiceMessage', '*', "WHERE adminContactServiceMessageId ='".$this->db->sanitize($adminContactServiceMessageId)."'");

			// If adminContactServiceMessageId already exists then set the set method type to UPDATE and fetch the values for the adminContactServiceMessage
			if ($fetch) {
				$this->adminContactServiceMessageId = $adminContactServiceMessageId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->adminId = $fetch[0]['adminId'];
				$this->contactServiceTicketId = $fetch[0]['contactServiceTicketId'];
				$this->message = $fetch[0]['message'];
				$this->isReadByContact = $fetch[0]['isReadByContact'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If adminContactServiceMessageId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new adminContactServiceMessageId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('adminContactServiceMessage', 'adminContactServiceMessageId');
				$this->adminContactServiceMessageId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbAdminContactServiceMessageId = $this->adminContactServiceMessageId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'adminContactServiceMessageId' => $this->db->sanitize($this->dbAdminContactServiceMessageId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'adminId' => $this->db->sanitize($this->adminId),
				'contactServiceTicketId' => $this->db->sanitize($this->contactServiceTicketId),
				'message' => $this->db->sanitize($this->message),
				'isReadByContact' => $this->db->sanitize($this->isReadByContact),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('adminContactServiceMessage', $attributes, "WHERE adminContactServiceMessageId = '".$this->db->sanitize($this->dbAdminContactServiceMessageId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('adminContactServiceMessage', $attributes)) {
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
			if (!$this->db->delete('adminContactServiceMessage', "WHERE adminContactServiceMessageId = '".$this->db->sanitize($this->dbAdminContactServiceMessageId)."'", 1)) {
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
