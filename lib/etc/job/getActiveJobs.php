<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getActiveJobs($startDateTime, $endDateTime, array $exclude = array()) { // "singular", "recurring", "completed", "cancelled", "overdue"
			
            require_once dirname(__DIR__).'../../database.php';
            $db = new database();
            
            $startDateTimeSan = $db->sanitize($startDateTime);
			$endDateTimeSan = $db->sanitize($endDateTime);

			$jobs = [];

            // Get all overdue jobs
            $fetch = $db->select('job', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' AND (startDateTime < '".$startDateTimeSan."' AND completedDate = NULL)");
            if ($fetch) {
                $jobs = array_merge($jobs, $fetch);
            }

            // Get all jobs from the jobs table that match the date range
            $fetch = $db->select('job', '*', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' AND ((startDateTime >= '".$startDateTimeSan."' AND endDateTime <= '".$startDateTimeSan."') OR (startDateTime >= '".$startDateTimeSan."' AND endDateTime IS NULL) OR (endDateTime >= '".$startDateTimeSan."' AND endDateTime <= '".$endDateTimeSan."') OR (startDateTime <= '".$endDateTimeSan."' AND endDateTime IS NULL) OR (frequencyInterval != 'none' AND startDateTime <= '".$startDateTimeSan."' AND endDateTime <= '".$endDateTimeSan."' ))");
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
