<?php

    //CUSTOMER FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class formManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		public function generateFormToken() {

			// Include the idManager and generate a safe token for the given table

			// insert the token into the database

			// return the token generated

		}

		public function tokenIsUsed(string $token) {
			// Check in the database for a form token with the token of the given token, that is not used

			// if used already, return true

			// if not, return false
		}

		public function useFormToken(string $token) {
			// Check in the database for a form token with the token of the given token, that is not used

			// if used already, return

			// if not, set it to used and return
		}

	}

?>