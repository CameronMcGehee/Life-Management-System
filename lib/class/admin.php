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

		public $loginAttempts; // (id => ('adminLoginAttemptId' => value, 'adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		public $savedLogins; // (id => ('adminSavedLoginId' => value, 'dateTimeAdded' => value))

		// Arrays to track possible database updates
		private $addedLoginAttempts; // (id => ('adminLoginAttemptId' => value, 'adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $updatedLoginAttempts; // (id => ('adminLoginAttemptId' => value, 'adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $removedLoginAttempts; // (adminLoginAttemptId)
		
		private $addedSavedLogins; // (id => ('adminSavedLoginId' => value, 'dateTimeAdded' => value))
		private $updatedSavedLogins; // (id => ('adminSavedLoginId' => value, 'dateTimeAdded' => value))
		private $removedSavedLogins; // (adminSavedLoginId)

		//Init all variables
		function __construct(string $adminId = '', bool $getLoginAttempts, bool $getSavedLogins) {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Init arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();

			$this->addedLoginAttempts = array();
			$this->addedSavedLogins = array();

			$this->updatedLoginAttempts = array();
			$this->updatedSavedLogins = array();

			$this->removedLoginAttempts = array();
			$this->removedSavedLogins = array();

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

		// Get Entry Functions

		public function getLoginAttempts () {
			// If there are entries for adminloginAttempt then push them to the array
			$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminId = '$this->originalAdminId'");
			if ($fetch) {
				$this->loginAttempts = array();
				foreach ($fetch as $row) {
					array_push($this->loginAttempts,  array(
						'adminLoginAttemptId' => $row['adminLoginAttemptId'],
						'clientIp' => $row['clientIp'],
						'enteredUsername' => $row['enteredUsername'],
						'result' => $row['result'],
						'dateTimeAdded' => $row['dateTimeAdded']
					));
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
					array_push($this->savedLogins, array(
						'adminSavedLoginId' => $row['adminSavedLoginId'],
						'dateTimeAdded' => $row['dateTimeAdded']
				));
				}
			}
			$this->setSavedLogins = true;
		}

		// loginAttempt Functions

		public function addLoginAttempt($clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'adminLoginAttempt', 'adminLoginAttemptId');
			
			array_push($this->addedLoginAttempts, [$uuid->generatedId => array(
				'adminLoginAttemptId' => $uuid->generatedId,
				'adminId' => $this->originalAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			)]);
			array_push($this->loginAttempts, [$uuid->generatedId => array(
				'adminLoginAttemptId' => $uuid->generatedId,
				'adminId' => $this->originalAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			)]);
		}

		public function updateLoginAttempt($adminLoginAttemptId, $clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			unset($this->loginAttempts[$adminLoginAttemptId]);
			array_push($this->updatedLoginAttempts, [$adminLoginAttemptId => array(
				'adminLoginAttemptId' => $adminLoginAttemptId,
				'adminId' => $this->originalAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			)]);
			array_push($this->loginAttempts, [$adminLoginAttemptId => array(
				'adminLoginAttemptId' => $adminLoginAttemptId,
				'adminId' => $this->originalAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			)]);
		}

		public function removeLoginAttempt($adminLoginAttemptId) {
			array_push($this->removedLoginAttempts, $adminLoginAttemptId);
			unset($this->loginAttempts[$adminLoginAttemptId]);
		}

		// savedLogin Functions

		public function addSavedLogin($clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'adminSavedLogin', 'adminSavedLoginId');
			
			array_push($this->addedSavedLogins, [$adminSavedLoginId => array(
				'adminSavedLoginId' => $adminSavedLoginId,
				'dateTimeAdded' => $dateTimeAdded
				)]);
			array_push($this->savedLogins, [$adminSavedLoginId => array(
				'adminSavedLoginId' => $adminSavedLoginId,
				'dateTimeAdded' => $dateTimeAdded
			)]);
		}

		public function updateSavedLogin($adminSavedLoginId, $clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			unset($this->savedLogins[$adminSavedLoginId]);
			array_push($this->updatedSavedLogins, [$adminSavedLoginId => array(
				'adminSavedLoginId' => $adminSavedLoginId,
				'dateTimeAdded' => $dateTimeAdded
			)]);
			array_push($this->savedLogins, [$adminSavedLoginId => array(
				'adminSavedLoginId' => $adminSavedLoginId,
				'dateTimeAdded' => $dateTimeAdded
			)]);
		}

		public function removeSavedLogin($adminSavedLoginId) {
			array_push($this->removedSavedLogins, $adminSavedLoginId);
			unset($this->savedLogins[$adminSavedLoginId]);
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

			// Linked tables --------------------------------------------------------------------------

			// loginAttempt Adds (SANITIZE!!!!)

			foreach ($addedLoginAttempts as $entry) {
				if (!$this->db->insert('adminLoginAttempt', array(
					'adminLoginAttemptId' => $this->db->sanitize($entry['adminLoginAttemptId']),
					'adminId' => $this->db->sanitize($this->originalAdminId),
					'clientIp' => $this->db->sanitize($entry['clientIp']),
					'enteredUsername' => $this->db->sanitize($entry['enteredUsername']),
					'result' => $this->db->sanitize($entry['result']),
					'dateTimeAdded' => $this->db->sanitize($entry['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt Updates (SANITIZE!!!!)

			foreach ($updatedLoginAttempts as $entry) {
				if (!$this->db->update('adminLoginAttempt', array(
					'clientIp' => $this->db->sanitize($entry['clientIp']),
					'enteredUsername' => $this->db->sanitize($entry['enteredUsername']),
					'result' => $this->db->sanitize($entry['result']),
					'dateTimeAdded' => $this->db->sanitize($entry['dateTimeAdded'])
				), "WHERE adminLoginAttemptId = '".$this->db->sanitize($entry['adminLoginAttemptId'])."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt Removes (SANITIZE!!!!)

			foreach ($removedLoginAttempts as $entry) {
				if (!$this->db->delete('adminLoginAttempt', "WHERE adminLoginAttemptId = '".$this->db->sanitize($entry['adminLoginAttemptId'])."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// -----------------------------------------------------------------------------------------

			// savedLogin Adds

			foreach ($addedSavedLogins as $entry) {
				if (!$this->db->insert('adminSavedLogin', array(
					'adminSavedLoginId' => $this->db->sanitize($entry['adminSavedLoginId']),
					'adminId' => $this->db->sanitize($this->originalAdminId),
					'dateTimeAdded' => $this->db->sanitize($entry['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// savedLogin Updates

			foreach ($updatedSavedLogins as $entry) {
				if (!$this->db->update('adminSavedLogin', array(
					'dateTimeAdded' => $this->db->sanitize($entry['dateTimeAdded'])
				), "WHERE adminSavedLoginId = '".$this->db->sanitize($entry['adminSavedLoginId'])."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// savedLogin Removes

			foreach ($removedSavedLogins as $entry) {
				if (!$this->db->delete('adminSavedLogin', "WHERE adminSavedLoginId = '".$this->db->sanitize($entry['adminSavedLoginId'])."'", 1)) {
					return $this->db->getLastError();
				}
			}

			return true;
		}
	}

?>
