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
	$soap = new SoapClient ( "https://merchant.paytoo.info/api/merchant/?wsdl", array (
			"classmap" => array (
					"PaytooAccountType" => "PaytooAccountType",
					"PaytooCreditCardType" => "PaytooCreditCardType" 
			) 
	) );
	$merchant_id = 'xxxxx'; // Your merchant ID
	$api_password = 'xxxx'; // Your API Password

	$response = $soap->auth ( $merchant_id, $api_password );
	if ($response->status == 'OK') {
		echo "Connected\n";
		
		$CreditCard = new PaytooCreditCardType ();
		$CreditCard->cc_type = "VISA"; // mandatory
		$CreditCard->cc_holder_name = "DEMO	USER"; // mandatory
		$CreditCard->cc_number = "4444333322221111"; // mandatory
		$CreditCard->cc_cvv = "123"; // mandatory
		$CreditCard->cc_month = "12"; // mandatory
		$CreditCard->cc_year = "14"; // mandatory
		$Customer = new PaytooAccountType ();
		$Customer->email = "testing@user.com"; // mandatory
		$Customer->firstname = "Demo"; // mandatory
		$Customer->lastname = "User"; // mandatory
		$Customer->address = "200	SW	1st	Avenue";
		$Customer->city = "Fort	Lauderdale";
		$Customer->zipcode = "33301";
		$Customer->state = "FL";
		$Customer->country = "US";
		$amount = 6.00; // mandatory
		$currency = 'USD'; // mandatory
		
		echo "Processing	Credit	Card	Sale\n";
		$ref_id = rand ( 1000, 9999 ); // mandatory
		$description = "Order	#" . $ref_id . "	with	Paytoo	Merchant";
		$addinfo = "";
		$response = $soap->CreditCardSingleTransaction ( $CreditCard, $Customer, $amount, $currency, $ref_id, $description );
		if ($response->status == 'OK') {
			echo "Transaction	has	been	processed\n";
			echo "Request	ID:	" . $response->request_id . "\n";
			echo "Tr.	ID:	" . $response->tr_id . "\n";
		} else {
			echo $response->status . "	-	" . $response->msg . "\n";
		}
		
		$soap->logout ();
		echo "Logout\n";
	} else {
		echo "Auth	error:	" . $response->status . "	-	" . $response->msg . "\n";
	}
} catch ( Exception $e ) {
	var_export ( $e );
}
?>