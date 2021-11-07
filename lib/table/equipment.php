<?php

	class equipment {

		private string $setType;
		private database $db;

		private string $dbEquipmentId; // Used when updating the table incase the equipmentId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $equipmentId;
		public $businessId;
		public $linkedToCrewId;
		public $linkedToStaffId;
		public $name;
		public $description;
		public $condition;
		public $model;
		public $serialNumber;
		public $purchaseDate;
		public $purchasePrice;
		public $storageLocation;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $tags = array();
		public $images = array();
		public $maintenanceLogs = array();
		public $chemicals = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->linkedToCrewId = NULL;
			$this->linkedToStaffId = NULL;
			$this->name = '';
			$this->description = NULL;
			$this->condition = NULL;
			$this->model = NULL;
			$this->serialNumber = NULL;
			$this->purchaseDate = NULL;
			$this->purchasePrice = NULL;
			$this->storageLocation = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->tags = array();
			$this->images = array();
			$this->maintenanceLogs = array();
			$this->chemicals = array();
		}

		function __construct(string $equipmentId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('equipment', '*', "WHERE equipmentId ='".$this->db->sanitize($equipmentId)."'");

			// If equipmentId already exists then set the set method type to UPDATE and fetch the values for the equipment
			if ($fetch) {
				$this->equipmentId = $equipmentId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToCrewId = $fetch[0]['linkedToCrewId'];
				$this->linkedToStaffId = $fetch[0]['linkedToStaffId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->condition = $fetch[0]['condition'];
				$this->model = $fetch[0]['model'];
				$this->serialNumber = $fetch[0]['serialNumber'];
				$this->purchaseDate = $fetch[0]['purchaseDate'];
				$this->purchasePrice = $fetch[0]['purchasePrice'];
				$this->storageLocation = $fetch[0]['storageLocation'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If equipmentId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new equipmentId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('equipment', 'equipmentId');
				$this->equipmentId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbEquipmentId = $this->equipmentId;
			
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
			$fetch = $this->db->select('equipmentImage', 'equipmentImageId', "WHERE equipmentId = '$this->dbEquipmentId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->images, $row['equipmentImageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// maintenanceLogs
		public function pullMaintenanceLogs($params = '') {
			$this->maintenanceLogs = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentMaintenanceLog', 'equipmentMaintenanceLogId', "WHERE equipmentId = '$this->dbEquipmentId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->maintenanceLogs, $row['equipmentMaintenanceLogId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// chemicals
		public function pullChemicals($params = '') {
			$this->chemicals = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipmentChemicalBridge', 'chemicalId', "WHERE equipmentId = '$this->dbChemicalId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicals, $row['chemicalId']);
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
				'equipmentId' => $this->db->sanitize($this->dbEquipmentId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToCrewId' => $this->db->sanitize($this->linkedToCrewId),
				'linkedToStaffId' => $this->db->sanitize($this->linkedToStaffId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'condition' => $this->db->sanitize($this->condition),
				'model' => $this->db->sanitize($this->model),
				'serialNumber' => $this->db->sanitize($this->serialNumber),
				'purchaseDate' => $this->db->sanitize($this->purchaseDate),
				'purchasePrice' => $this->db->sanitize($this->purchasePrice),
				'storageLocation' => $this->db->sanitize($this->storageLocation),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('equipment', $attributes, "WHERE equipmentId = '".$this->db->sanitize($this->dbEquipmentId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('equipment', $attributes)) {
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
			if (!$this->db->delete('equipment', "WHERE equipmentId = '".$this->db->sanitize($this->dbEquipmentId)."'", 1)) {
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
