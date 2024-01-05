<?php
require_once('vendor/autoload.php');
include('includes/dbconnection.php');

\Stripe\Stripe::setApiKey('sk_test_51OI6ZrGnOza8XShbn65fS9IqEKoy0RDqN3Rcy1ToHfFggHIQmmvRuQypXbaA1E0D5SYZgXR3cnBQ1buEq21wrhyZ00tShFgZgJ');

if (isset($_POST['stripeToken'])) {
    $eid = $_POST['editid'];
    $customerName = $_POST['customer_name'];
    $customerEmail = $_POST['customer_email'];

    $token = $_POST['stripeToken'];
    $amount = $_POST['stripeAmount'];
    $amountInCents = (int) ($amount * 100);

    try {
        // Create a customer
        $customer = \Stripe\Customer::create([
            'email' => $customerEmail,
            'name' => $customerName,
            'source' => $token, // Attach the payment source to the customer
        ]);

        // Create a charge using the customer ID
        $charge = \Stripe\Charge::create([
            'amount' => $amountInCents,
            'currency' => 'NPR',
            'customer' => $customer->id, // Use the customer ID here
            'description' => 'Total Booking charge for ' . $customerName,
            'metadata' => [
                'customer' => $customerName,
            ],
        ]);

        echo "Payment successful!";

        // Include your database update logic here
        // Update the 'payment' field in your database to 1
        $updateSql = "UPDATE tblbooking SET Payment = 1, PaymentAmt = :amount WHERE ID = :eid";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':eid', $eid, PDO::PARAM_STR);
        $updateQuery->bindParam(':amount', $amount, PDO::PARAM_STR);
        $updateQuery->execute();

    } catch (\Stripe\Exception\CardException $e) {
        echo $e->getError()->message;
    } catch (\Stripe\Exception\RateLimitException $e) {
        echo $e->getError()->message;
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        echo $e->getError()->message;
    } catch (\Stripe\Exception\AuthenticationException $e) {
        echo $e->getError()->message;
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        echo $e->getError()->message;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo $e->getError()->message;
    }
} else {
    echo "Error: Stripe token is missing.";
}

error_log(print_r($_POST, true));
print_r($_POST);
?>
