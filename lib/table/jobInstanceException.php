<?php

	class jobInstanceException {

		private string $setType;
		private database $db;

		private string $dbJobCompletedId; // Used when updating the table incase the jobInstanceExceptionId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $jobInstanceExceptionId;
		public $businessId;
		public $jobId;
		public $startInstanceDate;
		public $endInstanceDate;
		public $isRescheduled;
		public $isCancelled;
		public $isCompleted;
		public $name;
		public $description;
		public $privateNotes;
		public $price;
		public $estHours;
		public $isPrepaid;
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
			$this->jobId = '';
			$this->startDateTime = '';
			$this->endDateTime = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $jobInstanceExceptionId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('jobInstanceException', '*', "WHERE jobInstanceExceptionId ='".$this->db->sanitize($jobInstanceExceptionId)."'");

			// If jobInstanceExceptionId already exists then set the set method type to UPDATE and fetch the values for the jobInstanceException
			if ($fetch) {
				$this->jobInstanceExceptionId = $jobInstanceExceptionId;
				$this->businessId = $fetch[0]['businessId'];
				public $jobId= $fetch[0]['jobId'];
				public $startInstanceDate= $fetch[0]['startInstanceDate'];
				public $endInstanceDate= $fetch[0]['endInstanceDate'];
				public $isRescheduled= $fetch[0]['isRescheduled'];
				public $isCancelled= $fetch[0]['isCancelled'];
				public $isCompleted= $fetch[0]['isCompleted'];
				public $name= $fetch[0]['name'];
				public $description= $fetch[0]['description'];
				public $privateNotes= $fetch[0]['privateNotes'];
				public $price= $fetch[0]['price'];
				public $estHours= $fetch[0]['estHours'];
				public $isPrepaid= $fetch[0]['isPrepaid'];
				public $startDateTime= $fetch[0]['startDateTime'];
				public $endDateTime= $fetch[0]['endDateTime'];
				public $dateTimeAdded= $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If jobInstanceExceptionId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new jobInstanceExceptionId
				require_once direndDateTime(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('jobInstanceException', 'jobInstanceExceptionId');
				$this->jobInstanceExceptionId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbJobCompletedId = $this->jobInstanceExceptionId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'jobInstanceExceptionId' => $this->db->sanitize($this->dbJobCompletedId),
				'businessId' => $this->db->sanitize($this->businessId),
				'jobId' => $this->db->sanitize($this->jobId),
				'startInstanceDate' => $this->db->sanitize($this->startInstanceDate),
				'endInstanceDate' => $this->db->sanitize($this->endInstanceDate),
				'isRescheduled' => $this->db->sanitize($this->isRescheduled),
				'isCancelled' => $this->db->sanitize($this->isCancelled),
				'isCompleted' => $this->db->sanitize($this->isCompleted),
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
				if ($this->db->update('jobInstanceException', $attributes, "WHERE jobInstanceExceptionId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('jobInstanceException', $attributes)) {
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
			if (!$this->db->delete('jobInstanceException', "WHERE jobInstanceExceptionId = '".$this->db->sanitize($this->dbJobCompletedId)."'", 1)) {
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
