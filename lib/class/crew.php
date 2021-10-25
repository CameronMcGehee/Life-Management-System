<?php

	class crew {

		private string $setType;
		private databaseManager $db;

		public string $originalcrewId; // Used when updating the table incase the crewId has been changed after instantiation
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		public $crewId;
		public $businessId;
		public $createdByAdminId;
		public $name;
		public $description;
		public $dateTimeAdded;

		function __construct(string $crewId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
			$this->db = new databaseManager;

			// Fetch from database
			$fetch = $this->db->select('crew', '*', "WHERE crewId ='$crewId'");

			// If crewId already exists then set the set method type to UPDATE and fetch the values for the crew
			if ($fetch) {
				$this->setType = 'UPDATE';
				$this->crewId = $crewId;
				$this->existed = true;

				$this->businessId = $fetch[0]['businessId'];
				$this->createdByAdminId = $fetch[0]['createdByAdminId'];
				$this->name = $fetch[0]['name'];
				$this->description = $fetch[0]['description'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];
			// If crewId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				$this->setType = 'INSERT';
				$this->existed = false;

				// Make a new crewId
				require_once dirname(__FILE__)."/uuid.php";
				$uuid = new uuid('table', 'crew', 'crewId');
				$this->crewId = $uuid->generatedId;

				$this->businessId = '';
				$this->createdByAdminId = '';
				$this->name = '';
				$this->description = NULL;

				// Default dateTimeAdded to now since it is likely going to be inserted at this time
				$currentDateTime = new DateTime();
				$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');
			}

			$this->$originalcrewId = $this->crewId;
			
		}

		// Adds the crew to the database or updates the values
		public function set() {

			$attributes = array(
				'crewId' => $this->db->sanitize($this->crewId),
				'businessId' => $this->db->sanitize($this->businessId),
				'createdByAdminId' => $this->db->sanitize($this->createdByAdminId),
				'name' => $this->db->sanitize($this->name),
				'description' => $this->db->sanitize($this->description),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('crew', $attributes, "WHERE crewId = ".$this->db->sanitize($this->originalcrewId), 1)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('crew', $attributes)) {
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}
	}

?>
