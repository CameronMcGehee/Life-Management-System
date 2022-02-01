<?php

	require_once dirname(__FILE__)."/../render.php";

	class jobCalendar extends render {

		public string $renderId;
		public array $options;

		function __construct(string $renderId, array $options = []) {
			$this->renderId = $renderId;

            require_once dirname(__FILE__)."/../../table/jobSingular.php";
            require_once dirname(__FILE__)."/../../table/jobRecurring.php";
            require_once dirname(__FILE__)."/../../table/jobCompleted.php";
            require_once dirname(__FILE__)."/../../table/jobCancellation.php";

			if (empty($options['rootPathPrefix'])) {
				$options['rootPathPrefix'] = './';
			}

            if (empty($options['month'])) {
				$options['month'] = date('Y-m');
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

            // Get all jobs within the date range

            $currentMonthJobsArray = [];

			$this->output = '';

			// Start the table with headers for weekdays

			$this->output .= '<table class="defaultTable jobCalendar" style="'.$this->options['style'].'"><tr style="height: 1em;"><td class="defaultTableCell" style="text-align: center;"><b>Sunday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Monday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Tuesday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Wednesday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Thursday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Friday</b></td><td class="defaultTableCell" style="text-align: center;"><b>Saturday</b></td>';

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

                    // Get jobs of that day

                    foreach ($currentMonthJobsArray as $jobId) {
                        echo '<p class="job activeJob">Active Job...</p>';
                    }

					// If the current day is the actual current day, make the border of the cell green

                    if ($currentDate->format("Y-m-d") == $today) {
                        $borderStyle = ' border: 2px solid green;';
                    } else {
                        $borderStyle = '';
                    }
					
					$this->output .= '<td class="vat" style="text-align: center;'.$borderStyle.' background-color: '.$backgroundColor.'"><p style="font-size: .8em; text-align: left;"><b>'.$dayNumber.'</b></p></td>';
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
