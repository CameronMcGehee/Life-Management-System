<?php

	require_once dirname(__FILE__)."/../render.php";

	class paymentMethodSelector extends render {

		public string $renderId;
		public array $options;
		public workspace $currentWorkspace;

		function __construct(string $renderId, array $options = []) {

			require_once dirname(__FILE__)."/../../table/paymentMethod.php";
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
					throw new Exception("Workspace Id doesn't exist (in paymentMethodSelector)");
				} else {
					$this->currentWorkspace->pullPaymentMethods($options['queryParams']);
				}
			} else {
				throw new Exception("No workspaceId set to pull paymentMethods from (in paymentMethodSelector)");
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
				$options['allowNone'] = false;
			}

			$this->options = $options;
		}

		function render() {
			$this->output = '<select id="'.$this->options['id'].'" class="'.$this->options['class'].'" style="'.$this->options['style'].'" name="'.$this->options['name'].'">';

			// For each method, get the name and output it

			if ($this->options['allowNone']) {
				$this->output .= '<option value="none">None</option>';
			}
			
			foreach ($this->currentWorkspace->paymentMethods as $paymentMethodId) {
				$currentPaymentMethod = new paymentMethod($paymentMethodId);
				$this->output .= '<option ';
				
				if ($this->options['selectedId'] == $paymentMethodId) {
					$this->output .= 'selected="selected"';
				}

				$this->output .= 'value="'.htmlspecialchars($paymentMethodId).'">'.htmlspecialchars(strval($currentPaymentMethod->name)).'</option>';
			}

			$this->output .= '</select>';
			
		}
	}

?>
