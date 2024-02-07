<?php

	class chemicalApplication {

		private string $setType;
		private database $db;

		private string $dbChemicalApplicationId; // Used when updating the table incase the chemicalApplicationId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $chemicalApplicationId;
		public $workspaceId;
		public $chemicalId;
		public $propertyId;
		public $linkedToCrewId;
		public $linkedToStaffId;
		public $linkedToCalendarEventCompletedId;
		public $weatherDescription;
		public $amountApplied;
		public $wasSubtractedFromStock;
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
			$this->propertyId = '';
			$this->linkedToCrewId = NULL;
			$this->linkedToStaffId = NULL;
			$this->linkedToCalendarEventCompletedId = NULL;
			$this->weatherDescription = NULL;
			$this->amountApplied = NULL;
			$this->wasSubtractedFromStock = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $chemicalApplicationId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('chemicalApplication', '*', "WHERE chemicalApplicationId ='".$this->db->sanitize($chemicalApplicationId)."'");

			// If chemicalApplicationId already exists then set the set method type to UPDATE and fetch the values for the chemicalApplication
			if ($fetch) {
				$this->chemicalApplicationId = $chemicalApplicationId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->chemicalId = $fetch[0]['chemicalId'];
				$this->propertyId = $fetch[0]['propertyId'];
				$this->linkedToCrewId = $fetch[0]['linkedToCrewId'];
				$this->linkedToStaffId = $fetch[0]['linkedToStaffId'];
				$this->linkedToCalendarEventCompletedId = $fetch[0]['linkedToCalendarEventCompletedId'];
				$this->weatherDescription = $fetch[0]['weatherDescription'];
				$this->amountApplied = $fetch[0]['amountApplied'];
				$this->wasSubtractedFromStock = $fetch[0]['wasSubtractedFromStock'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If chemicalApplicationId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new chemicalApplicationId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('chemicalApplication', 'chemicalApplicationId');
				$this->chemicalApplicationId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbChemicalApplicationId = $this->chemicalApplicationId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'chemicalApplicationId' => $this->db->sanitize($this->dbChemicalApplicationId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'chemicalId' => $this->db->sanitize($this->chemicalId),
				'propertyId' => $this->db->sanitize($this->propertyId),
				'linkedToCrewId' => $this->db->sanitize($this->linkedToCrewId),
				'linkedToStaffId' => $this->db->sanitize($this->linkedToStaffId),
				'linkedToCalendarEventCompletedId' => $this->db->sanitize($this->linkedToCalendarEventCompletedId),
				'weatherDescription' => $this->db->sanitize($this->weatherDescription),
				'amountApplied' => $this->db->sanitize($this->amountApplied),
				'wasSubtractedFromStock' => $this->db->sanitize($this->wasSubtractedFromStock),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('chemicalApplication', $attributes, "WHERE chemicalApplicationId = ".$this->db->sanitize($this->dbChemicalApplicationId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('chemicalApplication', $attributes)) {
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
			if (!$this->db->delete('chemicalApplication', "WHERE chemicalApplicationId = '".$this->db->sanitize($this->dbChemicalApplicationId)."'", 1)) {
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
