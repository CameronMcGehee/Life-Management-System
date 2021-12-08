<?php

	require_once dirname(__FILE__)."/../render.php";

	class didYouKnowBox extends render {

		public string $path;
		public $allowedTags;
		public string $style;

		function __construct(string $path = './', $allowedTags = NULL, string $style = '') {
			$this->path = $path;
			$this->allowedTags = $allowedTags;
			$this->style = $style;
		}

		function render() {

			$this->output = '';

			
		}
	}

?>
