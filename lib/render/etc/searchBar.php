<?php

	require_once dirname(__FILE__)."/../render.php";

	class searchBar extends render {

		public $rootPathPrefix;
		public $value;
		public $getVarName;
		public $style;
		public string $renderId;

		function __construct(string $renderId, $rootPathPrefix = './', $getVarName = '-q', $value = 'az') {

			parent::__construct();
			
			$this->rootPathPrefix = $rootPathPrefix;
			$this->renderId = $renderId;
			$this->getVarName = $getVarName;
			$this->value = $value;
		}

		function render() {

			$this->output = '';

			$this->output .= '<input type="text" class="defaultInput" style="'.$this->style.'" id="'.$this->renderId.'" onchange="'.$this->renderId.'changeSearch()" value="'.$this->value.'" placeholder="Search...">';

			$this->output .= '
            <script>
                function '.$this->renderId.'changeSearch() {

                    var url = new URL(window.location.href);

                    url.searchParams.set("'.$this->getVarName.'", $("#'.$this->renderId.'").val());

					window.location.replace(url.href);
                }
            
            </script>';
		}
	}

?>
