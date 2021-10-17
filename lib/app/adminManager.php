<?php

    //ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class adminManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions get and set admin values in the database in order to ensure that everything is sanitized and there is little redundancy
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		private function getAdminValue(string $adminId, string $field, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$adminIdSan = $this->databaseManager->sanitize($adminId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$result = $this->databaseManager->select("admin", "$fieldSan", "WHERE adminId = '$adminIdSan'");
			if ($result) {
				if (gettype($result) == 'array') {
					$output = $result[0][$field];
					if ($output === NULL) {
						return false;
					}
					if ($stripTags) {
						$output = strip_tags($output);
					}
					if ($escapeHtmlSpecialChars) {
						$output = htmlspecialchars($output);
					}
					return $output;
				}
			}
			return false;
		}

		private function setAdminValue(string $adminId, string $field, string $data) {
			$adminIdSan = $this->databaseManager->sanitize($adminId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$dataSan = $this->databaseManager->sanitize($data);
			$result = $this->databaseManager->update("admin", array("$fieldSan" => $dataSan), "WHERE adminId = '$adminIdSan'", 1);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions retrieve and set values for attributes of a customer
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// adminId
		public function getAdminId(string $adminId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getAdminValue($adminId, "adminId", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setAdminId(string $adminId, string $data) {
			$result = $this->setAdminValue($adminId, 'adminId', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// More Needed obviously
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions perform operations related to admins
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// addAdmin
		public function addAdmin($surname = NULL, $firstName, $lastName, $email = NULL, $phonePrefix = NULL, $phone1 = NULL, $phone2 = NULL, $phone3 = NULL, $billAddress = NULL, $billCity = NULL, $billState = NULL, $billZipCode = NULL, $overrideCreditAlertIsEnabled = NULL, $overrideCreditAlertAmount = NULL, $overrideAutoApplyCredit = NULL, $overrideBalanceAlertIsEnabled = NULL, $overrideBalanceAlertAmount = NULL, $allowCZSignIn = 1, $discountPercent = 0, $overridePaymentTerm = NULL, $notes = NULL) {

		}


		// removeCustomer

	}

	// Experimenting with new concept of managing the classes, where you can create an instance of 'admin', set the values of the admin object and then run a set() method that will update the admin in the database rather than the weird way of managing classes above where it is practically functional programming, but encased in a class so it has database shell access

	class admin {

		private $db;

		public $adminId;
		public $username;
		public $password;
		public $email;
		public $surname;
		public $firstName;
		public $lastName;
		public $profilePicture;
		public $allowCZSignIn;
		public $dateTimeJoined;
		public $dateTimeLeft;
		
		public $setType;

		function __construct(string $adminId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->db = new databaseManager;

			// If adminId is blank then make a new one

			if ($adminId == '') {
				// Make a new admin Id from a random id
				require_once dirname(__FILE__)."/uuid.php";
				$newUuid = new uuid('admin');
				$newUuid = $newUuid->generatedId;
			}

			// Fetch from database
			$fetch = $this->db->select('admin', '*', "WHERE adminId ='$adminId'");

			// If adminId already exists then set the set method type to UPDATE
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->adminId = $adminId;

				$this->$username = $fetch[0]['username'];
				$this->$password = $fetch[0]['password'];
				$this->$email = $fetch[0]['email'];
				$this->$surname = $fetch[0]['surname'];
				$this->$firstName = $fetch[0]['firstName'];
				$this->$lastName = $fetch[0]['lastName'];
				$this->$profilePicture = $fetch[0]['profilePicture'];
				$this->$allowCZSignIn = $fetch[0]['allowCZSignIn'];
				$this->$dateTimeJoined = $fetch[0]['dateTimeJoined'];
				$this->$dateTimeLeft = $fetch[0]['dateTimeLeft'];
			}

			
		}

		// Adds the admin to the database or updates the values
		public function set() {

			if ($this->setType == 'UPDATE') {

				// Update the values in the database
				if ($this->db->update('admin', array("username" => $this->username /* etc */), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database
				if ($this->db->insert('admin', array("adminId" => $this->adminId /* etc */))) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}
	}

?>
