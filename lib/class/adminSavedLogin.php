<?php

	class adminSavedLogin {

		private string $setType;
		private databaseManager $db;

		public string $originalAdminSavedLoginId; // Used when updating the table incase the adminSavedLoginId has been changed after instantiation
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		public $adminSavedLoginId;
		public $adminId;
		public $dateTimeAdded;

		function __construct(string $adminSavedLoginId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('adminSavedLogin', '*', "WHERE adminSavedLoginId ='$adminSavedLoginId'");

			// If adminSavedLoginId already exists then set the set method type to UPDATE and fetch the values for the adminSavedLogin
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->adminSavedLoginId = $adminSavedLoginId;
				$this->existed = true;

				$this->adminId = (string)$fetch[0]['adminId'];
				$this->dateTimeAdded = (string)$fetch[0]['dateTimeAdded'];
			// If adminSavedLoginId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new adminSavedLogin Id from a random id
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'adminSavedLogin', 'adminSavedLoginId');
				$this->adminSavedLoginId = $uuid->generatedId;

				$this->adminId = '';

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime(); $this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			$this->$originalAdminSavedLoginId = $this->adminSavedLoginId;
			
		}

		// Adds the adminSavedLogin to the database or updates the values
		public function set() {

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('adminSavedLogin', array(
					'adminSavedLoginId' => $this->db->sanitize($this->adminSavedLoginId),
					'adminId' => $this->db->sanitize($this->adminId),
					'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
				), "WHERE adminSavedLoginId = ".$this->db->sanitize($this->originalAdminSavedLoginId), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('adminSavedLogin', array(
					'adminSavedLoginId' => $this->db->sanitize($this->adminSavedLoginId),
					'adminId' => $this->db->sanitize($this->adminId),
					'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
				))) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}
	}

?>
