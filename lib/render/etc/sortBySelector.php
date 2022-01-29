<?php

	require_once dirname(__FILE__)."/../render.php";

	class sortBySelector extends render {

		public $rootPathPrefix;
		public $options;
		public $selected;
		public $getVarName;
		public $style;
		public $id;

		function __construct(string $renderId, $rootPathPrefix = './', $getVarName = 'page', $selected = 'az') {
			$this->rootPathPrefix = $rootPathPrefix;
			$this->renderId = $renderId;
			$this->getVarName = $getVarName;
			$this->selected = $selected;
		}

		function render() {

			$this->output = '';

			$this->output .= '<select class="defaultInput" style="'.$this->style.'" id="'.$this->renderId.'" onchange="'.$this->renderId.'changeSort()">';

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
				$this->output .= '<option value="newest" selected>Youngest - Oldest</option>';
			} else {
				$this->output .= '<option value="newest">Youngest - Oldest</option>';
			}

			if ($this->selected == 'oldest') {
				$this->output .= '<option value="oldest" selected>Oldest - Youngest</option>';
			} else {
				$this->output .= '<option value="oldest">Oldest - Youngest</option>';
			}

			$this->output .= '</select>';

			$this->output .= '
            <script>
                function '.$this->renderId.'changeSort() {

                    var url = new URL(window.location.href);

                    url.searchParams.set("'.$this->getVarName.'", $("#'.$this->renderId.' option:selected").val());

					window.location.replace(url.href);
                }
            
            </script>';
		}
	}

?>
