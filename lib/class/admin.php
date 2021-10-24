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

		function __construct(string $adminId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('admin', '*', "WHERE adminId ='$adminId'");

			// If adminId already exists then set the set method type to UPDATE and fetch the values for the admin
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->adminId = $adminId;
				$this->existed = true;

				$this->username = (string)$fetch[0]['username'];
				$this->password = (string)$fetch[0]['password'];
				$this->email = (string)$fetch[0]['email'];
				$this->surname = (string)$fetch[0]['surname'];
				$this->firstName = (string)$fetch[0]['firstName'];
				$this->lastName = (string)$fetch[0]['lastName'];
				$this->profilePicture = (string)$fetch[0]['profilePicture'];
				$this->allowSignIn = (string)$fetch[0]['allowSignIn'];
				$this->dateTimeJoined = (string)$fetch[0]['dateTimeJoined'];
				$this->dateTimeLeft = (string)$fetch[0]['dateTimeLeft'];
			// If adminId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new admin Id from a random id
				require_once dirname(__FILE__)."/uuid.php";
				$newUuid = new uuid('table', 'admin', 'adminId');
				$this->adminId = $newUuid->generatedId;

				$this->username = '';
				$this->password = '';
				$this->email = '';
				$this->surname = NULL;
				$this->firstName = '';
				$this->lastName = '';
				$this->profilePicture = NULL;
				$this->allowSignIn = '1';
				// Default dateTimeJoined to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime(); $this->dateTimeJoined = $currentDateTime->format('Y-m-d H:i:s');

				$this->dateTimeLeft = NULL;
			}

			$this->$originalAdminId = $this->adminId;
			
		}

		// Adds the admin to the database or updates the values
		public function set() {

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('admin', array(
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
				), "WHERE adminId = ".$this->db->sanitize($this->originalAdminId), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('admin', array(
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
				))) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}
	}

?>
