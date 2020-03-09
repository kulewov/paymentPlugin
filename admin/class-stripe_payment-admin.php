<?php

use Stripe\Stripe;
use Stripe\Charge;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       stripe_payment
 * @since      1.0.0
 *
 * @package    Stripe_payment
 * @subpackage Stripe_payment/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Stripe_payment
 * @subpackage Stripe_payment/admin
 * @author     stripe_payment <stripe_payment>
 */
class Stripe_payment_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    function plugin_settings()
    {

        add_settings_section('section_payment', '', '', 'submenu_settings');
        //---------------------Checkboxes--------------------------

        add_settings_field('options_field_PayPal', __('Enable Pay Pal','stripe_payment'), function () {
            ?><input type="checkbox" name="option_PayPal" id="PayPal_checkBox"
                     value="<?= get_option('option_PayPal'); ?>"/><?php
        }, 'submenu_settings', 'section_payment');

        add_settings_field('options_field_Stripe', __('Enable Stripe','stripe_payment'), function () {
            ?><input type="checkbox" name="option_Stripe" id="Stripe_checkBox"
                     value="<?= get_option('option_Stripe'); ?>"/><?php
        }, 'submenu_settings', 'section_payment');

        add_settings_field('options_field_PayPal_email_on', __('Enable payments by email','stripe_payment'), function () {
            ?><input type="checkbox" name="option_PayPal_email_on" id="PayPal_email_on"
                     value="<?= get_option('option_PayPal_email_on'); ?>"/><?php
        }, 'submenu_settings', 'section_payment');


        add_settings_field('options_field_PayPal_sandbox_on', __('Enable sandbox','stripe_payment'), function () {
            ?><input type="checkbox" name="option_paypal_sandbox_on" id="PayPal_sandbox_on"
                     value="<?= get_option('option_paypal_sandbox_on'); ?>"/><?php
        }, 'submenu_settings', 'section_payment');

        //--------------------PayPal fields----------------------
        add_settings_field('options_field_paypal_id', __('Paypal ID','stripe_payment'), function () {
            ?><input type="text" id="paypal_id" name="option_paypal_id"
                     value="<?= get_option('option_paypal_id'); ?>"><?php
        }, 'submenu_settings', 'section_payment');

        add_settings_field('options_field_paypal_secret_code', __('Paypal Secret code','stripe_payment'), function () {
            ?><input type="password" id="paypal_code" name="option_paypal_secret_code"
                     value="<?= get_option('option_paypal_secret_code'); ?>"><?php
        }, 'submenu_settings', 'section_payment');

        add_settings_field('options_field_paypal_email', __('Paypal email','stripe_payment'), function () {
            ?><input type="email" id="paypal_email" name="option_PayPal_email"
                     value="<?= get_option('option_PayPal_email'); ?>"><?php
        }, 'submenu_settings', 'section_payment');


        //-------------------Stripe fields------------------------
        add_settings_field('options_field_stripe_publish_key', __('Stripe Publishable key','stripe_payment'), function () {
            ?><input type="text" id="stripe_p_key" name="option_publish_key"
                     value="<?php echo get_option('option_publish_key'); ?>"><?php
            if (strpos(get_option('option_publish_key'), '_test_') !== false) {
                echo "<br><p style='color:red;position:absolute;'>".__('You are in the test mode','stripe_payment')."</p>";
            }
        }, 'submenu_settings', 'section_payment');
        add_settings_field('options_field_stripe_secret_key', __('Stripe Secret key','stripe_payment'), function () {
            ?><input type="password" id="stripe_s_key" name="option_secret_key"
                     value="<?php echo get_option('option_secret_key'); ?>"><?php
        }, 'submenu_settings', 'section_payment');


        register_setting('option_payment', 'option_paypal_sandbox_on');
        register_setting('option_payment', 'option_PayPal_email_on');

        register_setting('option_payment', 'option_PayPal_email');
        register_setting('option_payment', 'option_paypal_id');
        register_setting('option_payment', 'option_paypal_secret_code');
        register_setting('option_payment', 'option_PayPal');
        register_setting('option_payment', 'option_Stripe');
        register_setting('option_payment', 'option_publish_key');
        register_setting('option_payment', 'option_secret_key');
    }

    function create_post_type()
    {
        $labels = array(
            'menu_name' => 'Stripe Payment'
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => false,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail')
        );
        register_post_type('stripe_payment', $args);
    }

    function stripe_payment_submenu_page()
    {
        add_submenu_page('stripe_payment', 'Settings', 'Settings', 'manage_options', 'submenu_settings', 'submenu_page_settings');
        function submenu_page_settings()
        {
            ?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title() ?></h2>

                <form action="options.php" method="POST">
                    <?php
                    settings_fields('option_payment');
                    do_settings_sections('submenu_settings');
                    submit_button('Save changes', 'button-primary', 'submit', false, array('id' => "save_payment"));

                    ?>
                </form>
            </div>
            <?php
        }


        add_submenu_page('stripe_payment', 'Update to Pro', 'Update to Pro', 'manage_options', 'submenu_update_to_pro', 'submenu_update_to_pro');

        function submenu_update_to_pro($value = '')
        {
            require_once 'site/index.html';
        }
    }

    function payment_query()
    {
        $postarr = array(
            'post_title' => 'Платеж',
            'post_type' => 'stripe_payment',
            'post_status' => 'publish'
        );

        if ($_POST['system'] === 'stripe') {
            $post_ID = wp_insert_post($postarr);
            update_post_meta($post_ID, 'card_phone_number', $_POST['cardNumber']);
            update_post_meta($post_ID, 'sum', $_POST['sum']);
            update_post_meta($post_ID, 'id', $post_ID);
            update_post_meta($post_ID, 'system', $_POST['system']);
            update_post_meta($post_ID, 'token', $_POST['token']);
            $this->stripe_payment();
            wp_die();
        } elseif ($_POST['system'] === 'paypal') {
            $post_ID = wp_insert_post($postarr);
            update_post_meta($post_ID, 'card_phone_number', $_POST['phone']);
            update_post_meta($post_ID, 'sum', $_POST['sum']);
            update_post_meta($post_ID, 'id', $post_ID);
            update_post_meta($post_ID, 'system', $_POST['system']);
            update_post_meta($post_ID, 'token', $_POST['email']);
            get_option('option_PayPal_email_on') === 'on' ? $this->paypal_payment_email() : $this->paypal_payment($post_ID);
        }
    }

    function stripe_payment()
    {
        $stripe = new Stripe();
        $stripe->setApiKey(get_option('option_secret_key'));
        $charge = new Charge();

        try {
            $charge->create(array(
                "amount" => $_POST['sum'] * 100, // summ in cent
                "currency" => "usd",
                "source" => $_POST['token'],
                "description" => "Example charge"
            ));
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            echo $err['message'];
            wp_die();
        } catch (\Stripe\Error\RateLimit $e) {
            echo __("Too many requests made to the API too quickly",'stripe_payment');
            wp_die();
        } catch (\Stripe\Error\InvalidRequest $e) {
            echo __("Invalid parameters were supplied to Stripe's API",'stripe_payment');
            wp_die();
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            $_POST['test'] = __("Authentication with Stripe's API failed (maybe you changed API keys recently)",'stripe_payment');
            echo $_POST['test'];
            wp_die();
        } catch (\Stripe\Error\ApiConnection $e) {
            echo "Network communication with Stripe failed";
            wp_die();
        } catch (\Stripe\Error\Base $e) {
            echo __("Display a very generic error to the user, and maybe send yourself an email",'stripe_payment');
            wp_die();
        } catch (Exception $e) {
            echo __("Something else happened, completely unrelated to Stripe",'stripe_payment');
            wp_die();
        }
        echo get_site_url() . '/payment_success';
    }

    function paypal_payment_email()
    {
        $post = [
            'cmd' => '_xclick',
            'business' => get_option('admin_email'),
            'return' => get_site_url() . '/payment_success',
            'currency_code' => $_POST['cur'],
            'bn' => 'PP-BuyNowBF',
            'amount' => $_POST['sum']
        ];
        $versus = get_option('option_paypal_sandbox_on');
        if (empty($versus))
            $versus = 'paypal';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www." . $versus . ".com/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        preg_match_all('/^Location:(.*)$/mi', $response, $matches);
        echo $matches[1][0];
        wp_die();

    }

    function paypal_payment($post_ID)
    {
        global $paypalConfig, $apiContext;

        require plugin_dir_path(__FILE__) . '../includes/paypal-config.php';

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');//тип оплаты


        $amount = new Amount();
        $amount->setCurrency($_POST['val'])//валюта
        ->setTotal($_POST['sum']); //сумма

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription('Some description about the payment being made')
            ->setInvoiceNumber($post_ID);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($paypalConfig['return_url'])//сылка для успешного платежа
        ->setCancelUrl($paypalConfig['cancel_url']);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);
        try {
            $payment->create($apiContext);//попытка создать платеж
        } catch (Exception $e) {
            throw new Exception('Unable to create link for payment');
        }
        echo $payment->getApprovalLink();
        //var_dump($payment);
    }

    function load_page()
    {
        //---------------Stripe form-----------------------
        if (get_option('option_Stripe') == 'on') {
            $stripe = '<form id="stripe_payment_form" method="post" action="">';
            $stripe .= "<span class='payment-errors'/>";
            $stripe .= "<input type='text' id='cardNumber' data-stripe='number' maxlength='16' placeholder='"echo __('Enter your card number','stripe_payment').">";
            $stripe .= "<input type='text' id='expMonth' data-stripe='exp_month' maxlength='2' placeholder='MM'>";
            $stripe .= "<input type='text' id='expYear' data-stripe='exp_year' maxlength='2' placeholder='YY'>";
            $stripe .= "<input type='text' id='CVC' data-stripe='cvc' maxlength='3' placeholder='CVC/CVV'>";
            $stripe .= "<input type='text' id='sumPayment' placeholder='Sum of payment'>";
            $stripe .= "<input type='submit' class='submit payment_but' value='Submit' id='cf_send'>";
            $stripe .= "</form>";
        }
        //------------PayPal form (by email)------------------
        if (get_option('option_PayPal') == 'on') {
            $paypal = "<form id='paypal_payment_form' target='' action='' method=''>";
            $paypal .= "<input type='email' name='email' id='PaypalEmail' placeholder='"echo __('Enter your email','stripe_payment').">";
            $paypal .= "<select name='currency_code' id='PaypalCurrency'>";
            $paypal .= "<option selected value='default'>"echo __("Chose your Currency",'stripe_payment')."</option>";
            $paypal .= "<option value='AUD'>"echo __("Australian dollar",'stripe_payment')."</option>";
            $paypal .= "<option value='CAD'>"echo __("Canadian dollar",'stripe_payment')."</option>";
            $paypal .= "<option value='EUR'>"echo __("EURO",'stripe_payment')."</option>";
            $paypal .= "<option value='GBP'>"echo __("Pound Sterling",'stripe_payment')."</option>";
            $paypal .= "<option value='USD'>"echo __('U.S. dollar','stripe_payment')."</option>";
            $paypal .= "</select>";
            $paypal .= "<input type='phone' name='phone' id='PaypalPhone' placeholder="echo __('Yor phone number','stripe_payment').">";
            $paypal .= "<input type='sum' name='amount' id='PaypalSum' placeholder="echo __('Sum of payment','stripe_payment').">";
            $paypal .= "<input type='submit' class='submit payment_but' value='Submit' id='PaypalSend'>";
            $paypal .= "</form>";
        }
        $versus = get_option('option_paypal_sandbox_on');
        if (empty($versus))
            $versus = 'paypal';

        $args = array(
            'paypal' => get_option('option_PayPal'),
            'stripe' => get_option('option_Stripe'),
            'version' => $versus,
            'cansel' => get_site_url() . '/payment_fail',
            'stripe_form' => $stripe,
            'paypal_form' => $paypal
        );
        echo json_encode($args);
        wp_die();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/css/stripe_payment-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/js/stripe_payment-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'myajax',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );
    }
}
