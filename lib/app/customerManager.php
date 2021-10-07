<?php

    //CUSTOMER FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class customerManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions get and set customer values in the database in order to ensure that everything is sanitized and there is little redundancy
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		private function getCustomerValue(string $customerId, string $field, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$customerIdSan = $this->databaseManager->sanitize($customerId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$result = $this->databaseManager->select("customer", "$fieldSan", "WHERE customerId = '$customerIdSan'");
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

		private function setCustomerValue(string $customerId, string $field, string $data) {
			$customerIdSan = $this->databaseManager->sanitize($customerId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$dataSan = $this->databaseManager->sanitize($data);
			$result = $this->databaseManager->update("customer", array("$fieldSan" => $dataSan), "WHERE customerId = '$customerIdSan'", 1);
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

		// businessId
		public function getBusinessId(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "businessId", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setBusinessId(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'businessId', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// addedByAdminId
		public function getAddedByAdminId(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "addedByAdminId", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setAddedByAdminId(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'addedByAdminId', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// name (array of all three [surname, firstName, Lastname])
		public function getName(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$surnameResult = $this->getCustomerValue($customerId, "surname", $stripTags, $escapeHtmlSpecialChars);
			$firstNameResult = $this->getCustomerValue($customerId, "firstName", $stripTags, $escapeHtmlSpecialChars);
			$lastNameResult = $this->getCustomerValue($customerId, "lastName", $stripTags, $escapeHtmlSpecialChars);

			$nameArray = array();
			if ($surnameResult) {
				array_push($nameArray, array("surname" => $surnameResult));
			} else {
				array_push($nameArray, array("surname" => NULL));
			}
			if ($firstNameResult) {
				array_push($nameArray, array("firstName" => $firstNameResult));
			} else {
				array_push($nameArray, array("firstName" => NULL));
			}
			if ($lastNameResult) {
				array_push($nameArray, array("lastName" => $lastNameResult));
			} else {
				array_push($nameArray, array("lastName" => NULL));
			}
			return $nameArray;
		}

		public function setSurname(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'surname', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setFirstName(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'firstName', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setLastName(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'lastName', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// Billing Address (array of all four [address, state, city, zipCode])
		public function getBillingAddress(string $businessId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$billAddressResult = $this->getCustomerValue($businessId, "billAddress", $stripTags, $escapeHtmlSpecialChars);
			$billCityResult = $this->getCustomerValue($businessId, "billCity", $stripTags, $escapeHtmlSpecialChars);
			$billStateResult = $this->getCustomerValue($businessId, "billState", $stripTags, $escapeHtmlSpecialChars);
			$billZipCodeResult = $this->getCustomerValue($businessId, "billZipCode", $stripTags, $escapeHtmlSpecialChars);

			$billAddressArray = array();
			if ($billAddressResult) {
				array_push($billAddressArray, array("billAddress" => $billAddressResult));
			} else {
				array_push($billAddressArray, array("billAddress" => NULL));
			}
			if ($billCityResult) {
				array_push($billAddressArray, array("billCity" => $billCityResult));
			} else {
				array_push($billAddressArray, array("billCity" => NULL));
			}
			if ($billStateResult) {
				array_push($billAddressArray, array("billState" => $billStateResult));
			} else {
				array_push($billAddressArray, array("billState" => NULL));
			}
			if ($billZipCodeResult) {
				array_push($billAddressArray, array("billZipCode" => $billZipCodeResult));
			} else {
				array_push($billAddressArray, array("billZipCode" => NULL));
			}
			return $billAddressArray;
		}
		public function setBillAddress(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'billAddress', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setBillCity(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'billCity', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setBillState(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'billState', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		public function setBillZipCode(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'billZipCode', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// creditCache
		public function getCreditCache(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "creditCache", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setCreditCache(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'creditCache', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overrideCreditAlertIsEnabled
		public function getOverrideCreditAlertIsEnabled(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overrideCreditAlertIsEnabled", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverrideCreditAlertIsEnabled(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overrideCreditAlertIsEnabled', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overrideCreditAlertAmount
		public function getOverrideCreditAlertAmount(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overrideCreditAlertAmount", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverrideCreditAlertAmount(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overrideCreditAlertAmount', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overrideAutoApplyCredit
		public function getOverrideAutoApplyCredit(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overrideAutoApplyCredit", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverrideAutoApplyCredit(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overrideAutoApplyCredit', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// balanceCache
		public function getBalanceCache(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "balanceCache", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setBalanceCache(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'balanceCache', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overrideBalanceAlertIsEnabled
		public function getOverrideBalanceAlertIsEnabled(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overrideBalanceAlertIsEnabled", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverrideBalanceAlertIsEnabled(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overrideBalanceAlertIsEnabled', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overrideBalanceAlertAmount
		public function getOverrideBalanceAlertAmount(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overrideBalanceAlertAmount", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverrideBalanceAlertAmount(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overrideBalanceAlertAmount', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// allowCZSignIn
		public function getAllowCZSignIn(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "allowCZSignIn", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setAllowCZSignIn(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'allowCZSignIn', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// discountPercent
		public function getDiscountPercent(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "discountPercent", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setDiscountPercent(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'discountPercent', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// overridePaymentTerm
		public function getOverridePaymentTerm(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "overridePaymentTerm", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setOverridePaymentTerm(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'overridePaymentTerm', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// notes
		public function getNotes(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "notes", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setNotes(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'notes', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// dateTimeAdded
		public function getDateTimeAdded(string $customerId, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$result = $this->getCustomerValue($customerId, "dateTimeAdded", $stripTags, $escapeHtmlSpecialChars);
			if ($result) {
				return $result;
			}
			return false;
		}
		public function setDateTimeAdded(string $customerId, string $data) {
			$result = $this->setCustomerValue($customerId, 'dateTimeAdded', $data);
			if ($result !== true) {
				return false;
			}
			return true;
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions perform operations related to customers
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// addCustomer
		public function addCustomer($surname = NULL, $firstName, $lastName, $email = NULL, $phonePrefix = NULL, $phone1 = NULL, $phone2 = NULL, $phone3 = NULL, $billAddress = NULL, $billCity = NULL, $billState = NULL, $billZipCode = NULL, $overrideCreditAlertIsEnabled = NULL, $overrideCreditAlertAmount = NULL, $overrideAutoApplyCredit = NULL, $overrideBalanceAlertIsEnabled = NULL, $overrideBalanceAlertAmount = NULL, $allowCZSignIn = 1, $discountPercent = 0, $overridePaymentTerm = NULL, $notes = NULL) {

		}


		// removeCustomer

	}

?>