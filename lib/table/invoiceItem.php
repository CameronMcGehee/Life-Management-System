<?php

	class invoiceItem {

		private string $setType;
		private database $db;

		private string $dbInvoiceItemId; // Used when updating the table incase the invoiceItemId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $invoiceItemId;
		public $workspaceId;
		public $invoiceId;
		public $name;
		public $price;
		public $taxIsPercent;
		public $tax;
		public $quantity;
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
			$this->invoiceId = '';
			$this->name = '';
			$this->price = '0';
			$this->taxIsPercent = '0';
			$this->tax = '0';
			$this->quantity = '1';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $invoiceItemId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('invoiceItem', '*', "WHERE invoiceItemId ='".$this->db->sanitize($invoiceItemId)."'");

			// If invoiceItemId already exists then set the set method type to UPDATE and fetch the values for the invoiceItem
			if ($fetch) {
				$this->invoiceItemId = $invoiceItemId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->invoiceId = $fetch[0]['invoiceId'];
				$this->name = $fetch[0]['name'];
				$this->price = $fetch[0]['price'];
				$this->taxIsPercent = $fetch[0]['taxIsPercent'];
				$this->tax = $fetch[0]['tax'];
				$this->quantity = $fetch[0]['quantity'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If invoiceItemId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new invoiceItemId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('invoiceItem', 'invoiceItemId');
				$this->invoiceItemId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbInvoiceItemId = $this->invoiceItemId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'invoiceItemId' => $this->db->sanitize($this->dbInvoiceItemId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'invoiceId' => $this->db->sanitize($this->invoiceId),
				'name' => $this->db->sanitize($this->name),
				'price' => $this->db->sanitize($this->price),
				'taxIsPercent' => $this->db->sanitize($this->taxIsPercent),
				'tax' => $this->db->sanitize($this->tax),
				'quantity' => $this->db->sanitize($this->quantity),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('invoiceItem', $attributes, "WHERE invoiceItemId = '".$this->db->sanitize($this->dbInvoiceItemId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('invoiceItem', $attributes)) {
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
			if (!$this->db->delete('invoiceItem', "WHERE invoiceItemId = '".$this->db->sanitize($this->dbInvoiceItemId)."'", 1)) {
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
