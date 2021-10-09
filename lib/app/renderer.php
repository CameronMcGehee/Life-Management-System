<?php

    // HTML OUTPUT/DISPLAY FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class renderer {
		
		function __construct() {
			require_once dirname(__FILE__)."/databaseManager.php";
			$this->databaseManager = new databaseManager;
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Renders related to the general site
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function renderAdminHtmlTop (string $rootPathPrefix = './', string $pageTitle = '', string $pageDescription = 'UltiScape CMS') {
			$output = '';

			$output .= '<!DOCTYPE html>';
			$output .= '<html lang="en">';
			$output .= '<head>';
				$output .= '<meta charset="UTF-8">';
				$output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
				$output .= '<title>Ultiscape (Admin) - '.$pageTitle.'</title>';
				$output .= '<meta name="description" content="'.$pageDescription.'">';

				// CSS

				$output .= '<link rel="stylesheet" type="text/css" href="'.$rootPathPrefix.'css/app/admin/adminMain.css">';

				// Javascript

				$output .= '<script type="text/javascript" src="'.$rootPathPrefix.'js/jquery-3.6.0.min.js"></script>';

				// Favicon stuff when we have it

				// Do not end head tag so we can add something if needed on a specific page, like javascript

			return $output;
		}

		function renderAdminHtmlBottom (string $rootPathPrefix = './') {
			return '</head>';
		}
		
		function renderAdminTopBar(string $rootPathPrefix = './', bool $showLogo = true, bool $showBusinessSelector = true, bool $showProfileButton = true) {
			$output = '';

			$output .= '<div class="adminTopBarWrapper defaultInsetShadow">';

			$output .= '<div class="xyCenteredFlex" id="ultiscapeLogoWrapper">';
				if ($showLogo) {
					$output .= '<a id="ultiscapeLogoImageWrapper" href="'.$rootPathPrefix.'"><img src="'.$rootPathPrefix.'images/logos/mainLogoTopBarWhiteTrans.png"></a>';
				}
			$output .= '</div>';

			// Blank Space
			$output .= '<div>';
			$output .= '</div>';

			// Business Selector Button
			$output .= '<div id="businessSelectorWrapper">';
				if ($showBusinessSelector) {
					
				}
			$output .= '</div>';

			// Profile Picture Button
			$output .= '<div class="yCenteredFlex flexDirectionRow" id="profileButtonWrapper">';
				if ($showProfileButton) {
					// Check if there is a profile picture set. If the profile picture is set, make sure the file exists. If it does, use it's path
					$fileName = '';
					if (false) {
						if (false) {
							$pfpPath = $rootPathPrefix.'images/uploads/profile/'.$fileName;
						}
					} else {
						$pfpPath = $rootPathPrefix.'images/icons/user_male.svg';
					}
					$output .= '<img id="profilePictureButton" src="'.$pfpPath.'"><img src="'.$rootPathPrefix.'images/icons/chevron_down.svg" id="chevron">';
				}
			$output .= '</div>';
			
			// End main top bar wrapper div
			$output .= '</div>';
			
			return $output;
		}

		function renderAdminTopBarDropdowns(string $rootPathPrefix = './') {
			$output = '';
			
			// Profile Picture button dropdown

			$output .= '<span class="profilePictureButtonDropdownHider" id="pfpMenu"><div class="profilePictureButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">';
				$output .= '<a href="'.$rootPathPrefix.'admin/editprofile"><p>Edit Profile</p></a>';
				$output .= '<a href="'.$rootPathPrefix.'admin/scripts/standalone/logout.script" class="smallButtonWrapper orangeButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/icons/exit_right.svg"></a>';
			$output .= '</div></span>';

			return $output;
		}

		function renderAdminTopBarDropdownScripts(string $rootPathPrefix = './') {
			$output = '';

			// Scripts for dropping them down
			$output .= '
			<script>
			
			$(function() {
				$("#profileButtonWrapper").click(function() {
					$("#pfpMenu").toggle();
				});

				$("#businessSelectorWrapper").click(function() {
					$("#bsMenu").toggle();
				});
			});
			
			</script>';

			return $output;
		}

		function renderAdminSideBar(string $rootPathPrefix = './') {
			$output = '';

			$output .= '<div class="cmsSideBarWrapper">';

			$output .= '<a class="sideBarButton defaultAll4InsetShadow" id="button1" href="'.$rootPathPrefix.'admin/people"><img src="'.$rootPathPrefix.'images/icons/users.svg"><p>People</p></a>';
			$output .= '<a class="sideBarButton defaultAll4InsetShadow" id="button2" href="'.$rootPathPrefix.'admin/communications"><img src="'.$rootPathPrefix.'images/icons/thread.svg"><p>Communications</p></a>';
			$output .= '<a class="sideBarButton defaultAll4InsetShadow" id="button3" href="'.$rootPathPrefix.'admin/jobs"><img src="'.$rootPathPrefix.'images/icons/calendar_month.svg"><p>Jobs</p></a>';
			$output .= '<a class="sideBarButton defaultAll4InsetShadow" id="button4" href="'.$rootPathPrefix.'admin/documents"><img src="'.$rootPathPrefix.'images/icons/document.svg"><p>Documents</p></a>';
			$output .= '<a class="sideBarButton defaultAll4InsetShadow" id="button5" href="'.$rootPathPrefix.'admin/inventory"><img src="'.$rootPathPrefix.'images/icons/archive.svg"><p>Inventory</p></a>';
			$output .= '<div id="smallBottomLinks"><a href="'.$rootPathPrefix.'admin/overview">Overview</a> | <a href="'.$rootPathPrefix.'admin/sitemap">Sitemap</a></div>';

			$output .= '</div>';
			
			return $output;
		}

		function renderAdminFooter(string $rootPathPrefix = './', $isLoginPage = false) {
			$output = '';

			if ($isLoginPage) {
				$class = 'cmsLoginFooterWrapper';
			} else {
				$class = 'cmsFooterWrapper';
			}

			$date = date('Y');

			$output .= '<div class="'.$class.' defaultInsetShadow"><p>Copyright &copy <a target="_blank" href="https://cameronmcgehee.com">McGehee Enterprises</a> '.$date.'</p></div>';
			
			return $output;
		}

		function renderAdminMobileNavBar(string $rootPathPrefix = './', bool $showError = true) {
			$output = '';

			$output .= '<div class="cmsMobileNavBarWrapper">';

			// If user is signed in and business is selected then render the buttons, otherwise print an error.

			if (isset($_SESSION['ultiscape_adminId']) && isset($_SESSION['ultiscape_businessId'])) {
				$output .= '<div class="mobileNavBarButtonArray">';
					$output .= '<a class="button" id="button1" href="'.$rootPathPrefix.'admin/people"><img src="'.$rootPathPrefix.'images/icons/users.svg"><p>People</p></a>';
					$output .= '<a class="button" id="button2" href="'.$rootPathPrefix.'admin/communications"><img src="'.$rootPathPrefix.'images/icons/thread.svg"><p>Comms</p></a>';
					$output .= '<a class="button" id="button3" href="'.$rootPathPrefix.'admin/jobs"><img src="'.$rootPathPrefix.'images/icons/calendar_month.svg"><p>Jobs</p></a>';
					$output .= '<a class="button" id="button4" href="'.$rootPathPrefix.'admin/documents"><img src="'.$rootPathPrefix.'images/icons/document.svg"><p>Docs</p></a>';
					$output .= '<a class="button" id="button5" href="'.$rootPathPrefix.'admin/inventory"><img src="'.$rootPathPrefix.'images/icons/archive.svg"><p>Inventory</p></a>';
				$output .= '</div>';
			} else {
				$output .= '<p class="margin90 textCentered">Please login and select business.</p>';
			}
			
			$output .= '</div>';
			
			return $output;
		}

	}

?>
