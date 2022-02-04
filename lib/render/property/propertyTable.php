<?php

    require_once dirname(__FILE__)."/../render.php";

    class propertyTable extends render {

        private business $currentBusiness; // For storing the object of the business

        public string $renderId = '';
        public array $options = [];
        private $currentDate;

        function __construct(string $renderId, array $options = []) {

            parent::__construct();

            if (!isset($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

            if (!isset($options['queryParams'])) {
				$options['queryParams'] = '';
			}

            if (!isset($options['businessId'])) {
				if (isset($_SESSION['ultiscape_businessId'])) {
                    $options['businessId'] = $_SESSION['ultiscape_businessId'];
                } else {
                    throw new Exception("No businessId set to pull properties from (in propertyTable)");
                }
			}

            if (!isset($options['maxRows']) || !is_numeric($options['maxRows'])) {
				$options['maxRows'] = 10;
			}

            if (!isset($options['pageGetVarName'])) {
				$options['pageGetVarName'] = '-p';
			}

            if (!isset($options['sortGetVarName'])) {
				$options['sortGetVarName'] = '-s';
			}

            if (!isset($options['searchGetVarName'])) {
				$options['searchGetVarName'] = '-q';
			}

            if (!isset($options['usePage']) || !is_numeric($options['usePage'])) {
				$options['usePage'] = 1;
			}

            if (!isset($options['showAdd'])) {
				$options['showAdd'] = false;
			}
            
            if (!isset($options['showSort'])) {
				$options['showSort'] = false;
			}

            if (!isset($options['showSearch'])) {
				$options['showSearch'] = true;
			}

            if (!isset($options['useSearch'])) {
				$options['useSearch'] = '';
			}

            if (!isset($options['useSort']) || !in_array($options['useSort'], ['az', 'za', 'newest', 'oldest'])) {
				$options['useSort'] = 'az';
			}

            if (!isset($options['showPageNav'])) {
				$options['showPageNav'] = true;
			}

            if (!isset($options['showCustomer'])) {
				$options['showCustomer'] = true;
			}

            if (!isset($options['showLawnSize'])) {
				$options['showLawnSize'] = true;
			}

            if (!isset($options['showMulchQuantity'])) {
				$options['showMulchQuantity'] = true;
			}

            if (!isset($options['showPricePerMow'])) {
				$options['showPricePerMow'] = true;
			}

            if (!isset($options['showLastServiced'])) {
				$options['showLastServiced'] = true;
			}

            if (!isset($options['showDateAdded'])) {
				$options['showDateAdded'] = false;
			}

            if (!isset($options['showBatch'])) {
				$options['showBatch'] = false;
			}

            $this->currentBusiness = new business($options['businessId']);

            $this->renderId = $renderId;

            require_once dirname(__FILE__)."/../../table/authToken.php";
            require_once dirname(__FILE__)."/../../table/property.php";
            require_once dirname(__FILE__)."/../../table/business.php";
            require_once dirname(__FILE__)."/../etc/tagEditor.php";
            require_once dirname(__FILE__)."/../etc/pageNavigator.php";
            require_once dirname(__FILE__)."/../etc/sortBySelector.php";
            require_once dirname(__FILE__)."/../etc/searchBar.php";
            require_once dirname(__FILE__)."/../../etc/time/diffCalc.php";

            // Page
            if (isset($_GET[$renderId.$options['pageGetVarName']])) {
                $options['usePage'] = $_GET[$renderId.$options['pageGetVarName']];
            }
            // Sort
            if (isset($_GET[$renderId.$options['sortGetVarName']])) {
                $options['useSort'] = $_GET[$renderId.$options['sortGetVarName']];
            }
            // Search
            if (isset($_GET[$renderId.$options['searchGetVarName']])) {
                $options['useSearch'] = $_GET[$renderId.$options['searchGetVarName']];
            }

            $this->options = $options;

            $this->currentDate = new DateTime;
            $this->currentDate = $this->currentDate->format('Y-m-d H:i:s');
        }

        function render() {
            $this->output = '';

            $firstLimit = ($this->options['usePage'] - 1) * $this->options['maxRows'];

            // Get count for page count
            $pageCountQuery = "WHERE businessId = '".$_SESSION['ultiscape_businessId']."'";
            if ($this->options['useSearch'] != '') {
                $keywords = explode(" ", $this->options['useSearch']);
                foreach ($keywords as $key => $keyword) {
                    $pageCountQuery .= ' AND (firstName LIKE \'%'.$this->db->sanitize($keyword).'%\' OR lastName LIKE \'%'.$this->db->sanitize($keyword).'%\')';
                }
            }
            if ($this->options['queryParams'] != '') {
                $pageCountQuery .= ' '.$this->options['queryParams'];
            }
            $selectAll = $this->db->select('property', "COUNT(propertyId) AS num", $pageCountQuery);

            // Start div for table header (create property button and nav)
            if ($this->options['showAdd'] || $this->options['showSort'] || $this->options['showPageNav']) {
                $this->output .= '<div style="display: grid; grid-template-columns: 20% 80%; grid-template-rows: 1.5em; grid-template-areas: "1 2";">';

                // Render the add property button
                $this->output .= '<div class="yCenteredFlex" style="width: 6em;">';
                if ($this->options['showAdd']) {
                    $this->output .= '<a class="smallButtonWrapper greenButton noUnderline yCenteredFlex" href="'.$this->options['rootPathPrefix'].'admin/properties/property">➕ New</a>';
                }
                $this->output .= '</div>';
                
                // Render the page navigator, sort-by selector, and search bar
                if ($selectAll || $this->options['useSearch'] != '') {

                    if (!is_bool($selectAll)) {
                        $pageNav = new pageNavigator(ceil(($selectAll[0]['num'] / $this->options['maxRows'])), $this->options['usePage'], './', $this->renderId.'-p', 'float: right; padding: .2em;');
                        if ($this->options['showPageNav']) {
                            $pageNav->render();
                        }  
                    }

                    $sortBySelector = new sortBySelector($this->renderId."sortSelector", './', $this->renderId.$this->options['sortGetVarName'], $this->options['useSort']);
                    $sortBySelector->style = 'width: 5em;';
                    $searchBar = new searchBar($this->renderId."searchBar", './', $this->renderId.$this->options['searchGetVarName'], $this->options['useSearch']);
                    $searchBar->style = 'width: 5em;';
                    
                    if ($this->options['showSort']) {
                        $sortBySelector->render();
                    }
                    if ($this->options['showSearch']) {
                        $searchBar->render();
                    }

                    if (isset($pageNav)) {
                        $pageNavOutput = $pageNav->output;
                    } else {
                        $pageNavOutput = '';
                    }

                    $this->output .= '<div><span style="height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$pageNavOutput.'</span> <span style="width: min-content; height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$sortBySelector->output.'</span> <span style="width: min-content; height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$searchBar->output.'</span></div>';
                }
                
                // End div for table header
                $this->output .= '</div>';
            }

            // Get actual results
            $params = '';

            if ($this->options['useSearch'] != '') {
                foreach ($keywords as $key => $keyword) {
                    $params .= 'AND (firstName LIKE \'%'.$this->db->sanitize($keyword).'%\' OR lastName LIKE \'%'.$this->db->sanitize($keyword).'%\')';
                }
            }

            switch ($this->options['useSort']) {
                case 'newest':
                    $params .= 'ORDER BY dateTimeAdded DESC ';
                    break;
                case 'oldest':
                    $params .= 'ORDER BY dateTimeAdded ASC ';
                    break;
                default:
                    break;
            }

            if (empty($this->options['queryParams'])) {
                $params .= "LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            } else {
                $params .= $this->options['queryParams']." LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            }

            $this->currentBusiness->pullProperties($params);
            
			if (count($this->currentBusiness->properties) < 1) {
				$this->output .= '<table class="defaultTable" style="margin-top: .5em;"><tr><td class="la">No properties...</td></tr></table>
                ';
                return;
			}

			$this->output .= '<table class="defaultTable highlightOdd hoverHighlight" style="margin-top: .5em;">
            ';
            
            $this->output .= '<tr>
            ';
            
            
            if ($this->options['showBatch']) {
                $this->output .= '<th class="ca nrb">✔</th>
                ';
            }

            $this->output .= '<th class="la nrb">Address</th>
            ';

            if ($this->options['showCustomer']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nrb nlb">Customer</th>
                ';
            }
            if ($this->options['showLawnSize']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nrb nlb">Lawn Size</th>
                ';
            }
            if ($this->options['showPricePerMow']) {
                $this->output .= '<th class="la desktopOnlyTable-cell nlb">Price Per Mow</th>
                ';
            }
            if ($this->options['showMulchQuantity']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nlb">Mulch ('.htmlspecialchars($this->currentBusiness->areaSymbol).'²)</th>
                ';
            }
            if ($this->options['showLastServiced']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nlb">Last Serviced</th>
                ';
            }
            if ($this->options['showDateAdded']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nlb">Serviced Since</th>
                ';
            }

            $this->output .= '</tr>
            ';
			
			foreach ($this->currentBusiness->properties as $propertyId) {

                $property = new property($propertyId);

                // Tag Editor
                // $tagEditor = new tagEditor($this->renderId."tagEditor", [
                //     'rootPathPrefix' => $this->options['rootPathPrefix'],
                //     'type' => 'property',
                //     'objectId' => $propertyId,
                //     'style' => 'display: inline;',
                //     'largeSize' => false
                // ]);
                // $tagEditor->render();

                $address = '';

                if (!empty($property->address1)) {
                    $address .= htmlspecialchars($property->address1);
                    if (!empty($property->address2) || !empty($property->state) || !empty($property->city) || !empty($property->zipCode)) {
                        $address .= '<br>';
                    }
                }
                if (!empty($property->address2)) {
                    $address .= htmlspecialchars($property->address2);
                    if (!empty($property->city) || !empty($property->state) || !empty($property->zipCode)) {
                        $address .= '<br>';
                    }
                }
                if (!empty($property->city)) {
                    $address .= htmlspecialchars($property->city);
                    if (!empty($property->state)) {
                        $address .= ', ';
                    }
                }
                if (!empty($property->state)) {
                    $address .= htmlspecialchars($property->state);
                }
                if (!empty($property->zipCode)) {
                    $address .= ' '.htmlspecialchars($property->zipCode).'';
                }

                if ($address == '') {
                    $address = '<span style="color: red;">Not on file.</span>';
                }

                $customerName = 'NF';

                if (!is_null($property->lawnSize)) {
                    $lawnSize = (int)$property->lawnSize;
                } else {
                    $lawnSize = '-';
                }

                if (!is_null($property->pricePerMow)) {
                    $pricePerMow = (float)$property->pricePerMow;
                } else {
                    $pricePerMow = '-';
                }

                if (!is_null($property->mulchQuantity)) {
                    $mulchQuantity = (float)$property->mulchQuantity;
                } else {
                    $mulchQuantity = '-';
                }

                // Render the row
				$this->output .= '<tr>';
                if ($this->options['showBatch']) {
                    $this->output .= '<td class="ca nrb" style="width: 2em;"><input class="defaultInput" type="checkbox" name="'.$this->renderId.'-checkbox" value="'.htmlspecialchars($property->propertyId).'"></td>';
                }
                $this->output .= '<td class="la nrb vam" style="max-width: 10em;"><a href="'.$this->options['rootPathPrefix'].'admin/properties/property?id='.htmlspecialchars(htmlspecialchars($property->propertyId)).'" style="font-size: 1.1em; margin-right: .5em;"><b>'.$address.'</b></a></td>
                ';
                                    
                if ($this->options['showCustomer']) {
                    $this->output .= '<td class="la nlb nrb">'.$customerName.'</td>
                    ';
                }
                if ($this->options['showLawnSize']) {
                    $this->output .= '<td class="la nrb nlb">'.$lawnSize.'</td>
                    ';
                }
                if ($this->options['showPricePerMow']) {
                    $this->output .= '<td class="la nrb nlb">'.$pricePerMow.'</td>
                    ';
                }
                if ($this->options['showMulchQuantity']) {
                    $this->output .= '<td class="la nrb nlb">'.$mulchQuantity.'</td>
                    ';
                }
                if ($this->options['showLastServiced']) {
                    $diffOutput = getDateTimeDiffString($property->dateTimeAdded, $this->currentDate);
                    $dateAddedOutput = new DateTime($property->dateTimeAdded);
                    $dateAddedOutput = $dateAddedOutput->format('m/d/Y');
                    $this->output .= '<td class="ca nlb">'.htmlspecialchars($dateAddedOutput).' ('.$diffOutput.' ago)</td>
                    ';
                }
                if ($this->options['showDateAdded']) {
                    $diffOutput = getDateTimeDiffString($property->dateTimeAdded, $this->currentDate);
                    $dateAddedOutput = new DateTime($property->dateTimeAdded);
                    $dateAddedOutput = $dateAddedOutput->format('m/d/Y');
                    $this->output .= '<td class="ca nlb">'.htmlspecialchars($dateAddedOutput).' ('.$diffOutput.' ago)</td>
                    ';
                }

                $this->output .='</tr>
                ';
			
            }

			$this->output .= '</table>';

            // Batch Operations

            if ($this->options['showBatch']) {
                $deletePropertiesAuthToken = new authToken;
                $deletePropertiesAuthToken->authName = 'deleteProperties';
                $deletePropertiesAuthToken->set();
                $this->output .= '<div style="margin-top: .5em;font-size: .9em;"><p>With selected: 
                <select class="defaultInput" id="batchSelect">
                    <option value="none">Nothing</option>
                    <option value="delete">❌ Delete</option>
                </select> <button class="defaultInput" onclick="'.$this->renderId.'batchOperation()">Go</button></p>
                
                <script>
                    var deletePropertiesAuthToken = "'.$deletePropertiesAuthToken->authTokenId.'";
                    function '.$this->renderId.'batchOperation() {
    
                        var allChecked = document.querySelectorAll("input[name='.$this->renderId.'-checkbox]:checked");
    
                        var checkedArray = Array.from(allChecked).map(checkbox => checkbox.value);
    
                        if ($("#batchSelect option:selected").val() == "delete" && checkedArray.length > 0) {
                                $("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/property/deleteProperties.php", {"properties[]": checkedArray, "authToken": deletePropertiesAuthToken}, function() {
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
