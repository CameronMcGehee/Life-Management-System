<?php

	function getRecurringDates(string $eventStartDate, $eventEndDate, string $viewStartDate, $viewEndDate, string $int, int $freq, $weekday) {

		// Get which of the two end dates (eventEndDate and viewEndDate) come first, to be used for When->until()

		if ($eventEndDate == NULL) {
			$untilDate = $viewEndDate;
		} else if (new DateTime($eventEndDate) < new DateTime($viewEndDate)) {
			$untilDate = $eventEndDate;
		} else {
			$untilDate = $viewEndDate;
		}

		// For some reason daily recurence with When does not include the last date. Add one day so that it will create a date on the end date if there is an instance there
		$untilDate = new DateTime($untilDate);
		$untilDate = $untilDate->modify("+1 day")->format('Y-m-d');

		require_once dirname(__DIR__).'../../when/When.php';

		$result = [];

		$r = new When();
		$r->RFC5545_COMPLIANT = When::IGNORE;
		$r->rangeLimit = 1000;

		switch ($int) {
			case 'day':

				$r->startDate(new DateTime($eventStartDate));
				$r->freq("daily");
				$r->interval($freq);
				$r->until(new DateTime($untilDate));
				
				$r->generateOccurrences();
				
				foreach ($r->occurrences as $dateObj) {
					if ($dateObj <= new DateTime($untilDate)) {
						array_push($result, $dateObj->format('Y-m-d'));
					}
				}
				return $result;
				break;
			case 'week':

				$r->startDate(new DateTime($eventStartDate));
				$r->freq("weekly");
				$r->interval($freq);
				$r->until(new DateTime($untilDate));

				if ($weekday == NULL) {
					return false;
				} else {
					switch ($weekday) {
						case '0':
							$weekDayStr = 'su';
							break;
						case '1':
							$weekDayStr = 'mo';
							break;
						case '2':
							$weekDayStr = 'tu';
							break;
						case '3':
							$weekDayStr = 'we';
							break;
						case '4':
							$weekDayStr = 'th';
							break;
						case '5':
							$weekDayStr = 'fr';
							break;
						case '6':
							$weekDayStr = 'sa';
							break;
						default:
							$weekDayStr = 'mo';
					}
					$r->byday($weekDayStr);
				}
				
				$r->generateOccurrences();

				foreach ($r->occurrences as $dateObj) {
					array_push($result, $dateObj->format('Y-m-d'));
				}
				return $result;
			   
				break;
			case 'month':

				$r->RFC5545_COMPLIANT = When::IGNORE;
				if ($freq == 1) {
					$r->startDate(new DateTime($viewStartDate));
				} else {

				}
				$r->startDate(new DateTime($eventStartDate));
				$r->freq("monthly");
				$r->interval($freq);
				$r->until(new DateTime($untilDate));

				if ($weekday == NULL) {
					// Use the month number of the date
					$dateObj = new DateTime($eventStartDate);
					$r->bymonthday($dateObj->format('d'));
				} else {
					// Parse monthweek-wekkdaynumber
					$parsedWeek = explode('-', $weekday);
					switch ($parsedWeek[1]) {
						case '0':
							$weekDayStr = 'su';
							break;
						case '1':
							$weekDayStr = 'mo';
							break;
						case '2':
							$weekDayStr = 'tu';
							break;
						case '3':
							$weekDayStr = 'we';
							break;
						case '4':
							$weekDayStr = 'th';
							break;
						case '5':
							$weekDayStr = 'fr';
							break;
						case '6':
							$weekDayStr = 'sa';
							break;
						default:
							$weekDayStr = 'mo';
					}
					$r->byday($parsedWeek[0].$weekDayStr);
				}
				
				$r->generateOccurrences();

				foreach ($r->occurrences as $dateObj) {
					if ($dateObj <= new DateTime($untilDate)) {
						array_push($result, $dateObj->format('Y-m-d'));
					}
				}
				return $result;
				
				break;
			case 'year':
				$r->startDate(new DateTime($eventStartDate));
				$r->freq("yearly");
				$r->interval($freq);
				$r->until(new DateTime($untilDate));
				
				$r->generateOccurrences();
				
				foreach ($r->occurrences as $dateObj) {
					if ($dateObj <= new DateTime($untilDate)) {
						array_push($result, $dateObj->format('Y-m-d'));
					}
				}
				return $result;
				break;
		}
	}

?>
