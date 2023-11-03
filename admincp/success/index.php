<?php 
$pp_hostname = "www.sandbox.paypal.com"; // Change to www.sandbox.paypal.com to test against sandbox


// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';
 
$tx_token = $_GET['tx'];
//$auth_token = "X8s6FinHqzxxWXRIYELbCtGM60nyyCI5b_hXBRJsYMQxSPzLH6_bBR2_R48";
// W2KDnc6ARCRy0MfYqN25VicSymHhqSyR-LTD_BFcqKQAabfTUypWSl95u1q
$auth_token = "6_ia0vJ1fPnBVUumkJFA5iEiRXHAc3k9dBET1eLfkWqDcJIgEZAZcyzSWpm";
$req .= "&tx=$tx_token&at=$auth_token";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
//if your server does not bundled with default verisign certificates.
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
$res = curl_exec($ch);
curl_close($ch);

if(!$res){
    echo "error";
}else{
     // parse the data
    $lines = explode("\n", trim($res));
    $keyarray = array();
    if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i = 1; $i < count($lines); $i++) {
            $temp = explode("=", $lines[$i],2);
            $keyarray[urldecode($temp[0])] = urldecode($temp[1]);
        }
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
    $status = $keyarray['payment_status'];// ( == 'Completed')
    $status_pending = $keyarray['pending_reason'];
    $tnx_id = $keyarray['txn_id'];  // check if model doesn't have any entry with this one
    var_dump($keyarray);
    $firstname = $keyarray['first_name'];
    $lastname = $keyarray['last_name'];
    $itemname = $keyarray['item_name'];
    $email = $keyarray['payer_email'];
    echo ("<p><h3>Thank you for your purchase!</h3></p>");
     
    echo ("<b>Payment Details</b><br>\n");
    echo ("<li>Name: $firstname $lastname</li>\n");
    echo ("<li>Item: $itemname</li>\n");
    echo ("<li>Email: $email</li>\n");
    echo ("<li>Status: $status</li>\n");
    if( $status == 'Pending' )
        echo ("<li>Pending reason: $status_pending</li>\n");
    }
    else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
    }
}
?>