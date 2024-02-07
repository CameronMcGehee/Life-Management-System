<?php

	class calendarEventInstanceException {

		private string $setType;
		private database $db;

		private string $dbCalendarEventInstanceExceptionId; // Used when updating the table incase the calendarEventInstanceExceptionId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $calendarEventInstanceExceptionId;
		public $workspaceId;
		public $calendarEventId;
		public $instanceDate;
		public $isRescheduled;
		public $isCancelled;
		public $isCompleted;
		public $linkedToCompletedCalendarEventId;
		public $linkedToContactId;
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
			$this->calendarEventId = '';
			$this->instanceDate = '';
			$this->newStartDateTime = '';
			$this->newEndDateTime = '';
			$this->isRescheduled = 0;
			$this->isCancelled = 0;
			$this->isCompleted = 0;
			$this->linkedToCompletedCalendarEventId = NULL;
			$this->linkedToContactId = NULL;
			$this->linkedToPropertyId = NULL;
			$this->name = '';
			$this->description = NULL;
			$this->privateNotes = NULL;
			$this->price = NULL;
			$this->estHours = NULL;
			$this->isPrepaid = 0;
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

		function __construct(string $calendarEventInstanceExceptionId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('calendarEventInstanceException', '*', "WHERE calendarEventInstanceExceptionId ='".$this->db->sanitize($calendarEventInstanceExceptionId)."'");

			// If calendarEventInstanceExceptionId already exists then set the set method type to UPDATE and fetch the values for the calendarEventInstanceException
			if ($fetch) {
				$this->calendarEventInstanceExceptionId = $calendarEventInstanceExceptionId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->calendarEventId = $fetch[0]['calendarEventId'];
				$this->instanceDate = $fetch[0]['instanceDate'];
				$this->isRescheduled = $fetch[0]['isRescheduled'];
				$this->isCancelled = $fetch[0]['isCancelled'];
				$this->isCompleted = $fetch[0]['isCompleted'];
				$this->linkedToCompletedCalendarEventId = $fetch[0]['linkedToCompletedCalendarEventId'];
				$this->linkedToContactId = $fetch[0]['linkedToContactId'];
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

			// If calendarEventInstanceExceptionId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new calendarEventInstanceExceptionId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('calendarEventInstanceException', 'calendarEventInstanceExceptionId');
				$this->calendarEventInstanceExceptionId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCalendarEventInstanceExceptionId = $this->calendarEventInstanceExceptionId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'calendarEventInstanceExceptionId' => $this->db->sanitize($this->dbCalendarEventInstanceExceptionId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'calendarEventId' => $this->db->sanitize($this->calendarEventId),
				'instanceDate' => $this->db->sanitize($this->instanceDate),
				'isRescheduled' => $this->db->sanitize($this->isRescheduled),
				'isCancelled' => $this->db->sanitize($this->isCancelled),
				'isCompleted' => $this->db->sanitize($this->isCompleted),
				'linkedToCompletedCalendarEventId' => $this->db->sanitize($this->linkedToCompletedCalendarEventId),
				'linkedToContactId' => $this->db->sanitize($this->linkedToContactId),
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
				if ($this->db->update('calendarEventInstanceException', $attributes, "WHERE calendarEventInstanceExceptionId = '".$this->db->sanitize($this->dbCalendarEventInstanceExceptionId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('calendarEventInstanceException', $attributes)) {
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
			if (!$this->db->delete('calendarEventInstanceException', "WHERE calendarEventInstanceExceptionId = '".$this->db->sanitize($this->dbCalendarEventInstanceExceptionId)."'", 1)) {
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
