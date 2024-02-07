<?php

    // Get a full list of calendarEvents within a date span (start and end dates are included) (overdue calendarEvents do not have to be within the date span!)

		function getPaymentTotal($invoiceId) {
			
            require_once dirname(__DIR__).'../../table/invoice.php';
            require_once dirname(__DIR__).'../../table/payment.php';

            // Check if the invoice exists
            $invoice = new invoice($invoiceId);
            if (!$invoice->existed) {
                return false;
            }

            // Get payments
            $invoice->pullPayments();

            if (count($invoice->payments) < 1) {
                return 0;
            }

            // For each payment, add to the total
            $runningTotal = 0;
            foreach ($invoice->payments as $paymentId) {
                $payment = new payment($paymentId);
                if ($payment->existed) {
                    $runningTotal = $runningTotal + ($payment->amount);
                }
            }

            return $runningTotal;
            
		}

?>
