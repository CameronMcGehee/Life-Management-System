<?php

    require_once dirname(__FILE__)."/../render.php";

    class customerTable extends render {

        private $currentBusiness; // For storing the object of the business

        public $queryParams = '';
        public $rootPathPrefix = './';
        public $perPage = 15;
        public $page = 1;

        function __construct($businessId = NULL) {

            parent::__construct();

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

            // Get count for page count
            $selectAll = $this->db->select('customer', "COUNT(customerId) AS num", "WHERE businessId = '".$_SESSION['ultiscape_businessId']."'");

            // Start div for table header (create customer button and nav)
            $this->output .= '<div class="twoCol">';

            // Render the add customer button

            $this->output .= '<div class="yCenteredFlex"><a class="smallButtonWrapper greenButton noUnderline yCenteredFlex" href="'.$this->rootPathPrefix.'customers/create">➕ New</a></div>';
            
            // Render the page navigator
            $pageNav = new pageNavigator(ceil(($selectAll[0]['num'] / $this->perPage)), $this->page, './', 'p', 'text-align: right; padding: .2em;');
            $pageNav->render();
            $this->output .= '<div>'.$pageNav->output.'</div>';

            // End div for table header
            $this->output .= '</div>';

            // Get actual results
            if (empty($this->queryParams)) {
                $params = "LIMIT ".$firstLimit.", ".$this->perPage;
            } else {
                $params = $this->queryParams." LIMIT ".$firstLimit.", ".$this->perPage;
            }

            $this->currentBusiness->pullCustomers($params);
			
			if (count($this->currentBusiness->customers) < 1) {
				$this->output = '<table class="defaultTable"><tr><td class="la">No customers...</td></tr></table>
                ';
                return;
			}

			$this->output .= '<table class="defaultTable" style="margin-top: .5em;">
            ';
			$this->output .= '<tr><th class="la nrb">Name</th><th class="ca desktopOnlyTable-cell nrb nlb">Email(s)</th><th class="ca desktopOnlyTable-cell nrb nlb">Phone Number(s)</th><th class="ca desktopOnlyTable-cell nlb">Billing Address</th><th class="ca mobileOnlyTable-cell nlb">Contact</th></tr>
            ';
			
			foreach ($this->currentBusiness->customers as $customerId) {

                $customer = new customer($customerId);
                $customer->pullEmailAddresses();
                $customer->pullPhoneNumbers();

                $mobileInfo = '';

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

                $billaddress = '';

                if (!empty($customer->billAddress1)) {
                    $billaddress .= htmlspecialchars($customer->billAddress1);
                    if (!empty($customer->billAddress2)) {
                        $billaddress .= '<br>';
                    }
                }
                if (!empty($customer->billAddress2)) {
                    $billaddress .= htmlspecialchars($customer->billAddress2);
                    if (!empty($customer->billCity) || !empty($customer->billState) || !empty($customer->billZipCode)) {
                        $billaddress .= '<br>';
                    }
                }
                if (!empty($customer->billCity)) {
                    $billaddress .= htmlspecialchars($customer->billCity);
                    if (!empty($customer->billState)) {
                        $billaddress .= ', ';
                    }
                }
                if (!empty($customer->billState)) {
                    $billaddress .= htmlspecialchars($customer->billState);
                }
                if (!empty($customer->billZipCode)) {
                    $billaddress .= ' '.htmlspecialchars($customer->billZipCode).'';
                }

                if ($billaddress == '') {
                    $billaddress = '<span style="color: red;">Not on file.</span>';
                }

				$this->output .= '<tr><td class="la nrb"><a href="'.$this->rootPathPrefix.'customers/customer?id='.htmlspecialchars($customer->customerId).'">'.htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->lastName).'</a></td><td class="la desktopOnlyTable-cell nlb nrb">'.$email.'</td><td class="la desktopOnlyTable-cell nrb nlb">'.$phone.'</td><td class="la desktopOnlyTable-cell nlb">'.$billaddress.'</td><td class="la mobileOnlyTable-cell nlb">'.$mobileInfo.'</td></tr>
                ';
			
            }

			$this->output .= '</table>';
        }

    }

?>
