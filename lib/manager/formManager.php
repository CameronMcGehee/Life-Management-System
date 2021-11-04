<?php

	class formToken {

		function __construct() {
			require_once dirname(__FILE__)."/../database.php";
			$this->databaseManager = new database;
		}

	}

?>
