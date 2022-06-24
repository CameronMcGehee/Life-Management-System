<?php

	class serviceListing {

		private string $setType;
		private database $db;

		private string $dbServiceListingId; // Used when updating the table incase the serviceListingId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $serviceListingId;
		public $businessId;
		public $name;
		public $description;
		public $imgFile;
		public $price;
		public $minPrice;
		public $maxPrice;
		public $isRequestable;
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
			$this->name = '';
			$this->description = NULL;
			$this->imgFile = NULL;
			$this->price = NULL;
			$this->minPrice = NULL;
			$this->maxPrice = NULL;
			$this->isRequestable = '1';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $serviceListingId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('serviceListing', '*', "WHERE serviceListingId ='".$this->db->sanitize($serviceListingId)."'");

			// If serviceListingId already exists then set the set method type to UPDATE and fetch the values for the serviceListing
			if ($fetch) {
				$this->serviceListingId = $serviceListingId;
				$this->businessId = $fetch[0]['businessId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->imgFile = $fetch[0]['imgFile'];
				$this->price = $fetch[0]['price'];
				$this->minPrice = $fetch[0]['minPrice'];
				$this->maxPrice = $fetch[0]['maxPrice'];
				$this->isRequestable = $fetch[0]['isRequestable'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If serviceListingId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new serviceListingId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('serviceListing', 'serviceListingId');
				$this->serviceListingId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbServiceListingId = $this->serviceListingId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'serviceListingId' => $this->db->sanitize($this->dbServiceListingId),
				'businessId' => $this->db->sanitize($this->businessId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'imgFile' => $this->db->sanitize($this->imgFile),
				'price' => $this->db->sanitize($this->price),
				'minPrice' => $this->db->sanitize($this->minPrice),
				'maxPrice' => $this->db->sanitize($this->maxPrice),
				'isRequestable' => $this->db->sanitize($this->isRequestable),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('serviceListing', $attributes, "WHERE serviceListingId = '".$this->db->sanitize($this->dbServiceListingId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('serviceListing', $attributes)) {
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
			if (!$this->db->delete('serviceListing', "WHERE serviceListingId = '".$this->db->sanitize($this->dbServiceListingId)."'", 1)) {
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
