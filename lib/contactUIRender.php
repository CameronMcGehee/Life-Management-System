<?php

    // HTML OUTPUT/DISPLAY FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class contactUIRender {

		private $config;
		
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

		function renderContactHtmlTop (string $rootPathPrefix = './', string $pageTitle = '', string $pageDescription = 'LifeMS') {
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
				if (isset($_SESSION['lifems_workspaceId'])) {
					require_once dirname(__FILE__)."/table/workspace.php";

					$currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);

					$output .= '<title>'.$pageTitle.' - '.htmlspecialchars($currentWorkspace->displayName).' (Contact)</title>
					';
				} else {
					$output .= '<title>'.$pageTitle.' - LifeMS (Contact)</title>
					';
				}
				
				$output .= '<meta name="description" content="'.$pageDescription.'">
				';

				// CSS

				$output .= '<link rel="stylesheet" type="text/css" href="'.$rootPathPrefix.'css/app/main.css">
				';
				$output .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
				';

				// Javascript

				$output .= '<script type="text/javascript" src="'.$rootPathPrefix.'js/jquery-3.6.0.min.js"></script>
				';

				// Favicon stuff when we have it

				// Do not end head tag so we can add something if needed on a specific page, like javascript

			return $output;
		}

		function renderContactHtmlBottom (string $rootPathPrefix = './') {
			return '</html>
			';
		}
		
		function renderContactTopBar(string $rootPathPrefix = './', bool $showLogo = true, bool $showWorkspaceSelector = true, bool $showProfileButton = true) {
			$output = '';

			$output .= '<div class="contactTopBarWrapper defaultInsetShadow">
			';

			$output .= '<div class="xyCenteredFlex" id="lifemsLogoWrapper">
			';
				if ($showLogo) {
					$output .= '<a class="noUnderline" id="lifemsLogoImageWrapper" href="'.$rootPathPrefix.'"><img src="'.$rootPathPrefix.'images/lifems/logos/mainLogoTopBarWhiteTrans.png"></a>
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
				// if ($showWorkspaceSelector) {
				// 	$output .= '<div class="yCenteredFlex flexDirectionRow" id="workspaceSelectorButton">
				// 	';
				// 	require_once dirname(__FILE__)."/table/workspace.php";
				// 	$workspace = new workspace($_SESSION['lifems_workspaceId']);
				// 	$currentWorkspaceLogo = $workspace->fullLogoFile;
				// 	// If the currently selected workspace does not have a logo file, display the default one
				// 	if ($currentWorkspaceLogo == NULL) {
				// 		$bsImgPath = $rootPathPrefix.'images/lifems/etc/noLogo.png';
				// 	} else {
				// 		$bsImgPath = $rootPathPrefix.'images/lifems/uploads/workspace/fullLogoFile/'.$currentWorkspaceLogo;
				// 	}
					
				// 	// Render the button itself
				// 	$output .= '<img id="workspaceSelectorSelectedImg" src="'.$bsImgPath.'"><img src="'.$rootPathPrefix.'images/lifems/icons/chevron_down.svg"></div>
				// 	';
				// }
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

		function renderContactTopBarDropdowns(string $rootPathPrefix = './') {
			$output = '';

			// Workspace Selector button dropdown
			// $output .= '<span class="workspaceSelectorButtonDropdownHider" id="bsMenu"><div class="workspaceSelectorButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			// ';
			
			// 	// Eventually for each workspace that belongs to the contact, output a button to switch to that workspace
			// 	$output .= '<p><a href="'.$rootPathPrefix.'contact/selectworkspace">Switch Workspace</a></p>
			// 	';

			// 	// Edit button temporarily until we make it look nice
			// 	$output .= '<p><a href="'.$rootPathPrefix.'contact/workspacesettings">Workspace Settings</a></p>
			// 	';

			// 	// Button to create a new workspace
			// 	$output .= '<br><a href="'.$rootPathPrefix.'contact/createworkspace" class="smallButtonWrapper greenButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/lifems/icons/plus.svg"></a>
			// 	';

			// $output .= '</div></span>
			// ';
			
			// Profile Picture button dropdown
			$output .= '<span class="profilePictureButtonDropdownHider" id="pfpMenu"><div class="profilePictureButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			';
				$output .= '<p><a href="'.$rootPathPrefix.'contact/editprofile">Edit Profile</a></p>
				';
				$output .= '<a href="'.$rootPathPrefix.'contact/scripts/standalone/logout.script" class="smallButtonWrapper orangeButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/lifems/icons/exit_right.svg"></a>
				';
			$output .= '</div></span>
			';

			return $output;
		}

		function renderContactUIMenuToggleScripts(string $rootPathPrefix = './', bool $showProfileButtonScript = true, bool $showWorkspaceSelectorScript = true, bool $showSideBarMoreMenuScript = true, bool $showMobileNavBarMoreMenuScript = true) {
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

				// if ($showWorkspaceSelectorScript) {
				// 	$output .= '$("#workspaceSelectorButtonWrapper").click(function() {
				// 					$("#bsMenu").toggle();

				// 					if($("#pfpMenu").is(":visible")) {
				// 						$("#pfpMenu").toggle();
				// 					}
				// 				});
				// 				';
				// }

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

		function renderContactSideBar(string $rootPathPrefix = './') {
			$output = '';

			$output .= '<div class="cmsSideBarWrapper">
			';

			// Eventually will add checks to see if the currently selected workspace has the modules enabled for the buttons to show accordingly

			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button1" href="'.$rootPathPrefix.'contact/home"><img src="'.$rootPathPrefix.'images/lifems/icons/grid.svg"><p>Home</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button3" href="'.$rootPathPrefix.'contact/calendarEvents"><img src="'.$rootPathPrefix.'images/lifems/icons/calendar_month.svg"><p>Scheduled Services</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button4" href="'.$rootPathPrefix.'contact/invoices"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Invoices</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button2" href="'.$rootPathPrefix.'contact/estimates"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Estimates</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button5"><img src="'.$rootPathPrefix.'images/lifems/icons/drag.svg"><p>More...</p></a>
			';

			// Bottom links
			$output .= '<div id="smallBottomLinks"><a class="noUnderline" href="'.$rootPathPrefix.'contact/home">Home</a> | <a class="noUnderline" href="'.$rootPathPrefix.'contact/sitemap">Sitemap</a></div>
			';

			// Start More menu
			$output .= '<div id="sideBarMoreMenu">
			';
			// More menu items
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button6" href="'.$rootPathPrefix.'contact/inventory"><img src="'.$rootPathPrefix.'images/lifems/icons/archive.svg"><p>Inventory</p></a>
			';
			// End More menu
			$output .= '</div>
			';

			$output .= '</div>
			';
			
			return $output;
		}

		function renderContactFooter(string $rootPathPrefix = './', $isLoginPage = false) {
			$output = '';

			if ($isLoginPage) {
				$class = 'cmsLoginFooterWrapper';
			} else {
				$class = 'cmsFooterWrapper';
			}

			$date = date('Y');

			// Get the current version
			$version = htmlspecialchars(strval($this->database->select("systemInfo", "*", "WHERE `var` = 'currentVersion'")[0]["val"]));

			$output .= '<div class="'.$class.' defaultInsetShadow"><p>Copyright &copy <a class="noUnderline" target="_blank" href="https://cameronmcgehee.com">McGehee Enterprises</a> '.$date.' - <b>v'.$version.'</b></p></div>
			';
			
			return $output;
		}

		function renderContactMobileNavBar(string $rootPathPrefix = './', bool $showError = true) {
			$output = '';

			$output .= '<div class="cmsMobileNavBarWrapper">
			';

			// If user is signed in and workspace is selected then render the buttons, otherwise print an error.

			if (isset($_SESSION['lifems_contactId'])) {
				$output .= '<div class="mobileNavBarButtonArray">
				';
					$output .= '<a class="button" id="button1" href="'.$rootPathPrefix.'contact/home"><img src="'.$rootPathPrefix.'images/lifems/icons/grid.svg"><p>Home</p></a>
					';
					$output .= '<a class="button" id="button3" href="'.$rootPathPrefix.'contact/calendarEvents"><img src="'.$rootPathPrefix.'images/lifems/icons/calendar_month.svg"><p>Scheduled Services</p></a>
					';
					$output .= '<a class="button" id="button4" href="'.$rootPathPrefix.'contact/invoices"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Invoices</p></a>
					';
					$output .= '<a class="button" id="button2" href="'.$rootPathPrefix.'contact/estimates"><img src="'.$rootPathPrefix.'images/lifems/icons/document.svg"><p>Estimates</p></a>
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
			$output .= '<p><a href="'.$rootPathPrefix.'contact/inventory">Inventory</a></p><p>Item 2</p><p>Item 3</p><p>Item 4</p>
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
