<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              stripe_payment
 * @since             1.0.0
 * @package           Stripe_payment
 *
 * @wordpress-plugin
 * Plugin Name:       Multiply Payment
 * Plugin URI:        hardevs.io
 * Description:       To start using payment form type this on your page <code>[payment_forms]<code/>
 * Version:           1.0.0
 * Author:            hardevs.io
 * Author URI:        hardevs.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stripe_payment
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('STRIPE_PAYMENT_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-stripe_payment-activator.php
 */
function activate_stripe_payment()
{
    $success = array(
        'comment_status' => 'closed',
        'post_author' => 'Multiply Payment',
        'post_name' => 'payment_success',
        'post_content' => '<code>[payment_success]</code>',
        'post_status' => 'publish',
        'post_title' => 'Multiply payment success',
        'post_type' => 'page');
    $fail = array(
        'comment_status' => 'closed',
        'post_author' => 'Multiply Payment',
        'post_name' => 'payment_fail',
        'post_content' => '',
        'post_status' => 'publish',
        'post_title' => 'Multiply payment fail',
        'post_type' => 'page');
    wp_insert_post($fail);
    wp_insert_post($success);

    require_once plugin_dir_path(__FILE__) . 'includes/class-stripe_payment-activator.php';
    Stripe_payment_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-stripe_payment-deactivator.php
 */
function deactivate_stripe_payment()
{
    wp_delete_post(url_to_postid(get_site_url() . '/payment_success'), $force_delete = true);
    wp_delete_post(url_to_postid(get_site_url() . '/payment_fail'), $force_delete = true);
    delete_option('option_PayPal');
    delete_option('option_Stripe');
    delete_option('option_paypal_id');
    delete_option('option_paypal_secret_code');
    //delete_option( 'option_publish_key' );
    //delete_option( 'option_secret_key' );
    require_once plugin_dir_path(__FILE__) . 'includes/class-stripe_payment-deactivator.php';
    Stripe_payment_Deactivator::deactivate();
}


register_activation_hook(__FILE__, 'activate_stripe_payment');
register_deactivation_hook(__FILE__, 'deactivate_stripe_payment');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-stripe_payment.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stripe_payment()
{

    $plugin = new Stripe_payment();
    $plugin->run();

}

run_stripe_payment();
