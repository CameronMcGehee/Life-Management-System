<?php

	class admin {

		private string $setType;
		private database $db;

		private string $dbAdminId; // Used when updating the table incase the adminId has been changed after instantiation

		public bool $existed; // Used to see whether the given entity existed already (in the database) at the time of instantiation

		private $cryptoKey;
		private $fieldsToEncrypt = array('firstName', 'lastName');

		// Main database attributes
		public $adminId;
		public $username;
		public $password;
		public $email;
		public $firstName;
		public $lastName;
		public $profilePicture;
		public $allowSignIn;
		public $dateTimeJoined;
		public $dateTimeLeft;

		// Arrays to store linked data.
		public $loginAttempts = array();
		public $savedLogins = array();
		public $ownedBusinesses = array();
		public $sharedBusinesses = array();
		public $businesses = array();
		public $customerServiceMessages = array();
		public $estimateApprovals = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			$this->username = '';
			$this->password = '';
			$this->email = '';
			$this->firstName = '';
			$this->lastName = '';
			$this->profilePicture = NULL;
			$this->allowSignIn = '1';
			// Default dateTimeJoined to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->dateTimeJoined = $currentDateTime->format('Y-m-d H:i:s');
			$this->dateTimeLeft = NULL;

			// Clear arrays
			$this->loginAttempts = array();
			$this->savedLogins = array();
			$this->ownedBusinesses = array();
			$this->sharedBusinesses = array();
			$this->businesses = array();
			$this->customerServiceMessages = array();
			$this->estimateApprovals = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $adminId = '') {

			// Include encryption tools since this class contains encrypted data
			require_once dirname(__FILE__)."/../etc/crypto/encryptString.php";
			require_once dirname(__FILE__)."/../etc/crypto/decryptString.php";
			$this->cryptoKey = include dirname(__FILE__)."/../../config/cryptoKey.php";

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			// Fetch from database
			$fetch = $this->db->select('admin', '*', "WHERE adminId ='".$this->db->sanitize($adminId)."'");

			// If adminId already exists then set the set method type to UPDATE and fetch the values for the admin
			if ($fetch) {
				$this->adminId = $adminId;
				$this->username = $fetch[0]['username'];
				$this->password = $fetch[0]['password'];
				$this->email = $fetch[0]['email'];
				$this->firstName = $fetch[0]['firstName'];
				$this->lastName = $fetch[0]['lastName'];
				$this->profilePicture = $fetch[0]['profilePicture'];
				$this->allowSignIn = $fetch[0]['allowSignIn'];
				$this->dateTimeJoined = $fetch[0]['dateTimeJoined'];
				$this->dateTimeLeft = $fetch[0]['dateTimeLeft'];

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

			// If adminId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new adminId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('admin', 'adminId');
				$this->adminId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbAdminId = $this->adminId;
			
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function pullLoginAttempts ($params = '') {
			$this->loginAttempts = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminLoginAttempt', 'adminLoginAttemptId', "WHERE adminId = '$this->dbAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->loginAttempts, $row['adminLoginAttemptId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullSavedLogins($params = '') {
			$this->savedLogins = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminSavedLogin', 'adminSavedLoginId', "WHERE adminId = '$this->dbAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->savedLogins, $row['adminSavedLoginId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		public function pullOwnedBusinesses($params = '') {
			$this->ownedBusinesses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', 'businessId', "WHERE adminId = '$this->dbAdminId' AND adminIsOwner = '1'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->ownedBusinesses, $row['businessId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullSharedBusinesses($params = '') {
			$this->sharedBusinesses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', 'businessId', "WHERE adminId = '$this->dbAdminId' AND adminIsOwner = '0'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->sharedBusinesses, $row['businessId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}
		
		public function pullBusinesses($params = '') {
			$this->businesses = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', 'businessId', "WHERE adminId = '$this->dbAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->businesses, $row['businessId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullCustomerServiceMessages($params = '') {
			$this->customerServiceMessages = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminCustomerServiceMessage', 'adminCustomerServiceMessageId', "WHERE adminId = '$this->dbAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->customerServiceMessages, $row['adminCustomerServiceMessageId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		public function pullEstimateApprovals($params = '') {
			$this->estimateApprovals = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('estimateApproval', 'estimateApprovalId', "WHERE approvedByAdminId = '$this->dbAdminId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->estimateApprovals, $row['estimateApprovalId']);
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
		// Extra bridge data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function getBusinessPermissions($businessId) {
			// If there are entries, push them to the array
			$fetch = $this->db->select('adminBusinessBridge', '*', "WHERE adminId = '$this->dbAdminId' AND businessId = '".$this->db->sanitize($businessId)."'");
			if ($fetch) {
				return array(
					'isOwner' => $fetch[0]['adminIsOwner'],
					'canManageTag' => $fetch[0]['adminCanManageTag'],
					'canUploadDocument' => $fetch[0]['adminCanUploadDocument'],
					'canManageBlog' => $fetch[0]['adminCanManageBlog'],
					'canManageSMS' => $fetch[0]['adminCanManageSMS'],
					'canManageEmail' => $fetch[0]['adminCanManageEmail'],
					'canManageServiceListing' => $fetch[0]['adminCanManageServiceListing'],
					'canManageQuoteRequest' => $fetch[0]['adminCanManageQuoteRequest'],
					'canManageCustomerService' => $fetch[0]['adminCanManageCustomerService'],
					'canManageTimeLog' => $fetch[0]['adminCanManageTimeLog'],
					'canManagePayrollDue' => $fetch[0]['adminCanManagePayrollDue'],
					'canManagePayrollSatisfaction' => $fetch[0]['adminCanManagePayrollSatisfaction'],
					'canManageCustomer' => $fetch[0]['adminCanManageCustomer'],
					'canManageStaff' => $fetch[0]['adminCanManageStaff'],
					'canManageCrew' => $fetch[0]['adminCanManageCrew'],
					'canManageEquipment' => $fetch[0]['adminCanManageEquipment'],
					'canManageChemical' => $fetch[0]['adminCanManageChemical'],
					'canManageJob' => $fetch[0]['adminCanManageJob'],
					'canManageInvoice' => $fetch[0]['adminCanManageInvoice'],
					'canManagePayment' => $fetch[0]['adminCanManagePayment'],
					'canManageEstimate' => $fetch[0]['adminCanManageEstimate'],
					'canApproveEstimate' => $fetch[0]['adminCanApproveEstimate']
				);
			} elseif ($this->db->getLastError() === '') {
					return false;
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
				'adminId' => $this->dbAdminId,
				'username' => $this->username,
				'password' => $this->password,
				'email' => $this->email,
				'firstName' => $this->firstName,
				'lastName' => $this->lastName,
				'profilePicture' => $this->profilePicture,
				'allowSignIn' => $this->allowSignIn,
				'dateTimeJoined' => $this->dateTimeJoined,
				'dateTimeLeft' => $this->dateTimeLeft
			);

			// Encrypt encrypted data
			foreach ($this->fieldsToEncrypt as $field) {
				if ($attr[$field] != NULL) {
					$attr[$field] = encryptString((string)$attr[$field], $this->cryptoKey);
				}
			}

			$attributes = array(
				'adminId' => $this->db->sanitize($this->dbAdminId),
				'username' => $this->db->sanitize($attr['username']),
				'password' => $this->db->sanitize($attr['password']),
				'email' => $this->db->sanitize($attr['email']),
				'firstName' => $this->db->sanitize($attr['firstName']),
				'lastName' => $this->db->sanitize($attr['lastName']),
				'profilePicture' => $this->db->sanitize($attr['profilePicture']),
				'allowSignIn' => $this->db->sanitize($attr['allowSignIn']),
				'dateTimeJoined' => $this->db->sanitize($attr['dateTimeJoined']),
				'dateTimeLeft' => $this->db->sanitize($attr['dateTimeLeft'])
			);

			if ($this->setType == 'UPDATE') {
				// Update the values in the database after sanitizing them
				if ($this->db->update('admin', $attributes, "WHERE adminId = '".$this->db->sanitize($this->dbAdminId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}
			} else {
				// Insert the values to the database after sanitizing them
				if ($this->db->insert('admin', $attributes)) {
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
			if (!$this->db->delete('admin', "WHERE adminId = '".$this->db->sanitize($this->dbAdminId)."'", 1)) {
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
