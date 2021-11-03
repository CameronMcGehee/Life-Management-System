<?php

	class chemical {

		private string $setType;
		private databaseManager $db;

		private string $dbChemicalId; // Used when updating the table incase the chemicalId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $chemicalId;
		public $businessId;
		public $linkedToCrewId;
		public $linkedToStaffId;
		public $name;
		public $epa;
		public $ingeredients;
		public $manufacturer;
		public $dilution;
		public $targets;
		public $applicationMethod;
		public $applicationRate;
		public $defaultAmountApplied;
		public $defaultAmountAppliedUnit;
		public $amountInStock;
		public $amountInStockUnit;
		public $notesToCustomer;
		public $notesToStaff;
		public $description;
		public $condition;
		public $purchaseDate;
		public $purchasePrice;
		public $storageLocation;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $images = array();
		public $equipment = array();

		function __construct(string $chemicalId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('chemical', '*', "WHERE chemicalId ='".$this->db->sanitize($chemicalId)."'");

			// If chemicalId already exists then set the set method type to UPDATE and fetch the values for the chemical
			if ($fetch) {
				$this->chemicalId = $chemicalId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToCrewId = $fetch[0]['linkedToCrewId'];
				$this->linkedToStaffId = $fetch[0]['linkedToStaffId'];
				$this->name = $fetch[0]['name'];
				$this->epa = $fetch[0]['epa'];
				$this->ingeredients = $fetch[0]['ingeredients'];
				$this->manufacturer = $fetch[0]['manufacturer'];
				$this->dilution = $fetch[0]['dilution'];
				$this->targets = $fetch[0]['targets'];
				$this->applicationMethod = $fetch[0]['applicationMethod'];
				$this->applicationRate = $fetch[0]['applicationRate'];
				$this->defaultAmountApplied = $fetch[0]['defaultAmountApplied'];
				$this->defaultAmountAppliedUnit = $fetch[0]['defaultAmountAppliedUnit'];
				$this->amountInStock = $fetch[0]['amountInStock'];
				$this->amountInStockUnit = $fetch[0]['amountInStockUnit'];
				$this->notesToCustomer = $fetch[0]['notesToCustomer'];
				$this->notesToStaff = $fetch[0]['notesToStaff'];
				$this->description = $fetch[0]['description'];
				$this->condition = $fetch[0]['condition'];
				$this->purchaseDate = $fetch[0]['purchaseDate'];
				$this->purchasePrice = $fetch[0]['purchasePrice'];
				$this->storageLocation = $fetch[0]['storageLocation'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If chemicalId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new chemicalId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('chemical', 'chemicalId');
				$this->chemicalId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->linkedToCrewId = NULL;
				$this->linkedToStaffId = NULL;
				$this->name = '';
				$this->epa = NULL;
				$this->ingeredients = NULL;
				$this->manufacturer = NULL;
				$this->dilution = NULL;
				$this->targets = NULL;
				$this->applicationMethod = NULL;
				$this->applicationRate = NULL;
				$this->defaultAmountApplied = NULL;
				$this->defaultAmountAppliedUnit = 'ml/ft²';
				$this->amountInStock = NULL;
				$this->amountInStockUnit = 'ml';
				$this->notesToCustomer = NULL;
				$this->notesToStaff = NULL;
				$this->description = NULL;
				$this->condition = NULL;
				$this->purchaseDate = NULL;
				$this->purchasePrice = NULL;
				$this->storageLocation = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbChemicalId = $this->chemicalId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// images
		public function pullImages($params = '') {
			$this->images = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalImage', 'chemicalImageId', "WHERE chemicalId = '$this->dbChemicalId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->images, $row['chemicalImageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// equipment
		public function pullEquipment($params = '') {
			$this->equipment = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentChemicalBridge', 'equipmentId', "WHERE chemicalId = '$this->dbChemicalId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->equipment, $row['equipmentId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'chemicalId' => $this->db->sanitize($this->dbChemicalId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToCrewId' => $this->db->sanitize($this->linkedToCrewId),
				'linkedToStaffId' => $this->db->sanitize($this->linkedToStaffId),
				'name' => $this->db->sanitize($this->name),
				'epa' => $this->db->sanitize($this->epa),
				'ingeredients' => $this->db->sanitize($this->ingeredients),
				'manufacturer' => $this->db->sanitize($this->manufacturer),
				'dilution' => $this->db->sanitize($this->dilution),
				'targets' => $this->db->sanitize($this->targets),
				'applicationMethod' => $this->db->sanitize($this->applicationMethod),
				'applicationRate' => $this->db->sanitize($this->applicationRate),
				'defaultAmountApplied' => $this->db->sanitize($this->defaultAmountApplied),
				'defaultAmountAppliedUnit' => $this->db->sanitize($this->defaultAmountAppliedUnit),
				'amountInStock' => $this->db->sanitize($this->amountInStock),
				'amountInStockUnit' => $this->db->sanitize($this->amountInStockUnit),
				'notesToCustomer' => $this->db->sanitize($this->notesToCustomer),
				'notesToStaff' => $this->db->sanitize($this->notesToStaff),
				'description' => $this->db->sanitize($this->description),
				'condition' => $this->db->sanitize($this->condition),
				'purchaseDate' => $this->db->sanitize($this->purchaseDate),
				'purchasePrice' => $this->db->sanitize($this->purchasePrice),
				'storageLocation' => $this->db->sanitize($this->storageLocation),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('chemical', $attributes, "WHERE chemicalId = '".$this->db->sanitize($this->dbChemicalId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('chemical', $attributes)) {
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
			if (!$this->db->delete('chemical', "WHERE chemicalId = '".$this->db->sanitize($this->dbChemicalId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('chemical', 'chemicalId');
			$this->chemicalId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->linkedToCrewId = NULL;
			$this->linkedToStaffId = NULL;
			$this->name = '';
			$this->epa = NULL;
			$this->ingeredients = NULL;
			$this->manufacturer = NULL;
			$this->dilution = NULL;
			$this->targets = NULL;
			$this->applicationMethod = NULL;
			$this->applicationRate = NULL;
			$this->defaultAmountApplied = NULL;
			$this->defaultAmountAppliedUnit = 'ml/ft²';
			$this->amountInStock = NULL;
			$this->amountInStockUnit = 'ml';
			$this->notesToCustomer = NULL;
			$this->notesToStaff = NULL;
			$this->description = NULL;
			$this->condition = NULL;
			$this->purchaseDate = NULL;
			$this->purchasePrice = NULL;
			$this->storageLocation = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->images = array();
			$this->equipment = array();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
