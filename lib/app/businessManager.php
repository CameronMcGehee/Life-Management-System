<?php

	//BUSINESS FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class businessManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions get and set business values in the database in order to ensure that everything is sanitized and there is little redundancy
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		private function getBusinessValue(string $businessId, string $field, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$businessIdSan = $this->databaseManager->sanitize($businessId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$result = $this->databaseManager->select("business", "$fieldSan", "WHERE businessId = '$businessIdSan'");
			if ($result) {
				if (gettype($result) == 'array') {
					$output = $result[0][$field];
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

		private function setBusinessValue(string $businessId, string $field, string $data) {
			$businessIdSan = $this->databaseManager->sanitize($businessId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$dataSan = $this->databaseManager->sanitize($data);
			$result = $this->databaseManager->update("business", array("$fieldSan" => $dataSan), "WHERE businessId = '$businessIdSan'", 1);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions retrieve and set values for non-setting attributes of a business
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// ownderAdminId 
		public function getOwner(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "ownerAdminId", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOwner(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'ownerAdminId', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// displayName 
		public function getDisplayName(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "displayName", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setDisplayName(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'displayName', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// adminDisplayName
		public function getAdminDisplayName(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "adminDisplayName", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setAdminDisplayName(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'adminDisplayName', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// fullLogoFile 
		public function getFullLogoFile(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "fullLogoFile", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setFullLogoFile(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'fullLogoFile', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// address,state,city,zipCode 
		public function getLocation(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$addressResult = $this->getBusinessValue($businessId, "address", $stripTags, $escapeHtmlSpecialChars);
			$cityResult = $this->getBusinessValue($businessId, "city", $stripTags, $escapeHtmlSpecialChars);
			$stateResult = $this->getBusinessValue($businessId, "state", $stripTags, $escapeHtmlSpecialChars);
			$zipCodeResult = $this->getBusinessValue($businessId, "zipCode", $stripTags, $escapeHtmlSpecialChars);

			$locationArray = array();
			if ($addressResult) {
				array_push($locationArray, array("address" => $addressResult));
			} else {
				array_push($locationArray, array("address" => NULL));
			}
			if ($cityResult) {
				array_push($locationArray, array("city" => $cityResult));
			} else {
				array_push($locationArray, array("city" => NULL));
			}
			if ($stateResult) {
				array_push($locationArray, array("state" => $stateResult));
			} else {
				array_push($locationArray, array("state" => NULL));
			}
			if ($zipCodeResult) {
				array_push($locationArray, array("zipCode" => $zipCodeResult));
			} else {
				array_push($locationArray, array("zipCode" => NULL));
			}
			return $locationArray;
		}
		public function setAddress(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'address', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setState(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'state', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setCity(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'city', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setZipCode(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'zipCode', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// phonePrefix,phone1,phone2,phone3
		public function getPhone(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$phonePrefixResult = $this->getBusinessValue($businessId, "phonePrefix", $stripTags, $escapeHtmlSpecialChars);
			$phone1Result = $this->getBusinessValue($businessId, "phone1", $stripTags, $escapeHtmlSpecialChars);
			$phone2Result = $this->getBusinessValue($businessId, "phone2", $stripTags, $escapeHtmlSpecialChars);
			$phone3Result = $this->getBusinessValue($businessId, "phone3", $stripTags, $escapeHtmlSpecialChars);

			$phoneArray = array();
			if ($phonePrefixResult) {
				array_push($phoneArray, array("phonePrefix" => $phonePrefixResult));
			} else {
				array_push($phoneArray, array("phonePrefix" => NULL));
			}
			if ($phone1Result) {
				array_push($phoneArray, array("phone1" => $phone1Result));
			} else {
				array_push($phoneArray, array("phone1" => NULL));
			}
			if ($phone2Result) {
				array_push($phoneArray, array("phone2" => $phone2Result));
			} else {
				array_push($phoneArray, array("phone2" => NULL));
			}
			if ($phone3Result) {
				array_push($phoneArray, array("phone3" => $phone3Result));
			} else {
				array_push($phoneArray, array("phone3" => NULL));
			}
			return $phoneArray;
		}
		public function setPhonePrefix(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'phonePrefix', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setPhone1(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'phone1', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setPhone2(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'phone2', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setZipPhone3(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'phone3', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// email 
		public function getEmail(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "email", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setEmail(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'email', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// currencySymbol 
		public function getCurrencySymbol(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "currencySymbol", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setCurrencySymbol(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'currencySymbol', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// timeZone 
		public function getTimeZone(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "timeZone", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setTimeZone(string $businessId, string $data) {
			$result = $this->setBusinessValue($businessId, 'timeZone', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions retrieve and set values for setting attributes of a business
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// modCust 
		public function modCustomersEnabled(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "modCust", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setModCustomersEnabled(string $businessId, bool $data) {
			$result = $this->setBusinessValue($businessId, 'modCust', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// modInv 
		public function ModInvoicesEnabled(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getBusinessValue($businessId, "modCust", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setModInvoicesEnabled(string $businessId, bool $data) {
			$result = $this->setBusinessValue($businessId, 'modCust', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

	}

?>
