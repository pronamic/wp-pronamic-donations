<?php
/*
Plugin Name: Pronamic Donations
Plugin URI: http://www.pronamic.eu/plugins/pronamic-donations/
Description: This plugin adds some basic donation functionality to WordPress.

Version: 1.1.0
Requires at least: 3.5

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_donations
Domain Path: /languages/

License: GPLv3

GitHub URI: https://github.com/pronamic/wp-pronamic-donations/
*/

define( 'PRONAMIC_DONATIONS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Global includes
 */
if ( is_admin() ) {
	require PRONAMIC_DONATIONS_PATH . 'includes/options.php';
	require PRONAMIC_DONATIONS_PATH . 'includes/meta-boxes.php';
}

require PRONAMIC_DONATIONS_PATH . 'includes/shortcodes.php';

/**
 * Widget includes
 */
require PRONAMIC_DONATIONS_PATH . 'widgets/Pronamic_Donations_Total_Box_Widget.php';
require PRONAMIC_DONATIONS_PATH . 'widgets/Pronamic_Donations_Post_Box_Widget.php';

/**
 * Init
 */
function pronamic_donations_init() {
		load_plugin_textdomain( 'pronamic_donations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'pronamic_donations_init' );

/**
 * Widgets
 */
function pronamic_donations_wp_widgets() {
	register_widget( 'Pronamic_Donations_Total_Box_Widget' );
	register_widget( 'Pronamic_Donations_Post_Box_Widget' );
}

add_action( 'widgets_init', 'pronamic_donations_wp_widgets', 1 );

/**
 * Enqueue scripts & styles
 */
function pronamic_donations_load_scripts() {
	wp_enqueue_style(
		'pronamic-donations',
		plugins_url( '/css/pronamic-donations.css' , __FILE__ )
	);
}

add_action( 'wp_enqueue_scripts', 'pronamic_donations_load_scripts' );

/**
 * Gravity Forms
 */
$form_ids = get_option( 'pronamic_donations_gravity_form_ids' );

if ( ! $form_ids ) {
	$form_ids = array( get_option( 'pronamic_donations_gravity_form_id' ) );
}

if ( $form_ids ) {

	function update_donation_information( $entry, $form ) {
		global $form_ids;

		if ( ! in_array( $form->id, $form_ids ) ) {
			return;
		}

		$post_id = filter_input( INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		gform_update_meta( $entry['id'], 'pronamic_donations_post_id', $post_id );
	}

	add_action( 'gform_after_submission', 'update_donation_information', 10, 2 );
}

function pronamic_donations_gform_post_payment_completed( $entry, $action ) {
	global $form_ids;

	if ( $form_ids && ! in_array( $entry['form_id'], $form_ids ) ) {
		return;
	}

	if ( ! isset( $action['amount'] ) ) {
		return;
	}

	$amount = $action['amount'];

	// Totals
	$total_raised = get_option( 'pronamic_donations_total_raised' );
	$total_number = get_option( 'pronamic_donations_total_number' );

	$total_raised += $amount;
	$total_number += 1;

	update_option( 'pronamic_donations_total_raised', $total_raised );
	update_option( 'pronamic_donations_total_number', $total_number );

	// Per post
	$post_id = gform_get_meta( $entry['id'], 'pronamic_donations_post_id' );

	if ( ! empty( $post_id ) ) {
		$raised = get_post_meta( $post_id, '_pronamic_donations_raised', true );
		$number = get_post_meta( $post_id, '_pronamic_donations_number', true );

		$raised += $amount;
		$number += 1;

		update_post_meta( $post_id, '_pronamic_donations_raised', $raised );
		update_post_meta( $post_id, '_pronamic_donations_number', $number );
	}
}

add_action( 'gform_post_payment_completed', 'pronamic_donations_gform_post_payment_completed', 10, 2 );

/**
 * Template functions
 */
function pronamic_donations_get_total_raised() {
	return get_option( 'pronamic_donations_total_raised' );
}

function pronamic_donations_get_total_number() {
	return get_option( 'pronamic_donations_total_number' );
}
	