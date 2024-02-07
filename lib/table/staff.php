<?php

	class staff {

		private string $setType;
		private database $db;

		private string $dbStaffId; // Used when updating the table incase the staffId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('firstName', 'lastName', 'payrollAddress1', 'payrollAddress2', 'payrollCity', 'payrollState', 'payrollZipCode', 'password');

		// Main database attributes
		public $staffId;
		public $workspaceId;
		public $firstName;
		public $lastName;
		public $profilePicture;
		public $calendarEventTitle;
		public $bio;
		public $payrollAddress1;
		public $payrollAddress2;
		public $payrollState;
		public $payrollCity;
		public $payrollZipCode;
		public $overridePayrollType;
		public $overrideHourlyRate;
		public $overridePerCalendarEventRate;
		public $overrideCalendarEventPercentage;
		public $payrollDueCache;
		public $advancePaymentCache;
		public $allowSignIn;
		public $password;
		public $discountPercent;
		public $overridePaymentTerm;
		public $dateTimeAdded;

		// Arrays to store linked data.
		public $loginAttempts = array();
		public $savedLogins = array();
		public $phoneNumbers = array();
		public $emailAddresses = array();
		public $tags = array();
		public $leaderCrews = array();
		public $staffCrews = array();
		public $calendarEventSingulars = array();
		public $calendarEventRecurrings = array();
		public $calendarEventCompleteds = array();
		public $timeLogs = array();
		public $payrollDues = array();
		public $payrollSatisfactions = array();

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
			$this->firstName = '';
			$this->lastName = NULL;
			$this->profilePicture = NULL;
			$this->calendarEventTitle = NULL;
			$this->bio = NULL;
			$this->payrollAddress1 = NULL;
			$this->payrollAddress2 = NULL;
			$this->payrollState = NULL;
			$this->payrollCity = NULL;
			$this->payrollZipCode = NULL;
			$this->overridePayrollType = NULL;
			$this->overrideHourlyRate = NULL;
			$this->overridePerCalendarEventRate = NULL;
			$this->overrideCalendarEventPercentage = NULL;
			$this->payrollDueCache = '0';
			$this->advancePaymentCache = '0';
			$this->allowSignIn = '1';
			$this->password = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();
			$this->phoneNumbers = array();
			$this->emailAddresses = array();
			$this->tags = array();
			$this->leaderCrews = array();
			$this->staffCrews = array();
			$this->calendarEventSingulars = array();
			$this->calendarEventRecurrings = array();
			$this->calendarEventCompleteds = array();
			$this->timeLogs = array();
			$this->payrollDues = array();
			$this->payrollSatisfactions = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $staffId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('staff', '*', "WHERE staffId ='".$this->db->sanitize($staffId)."'");

			// If staffId already exists then set the set method type to UPDATE and fetch the values for the staff
			if ($fetch) {
				$this->staffId = $staffId;
				$this->workspaceId = $fetch[0]['workspaceId'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->profilePicture = $fetch[0]['profilePicture'];
				$this->calendarEventTitle = $fetch[0]['calendarEventTitle'];
				$this->bio = $fetch[0]['bio'];
				$this->payrollAddress1 = $fetch[0]['payrollAddress1'];
				$this->payrollAddress2 = $fetch[0]['payrollAddress2'];
				$this->payrollState = $fetch[0]['payrollState'];
				$this->payrollCity = $fetch[0]['payrollCity'];
				$this->payrollZipCode = $fetch[0]['payrollZipCode'];
				$this->overridePayrollType = $fetch[0]['overridePayrollType'];
				$this->overrideHourlyRate = $fetch[0]['overrideHourlyRate'];
				$this->overridePerCalendarEventRate = $fetch[0]['overridePerCalendarEventRate'];
				$this->overrideCalendarEventPercentage = $fetch[0]['overrideCalendarEventPercentage'];
				$this->payrollDueCache = $fetch[0]['payrollDueCache'];
				$this->advancePaymentCache = $fetch[0]['advancePaymentCache'];
				$this->allowSignIn = $fetch[0]['allowSignIn'];
				$this->password = $fetch[0]['password'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				// Decrypt encrypted data
				foreach ($this->fieldsToEncrypt as $field) {
					if (!is_null($this->{$field}) && !empty($this->{$field})) {
						$this->{$field} = decryptString((string)$this->{$field}, $this->cryptoKey);
					}
					if ($this->{$field} === false) {
						$this->{$field} = 'decryptError';
					}
				}

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If staffId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new staffId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('staff', 'staffId');
				$this->staffId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbStaffId = $this->staffId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// loginAttempts
		public function pullLoginAttempts($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffLoginAttempt', 'staffLoginAttemptId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['staffLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// savedLogins
		public function pullSavedLogins($params = '') {
			$this->savedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffSavedLogin', 'staffSavedLoginId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['staffSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// phoneNumbers
		public function pullPhoneNumbers($params = '') {
			$this->phoneNumbers = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffPhoneNumber', 'staffPhoneNumberId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->phoneNumbers, $row['staffPhoneNumberId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// emailAddresses
		public function pullEmailAddresses($params = '') {
			$this->emailAddresses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffEmailAddress', 'staffEmailAddressId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->emailAddresses, $row['staffEmailAddressId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// tags
		public function pullTags($params = '') {
			$this->tags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('staffTag', 'staffTagId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->tags, $row['staffTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// leaderCrews
		public function pullLeaderCrews($params = '') {
			$this->leaderCrews = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crewLeaderBridge', 'crewId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->leaderCrews, $row['crewId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// staffCrews
		public function pullStaffCrews($params = '') {
			$this->leaderCrews = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('crewStaffBridge', 'crewId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->leaderCrews, $row['crewId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventSingulars
		public function pullCalendarEventSingulars($params = '') {
			$this->calendarEventSingulars = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventSingularStaffBridge', 'calendarEventSingularStaffBridgeId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventSingulars, $row['calendarEventSingularId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventRecurrings
		public function pullCalendarEventRecurrings($params = '') {
			$this->calendarEventRecurrings = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventRecurringStaffBridge', 'calendarEventRecurringStaffBridgeId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventRecurrings, $row['calendarEventRecurringId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// calendarEventCompleteds
		public function pullCalendarEventCompleteds($params = '') {
			$this->calendarEventCompleteds = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('calendarEventCompletedStaffBridge', 'calendarEventCompletedStaffBridgeId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->calendarEventCompleteds, $row['calendarEventCompletedId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// timeLogs
		public function pullTimeLogs($params = '') {
			$this->timeLogs = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('timeLog', 'timeLogId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->timeLogs, $row['timeLogId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payrollDues
		public function pullPayrollDues($params = '') {
			$this->payrollDues = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollDue', 'payrollDueId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payrollDues, $row['payrollDueId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// payrollSatisfactions
		public function pullPayrollSatisfactions($params = '') {
			$this->payrollSatisfactions = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('payrollSatisfaction', 'payrollSatisfactionId', "WHERE staffId = '$this->dbStaffId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->payrollSatisfactions, $row['payrollSatisfactionId']);
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

			$attr = array(
				'staffId' => $this->dbStaffId,
				'workspaceId' => $this->workspaceId,
				'firstName' => $this->firstName,
				'lastName' => $this->lastName,
				'profilePicture' => $this->profilePicture,
				'calendarEventTitle' => $this->calendarEventTitle,
				'bio' => $this->bio,
				'payrollAddress1' => $this->payrollAddress1,
				'payrollAddress2' => $this->payrollAddress2,
				'payrollState' => $this->payrollState,
				'payrollCity' => $this->payrollCity,
				'payrollZipCode' => $this->payrollZipCode,
				'overridePayrollType' => $this->overridePayrollType,
				'overrideHourlyRate' => $this->overrideHourlyRate,
				'overridePerCalendarEventRate' => $this->overridePerCalendarEventRate,
				'overrideCalendarEventPercentage' => $this->overrideCalendarEventPercentage,
				'payrollDueCache' => $this->payrollDueCache,
				'advancePaymentCache' => $this->advancePaymentCache,
				'allowSignIn' => $this->allowSignIn,
				'password' => $this->password,
				'dateTimeAdded' => $this->dateTimeAdded
			);

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if ($attr[$field] != NULL) {
					$attr[$field] = encryptString((string)$attr[$field], $this->cryptoKey);
				}
			}

			$attributes = array(
				'staffId' => $this->db->sanitize($this->dbStaffId),
				'workspaceId' => $this->db->sanitize($attr['workspaceId']),
				'firstName' => $this->db->sanitize($attr['firstName']),
				'lastName' => $this->db->sanitize($attr['lastName']),
				'profilePicture' => $this->db->sanitize($attr['profilePicture']),
				'calendarEventTitle' => $this->db->sanitize($attr['calendarEventTitle']),
				'bio' => $this->db->sanitize($attr['bio']),
				'payrollAddress1' => $this->db->sanitize($attr['payrollAddress1']),
				'payrollAddress2' => $this->db->sanitize($attr['payrollAddress2']),
				'payrollState' => $this->db->sanitize($attr['payrollState']),
				'payrollCity' => $this->db->sanitize($attr['payrollCity']),
				'payrollZipCode' => $this->db->sanitize($attr['payrollZipCode']),
				'overridePayrollType' => $this->db->sanitize($attr['overridePayrollType']),
				'overrideHourlyRate' => $this->db->sanitize($attr['overrideHourlyRate']),
				'overridePerCalendarEventRate' => $this->db->sanitize($attr['overridePerCalendarEventRate']),
				'overrideCalendarEventPercentage' => $this->db->sanitize($attr['overrideCalendarEventPercentage']),
				'payrollDueCache' => $this->db->sanitize($attr['payrollDueCache']),
				'advancePaymentCache' => $this->db->sanitize($attr['advancePaymentCache']),
				'allowSignIn' => $this->db->sanitize($attr['allowSignIn']),
				'password' => $this->db->sanitize($attr['password']),
				'dateTimeAdded' => $this->db->sanitize($attr['dateTimeAdded'])
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('staff', $attributes, "WHERE staffId = '".$this->db->sanitize($this->dbStaffId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('staff', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}
			}
			return true;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('staff', "WHERE staffId = '".$this->db->sanitize($this->dbStaffId)."'", 1)) {
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
