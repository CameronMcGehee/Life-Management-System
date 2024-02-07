<?php

	// JUST FYI THIS IS COMPLETELY NOT FINISHED AT ALL!

	require_once dirname(__FILE__)."/../render.php";

	class contactCalendarEventCalendar extends render {

		public string $renderId;
		public array $options;

		function __construct(string $renderId, array $options = []) {

			parent::__construct();
			
			$this->renderId = $renderId;

            require_once dirname(__FILE__)."/../../table/calendarEvent.php";
			require_once dirname(__FILE__)."/../../etc/calendarEvent/getActiveCalendarEventInstances.php";
			require_once dirname(__FILE__)."/../../etc/calendarEvent/getCompletedCalendarEvents.php";

			if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

			if (!isset($options['workspaceId'])) {
				if (isset($_SESSION['lifems_workspaceId'])) {
                    $options['workspaceId'] = $_SESSION['lifems_workspaceId'];
                } else {
                    throw new Exception("No workspaceId set to pull invoices from (in contactInvoiceTable)");
                }
			}

            if (!isset($options['contactId'])) {
				if (isset($_SESSION['lifems_contactId'])) {
                    $options['contactId'] = $_SESSION['lifems_contactId'];
                } else {
                    throw new Exception("No contactId set to pull invoices from (in contactInvoiceTable)");
                }
			}

			if (empty($options['showAdd'])) {
				$options['showAdd'] = true;
			}

			if (empty($options['showMonthSelector'])) {
				$options['showMonthSelector'] = true;
			}

            if (empty($options['month'])) {
				$monthDate = new DateTime();
				$options['month'] = $monthDate->format('Y-m');
			}

			if (empty($options['getVarName'])) {
				$options['getVarName'] = 'm';
			}

			if (empty($options['style'])) {
				$options['style'] = '';
			}

			$this->options = $options;
		}

		function render() {

			// Make sure that the date is actually a valid date
			if ($this->options['month'] != '') {
				if (!strtotime($this->options['month'])) {
					return false;
				}
			} else {
				return false;
			}

			$month_ini = new DateTime("first day of ".$this->options['month']);
			$month_end = new DateTime("last day of ".$this->options['month']);
			$startDate = $month_ini->format('Y-m-d');
			$endDate =  $month_end->format('Y-m-d');
			$today = date('Y-m-d');
			$backgroundColor = 'white';

			// Get first day of week

			$firstDay = new DateTime($startDate);
			$firstDay = (int)$firstDay->format('N');   // Output: 1 for Monday - 7 for Sunday.

			// Get last day of week

			$lastDay = new DateTime($endDate);
			$lastDay = (int)$lastDay->format('N');   // Output: 1 for Monday - 7 for Sunday.

            // Get all calendarEvents within the date range

			$calendarEvents = getActiveCalendarEventInstances($month_ini->format('Y-m-d'), $month_end->format('Y-m-d'), "AND linkedToContactId = '".$this->options['contactId']."'");
			$completedCalendarEvents = getCompletedCalendarEvents($month_ini->format('Y-m-d'), $month_end->format('Y-m-d'), "AND linkedToContactId = '".$this->options['contactId']."'");

			$this->output = '';

			// Start div for table header (create contact button and nav)
            if ($this->options['showAdd'] || $this->options['showMonthSelector']) {
                $this->output .= '<div style="display: grid; grid-template-columns: 20% 80%; grid-template-rows: 3em; grid-template-areas: \'1 2\'; margin-left: 2em; margin-right: 2em;">';

                // Render the add contact button
                $this->output .= '<div class="yCenteredFlex" style="width: 10em;">';
                if ($this->options['showAdd']) {
                    $this->output .= '<a class="smallButtonWrapper greenButton noUnderline yCenteredFlex" href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/calendarEvent">➕ Schedule CalendarEvent</a>';
                }
                $this->output .= '</div>';

				if ($this->options['showMonthSelector']) {
					$monthSelectorOutput = '<input onchange="'.$this->renderId.'ChangeMonth()" class="defaultInput" type="month" name="monthSelector" id="'.$this->renderId.'MonthSelector" value="'.htmlspecialchars($this->options['month']).'">';

					$this->output .= '
						<script>
							function '.$this->renderId.'ChangeMonth() {

								var url = new URL(window.location.href);

								url.searchParams.set("'.$this->renderId.'-'.$this->options['getVarName'].'", $("#'.$this->renderId.'MonthSelector").val());

								window.location.replace(url.href);
								
							}
						
						</script>';
				}

				

                $this->output .= '<div><span style="height: 100%; float:right; margin-right: .3em;" class="yCenteredFlex">'.$monthSelectorOutput.'</span></div></div>';

			}

			// Start the table with headers for weekdays

			$this->output .= '<table class="defaultTable calendarEventCalendar" style="'.$this->options['style'].'"><tr style="height: 1em;"><td class="defaultTableCell" style="text-align: center;"><b>Sunday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Monday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Tuesday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Wednesday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Thursday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Friday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Saturday</b></td>';

			$firstDayHit = false;
			$lastDayHit = false;
			$currentDayOfWeek = 7;
			$currentDate = $month_ini;
			
			// Draw blanks up until the first day of the month and then go through all the days

			if (!$currentDate || !$month_ini || !$month_end) {
				return false;
			}

			$oneDayInterval = new DateInterval('P1D');
			$month_end->add($oneDayInterval);
			$dayNumber = 1;

			while (!$lastDayHit) {
				if ($currentDayOfWeek == 7) {
					$this->output .= '</tr><tr>';

					$weekRangeDate = strtotime ($currentDate->format("Y-m-d"));
					$weekRange = array (
						"start" => $currentDate->format("Y-m-d"),
						"end" => date ('Y-m-d', strtotime ('next saturday', $weekRangeDate))
					);

						$backgroundColor = 'white';
					
				}

				if ($currentDayOfWeek == $firstDay && !$firstDayHit) {
					$firstDayHit = true;
				}

				if ($firstDayHit) {
					// ECHO OUT THE THE CELL

                    // Get calendarEvents of that day

					$calendarEventsOutput = '';

					foreach ($completedCalendarEvents as $currentCalendarEvent) {
						$currentCalendarEventStartDateTime = new DateTime($currentCalendarEvent['instanceDate']);
						if ($currentCalendarEventStartDateTime->format('Y-m-d') == $currentDate->format('Y-m-d')) { // If it is today
							$calendarEventsOutput .= '<a href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/completedcalendarEvent?id='.htmlspecialchars($currentCalendarEvent['completedCalendarEventId']).'&instance='.$currentCalendarEvent['instanceDate'].'"><p class="calendarEvent completedCalendarEvent">'.htmlspecialchars($currentCalendarEvent['name']).'</p></a>';
						}
                    }

                    foreach ($calendarEvents as $currentCalendarEvent) {
						$currentCalendarEventStartDateTime = new DateTime($currentCalendarEvent['instanceDate']);
						$currentCalendarEventEndDateTime = new DateTime($currentCalendarEvent['endDateTime']);
						if ($currentCalendarEventStartDateTime->format('Y-m-d') == $currentDate->format('Y-m-d')) { // If it is today
							if (!$currentCalendarEvent['isCompleted']) {
								if ($currentCalendarEvent['isCancelled']) {
									if ($currentCalendarEvent['frequencyInterval'] == 'none') {
										$calendarEventsOutput .= '<a href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/calendarEvent?id='.htmlspecialchars($currentCalendarEvent['calendarEventId']).'&instance='.$currentCalendarEvent['instanceDate'].'"><p class="calendarEvent cancelledCalendarEvent">'.htmlspecialchars($currentCalendarEvent['name']).'</p></a>';
									} else {
										$calendarEventsOutput .= '<a href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/instance?id='.htmlspecialchars($currentCalendarEvent['calendarEventId']).'&instance='.$currentCalendarEvent['instanceDate'].'"><p class="calendarEvent cancelledCalendarEvent">'.htmlspecialchars($currentCalendarEvent['name']).'</p></a>';
									}
								} elseif ($currentCalendarEvent['isRescheduled']) {
									$calendarEventsOutput .= '<a href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/instance?id='.htmlspecialchars($currentCalendarEvent['calendarEventId']).'&instance='.$currentCalendarEvent['instanceDate'].'"><p class="calendarEvent activeCalendarEvent">'.htmlspecialchars($currentCalendarEvent['name']).'</p></a>';
								} else {
									$calendarEventsOutput .= '<a href="'.$this->options['rootPathPrefix'].'contact/calendarEvents/calendarEvent?id='.htmlspecialchars($currentCalendarEvent['calendarEventId']).'&instance='.$currentCalendarEvent['instanceDate'].'"><p class="calendarEvent activeCalendarEvent">'.htmlspecialchars($currentCalendarEvent['name']).'</p></a>';

								}
							}
						}
                    }

					// If the current day is the actual current day, make the border of the cell green

                    if ($currentDate->format("Y-m-d") == $today) {
                        $borderStyle = ' border: 2px solid green;';
                    } else {
                        $borderStyle = '';
                    }
					
					$this->output .= '<td class="vat" style="text-align: center;'.$borderStyle.' background-color: '.$backgroundColor.'"><p style="font-size: .8em; text-align: left;"><b>'.$dayNumber.'</b></p>'.$calendarEventsOutput.'</td>';
					$dayNumber++;
					$currentDate->add($oneDayInterval);
				} else {
					$this->output .= '<td class="defaultTableCell" style="text-align: center; background-color: lightgray;"></td>';
				}

				if ($currentDayOfWeek == 7) {
					$currentDayOfWeek = 1;
				} else {
					$currentDayOfWeek++;
				}

				if (!$month_end->format("Y-m-d") || !$currentDate->format("Y-m-d")) {
					return false;
				} else {
					if ($currentDate->format("Y-m-d") == $month_end->format("Y-m-d")) {
						$lastDayHit = true;
					}
				}

			}

			for ($i = $currentDayOfWeek; $i < 7; $i++) {
				$this->output .= '<td class="defaultTableCell" style="text-align: center; background-color: lightgray;"></td>';
			}
			
			$this->output .= '</tr>';

			$this->output .= '</table>';
			
		}
	}

?>
