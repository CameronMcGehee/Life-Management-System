<?php

	class job {

		private string $setType;
		private database $db;

		private string $dbJobSingularId; // Used when updating the table incase the jobId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $jobId;
		public $businessId;
		public $linkedToCustomerId;
		public $linkedToPropertyId;
		public $name;
		public $description;
		public $privateNotes;
		public $price;
		public $estHours;
		public $isPrepaid;
		public $frequencyInterval;
		public $frequency;
		public $weekday;
		public $startDateTime;
		public $endDateTime;
		public $isCancelled;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $instanceExceptions = array();
		public $crews = array();
		public $staff = array();

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
			$this->linkedToCustomerId = NULL;
			$this->linkedToPropertyId = NULL;
			$this->name = '';
			$this->description = NULL;
			$this->privateNotes = NULL;
			$this->price = NULL;
			$this->estHours = NULL;
			$this->isPrepaid = '0';
			$this->frequencyInterval = 'none';
			$this->frequency = '0';
			$this->weekday = NULL;
			$this->startDateTime = '';
			$this->endDateTime = NULL;
			$this->isCancelled = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->instanceExceptions = array();
			$this->crews = array();
			$this->staff = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $jobId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('job', '*', "WHERE jobId ='".$this->db->sanitize($jobId)."'");

			// If jobId already exists then set the set method type to UPDATE and fetch the values for the job
			if ($fetch) {
				$this->jobId = $jobId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->linkedToPropertyId = $fetch[0]['linkedToPropertyId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->privateNotes = $fetch[0]['privateNotes'];
				$this->price = $fetch[0]['price'];
				$this->estHours = $fetch[0]['estHours'];
				$this->isPrepaid = $fetch[0]['isPrepaid'];
				$this->frequencyInterval = $fetch[0]['frequencyInterval'];
				$this->frequency = $fetch[0]['frequency'];
				$this->weekday = $fetch[0]['weekday'];
				$this->startDateTime = $fetch[0]['startDateTime'];
				$this->endDateTime = $fetch[0]['endDateTime'];
				$this->isCancelled = $fetch[0]['isCancelled'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If jobId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new jobId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('job', 'jobId');
				$this->jobId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbJobSingularId = $this->jobId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// instanceExceptions
		public function pullInstanceExceptions($params = '') {
			$this->instanceExceptions = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobInstanceException', 'jobInstanceExceptionId', "WHERE jobId = '$this->dbJobSingularId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->instanceExceptions, $row['jobInstanceExceptionId']);
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
			$fetch = $this->db->select('jobCrewBridge', 'crewId', "WHERE jobId = '$this->dbJobSingularId'".$params);
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
			$fetch = $this->db->select('jobStaffBridge', 'staffId', "WHERE jobId = '$this->dbJobSingularId'".$params);
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
				'jobId' => $this->db->sanitize($this->dbJobSingularId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToCustomerId' => $this->db->sanitize($this->linkedToCustomerId),
				'linkedToPropertyId' => $this->db->sanitize($this->linkedToPropertyId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'privateNotes' => $this->db->sanitize($this->privateNotes),
				'price' => $this->db->sanitize($this->price),
				'estHours' => $this->db->sanitize($this->estHours),
				'isPrepaid' => $this->db->sanitize($this->isPrepaid),
				'frequency' => $this->db->sanitize($this->frequency),
				'frequencyInterval' => $this->db->sanitize($this->frequencyInterval),
				'weekday' => $this->db->sanitize($this->weekday),
				'startDateTime' => $this->db->sanitize($this->startDateTime),
				'endDateTime' => $this->db->sanitize($this->endDateTime),
				'isCancelled' => $this->db->sanitize($this->isCancelled),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('job', $attributes, "WHERE jobId = '".$this->db->sanitize($this->dbJobSingularId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('job', $attributes)) {
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
			if (!$this->db->delete('job', "WHERE jobId = '".$this->db->sanitize($this->dbJobSingularId)."'", 1)) {
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
