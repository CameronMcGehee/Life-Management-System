<?php

    function getWeekOfMonth($date) {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return getWeekOfYear($date) - getWeekOfYear($firstOfMonth) + 1;
    }

    function getWeekOfYear($date) {
        $weekOfYear = intval(date("W", $date));
        if (date('n', $date) == "1" && $weekOfYear > 51) {
            // It's the last week of the previos year.
            return 0;
        }
        else if (date('n', $date) == "12" && $weekOfYear == 1) {
            // It's the first week of the next year.
            return 53;
        }
        else {
            // It's a "normal" week.
            return $weekOfYear;
        }
    }

?>
