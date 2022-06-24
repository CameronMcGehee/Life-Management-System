<?php

	class customerTag {

		private string $setType;
		private database $db;

		private string $dbCustomerTagId; // Used when updating the table incase the customerTagId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerTagId;
		public $businessId;
		public $name;
		public $description;
		public $color;
		public $imgFile;
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
			// Get a random color for the default, thanks to color randomizer from Chris Coyier: https://css-tricks.com/snippets/php/random-hex-color/
			$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
			$this->color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
			$this->imgFile = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerTagId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customerTag', '*', "WHERE customerTagId ='".$this->db->sanitize($customerTagId)."'");

			// If customerTagId already exists then set the set method type to UPDATE and fetch the values for the customerTag
			if ($fetch) {
				$this->customerTagId = $customerTagId;
				$this->businessId = $fetch[0]['businessId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->color = $fetch[0]['color'];
				$this->imgFile = $fetch[0]['imgFile'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerTagId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerTagId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerTag', 'customerTagId');
				$this->customerTagId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbCustomerTagId = $this->customerTagId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerTagId' => $this->db->sanitize($this->dbCustomerTagId),
				'businessId' => $this->db->sanitize($this->businessId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'color' => $this->db->sanitize($this->color),
				'imgFile' => $this->db->sanitize($this->imgFile),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerTag', $attributes, "WHERE customerTagId = ".$this->db->sanitize($this->dbCustomerTagId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerTag', $attributes)) {
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
			if (!$this->db->delete('customerTag', "WHERE customerTagId = '".$this->db->sanitize($this->dbCustomerTagId)."'", 1)) {
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
