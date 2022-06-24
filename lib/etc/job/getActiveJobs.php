<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getActiveJobs($startDateTime, $endDateTime, $queryParams = '') {
			
            require_once dirname(__DIR__).'../../database.php';
            $db = new database();
            
            $startDateTimeSan = $db->sanitize($startDateTime);
			$endDateTimeSan = $db->sanitize($endDateTime);

			$jobs = [];

            if ($queryParams != '') {
                $queryParams = ' '.$queryParams;
            }

            // Get all overdue jobs
            $fetch = $db->select('job', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' AND (startDateTime < '".$startDateTimeSan."' AND completedDate = NULL)". $queryParams);
            if ($fetch) {
                $jobs = array_merge($jobs, $fetch);
            }

            // Get all jobs from the jobs table that match the date range
            $fetch = $db->select('job', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' 
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
                return $jobs;
            } else {
                return [];
            }
            
		}

?>
