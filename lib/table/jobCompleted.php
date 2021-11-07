<?php

	class jobCompleted {

		private string $setType;
		private database $db;

		private string $dbJobCompletedId; // Used when updating the table incase the jobCompletedId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $jobCompletedId;
		public $businessId;
		public $linkedToJobRecurringId;
		public $linkedToCustomerId;
		public $linkedToPropertyId;
		public $name;
		public $description;
		public $privateNotes;
		public $price;
		public $estHours;
		public $wasPrepaid;
		public $startDateTime;
		public $endDateTime;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $crews = array();
		public $staff = array();
		public $chemicalApplications = array();

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
			$this->linkedToJobRecurringId = NULL;
			$this->linkedToCustomerId = NULL;
			$this->linkedToPropertyId = NULL;
			$this->name = '';
			$this->description = NULL;
			$this->privateNotes = NULL;
			$this->price = NULL;
			$this->estHours = NULL;
			$this->wasPrepaid = '0';
			$this->startDateTime = '';
			$this->endDateTime = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->crews = array();
			$this->staff = array();
			$this->chemicalApplications = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $jobCompletedId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('jobCompleted', '*', "WHERE jobCompletedId ='".$this->db->sanitize($jobCompletedId)."'");

			// If jobCompletedId already exists then set the set method type to UPDATE and fetch the values for the jobCompleted
			if ($fetch) {
				$this->jobCompletedId = $jobCompletedId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToJobRecurringId = $fetch[0]['linkedToJobRecurringId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->linkedToPropertyId = $fetch[0]['linkedToPropertyId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->privateNotes = $fetch[0]['privateNotes'];
				$this->price = $fetch[0]['price'];
				$this->estHours = $fetch[0]['estHours'];
				$this->wasPrepaid = $fetch[0]['wasPrepaid'];
				$this->startDateTime = $fetch[0]['startDateTime'];
				$this->endDateTime = $fetch[0]['endDateTime'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If jobCompletedId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new jobCompletedId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('jobCompleted', 'jobCompletedId');
				$this->jobCompletedId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbJobCompletedId = $this->jobCompletedId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// crews
		public function pullCrews($params = '') {
			$this->crews = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCompletedCrewBridge', 'crewId', "WHERE jobCompletedId = '$this->dbJobCompletedId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->crews, $row['crewId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staff
		public function pullStaff($params = '') {
			$this->staff = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCompletedStaffBridge', 'staffId', "WHERE jobCompletedId = '$this->dbJobCompletedId'".$params);
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

		// chemicalApplications
		public function pullChemicalApplications($params = '') {
			$this->chemicalApplications = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalApplication', 'chemicalApplicationId', "WHERE linkedToJobCompletedId = '$this->dbJobCompletedId'".$params);
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

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'jobCompletedId' => $this->db->sanitize($this->dbJobCompletedId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToJobRecurringId' => $this->db->sanitize($this->linkedToJobRecurringId),
				'linkedToCustomerId' => $this->db->sanitize($this->linkedToCustomerId),
				'linkedToPropertyId' => $this->db->sanitize($this->linkedToPropertyId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'privateNotes' => $this->db->sanitize($this->privateNotes),
				'price' => $this->db->sanitize($this->price),
				'estHours' => $this->db->sanitize($this->estHours),
				'wasPrepaid' => $this->db->sanitize($this->wasPrepaid),
				'startDateTime' => $this->db->sanitize($this->startDateTime),
				'endDateTime' => $this->db->sanitize($this->endDateTime),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('jobCompleted', $attributes, "WHERE jobCompletedId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('jobCompleted', $attributes)) {
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
			if (!$this->db->delete('jobCompleted', "WHERE jobCompletedId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
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
