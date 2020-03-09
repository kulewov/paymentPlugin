<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
require_once( plugin_dir_path( __FILE__ ) . '../../../../wp-load.php' );
require plugin_dir_path(__FILE__).'paypal/autoload.php';

$enableSandbox = false;

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.

$paypalConfig = [
    'client_id' => get_option('option_paypal_id'),
    'client_secret' => get_option('option_paypal_secret_code'),
                    //http://{$_SERVER['HTTP_HOST']}
    'return_url' => plugins_url('../admin/response.php',__FILE__),
    'cancel_url' => get_site_url().'/payment_fail'
];



$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

 /*
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
 
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => 'sandbox'
    ]);

    return $apiContext;
  //
}  
