<?php

	class payrollSatisfaction {

		private string $setType;
		private database $db;

		private string $dbPayrollSatisfactionId; // Used when updating the table incase the payrollSatisfactionId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $payrollSatisfactionId;
		public $workspaceId;
		public $staffId;
		public $linkedToPayrollDueId;
		public $method;
		public $amount;
		public $notes;
		public $excessWasAddedToAdvancePay;
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
			$this->staffId = '';
			$this->linkedToPayrollDueId = NULL;
			$this->method = NULL;
			$this->amount = '0';
			$this->notes = NULL;
			$this->excessWasAddedToAdvancePay = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $payrollSatisfactionId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('payrollSatisfaction', '*', "WHERE payrollSatisfactionId ='".$this->db->sanitize($payrollSatisfactionId)."'");

			// If payrollSatisfactionId already exists then set the set method type to UPDATE and fetch the values for the payrollSatisfaction
			if ($fetch) {
				$this->payrollSatisfactionId = $payrollSatisfactionId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->staffId = $fetch[0]['staffId'];
				$this->linkedToPayrollDueId = $fetch[0]['linkedToPayrollDueId'];
				$this->method = $fetch[0]['method'];
				$this->amount = $fetch[0]['amount'];
				$this->notes = $fetch[0]['notes'];
				$this->excessWasAddedToAdvancePay = $fetch[0]['excessWasAddedToAdvancePay'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If payrollSatisfactionId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new payrollSatisfactionId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('payrollSatisfaction', 'payrollSatisfactionId');
				$this->payrollSatisfactionId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbPayrollSatisfactionId = $this->payrollSatisfactionId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'payrollSatisfactionId' => $this->db->sanitize($this->dbPayrollSatisfactionId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'staffId' => $this->db->sanitize($this->staffId),
				'linkedToPayrollDueId' => $this->db->sanitize($this->linkedToPayrollDueId),
				'method' => $this->db->sanitize($this->method),
				'amount' => $this->db->sanitize($this->amount),
				'notes' => $this->db->sanitize($this->notes),
				'excessWasAddedToAdvancePay' => $this->db->sanitize($this->excessWasAddedToAdvancePay),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('payrollSatisfaction', $attributes, "WHERE payrollSatisfactionId = ".$this->db->sanitize($this->dbPayrollSatisfactionId), 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('payrollSatisfaction', $attributes)) {
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
			if (!$this->db->delete('payrollSatisfaction', "WHERE payrollSatisfactionId = '".$this->db->sanitize($this->dbPayrollSatisfactionId)."'", 1)) {
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
