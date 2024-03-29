<?php

    // HTML OUTPUT/DISPLAY FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class adminUIRender {

		private $config;
		private $database;
		
		function __construct() {
			require_once dirname(__FILE__)."/database.php";
			$this->database = new database;

			$this->config = $GLOBALS['lifemsConfig'];
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Renders related to the general site
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function renderAdminHtmlTop (string $rootPathPrefix = './', array $options =[
			"pageTitle" => "",
			"pageDescription" => "LifeMS"
		]) {
			$output = '';

			$output .= '<!DOCTYPE html>
			';
			$output .= '<html lang="en">
			';
			$output .= '<head>
			';
				$output .= '<meta charset="UTF-8">
				';
				$output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">
				';
				$output .= '<title>'.$options["pageTitle"].' - LifeMS (Admin)</title>
				';
				$output .= '<meta name="description" content="'.$options["pageDescription"].'">
				';

				// CSS

				$output .= '<link rel="stylesheet" type="text/css" href="'.$rootPathPrefix.'css/app/main.css">
				';
				$output .= '<link rel="stylesheet" href="'.$rootPathPrefix.'css/font-awesome-4.7.0/css/font-awesome.min.css">
				';

				// Javascript

				$output .= '<script type="text/javascript" src="'.$rootPathPrefix.'js/jquery-3.6.0.min.js"></script>
				';

				// Favicon stuff when we have it

				// Do not end head tag so we can add something if needed on a specific page, like javascript

			return $output;
		}

		function renderAdminHtmlBottom (string $rootPathPrefix = './') {
			return '</html>
			';
		}
		
		function renderAdminTopBar(string $rootPathPrefix = './', bool $showLogo = true, bool $showWorkspaceSelector = true, bool $showProfileButton = true) {
			$output = '';

			$output .= '<div class="adminTopBarWrapper defaultInsetShadow">
			';

			$output .= '<div class="xyCenteredFlex" id="lifemsLogoWrapper">
			';
				if ($showLogo) {
					$output .= '<a class="noUnderline" href="'.$rootPathPrefix.'"><h2>LifeMS</h2></a>
					';
				}
			$output .= '</div>
			';

			// Blank Space
			$output .= '<div></div>
			';

			// Workspace Selector Button
			$output .= '<div class="yCenteredFlex flexDirectionRow" id="workspaceSelectorButtonWrapper">
			';
				if ($showWorkspaceSelector) {
					$output .= '<div class="yCenteredFlex flexDirectionRow" id="workspaceSelectorButton">
					';
					require_once dirname(__FILE__)."/table/workspace.php";
					$workspace = new workspace($_SESSION['lifems_workspaceId']);
					$currentWorkspaceLogo = $workspace->fullLogoFile;
					// If the currently selected workspace does not have a logo file, display the default one
					if ($currentWorkspaceLogo == NULL) {
						$bsImgPath = $rootPathPrefix.'images/lifems/etc/noLogo.png';
					} else {
						$bsImgPath = $rootPathPrefix.'images/lifems/uploads/workspace/fullLogoFile/'.$currentWorkspaceLogo;
					}
					
					// Render the button itself
					$output .= '<img id="workspaceSelectorSelectedImg" src="'.$bsImgPath.'"><img src="'.$rootPathPrefix.'images/lifems/icons/chevron_down.svg"></div>
					';
				}
			$output .= '</div>
			';

			// Profile Picture Button
			$output .= '<div class="yCenteredFlex flexDirectionRow" id="profileButtonWrapper">
			';
				if ($showProfileButton) {
					// Check if there is a profile picture set. If the profile picture is set, make sure the file exists. If it does, use it's path
					$fileName = '';
					if (false) {
						if (false) {
							$pfpPath = $rootPathPrefix.'images/lifems/uploads/profile/'.$fileName;
						}
					} else {
						$pfpPath = $rootPathPrefix.'images/lifems/icons/user_male.svg';
					}
					$output .= '<img id="profilePictureButton" src="'.$pfpPath.'"><img src="'.$rootPathPrefix.'images/lifems/icons/chevron_down.svg" class="whiteChevron">
					';
				}
			$output .= '</div>
			';
			
			// End main top bar wrapper div
			$output .= '</div>
			';
			
			return $output;
		}

		function renderAdminTopBarDropdowns(string $rootPathPrefix = './') {
			$output = '';

			// Workspace Selector button dropdown
			$output .= '<span class="workspaceSelectorButtonDropdownHider" id="bsMenu"><div class="workspaceSelectorButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			';
			
				// Eventually for each workspace that belongs to the admin, output a button to switch to that workspace
				$output .= '<p><a href="'.$rootPathPrefix.'admin/selectworkspace">Switch Workspace</a></p>
				';

				// Edit button temporarily until we make it look nice
				$output .= '<p><a href="'.$rootPathPrefix.'admin/workspacesettings">Workspace Settings</a></p>
				';

				// Button to create a new workspace
				$output .= '<br><a href="'.$rootPathPrefix.'admin/createworkspace" class="smallButtonWrapper greenButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/lifems/icons/plus.svg"></a>
				';

			$output .= '</div></span>
			';
			
			// Profile Picture button dropdown
			$output .= '<span class="profilePictureButtonDropdownHider" id="pfpMenu"><div class="profilePictureButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			';
				$output .= '<p><a href="'.$rootPathPrefix.'admin/editprofile">Edit Profile</a></p>
				';
				$output .= '<a href="'.$rootPathPrefix.'admin/scripts/standalone/logout.script" class="smallButtonWrapper orangeButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/lifems/icons/exit_right.svg"></a>
				';
			$output .= '</div></span>
			';

			return $output;
		}

		function renderAdminUIMenuToggleScripts(string $rootPathPrefix = './', bool $showProfileButtonScript = true, bool $showWorkspaceSelectorScript = true, bool $showSideBarMoreMenuScript = true, bool $showMobileNavBarMoreMenuScript = true) {
			$output = '';

			// Scripts for dropping them down
			$output .= '<script>
			';
			
			$output .= '$(function() {
				';

				if ($showProfileButtonScript) {
					$output .= '$("#profileButtonWrapper").click(function() {
									$("#pfpMenu").toggle();

									if($("#bsMenu").is(":visible")) {
										$("#bsMenu").toggle();
									}
								});
								';
				}

				if ($showWorkspaceSelectorScript) {
					$output .= '$("#workspaceSelectorButtonWrapper").click(function() {
									$("#bsMenu").toggle();

									if($("#pfpMenu").is(":visible")) {
										$("#pfpMenu").toggle();
									}
								});
								';
				}

				if ($showSideBarMoreMenuScript) {
					$output .= '$(".sideBarButton#button5").click(function() {
									$("#sideBarMoreMenu").toggle();
								});
								';
				}

				if ($showMobileNavBarMoreMenuScript) {
					$output .= '$(".mobileNavBarButtonArray #button5").click(function() {
									$("#mobileNavBarMoreMenuHider").toggle();
								});
								';
				}

				// Clicking anywhere hides menu scripts

				$output .= '$("html").click(function(e) {                    
								if(!$(e.target).is("#mobileNavBarMoreMenuWrapper") && !$(e.target).parents("#mobileNavBarMoreMenuWrapper").length && !$(e.target).parents("#button5").length){
									$("#mobileNavBarMoreMenuHider").hide();
								}
								if(!$(e.target).is("#sideBarMoreMenu") && !$(e.target).parents("#sideBarMoreMenu").length && !$(e.target).parents(".cmsSideBarWrapper").length){
									$("#sideBarMoreMenu").hide();
								}
							}); ';

				
			$output .= '});
			';
			
			$output .= '</script>
			';

			return $output;
		}

		function renderAdminSideBar(string $rootPathPrefix = './') {
			$output = '';

			$output .= '<div class="cmsSideBarWrapper">
			';

			// Eventually will add checks to see if the currently selected workspace has the modules enabled for the buttons to show accordingly

			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button1" href="'.$rootPathPrefix.'admin/contacts"><img src="'.$rootPathPrefix.'images/lifems/icons/users.svg"><p>Contacts</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button3" href="'.$rootPathPrefix.'admin/calendarEvents"><img src="'.$rootPathPrefix.'images/lifems/icons/calendar_month.svg"><p>CalendarEvents</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button4" href="'.$rootPathPrefix.'admin/invoices"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Invoices</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button2" href="'.$rootPathPrefix.'admin/estimates"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Estimates</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button3" href="'.$rootPathPrefix.'admin/notes"><img src="'.$rootPathPrefix.'images/lifems/icons/pen.svg"><p>Notes</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button5"><img src="'.$rootPathPrefix.'images/lifems/icons/drag.svg"><p>More...</p></a>
			';

			// Bottom links
			$output .= '<div id="smallBottomLinks"><a class="noUnderline" href="'.$rootPathPrefix.'admin/workspaceoverview">Overview</a> | <a class="noUnderline" href="'.$rootPathPrefix.'admin/sitemap">Sitemap</a></div>
			';

			// Start More menu
			$output .= '<div id="sideBarMoreMenu">
			';
				// More menu items
				$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button6" href="'.$rootPathPrefix.'admin/inventory"><img src="'.$rootPathPrefix.'images/lifems/icons/archive.svg"><p>Inventory</p></a>
				';
			// End More menu
			$output .= '</div>
			';

			$output .= '</div>
			';
			
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

			// Get the current version
			$version = htmlspecialchars(strval($this->database->select("systemInfo", "*", "WHERE `var` = 'currentVersion'")[0]["val"]));

			$output .= '<div class="'.$class.' defaultInsetShadow"><p>Life Management System - &copy <a class="noUnderline" target="_blank" href="https://cameronmcgehee.com">McGehee Enterprises</a> '.$date.' - '.$version.'</p></div>
			';
			
			return $output;
		}

		function renderAdminMobileNavBar(string $rootPathPrefix = './', bool $showError = true) {
			$output = '';

			$output .= '<div class="cmsMobileNavBarWrapper">
			';

			// If user is signed in and workspace is selected then render the buttons, otherwise print an error.

			if (isset($_SESSION['lifems_adminId']) && isset($_SESSION['lifems_workspaceId'])) {
				$output .= '<div class="mobileNavBarButtonArray">
				';
					$output .= '<a class="button" id="button1" href="'.$rootPathPrefix.'admin/contacts"><img src="'.$rootPathPrefix.'images/lifems/icons/users.svg"><p>Contacts</p></a>
					';
					$output .= '<a class="button" id="button3" href="'.$rootPathPrefix.'admin/calendarEvents"><img src="'.$rootPathPrefix.'images/lifems/icons/calendar_month.svg"><p>CalendarEvents</p></a>
					';
					$output .= '<a class="button" id="button4" href="'.$rootPathPrefix.'admin/invoices"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Invoices</p></a>
					';
					$output .= '<a class="button" id="button2" href="'.$rootPathPrefix.'admin/estimates"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Estimates</p></a>
					';
					$output .= '<span class="button" id="button5"><img src="'.$rootPathPrefix.'images/lifems/icons/drag.svg"></span>
					';
				$output .= '</div>
				';
			} else {
				$output .= '<p class="marginLeftRight90 textCentered">Please login and select workspace.</p>
				';
			}

			// Start More menu
			$output .= '<span id="mobileNavBarMoreMenuHider"><div id="mobileNavBarMoreMenuWrapper">
			';
			//More menu items
			$output .= '<p><a href="'.$rootPathPrefix.'admin/inventory">Inventory</a></p><p>Item 2</p><p>Item 3</p><p>Item 4</p>
			';
			// End More menu
			$output .= '</div></span>
			';
			
			$output .= '</div>
			';
			
			return $output;
		}

	}

?>
