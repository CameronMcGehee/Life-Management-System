<?php

	class payment {

		private string $setType;
		private database $db;

		private string $dbPaymentId; // Used when updating the table incase the paymentId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $paymentId;
		public $businessId;
		public $linkedToInvoiceId;
		public $customerId;
		public $method;
		public $amount;
		public $notes;
		public $excessWasAddedToCredit;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $paymentId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('payment', '*', "WHERE paymentId ='".$this->db->sanitize($paymentId)."'");

			// If paymentId already exists then set the set method type to UPDATE and fetch the values for the payment
			if ($fetch) {
				$this->paymentId = $paymentId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToInvoiceId = $fetch[0]['linkedToInvoiceId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->method = $fetch[0]['method'];
				$this->amount = $fetch[0]['amount'];
				$this->notes = $fetch[0]['notes'];
				$this->excessWasAddedToCredit = $fetch[0]['excessWasAddedToCredit'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If paymentId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new paymentId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('payment', 'paymentId');
				$this->paymentId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->linkedToInvoiceId = '';
				$this->customerId = '';
				$this->method = '';
				$this->amount = '0';
				$this->notes = NULL;
				$this->excessWasAddedToCredit = '0';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbPaymentId = $this->paymentId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'paymentId' => $this->db->sanitize($this->dbPaymentId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToInvoiceId' => $this->db->sanitize($this->linkedToInvoiceId),
				'customerId' => $this->db->sanitize($this->customerId),
				'method' => $this->db->sanitize($this->method),
				'amount' => $this->db->sanitize($this->amount),
				'notes' => $this->db->sanitize($this->notes),
				'excessWasAddedToCredit' => $this->db->sanitize($this->excessWasAddedToCredit),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('payment', $attributes, "WHERE paymentId = '".$this->db->sanitize($this->dbPaymentId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('payment', $attributes)) {
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
			if (!$this->db->delete('payment', "WHERE paymentId = '".$this->db->sanitize($this->dbPaymentId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('payment', 'paymentId');
			$this->paymentId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->linkedToInvoiceId = '';
			$this->customerId = '';
			$this->method = '';
			$this->amount = '0';
			$this->notes = NULL;
			$this->excessWasAddedToCredit = '0';
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
