<?php

	class crew {

		private string $setType;
		private database $db;

		private string $dbCrewId; // Used when updating the table incase the crewId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $crewId;
		public $workspaceId;
		public $name;
		public $description;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $chemicals = array();
		public $equipment = array();
		public $chemicalApplications = array();
		public $calendarEventCompleteds = array();
		public $calendarEventSingulars = array();
		public $calendarEventRecurrings = array();
		public $leaders = array();
		public $staff = array();

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
			$this->name = '';
			$this->description = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->chemicals = array();
			$this->equipment = array();
			$this->chemicalApplications = array();
			$this->calendarEventCompleteds = array();
			$this->calendarEventSingulars = array();
			$this->calendarEventRecurrings = array();
			$this->leaders = array();
			$this->staff = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $crewId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('crew', '*', "WHERE crewId ='".$this->db->sanitize($crewId)."'");

			// If crewId already exists then set the set method type to UPDATE and fetch the values for the crew
			if ($fetch) {
				$this->crewId = $crewId;
				$this->workspaceId = $fetch[0]['workspaceId'];
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

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCrewId = $this->crewId;
			
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
		
		// calendarEventCompleteds
		public function pullCalendarEventCompleteds ($params = '') {
			$this->calendarEventCompleteds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventCompletedCrewBridge', 'calendarEventCompletedCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventCompleteds, $row['calendarEventCompletedId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// calendarEventSingulars
		public function pullCalendarEventSingulars ($params = '') {
			$this->calendarEventSingulars = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventSingularCrewBridge', 'calendarEventSingularCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventSingulars, $row['calendarEventSingularId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// calendarEventRecurrings
		public function pullCalendarEventRecurrings ($params = '') {
			$this->calendarEventRecurrings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventRecurringCrewBridge', 'calendarEventRecurringCrewId', "WHERE crewId = '$this->dbCrewId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventRecurrings, $row['calendarEventRecurringId']);
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
				'workspaceId' => $this->db->sanitize($this->workspaceId),
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

			$this->setToDefaults();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
