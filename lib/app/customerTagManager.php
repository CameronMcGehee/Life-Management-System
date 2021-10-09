<?php

    //CUSTOMER FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------

	class customerTagManager {

		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions get and set customer tag values in the database in order to ensure that everything is sanitized and there is little redundancy
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		private function getCustomerTagValue(string $customerTagId, string $field, bool $stripTags = false, bool $escapeHtmlSpecialChars = true) {
			$customerTagIdSan = $this->databaseManager->sanitize($customerTagId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$result = $this->databaseManager->select("customerTag", "$fieldSan", "WHERE customerTagId = '$customerTagIdSan'");
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

		private function setCustomerTagValue(string $customerTagId, string $field, string $data) {
			$customerTagIdSan = $this->databaseManager->sanitize($customerTagId);
			$fieldSan = $this->databaseManager->sanitize($field);
			$dataSan = $this->databaseManager->sanitize($data);
			$result = $this->databaseManager->update("customerTag", array("$fieldSan" => $dataSan), "WHERE customerTagId = '$customerTagIdSan'", 1);
			if ($result !== true) {
				return false;
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions retrieve and set values for attributes of a customer tag
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// businessId



		// customerId



		// tagName



		// dateTimeAdded

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions perform operations related to customer tags
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// addTagToCustomer



		// removeTagFromCustomer



		// getTagsForCustomer

	}

?>
