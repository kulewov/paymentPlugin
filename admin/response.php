<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require_once(__DIR__ . '/../../../../wp-load.php');
require plugin_dir_path(__FILE__) . '../includes/paypal-config.php';

if (empty($_GET['paymentId']) || empty($_GET['PayerID'])) {
    throw new Exception('The response is missing the paymentId and PayerID');
}

$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);

$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);

try {
    // Take the payment
    $payment->execute($execution, $apiContext);

    try {

        $payment = Payment::get($paymentId, $apiContext);

        $data = [
            'transaction_id' => $payment->getId(),
            'payment_amount' => $payment->transactions[0]->amount->total,
            'payment_status' => $payment->getState(),
            'invoice_id' => $payment->transactions[0]->invoice_number
        ];
        if ($data !== null && $data['payment_status'] === 'approved') {
            update_post_meta($payment->transactions[0]->invoice_number, 'number_payment', $payment->getId());
            update_post_meta($payment->transactions[0]->invoice_number, 'status', $payment->getState());
            header('location:' . get_site_url() . '/payment_success');
            exit(1);
        } else {
            update_post_meta($payment->transactions[0]->invoice_number, 'number_payment', $payment->getId());
            update_post_meta($payment->transactions[0]->invoice_number, 'status', $payment->getState());
            header('location:' . get_site_url() . '/payment_fail');
        }

    } catch (Exception $e) {
        // Failed to retrieve payment from PayPal

    }

} catch (Exception $e) {
    // Failed to take payment

}
