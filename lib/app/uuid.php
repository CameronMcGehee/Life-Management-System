<?php

    //CUSTOMER FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class uuid {

		private databaseManager $db;
		private string $table;
		private string $idColumn;

		public string $generatedId;

		function __construct($type, $table, $idColumn) {

			$this->table = $table;
			$this->idColumn = $idColumn;
			
			// Connect to the database
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->db = new databaseManager;

			// Generate the first id
			$this->generatedId = mt_rand(1, 9999).uniqid();

			// Check if there is already an id in the given table in the given column with that id
			switch ($type) {
				case 'table':
					if ($this->db->select($this->table, $this->idColumn, "WHERE $this->idColumn = '$this->generatedId'")) {
						$this->regenerate();
					}
					break;
			}
		}

		// Regenerate a different id
		public function regenerate() {
			$this->generatedId = mt_rand(1, 9999).uniqid();

			while ($this->db->select($this->table, $this->idColumn, "WHERE $this->idColumn = '$this->generatedId'")) {
				$this->generatedId = mt_rand(1, 9999).uniqid();
			}
		}

	}

?>
