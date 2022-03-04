<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getActiveJobInstances($startDateTime, $endDateTime, array $exclude = array()) { // "singular", "recurring", "completed", "cancelled", "overdue"
			
            require_once dirname(__FILE__).'../../job/getActiveJobs.php';
            require_once dirname(__FILE__).'../../time/getRecurringDates.php';

            $instances = [];

            $jobs = getActiveJobs($startDateTime, $endDateTime, $exclude);

            foreach ($jobs as $job) {
                $freq = $job['frequency'];
                $freqInt = $job['frequencyInterval'];
                $startDate = new DateTime($job['startDateTime']);

                if ($freqInt == 'none') {
                    $job['instanceDate'] = $startDate->format('Y-m-d');
                    $job['isCompleted'] = false;
                    array_push($instances, $job);
                } else {
                    $dates = getRecurringDates($startDate->format('Y-m-d'), $endDateTime, $freqInt, $freq);
                    foreach ($dates as $date) {
                        // Check for an exception that matches this instasnce date - if there is then push the exception date
                        if (false) {

                        } else {
                            $job['instanceDate'] = $date;
                            $job['isCompleted'] = false;
                            array_push($instances, $job);
                        }
                    }
                }
            }

            return $instances;
            
		}

?>
