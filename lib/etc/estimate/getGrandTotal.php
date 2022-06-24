<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getGrandTotal($estimateId) {
			
            require_once dirname(__DIR__).'../../table/estimate.php';
            require_once dirname(__DIR__).'../../table/estimateItem.php';

            // Check if the estimate exists
            $estimate = new estimate($estimateId);
            if (!$estimate->existed) {
                return false;
            }

            // Get items
            $estimate->pullItems();

            if (count($estimate->items) < 1) {
                return 0;
            }

            // For each item, add to the total
            $runningTotal = 0;
            foreach ($estimate->items as $itemId) {
                $item = new estimateItem($itemId);
                if ($item->existed) {
                    $runningTotal = $runningTotal + (float)( ($item->price * $item->quantity) + ( ($item->price * $item->quantity) * ($item->tax / 100) ) );
                }
            }

            // Discount
            if ((string)$estimate->discountIsPercent == '1') {
                return $runningTotal - ($runningTotal * ($estimate->discount/100) );
            } else {
                return $runningTotal - $estimate->discount;
            }
            
		}

?>
