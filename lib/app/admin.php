<?php

	// Experimenting with new concept of managing the classes, where you can create an instance of 'admin', set the values of the admin object and then run a set() method that will update the admin in the database rather than the weird way of managing classes before where it is practically functional programming, but encased in a class

	class admin {

		private $setType;
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
