<?php

	class admin {

		private string $setType;
		private databaseManager $db;

		public string $originalAdminId; // Used when updating the table incase the adminId has been changed after instantiation
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

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

		public $loginAttempts;
		public $savedLogins;

		private bool $setLoginAttempts;
		private bool $setSavedLogins;

		function __construct(string $adminId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Empty arrays for extra fetch data
			$this->loginAttempts = array();
			$this->savedLogins = array();

			// Set the loginAttempt and savedLogin set bools to false (these get set to true when the get functions are called to tell the set() function whether it should push these arrays to the database)
			$this->setLoginAttempts = false;
			$this->setSavedLogins = false;

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

			$this->originalAdminId = $this->adminId;
			
		}












		public function getLoginAttempts () {
			// If there are entries for adminloginAttempt then push them to the array
			$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminId = '$this->originalAdminId'");
			if ($fetch) {
				$this->loginAttempts = array();
				foreach ($fetch as $row) {
					array_push($this->loginAttempts,  array('adminLoginAttemptId' => $row['adminLoginAttemptId'], 'clientIp' => $row['clientIp'], 'enteredUsername' => $row['enteredUsername'], 'result' => $row['result'], 'dateTimeAdded' => $row['dateTimeAdded']));
				}
			}

			$this->setLoginAttempts = true;
		}

		public function getSavedLogins() {
			// If there are entries for adminsavedLogin then push them to the array
			$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminId = '$this->originalAdminId'");
			if ($fetch) {
				$this->savedLogins = array();
				foreach ($fetch as $row) {
					array_push($this->savedLogins, $row['adminSavedLoginId']);
				}
			}
			$this->setSavedLogins = true;
		}














		// Updates or inserts the data to the database
		public function set() {

			$attributes = array(
				'adminId' => $this->db->sanitize($this->adminId),
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
				$this->db->update('admin', $attributes, "WHERE adminId = '".$this->db->sanitize($this->originalAdminId)."'", 1);

			} else {

				// Insert the values to the database after sanitizing them
				if (!$this->db->insert('admin', $attributes)) {
					return $this->db->getLastError();
				}

			}

			// If the set bools for loginAttempts have been triggered, update/insert all the entries.
			if ($this->setLoginAttempts) {
				// Get all the current entries of loginAttempts for the current admin
				$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminId = '$this->adminId'");
				if (!$fetch) {
					$fetch = array();
				}

				//For each of the entries, check if it exists in the current public array. If it doesn't, delete the entry from the database.
				foreach ($fetch as $row) {
					foreach ($this->loginAttempts as $loginAttempt) {
						if ($row['adminLoginAttemptId'] == $loginAttempt['adminLoginAttemptId']) {
							if (!$this->db->delete('adminLoginAttempt', "WHERE adminLoginAttemptId = '".$row['adminLoginAttemptId']."'", 1)) {
								return $this->db->getLastError();
							}
						}
					}
				}
				// For each item in the current public array, check if it exists in the database. If it does, do update. If it doesn't, do insert.
				foreach ($this->loginAttempts as $loginAttempt) {
					$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminLoginAttemptId = '".$loginAttempt['adminLoginAttemptId']."'");
					if ($fetch) {
						// Update
						if (!$this->db->update('adminLoginAttempt', array('clientIp' => $loginAttempt['clientIp'], 'enteredUsername' => $loginAttempt['enteredUsername'], 'result' => $loginAttempt['result']), "WHERE adminLoginAttemptId = '".$loginAttempt['adminLoginAttemptId']."'", 1)) {
							return $this->db->getLastError();
						}
					} else {
						// Insert
						if (!$this->db->insert('adminLoginAttempt', array('adminLoginAttemptId' => $loginAttempt['adminLoginAttemptId'], 'adminId' => $this->originalAdminId, 'clientIp' => $loginAttempt['clientIp'], 'enteredUsername' => $loginAttempt['enteredUsername'], 'result' => $loginAttempt['result'], 'dateTimeAdded' => $loginAttempt['dateTimeAdded']), "WHERE adminLoginAttemptId = '".$loginAttempt['adminLoginAttemptId']."'")) {
							return $this->db->getLastError();
						}
					}
				}
			}

			// If the set bools for savedLogins have been triggered, update/insert all the entries
			if ($this->setSavedLogins) {
				// Get all the current entries of savedLogins for the current admin
				$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminId = '$this->adminId'");
				if (!$fetch) {
					$fetch = array();
				}

				//For each of the entries, check if it exists in the current public array. If it doesn't, delete the entry from the database.
				foreach ($fetch as $row) {
					if (!in_array($row['adminSavedLoginId'], $this->savedLogins)) {
						if (!$this->db->delete('adminSavedLogin', "WHERE adminSavedLoginId = '".$row['adminSavedLoginId']."'", 1)) {
							return $this->db->getLastError();
						}
					}
				}
				// For each item in the current public array, check if it exists in the database. If it doesn't, do insert.
				foreach ($this->savedLogins as $savedLogin) {
					$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminSavedLoginId = '".$savedLogin."'");
					if (!$fetch) {
						// Insert
						$currentDateTime = new DateTime();
						if (!$this->db->insert('adminSavedLogin', array('adminSavedLoginId' => $savedLogin, 'adminId' => $this->adminId, 'dateTimeAdded' => $currentDateTime->format('Y-m-d H:i:s')))) {
							return $this->db->getLastError();
						}
					}
				}
			}
			return true;
		}
	}

?>
