<?php

	require_once dirname(__FILE__)."/../render.php";

	class popupsHandler extends render {

		public string $renderId;
		public array $options;
		private array $popupsArray;

		function __construct(string $renderId, array $options = []) {
			
			parent::__construct();
			
			$this->renderId = $renderId;

			if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

            if (empty($options['useDimOverlay'])) {
				$options['useDimOverlay'] = true;
			}

            if (empty($options['center'])) {
				$options['center'] = true;
			}

            if (empty($options['popups']) || !is_array($options['popups'])) {
				$options['popups'] = [];
			} else {
                $this->popupsArray = require_once dirname(__FILE__)."/../../../config/popups.php";
            }

            if (empty($options['delay'])) {
				$options['delay'] = '';
			}

            if (empty($options['style'])) {
				$options['style'] = '';
			}

            if (empty($options['class'])) {
				$options['class'] = '';
			}

			$this->options = $options;

		}

		function render() {

            $this->output = '';

            // If there is no popup set, render nothing
            
            if ($this->options['popups'] == '') {
                $this->output = '';
                return;
            }

            // Otherwise, render the popups, starting with the optional dim overlay div

            if ($this->options['useDimOverlay']) {
                $this->output .= '<div id="'.$this->renderId.'" class="dimOverlay">';
            }
            
            foreach ($this->options['popups'] as $popup) {
                
                // If it is in the popups file, output the content.

                if (in_array($popup, array_keys($this->popupsArray))) {

                    // "alert" style Popup
                    $this->output .= '<span class="overlay';
                    if ($this->options['center']) {
                        $this->output .= ' xyCenteredFlex';
                    }

                    $this->output .= '"><div style="'.$this->options['style'].'" class="popupMessageDialog '.$this->options['class'].'" id="'.$popup.'">'.$this->popupsArray[$popup].'</div></span>';

                    // Output js function that closes the popup when the x is pressed
                    $this->output .= '<script>
                        
                    </script>';
                } else {
                    $this->output = '';
                    return;
                }

            }

            // Close the dim overlay div if in use
            if ($this->options['useDimOverlay']) {
                $this->output .= '</div>';
            }
			
		}
	}

?>
