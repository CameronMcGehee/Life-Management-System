<?php

    //CUSTOMER FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class uuid {

		private $db;
		private $table;
		private $idColumn;

		public $generatedId;

		function __construct($table, $idColumn) {

			$this->table = $table;
			$this->idColumn = $idColumn;
			
			// Connect to the database
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->db = new databaseManager;

			// Generate the first id
			$this->generatedId = mt_rand(1, 9999); $n .= uniqid();

			// Check if there is already an id in the given table in the given column with that id
			if ($this->db->select($this->table, $this->idColumn, "WHERE $this->idColumn = '$this->generatedId'")) {
				$this->regenerate;
			}
		}

		// Regenerate a different id
		public function regenerate() {
			$this->generatedId = mt_rand(1, 9999); $n .= uniqid();

			while ($this->db->select($this->table, $this->idColumn, "WHERE $this->idColumn = '$this->generatedId'")) {
				$this->generatedId = mt_rand(1, 9999); $n .= uniqid();
			}
		}

	}

?>
