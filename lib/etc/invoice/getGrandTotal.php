<?php

    // Get a full list of jobs within a date span (start and end dates are included) (overdue jobs do not have to be within the date span!)

		function getGrandTotal($invoiceId) {
			
            require_once dirname(__DIR__).'../../table/invoice.php';
            require_once dirname(__DIR__).'../../table/invoiceItem.php';

            // Check if the invoice exists
            $invoice = new invoice($invoiceId);
            if (!$invoice->existed) {
                return false;
            }

            // Get items
            $invoice->pullItems();

            if (count($invoice->items) < 1) {
                return 0;
            }

            // For each item, add to the total
            $runningTotal = 0;
            foreach ($invoice->items as $itemId) {
                $item = new invoiceItem($itemId);
                if ($item->existed) {
                    $runningTotal = $runningTotal + (float)( ($item->price * $item->quantity) + ( ($item->price * $item->quantity) * ($item->tax / 100) ) );
                }
            }

            // Discount
            if ((string)$invoice->discountIsPercent == '1') {
                return $runningTotal - ($runningTotal * ($invoice->discount/100) );
            } else {
                return $runningTotal - $invoice->discount;
            }
            
		}

?>
