<?php

    //ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class adminManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions get and set admin values in the database in order to ensure that everything is sanitized and there is little redundancy
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		private function getAdminValue(string $adminId, string $field, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$adminIdSan = $this->databaseManager->sanitize($adminId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$result = $this->databaseManager->select("admin", "$fieldSan", "WHERE adminId = '$adminIdSan'");
			if ($result) {
				if (gettype($result) == 'array') {
					$output = $result[0][$field];
					if ($output === NULL) {
						return false;
					}
					if ($stripTags) {
						$output = strip_tags($output);
					}
					if ($escapeHtmlSpecialChars) {
						$output = htmlspecialchars($output);
					}
					return $output;
				}
			}
			return false;
		}

		private function setAdminValue(string $adminId, string $field, string $data) {
			$adminIdSan = $this->databaseManager->sanitize($adminId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$dataSan = $this->databaseManager->sanitize($data);
			$result = $this->databaseManager->update("admin", array("$fieldSan" => $dataSan), "WHERE adminId = '$adminIdSan'", 1);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions retrieve and set values for attributes of a customer
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// adminId
		public function getAdminId(string $adminId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getAdminValue($adminId, "adminId", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setAdminId(string $adminId, string $data) {
			$result = $this->setAdminValue($adminId, 'adminId', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// More Needed obviously
		
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
