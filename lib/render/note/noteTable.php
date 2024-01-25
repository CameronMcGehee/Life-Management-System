<?php

    require_once dirname(__FILE__)."/../render.php";

    class noteTable extends render {

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
                    throw new Exception("No businessId set to pull notes from (in noteTable)");
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

            if (!isset($options['useSort']) || !in_array($options['useSort'], ['az', 'za', 'newest', 'oldest', 'updateDesc', 'updateAsc'])) {
				$options['useSort'] = 'updateDesc';
			}

            if (!isset($options['showPageNav'])) {
				$options['showPageNav'] = true;
			}

            if (!isset($options['showPreview'])) {
				$options['showPreview'] = true;
			}

            if (!isset($options['showLastUpdate'])) {
				$options['showLastUpdate'] = false;
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
            require_once dirname(__FILE__)."/../../table/note.php";
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
                    $pageCountQuery .= ' AND (title LIKE \'%'.$this->db->sanitize($keyword).'%\')';
                }
            }
            if ($this->options['queryParams'] != '') {
                $pageCountQuery .= ' '.$this->options['queryParams'];
            }
            $selectAll = $this->db->select('note', "COUNT(noteId) AS num", $pageCountQuery);

            // Start div for table header (create note button and nav)
            if ($this->options['showAdd'] || $this->options['showSort'] || $this->options['showPageNav']) {
                $this->output .= '<div style="display: grid; grid-template-columns: 20% 80%; grid-template-rows: 1.5em; grid-template-areas: "1 2";">';

                // Render the add note button
                $this->output .= '<div class="yCenteredFlex" style="width: 6em;">';
                if ($this->options['showAdd']) {
                    $this->output .= '<a class="smallButtonWrapper greenButton noUnderline yCenteredFlex" href="'.$this->options['rootPathPrefix'].'admin/notes/note">➕ New</a>';
                }
                $this->output .= '</div>';
                
                // Render the page navigator, sort-by selector, and search bar
                if ($selectAll || $this->options['useSearch'] != '') {

                    if (is_array($selectAll) && (int)$selectAll[0]['num'] > 0) {
                        $pageNav = new pageNavigator(ceil(($selectAll[0]['num'] / $this->options['maxRows'])), $this->options['usePage'], './', $this->renderId.'-p', 'float: right; padding: .2em;');
                        if ($this->options['showPageNav']) {
                            $pageNav->render();
                        }

                        $sortBySelector = new sortBySelector($this->renderId."sortSelector", './', $this->renderId.$this->options['sortGetVarName'], $this->options['useSort']);
                        $sortBySelector->style = 'width: 5em;';

                        if ($this->options['showSort']) {
                            $sortBySelector->render();
                        }
                    }
                    
                    $searchBar = new searchBar($this->renderId."searchBar", './', $this->renderId.$this->options['searchGetVarName'], $this->options['useSearch']);
                    $searchBar->style = 'width: 5em;';
                    
                    if ($this->options['showSearch']) {
                        $searchBar->render();
                    }

                    if (isset($searchBar)) {
                        $searchBarOutput = $searchBar->output;
                    } else {
                        $searchBarOutput = '';
                    }

                    if (isset($sortBySelector)) {
                        $sortBySelectorOutput = $sortBySelector->output;
                    } else {
                        $sortBySelectorOutput = '';
                    }

                    if (isset($pageNav)) {
                        $pageNavOutput = $pageNav->output;
                    } else {
                        $pageNavOutput = '';
                    }

                    $this->output .= '<div><span style="height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$pageNavOutput.'</span> <span style="width: min-content; height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$sortBySelectorOutput.'</span> <span style="width: min-content; height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$searchBarOutput.'</span></div>';
                }
                
                // End div for table header
                $this->output .= '</div>';
            }

            // Get actual results
            $params = '';

            if ($this->options['useSearch'] != '') {
                foreach ($keywords as $key => $keyword) {
                    $params .= 'AND (title LIKE \'%'.$this->db->sanitize($keyword).'%\')';
                }
            }

            switch ($this->options['useSort']) {
                case 'az':
                    $params .= 'ORDER BY title ASC ';
                    break;
                case 'za':
                    $params .= 'ORDER BY title DESC ';
                    break;
                case 'newest':
                    $params .= 'ORDER BY dateTimeAdded DESC ';
                    break;
                case 'oldest':
                    $params .= 'ORDER BY dateTimeAdded ASC ';
                    break;
                case 'updateDesc':
                    $params .= 'ORDER BY lastUpdate DESC ';
                    break;
                case 'updateAsc':
                    $params .= 'ORDER BY lastUpdate ASC ';
                    break;
                default:
                    break;
            }

            if (empty($this->options['queryParams'])) {
                $params .= "LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            } else {
                $params .= $this->options['queryParams']." LIMIT ".$firstLimit.", ".$this->options['maxRows'];
            }

            $this->currentBusiness->pullNotes($params);
            
			if (count($this->currentBusiness->notes) < 1) {
				$this->output .= '<table class="defaultTable" style="margin-top: .5em;"><tr><td class="la">No notes...</td></tr></table>
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

            $this->output .= '<th class="la nrb">Name</th>
            ';

            if ($this->options['showPreview']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nrb nlb">Email(s)</th>
                ';
            }
            if ($this->options['showLastUpdate']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nlb">Updated</th>
                ';
            }
            if ($this->options['showDateAdded']) {
                $this->output .= '<th class="ca desktopOnlyTable-cell nlb">Created</th>
                ';
            }

            $this->output .= '</tr>
            ';
			
			foreach ($this->currentBusiness->notes as $noteId) {

                $note = new note($noteId);

                // Tag Editor
                $tagEditor = new tagEditor($this->renderId."tagEditor", [
                    'rootPathPrefix' => $this->options['rootPathPrefix'],
                    'type' => 'note',
                    'objectId' => $noteId,
                    'style' => 'display: inline;',
                    'largeSize' => false
                ]);
                $tagEditor->render();

                // Preview
                $preview = 'prev';

                // Render the row
				$this->output .= '<tr>';
                if ($this->options['showBatch']) {
                    $this->output .= '<td class="ca nrb" style="width: 2em;"><input class="defaultInput" type="checkbox" name="'.$this->renderId.'-checkbox" value="'.htmlspecialchars($note->noteId).'"></td>';
                }
                $this->output .= '<td class="la nrb vam" style="max-width: 10em;"><a href="'.$this->options['rootPathPrefix'].'admin/notes/note?id='.htmlspecialchars($note->noteId).'" style="font-size: 1.1em; margin-right: .5em;"><b>'.htmlspecialchars($note->title).'</b></a>'.$tagEditor->output.'</td>';
                                    
                if ($this->options['showPreview']) {
                    $this->output .= '<td class="la desktopOnlyTable-cell nlb nrb">'.$preview.'</td>
                    ';
                }
                if ($this->options['showLastUpdate']) {
                    $diffOutput = getDateTimeDiffString($note->lastUpdate, $this->currentDate);
                    $lastUpdateOutput = new DateTime($note->lastUpdate);
                    $lastUpdateOutput = $lastUpdateOutput->format('m/d/Y');
                    $this->output .= '<td class="ca desktopOnlyTable-cell nlb">'.htmlspecialchars($dateAddedOutput).' ('.$diffOutput.' ago)</td>
                    ';
                }
                if ($this->options['showDateAdded']) {
                    $diffOutput = getDateTimeDiffString($note->dateTimeAdded, $this->currentDate);
                    $dateAddedOutput = new DateTime($note->dateTimeAdded);
                    $dateAddedOutput = $dateAddedOutput->format('m/d/Y');
                    $this->output .= '<td class="ca desktopOnlyTable-cell nlb">'.htmlspecialchars($dateAddedOutput).' ('.$diffOutput.' ago)</td>
                    ';
                }

                $this->output .='</tr>
                ';
			
            }

			$this->output .= '</table>';

            // Batch Operations

            if ($this->options['showBatch']) {
                $deleteNotesAuthToken = new authToken;
                $deleteNotesAuthToken->authName = 'deleteNotes';
                $deleteNotesAuthToken->set();
                $this->output .= '<div style="margin-top: .5em;font-size: .9em;"><p>With selected: 
                <select class="defaultInput" id="batchSelect">
                    <option value="none">Nothing</option>
                    <option value="delete">❌ Delete</option>
                </select> <button class="defaultInput" onclick="'.$this->renderId.'batchOperation()">Go</button></p>
                
                <script>
                    var deleteNotesAuthToken = "'.$deleteNotesAuthToken->authTokenId.'";
                    function '.$this->renderId.'batchOperation() {
    
                        var allChecked = document.querySelectorAll("input[name='.$this->renderId.'-checkbox]:checked");
    
                        var checkedArray = Array.from(allChecked).map(checkbox => checkbox.value);
    
                        if ($("#batchSelect option:selected").val() == "delete" && checkedArray.length > 0) {
                                $("#scriptLoader").load("'.$this->options['rootPathPrefix'].'admin/scripts/async/note/deleteNotes.php", {"notes[]": checkedArray, "authToken": deleteNotesAuthToken}, function() {
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
