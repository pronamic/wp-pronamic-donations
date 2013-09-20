<?php
/*
Plugin Name: Pronamic Donations
Plugin URI: http://pronamic.eu/wordpress/donations/
Description: This plugin add some basic donation functionality to WordPress.
Version: 1.0
Requires at least: 3.5
Author: Pronamic
Author URI: http://pronamic.eu/
License: GPL
*/

define( 'PRONAMIC_DONATIONS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Global includes
 */
require PRONAMIC_DONATIONS_PATH . 'includes/options.php';
require PRONAMIC_DONATIONS_PATH . 'includes/meta-boxes.php';

/**
 * Widget includes
 */
require PRONAMIC_DONATIONS_PATH . 'widgets/Pronamic_Donations_Total_Box_Widget.php';
require PRONAMIC_DONATIONS_PATH . 'widgets/Pronamic_Donations_Post_Box_Widget.php';

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
if ( get_option( 'pronamic_donations_gravity_form_id' ) ) {

	function update_donation_information( $entry, $form ) {
		$pid = filter_input( INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT );
	
		if ( isset( $pid ) ) {
			$donate_id = $pid;
		} else {
			$post = get_post( $entry['post_id'] );

			$donate_id = $post->ID;
		}

		$total = 0;

		foreach( $form['fields'] as &$field ) {
			if ( $field['type'] == 'total' ) {
				$total += $entry[$field['id']];
			}
		}

		$raised = get_post_meta( $donate_id, '_pronamic_donations_raised', true );
		$number = get_post_meta( $donate_id, '_pronamic_donations_number', true );
		$total_raised = get_option( 'pronamic_donations_total_raised' );
		$total_number = get_option( 'pronamic_donations_total_number' );
		
		$raised += $total;
		$number = $number + 1;
		$total_raised += $total;
		$total_number = $total_number + 1;
		
		update_post_meta( $donate_id, '_pronamic_donations_raised', $raised );
		update_post_meta( $donate_id, '_pronamic_donations_number', $number );
		update_option( 'pronamic_donations_total_raised', $total_raised );
		update_option( 'pronamic_donations_total_number', $total_number );
	}

	add_action( 'gform_after_submission_' . get_option( 'pronamic_donations_gravity_form_id' ), 'update_donation_information', 10, 2 );
}

/**
 * Template functions
 */
 function pronamic_donations_get_total_raised() {
	return get_option( 'pronamic_donations_total_raised' );
 }
 
function pronamic_donations_get_total_number() {
	return get_option( 'pronamic_donations_total_number' );
 }