<?php

    require_once dirname(__FILE__)."/../render.php";

    class customerTable extends render {

        private $currentBusiness; // For storing the object of the business

        public $queryParams = '';
        public $rootPathPrefix = './';
        public $perPage = 10;
        public $page = 1;

        function __construct($businessId = NULL) {

            require_once dirname(__FILE__)."/../../table/customer.php";
            require_once dirname(__FILE__)."/../../table/customerEmailAddress.php";
            require_once dirname(__FILE__)."/../../table/customerPhoneNumber.php";
            require_once dirname(__FILE__)."/../../table/business.php";
            require_once dirname(__FILE__)."/../etc/pageNavigator.php";

            // If the businessId is not given, just use the one set by the session. If it is not in the session, throw an exception.
            if ($businessId === NULL) {
                if (isset($_SESSION['ultiscape_businessId'])) {
                    $businessId = $_SESSION['ultiscape_businessId'];
                } else {
                    throw new Exception("No businessId set to pull customers from (in customerTable)");
                }
            }
            
            $this->currentBusiness = new business($businessId);            
        }

        function render() {
            $this->output = '';

            $firstLimit = ($this->page - 1) * $this->perPage;

            // Get all results for page count
            $this->currentBusiness->pullCustomers($this->queryParams);

            // Render the page navigator
            $pageNav = new pageNavigator(ceil((count($this->currentBusiness->customers) / $this->perPage)), $this->page, './', 'p', 'text-align: right; padding: .2em;');
            $pageNav->render();
            $this->output .= $pageNav->output.'';

            // Get actual results
            if (empty($this->queryParams)) {
                $params = "LIMIT ".$firstLimit.", ".$this->perPage;
            } else {
                $params = $this->queryParams." LIMIT ".$firstLimit.", ".$this->perPage;
            }

            $this->currentBusiness->pullCustomers($params);
			
			if (count($this->currentBusiness->customers) < 1) {
				$this->output = '<table class="defaultTable" style="width: 100%; max-width: 100%;"><tr><td class="lax">No customers...</td></tr></table>';
                return;
			}

			$this->output .= '<table class="defaultTable" style="width: 100%; max-width: 100%; margin-top: .5em;">';
			$this->output .= '<tr><th class="lax" style="text-decoration: underline;">Name</th><th class="lax" style="text-decoration: underline;">E-mail(s)</th><th class="lax" style="text-decoration: underline;">Phone Number(s)</th><th class="lax" style="text-decoration: underline;">Billing Address</th></tr>';
			
			foreach ($this->currentBusiness->customers as $customerId) {

                $customer = new customer($customerId);
                $customer->pullEmailAddresses();
                $customer->pullPhoneNumbers();

                $email = '';
                if (count($customer->emailAddresses) < 1) {
                    $email = '<span class="xyCenteredFlex maxWidth maxHeight"> - </span>';
                } else {
                    foreach ($customer->emailAddresses as $emailId) {
                        $currentEmail = new customerEmailAddress($emailId);
                        if ($currentEmail->existed) {
                            $email .= '<li><a href="'.$this->rootPathPrefix.'customers/customer/email?id='.$currentEmail->customerEmailAddressId.'">'.$currentEmail->email.'</a></li>';
                        }
                    }
                }

				// $phone = '('.htmlspecialchars($customer->phone1).') '.htmlspecialchars($customer->phone2).' - '.htmlspecialchars($customer->phone3);
				$phone = '';
                if (count($customer->phoneNumbers) < 1) {
                    $phone = '<span class="xyCenteredFlex maxWidth maxHeight"> - </span>';
                } else {
                    foreach ($customer->phoneNumbers as $phoneNumberId) {
                        $currentPhone = new customerPhoneNumber($phoneNumberId);
                        if ($currentPhone->existed) {
                            $phone .= '<li><a href="'.$this->rootPathPrefix.'customers/customer/phonenumber?id='.$currentPhone->customerPhoneNumberId.'">+'.htmlspecialchars($currentPhone->phonePrefix).' ('.htmlspecialchars($currentPhone->phone1).') - '.htmlspecialchars($currentPhone->phone2).' - '.htmlspecialchars($currentPhone->phone3).'</a></li>';
                        }
                    }
                }

				$billaddress = htmlspecialchars($customer->billAddress1).' '.htmlspecialchars($customer->billCity).', '.htmlspecialchars($customer->billState).', '.htmlspecialchars($customer->billZipCode);

				$this->output .= '<tr><td class="lax"><a href="'.$this->rootPathPrefix.'customers/customer?id='.htmlspecialchars($customer->customerId).'">'.htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->lastName).'</a></td><td class="lax">'.$email.'</td><td class="lax">'.$phone.'</td><td class="lax">'.$billaddress.'</td></tr>';
			
            }

			$this->output .= '</table>';
        }

    }

?>
