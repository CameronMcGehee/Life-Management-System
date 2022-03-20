<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getActiveJobInstances($startDateTime, $endDateTime, array $exclude = array()) { // "singular", "recurring", "completed", "cancelled", "overdue"
			
            require_once dirname(__DIR__).'/job/getActiveJobs.php';
            require_once dirname(__DIR__).'/time/getRecurringDates.php';

            $instances = [];

            $jobs = getActiveJobs($startDateTime, $endDateTime, $exclude);

            foreach ($jobs as $job) {
                $freq = $job['frequency'];
                $freqInt = $job['frequencyInterval'];
                $weekday = $job['weekday'];
                $startDate = new DateTime($job['startDateTime']);
                if ($job['endDateTime'] == NULL) {
                    $endDate = NULL;
                } else {
                    $endDate = new DateTime($job['endDateTime']);
                    $endDate = $endDate->format('Y-m-d');
                }

                if ($freqInt == 'none') {
                    $job['instanceDate'] = $startDate->format('Y-m-d');
                    $job['isCompleted'] = false;
                    $job['isCancelled'] = false;
                    $job['isRescheduled'] = false;
                    array_push($instances, $job);
                } else {
                    require_once dirname(__DIR__).'../../database.php';
                    $db = new database();
                    $dates = getRecurringDates($startDate->format('Y-m-d'), $endDate, $startDateTime, $endDateTime, $freqInt, $freq, $weekday);

                    // Get instance exceptions
                    require_once dirname(__DIR__).'../../table/jobInstanceException.php';
                    $instanceExceptions = $db->select('jobInstanceException', '*', "WHERE jobId = '".$job['jobId']."'");
                    $exceptionsList = [];
                    if ($instanceExceptions) {
                        foreach ($instanceExceptions as $exception) {
                            array_push($exceptionsList, $exception);
                        }
                    }
                    foreach ($dates as $date) {
                        $foundException = false;
                        foreach ($exceptionsList as $exception) { // Check for an exception that matches this instance date - if there is then push the exception date
                            if ($exception['instanceDate'] == $date) {
                                $foundException = true;
                                $instanceJobForPush = $job;
                                $instanceJobForPush['instanceDate'] = new DateTime($exception['startDateTime']);
                                $instanceJobForPush['instanceDate'] = $instanceJobForPush['instanceDate']->format('Y-m-d');

                                $instanceJobForPush['isCompleted'] = false;
                                $instanceJobForPush['isCancelled'] = false;
                                $instanceJobForPush['isRescheduled'] = false;

                                $instanceJobForPush['name'] = $exception['name'];
                                $instanceJobForPush['description'] = $exception['description'];
                                $instanceJobForPush['privateNotes'] = $exception['privateNotes'];
                                $instanceJobForPush['price'] = $exception['price'];
                                $instanceJobForPush['estHours'] = $exception['estHours'];
                                $instanceJobForPush['isPrepaid'] = $exception['isPrepaid'];
                                $instanceJobForPush['startDateTime'] = $exception['startDateTime'];
                                $instanceJobForPush['endDateTime'] = $exception['endDateTime'];

                                if ($exception['isCompleted']) {
                                    $instanceJobForPush['isCompleted'] = true;
                                    $instanceJobForPush['completedJobId'] = $exception['linkedToCompletedJobId'];
                                }

                                if ($exception['isCancelled']) {
                                    $instanceJobForPush['isCancelled'] = true;
                                }

                                if ($exception['isRescheduled']) {
                                    $instanceJobForPush['isRescheduled'] = true;
                                }
                                
                                array_push($instances, $instanceJobForPush);
                            }
                        }

                        if (!$foundException) {
                            $job['instanceDate'] = $date;
                            $job['isCompleted'] = false;
                            $job['isCancelled'] = false;
                            $job['isRescheduled'] = false;
                            array_push($instances, $job);
                        }
                    }
                }
            }

            return $instances;
            
		}

?>
