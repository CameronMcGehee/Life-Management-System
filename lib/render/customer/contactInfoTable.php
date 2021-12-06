<?php

    require_once dirname(__FILE__)."/../render.php";

    class customerTable extends render {

        private customer $currentCustomer; // For storing the object of the business

        function __construct($customerId) {

            parent::__construct();

            require_once dirname(__FILE__)."/../../table/customer.php";
            require_once dirname(__FILE__)."/../../table/customerEmailAddress.php";
            require_once dirname(__FILE__)."/../../table/customerPhoneNumber.php";
            
            $this->currentCustomer = new customer($customerId);
        }

        function render() {
            $this->output = '';
        }

    }

?>
