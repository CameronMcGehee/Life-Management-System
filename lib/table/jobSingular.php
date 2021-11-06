<?php

	class jobSingular {

		private string $setType;
		private database $db;

		private string $dbJobSingularId; // Used when updating the table incase the jobSingularId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $jobSingularId;
		public $businessId;
		public $linkedToJobRecurringId;
		public $linkedToCustomerId;
		public $linkedToPropertyId;
		public $name;
		public $description;
		public $privateNotes;
		public $price;
		public $estHours;
		public $isPrepaid;
		public $startDateTime;
		public $endDateTime;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $cancellations = array();
		public $crews = array();
		public $staff = array();

		function __construct(string $jobSingularId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('jobSingular', '*', "WHERE jobSingularId ='".$this->db->sanitize($jobSingularId)."'");

			// If jobSingularId already exists then set the set method type to UPDATE and fetch the values for the jobSingular
			if ($fetch) {
				$this->jobSingularId = $jobSingularId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToJobRecurringId = $fetch[0]['linkedToJobRecurringId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->linkedToPropertyId = $fetch[0]['linkedToPropertyId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->privateNotes = $fetch[0]['privateNotes'];
				$this->price = $fetch[0]['price'];
				$this->estHours = $fetch[0]['estHours'];
				$this->isPrepaid = $fetch[0]['isPrepaid'];
				$this->startDateTime = $fetch[0]['startDateTime'];
				$this->endDateTime = $fetch[0]['endDateTime'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If jobSingularId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new jobSingularId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('jobSingular', 'jobSingularId');
				$this->jobSingularId = $uuid->generatedId;

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
				$this->isPrepaid = '0';
				$this->startDateTime = '';
				$this->endDateTime = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbJobSingularId = $this->jobSingularId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// cancellations
		public function pullCancellations($params = '') {
			$this->cancellations = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCancellation', 'jobCancellationId', "WHERE jobSingularId = '$this->dbJobSingularId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->cancellations, $row['jobCancellationId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// crews
		public function pullCrews($params = '') {
			$this->crews = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobSingularCrewBridge', 'crewId', "WHERE jobSingularId = '$this->dbJobSingularId'".$params);
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
			$fetch = $this->db->select('jobSingularStaffBridge', 'staffId', "WHERE jobSingularId = '$this->dbJobSingularId'".$params);
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
				'jobSingularId' => $this->db->sanitize($this->dbJobSingularId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToJobRecurringId' => $this->db->sanitize($this->linkedToJobRecurringId),
				'linkedToCustomerId' => $this->db->sanitize($this->linkedToCustomerId),
				'linkedToPropertyId' => $this->db->sanitize($this->linkedToPropertyId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'privateNotes' => $this->db->sanitize($this->privateNotes),
				'price' => $this->db->sanitize($this->price),
				'estHours' => $this->db->sanitize($this->estHours),
				'isPrepaid' => $this->db->sanitize($this->isPrepaid),
				'startDateTime' => $this->db->sanitize($this->startDateTime),
				'endDateTime' => $this->db->sanitize($this->endDateTime),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('jobSingular', $attributes, "WHERE jobSingularId = '".$this->db->sanitize($this->dbJobSingularId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('jobSingular', $attributes)) {
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
			if (!$this->db->delete('jobSingular', "WHERE jobSingularId = '".$this->db->sanitize($this->dbJobSingularId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('jobSingular', 'jobSingularId');
			$this->jobSingularId = $uuid->generatedId;

			// Reset all variables
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
			$this->isPrepaid = '0';
			$this->startDateTime = '';
			$this->endDateTime = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->cancellations = array();
			$this->completed = array();
			$this->linkedJobSingulars = array();
			$this->crews = array();
			$this->staff = array();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
