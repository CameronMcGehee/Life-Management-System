<?php

	require_once dirname(__FILE__)."/../render.php";

	class customerSelector extends render {

		public string $renderId;
		public array $options;

		function __construct(string $renderId, array $options = []) {

			require_once dirname(__FILE__)."/../../table/customer.php";
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
					throw new Exception("Business Id doesn't exist (in customerSelector)");
				} else {
					$this->currentBusiness->pullCustomers($options['queryParams']);
				}
			} else {
				throw new Exception("No businessId set to pull customers from (in customerSelector)");
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

			$this->options = $options;
		}

		function render() {
			$this->output = '<select class="'.$this->options['class'].'" style="'.$this->options['style'].'" name="'.$this->options['name'].'">';

			// For each customer, get the name and output it

			if ($this->options['allowNone']) {
				$this->output .= '<option value="none">None</option>';
			}
			
			foreach ($this->currentBusiness->customers as $customerId) {
				$currentCustomer = new customer($customerId);
				$this->output .= '<option ';
				
				if ($this->options['selectedId'] == $customerId) {
					$this->output .= 'selected="selected"';
				}

				$this->output .= 'value="'.htmlspecialchars($customerId).'">'.htmlspecialchars($currentCustomer->firstName).' '.htmlspecialchars($currentCustomer->lastName).'</option>';
			}

			$this->output .= '</select>';
			
		}
	}

?>
