<?php

	require_once dirname(__FILE__)."/../render.php";

	class propertySelector extends render {

		public string $renderId;
		public array $options;

		function __construct(string $renderId, array $options = []) {

			require_once dirname(__FILE__)."/../../table/property.php";
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
					throw new Exception("Business Id doesn't exist (in propertySelector)");
				} else {
					$this->currentBusiness->pullProperties($options['queryParams']);
				}
			} else {
				throw new Exception("No businessId set to pull properties from (in propertySelector)");
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
			$this->output = '<select id="'.$this->options['id'].'" class="'.$this->options['class'].'" style="'.$this->options['style'].'" name="'.$this->options['name'].'">';

			// For each property, get the name and output it

			if ($this->options['allowNone']) {
				$this->output .= '<option value="none">None</option>';
			}
			
			foreach ($this->currentBusiness->properties as $propertyId) {
				$currentProperty = new property($propertyId);
				$this->output .= '<option ';
				
				if ($this->options['selectedId'] == $propertyId) {
					$this->output .= 'selected="selected"';
				}

				$this->output .= 'value="'.htmlspecialchars($propertyId).'">'.$currentProperty->address1.' - '.$currentProperty->city.'</option>';
			}

			$this->output .= '</select>';
			
		}
	}

?>
