<?php

	function generateAuthToken($authName) {
		require_once '../table/authToken.php';
		$token = new authToken();
		$token->authName = $authName;
		$token->set();
	}

?>