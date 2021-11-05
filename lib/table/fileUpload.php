<?php

	class fileUpload {

		private string $setType;
		private database $db;

		private string $dbFileUploadId; // Used when updating the table incase the fileUploadId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $fileUploadId;
		public $businessId;
		public $docIdId;
		public $linkedToCustomerId;
		public $linkedToStaffId;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $fileUploadId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('fileUpload', '*', "WHERE fileUploadId ='".$this->db->sanitize($fileUploadId)."'");

			// If fileUploadId already exists then set the set method type to UPDATE and fetch the values for the fileUpload
			if ($fetch) {
				$this->fileUploadId = $fileUploadId;
				$this->businessId = $fetch[0]['businessId'];
				$this->docIdId = $fetch[0]['docIdId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->linkedToStaffId = $fetch[0]['linkedToStaffId'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If fileUploadId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new fileUploadId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('fileUpload', 'fileUploadId');
				$this->fileUploadId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->docIdId = '';
				$this->linkedToCustomerId = NULL;
				$this->linkedToStaffId = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbFileUploadId = $this->fileUploadId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'fileUploadId' => $this->db->sanitize($this->dbFileUploadId),
				'businessId' => $this->db->sanitize($this->businessId),
				'docIdId' => $this->db->sanitize($this->docIdId),
				'linkedToCustomerId' => $this->db->sanitize($this->linkedToCustomerId),
				'linkedToStaffId' => $this->db->sanitize($this->linkedToStaffId),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('fileUpload', $attributes, "WHERE fileUploadId = '".$this->db->sanitize($this->dbFileUploadId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('fileUpload', $attributes)) {
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
			if (!$this->db->delete('fileUpload', "WHERE fileUploadId = '".$this->db->sanitize($this->dbFileUploadId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('fileUpload', 'fileUploadId');
			$this->fileUploadId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->docIdId = '';
			$this->linkedToCustomerId = NULL;
			$this->linkedToStaffId = NULL;
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
