<pre>
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
    var $max_pin = null;
    var $dist_id = null;
    var $res_id = null;
    var $document1 = null;
    var $document2 = null;
    var $document3 = null;
    var $custom_field1 = null;
    var $custom_field2 = null;
    var $custom_field3 = null;
    var $custom_field4 = null;
    var $custom_field5 = null;
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
    ini_set('soap.wsdl_cache_enabled', 0);
    $soap = new SoapClient("https://merchant.paytoo.info/api/merchant/?wsdl",array("classmap"=>array("PaytooAccountType"=>"PaytooAccountType","PaytooCreditCardType"=>"PaytooCreditCardType")));
    var_dump(array("classmap"=>array("PaytooAccountType"=>"PaytooAccountType","PaytooCreditCardType"=>"PaytooCreditCardType")));
    $merchant_id = '66395537';
    $api_password = 'pruebatest';
    //"Authentification
    $response = $soap->auth($merchant_id, $api_password);
    if($response->status=='OK') {
        echo "Connected\n";
        
        $CreditCard = new PaytooCreditCardType ();
        $CreditCard->cc_type = "VISA"; //"mandatory
        $CreditCard->cc_holder_name = "DEMO USER"; //"mandatory
        $CreditCard->cc_number = "4444333322221111"; //"mandatory
        $CreditCard->cc_cvv = "123"; //"mandatory
        $CreditCard->cc_month = "12"; //"mandatory
        $CreditCard->cc_year = "14"; //"mandatory
        
        
        $Customer = new PaytooAccountType ();
        $Customer->email = "warneson@gmail.com "; //"mandatory
        $Customer->firstname = "Demo"; //"mandatory
        $Customer->lastname = "User"; //"mandatory
        $Customer->address = "200 SW 1st Avenue";
        $Customer->city = "Fort Lauderdale";
        $Customer->zipcode = "33301";
        $Customer->state = "FL";
        $Customer->country = "US";
        $Customer->level="";
        
        var_dump($Customer); 
        $amount = 10.00; //"mandatory
        $currency = 'ARS'; //"mandatory
        
        echo "Processing Credit Card Sale\n";
        $ref_id = rand(1000, 9999); //"mandatory
        $description = "Order #".$ref_id." with Paytoo Merchant";
        $addinfo = "";
        $response = $soap->CreditCardSingleTransaction($CreditCard,$Customer,$amount,$currency,$ref_id,$description);
        
        if ($response->status == 'OK') {
            echo "Transaction has been processed\n";
            echo "Request ID: " . $response->request_id . "\n";
            echo "Tr. ID: " . $response->tr_id . "\n";
        } else {
            echo $response->status . " " . $response->msg . "\n";
        }
        echo "Processing Credit Card PreiAuth\n";
        $ref_id = rand(1000, 9999); //"mandatory
        $description = "Order #".$ref_id." with Paytoo Merchant";
        $addinfo = "";
        $response = $soap->CreditCardPreAuth ( $CreditCard, $Customer, $amount, $currency, $ref_id, $description);
        if ($response->status == 'OK' && $response->request_status == 'accepted') {
            echo "Request has been accepted\n";
            echo "Request ID: " . $response->request_id . "\n";
            $request_id = $response->request_id;
            echo "Capture the request ".$request_id."\n";
            $response = $soap->Settlement($request_id, $amounti2); //"Different"amount
            if ($response->status=='OK') {
                echo "Transaction has been captured\n";
                echo "Tr. ID: " . $response->tr_id . "\n";
                echo "Initial requested amount: ".$response->PaytooRequest->amount."\n";
                echo "Final processed amount: ".$response->PaytooTransaction->tr_amount_transfered."\n";
            } else {
                echo $response->status." ".$response->msg."\n";
            }
        } else {
              echo $response->status . " " . $response->msg . "\n";
        }
        
        echo "Processing Wallet Sale\n";
        $response = $soap->SingleTransaction('08587726', '123456', 13, 'ARS', '1234', 'Order 1234');
        if ($response->status == 'PENDING') {
            echo "Step 2a: Transaction accepted but it must be confirmed\n";
            echo "Request ID: " . $response->request_id . "\n";
            $request_id = $response->request_id;
            //"OTP"is"always"888888"on"sandbox
            $response2 = $soap->ConfirmTransaction($request_id, '888888');
            if ($response2->status == 'OK') {
                echo "Finish: transaction has been processed\n";
                echo "Tr. ID: " . $response2->tr_id . "\n";
            } else {
                echo "ConfirmTransaction error: ".$response2->status . " " . $response2->msg . "\n";
            }
        } elseif ($response->status == 'OK') {
            echo "Finish: transaction has been processed\n";
            echo "Tr. ID: " . $response->tr_id . "\n";
        } else {
            echo "SingleTransaction error: ".$response->status . " " . $response->msg . "\n";
        }
        
        echo "Processing Wallet PreiAuth\n";
        $response = $soap->PreAuth('08587726', '123456', 20, 'ARS', '1234', 'Order 1234');
        if ($response->status == 'PENDING') {
            echo "Step 2a: Transaction accepted but it must be confirmed\n";
            echo "Request ID: " . $response->request_id . "\n";
            $request_id = $response->request_id;
            //"OTP"is"always"888888"on"sandbox
            $response2 = $soap->ConfirmTransaction($request_id, '888888');
            if ($response2->status == 'OK') {
                echo "Step 3a: Transaction has been confirmed, it can be processed\n";
                 $response3 = $soap->Settlement($request_id);
                if ($response3->status == 'OK') {
                    echo "Finish: transaction has been processed\n";
                    echo "Tr. ID: " . $response3->tr_id . "\n";
                } else {
                    echo "Settlement error: ".$response3->status . " i " . $response3->msg . "\n";
                }
            } else {
                echo "ConfirmTransaction error: ".$response2->status . " i " . $response2->msg . "\n";
            }
        } elseif ($response->status == 'OK') {
            echo "Step 2b: Transaction has been accepted, no confirmation required, it can be processed\n";
            $request_id = $response->request_id;
            $response2 = $soap->Settlement($request_id);
            if ($response2->status == 'OK') {
                echo "Finish: transaction has been processed\n";
                echo "Tr. ID: " . $response2->tr_id . "\n";
            } else {
                echo "Settlement error: ".$response2->status . " i " . $response2->msg . "\n";
            }
        } else {
              echo "PreAuth error: ".$response->status . " i " . $response->msg."\n";
        }
        $soap->logout();
        echo "Logout\n";
    }else {
        echo "Auth error: ".$response->status." " .$response->msg."\n";
    }
}catch (Exception $e){
    var_export($e);
}
?>
</pre>
