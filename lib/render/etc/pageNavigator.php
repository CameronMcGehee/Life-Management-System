<?php

	require_once dirname(__FILE__)."/../render.php";

	class pageNavigator extends render {

		private $numPages;
		private $currentPage;
		public $path;
		public $pageVarName;
		public $style;
		public $id;

		private $nextPage;
		private $previousPage;

		function __construct($numPages, $currentPage, $path = './', $pageVarName = 'page', $style = '', $id = '') {
			$this->numPages = $numPages;
			$this->currentPage = $currentPage;
			$this->path = $path;
			$this->pageVarName = $pageVarName;
			$this->style = $style;

			if ($id == '') {
				// Use a generic Id
				$this->id = 'pageNavigator'.uniqid();
			} else {
				$this->id = $id;
			}

			$this->previousPage = $this->currentPage - 1;
			$this->nextPage = $this->currentPage + 1;
		}

		function render() {

			$this->output = '';

			$this->output .= '<div style="'.$this->style.'" id="'.$this->id.'" class="pageNavigator">';

			// firstPage
			if ($this->currentPage == 1) {
				$this->output .= '<a class="currentPageButton noUnderline" id="current" href="'.$this->path.'?'.$this->pageVarName.'=1"><span>1</span></a> ';
			} else {
				$this->output .= '<a class="pageButton noUnderline" id="first" href="'.$this->path.'?'.$this->pageVarName.'=1"><span>1</span></a> ';
			}

			// previousPage
			if ($this->previousPage >= 1) {
				$this->output .= '<a class="pageButton noUnderline" id="previous" href="'.$this->path.'?'.$this->pageVarName.'='.$this->previousPage.'"><span><--</span></a> ';
			}

			// currentPage
			if ($this->currentPage != 1) {
				$this->output .= '<a class="currentPageButton noUnderline" id="current" href="'.$this->path.'?'.$this->pageVarName.'='.$this->currentPage.'"><span>'.$this->currentPage.'</span></a> ';
			}

			// nextPage
			if ($this->nextPage < $this->numPages) {
				$this->output .= '<a class="pageButton noUnderline" id="next" href="'.$this->path.'?'.$this->pageVarName.'='.$this->nextPage.'"><span>--></span></a> ';
			}

			// lastPage
			if ($this->currentPage != $this->numPages) {
				$this->output .= '<a class="pageButton noUnderline" id="last" href="'.$this->path.'?'.$this->pageVarName.'='.$this->numPages.'"><span>'.$this->numPages.'</span></a> ';
			}

			$this->output .= '</div>';
		}
	}

?>
