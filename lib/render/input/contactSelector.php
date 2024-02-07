<?php

	require_once dirname(__FILE__)."/../render.php";

	class contactSelector extends render {

		public string $renderId;
		public array $options;
		public workspace $currentWorkspace;

		function __construct(string $renderId, array $options = []) {

			require_once dirname(__FILE__)."/../../table/contact.php";
			require_once dirname(__FILE__)."/../../table/workspace.php";
			
			parent::__construct();
			
			$this->renderId = $renderId;

			if (empty($options['queryParams'])) {
				$options['queryParams'] = '';
			}

			if (empty($options['selectedId'])) {
				$options['selectedId'] = 'none';
			}

			if (isset($_SESSION['lifems_workspaceId'])) {
				$this->currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);
				if (!$this->currentWorkspace->existed) {
					throw new Exception("Workspace Id doesn't exist (in contactSelector)");
				} else {
					$this->currentWorkspace->pullContacts($options['queryParams']);
				}
			} else {
				throw new Exception("No workspaceId set to pull contacts from (in contactSelector)");
			}

			if (empty($options['style'])) {
				$options['style'] = '';
			}

			if (empty($options['class'])) {
				$options['class'] = 'defaultInput';
			}

			if (empty($options['name'])) {
				$options['name'] = '';
			}

			if (empty($options['id'])) {
				$options['id'] = $renderId;
			}

			if (empty($options['allowNone'])) {
				$options['allowNone'] = true;
			}

			if (empty($options['readonly'])) {
				$options['readonly'] = false;
			}

			$this->options = $options;
		}

		function render() {
			$this->output = '<select id="'.$this->options['id'].'" class="'.$this->options['class'].'" style="'.$this->options['style'].'" name="'.$this->options['name'].'">';

			// For each contact, get the name and output it

			if ($this->options['readonly']) {
				if ($this->options['selectedId'] == 'none') {
					$this->output .= '<option value="none">None</option>';
				}
			} else {
				if ($this->options['allowNone']) {
					$this->output .= '<option value="none">None</option>';
				}
			}

			
			
			foreach ($this->currentWorkspace->contacts as $contactId) {
				$currentContact = new contact($contactId);

				$renderThisContact = true;

				if ($this->options['readonly'] && $this->options['selectedId'] != $currentContact->contactId) {
					$renderThisContact = false;
				}

				if ($renderThisContact) {
					$this->output .= '<option ';
				
					if ($this->options['selectedId'] == $contactId) {
						$this->output .= 'selected="selected"';
					}
	
					$this->output .= 'value="'.htmlspecialchars($contactId).'">'.htmlspecialchars($currentContact->firstName).' '.htmlspecialchars(strval($currentContact->lastName)).'</option>';
				}
				
			}

			$this->output .= '</select>';
			
		}
	}

?>
