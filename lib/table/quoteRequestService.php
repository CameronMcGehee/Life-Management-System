<?php

	class quoteRequestService {

		private string $setType;
		private database $db;

		private string $dbQuoteRequestServiceId; // Used when updating the table incase the quoteRequestServiceId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $quoteRequestServiceId;
		public $businessId;
		public $quoteRequestId;
		public $linkedToServiceListingId;
		public $currentName;
		public $currentDescription;
		public $currentImgFile;
		public $currentPrice;
		public $currentMinPrice;
		public $currentMaxPrice;
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
			$this->quoteRequestId = '';
			$this->linkedToServiceListingId = '';
			$this->currentName = '';
			$this->currentDescription = NULL;
			$this->currentImgFile = NULL;
			$this->currentPrice = NULL;
			$this->currentMinPrice = NULL;
			$this->currentMaxPrice = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $quoteRequestServiceId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('quoteRequestService', '*', "WHERE quoteRequestServiceId ='".$this->db->sanitize($quoteRequestServiceId)."'");

			// If quoteRequestServiceId already exists then set the set method type to UPDATE and fetch the values for the quoteRequestService
			if ($fetch) {
				$this->quoteRequestServiceId = $quoteRequestServiceId;
				$this->businessId = $fetch[0]['businessId'];
				$this->quoteRequestId = $fetch[0]['quoteRequestId'];
				$this->linkedToServiceListingId = $fetch[0]['linkedToServiceListingId'];
				$this->currentName = $fetch[0]['currentName'];
				$this->currentDescription = $fetch[0]['currentDescription'];
				$this->currentImgFile = $fetch[0]['currentImgFile'];
				$this->currentPrice = $fetch[0]['currentPrice'];
				$this->currentMinPrice = $fetch[0]['currentMinPrice'];
				$this->currentMaxPrice = $fetch[0]['currentMaxPrice'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If quoteRequestServiceId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new quoteRequestServiceId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('quoteRequestService', 'quoteRequestServiceId');
				$this->quoteRequestServiceId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbQuoteRequestServiceId = $this->quoteRequestServiceId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'quoteRequestServiceId' => $this->db->sanitize($this->dbQuoteRequestServiceId),
				'businessId' => $this->db->sanitize($this->businessId),
				'quoteRequestId' => $this->db->sanitize($this->quoteRequestId),
				'linkedToServiceListingId' => $this->db->sanitize($this->linkedToServiceListingId),
				'currentName' => $this->db->sanitize($this->currentName),
				'currentDescription' => $this->db->sanitize($this->currentDescription),
				'currentImgFile' => $this->db->sanitize($this->currentImgFile),
				'currentPrice' => $this->db->sanitize($this->currentPrice),
				'currentMinPrice' => $this->db->sanitize($this->currentMinPrice),
				'currentMaxPrice' => $this->db->sanitize($this->currentMaxPrice),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('quoteRequestService', $attributes, "WHERE quoteRequestServiceId = '".$this->db->sanitize($this->dbQuoteRequestServiceId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('quoteRequestService', $attributes)) {
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
			if (!$this->db->delete('quoteRequestService', "WHERE quoteRequestServiceId = '".$this->db->sanitize($this->dbQuoteRequestServiceId)."'", 1)) {
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
