<?php

    // HTML OUTPUT/DISPLAY FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class customerUIRender {

		private $config;
		
		function __construct() {
			require_once dirname(__FILE__)."/database.php";
			$this->database = new database;

			$this->config = $GLOBALS['ULTISCAPECONFIG'];
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Renders related to the general site
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function renderCustomerHtmlTop (string $rootPathPrefix = './', string $pageTitle = '', string $pageDescription = 'LMS') {
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
				if (isset($_SESSION['ultiscape_businessId'])) {
					require_once dirname(__FILE__)."/table/business.php";

					$currentBusiness = new business($_SESSION['ultiscape_businessId']);

					$output .= '<title>'.$pageTitle.' - '.htmlspecialchars($currentBusiness->displayName).' (Customer)</title>
					';
				} else {
					$output .= '<title>'.$pageTitle.' - LMS (Customer)</title>
					';
				}
				
				$output .= '<meta name="description" content="'.$pageDescription.'">
				';

				// CSS

				$output .= '<link rel="stylesheet" type="text/css" href="'.$rootPathPrefix.'css/app/main.css">
				';

				// Javascript

				$output .= '<script type="text/javascript" src="'.$rootPathPrefix.'js/jquery-3.6.0.min.js"></script>
				';

				// Favicon stuff when we have it

				// Do not end head tag so we can add something if needed on a specific page, like javascript

			return $output;
		}

		function renderCustomerHtmlBottom (string $rootPathPrefix = './') {
			return '</html>
			';
		}
		
		function renderCustomerTopBar(string $rootPathPrefix = './', bool $showLogo = true, bool $showBusinessSelector = true, bool $showProfileButton = true) {
			$output = '';

			$output .= '<div class="customerTopBarWrapper defaultInsetShadow">
			';

			$output .= '<div class="xyCenteredFlex" id="ultiscapeLogoWrapper">
			';
				if ($showLogo) {
					$output .= '<a class="noUnderline" id="ultiscapeLogoImageWrapper" href="'.$rootPathPrefix.'"><img src="'.$rootPathPrefix.'images/ultiscape/logos/mainLogoTopBarWhiteTrans.png"></a>
					';
				}
			$output .= '</div>
			';

			// Blank Space
			$output .= '<div></div>
			';

			// Business Selector Button
			$output .= '<div class="yCenteredFlex flexDirectionRow" id="businessSelectorButtonWrapper">
			';
				// if ($showBusinessSelector) {
				// 	$output .= '<div class="yCenteredFlex flexDirectionRow" id="businessSelectorButton">
				// 	';
				// 	require_once dirname(__FILE__)."/table/business.php";
				// 	$business = new business($_SESSION['ultiscape_businessId']);
				// 	$currentBusinessLogo = $business->fullLogoFile;
				// 	// If the currently selected business does not have a logo file, display the default one
				// 	if ($currentBusinessLogo == NULL) {
				// 		$bsImgPath = $rootPathPrefix.'images/ultiscape/etc/noLogo.png';
				// 	} else {
				// 		$bsImgPath = $rootPathPrefix.'images/ultiscape/uploads/business/fullLogoFile/'.$currentBusinessLogo;
				// 	}
					
				// 	// Render the button itself
				// 	$output .= '<img id="businessSelectorSelectedImg" src="'.$bsImgPath.'"><img src="'.$rootPathPrefix.'images/ultiscape/icons/chevron_down.svg"></div>
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
							$pfpPath = $rootPathPrefix.'images/ultiscape/uploads/profile/'.$fileName;
						}
					} else {
						$pfpPath = $rootPathPrefix.'images/ultiscape/icons/user_male.svg';
					}
					$output .= '<img id="profilePictureButton" src="'.$pfpPath.'"><img src="'.$rootPathPrefix.'images/ultiscape/icons/chevron_down.svg" class="whiteChevron">
					';
				}
			$output .= '</div>
			';
			
			// End main top bar wrapper div
			$output .= '</div>
			';
			
			return $output;
		}

		function renderCustomerTopBarDropdowns(string $rootPathPrefix = './') {
			$output = '';

			// Business Selector button dropdown
			// $output .= '<span class="businessSelectorButtonDropdownHider" id="bsMenu"><div class="businessSelectorButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			// ';
			
			// 	// Eventually for each business that belongs to the customer, output a button to switch to that business
			// 	$output .= '<p><a href="'.$rootPathPrefix.'customer/selectbusiness">Switch Business</a></p>
			// 	';

			// 	// Edit button temporarily until we make it look nice
			// 	$output .= '<p><a href="'.$rootPathPrefix.'customer/businesssettings">Business Settings</a></p>
			// 	';

			// 	// Button to create a new business
			// 	$output .= '<br><a href="'.$rootPathPrefix.'customer/createbusiness" class="smallButtonWrapper greenButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/ultiscape/icons/plus.svg"></a>
			// 	';

			// $output .= '</div></span>
			// ';
			
			// Profile Picture button dropdown
			$output .= '<span class="profilePictureButtonDropdownHider" id="pfpMenu"><div class="profilePictureButtonDropdownWrapper xyCenteredFlex flexDirectionColumn">
			';
				$output .= '<p><a href="'.$rootPathPrefix.'customer/editprofile">Edit Profile</a></p>
				';
				$output .= '<a href="'.$rootPathPrefix.'customer/scripts/standalone/logout.script" class="smallButtonWrapper orangeButton xyCenteredFlex defaultMainShadows" style="padding: .2em;"><img style="width: 2em; height: 2em;" src="'.$rootPathPrefix.'images/ultiscape/icons/exit_right.svg"></a>
				';
			$output .= '</div></span>
			';

			return $output;
		}

		function renderCustomerUIMenuToggleScripts(string $rootPathPrefix = './', bool $showProfileButtonScript = true, bool $showBusinessSelectorScript = true, bool $showSideBarMoreMenuScript = true, bool $showMobileNavBarMoreMenuScript = true) {
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

				// if ($showBusinessSelectorScript) {
				// 	$output .= '$("#businessSelectorButtonWrapper").click(function() {
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

		function renderCustomerSideBar(string $rootPathPrefix = './') {
			$output = '';

			$output .= '<div class="cmsSideBarWrapper">
			';

			// Eventually will add checks to see if the currently selected business has the modules enabled for the buttons to show accordingly

			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button1" href="'.$rootPathPrefix.'customer/home"><img src="'.$rootPathPrefix.'images/ultiscape/icons/grid.svg"><p>Home</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button3" href="'.$rootPathPrefix.'customer/jobs"><img src="'.$rootPathPrefix.'images/ultiscape/icons/calendar_month.svg"><p>Scheduled Services</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button4" href="'.$rootPathPrefix.'customer/invoices"><img src="'.$rootPathPrefix.'images/ultiscape/icons/document.svg"><p>Invoices</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button2" href="'.$rootPathPrefix.'customer/estimates"><img src="'.$rootPathPrefix.'images/ultiscape/icons/document.svg"><p>Estimates</p></a>
			';
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button5"><img src="'.$rootPathPrefix.'images/ultiscape/icons/drag.svg"><p>More...</p></a>
			';

			// Bottom links
			$output .= '<div id="smallBottomLinks"><a class="noUnderline" href="'.$rootPathPrefix.'customer/overview">Overview</a> | <a class="noUnderline" href="'.$rootPathPrefix.'customer/sitemap">Sitemap</a></div>
			';

			// Start More menu
			$output .= '<div id="sideBarMoreMenu">
			';
			// More menu items
			$output .= '<a class="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button6" href="'.$rootPathPrefix.'customer/inventory"><img src="'.$rootPathPrefix.'images/ultiscape/icons/archive.svg"><p>Inventory</p></a>
			';
			// End More menu
			$output .= '</div>
			';

			$output .= '</div>
			';
			
			return $output;
		}

		function renderCustomerFooter(string $rootPathPrefix = './', $isLoginPage = false) {
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

		function renderCustomerMobileNavBar(string $rootPathPrefix = './', bool $showError = true) {
			$output = '';

			$output .= '<div class="cmsMobileNavBarWrapper">
			';

			// If user is signed in and business is selected then render the buttons, otherwise print an error.

			if (isset($_SESSION['ultiscape_customerId'])) {
				$output .= '<div class="mobileNavBarButtonArray">
				';
					$output .= '<a class="button" id="button1" href="'.$rootPathPrefix.'customer/home"><img src="'.$rootPathPrefix.'images/ultiscape/icons/grid.svg"><p>Home</p></a>
					';
					$output .= '<a class="button" id="button3" href="'.$rootPathPrefix.'customer/jobs"><img src="'.$rootPathPrefix.'images/ultiscape/icons/calendar_month.svg"><p>Scheduled Services</p></a>
					';
					$output .= '<a class="button" id="button4" href="'.$rootPathPrefix.'customer/invoices"><img src="'.$rootPathPrefix.'images/ultiscape/icons/document.svg"><p>Invoices</p></a>
					';
					$output .= '<a class="button" id="button2" href="'.$rootPathPrefix.'customer/estimates"><img src="'.$rootPathPrefix.'images/ultiscape/icons/document.svg"><p>Estimates</p></a>
					';
					$output .= '<span class="button" id="button5"><img src="'.$rootPathPrefix.'images/ultiscape/icons/drag.svg"></span>
					';
				$output .= '</div>
				';
			} else {
				$output .= '<p class="marginLeftRight90 textCentered">Please login and select business.</p>
				';
			}

			// Start More menu
			$output .= '<span id="mobileNavBarMoreMenuHider"><div id="mobileNavBarMoreMenuWrapper">
			';
			//More menu items
			$output .= '<p><a href="'.$rootPathPrefix.'customer/inventory">Inventory</a></p><p>Item 2</p><p>Item 3</p><p>Item 4</p>
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
