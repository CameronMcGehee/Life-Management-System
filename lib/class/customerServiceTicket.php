<?php

	class customerServiceTicket {

		private string $setType;
		private databaseManager $db;

		public string $dbCustomerServiceTicketId; // Used when updating the table incase the customerServiceTicketId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerServiceTicketId;
		public $businessId;
		public $linkedToCustomerId;
		public $linkedToInvoiceId;
		public $linkedToEstimateId;
		public $linkedToQuoteRequestId;
		public $customerName;
		public $customerEmail;
		public $docIdId;
		public $subject;
		public $isResolved;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerServiceTicketId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('customerServiceTicket', '*', "WHERE customerServiceTicketId ='".$this->db->sanitize($customerServiceTicketId)."'");

			// If customerServiceTicketId already exists then set the set method type to UPDATE and fetch the values for the customerServiceTicket
			if ($fetch) {
				$this->customerServiceTicketId = $customerServiceTicketId;
				$this->businessId = $fetch[0]['businessId'];
				$this->linkedToCustomerId = $fetch[0]['linkedToCustomerId'];
				$this->linkedToInvoiceId = $fetch[0]['linkedToInvoiceId'];
				$this->linkedToEstimateId = $fetch[0]['linkedToEstimateId'];
				$this->linkedToQuoteRequestId = $fetch[0]['linkedToQuoteRequestId'];
				$this->customerName = $fetch[0]['customerName'];
				$this->customerEmail = $fetch[0]['customerEmail'];
				$this->docIdId = $fetch[0]['docIdId'];
				$this->subject = $fetch[0]['subject'];
				$this->isResolved = $fetch[0]['isResolved'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerServiceTicketId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerServiceTicketId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerServiceTicket', 'customerServiceTicketId');
				$this->customerServiceTicketId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->linkedToCustomerId = NULL;
				$this->linkedToInvoiceId = NULL;
				$this->linkedToEstimateId = NULL;
				$this->linkedToQuoteRequestId = NULL;
				$this->customerName = NULL;
				$this->customerEmail = NULL;
				$this->docIdId = '';
				$this->subject = '';
				$this->isResolved = '0';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbCustomerServiceTicketId = $this->customerServiceTicketId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerServiceTicketId' => $this->db->sanitize($this->dbCustomerServiceTicketId),
				'businessId' => $this->db->sanitize($this->businessId),
				'linkedToCustomerId' => $this->db->sanitize($this->linkedToCustomerId),
				'linkedToInvoiceId' => $this->db->sanitize($this->linkedToInvoiceId),
				'linkedToEstimateId' => $this->db->sanitize($this->linkedToEstimateId),
				'linkedToQuoteRequestId' => $this->db->sanitize($this->linkedToQuoteRequestId),
				'customerName' => $this->db->sanitize($this->customerName),
				'customerEmail' => $this->db->sanitize($this->customerEmail),
				'docIdId' => $this->db->sanitize($this->docIdId),
				'subject' => $this->db->sanitize($this->subject),
				'isResolved' => $this->db->sanitize($this->isResolved),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerServiceTicket', $attributes, "WHERE customerServiceTicketId = '".$this->db->sanitize($this->dbCustomerServiceTicketId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerServiceTicket', $attributes)) {
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
			if (!$this->db->delete('customerServiceTicket', "WHERE customerServiceTicketId = '".$this->db->sanitize($this->dbCustomerServiceTicketId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('customerServiceTicket', 'customerServiceTicketId');
			$this->customerServiceTicketId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->linkedToCustomerId = NULL;
			$this->linkedToInvoiceId = NULL;
			$this->linkedToEstimateId = NULL;
			$this->linkedToQuoteRequestId = NULL;
			$this->customerName = NULL;
			$this->customerEmail = NULL;
			$this->docIdId = '';
			$this->subject = '';
			$this->isResolved = '0';
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
