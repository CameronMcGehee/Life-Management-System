<?php

	require_once dirname(__FILE__)."/../render.php";

	class didYouKnowBox extends render {

		public string $renderId;
		public array $options;

		private array $phrases = [
			"You can add tags to each contact to sort them into relevant categories.",
			"LifeMS is still in early development, so please be patient if something doesn't work as intended.",
			'LifeMS is being developed entirely by one person, a senior in high school in Arlington, VA. You can learn more about him and his other projects at <a target="_blank" href="https://cameronmcgehee.com"> his website.</a>',
			'There will eventually be a homepage for LifeMS, explaining why a workspace may want to use the site as well as it\'s features.',
			'The idea for LifeMS started as a custom backend to run a 10 contact lawn service operated by a 13-year-old in 2017.',
			'Many (much too long) nights of development work has gone into bringing you this software...which is why we hope you find it useful!'
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
			$this->output = '<div class="grayInfoBox defaultMainShadows desktopOnlyBlock"><h3>Did you know?</h3><p>'.$this->phrases[$phrase].'</p></div>';
			
		}
	}

?>
