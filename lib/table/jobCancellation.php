<?php

	class jobCancellation {

		private string $setType;
		private database $db;

		private string $dbJobCompletedId; // Used when updating the table incase the jobCancellationId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $jobCancellationId;
		public $businessId;
		public $linkedToJobSingularId;
		public $linkedToJobRecurringId;
		public $startDateTime;
		public $endDateTime;
		public $dateTimeAdded;

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
			$this->linkedToJobSingularId = NULL;
			$this->linkedToJobRecurringId = NULL;
			$this->startDateTime = '';
			$this->endDateTime = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		function __construct(string $jobCancellationId = '') {

			// Connect to the database
			require_once direndDateTime(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('jobCancellation', '*', "WHERE jobCancellationId ='".$this->db->sanitize($jobCancellationId)."'");

			// If jobCancellationId already exists then set the set method type to UPDATE and fetch the values for the jobCancellation
			if ($fetch) {
				$this->jobCancellationId = $jobCancellationId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToJobSingularId = $fetch[0]['linkedToJobSingularId'];
				$this->linkedToJobRecurringId = $fetch[0]['linkedToJobRecurringId'];
				$this->startDateTime = $fetch[0]['startDateTime'];
				$this->endDateTime = $fetch[0]['endDateTime'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If jobCancellationId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new jobCancellationId
				require_once direndDateTime(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('jobCancellation', 'jobCancellationId');
				$this->jobCancellationId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbJobCompletedId = $this->jobCancellationId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'jobCancellationId' => $this->db->sanitize($this->dbJobCompletedId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToJobSingularId' => $this->db->sanitize($this->linkedToJobSingularId),
				'linkedToJobRecurringId' => $this->db->sanitize($this->linkedToJobRecurringId),
				'startDateTime' => $this->db->sanitize($this->startDateTime),
				'endDateTime' => $this->db->sanitize($this->endDateTime),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('jobCancellation', $attributes, "WHERE jobCancellationId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('jobCancellation', $attributes)) {
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
			if (!$this->db->delete('jobCancellation', "WHERE jobCancellationId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
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
