<?php

	class authToken {

		private string $setType;
		private databaseManager $db;

		public string $originalAuthTokenId; // Used when updating the table incase the authTokenId has been changed after instantiation
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		public $authTokenId;
		public $businessId;
		public $authName;
		public $dateTimeUsed;
		public $clientIpUsed;
		public $dateTimeAdded;

		function __construct(string $authTokenId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('authToken', '*', "WHERE authTokenId ='$authTokenId'");

			// If authTokenId already exists then set the set method type to UPDATE and fetch the values for the authToken
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->authTokenId = $authTokenId;
				$this->existed = true;

				$this->businessId = $fetch[0]['businessId'];
				$this->authName = $fetch[0]['authName'];
				$this->dateTimeUsed = $fetch[0]['dateTimeUsed'];
				$this->clientIpUsed = $fetch[0]['clientIpUsed'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			// If authTokenId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new authTokenId
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'authToken', 'authTokenId');
				$this->authTokenId = $uuid->generatedId;

				$this->businessId = '';
				$this->authName = NULL;
				$this->dateTimeUsed = NULL;
				$this->clientIpUsed = '';

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			$this->$originalAuthTokenId = $this->authTokenId;
			
		}

		// Adds the authToken to the database or updates the values
		public function set() {

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('authToken', array(
					'authTokenId' => $this->db->sanitize($this->authTokenId),
					'businessId' => $this->db->sanitize($this->businessId),
					'authName' => $this->db->sanitize($this->authName),
					'dateTimeUsed' => $this->db->sanitize($this->dateTimeUsed),
					'clientIpUsed' => $this->db->sanitize($this->clientIpUsed),
					'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
				), "WHERE authTokenId = ".$this->db->sanitize($this->originalAuthTokenId), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('authToken', array(
					'authTokenId' => $this->db->sanitize($this->authTokenId),
					'businessId' => $this->db->sanitize($this->businessId),
					'authName' => $this->db->sanitize($this->authName),
					'dateTimeUsed' => $this->db->sanitize($this->dateTimeUsed),
					'clientIpUsed' => $this->db->sanitize($this->clientIpUsed),
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
