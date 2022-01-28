<?php

	require_once dirname(__FILE__)."/../render.php";

	class sortBySelector extends render {

		public $options;
		public $selected;
		public $pageVarName;
		public $style;
		public $id;

		function __construct(string $renderId, $path = './', $pageVarName = 'page', $selected = 'az') {
			$this->renderId = $renderId;
			$this->pageVarName = $pageVarName;
			$this->selected = $selected;
		}

		function render() {

			$this->output = '';

			$this->output .= '<select class="defaultInput" style="'.$this->style.'" id="'.$this->renderId.'">';

			if ($this->selected == 'az') {
				$this->output .= '<option value="az" selected>A-Z</option>';
			} else {
				$this->output .= '<option value="az">A-Z</option>';
			}

			if ($this->selected == 'za') {
				$this->output .= '<option value="za" selected>Z-A</option>';
			} else {
				$this->output .= '<option value="za">Z-A</option>';
			}

			if ($this->selected == 'newest') {
				$this->output .= '<option value="newest" selected>Newest</option>';
			} else {
				$this->output .= '<option value="newest">Newest</option>';
			}

			if ($this->selected == 'oldest') {
				$this->output .= '<option value="oldest" selected>Oldest</option>';
			} else {
				$this->output .= '<option value="oldest">Oldest</option>';
			}

			$this->output .= '<option value="oldest">Oldest</option>';

			$this->output .= '</select>';
		}
	}

?>
