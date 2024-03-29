<?php

	class invoice {

		private string $setType;
		private database $db;

		private string $dbInvoiceId; // Used when updating the table incase the invoiceId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $invoiceId;
		public $workspaceId;
		public $docIdId;
		public $contactId;
		public $discountIsPercent;
		public $discount;
		public $customCalendarEventDetails;
		public $comments;
		public $privateNotes;
		public $isViewed;
		public $isEmailed;
		public $isOverdueNotified;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $items = array();
		public $payments = array();

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
			$this->docIdId = '';
			$this->contactId = NULL;
			$this->discountIsPercent = '0';
			$this->discount = '0';
			$this->customCalendarEventDetails = NULL;
			$this->comments = NULL;
			$this->privateNotes = NULL;
			$this->isViewed = '0';
			$this->isEmailed = '0';
			$this->isOverdueNotified = '0';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->items = array();
			$this->payments = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $invoiceId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			require_once dirname(__FILE__)."/../table/docId.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('invoice', '*', "WHERE invoiceId ='".$this->db->sanitize($invoiceId)."'");

			// If invoiceId already exists then set the set method type to UPDATE and fetch the values for the invoice
			if ($fetch) {
				$this->invoiceId = $invoiceId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->docIdId = $fetch[0]['docIdId'];
				$this->contactId = $fetch[0]['contactId'];
				$this->discountIsPercent = $fetch[0]['discountIsPercent'];
				$this->discount = $fetch[0]['discount'];
				$this->customCalendarEventDetails = $fetch[0]['customCalendarEventDetails'];
				$this->comments = $fetch[0]['comments'];
				$this->privateNotes = $fetch[0]['privateNotes'];
				$this->isViewed = $fetch[0]['isViewed'];
				$this->isEmailed = $fetch[0]['isEmailed'];
				$this->isOverdueNotified = $fetch[0]['isOverdueNotified'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If invoiceId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new invoiceId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('invoice', 'invoiceId');
				$this->invoiceId = $uuid->generatedId;

				$this->setToDefaults();

				// Make a new docId
				require_once dirname(__FILE__)."/docId.php";
				$docId = new docId($this->workspaceId);
				$this->docIdId = $docId->docIdId;

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbInvoiceId = $this->invoiceId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'invoiceId' => $this->db->sanitize($this->dbInvoiceId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'docIdId' => $this->db->sanitize($this->docIdId),
				'contactId' => $this->db->sanitize($this->contactId),
				'discountIsPercent' => $this->db->sanitize($this->discountIsPercent),
				'discount' => $this->db->sanitize($this->discount),
				'customCalendarEventDetails' => $this->db->sanitize($this->customCalendarEventDetails),
				'comments' => $this->db->sanitize($this->comments),
				'privateNotes' => $this->db->sanitize($this->privateNotes),
				'isViewed' => $this->db->sanitize($this->isViewed),
				'isEmailed' => $this->db->sanitize($this->isEmailed),
				'isOverdueNotified' => $this->db->sanitize($this->isOverdueNotified),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('invoice', $attributes, "WHERE invoiceId = '".$this->db->sanitize($this->dbInvoiceId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('invoice', $attributes)) {
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
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// items
		public function pullItems($params = '') {
			$this->items = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('invoiceItem', 'invoiceItemId', "WHERE invoiceId = '$this->dbInvoiceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->items, $row['invoiceItemId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payments
		public function pullPayments($params = '') {
			$this->payments = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payment', 'paymentId', "WHERE linkedToInvoiceId = '$this->dbInvoiceId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payments, $row['paymentId']);
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
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('invoice', "WHERE invoiceId = '".$this->db->sanitize($this->dbInvoiceId)."'", 1)) {
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
