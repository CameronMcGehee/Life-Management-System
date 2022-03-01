<?php

    function getRecurringDates(string $startingDate, string $endingDate, string $int, int $freq) {
        switch ($int) {
            case 'day':
                $result[] = $startingDate;
                if($endingDate) {
                    $startingTime = new DateTime($startingDate);
                    $continueDate = new DateTime($endingDate);

                    $days = $continueDate->diff($startingTime)->format('%a');
                    $counter = 1;
                    while($counter <= $days) {
                        $nextStartingDate = date('Y-m-d', strtotime("+".($counter * $freq)." day", strtotime($startingDate)));
                        $result[] = $nextStartingDate;
                        $counter++;
                    }
                }
                return $result;
                break;
            case 'week':
                $result[] = $startingDate;
                if($endingDate) {
                    $startingTime = new DateTime($startingDate);
                    $continueDate = new DateTime($endingDate);

                    $weeks = ceil($continueDate->diff($startingTime)->format('%a') / 7);
                    $counter = 1;
                    while($counter <= $weeks) {
                        $nextStartingDate = date('Y-m-d', strtotime("+".($counter * $freq)." week", strtotime($startingDate)));
                        $result[] = $nextStartingDate;
                        $counter++;
                    }
                }
                return $result;
                break;
            case 'month':
                $result[] = $startingDate;
                if($endingDate) {
                    $startingTime = new DateTime($startingDate);
                    $continueDate = new DateTime($endingDate);

                    $months= $continueDate->diff($startingTime)->m;
                    $counter = 1;
                    while($counter <= $months) {
                        $nextStartingDate = date('Y-m-d', strtotime("+".($counter * $freq)." month", strtotime($startingDate)));
                        $result[] = $nextStartingDate;
                        $counter++;
                    }
                }
                return $result;
                break;
            case 'year':
                $result[] = $startingDate;
                if($endingDate) {
                    $startingTime = new DateTime($startingDate);
                    $continueDate = new DateTime($endingDate);

                    $years= $continueDate->diff($startingTime)->y;
                    $counter = 1;
                    while($counter <= $years) {
                        $nextStartingDate = date('Y-m-d', strtotime("+".($counter * $freq)." year", strtotime($startingDate)));
                        $result[] = $nextStartingDate;
                        $counter++;
                    }
                }
                return $result;
                break;
        }
    }

?>
