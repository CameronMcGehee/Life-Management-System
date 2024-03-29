<?php

	class chemicalImage {

		private string $setType;
		private database $db;

		private string $dbChemicalImageId; // Used when updating the table incase the chemicalImageId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $chemicalImageId;
		public $workspaceId;
		public $chemicalId;
		public $imgFile;
		public $caption;
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
			$this->chemicalId = '';
			$this->imgFile = '';
			$this->caption = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $chemicalImageId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('chemicalImage', '*', "WHERE chemicalImageId ='".$this->db->sanitize($chemicalImageId)."'");

			// If chemicalImageId already exists then set the set method type to UPDATE and fetch the values for the chemicalImage
			if ($fetch) {
				$this->chemicalImageId = $chemicalImageId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->chemicalId = $fetch[0]['chemicalId'];
				$this->imgFile = $fetch[0]['imgFile'];
				$this->caption = $fetch[0]['caption'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If chemicalImageId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new chemicalImageId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('chemicalImage', 'chemicalImageId');
				$this->chemicalImageId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbChemicalImageId = $this->chemicalImageId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'chemicalImageId' => $this->db->sanitize($this->dbChemicalImageId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'chemicalId' => $this->db->sanitize($this->chemicalId),
				'imgFile' => $this->db->sanitize($this->imgFile),
				'caption' => $this->db->sanitize($this->caption),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('chemicalImage', $attributes, "WHERE chemicalImageId = ".$this->db->sanitize($this->dbChemicalImageId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('chemicalImage', $attributes)) {
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
			if (!$this->db->delete('chemicalImage', "WHERE chemicalImageId = '".$this->db->sanitize($this->dbChemicalImageId)."'", 1)) {
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
