<?php

	class crew {

		private string $setType;
		private databaseManager $db;

		public string $dbCrewId; // Used when updating the table incase the crewId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $crewId;
		public $businessId;
		public $createdByAdminId;
		public $name;
		public $description;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $chemicals = array();
		public $equipment = array();
		public $chemicalApplications = array();
		public $jobCompleteds = array();
		public $jobSingulars = array();
		public $jobRecurrings = array();
		public $leaders = array();
		public $staff = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $crewId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('crew', '*', "WHERE crewId ='$crewId'");

			// If crewId already exists then set the set method type to UPDATE and fetch the values for the crew
			if ($fetch) {
				$this->crewId = $crewId;
				$this->businessId = $fetch[0]['businessId'];
				$this->createdByAdminId = $fetch[0]['createdByAdminId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If crewId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new crewId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('crew', 'crewId');
				$this->crewId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->createdByAdminId = '';
				$this->name = '';
				$this->description = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbCrewId = $this->crewId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// chemicals
		public function pullChemicals ($params = '') {
			$this->chemicals = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemical', 'chemicalId', "WHERE linkedToCrewId = '$this->dbCrewId'".$params);
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
		
		// equipment
		public function pullEquipment ($params = '') {
			$this->equipment = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('equipment', 'equipmentId', "WHERE linkedToCrewId = '$this->dbCrewId'".$params);
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
		
		// chemicalApplications
		public function pullChemicalApplications ($params = '') {
			$this->chemicalApplications = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalApplication', 'chemicalApplicationId', "WHERE linkedToCrewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicalApplications, $row['chemicalApplicationId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// jobCompleteds
		public function pullJobCompleteds ($params = '') {
			$this->jobCompleteds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCompletedCrewBridge', 'jobCompletedCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobCompleteds, $row['jobCompletedId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// jobSingulars
		public function pullJobSingulars ($params = '') {
			$this->jobSingulars = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobSingularCrewBridge', 'jobSingularCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobSingulars, $row['jobSingularId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// jobRecurrings
		public function pullJobRecurrings ($params = '') {
			$this->jobRecurrings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobRecurringCrewBridge', 'jobRecurringCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobRecurrings, $row['jobRecurringId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// leaders
		public function pullLeaders ($params = '') {
			$this->leaders = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crewLeaderBridge', 'crewLeaderId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->leaders, $row['staffId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// staff
		public function pullStaff ($params = '') {
			$this->staff = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crewStaffBridge', 'crewStaffId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->staff, $row['staffId']);
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
				'crewId' => $this->db->sanitize($this->dbCrewId),
				'businessId' => $this->db->sanitize($this->businessId),
				'createdByAdminId' => $this->db->sanitize($this->createdByAdminId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('crew', $attributes, "WHERE crewId = '".$this->db->sanitize($this->dbCrewId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('crew', $attributes)) {
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
			if (!$this->db->delete('crew', "WHERE crewId = '".$this->db->sanitize($this->dbCrewId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('crew', 'crewId');
			$this->crewId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->createdByAdminId = '';
			$this->name = '';
			$this->description = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->chemicals = array();
			$this->equipment = array();
			$this->chemicalApplications = array();
			$this->jobCompleteds = array();
			$this->jobSingulars = array();
			$this->jobRecurrings = array();
			$this->leaders = array();
			$this->staff = array();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
