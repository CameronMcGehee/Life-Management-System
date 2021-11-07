<?php

	class quoteRequest {

		private string $setType;
		private database $db;

		private string $dbQuoteRequestId; // Used when updating the table incase the quoteRequestId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $quoteRequestId;
		public $businessId;
		public $linkedToCustomerId;
		public $name;
		public $email;
		public $address1;
		public $address2;
		public $state;
		public $zipCode;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $services = array();

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
			// Default linkedToCustomerId to the currently selected business
			if (isset($_SESSION['ultiscape_customerId'])) {
				$this->linkedToCustomerId = $_SESSION['ultiscape_customerId'];
			} else {
				$this->linkedToCustomerId = NULL;
			}
			$this->name = NULL;
			$this->email = NULL;
			$this->address1 = NULL;
			$this->address2 = NULL;
			$this->state = NULL;
			$this->zipCode = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->services = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $quoteRequestId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('quoteRequest', '*', "WHERE quoteRequestId ='".$this->db->sanitize($quoteRequestId)."'");

			// If quoteRequestId already exists then set the set method type to UPDATE and fetch the values for the quoteRequest
			if ($fetch) {
				$this->quoteRequestId = $quoteRequestId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->name = $fetch[0]['name'];
				$this->email = $fetch[0]['email'];
				$this->address1 = $fetch[0]['address1'];
				$this->address2 = $fetch[0]['address2'];
				$this->state = $fetch[0]['state'];
				$this->zipCode = $fetch[0]['zipCode'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If quoteRequestId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new quoteRequestId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('quoteRequest', 'quoteRequestId');
				$this->quoteRequestId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbQuoteRequestId = $this->quoteRequestId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// services
		public function pullServices ($params = '') {
			$this->services = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('quoteRequestService', 'quoteRequestServiceId', "WHERE quoteResquestId = '$this->dbQuoteRequestId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->services, $row['quoteRequestServiceId']);
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
				'quoteRequestId' => $this->db->sanitize($this->dbQuoteRequestId),
				'businessId' => $this->db->sanitize($this->businessId),
				'name' => $this->db->sanitize($this->name),
				'email' => $this->db->sanitize($this->email),
				'address1' => $this->db->sanitize($this->address1),
				'address2' => $this->db->sanitize($this->address2),
				'state' => $this->db->sanitize($this->state),
				'zipCode' => $this->db->sanitize($this->zipCode),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('quoteRequest', $attributes, "WHERE quoteRequestId = '".$this->db->sanitize($this->dbQuoteRequestId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('quoteRequest', $attributes)) {
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
			if (!$this->db->delete('quoteRequest', "WHERE quoteRequestId = '".$this->db->sanitize($this->dbQuoteRequestId)."'", 1)) {
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
