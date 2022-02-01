<?php

    function getDateTimDiff($date1,$date2) {
        $diff=array();

        $first = strtotime($date1);
        $second = strtotime($date2);
        $dateDiff = abs($first - $second);
        $diff['s'] = floor($dateDiff); //second
        $diff['m'] = floor($dateDiff/(60)); //minute
        $diff['h'] = floor($dateDiff/(60*60)); //hour
        $diff['d'] = floor($dateDiff/(60*60*24)); //day 
        $diff['M'] = floor($dateDiff/(60*60*24*30)); //Months
        $diff['y'] = floor($dateDiff/(60*60*24*30*365)); //year

        return $diff;
    }

    function getDateTimeDiffString ($date1,$date2) {
        $difOutput = '';
        $currentTime = date('Y-m-d H:i:s');
        $dif = getDateTimDiff($date1,$date2);
        if ($dif['y'] > 0) {
            $difOutput = $dif['y'].'y';
        } elseif ($dif['M'] > 0) {
            $difOutput = $dif['M'].'mo';
        } elseif ($dif['d'] > 0) {
            $difOutput = $dif['d'].'d';
        } elseif ($dif['h'] > 0) {
            $difOutput = $dif['h'].'h';
        } elseif ($dif['m'] > 0) {
            $difOutput = $dif['m'].'min';
        } elseif ($dif['s'] > 0) {
            $difOutput = $dif['s'].'s';
        }

        return $difOutput;
    }

?>