<?php

	class property {

		private string $setType;
		private database $db;

		private string $dbPropertyId; // Used when updating the table incase the propertyId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $propertyId;
		public $businessId;
		public $customerId;
		public $address1;
		public $address2;
		public $city;
		public $state;
		public $zipCode;
		public $lawnSize;
		public $mulchQuantity;
		public $pricePerMow;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $chemicalApplications = array();
		public $jobCompleteds = array();
		public $jobRecurrings = array();
		public $jobSingulars = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $propertyId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('property', '*', "WHERE propertyId ='".$this->db->sanitize($propertyId)."'");

			// If propertyId already exists then set the set address2 type to UPDATE and fetch the values for the property
			if ($fetch) {
				$this->propertyId = $propertyId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->address1 = $fetch[0]['address1'];
				$this->address2 = $fetch[0]['address2'];
				$this->city = $fetch[0]['city'];
				$this->state = $fetch[0]['state'];
				$this->zipCode = $fetch[0]['zipCode'];
				$this->lawnSize = $fetch[0]['lawnSize'];
				$this->mulchQuantity = $fetch[0]['mulchQuantity'];
				$this->pricePerMow = $fetch[0]['pricePerMow'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If propertyId does not exist then set the set address2 type to INSERT and inititialize default values
			} else {
				// Make a new propertyId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('property', 'propertyId');
				$this->propertyId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->customerId = '';
				$this->address1 = '';
				$this->address2 = NULL;
				$this->city = NULL;
				$this->state = NULL;
				$this->zipCode = NULL;
				$this->lawnSize = NULL;
				$this->mulchQuantity = NULL;
				$this->pricePerMow = NULL;
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbPropertyId = $this->propertyId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// chemicalApplications
		public function pullChemicalApplications($params = '') {
			$this->chemicalApplications = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('chemicalApplication', 'chemicalApplicationId', "WHERE propertyId = '$this->dbPropertyId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->chemicalApplications, $row['chemicalApplicationId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobCompleteds
		public function pullJobCompleteds($params = '') {
			$this->jobCompleteds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobCompleted', 'jobCompletedId', "WHERE linkedToPropertyId = '$this->dbPropertyId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobCompleteds, $row['jobCompletedId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobRecurrings
		public function pullJobRecurrings($params = '') {
			$this->jobRecurrings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobRecurring', 'jobRecurringId', "WHERE linkedToPropertyId = '$this->dbPropertyId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobRecurrings, $row['jobRecurringId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// jobSingulars
		public function pullJobSingulars($params = '') {
			$this->jobSingulars = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('jobSingular', 'jobSingularId', "WHERE linkedToPropertyId = '$this->dbPropertyId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->jobSingulars, $row['jobSingularId']);
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
				'propertyId' => $this->db->sanitize($this->dbPropertyId),
				'businessId' => $this->db->sanitize($this->businessId),
				'customerId' => $this->db->sanitize($this->customerId),
				'address1' => $this->db->sanitize($this->address1),
				'address2' => $this->db->sanitize($this->address2),
				'city' => $this->db->sanitize($this->city),
				'state' => $this->db->sanitize($this->state),
				'zipCode' => $this->db->sanitize($this->zipCode),
				'lawnSize' => $this->db->sanitize($this->lawnSize),
				'mulchQuantity' => $this->db->sanitize($this->mulchQuantity),
				'pricePerMow' => $this->db->sanitize($this->pricePerMow),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('property', $attributes, "WHERE propertyId = '".$this->db->sanitize($this->dbPropertyId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('property', $attributes)) {
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
			if (!$this->db->delete('property', "WHERE propertyId = '".$this->db->sanitize($this->dbPropertyId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('property', 'propertyId');
			$this->propertyId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->customerId = '';
			$this->address1 = '';
			$this->address2 = NULL;
			$this->city = NULL;
			$this->state = NULL;
			$this->zipCode = NULL;
			$this->lawnSize = NULL;
			$this->mulchQuantity = NULL;
			$this->pricePerMow = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->chemicalApplications = array();
			$this->jobCompleteds = array();
			$this->jobRecurrings = array();
			$this->jobSingulars = array();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
