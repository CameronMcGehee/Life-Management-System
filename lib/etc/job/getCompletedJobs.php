<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getCompletedJobs($startDateTime, $endDateTime, $queryParams = '') {
			
            require_once dirname(__DIR__).'../../database.php';
            $db = new database();
            
            $startDateTimeSan = $db->sanitize($startDateTime);
			$endDateTimeSan = $db->sanitize($endDateTime);

			$jobs = [];

            if ($queryParams != '') {
                $queryParams = ' '.$queryParams;
            }

            // Get all overdue jobs
            $fetch = $db->select('completedJob', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' AND (startDateTime < '".$startDateTimeSan."' AND completedDate = NULL)". $queryParams);
            if ($fetch) {
                $jobs = array_merge($jobs, $fetch);
            }

            // Get all jobs from the jobs table that match the date range
            $fetch = $db->select('completedJob', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' 
            AND (
                (startDateTime >= '".$startDateTimeSan."' AND startDateTime <= '".$endDateTimeSan."')
            OR (endDateTime >= '".$startDateTimeSan."' AND endDateTime <= '".$endDateTimeSan."') 
            OR (startDateTime <= '".$endDateTimeSan."' AND endDateTime IS NULL) 
            OR (frequencyInterval != 'none' AND startDateTime <= '".$startDateTimeSan."' AND endDateTime <= '".$endDateTimeSan."' )
            )". $queryParams);
            if ($fetch) {
                $jobs = array_merge($jobs, $fetch);
            }

			if ($jobs) {
                foreach ($jobs as $key => $job) {
                    if ($job['instanceDate'] == NULL) {
                        $instanceDate = new DateTime($job['startDateTime']);
                        $jobs[$key]['instanceDate'] = $instanceDate->format('Y-m-d');
                    } else {
                        $instanceDate = new DateTime($job['instanceDate']);
                        $jobs[$key]['instanceDate'] = $instanceDate->format('Y-m-d');
                    }
                }
                return $jobs;
            } else {
                return [];
            }
            
		}

?>
