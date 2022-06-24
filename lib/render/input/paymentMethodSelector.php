<?php

	require_once dirname(__FILE__)."/../render.php";

	class paymentMethodSelector extends render {

		public string $renderId;
		public array $options;

		function __construct(string $renderId, array $options = []) {

			require_once dirname(__FILE__)."/../../table/paymentMethod.php";
			require_once dirname(__FILE__)."/../../table/business.php";
			
			parent::__construct();
			
			$this->renderId = $renderId;

			if (empty($options['queryParams'])) {
				$options['queryParams'] = '';
			}

			if (empty($options['selectedId'])) {
				$options['selectedId'] = 'none';
			}

			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->currentBusiness = new business($_SESSION['ultiscape_businessId']);
				if (!$this->currentBusiness->existed) {
					throw new Exception("Business Id doesn't exist (in paymentMethodSelector)");
				} else {
					$this->currentBusiness->pullPaymentMethods($options['queryParams']);
				}
			} else {
				throw new Exception("No businessId set to pull paymentMethods from (in paymentMethodSelector)");
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
			
			foreach ($this->currentBusiness->paymentMethods as $paymentMethodId) {
				$currentPaymentMethod = new paymentMethod($paymentMethodId);
				$this->output .= '<option ';
				
				if ($this->options['selectedId'] == $paymentMethodId) {
					$this->output .= 'selected="selected"';
				}

				$this->output .= 'value="'.htmlspecialchars($paymentMethodId).'">'.htmlspecialchars($currentPaymentMethod->name).'</option>';
			}

			$this->output .= '</select>';
			
		}
	}

?>
