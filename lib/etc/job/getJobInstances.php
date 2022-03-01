<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getJobInstances($startDateTime, $endDateTime, array $exclude = array()) { // "singular", "recurring", "completed", "cancelled", "overdue"
			
            require_once dirname(__FILE__).'../getJobs.php';
            require_once dirname(__FILE__).'../../time/getRecurringDates.php';

            $jobs = getJobs($startDateTime, $endDateTime, $exclude);

            $instances = [];

            foreach ($jobs as $job) {
                $freq = $job['frequency'];
                $freqInt = $job['frequencyInterval'];
                $startDate = new DateTime($job['startDateTime']);

                $dates = getRecurringDates($startDate->format('Y-m-d'), $endDateTime, $freqInt, $freq);
                foreach ($dates as $date) {
                    $job['instanceDate'] = $date;
                    array_push($instances, $job);
                }
            }

            return $instances;
            
		}

?>
