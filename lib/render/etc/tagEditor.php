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

			$this->output = '<div style="'.$this->options['style'].'" id="'.$this->renderId.'">';

			require_once dirname(__FILE__)."/../../table/authToken.php";

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

				$this->output .= '<span id="tag'.$this->renderId.htmlspecialchars($tagId).'" class="defaultMainShadows tagBox" style="background-color: '.htmlspecialchars($currentTag->color).';';
				
				if ($this->options['largeSize']) {
					$this->output .= ' font-size: 1.2em;';
				} else {
					$this->output .= ' font-size: .9em;';
				}
				
				$this->output .= '"><a style="color: white; text-decoration: none; border: none;" href="'.$this->options['rootPathPrefix'].'admin/customer/tag/?id='.$tagId.'">'.htmlspecialchars($currentTag->name).'</a>';
				
				if ($this->options['showDelete']) {
					$this->output .= '<img onclick="'.$this->renderId.'removeTag(\''.htmlspecialchars($this->options['objectId']).'\', \''.htmlspecialchars($tagId).'\')" id="remove'.htmlspecialchars($tagId).'" src="'.$this->options['rootPathPrefix'].'images/ultiscape/icons/cross.svg" style="height: 1em; filter: brightness(100); cursor: pointer;">';
				}
				
				$this->output .= '</span>';
			}

			// Output a script that loads the script that actually removes the tag when the delete button is pressed, and remove that tag from the page if successful

			switch ($this->options['type']) {
				case 'crew':
					$deleteCrewTagLinksAuthToken = new authToken;
					$deleteCrewTagLinksAuthToken->authName = 'deleteCrewTagLinks';
					$deleteCrewTagLinksAuthToken->set();
					break;
				case 'customer':
					$deleteCustomerTagLinksAuthToken = new authToken;
					$deleteCustomerTagLinksAuthToken->authName = 'deleteCustomerTagLinks';
					$deleteCustomerTagLinksAuthToken->set();
					break;
				case 'chemical':
					$deleteChemicalTagLinksAuthToken = new authToken;
					$deleteChemicalTagLinksAuthToken->authName = 'deleteChemicalTagLinks';
					$deleteChemicalTagLinksAuthToken->set();
					break;
				case 'equipment':
					$deleteEquipmentTagLinksAuthToken = new authToken;
					$deleteEquipmentTagLinksAuthToken->authName = 'deleteEquipmentTagLinks';
					$deleteEquipmentTagLinksAuthToken->set();
					break;
				case 'staff':
					$deleteStaffTagLinksAuthToken = new authToken;
					$deleteStaffTagLinksAuthToken->authName = 'deleteStaffTagLinks';
					$deleteStaffTagLinksAuthToken->set();
					break;
				default:
					throw new Exception("Type given is not supported (in tagEditor '".$this->renderId."')");
			}

			$this->output .= '
            <script>
                function '.$this->renderId.'removeTag(objectId, tagId) {
					// Load the appropriate script to remove the tag link
					';
					
					switch ($this->options['type']){
						case "crew":
							$this->output .= '$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/crew/deleteCrewTagLinks.php", {
								"authToken": "'.$deleteCrewTagLinksAuthToken->authTokenId.'",
								"crewTagLinks": [[objectId, tagId]]
							});';
							break;
						case "customer":
							$this->output .= '$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/customer/deleteCustomerTagLinks.php", {
								"authToken": "'.$deleteCustomerTagLinksAuthToken->authTokenId.'",
								"customerTagLinks": [[objectId, tagId]]
							});';
							break;
						case "chemical":
							$this->output .= '$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/chemical/deleteChemicalTagLinks.php", {
								"authToken": "'.$deleteChemicalTagLinksAuthToken->authTokenId.'",
								"chemicalTagLinks": [[objectId, tagId]]
							});';
							break;
						case "equipment":
							$this->output .= '$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/equipment/deleteEquipmentTagLinks.php", {
								"authToken": "'.$deleteEquipmentTagLinksAuthToken->authTokenId.'",
								"equipmentTagLinks": [[objectId, tagId]]
							});';
							break;
						case "staff":
							$this->output .= '$("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/staff/deleteStaffTagLinks.php", {
								"authToken": "'.$deleteStaffTagLinksAuthToken->authTokenId.'",
								"staffTagLinks": [[objectId, tagId]]
							});';
							break;
						default:
							$this->output .= 'console.log("error...");';
					}

					$this->output .= '
					$("#tag'.$this->renderId.'" + tagId).remove();
				}
            
            </script>';

			if ($this->options['showAdd']) {
				$this->output .= '<span onclick="'.$this->renderId.'clickAddTagButton(\''.htmlspecialchars($this->options['objectId']).'\')" id="addTagButton'.htmlspecialchars($this->options['objectId']).'" class="defaultMainShadows" style="position: relative; display: inline-flex; align-items: center; background-color: #f2f2f2; border-radius: 1em; border: 1px solid #d9d9d9; padding-left: .3em; padding-right: .3em; padding-top: .1em; padding-bottom: .1em; margin-right: .5em;';

				if ($this->options['largeSize']) {
					$this->output .= ' font-size: 1.2em;';
				} else {
					$this->output .= ' font-size: .9em;';
				}

				$this->output .= '"><img src="'.$this->options['rootPathPrefix'].'images/ultiscape/icons/tags.svg" style="height: 1em; cursor: pointer;">';

				// Ouput a dialog (hidden initially), that shows up when the button is clicked, that lists all the available tags that haven't been added yet

				$this->output .= '<span class="addTagDialog" id="'.$this->renderId.'addTagDialog'.$this->options['objectId'].'" style="display: none;';

				if ($this->options['largeSize'] == false) {
					$this->output .= ' font-size: 1.4em;';
				}
				
				$this->output .= '">All tags that have not been added will go here</span></span>';

				// Output a script that shows the dialog for each tag add button

				$this->output .= '
					<script>
					
						function '.$this->renderId.'clickAddTagButton(objectId) {
							// If the one that is clicked is current hidden, show it
							if ($("#'.$this->renderId.'addTagDialog" + objectId).css("display") == "none") {
								$("#'.$this->renderId.'addTagDialog" + objectId).css({"display": "inline-block"});
								// Hide all other selectors
								$(".addTagDialog").not("#'.$this->renderId.'addTagDialog" + objectId).hide();
							} else {
								$("#'.$this->renderId.'addTagDialog" + objectId).css({"display": "none"});
							}
						}
					
					</script>
				';

				// Output a script that hides all other tag pickers when a tag picker has been clicked, or a click is anywhere else on the page



				// Script for when you click a tag in the dialog, which calls the script that creates the link and removes the tag from the selector dialog and appends it into the list of tags
			}

			$this->output .= '</div>';
		}
	}

?>
