<?php

	require_once dirname(__FILE__)."/../render.php";

	class pageNavigator extends render {

		private $numPages;
		private $currentPage;
		public $path;
		public $pageVarName;
		public $style;

		private $nextPage;
		private $previousPage;

		function __construct($numPages, $currentPage, $path = './', $pageVarName = 'page', $style = '') {
			$this->numPages = $numPages;
			$this->currentPage = $currentPage;
			$this->path = $path;
			$this->pageVarName = $pageVarName;
			$this->style = $style;

			$this->previousPage = $this->currentPage - 1;
			$this->nextPage = $this->currentPage + 1;
		}

		function render() {

			$this->output = '';

			$this->output .= '<div style="'.$this->style.'" class="pageNavigator">';

			// firstPage
			if ($this->currentPage == 1) {
				$this->output .= '<a class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'=1"><span id="currentPageButton">1</span></a> ';
			} else {
				$this->output .= '<a class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'=1"><span id="pageButton">1</span></a> ';
			}
			

			// previousPage
			if ($this->previousPage >= 1) {
				$this->output .= '<a id="pageButton" class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'='.$this->previousPage.'"><span><--</span></a> ';
			}

			// currentPage
			if ($this->currentPage != 1) {
				$this->output .= '<a id="currentPageButton" class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'='.$this->currentPage.'"><span>'.$this->currentPage.'</span></a> ';
			}

			// nextPage
			if ($this->nextPage < $this->numPages) {
				$this->output .= '<a id="pageButton" class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'='.$this->nextPage.'"><span>--></span></a> ';
			}

			// lastPage
			if ($this->currentPage != $this->numPages) {
				$this->output .= '<a id="pageButton" class="noUnderline" href="'.$this->path.'?'.$this->pageVarName.'='.$this->numPages.'"><span>'.$this->numPages.'</span></a> ';
			}

			$this->output .= '</div>';
		}
	}

?>
