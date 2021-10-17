<?php

	class admin {

		private string $setType;
		private databaseManager $db;

		public string $adminId;
		public string $username;
		public string $password;
		public string $email;
		public $surname;
		public string $firstName;
		public string $lastName;
		public $profilePicture;
		public bool $allowSignIn;
		public string $dateTimeJoined;
		public $dateTimeLeft;

		function __construct(string $adminId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->db = new databaseManager;

			// If adminId is blank then make a new one

			if ($adminId == '') {
				// Make a new admin Id from a random id
				require_once dirname(__FILE__)."/uuid.php";
				$newUuid = new uuid('admin', 'adminId');
				$newUuid = $newUuid->generatedId;
			}

			// Fetch from database
			$fetch = $this->db->select('admin', '*', "WHERE adminId ='$adminId'");

			// If adminId already exists then set the set method type to UPDATE and fetch the values for the admin
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->adminId = $adminId;

				$this->username = $fetch[0]['username'];
				$this->password = $fetch[0]['password'];
				$this->email = $fetch[0]['email'];
				$this->surname = $fetch[0]['surname'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->profilePicture = $fetch[0]['profilePicture'];
				$this->allowSignIn = $fetch[0]['allowSignIn'];
				if ($this->allowSignIn == '0') {
					$this->allowSignIn = false;
				} else {
					$this->allowSignIn = true;
				}
				$this->dateTimeJoined = $fetch[0]['dateTimeJoined'];
				$this->dateTimeLeft = $fetch[0]['dateTimeLeft'];
			// If adminId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->adminId = $newUuid;

				$this->username = '';
				$this->password = '';
				$this->email = '';
				$this->surname = NULL;
				$this->firstName = '';
				$this->lastName = '';
				$this->profilePicture = NULL;
				$this->allowSignIn = 1;
				$this->dateTimeJoined = '';
				$this->dateTimeLeft = NULL;
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
