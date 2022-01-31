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

			if (empty($options['largeSize']) || !is_bool($options['largeSize'])) {
				$options['largeSize'] = false;
			}

			if (empty($options['style'])) {
				$options['style'] = '';
			}

			$this->options = $options;
		}

		function render() {

			$currentObject;

			// Fetch all the tags

			switch ($this->options['type']) {
				case 'crew':
					require_once dirname(__FILE__)."/../../table/crew.php";
					require_once dirname(__FILE__)."/../../table/crewTag.php";
					$currentObject = new crew($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'customer':
					require_once dirname(__FILE__)."/../../table/customer.php";
					require_once dirname(__FILE__)."/../../table/customerTag.php";
					$currentObject = new customer($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'chemical':
					require_once dirname(__FILE__)."/../../table/chemical.php";
					require_once dirname(__FILE__)."/../../table/chemicalTag.php";
					$currentObject = new chemical($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'equipment':
					require_once dirname(__FILE__)."/../../table/equipment.php";
					require_once dirname(__FILE__)."/../../table/equipmentTag.php";
					$currentObject = new equipment($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				case 'staff':
					require_once dirname(__FILE__)."/../../table/staff.php";
					require_once dirname(__FILE__)."/../../table/staffTag.php";
					$currentObject = new staff($this->options['objectId']);
					if (!$currentObject->existed) {
						throw new Exception("ObjectId given doesn't exist (in tagEditor '".$this->renderId."')");
					}
					break;
				default:
					throw new Exception("Type given is not supported (in tagEditor '".$this->renderId."')");
			}

			$currentObject->pullTags();

			// var_dump($currentObject->tags);

			$this->output = '<div style="'.$this->options['style'].'" id="'.$this->renderId.'">';

			// Output each tag in a styled tag container (color is the color chosen by user), with a delete button to the right of the name

			foreach ($currentObject->tags as $tagId) {
				switch ($this->options['type']) {
					case 'crew':
						$currentTag = new crewTag($tagId);
						if (!$currentTag->existed) {
							throw new Exception("TagId given doesn't exist (in tagEditor '".$this->renderId."')");
						}
						break;
					case 'customer':
						$currentTag = new customerTag($tagId);
						if (!$currentTag->existed) {
							throw new Exception("TagId given doesn't exist (in tagEditor '".$this->renderId."')");
						}
						break;
					case 'chemical':
						$currentTag = new chemicalTag($tagId);
						if (!$currentTag->existed) {
							throw new Exception("TagId given doesn't exist (in tagEditor '".$this->renderId."')");
						}
						break;
					case 'equipment':
						$currentTag = new equipmentTag($tagId);
						if (!$currentTag->existed) {
							throw new Exception("TagId given doesn't exist (in tagEditor '".$this->renderId."')");
						}
						break;
					case 'staff':
						$currentTag = new staffTag($tagId);
						if (!$currentTag->existed) {
							throw new Exception("TagId given doesn't exist (in tagEditor '".$this->renderId."')");
						}
						break;
					default:
						throw new Exception("Type given is not supported (in tagEditor '".$this->renderId."')");
				}

				$this->output .= '<span id="tag'.$this->renderId.htmlspecialchars($tagId).'" class="defaultMainShadows" style="display: inline-flex; align-items: center; background-color: '.htmlspecialchars($currentTag->color).'; border-radius: 1em; padding-left: .3em; padding-right: .3em; padding-top: .1em; padding-bottom: .1em; color: white; margin-right: .5em;';
				
				if ($this->options['largeSize']) {
					$this->output .= ' font-size: 1.4em;';
				} else {
					$this->output .= ' font-size: .9em;';
				}
				
				$this->output .= '"><a style="color: white; text-decoration: none; border: none;" href="'.$this->options['rootPathPrefix'].'admin/customer/tag/?id='.$tagId.'">'.htmlspecialchars($currentTag->name).'</a>';
				
				if ($this->options['showDelete']) {
					$this->output .= '<img onclick="'.$this->renderId.'removeTag(\''.htmlspecialchars($this->options['type']).'\', \''.htmlspecialchars($this->options['objectId']).'\', \''.htmlspecialchars($tagId).'\')" id="remove'.htmlspecialchars($tagId).'" src="'.$this->options['rootPathPrefix'].'images/ultiscape/icons/cross.svg" style="height: 1em; filter: brightness(100); cursor: pointer;">';
				}
				
				$this->output .= '</span>';
			}

			// Output a script that loads the script that actually removes the tag when the delete button is pressed, and remove that tag from the page if successful

			$deleteCustomerTagLinksAuthToken = new authToken;
            $deleteCustomerTagLinksAuthToken->authName = 'deleteCustomerTagLinks';
            $deleteCustomerTagLinksAuthToken->set();

			$this->output .= '
            <script>
                function '.$this->renderId.'removeTag(type, objectId, tagId) {
					// Load the appropriate script to remove the tag link
					
					switch (type){
						case "crew":
							console.log("NF");
							break;
						case "customer":
							$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/customer/deleteCustomerTagLinks.php", {
								"authToken": "'.$deleteCustomerTagLinksAuthToken->authTokenId.'",
								"customerTagLinks": [[objectId, tagId]]
								});
							break;
						case "chemical":
							console.log("NF");
							break;
						case "equipment":
							console.log("NF");
							break;
						case "staff":
							console.log("NF");
							break;
						default:
							console.log("error...");
					}

					$("#tag'.$this->renderId.'" + tagId).remove();
				}
            
            </script>';

			$this->output .= '</div>';
		}
	}

?>
