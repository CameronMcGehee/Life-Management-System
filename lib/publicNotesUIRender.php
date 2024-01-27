<?php

    // HTML OUTPUT/DISPLAY FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	class publicNotesUIRender {

		private $config;
		private $database;
		
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

		function renderHtmlTop (string $rootPathPrefix = './', array $options =[
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

		function renderHtmlBottom (string $rootPathPrefix = './') {
			return '</html>
			';
		}
		
		function renderTopBar(string $rootPathPrefix = './', bool $showLogo = true) {
			$output = '';

			$output .= '<div class="adminTopBarWrapper defaultInsetShadow">
			';

			$output .= '<div class="xyCenteredFlex" id="ultiscapeLogoWrapper">
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
			
			// End main top bar wrapper div
			$output .= '</div>
			';
			
			return $output;
		}

		function renderFooter(string $rootPathPrefix = './', $isLoginPage = false) {
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

	}

?>
