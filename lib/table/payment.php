<?php

	class payment {

		private string $setType;
		private database $db;

		private string $dbPaymentId; // Used when updating the table incase the paymentId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $paymentId;
		public $workspaceId;
		public $linkedToInvoiceId;
		public $linkedToPaymentMethodId;
		public $contactId;
		public $methodName;
		public $methodPercentCut;
		public $methodAmountCut;
		public $amount;
		public $notes;
		public $excessWasAddedToCredit;
		public $dateTimeAdded;

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
			$this->linkedToInvoiceId = '';
			$this->linkedToPaymentMethodId = '';
			$this->contactId = '';
			$this->methodName = '';
			$this->methodPercentCut = '';
			$this->methodAmountCut = '';
			$this->amount = '0';
			$this->notes = NULL;
			$this->excessWasAddedToCredit = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

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

			// If paymentId already exists then set the set methodName type to UPDATE and fetch the values for the payment
			if ($fetch) {
				$this->paymentId = $paymentId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->linkedToInvoiceId = $fetch[0]['linkedToInvoiceId'];
				$this->linkedToPaymentMethodId = $fetch[0]['linkedToPaymentMethodId'];
				$this->contactId = $fetch[0]['contactId'];
				$this->methodName = $fetch[0]['methodName'];
				$this->methodPercentCut = $fetch[0]['methodPercentCut'];
				$this->methodAmountCut = $fetch[0]['methodAmountCut'];
				$this->amount = $fetch[0]['amount'];
				$this->notes = $fetch[0]['notes'];
				$this->excessWasAddedToCredit = $fetch[0]['excessWasAddedToCredit'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If paymentId does not exist then set the set methodName type to INSERT and inititialize default values
			} else {
				// Make a new paymentId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('payment', 'paymentId');
				$this->paymentId = $uuid->generatedId;
				
				$this->setToDefaults();

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
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'linkedToInvoiceId' => $this->db->sanitize($this->linkedToInvoiceId),
				'linkedToPaymentMethodId' => $this->db->sanitize($this->linkedToPaymentMethodId),
				'contactId' => $this->db->sanitize($this->contactId),
				'methodName' => $this->db->sanitize($this->methodName),
				'methodPercentCut' => $this->db->sanitize($this->methodPercentCut),
				'methodAmountCut' => $this->db->sanitize($this->methodAmountCut),
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

			$this->setToDefaults();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
