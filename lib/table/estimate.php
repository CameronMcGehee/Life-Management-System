<?php

	class estimate {

		private string $setType;
		private database $db;

		private string $dbEstimateId; // Used when updating the table incase the estimateId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $estimateId;
		public $workspaceId;
		public $docIdId;
		public $contactId;
		public $discountIsPercent;
		public $discount;
		public $customCalendarEventDetails;
		public $comments;
		public $privateNotes;
		public $isViewed;
		public $isEmailed;
		public $approvedByAdminId;
		public $adminReason;
		public $dateTimeApproved;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $items = array();
		public $approvals = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			// Default workspaceId to the currently selected workspace
			if (isset($_SESSION['lifems_workspaceId'])) {
				$this->workspaceId = $_SESSION['lifems_workspaceId'];
			} else {
				$this->workspaceId = '';
			}
			$this->docIdId = '';
			$this->contactId = NULL;
			$this->discountIsPercent = NULL;
			$this->discount = '0';
			$this->customCalendarEventDetails = NULL;
			$this->comments = NULL;
			$this->privateNotes = NULL;
			$this->isViewed = '0';
			$this->isEmailed = '0';
			$this->approvedByAdminId = NULL;
			$this->adminReason = NULL;
			$this->dateTimeApproved = NULL;
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->items = array();
			$this->approvals = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $estimateId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('estimate', '*', "WHERE estimateId ='".$this->db->sanitize($estimateId)."'");

			// If estimateId already exists then set the set method type to UPDATE and fetch the values for the estimate
			if ($fetch) {
				$this->estimateId = $estimateId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->docIdId = $fetch[0]['docIdId'];
				$this->contactId = $fetch[0]['contactId'];
				$this->discountIsPercent = $fetch[0]['discountIsPercent'];
				$this->discount = $fetch[0]['discount'];
				$this->customCalendarEventDetails = $fetch[0]['customCalendarEventDetails'];
				$this->comments = $fetch[0]['comments'];
				$this->privateNotes = $fetch[0]['privateNotes'];
				$this->isViewed = $fetch[0]['isViewed'];
				$this->isEmailed = $fetch[0]['isEmailed'];
				$this->approvedByAdminId = $fetch[0]['approvedByAdminId'];
				$this->adminReason = $fetch[0]['adminReason'];
				$this->dateTimeApproved = $fetch[0]['dateTimeApproved'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If estimateId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new estimateId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('estimate', 'estimateId');
				$this->estimateId = $uuid->generatedId;
				
				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbEstimateId = $this->estimateId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// items
		public function pullItems($params = '') {
			$this->items = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateItem', 'estimateItemId', "WHERE estimateId = '$this->dbEstimateId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->items, $row['estimateItemId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'estimateId' => $this->db->sanitize($this->dbEstimateId),
				'workspaceId' => $this->db->sanitize($this->workspaceId),
				'docIdId' => $this->db->sanitize($this->docIdId),
				'contactId' => $this->db->sanitize($this->contactId),
				'discountIsPercent' => $this->db->sanitize($this->discountIsPercent),
				'discount' => $this->db->sanitize($this->discount),
				'customCalendarEventDetails' => $this->db->sanitize($this->customCalendarEventDetails),
				'comments' => $this->db->sanitize($this->comments),
				'privateNotes' => $this->db->sanitize($this->privateNotes),
				'isViewed' => $this->db->sanitize($this->isViewed),
				'isEmailed' => $this->db->sanitize($this->isEmailed),
				'approvedByAdminId' => $this->db->sanitize($this->approvedByAdminId),
				'adminReason' => $this->db->sanitize($this->adminReason),
				'dateTimeApproved' => $this->db->sanitize($this->dateTimeApproved),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('estimate', $attributes, "WHERE estimateId = '".$this->db->sanitize($this->dbEstimateId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('estimate', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('estimate', "WHERE estimateId = '".$this->db->sanitize($this->dbEstimateId)."'", 1)) {
				return $this->db->getLastError();
			}

			$this->setToDefaults();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
