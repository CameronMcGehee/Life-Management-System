<?php

    require_once dirname(__FILE__)."/../render.php";

    class customerTable extends render {

        private business $currentBusiness; // For storing the object of the business

        public string $renderId = '';
        public array $options = [];
        public string $queryParams = '';
        public int $page = 1;

        function __construct(string $renderId, array $options = []) {

            parent::__construct();

            if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

            if (empty($options['businessId'])) {
				if (isset($_SESSION['ultiscape_businessId'])) {
                    $options['businessId'] = $_SESSION['ultiscape_businessId'];
                } else {
                    throw new Exception("No businessId set to pull customers from (in customerTable)");
                }
			}

            if (empty($options['maxRows']) || !is_numeric($options['maxRows'])) {
				$options['maxRows'] = 10;
			}

            if (empty($options['pageGetVarName'])) {
				$options['pageGetVarName'] = '-p';
			}

            if (empty($options['sortGetVarName'])) {
				$options['sortGetVarName'] = '-s';
			}

            if (empty($options['usePage']) || !is_numeric($options['usePage'])) {
				$options['usePage'] = 1;
			}

            if (empty($options['showAdd'])) {
				$options['showAdd'] = false;
			}
            
            if (empty($options['showSort'])) {
				$options['showSort'] = false;
			}

            if (empty($options['useSort']) || !in_array($options['useSort'], ['az', 'za', 'newest', 'oldest'])) {
				$options['useSort'] = 'az';
			}

            if (empty($options['showPageNav'])) {
				$options['showPageNav'] = true;
			}

            if (empty($options['showBatch'])) {
				$options['showBatch'] = false;
			}

            $this->currentBusiness = new business($options['businessId']);

            $this->renderId = $renderId;

            require_once dirname(__FILE__)."/../../table/authToken.php";
            require_once dirname(__FILE__)."/../../table/customer.php";
            require_once dirname(__FILE__)."/../../table/customerEmailAddress.php";
            require_once dirname(__FILE__)."/../../table/customerPhoneNumber.php";
            require_once dirname(__FILE__)."/../../table/business.php";
            require_once dirname(__FILE__)."/../etc/tagEditor.php";
            require_once dirname(__FILE__)."/../etc/pageNavigator.php";
            require_once dirname(__FILE__)."/../etc/sortBySelector.php";

            // Page
            if (isset($_GET[$renderId.$options['pageGetVarName']])) {
                $options['usePage'] = $_GET[$renderId.$options['pageGetVarName']];
            }
            // Sort
            if (isset($_GET[$renderId.$options['sortGetVarName']])) {
                $options['useSort'] = $_GET[$renderId.$options['sortGetVarName']];
            }

            $this->options = $options;
        }

        function render() {
            $this->output = '';

            $firstLimit = ($this->options['usePage'] - 1) * $this->options['maxRows'];

            // Get count for page count
            $selectAll = $this->db->select('customer', "COUNT(customerId) AS num", "WHERE businessId = '".$_SESSION['ultiscape_businessId']."'");

            // Start div for table header (create customer button and nav)
            if ($this->options['showAdd'] || $this->options['showSort'] || $this->options['showPageNav']) {
                $this->output .= '<div class="twoCol">';

                // Render the add customer button
                $this->output .= '<div class="yCenteredFlex">';
                if ($this->options['showAdd']) {
                    $this->output .= '<a class="smallButtonWrapper greenButton noUnderline yCenteredFlex" href="'.$this->options['rootPathPrefix'].'admin/customers/customer">➕ New</a>';
                }
                $this->output .= '</div>';
                
                // Render the page navigator and sort-by selector
                if ((int)$selectAll[0]['num'] > 0) {
                    
                    $pageNav = new pageNavigator(ceil(($selectAll[0]['num'] / $this->options['maxRows'])), $this->options['usePage'], './', $this->renderId.'-p', 'float: right; padding: .2em;');
                    if ($this->options['showPageNav']) {
                        $pageNav->render();
                    }

                    $sortBySelector = new sortBySelector($this->renderId."sortSelector", './', $this->renderId.'-s', $this->options['useSort']);
                    
                    if ($this->options['showSort']) {
                        $sortBySelector->render();
                    }

                    $this->output .= '<div>'.$pageNav->output.' <span style="width: min-content; height: 100%; float:right;" class="yCenteredFlex">'.$sortBySelector->output.'</span></div>';
                }
                
                // End div for table header
                $this->output .= '</div>';
            }

            // Get actual results
            $params = '';

            switch ($this->options['useSort']) {
                case 'az':
                    $params .= 'ORDER BY nameIndex ASC ';
                    break;
                case 'za':
                    $params .= 'ORDER BY nameIndex DESC ';
                    break;
                case 'newest':
                    $params .= 'ORDER BY dateTimeAdded DESC ';
                    break;
                case 'oldest':
                    $params .= 'ORDER BY dateTimeAdded ASC ';
                    break;
                default:
                    break;
            }

            if (empty($this->queryParams)) {
                $params .= "LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            } else {
                $params .= $this->queryParams." LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            }

            $this->currentBusiness->pullCustomers($params);
			
			if (count($this->currentBusiness->customers) < 1) {
				$this->output .= '<table class="defaultTable" style="margin-top: .5em;"><tr><td class="la">No customers...</td></tr></table>
                ';
                return;
			}

			$this->output .= '<table class="defaultTable highlightOdd hoverHighlight" style="margin-top: .5em;">
            ';
			$this->output .= '<tr>';
            if ($this->options['showBatch']) {
                $this->output .= '<th class="ca nrb">✔</th>';
            }
            $this->output .= '<th class="la nrb">Name</th><th class="ca desktopOnlyTable-cell nrb nlb">Email(s)</th><th class="ca desktopOnlyTable-cell nrb nlb">Phone Number(s)</th><th class="la desktopOnlyTable-cell nlb">Billing Address</th><th class="ca mobileOnlyTable-cell nlb">Contact</th></tr>
            ';
			
			foreach ($this->currentBusiness->customers as $customerId) {

                $customer = new customer($customerId);

                // Tag Editor

                $tagEditor = new tagEditor($this->renderId."tagEditor", [
                    'rootPathPrefix' => $this->options['rootPathPrefix'],
                    'type' => 'customer',
                    'objectId' => $customerId,
                    'style' => 'display: inline;',
                    'largeSize' => false
                ]);
                $tagEditor->render();

                // Contact Info
                $customer->pullEmailAddresses();
                $customer->pullPhoneNumbers();

                $mobileInfo = '';

                $email = '';
                if (count($customer->emailAddresses) < 1) {
                    $email = '<span class="xyCenteredFlex maxWidth maxHeight"> - </span>';
                } else {
                    $mobileInfo .= '<span style="font-weight: bold;">Email</span>';
                    foreach ($customer->emailAddresses as $emailId) {
                        $currentEmail = new customerEmailAddress($emailId);
                        if ($currentEmail->existed) {
                            if (!empty($currentEmail->description)) {
                                $email .= '<li><span class="hoverTip">'.htmlspecialchars($currentEmail->email).'<span>'.htmlspecialchars($currentEmail->description).'</span></span></li>';
                                $mobileInfo .= '<li><span class="hoverTip">'.htmlspecialchars($currentEmail->email).'<span>'.htmlspecialchars($currentEmail->description).'</span></span></li>';
                            } else {
                                $email .= '<li>'.htmlspecialchars($currentEmail->email).'</li>';
                                $mobileInfo .= '<li>'.htmlspecialchars($currentEmail->email).'</li>';
                            }
                        }
                    }
                }

				$phone = '';
                if (count($customer->phoneNumbers) < 1) {
                    $phone = '<span class="xyCenteredFlex maxWidth maxHeight"> - </span>';
                } else {
                    $mobileInfo .= '<span style="font-weight: bold;">Phone</span>';
                    foreach ($customer->phoneNumbers as $phoneNumberId) {
                        $currentPhone = new customerPhoneNumber($phoneNumberId);
                        if ($currentPhone->existed) {

                            if (strlen((string)$currentPhone->phone1) == 10) {
                                $phoneOutput = "+".$currentPhone->phonePrefix." (".substr($currentPhone->phone1, 0, 3).") ".substr($currentPhone->phone1, 3, 3)." - ".substr($currentPhone->phone1, 6, 4);
                            } else {
                                $phoneOutput = "(+".$currentPhone->phonePrefix.") ".$currentPhone->phone1;
                            }

                            if (!empty($currentPhone->description)) {
                                $phone .= '<li><span class="hoverTip">'.htmlspecialchars($phoneOutput).'<span>'.htmlspecialchars($currentPhone->description).'</span></span></li>';
                                $mobileInfo .= '<li><span class="hoverTip">'.htmlspecialchars($phoneOutput).'<span>'.htmlspecialchars($currentPhone->description).'</span></span></li>';
                            } else {
                                $phone .= '<li>'.htmlspecialchars($phoneOutput).'</li>';
                                $mobileInfo .= '<li>(+'.htmlspecialchars($currentPhone->phonePrefix).') '.htmlspecialchars($phoneOutput).'</li>';
                            }
                        }
                    }
                }

                if ($mobileInfo == '') {
                    $mobileInfo = '<span class="xyCenteredFlex maxWidth maxHeight"> - </span>';
                }

                $billaddress = '';

                if (!empty($customer->billAddress1)) {
                    $billaddress .= htmlspecialchars($customer->billAddress1);
                    if (!empty($customer->billAddress2) || !empty($customer->billState) || !empty($customer->billCity) || !empty($customer->billZipCode)) {
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

                // Render the row
				$this->output .= '<tr>';
                if ($this->options['showBatch']) {
                    $this->output .= '<td class="ca nrb" style="width: 2em;"><input class="defaultInput" type="checkbox" name="'.$this->renderId.'-checkbox" value="'.htmlspecialchars($customer->customerId).'"></td>';
                }
                $this->output .= '<td class="la nrb vam" style="max-width: 10em;"><a href="'.$this->options['rootPathPrefix'].'admin/customers/customer?id='.htmlspecialchars(htmlspecialchars($customer->customerId)).'" style="font-size: 1.1em; margin-right: .5em;"><b>'.htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->lastName).'</b></a>'.$tagEditor->output.'</td>
                                    <td class="la desktopOnlyTable-cell nlb nrb">'.$email.'</td>
                                    <td class="la desktopOnlyTable-cell nrb nlb">'.$phone.'</td>
                                    <td class="la desktopOnlyTable-cell nlb">'.$billaddress.'</td>
                                    <td class="la mobileOnlyTable-cell nlb">'.$mobileInfo.'</td>
                                </tr>
                ';
			
            }

			$this->output .= '</table>';

            // Batch Operations

            if ($this->options['showBatch']) {
                $deleteCustomersAuthToken = new authToken;
                $deleteCustomersAuthToken->authName = 'deleteCustomers';
                $deleteCustomersAuthToken->set();
                $this->output .= '<div style="margin-top: .5em;font-size: .9em;"><p>With selected: 
                <select class="defaultInput" id="batchSelect">
                    <option value="none">Nothing</option>
                    <option value="delete">❌ Delete</option>
                </select> <button class="defaultInput" onclick="'.$this->renderId.'batchOperation()">Go</button></p>
                
                <script>
                    var deleteCustomersAuthToken = "'.$deleteCustomersAuthToken->authTokenId.'";
                    function '.$this->renderId.'batchOperation() {
    
                        var allChecked = document.querySelectorAll("input[name='.$this->renderId.'-checkbox]:checked");
    
                        var checkedArray = Array.from(allChecked).map(checkbox => checkbox.value);
    
                        if ($("#batchSelect option:selected").val() == "delete" && checkedArray.length > 0) {
                                $("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/customer/deleteCustomers.php", {"customers[]": checkedArray, "authToken": deleteCustomersAuthToken}, function() {
                                    if ($("#scriptLoader").html() == "success") {
                                        document.location.reload(true);
                                    }
                                });
                        }
                    }
                
                </script>
                
                </div>';
            }
            
        }

    }

?>
