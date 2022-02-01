<?php

	require_once dirname(__FILE__)."/../render.php";

	class didYouKnowBox extends render {

		public string $renderId;
		public array $options;

		private array $phrases = [
			"With UltiScape, you can add tags to each customer to sort them into relevant categories.",
			"UltiScape is still in early development, so please be patient if something doesn't work as intended."
		];

		function __construct(string $renderId, array $options) {
			
			parent::__construct();
			
			$this->renderId = $renderId;

			if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

			if (empty($options['style'])) {
				$options['style'] = '';
			}

			$this->options = $options;
		}

		function render() {

			$phrase = rand(0, count($this->phrases)-1);
			$this->output = '<div class="grayInfoBox defaultMainShadows desktopOnlyBlock"><h3>Did you know?</h3><br><p>'.$this->phrases[$phrase].'</p></div>';
			
		}
	}

?>
