<?php

	require_once dirname(__FILE__)."/../render.php";

	class tagEditor extends render {

		public $rootPathPrefix;
		public array $options;
		public string $renderId;

		function __construct(string $renderId, array $options = []) {
			$this->renderId = $renderId;

			if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

			if (empty($options['type']) || !in_array($options['type'], ['crew', 'customer', 'chemical', 'equipment', 'staff'])) {
				throw new Exception("Invalid or no type set (in tagEditor '".$this->renderId."')");
			}

			if (empty($options['objectId'])) {
				throw new Exception("Invalid or no objectId set (in tagEditor '".$this->renderId."')");
			}

			if (empty($options['showDelete']) || !is_bool($options['showDelete'])) {
				$options['showDelete'] = true;
			}

			if (empty($options['showAdd']) || !is_bool($options['showAdd'])) {
				$options['showAdd'] = true;
			}

			$this->options = $options;
		}

		function render() {

			$currentObject;

			// Fetch all the tags

			switch ($this->options['type']) {
				case 'crew':
					require_once dirname(__FILE__)."/../../table/crew.php";
					$currentObject = new crew($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'customer':
					require_once dirname(__FILE__)."/../../table/customer.php";
					$currentObject = new customer($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'chemical':
					require_once dirname(__FILE__)."/../../table/chemical.php";
					$currentObject = new chemical($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'equipment':
					require_once dirname(__FILE__)."/../../table/equipment.php";
					$currentObject = new equipment($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'staff':
					require_once dirname(__FILE__)."/../../table/staff.php";
					$currentObject = new staff($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				default:
					throw new Exception("Type given is not supported (in tagEditor '".$this->renderId."')");
			}

			$currentObject->pullTags();

			var_dump($currentObject->tags);

			$this->output = '<div id="'.$this->renderId.'"></div>';

			// Output each tag in a styled tag container (color is the color chosen by user), with a delete button to the right of the name

			// foreach () {

			// }

			// Output a script that loads the script that actually deletes the tag when the delete button is pressed, and remove that tag from the page if successful

			$this->output .= '
            <script>
                function deleteTag(string tagId) {

				}
            
            </script>';

			$this->output .= '</div>';
		}
	}

?>
