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
			}

            // Get popup if set in url
            if (isset($_GET['popup'])) {
                array_push($options['popups'], $_GET['popup']);
            }

            if (count($options['popups']) > 0) {
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
            
            if ($this->options['popups'] == []) {
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
                    $this->output .= '<span id="'.$this->renderId.$popup.'" class="overlay';
                    if ($this->options['center']) {
                        $this->output .= ' xyCenteredFlex';
                    }

                    $content = $this->popupsArray[$popup];

                    // Parse variables
                    preg_match_all("/\[\[([^\]]*)\]\]/", $content, $matches);
                    foreach ($matches[1] as $varName) {
                        if (isset($_GET[$popup.$varName])) {
                            $content = str_replace('[['.$varName.']]', htmlspecialchars($_GET[$popup.$varName]), $content);
                        } else {
                            $content = str_replace('[['.$varName.']]', 'unknown', $content);
                        }
                    }

                    $this->output .= '"><div style="'.$this->options['style'].'" class="popupMessageDialog '.$this->options['class'].'">

                    <div style="width: 100%;"><span class="closeButton" onclick="close'.$this->renderId.$popup.'()"><img src="'.$this->options['rootPathPrefix'].'images/lifems/icons/cross.svg" /></span></div>
                    
                    '.$content.'</div></span>';

                    // Output js function that closes the popup when the x is pressed
                    $this->output .= '<script>
                        function close'.$this->renderId.$popup.'() {
                            $("#'.$this->renderId.$popup.'").slideUp(200, function () {
                                if ($("#'.$this->renderId.' div:visible").length < 1) {
                                    $("#'.$this->renderId.'").fadeOut(200);
                                    
                                    // Remove the popup variable so it doesn\'t show on a reload
                                    var url = new URL(window.location.href);
                                    url.searchParams.delete("popup");
                                    window.history.pushState("string", "LifeMS", url.href);
                                }
                            });
                        }
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
