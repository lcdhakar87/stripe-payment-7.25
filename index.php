<?php 
require_once('init.php');
 
 
$params = array(
	"testmode"   => "on",
	"private_live_key" => "sk_live_xxxxxxxxxxxxxxxxxxxxx",
	"public_live_key"  => "pk_live_xxxxxxxxxxxxxxxxxxxxx",
	//"private_test_key" => "sk_test_h1p2gUgSEcy8PSlyb6m0tplr",
	//"public_test_key"  => "pk_test_xWRBfEzKEhf80H60m0krqAdJ",
    "private_test_key" => "sk_test_BVlVCWU4Ba2NwpJNUH05Vep7",
	"public_test_key"  => "pk_test_n7UAl7RFk2i8jV2YILSnOSNZ"
);  



if(isset($_POST['stripeToken']))
{  echo "<pre>";
   print_r($_POST); 
           
        if ($params['testmode'] == "on") {
        	\Stripe\Stripe::setApiKey($params['private_test_key']);
        	$pubkey = $params['public_test_key'];
        } else {
        	\Stripe\Stripe::setApiKey($params['private_live_key']);
        	$pubkey = $params['public_live_key'];
        }
    echo "<br><br>Balance :================================================================================== <br>";
       $balance = \Stripe\Balance::retrieve();	
			
        print_r($balance);  
        
     echo "<br><br>Charge :================================================================================== <br>";
        
        // Token is created using Stripe Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create([
          'amount' => 999,
          'currency' => 'usd',
          'description' => 'Example charge',
          'source' => $token,
        ]);
            print_r($charge);
         

    echo "<br><br>BalanceTransaction :================================================================================== <br>";

     $trnsfer =  \Stripe\BalanceTransaction::retrieve(
                      $charge->balance_transaction
                    );
        print_r($trnsfer);
die;
}
?>


<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="https://js.stripe.com/v3/"></script>
<title>Stripe Pay Custom Integration Demo</title>
</head>
<body>
<link href="style.css" type="text/css" rel="stylesheet" />
<h1 class="bt_title">Stripe Pay Demo</h1>
<div class="dropin-page">
    
 <form action="" method="post" id="payment-form">
  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display Element errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

  <button>Submit Payment</button>
</form>
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

<!-- TO DO : Place below JS code in js file and include that JS file -->
<script type="text/javascript">
    var stripe = Stripe('<?php echo $params['public_test_key']; ?>');
    var elements = stripe.elements();
    var form = $('#payment-form');
    var style = {
          base: { 
            fontSize: '16px',
            color: '#32325d',
          },
        };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});
    
    // Add an instance of the card Element into the `card-element` <div>.
      card.mount('#card-element');
    
      var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
          event.preventDefault();
        
          stripe.createToken(card).then(function(result) {
            if (result.error) {
              // Inform the customer that there was an error.
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the token to your server.
              stripeTokenHandler(result.token);
            }
          });
        });
    
     
  function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
    
      // Submit the form
      form.submit();
}

 
</script>
</body>
</html>

