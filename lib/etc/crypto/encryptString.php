<?php

	function encryptString(string $data, $key) {
		$encryption_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
	}

?>
