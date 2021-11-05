<?php

	class adminCustomerServiceMessage {

		private string $setType;
		private database $db;

		private string $dbAdminCustomerServiceMessageId; // Used when updating the table incase the adminCustomerServiceMessageId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $adminCustomerServiceMessageId;
		public $businessId;
		public $adminId;
		public $customerServiceTicketId;
		public $message;
		public $isReadByCustomer;
		public $dateTimeAdded;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminCustomerServiceMessageId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('adminCustomerServiceMessage', '*', "WHERE adminCustomerServiceMessageId ='".$this->db->sanitize($adminCustomerServiceMessageId)."'");

			// If adminCustomerServiceMessageId already exists then set the set method type to UPDATE and fetch the values for the adminCustomerServiceMessage
			if ($fetch) {
				$this->adminCustomerServiceMessageId = $adminCustomerServiceMessageId;
				$this->businessId = $fetch[0]['businessId'];
				$this->adminId = $fetch[0]['adminId'];
				$this->customerServiceTicketId = $fetch[0]['customerServiceTicketId'];
				$this->message = $fetch[0]['message'];
				$this->isReadByCustomer = $fetch[0]['isReadByCustomer'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If adminCustomerServiceMessageId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new adminCustomerServiceMessageId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('adminCustomerServiceMessage', 'adminCustomerServiceMessageId');
				$this->adminCustomerServiceMessageId = $uuid->generatedId;
				// Default businessId to the currently selected business
				if (isset($_SESSION['ultiscape_businessId'])) {
					$this->businessId = $_SESSION['ultiscape_businessId'];
				} else {
					$this->businessId = '';
				}
				$this->adminId = '';
				$this->customerServiceTicketId = '';
				$this->message = '';
				$this->isReadByCustomer = '0';
				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->$dbAdminCustomerServiceMessageId = $this->adminCustomerServiceMessageId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'adminCustomerServiceMessageId' => $this->db->sanitize($this->dbAdminCustomerServiceMessageId),
				'businessId' => $this->db->sanitize($this->businessId),
				'adminId' => $this->db->sanitize($this->adminId),
				'customerServiceTicketId' => $this->db->sanitize($this->customerServiceTicketId),
				'message' => $this->db->sanitize($this->message),
				'isReadByCustomer' => $this->db->sanitize($this->isReadByCustomer),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('adminCustomerServiceMessage', $attributes, "WHERE adminCustomerServiceMessageId = '".$this->db->sanitize($this->dbAdminCustomerServiceMessageId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('adminCustomerServiceMessage', $attributes)) {
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
			if (!$this->db->delete('adminCustomerServiceMessage', "WHERE adminCustomerServiceMessageId = '".$this->db->sanitize($this->dbAdminCustomerServiceMessageId)."'", 1)) {
				return $this->db->getLastError();
			}

			// Generate a new random id
			require_once dirname(__FILE__)."/tableUuid.php";
			$uuid = new tableUuid('adminCustomerServiceMessage', 'adminCustomerServiceMessageId');
			$this->adminCustomerServiceMessageId = $uuid->generatedId;

			// Reset all variables
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->adminId = '';
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
