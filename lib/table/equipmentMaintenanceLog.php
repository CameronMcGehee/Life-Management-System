<?php

	class equipmentMaintenanceLog {

		private string $setType;
		private database $db;

		private string $dbEquipmentMaintenanceLogId; // Used when updating the table incase the equipmentMaintenanceLogId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $equipmentMaintenanceLogId;
		public $workspaceId;
		public $equipmentId;
		public $title;
		public $details;
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
			$this->equipmentId = '';
			$this->title = '';
			$this->details = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $equipmentMaintenanceLogId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('equipmentMaintenanceLog', '*', "WHERE equipmentMaintenanceLogId ='".$this->db->sanitize($equipmentMaintenanceLogId)."'");

			// If equipmentMaintenanceLogId already exists then set the set method type to UPDATE and fetch the values for the equipmentMaintenanceLog
			if ($fetch) {
				$this->equipmentMaintenanceLogId = $equipmentMaintenanceLogId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->equipmentId = $fetch[0]['equipmentId'];
				$this->title = $fetch[0]['title'];
				$this->details = $fetch[0]['details'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If equipmentMaintenanceLogId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new equipmentMaintenanceLogId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('equipmentMaintenanceLog', 'equipmentMaintenanceLogId');
				$this->equipmentMaintenanceLogId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbEquipmentMaintenanceLogId = $this->equipmentMaintenanceLogId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'equipmentMaintenanceLogId' => $this->db->sanitize($this->dbEquipmentMaintenanceLogId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'equipmentId' => $this->db->sanitize($this->equipmentId),
				'title' => $this->db->sanitize($this->title),
				'details' => $this->db->sanitize($this->details),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('equipmentMaintenanceLog', $attributes, "WHERE equipmentMaintenanceLogId = '".$this->db->sanitize($this->dbEquipmentMaintenanceLogId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('equipmentMaintenanceLog', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('equipmentMaintenanceLog', "WHERE equipmentMaintenanceLogId = '".$this->db->sanitize($this->dbEquipmentMaintenanceLogId)."'", 1)) {
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
