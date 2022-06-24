<?php

	class paymentMethod {

		private string $setType;
		private database $db;

		private string $dbPaymentMethodId; // Used when updating the table incase the paymentMethodId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $paymentMethodId;
		public $businessId;
		public $name;
		public $percentCut;
		public $amountCut;
		public $notes;
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
			$this->percentCut = '0';
			$this->amountCut = '0';
			$this->notes = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $paymentMethodId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('paymentMethod', '*', "WHERE paymentMethodId ='".$this->db->sanitize($paymentMethodId)."'");

			// If paymentMethodId already exists then set the set name type to UPDATE and fetch the values for the paymentMethod
			if ($fetch) {
				$this->paymentMethodId = $paymentMethodId;
				$this->businessId = $fetch[0]['businessId'];
				$this->name = $fetch[0]['name'];
				$this->percentCut = $fetch[0]['percentCut'];
				$this->amountCut = $fetch[0]['amountCut'];
				$this->notes = $fetch[0]['notes'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If paymentMethodId does not exist then set the set name type to INSERT and inititialize default values
			} else {
				// Make a new paymentMethodId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('paymentMethod', 'paymentMethodId');
				$this->paymentMethodId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbPaymentMethodId = $this->paymentMethodId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'paymentMethodId' => $this->db->sanitize($this->dbPaymentMethodId),
				'businessId' => $this->db->sanitize($this->businessId),
				'name' => $this->db->sanitize($this->name),
				'percentCut' => $this->db->sanitize($this->percentCut),
				'amountCut' => $this->db->sanitize($this->amountCut),
				'notes' => $this->db->sanitize($this->notes),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('paymentMethod', $attributes, "WHERE paymentMethodId = '".$this->db->sanitize($this->dbPaymentMethodId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('paymentMethod', $attributes)) {
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
			if (!$this->db->delete('paymentMethod', "WHERE paymentMethodId = '".$this->db->sanitize($this->dbPaymentMethodId)."'", 1)) {
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
