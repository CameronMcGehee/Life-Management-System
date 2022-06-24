<?php

	function decryptString(string $data, $key) {
		$encryption_key = base64_decode($key);
		list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

?>
