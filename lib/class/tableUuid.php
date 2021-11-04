<?php

	class tableUuid {

		private database $db;

		public $table;
		public $idColumn;
		public $generatedId;

		// If looking to create a new table entry, use ('table', $tableName, $idColumn) syntax.
		// If looking to create a new file entry, use ('file', $rootPath) syntax.

		function __construct($table, $idColumn) {

			$this->table = $table;
			$this->idColumn = $idColumn;
			
			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			$this->regenerate();
		}

		// Regenerate a different id
		public function regenerate() {
			$this->generatedId = mt_rand(1, 9999).uniqid();

			while ($this->db->select($this->db->sanitize($this->table), $this->db->sanitize($this->idColumn), "WHERE ".$this->db->sanitize($this->idColumn)." = '".$this->db->sanitize($this->generatedId)."'")) {
				$this->generatedId = mt_rand(1, 9999).uniqid();
			}
		}

	}

?>
