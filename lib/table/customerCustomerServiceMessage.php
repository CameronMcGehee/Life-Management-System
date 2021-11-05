<?php

	class customerCustomerServiceMessage {

		private string $setType;
		private database $db;

		private string $dbCustomerCustomerServiceMessageId; // Used when updating the table incase the customerCustomerServiceMessageId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $customerCustomerServiceMessageId;
		public $businessId;
		public $customerId;
		public $customerServiceTicketId;
		public $message;
		public $isReadByCustomer;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $customerCustomerServiceMessageId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('customerCustomerServiceMessage', '*', "WHERE customerCustomerServiceMessageId ='".$this->db->sanitize($customerCustomerServiceMessageId)."'");

			// If customerCustomerServiceMessageId already exists then set the set method type to UPDATE and fetch the values for the customerCustomerServiceMessage
			if ($fetch) {
				$this->customerCustomerServiceMessageId = $customerCustomerServiceMessageId;
				$this->businessId = $fetch[0]['businessId'];
				$this->customerId = $fetch[0]['customerId'];
				$this->customerServiceTicketId = $fetch[0]['customerServiceTicketId'];
				$this->message = $fetch[0]['message'];
				$this->isReadByCustomer = $fetch[0]['isReadByCustomer'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If customerCustomerServiceMessageId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new customerCustomerServiceMessageId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('customerCustomerServiceMessage', 'customerCustomerServiceMessageId');
				$this->customerCustomerServiceMessageId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->customerId = '';
				$this->customerServiceTicketId = '';
				$this->message = '';
				$this->isReadByCustomer = '0';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbCustomerCustomerServiceMessageId = $this->customerCustomerServiceMessageId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'customerCustomerServiceMessageId' => $this->db->sanitize($this->dbCustomerCustomerServiceMessageId),
				'businessId' => $this->db->sanitize($this->businessId),
				'customerId' => $this->db->sanitize($this->customerId),
				'customerServiceTicketId' => $this->db->sanitize($this->customerServiceTicketId),
				'message' => $this->db->sanitize($this->message),
				'isReadByCustomer' => $this->db->sanitize($this->isReadByCustomer),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('customerCustomerServiceMessage', $attributes, "WHERE customerCustomerServiceMessageId = '".$this->db->sanitize($this->dbCustomerCustomerServiceMessageId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('customerCustomerServiceMessage', $attributes)) {
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
			if (!$this->db->delete('customerCustomerServiceMessage', "WHERE customerCustomerServiceMessageId = '".$this->db->sanitize($this->dbCustomerCustomerServiceMessageId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('customerCustomerServiceMessage', 'customerCustomerServiceMessageId');
			$this->customerCustomerServiceMessageId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->customerId = '';
			$this->customerServiceTicketId = '';
			$this->message = '';
			$this->isReadByCustomer = '0';
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
