<?php

	class completedCalendarEvent {

		private string $setType;
		private database $db;

		private string $dbCalendarEventSingularId; // Used when updating the table incase the completedCalendarEventId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $completedCalendarEventId;
		public $workspaceId;
		public $linkedToCalendarEventId;
		public $linkedToContactId;
		public $linkedToPropertyId;
		public $contactFirstName;
		public $contactLastName;
		public $propertyAddress1;
		public $propertyAddress2;
		public $propertyCity;
		public $propertyState;
		public $propertyZipCode;
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
		public $instanceDate;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $cancellations = array();
		public $crews = array();
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
			$this->linkedToCalendarEventId = NULL;
			$this->linkedToContactId = NULL;
			$this->linkedToPropertyId = NULL;
			$this->contactFirstName = '';
			$this->contactLastName = '';
			$this->propertyAddress1 = '';
			$this->propertyAddress2 = NULL;
			$this->propertyCity = NULL;
			$this->propertyState = NULL;
			$this->propertyZipCode = NULL;
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
			$this->instanceDate = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->cancellations = array();
			$this->crews = array();
			$this->staff = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $completedCalendarEventId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('completedCalendarEvent', '*', "WHERE completedCalendarEventId ='".$this->db->sanitize($completedCalendarEventId)."'");

			// If completedCalendarEventId already exists then set the set method type to UPDATE and fetch the values for the completedCalendarEvent
			if ($fetch) {
				$this->completedCalendarEventId = $completedCalendarEventId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->linkedToCalendarEventId = $fetch[0]['linkedToCalendarEventId'];
				$this->linkedToContactId = $fetch[0]['linkedToContactId'];
				$this->linkedToPropertyId = $fetch[0]['linkedToPropertyId'];
				$this->contactFirstName = $fetch[0]['contactFirstName'];
				$this->contactLastName = $fetch[0]['contactLastName'];
				$this->propertyAddress1 = $fetch[0]['propertyAddress1'];
				$this->propertyAddress2 = $fetch[0]['propertyAddress2'];
				$this->propertyCity = $fetch[0]['propertyCity'];
				$this->propertyState = $fetch[0]['propertyState'];
				$this->propertyZipCode = $fetch[0]['propertyZipCode'];
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
				$this->instanceDate = $fetch[0]['instanceDate'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If completedCalendarEventId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new completedCalendarEventId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('completedCalendarEvent', 'completedCalendarEventId');
				$this->completedCalendarEventId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCalendarEventSingularId = $this->completedCalendarEventId;
			
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
			$fetch = $this->db->select('completedCalendarEventCrewBridge', 'crewId', "WHERE completedCalendarEventId = '$this->dbCalendarEventSingularId'".$params);
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
			$fetch = $this->db->select('completedCalendarEventStaffBridge', 'staffId', "WHERE completedCalendarEventId = '$this->dbCalendarEventSingularId'".$params);
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
				'completedCalendarEventId' => $this->db->sanitize($this->dbCalendarEventSingularId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'linkedToCalendarEventId' => $this->db->sanitize($this->linkedToCalendarEventId),
				'linkedToContactId' => $this->db->sanitize($this->linkedToContactId),
				'linkedToPropertyId' => $this->db->sanitize($this->linkedToPropertyId),
				'contactFirstName' => $this->db->sanitize($this->contactFirstName),
				'contactLastName' => $this->db->sanitize($this->contactLastName),
				'propertyAddress1' => $this->db->sanitize($this->propertyAddress1),
				'propertyAddress2' => $this->db->sanitize($this->propertyAddress2),
				'propertyCity' => $this->db->sanitize($this->propertyCity),
				'propertyState' => $this->db->sanitize($this->propertyState),
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
				'instanceDate' => $this->db->sanitize($this->instanceDate),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('completedCalendarEvent', $attributes, "WHERE completedCalendarEventId = '".$this->db->sanitize($this->dbCalendarEventSingularId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('completedCalendarEvent', $attributes)) {
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
			if (!$this->db->delete('completedCalendarEvent', "WHERE completedCalendarEventId = '".$this->db->sanitize($this->dbCalendarEventSingularId)."'", 1)) {
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
