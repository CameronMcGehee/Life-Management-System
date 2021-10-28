<?php

	class admin {

		private string $setType;
		private databaseManager $db;

		private string $staticAdminId; // Used when updating the table incase the adminId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		// Main database attributes
		public $adminId;
		public $username;
		public $password;
		public $email;
		public $surname;
		public $firstName;
		public $lastName;
		public $profilePicture;
		public $allowSignIn;
		public $dateTimeJoined;
		public $dateTimeLeft;

		// Arrays to store linked data.
		public $loginAttempts;
		public $savedLogins;
		public $businesses;
		public $customerServiceMessages;
		public $estimateApprovals;

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminId = '', bool $getLoginAttempts = false, bool $getSavedLogins = false) {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Init arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();
			$this->businesses = array();
			$this->customerServiceMessages = array();
			$this->estimateApprovals = array();

			// Fetch from database
			$fetch = $this->db->select('admin', '*', "WHERE adminId ='$adminId'");

			// If adminId already exists then set the set method type to UPDATE and fetch the values for the admin
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->adminId = $adminId;
				$this->existed = true;

				$this->username = $fetch[0]['username'];
				$this->password = $fetch[0]['password'];
				$this->email = $fetch[0]['email'];
				$this->surname = $fetch[0]['surname'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->profilePicture = $fetch[0]['profilePicture'];
				$this->allowSignIn = $fetch[0]['allowSignIn'];
				$this->dateTimeJoined = $fetch[0]['dateTimeJoined'];
				$this->dateTimeLeft = $fetch[0]['dateTimeLeft'];

			// If adminId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new adminId
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'admin', 'adminId');
				$this->adminId = $uuid->generatedId;

				$this->username = '';
				$this->password = '';
				$this->email = '';
				$this->surname = NULL;
				$this->firstName = '';
				$this->lastName = '';
				$this->profilePicture = NULL;
				$this->allowSignIn = '1';
				// Default dateTimeJoined to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeJoined = $currentDateTime->format('Y-m-d H:i:s');

				$this->dateTimeLeft = NULL;
			}

			$this->staticAdminId = $this->adminId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminId = '$this->staticAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['adminLoginAttempts']);
				}
			}
		}

		public function pullSavedLogins($params = '') {
			$this->savedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminId = '$this->staticAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->savedLogins, $row['adminSavedLoginId']);
				}
			}
		}

		public function pullBusinesses($params = '') {
			$this->businesses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', '*', "WHERE adminId = '$this->staticAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->businesses, $row['businessId']);
				}
			}
		}

		public function pullCustomerServiceMessages($params = '') {
			$this->customerServiceMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminCustomerServiceMessage', '*', "WHERE adminId = '$this->staticAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerServiceMessages, $row['adminCustomerServiceMessageId']);
				}
			}
		}

		public function pullEstimateApprovals($params = '') {
			$this->businesses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateApproval', '*', "WHERE approvedByAdminId = '$this->staticAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->businesses, $row['estimateApprovalId']);
				}
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'adminId' => $this->db->sanitize($this->staticAdminId),
				'username' => $this->db->sanitize($this->username),
				'password' => $this->db->sanitize($this->password),
				'email' => $this->db->sanitize($this->email),
				'surname' => $this->db->sanitize($this->surname),
				'firstName' => $this->db->sanitize($this->firstName),
				'lastName' => $this->db->sanitize($this->lastName),
				'profilePicture' => $this->db->sanitize($this->profilePicture),
				'allowSignIn' => $this->db->sanitize($this->allowSignIn),
				'dateTimeJoined' => $this->db->sanitize($this->dateTimeJoined),
				'dateTimeLeft' => $this->db->sanitize($this->dateTimeLeft)
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('admin', $attributes, "WHERE adminId = '".$this->db->sanitize($this->staticAdminId)."'", 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('admin', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}
			}
			return true;
		}
	}

?>
