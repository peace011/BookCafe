<?php
require_once('vendor/autoload.php'); // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51OI6ZrGnOza8XShbn65fS9IqEKoy0RDqN3Rcy1ToHfFggHIQmmvRuQypXbaA1E0D5SYZgXR3cnBQ1buEq21wrhyZ00tShFgZgJ'); // Replace with your actual secret key

if (isset($_POST['stripeToken'])) {
    $token = $_POST['stripeToken']; // Make sure this matches the name attribute of your Stripe.js token input field
    $amount = $_POST['stripeAmount'];

    try {
        // Create a charge using the Stripe API
        $charge = \Stripe\Charge::create([
            'amount' => $amount, // Amount in cents
            'currency' => 'usd',
            'source' => $token,
            'description' => 'Total Booking charge',
        ]);

        // Payment success logic
        echo "Payment successful!";
    } catch (\Stripe\Exception\CardException $e) {
        // Card was declined
        echo $e->getError()->message;
    } catch (\Stripe\Exception\RateLimitException $e) {
        // Too many requests made to the API too quickly
        echo $e->getError()->message;
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        // Invalid parameters were supplied to Stripe's API
        echo $e->getError()->message;
    } catch (\Stripe\Exception\AuthenticationException $e) {
        // Authentication with Stripe's API failed
        echo $e->getError()->message;
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        // Network communication with Stripe failed
        echo $e->getError()->message;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Generic error
        echo $e->getError()->message;
    }
} else {
    echo "Error: Stripe token is missing.";
}

// Log to error log
error_log(print_r($_POST, true));

// Print to browser (for debugging)
print_r($_POST);
?>
