<?php

	class docId {

		private string $setType;
		private database $db;

		private string $dbDocIdId; // Used when updating the table incase the docIdId has been changed after instantiation
		private string $selectedBusinessId; // Used when generating new docIds
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $docIdId;
		public $businessId;
		public $incrementalId;
		public $randomId;
		public $dateTimeAdded;

		// linkedItem[] will store whatever the docId is linked to [table/datatype, id]
		public $linkedItem = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $businessId, string $docIdId = '') {

			// Set the businessId so that we can generate new ids
			$this->selectedBusinessId = $businessId;

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('docId', '*', "WHERE docIdId ='".$this->db->sanitize($docIdId)."'");

			// If docIdId already exists then set the set method type to UPDATE and fetch the values for the docId
			if ($fetch) {
				$this->docIdId = $docIdId;
				$this->businessId = $fetch[0]['businessId'];
				$this->incrementalId = $fetch[0]['incrementalId'];
				$this->randomId = $fetch[0]['randomId'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If docIdId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new docIdId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('docId', 'docIdId');
				$this->docIdId = $uuid->generatedId;

				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->incrementalId = '';
				$this->randomId = '';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbDocIdId = $this->docIdId;

			if ($existed) {
				// If the docId existed, fill the linkedItem array with whatever the docId is linked to
				$this->pullLinkedItem();
			} elseif ($this->selectedBusinessId != '') {
				$this->generateIncrementalId();
				$this->generateRandomId();
			}
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// linkedItem
		public function pullLinkedItem () {
			$this->linkedItem = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}

			// Find what item has this docIdId
			// docIds can be linked to either a customerServiceMessage, a fileUpload, an invoice or an estimate
			$fetch = $this->db->select('customerServiceMessage', 'customerServiceMessageId', "WHERE docIdId = '$this->dbDocIdId'");
			if ($fetch) {
				array_push($this->linkedItem, array('customerServiceMessage', $fetch[0]['customerServiceMessageId']));
				return;
			}
			$fetch = $this->db->select('fileUpload', 'fileUploadId', "WHERE docIdId = '$this->dbDocIdId'");
			if ($fetch) {
				array_push($this->linkedItem, array('fileUpload', $fetch[0]['fileUploadId']));
				return;
			}
			$fetch = $this->db->select('invoice', 'invoiceId', "WHERE docIdId = '$this->dbDocIdId'");
			if ($fetch) {
				array_push($this->linkedItem, array('invoice', $fetch[0]['invoiceId']));
				return;
			}
			$fetch = $this->db->select('estimate', 'estimateId', "WHERE docIdId = '$this->dbDocIdId'");
			if ($fetch) {
				array_push($this->linkedItem, array('estimate', $fetch[0]['estimateId']));
				return;
			}
			$this->linkedItem = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Regenerate id functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function generateIncrementalId() {
			// For incremental id, find the last highest id for this business and then set this one to one above that
			$fetch = $this->db->select('docId', 'incrementalId', "WHERE businessId = '".$this->db->sanitize($this->selectedBusinessId)."' ORDER BY incrementalId DESC LIMIT 1");
			if (!$fetch) {
				$newIncrementalId = 1;
			} else {
				$newIncrementalId = (int)$fetch[0]['incrementalId'] + 1;
			}
			$this->incrementalId = (string)$newIncrementalId;
			return true;
		}
		public function generateRandomId() {
			// for random id, generate a random 5 number id until it doesn't exist already
			$newRandomId = (string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9);
			while ($this->db->select('docId', 'randomId', "WHERE businessId = '".$this->db->sanitize($this->selectedBusinessId)."' AND randomId = '$newRandomId'")) {
				$newRandomId = (string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9);
			}
			$this->randomId = (string)$newRandomId;
			return true;
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'docIdId' => $this->db->sanitize($this->dbDocIdId),
				'businessId' => $this->db->sanitize($this->businessId),
				'incrementalId' => $this->db->sanitize($this->incrementalId),
				'randomId' => $this->db->sanitize($this->randomId),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('docId', $attributes, "WHERE docIdId = '".$this->db->sanitize($this->dbDocIdId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('docId', $attributes)) {
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
			if (!$this->db->delete('docId', "WHERE docIdId = '".$this->db->sanitize($this->dbDocIdId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('docId', 'docIdId');
			$this->docIdId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->incrementalId = '';
			$this->randomId = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->linkedItem = array();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			// Generate new ids
			if ($this->businessId != '') {
				$this->generateIncrementalId();
				$this->generateRandomId();
			}

			return true;
		}
	}

?>