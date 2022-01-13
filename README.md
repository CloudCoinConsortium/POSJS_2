# POS_JS_2
Second version of Point of Service for the new coin

In order to use POS JS library you need to instantiate POSJS2 Object
The widget is opened by the show() method of the object

Example:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://skyvault.cc/assets/pos/posjs2.min.v001.js" ></script>
        <script>
            var pos = new POSJS2({
                'timeout':'5000', 
                'action': 'https://e12.miroch.ru/backend.php', 
                'merchant_skyvault':'Your.skyvault.cc'
            })
            var get_parameters = {
                'amount' : 100,
                'guid' : 'c3f03109ee0845ab8719abcfe444173a', // GUID customized by you but must be a String. 
                'sn' : '1554887' // Merchant Variable customized by you but must be a String. 
            }
        </script>
    </head>
    <body>
        <div><button onclick="pos.show(get_parameters)">PAY WITH CLOUDCOIN</button></div>
    </body>
</html>

```
Another Example:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://skyvault.cc/assets/pos/posjs2.min.v001.js"></script>
            
        <script>
            var pos = new POSJS2({
                'timeout':'5000',
                'action': 'https://yourdomain.com/your_action_page.php', 
                'merchant_skyvault' : 'Your.skyvault.cc'
            })
            var get_parameters = {}; // Create a get_parameters object to hold GET parameters for your action page.
            get_parameters.amount = 100;  // Mandatory GET variable specifies the amount of CloudCoins to be paid.                        
            get_parameters.customerID = '1554887'; // Optional Merchant Variable customized by you but must be a String. 
        </script>
    </head>
    <body>
        <div>
            <img onclick='pos.show(get_parameters)' src='https://skyvault.cc/assets/pos/cc.png' width='100' alt='Pay with Cloud Coin'>
        </div>
    </body>
</html>
```

More Thurough Example
```html
<!--
	SKYVAULT MODAL Version 0.
	This code will place a Pay with Cloudcoin button on your page.When you click this 
	button, a modal will popup. The cusomter will enter their payment info. 
	Then the modal will transfer funds from their account to yours. Then the customer
	will be redirected to a URL like this:
	https://yourdomain.com/your_action_page.php?amount=100&senders_skyvault=jd.skyvault.cc&
	guid=4aba6d7e9df04e7395bb4c941a594b70&your_parameter_1=something&your_parameter-2=something
	
	
	INSTRUCTION STEPS:
	1. Customize the POSJS2 constructor. 
		Please set the four fields to your needs. 
		
		Timeout: The number of microsecond that your web page will wait as it 
			attempts to transfer cloudcoins from the sender to the receiver. 
			If your users have slow Internet, this should be increased. 
			Usually, five seconds if fine. 
			
		action: The url that the GET variables will be sent to. 
		
		merchant_skyvault: Your skyvault that you want to receive the senders funds in. 
	
	2. When the user adds something to their basket or there basket changes, call the 
		setAmountDue() function. An alternative would be to add a line within the 
		"openModal()" function so that the total due is set.
		
	3. Add any fields that you want to have sent to your action page. You must put an 
		action page on your server. You can add GET parameters by just adding fields to 
		the get_parameters object and giving that field a value. 
		Like this: get_parameter.firstName = "Bob";
	
	4. Put any tests that you want done in the "openModal()" function. Here, you could also
		call the setAmountDue() and setMerchantVaribles()0. 
	
	
	5. After the user enters data into the Modal and clicks Pay, they will be redirected
		to your action page along with all the GET variables. That page will then
		check with the RAIDA to ensure that you have received the sender's funds. 
		See our sample php page for help with this.
		
		Need help? Skype Sean Worthington Skype ID sean.worthington or Sean_Worthington@hotmail.com
	-->

<!DOCTYPE html>
<html lang="en">
    <head>
	<!-- This script points to version "v0". There maybe newer versions like "v1". -->
    <script src="https://skyvault.cc/assets/pos/posjs2.min.v001.js" ></script>
		
	<script name="Skyvault Modal">
		
			//Create an object to hold the GET parameters that will be sent to the action page.			
			var get_parameters = {};
			
			//Optional. Helps track if the amount of cloudcoins due has been set yet befor the modal is called. 
			var amountSet = false; 
			
			//Helps you track if all of your custom variables have been set before the modal is called. 
			var merchantVariablesSet = true; 
			
			var pos = new POSJS2({
					'timeout':'20000', //Customize these URLs
					'action': 'https://yourdomain.com/your_action_page.php', 
					'merchant_skyvault' : 'sean.cloudcoin.global'
				})
			
			
			/* You must set the amount parameter before opening the Modal.
				This fucntion helps you set the amount. */
			function setAmountDue( amount_due ){
				get_parameters.amount = amount_due; //Adds a field called amount and gives it a value. 
				amountSet = true;
			}
			
			/* This is an optional function. Call this before you call openModal 
				(if you have your own variables).  Or these can be created within the openModal()
				function. Delete it if unneeded  */
			function setMerchantVariables( customerID, phone, other){
				//Add as many variables as you want. These are yours to customize. 
				//All of these will become GET parameters and passed onto your 
				// Action page. Just change the key and the value. 
				//get_paramters.key = value;
				get_parameters.customerID = customerID; //Demonstrates how to add a customer_id GET parameter.  
				get_parameters.phonenumber = phone;//Another custom field. 
				get_parameters.merchantData = other;//Another custom field. 
				merchantVariablesSet = true;
				
			}
			
			// Perform any validation that you want done before the user sends payment. 
			function openModal(){
				setAmountDue(100);
				setMerchantVariables();
				if( amountSet && merchantVariablesSet){
					pos.show(get_parameters);// This actually opens the modal.
				}
			}
		
		</script>
    </head>
    <body>
		<div name="Skyvault Modal">
			<img id="cc" onclick="openModal()" src="https://skyvault.cc/assets/pos/cc.png" 
		onmouseover="cc.src='https://skyvault.cc/assets/pos/hover.png'" onmouseout="cc.src='https://skyvault.cc/assets/pos/cc.png'" width="100">
		</div>
    </body>
</html>

```

## POSJS2 configuration

```js
{
'timeout' : Number // Timeout for calling RAIDA servers
'action : String // URL to send results
'merchant_skyvault' // The merchant's skyvault that will receive payments such as 'payments.domain.com'
}
```


## Data structure
```js
{
'amount' : Number // Amount of Cloudcoins the customer should send you. This is manditory and must be included. 
'param1' : String // Any GET parameter that you want to create and have sent to  your action page.
'param2' : String // You can create all the GET parameters you like. 
```
