<?php

	class uuid {

		private databaseManager $db;
		private string $input2;
		private string $input3;

		public string $generatedId;

		// If looking to create a new table entry, use ('table', $tableName, $idColumn) syntax.
		// If looking to create a new file entry, use ('file', $rootPath) syntax.

		function __construct($type, $input2, $input3 = '') {

			$this->table = $input2;
			$this->idColumn = $input3;
			
			// Connect to the database
			require_once dirname(__FILE__)."/../manager/databaseManager.php";
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
				case 'file':
					// While the file exists in the given path, regenerate
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
