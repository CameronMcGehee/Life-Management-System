<?php

	class equipmentImage {

		private string $setType;
		private database $db;

		private string $dbEquipmentImageId; // Used when updating the table incase the equipmentImageId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $equipmentImageId;
		public $businessId;
		public $equipmentId;
		public $imgFile;
		public $caption;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $equipmentImageId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('equipmentImage', '*', "WHERE equipmentImageId ='".$this->db->sanitize($equipmentImageId)."'");

			// If equipmentImageId already exists then set the set method type to UPDATE and fetch the values for the equipmentImage
			if ($fetch) {
				$this->equipmentImageId = $equipmentImageId;
				$this->businessId = $fetch[0]['businessId'];
				$this->equipmentId = $fetch[0]['equipmentId'];
				$this->imgFile = $fetch[0]['imgFile'];
				$this->caption = $fetch[0]['caption'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If equipmentImageId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new equipmentImageId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('equipmentImage', 'equipmentImageId');
				$this->equipmentImageId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->equipmentId = '';
				$this->imgFile = '';
				$this->caption = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbEquipmentImageId = $this->equipmentImageId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'equipmentImageId' => $this->db->sanitize($this->dbEquipmentImageId),
				'businessId' => $this->db->sanitize($this->businessId),
				'equipmentId' => $this->db->sanitize($this->equipmentId),
				'imgFile' => $this->db->sanitize($this->imgFile),
				'caption' => $this->db->sanitize($this->caption),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('equipmentImage', $attributes, "WHERE equipmentImageId = ".$this->db->sanitize($this->dbEquipmentImageId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('equipmentImage', $attributes)) {
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
			if (!$this->db->delete('equipmentImage', "WHERE equipmentImageId = '".$this->db->sanitize($this->dbEquipmentImageId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('equipmentImage', 'equipmentImageId');
			$this->equipmentImageId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->equipmentId = '';
			$this->imgFile = '';
			$this->caption = NULL;
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
