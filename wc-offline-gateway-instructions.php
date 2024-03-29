<?php

/**
 * Plugin Name: WooCommerce Offline Gateway Intructions
 * Text Domain: wc-offline-gateway-instructions
 * Description: Checks payment gateway on order_received page and change thank you message accordingly
 * Version: 1.0.1
 */

/* Protect php code */
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_thankyou_order_received_text', 'woocommerce_thankyou_order_received_text_gateway_filter', 10, 2 );

/**
 * Function for `woocommerce_thankyou_order_received_text` filter-hook.
 * Customize the "Thank you. Your order has been received." message on the Thank You page
 * 
 * @param \WC_Order    $order      Order object.
 * @param string|false $permission If the current user can view the order details or not.
 *
 * @return \WC_Order
 */
function woocommerce_thankyou_order_received_text_gateway_filter( $order, $permission ){

    // do nothing if we are not on the order received page
    if( ! is_wc_endpoint_url( 'order-received' ) || empty( $_GET[ 'key' ] ) ) {
        return;	
    }

    // Get the order ID
    $order_id = wc_get_order_id_by_order_key( $_GET[ 'key' ] );

    // Get an instance of the WC_Order object
    $order = wc_get_order( $order_id );

    // check payment method and act accordingly
	if ( in_array( $order->get_payment_method(), ['venmo', 'zelle', 'cashapp','mail'] ) ) {
		// if manual payment verification is required, replace thank you text with below
        echo '<p>Thanks for your order! It’s on-hold until we confirm that payment has been received.</p>';
    } else {
        echo '<p>Thank you! Your order has been received.</p>';
    }
	/* if ( 'venmo_gateway' === $order->get_payment_method() ) {
		// if cash of delivery, redirecto to a custom thank you page
        echo '<p>Thanks for your order. It’s on-hold until we confirm that payment has been received.</p>';
		//exit; // always exit
    } elseif ( 'zelle_gateway' === $order->get_payment_method() ) {
		// if cash of delivery, redirecto to a custom thank you page
        echo '<p>Thank you for your order! Zelle: support@michiganmyco.com</p>';
		//exit; // always exit
    } elseif ( 'cashapp_gateway' === $order->get_payment_method() ) {
		// if cash of delivery, redirecto to a custom thank you page
        echo '<p>Thank you for your order! Cash App: $MichiganMyco</p>';
		//exit; // always exit
    } elseif ( 'mail_gateway' === $order->get_payment_method() ) {
		// if cash of delivery, redirecto to a custom thank you page
        echo '<p>Thank you for your order! Send cash/check to:</p>';
        echo '<p>MICHIGAN MYCO</br>PO BOX 2207</br>SOUTHFIELD, MI 48037</p>';
		//exit; // always exit
    } else {
        echo '<p>Thank you. Your order has been received.</p>';
    } */
}
