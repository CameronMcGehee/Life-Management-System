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

		// Arrays that allow the you to see the linked data. Not used when querying the database, but are useful when retrieving data.
		public $loginAttempts; // (adminLoginAttemptId => ('adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		public $savedLogins; // (adminSavedLoginId => ('dateTimeAdded' => value))

		// Arrays to track possible database updates
		private $addedLoginAttempts; // (adminLoginAttemptId => ('adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $updatedLoginAttempts; // (adminLoginAttemptId => ('adminId' => value, 'clientIp' => value, 'enteredUsername' => value, 'result' => value, 'dateTimeAdded' => value))
		private $removedLoginAttempts; // (adminLoginAttemptId)
		
		private $addedSavedLogins; // (adminSavedLoginId => ('dateTimeAdded' => value))
		private $updatedSavedLogins; // (adminSavedLoginId => ('dateTimeAdded' => value))
		private $removedSavedLogins; // (adminSavedLoginId)

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

			$this->addedLoginAttempts = array();
			$this->updatedLoginAttempts = array();
			$this->removedLoginAttempts = array();
			
			$this->addedSavedLogins = array();
			$this->updatedSavedLogins = array();
			$this->removedSavedLogins = array();

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
		// Linked data pull functions. Will get the data from the database and will reset any changes made to the public arrays before calling set().
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts () {
			// If there are entries for adminloginAttempt then push them to the array
			$fetch = $this->db->select('adminLoginAttempt', '*', "WHERE adminId = '$this->staticAdminId'");
			if ($fetch) {
				$this->loginAttempts = array();
				foreach ($fetch as $row) {
					$this->loginAttempts[$row['adminLoginAttemptId']] = array(
						'clientIp' => $row['clientIp'],
						'enteredUsername' => $row['enteredUsername'],
						'result' => $row['result'],
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedLoginAttempts = array();
			$this->updatedLoginAttempts = array();
			$this->removedLoginAttempts = array();
		}

		public function pullSavedLogins() {
			// If there are entries for adminsavedLogin then push them to the array
			$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminId = '$this->staticAdminId'");
			if ($fetch) {
				$this->savedLogins = array();
				foreach ($fetch as $row) {
					$this->savedLogins[$row['adminSavedLoginId']] = array(
						'dateTimeAdded' => $row['dateTimeAdded']
					);
				}
			}
			// Clear change arrays
			$this->addedSavedLogins = array();
			$this->updatedSavedLogins = array();
			$this->removedSavedLogins = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data handling functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// loginAttempt

		public function addLoginAttempt($clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'adminLoginAttempt', 'adminLoginAttemptId');
			
			$this->addedLoginAttempts[$uuid->generatedId] = array(
				'adminId' => $this->staticAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$uuid->generatedId] = array(
				'adminId' => $this->staticAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateLoginAttempt($adminLoginAttemptId, $clientIp, $enteredUsername, $result, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedLoginAttempts[$adminLoginAttemptId] = array(
				'adminId' => $this->staticAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->loginAttempts[$adminLoginAttemptId] = array(
				'adminId' => $this->staticAdminId,
				'clientIp' => $clientIp,
				'enteredUsername' => $enteredUsername,
				'result' => $result,
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeLoginAttempt($adminLoginAttemptId) {
			array_push($this->removedLoginAttempts, $adminLoginAttemptId);
			unset($this->loginAttempts[$adminLoginAttemptId]);
		}

		// savedLogin

		public function addSavedLogin($dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			// Generate a new id
			require_once dirname(__FILE__)."/../class/uuid.php";
            $uuid = new uuid('table', 'adminSavedLogin', 'adminSavedLoginId');
			
			$this->addedSavedLogins[$uuid->generatedId] = array(
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$uuid->generatedId] = array(
				'dateTimeAdded' => $dateTimeAdded
			);

			return $uuid->generatedId;
		}

		public function updateSavedLogin($adminSavedLoginId, $dateTimeAdded = '') {
			// If dateTimeAdded is not provided, use the current time.
			if ($dateTimeAdded == '') {
				$currentDateTime = new DateTime();
				$dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}
			$this->updatedSavedLogins[$adminSavedLoginId] = array(
				'dateTimeAdded' => $dateTimeAdded
			);
			$this->savedLogins[$adminSavedLoginId] = array(
				'dateTimeAdded' => $dateTimeAdded
			);
		}

		public function removeSavedLogin($adminSavedLoginId) {
			array_push($this->removedSavedLogins, $adminSavedLoginId);
			unset($this->savedLogins[$adminSavedLoginId]);
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

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
				$this->db->update('admin', $attributes, "WHERE adminId = '".$this->db->sanitize($this->staticAdminId)."'", 1);
			} else {
				// Insert the values to the database after sanitizing them
				if (!$this->db->insert('admin', $attributes)) {
					return $this->db->getLastError();
				}
			}

			// Linked tables --------------------------------------------------------------------------

			// loginAttempt adds
			foreach ($this->addedLoginAttempts as $id => $attributes) {
				if (!$this->db->insert('adminLoginAttempt', array(
					'adminLoginAttemptId' => $this->db->sanitize($id),
					'adminId' => $this->db->sanitize($this->staticAdminId),
					'clientIp' => $this->db->sanitize($attributes['clientIp']),
					'enteredUsername' => $this->db->sanitize($attributes['enteredUsername']),
					'result' => $this->db->sanitize($attributes['result']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt updates
			foreach ($this->updatedLoginAttempts as $id => $attributes) {
				if (!$this->db->update('adminLoginAttempt', array(
					'clientIp' => $this->db->sanitize($attributes['clientIp']),
					'enteredUsername' => $this->db->sanitize($attributes['enteredUsername']),
					'result' => $this->db->sanitize($attributes['result']),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE adminLoginAttemptId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// loginAttempt removes
			foreach ($this->removedLoginAttempts as $id) {
				if (!$this->db->delete('adminLoginAttempt', "WHERE adminLoginAttemptId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// -----------------------------------------------------------------------------------------

			// savedLogin adds
			foreach ($this->addedSavedLogins as $id => $attributes) {
				if (!$this->db->insert('adminSavedLogin', array(
					'adminSavedLoginId' => $this->db->sanitize($id),
					'adminId' => $this->db->sanitize($this->staticAdminId),
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				))) {
					return $this->db->getLastError();
				}
			}

			// savedLogin updates
			foreach ($this->updatedSavedLogins as $id => $attributes) {
				if (!$this->db->update('adminSavedLogin', array(
					'dateTimeAdded' => $this->db->sanitize($attributes['dateTimeAdded'])
				), "WHERE adminSavedLoginId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			// savedLogin removes
			foreach ($this->removedSavedLogins as $id) {
				if (!$this->db->delete('adminSavedLogin', "WHERE adminSavedLoginId = '".$this->db->sanitize($id)."'", 1)) {
					return $this->db->getLastError();
				}
			}

			return true;
		}
	}

?>
