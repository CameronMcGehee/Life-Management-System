<?php

	class render {

		protected database $db;
		public $output = '';

		function __construct() {
			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;
		}

	}

?>
