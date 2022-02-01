<?php

	require_once dirname(__FILE__)."/../render.php";

	class pageNavigator extends render {

		private $numPages;
		private $currentPage;
		public $path;
		public $getVarName;
		public $style;
		public $renderId;

		private $nextPage;
		private $previousPage;

		function __construct($numPages, $currentPage, $path = './', $getVarName = 'page', $style = '', $renderId = '') {

			parent::__construct();
			
			$this->numPages = $numPages;
			$this->currentPage = $currentPage;
			$this->path = $path;
			$this->getVarName = $getVarName;
			$this->style = $style;

			if ($renderId == '') {
				// Use a generic Id
				$this->renderId = 'pageNavigator'.uniqid();
			} else {
				$this->renderId = $renderId;
			}

			$this->previousPage = $this->currentPage - 1;
			$this->nextPage = $this->currentPage + 1;
		}

		function render() {

			$this->output = '';

			$this->output .= '<div style="'.$this->style.'" id="'.$this->renderId.'" class="pageNavigator">';

			// firstPage
			if ($this->currentPage == 1) {
				$this->output .= '<span class="currentPageButton noUnderline" id="current" onclick="'.$this->renderId.'changePage(1)"><span>1</span></span> ';
			} else {
				$this->output .= '<span class="pageButton noUnderline" id="first" onclick="'.$this->renderId.'changePage(1)"><span>1</span></span> ';
			}

			// previousPage
			if ($this->previousPage >= 1) {
				$this->output .= '<span class="pageButton noUnderline" id="previous" onclick="'.$this->renderId.'changePage('.$this->previousPage.')"><span><--</span></span> ';
			}

			// currentPage
			if ($this->currentPage != 1) {
				$this->output .= '<span class="currentPageButton noUnderline" id="current" onclick="'.$this->renderId.'changePage('.$this->currentPage.')"><span>'.$this->currentPage.'</span></span> ';
			}

			// nextPage
			if ($this->nextPage < $this->numPages) {
				$this->output .= '<span class="pageButton noUnderline" id="next" onclick="'.$this->renderId.'changePage('.$this->nextPage.')"><span>--></span></span> ';
			}

			// lastPage
			if ($this->currentPage != $this->numPages) {
				$this->output .= '<span class="pageButton noUnderline" id="last" onclick="'.$this->renderId.'changePage('.$this->numPages.')"><span>'.$this->numPages.'</span></span> ';
			}

			$this->output .= '</div>';

			$this->output .= '
            <script>
                function '.$this->renderId.'changePage(num) {

                    var url = new URL(window.location.href);

                    url.searchParams.set("'.$this->getVarName.'", num);

					window.location.replace(url.href);
                }
            
            </script>';
		}
	}

?>
