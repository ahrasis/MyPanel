<?php

require 'soap_config.php';


$client = new SoapClient(null, array('location' => $soap_location,
		'uri'      => $soap_uri,
		'trace' => 1,
		'exceptions' => 1));


try {
	if($session_id = $client->login($username, $password)) {
		echo 'Logged successfull. Session ID:'.$session_id.'<br />';
	}

	//* Set the function parameters.
	$mailuser_id = 1;

	// Lookup by primary key, gets back a single record.
	$mail_user = $client->mail_user_get($session_id, $mailuser_id);
	print_r($mail_user);

	// Lookup by pattern gives an array of records.
	$mail_users_array = $client->mail_user_get($session_id, array('email' => '%@example.com'));
	print_r($mail_user_array);

	if($client->logout($session_id)) {
		echo 'Logged out.<br />';
	}


} catch (SoapFault $e) {
	echo $client->__getLastResponse();
	die('SOAP Error: '.$e->getMessage());
}

?>
