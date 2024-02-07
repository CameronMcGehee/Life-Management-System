<?php

	class contactServiceTicket {

		private string $setType;
		private database $db;

		private string $dbContactServiceTicketId; // Used when updating the table incase the contactServiceTicketId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $contactServiceTicketId;
		public $workspaceId;
		public $linkedToContactId;
		public $linkedToInvoiceId;
		public $linkedToEstimateId;
		public $linkedToQuoteRequestId;
		public $contactName;
		public $contactEmail;
		public $docIdId;
		public $subject;
		public $isResolved;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $adminMessages = array();
		public $contactMessages = array();

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
			$this->linkedToContactId = NULL;
			$this->linkedToInvoiceId = NULL;
			$this->linkedToEstimateId = NULL;
			$this->linkedToQuoteRequestId = NULL;
			$this->contactName = NULL;
			$this->contactEmail = NULL;
			$this->docIdId = '';
			$this->subject = '';
			$this->isResolved = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->adminMessages = array();
			$this->contactMessages = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $contactServiceTicketId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('contactServiceTicket', '*', "WHERE contactServiceTicketId ='".$this->db->sanitize($contactServiceTicketId)."'");

			// If contactServiceTicketId already exists then set the set method type to UPDATE and fetch the values for the contactServiceTicket
			if ($fetch) {
				$this->contactServiceTicketId = $contactServiceTicketId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->linkedToContactId = $fetch[0]['linkedToContactId'];
				$this->linkedToInvoiceId = $fetch[0]['linkedToInvoiceId'];
				$this->linkedToEstimateId = $fetch[0]['linkedToEstimateId'];
				$this->linkedToQuoteRequestId = $fetch[0]['linkedToQuoteRequestId'];
				$this->contactName = $fetch[0]['contactName'];
				$this->contactEmail = $fetch[0]['contactEmail'];
				$this->docIdId = $fetch[0]['docIdId'];
				$this->subject = $fetch[0]['subject'];
				$this->isResolved = $fetch[0]['isResolved'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If contactServiceTicketId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new contactServiceTicketId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('contactServiceTicket', 'contactServiceTicketId');
				$this->contactServiceTicketId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbContactServiceTicketId = $this->contactServiceTicketId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// adminMessages
		public function pullAdminMessages($params = '') {
			$this->adminMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminContactServiceMessage', 'adminContactServiceMessageId', "WHERE contactServiceTicketId = '$this->dbContactServiceTicketId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->adminMessages, $row['adminContactServiceMessageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// contactMessages
		public function pullContactMessages($params = '') {
			$this->contactMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('contactContactServiceMessage', 'contactContactServiceMessageId', "WHERE contactServiceTicketId = '$this->dbContactServiceTicketId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->contactMessages, $row['contactContactServiceMessageId']);
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
				'contactServiceTicketId' => $this->db->sanitize($this->dbContactServiceTicketId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'linkedToContactId' => $this->db->sanitize($this->linkedToContactId),
				'linkedToInvoiceId' => $this->db->sanitize($this->linkedToInvoiceId),
				'linkedToEstimateId' => $this->db->sanitize($this->linkedToEstimateId),
				'linkedToQuoteRequestId' => $this->db->sanitize($this->linkedToQuoteRequestId),
				'contactName' => $this->db->sanitize($this->contactName),
				'contactEmail' => $this->db->sanitize($this->contactEmail),
				'docIdId' => $this->db->sanitize($this->docIdId),
				'subject' => $this->db->sanitize($this->subject),
				'isResolved' => $this->db->sanitize($this->isResolved),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('contactServiceTicket', $attributes, "WHERE contactServiceTicketId = '".$this->db->sanitize($this->dbContactServiceTicketId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('contactServiceTicket', $attributes)) {
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
			if (!$this->db->delete('contactServiceTicket', "WHERE contactServiceTicketId = '".$this->db->sanitize($this->dbContactServiceTicketId)."'", 1)) {
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
