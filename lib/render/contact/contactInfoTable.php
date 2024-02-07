<?php

    require_once dirname(__FILE__)."/../render.php";

    class contactTable extends render {

        private contact $currentContact; // For storing the object of the workspace

        function __construct($contactId) {

            parent::__construct();

            require_once dirname(__FILE__)."/../../table/contact.php";
            require_once dirname(__FILE__)."/../../table/contactEmailAddress.php";
            require_once dirname(__FILE__)."/../../table/contactPhoneNumber.php";
            
            $this->currentContact = new contact($contactId);
        }

        function render() {
            $this->output = '';
        }

    }

?>
