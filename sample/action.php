<?php
// sample_backend.php
// Created by RAIDATech
// Author Sean H. Worthington 8/22/2020
// Demo to confirm that a customer has sent enough CloudCoins to your SkyVault.
// Sample Call:
// http://localhost/pos/action.php?amount=5&coupon=23423&guid=40bb8c0d150f3fb3ca2265fdf7bf730d&sn=11&merchant_skyvault=sean.cloudcoin.global&customer_skyvault=sean4.skyvault.cc

//See the variables that were sent to the page
foreach($_GET as $key => $value){
  echo "<b>".$key . " :</b> " . $value . "<br>";
}

/* 1. LOAD THE 'GET' VARIABLES   */

	$myvault = $_GET['merchant_skyvault'];
	$amount_due = $_GET['amount'];
	$receipt = $_GET['guid'];

	//Validate inputs (see helper functions below)

	
/* 2. Check that this order has not been processed before */
	//Simulated call to DB
	//$past_order_count = SELECT COUNT(*) FROM orders WHERE order_id = $receipt_guid
    	$past_order_count = 0;
	if( $past_order_count > 0 ) {
		echo("This order has already been processed.");
	}

 
/* 3. Parse Merchant's data  */ 
	
	// Simulating that merchant data is for one product. Checking DB for price. 
	// SELECT price FROM products WHERE product_name ='$merchantData';
	// $total_due = $row['price'];
	$total_due = 5; 
	
	
	/* 4. Call raida_go program to see how many CloudCoins were sent to your SkyVault */
	$path="E:/Documents/pos/raida_go.exe";
	if (!file_exists($path))
		die("Raida_go doesn't exist");

	if (!is_executable($path))
		die("Raida go is not executable for webserver user");
 
	//$command = "./raida_go receive $receipt_guid"; //This is for Linux. 
	$command = "$path view_receipt $receipt $myvault"; //This is for Windows 
	
	echo "<br><b>The command:</b> $command<br>";
	
	$json_obj = exec($command, $outarray, $error_code); 

	echo "<br><b>The error code:</b> $error_code<br>";//zero means no error
	
	if( $error_code > 0){
		die("<br><b>ERROR:</b> ".$error_code);
	}

	echo "<br><b>The response:</b> <code>$json_obj</code><br>";
	// {"amount_verified":100,"status":"success","message":"CloudCoins verified"}
	
	$arr = json_decode($json_obj, true);
	
	$amount_verified = intval( $arr["amount_verified"] );
	
	echo "<br><b>The amount verfified:</b> $amount_verified.<br>";
	

/* 5. Decisions based on amount verified */

	if( $amount_verified == $total_due ){
		
		echo("<br>Thank you for your payment. Your order is being processed");
		/* Process Payment */
		// INSERT INTO orders ( order_id, product, amount, customer) 
		// VALUES ( $recrupt_guid, $merchantData, $total_due, $received_from );
		die();
	
	}// end if no CloudCoins were received

	if( $amount_verified == 0 ){
		// This should not happen
		die("<br>No CloudCoins Were Received. Please check your SkyVault's balance to see if you have funds.");
	}// end if no CloudCoins were received

	if( $amount_verified > $total_due ){
		// The customer sent too much money. The modal should stop this from happening. 
		echo( "<br>Thank you for your purchase. You sent us more CloudCoins than were owed. We have credited your account.");
		// or
		// echo( "Thank you for your purchase. You have sent your change to your SkyVault Account.");
		// $command = "./raida_go send $received_from $change_due";//Linux. Different for Windows
		// $exec($command); 
		die();
	}
	
	if( $amount_verified < $total_due ){
		//The modal should stop this from happening
		echo( "<br>$amount_due was needed but we only received $amount_verified. We have credited your account.");
		// or
		// echo( "$amount_due was needed but we only received $amount_verified. We have returned your payment.");
		// $command = "./raida_go send $received_from $change_due";//Linux. Different for Windows
		// $exec($command); 
		die();
	}
		
	die();

/* Helper Functions */

	function isValidSkyVault( $received_from ){
		//A skyvault address should have three parts like this: billy.mydomain.com
		$pattern = "(?=^.{1,254}$)(^(?:(?!\d+\.)[a-zA-Z0-9_\-]{1,63}\.?)+(?:[a-zA-Z]{2,})$)";
		return preg_match($pattern, $received_from );
	}//end validate skyvault address
	
	function isValidGUID(string $hexStr){
		$length = 32;
		if( strlen($hexStr) === $length && ctype_xdigit($hexStr)){ 
			return true;
		}else{ 
			return false;
		} 
	}//end is valide hex

?>
