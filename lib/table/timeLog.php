<?php

	class timeLog {

		private string $setType;
		private database $db;

		private string $dbTimeLogId; // Used when updating the table incase the timeLogId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $timeLogId;
		public $businessId;
		public $staffId;
		public $dateTimeStart;
		public $dateTimeEnd;
		public $notes;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $payrollDues = array();

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
			$this->staffId = '';
			$this->dateTimeStart = '';
			$this->dateTimeEnd = NULL;
			$this->notes = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->payrollDues = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $timeLogId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('timeLog', '*', "WHERE timeLogId ='".$this->db->sanitize($timeLogId)."'");

			// If timeLogId already exists then set the set method type to UPDATE and fetch the values for the timeLog
			if ($fetch) {
				$this->timeLogId = $timeLogId;
				$this->businessId = $fetch[0]['businessId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->dateTimeStart = $fetch[0]['dateTimeStart'];
				$this->dateTimeEnd = $fetch[0]['dateTimeEnd'];
				$this->notes = $fetch[0]['notes'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If timeLogId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new timeLogId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('timeLog', 'timeLogId');
				$this->timeLogId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbTimeLogId = $this->timeLogId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// payrollDues
		public function pullPayrollDues($params = '') {
			$this->payrollDues = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollDue', 'payrollDueId', "WHERE linkedToTimeLogId = '$this->dbTimeLogId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payrollDues, $row['payrollDueId']);
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
				'timeLogId' => $this->db->sanitize($this->dbTimeLogId),
				'businessId' => $this->db->sanitize($this->businessId),
				'staffId' => $this->db->sanitize($this->staffId),
				'dateTimeStart' => $this->db->sanitize($this->dateTimeStart),
				'dateTimeEnd' => $this->db->sanitize($this->dateTimeEnd),
				'notes' => $this->db->sanitize($this->notes),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('timeLog', $attributes, "WHERE timeLogId = ".$this->db->sanitize($this->dbTimeLogId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('timeLog', $attributes)) {
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
			if (!$this->db->delete('timeLog', "WHERE timeLogId = '".$this->db->sanitize($this->dbTimeLogId)."'", 1)) {
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
