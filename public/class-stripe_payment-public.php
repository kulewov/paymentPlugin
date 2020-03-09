<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       stripe_payment
 * @since      1.0.0
 *
 * @package    Stripe_payment
 * @subpackage Stripe_payment/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Stripe_payment
 * @subpackage Stripe_payment/public
 * @author     stripe_payment <stripe_payment>
 */
class Stripe_payment_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	public function register_shortcodes() {
			 add_shortcode( 'payment_forms', array( $this, 'form' ) );
			 add_shortcode( 'payment_success', array($this,'succes') );
	 }

public function form() {
			 require 'form.php';
	 }
	 public function succes()
	 {
	 	require 'success_page.php';
	 }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/stylesheets/stripe_payment-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/stripe_payment-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'myajax',
			array(
				'url' => admin_url('admin-ajax.php'),
				'pKey'=>get_option('option_publish_key')
			)
		);
        wp_enqueue_script('stripe','https://js.stripe.com/v2/', array(), NULL, false);
        wp_enqueue_script('mask','https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js');
        wp_enqueue_script('jquery-validator', plugin_dir_url( __FILE__ ) . '../includes/jquery.validate.min.js', array('jquery'), '', true);
        wp_enqueue_script('coockie','https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js');
	}
	/**/

}
