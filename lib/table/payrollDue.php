<?php

	class payrollDue {

		private string $setType;
		private database $db;

		private string $dbPayrollDueId; // Used when updating the table incase the payrollDueId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $payrollDueId;
		public $businessId;
		public $staffId;
		public $linkedToTimeLogId;
		public $linkedToJobCompletedId;
		public $amount;
		public $notes;
		public $isManualPaid;
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
			$this->staffId = '';
			$this->linkedToTimeLogId = NULL;
			$this->linkedToJobCompletedId = NULL;
			$this->amount = '0';
			$this->notes = NULL;
			$this->isManualPaid = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $payrollDueId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('payrollDue', '*', "WHERE payrollDueId ='".$this->db->sanitize($payrollDueId)."'");

			// If payrollDueId already exists then set the set method type to UPDATE and fetch the values for the payrollDue
			if ($fetch) {
				$this->payrollDueId = $payrollDueId;
				$this->businessId = $fetch[0]['businessId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->linkedToTimeLogId = $fetch[0]['linkedToTimeLogId'];
				$this->linkedToJobCompletedId = $fetch[0]['linkedToJobCompletedId'];
				$this->amount = $fetch[0]['amount'];
				$this->notes = $fetch[0]['notes'];
				$this->isManualPaid = $fetch[0]['isManualPaid'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If payrollDueId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new payrollDueId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('payrollDue', 'payrollDueId');
				$this->payrollDueId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbPayrollDueId = $this->payrollDueId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'payrollDueId' => $this->db->sanitize($this->dbPayrollDueId),
				'businessId' => $this->db->sanitize($this->businessId),
				'staffId' => $this->db->sanitize($this->staffId),
				'linkedToTimeLogId' => $this->db->sanitize($this->linkedToTimeLogId),
				'linkedToJobCompletedId' => $this->db->sanitize($this->linkedToJobCompletedId),
				'amount' => $this->db->sanitize($this->amount),
				'notes' => $this->db->sanitize($this->notes),
				'isManualPaid' => $this->db->sanitize($this->isManualPaid),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('payrollDue', $attributes, "WHERE payrollDueId = ".$this->db->sanitize($this->dbPayrollDueId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('payrollDue', $attributes)) {
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
			if (!$this->db->delete('payrollDue', "WHERE payrollDueId = '".$this->db->sanitize($this->dbPayrollDueId)."'", 1)) {
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
