<?php

    //ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class adminManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions perform operations related to admins
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// addAdmin
		public function addAdmin($surname = NULL, $firstName, $lastName, $email = NULL, $phonePrefix = NULL, $phone1 = NULL, $phone2 = NULL, $phone3 = NULL, $billAddress = NULL, $billCity = NULL, $billState = NULL, $billZipCode = NULL, $overrideCreditAlertIsEnabled = NULL, $overrideCreditAlertAmount = NULL, $overrideAutoApplyCredit = NULL, $overrideBalanceAlertIsEnabled = NULL, $overrideBalanceAlertAmount = NULL, $allowCZSignIn = 1, $discountPercent = 0, $overridePaymentTerm = NULL, $notes = NULL) {

		}


		// removeCustomer

	}

?>
