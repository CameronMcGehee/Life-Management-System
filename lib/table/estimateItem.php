<?php

	class estimateItem {

		private string $setType;
		private database $db;

		private string $dbEstimateItemId; // Used when updating the table incase the estimateItemId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $estimateItemId;
		public $businessId;
		public $estimateId;
		public $name;
		public $price;
		public $taxIsPercent;
		public $tax;
		public $quantity;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $estimateItemId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('estimateItem', '*', "WHERE estimateItemId ='".$this->db->sanitize($estimateItemId)."'");

			// If estimateItemId already exists then set the set method type to UPDATE and fetch the values for the estimateItem
			if ($fetch) {
				$this->estimateItemId = $estimateItemId;
				$this->businessId = $fetch[0]['businessId'];
				$this->estimateId = $fetch[0]['estimateId'];
				$this->name = $fetch[0]['name'];
				$this->price = $fetch[0]['price'];
				$this->taxIsPercent = $fetch[0]['taxIsPercent'];
				$this->tax = $fetch[0]['tax'];
				$this->quantity = $fetch[0]['quantity'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If estimateItemId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new estimateItemId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('estimateItem', 'estimateItemId');
				$this->estimateItemId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->estimateId = '';
				$this->name = '';
				$this->price = '0';
				$this->taxIsPercent = '0';
				$this->tax = '0';
				$this->quantity = '1';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbEstimateItemId = $this->estimateItemId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'estimateItemId' => $this->db->sanitize($this->dbEstimateItemId),
				'businessId' => $this->db->sanitize($this->businessId),
				'estimateId' => $this->db->sanitize($this->estimateId),
				'name' => $this->db->sanitize($this->name),
				'price' => $this->db->sanitize($this->price),
				'taxIsPercent' => $this->db->sanitize($this->taxIsPercent),
				'tax' => $this->db->sanitize($this->tax),
				'quantity' => $this->db->sanitize($this->quantity),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('estimateItem', $attributes, "WHERE estimateItemId = '".$this->db->sanitize($this->dbEstimateItemId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('estimateItem', $attributes)) {
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
			if (!$this->db->delete('estimateItem', "WHERE estimateItemId = '".$this->db->sanitize($this->dbEstimateItemId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('estimateItem', 'estimateItemId');
			$this->estimateItemId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->estimateId = '';
			$this->name = '';
			$this->price = '0';
			$this->taxIsPercent = '0';
			$this->tax = '0';
			$this->quantity = '1';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			// (No arrays)

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
