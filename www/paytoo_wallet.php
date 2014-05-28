<?php
class PaytooAccountType {
	var $user_id = null;
	var $wallet = null;
	var $currency = null;
	var $balance = null;
	var $registered_phone = null;
	var $sim_phonenumber = null;
	var $prepaidcard = null;
	var $email = null;
	var $password = null;
	var $gender = null;
	var $firstname = null;
	var $middlename = null;
	var $lastname = null;
	var $address = null;
	var $city = null;
	var $zipcode = null;
	var $country = null;
	var $state = null;
	var $phone = null;
	var $birthday = null;
	var $security_code = null;
	var $question1 = null;
	var $answer1 = null;
	var $question2 = null;
	var $answer2 = null;
	var $question3 = null;
	var $answer3 = null;
	var $citizenship = null;
	var $id_type = null;
	var $id_issued_by_country = null;
	var $id_issued_by_state = null;
	var $id_number = null;
	var $id_expiration = null;
	var $ssn_number = null;
	var $max_pin = null;
	var $dist_id = null;
	var $res_id = null;
	var $pos_id = null;
	var $document1 = null;
	var $document2 = null;
	var $document3 = null;
	var $custom_field1 = null;
	var $custom_field2 = null;
	var $custom_field3 = null;
	var $custom_field4 = null;
	var $custom_field5 = null;
	var $level = null;
}
class PaytooCreditCardType {
	var $cc_id = null;
	var $cc_type = null;
	var $cc_currency = null;
	var $cc_holder_name = null;
	var $cc_number = null;
	var $cc_month = null;
	var $cc_year = null;
	var $cc_cvv = null;
	var $cc_default = null;
	var $cc_status = null;
	var $cc_status_infos = null;
	var $cc_creation = null;
	var $cc_lastupdate = null;
	var $cc_track_1 = null;
	var $cc_track_2 = null;
	var $cc_track_3 = null;
}

try {
	ini_set ( 'soap.wsdl_cache_enabled', 0 );
	$soap = new SoapClient ( "https://go.paytoo.info/api/merchant/", array (
			"classmap" => array (
					"PaytooAccountType" => "PaytooAccountType",
					"PaytooCreditCardType" => "PaytooCreditCardType" 
			) 
	) );
	$merchant_id = 97383913; // Your merchant ID
	$api_password = 'testing'; // Your API Password

	
	echo "Processing	Wallet	Sale\n";
	$response = $soap->SingleTransaction ( '01109123', 123456, 13, 'USD', '1234', 'Order	1234' );
	if ($response->status == 'PENDING') {
		echo "Step	2a:	Transaction	accepted	but	it	must	be	confirmed\n";
		echo "Request	ID:	" . $response->request_id . "\n";
		$request_id = $response->request_id;
		// OTP is always 888888 on sandbox
		$response2 = $soap->ConfirmTransaction ( $request_id, 888888 );
		if ($response2->status == 'OK') {
			echo "Finish:	transaction	has	been	processed\n";
			echo "Tr.	ID:	" . $response2->tr_id . "\n";
		} else {
			echo "ConfirmTransaction	error:	" . $response2->status . "	-	" . $response2->msg . "\n";
		}
	} elseif ($response->status == 'OK') {
		echo "Finish:	transaction	has	been	processed\n";
		echo "Tr.	ID:	" . $response->tr_id . "\n";
	} else {
		echo "SingleTransaction	error:	" . $response->status . "	-	" . $response->msg . "\n";
	}
} catch ( Exception $e ) {
	var_export ( $e );
}
?>