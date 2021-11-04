<?php

    // MAIN CMS/BACKEND/ADMIN FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class adminLoginManager {

		function __construct() {
			require_once dirname(__FILE__)."/../database.php";
			$this->databaseManager = new database;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions are run to check the current login session, and if not, redirect the user to a specified page
		// These function(s) are meant to be run RIGHT AFTER THE 'startSession.php' file
		// on every page that outputs information that is sensitive to a user, or that requires a login to function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public static function cmsVerifyAdminLoginRedirect(string $loginLocation = './', string $businessSelectLocation = './') {
			if (!isset($_SESSION['ultiscape_adminId']) && !isset($_SESSION['ultiscape_businessId'])) {
				header("location: ".$loginLocation."login");
			} elseif (isset($_SESSION['ultiscape_adminId']) && !isset($_SESSION['ultiscape_businessId'])) {
				header("location: ".$businessSelectLocation."selectBusiness");
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The check and get login parameters for tasks such as login and verification
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function adminExists(string $adminId) {
			$adminId = $this->databaseManager->sanitize($adminId);
			$result = $this->databaseManager->select("admin", "adminId", "WHERE adminId = '$adminId'");

			if ($result) {
				if (gettype($result) == 'array') {
					return true;
				}
			}

			return false;
		}
		
		function emailExists(string $email, bool $returnAdminId = false) {
			$email = $this->databaseManager->sanitize($email);
			$result = $this->databaseManager->select("admin", "adminId", "WHERE email = '$email'");

			if ($result) {
				if (gettype($result) == 'array') {
					if ($returnAdminId) {
						return $result[0]['adminId'];
					} else {
						return true;
					}
				}
			}

			return false;
		}

		function usernameExists(string $username, bool $returnAdminId = false) {
			$username = $this->databaseManager->sanitize($username);
			$result = $this->databaseManager->select("admin", "adminId", "WHERE username = '$username'");

			if ($result) {
				if (gettype($result) == 'array') {
					if ($returnAdminId) {
						return $result[0]['adminId'];
					} else {
						return true;
					}
				}
			}

			return false;
		}

		function passwordExists(string $password, bool $returnAdminIds = false) {
			$password = $this->databaseManager->sanitize($password);
			$result = $this->databaseManager->select("admin", "adminId", "WHERE password = '$password'");

			if ($result) {
				if (gettype($result) == 'array') {
					if ($returnAdminIds) {
						$adminIds = [];
						foreach ($result as $row) {
							array_push($adminIds, $row['adminId']);
						}
						return $adminIds;
					} else {
						return true;
					}
				}
			}

			return false;
		}
		
		function getUsername(string $adminId) {
			$adminId = $this->databaseManager->sanitize($adminId);
			$result = $this->databaseManager->select("admin", "username", "WHERE adminId = '$adminId'");

			if ($result) {
				if (gettype($result) == 'array') {
					return $result[0]['username'];
				}
			}

			return false;
		}

		function getEmail(string $adminId) {
			$adminId = $this->databaseManager->sanitize($adminId);
			$result = $this->databaseManager->select("admin", "username", "WHERE adminId = '$adminId'");

			if ($result) {
				if (gettype($result) == 'array') {
					return $result[0]['username'];
				}
			}

			return false;
		}

		function getPassword(string $adminId) {
			$adminId = $this->databaseManager->sanitize($adminId);
			echo $adminId;
			$result = $this->databaseManager->select("admin", "password", "WHERE adminId = '$adminId'");

			if ($result) {
				if (gettype($result) == 'array') {
					return $result[0]['password'];
				}
			}

			return false;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// The following functions manage the status of the cms login
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function login(string $adminId, bool $saveLogin = false) {
			if ($this->adminExists($adminId)) {
				$_SESSION['ultiscape_adminId'] = $adminId;

				if ($saveLogin) {
					$this->saveLogin();
				}
				return true;
			}
			return false;
		}

		function setBusiness(string $businessId) {
			// check if the business Id exits in the businessManager class once that is made

			$_SESSION['ultiscape_businessId'] = $businessId;
			return true;
		}

		static function logout() {
			session_unset();
			return true;
		}

		function saveLogin() {

			// Save the login in the database of saved logins, storing a random code in a cookie and in the database that is associated with an adminId to log in as

			// Still need to make the table of stored logins

		}

		function destroySavedLogin() {

			// Remove the record of a saved login from the database table. Usually done when logging out.

		}

	}

?>
