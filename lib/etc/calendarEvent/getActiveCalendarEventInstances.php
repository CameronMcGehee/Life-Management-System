<?php

    // Get a full list of calendarEvents within a date span (start and end dates are included) (overdue calendarEvents do not have to be within the date span!)

		function getActiveCalendarEventInstances($startDateTime, $endDateTime, $queryParams = '') {
			
            require_once dirname(__DIR__).'/calendarEvent/getActiveCalendarEvents.php';
            require_once dirname(__DIR__).'/time/getRecurringDates.php';

            $instances = [];

            $calendarEvents = getActiveCalendarEvents($startDateTime, $endDateTime, $queryParams);

            foreach ($calendarEvents as $calendarEvent) {
                $freq = $calendarEvent['frequency'];
                $freqInt = $calendarEvent['frequencyInterval'];
                $weekday = $calendarEvent['weekday'];
                $startDate = new DateTime($calendarEvent['startDateTime']);
                if ($calendarEvent['endDateTime'] == NULL) {
                    $endDate = NULL;
                } else {
                    $endDate = new DateTime($calendarEvent['endDateTime']);
                    $endDate = $endDate->format('Y-m-d');
                }

                if ($freqInt == 'none') {
                    $calendarEvent['instanceDate'] = $startDate->format('Y-m-d');
                    $calendarEvent['isCompleted'] = false;
                    $calendarEvent['isCancelled'] = $calendarEvent['isCancelled'];
                    $calendarEvent['isRescheduled'] = false;
                    array_push($instances, $calendarEvent);
                } else {
                    require_once dirname(__DIR__).'../../database.php';
                    $db = new database();
                    $dates = getRecurringDates($startDate->format('Y-m-d'), $endDate, $startDateTime, $endDateTime, $freqInt, $freq, $weekday);

                    // Get instance exceptions
                    require_once dirname(__DIR__).'../../table/calendarEventInstanceException.php';
                    $instanceExceptions = $db->select('calendarEventInstanceException', '*', "WHERE calendarEventId = '".$calendarEvent['calendarEventId']."'");
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
                                $instanceCalendarEventForPush = $calendarEvent;
                                $instanceCalendarEventForPush['instanceDate'] = new DateTime($exception['startDateTime']);
                                $instanceCalendarEventForPush['instanceDate'] = $instanceCalendarEventForPush['instanceDate']->format('Y-m-d');

                                $instanceCalendarEventForPush['isCompleted'] = false;
                                $instanceCalendarEventForPush['isCancelled'] = false;
                                $instanceCalendarEventForPush['isRescheduled'] = false;

                                $instanceCalendarEventForPush['name'] = $exception['name'];
                                $instanceCalendarEventForPush['description'] = $exception['description'];
                                $instanceCalendarEventForPush['privateNotes'] = $exception['privateNotes'];
                                $instanceCalendarEventForPush['price'] = $exception['price'];
                                $instanceCalendarEventForPush['estHours'] = $exception['estHours'];
                                $instanceCalendarEventForPush['isPrepaid'] = $exception['isPrepaid'];
                                $instanceCalendarEventForPush['startDateTime'] = $exception['startDateTime'];
                                $instanceCalendarEventForPush['endDateTime'] = $exception['endDateTime'];

                                if ($exception['isCompleted']) {
                                    $instanceCalendarEventForPush['isCompleted'] = true;
                                    $instanceCalendarEventForPush['completedCalendarEventId'] = $exception['linkedToCompletedCalendarEventId'];
                                }

                                if ($exception['isCancelled']) {
                                    $instanceCalendarEventForPush['isCancelled'] = true;
                                }

                                if ($exception['isRescheduled']) {
                                    $instanceCalendarEventForPush['isRescheduled'] = true;
                                }
                                
                                array_push($instances, $instanceCalendarEventForPush);
                            }
                        }

                        if (!$foundException) {
                            $calendarEvent['instanceDate'] = $date;
                            $calendarEvent['isCompleted'] = false;
                            $calendarEvent['isCancelled'] = false;
                            $calendarEvent['isRescheduled'] = false;
                            array_push($instances, $calendarEvent);
                        }
                    }
                }
            }

            return $instances;
            
		}

?>
