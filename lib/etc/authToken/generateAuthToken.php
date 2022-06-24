<?php

	function generateAuthToken($authName) {
		require_once dirname(__DIR__)..'../table/authToken.php';
		$token = new authToken();
		$token->authName = $authName;
		$token->set();
	}

?>