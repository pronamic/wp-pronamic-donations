<?php

/**
 * Donation shortcode
 */
function pronamic_donations_donation_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'id'          => get_the_ID(),
		'number'      => 'true',
		'goal'        => 'true',
		'raised'      => 'true',
		'percentage'  => 'true',
		'form'        => 'true',
	), $atts );

	if ( get_post_meta( $atts['id'], '_pronamic_donations_funding_goal', true ) && get_post_meta( $atts['id'], '_pronamic_donations_raised', true ) ) {
		$percentage = ( get_post_meta( $atts['id'], '_pronamic_donations_raised', true ) * 100 ) / get_post_meta( $atts['id'], '_pronamic_donations_funding_goal', true );
		$percentage = round( $percentage, 2 );
	}

	ob_start(); ?>

	<div class="donations">
		<?php if ( get_post_meta( $atts['id'], '_pronamic_donations_number', true ) && $atts['number'] == 'true' ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( get_post_meta( $atts['id'], '_pronamic_donations_number', true ) ); ?></span> <span class="label"><?php _e( 'donations', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>
		
		<?php if ( get_post_meta( $atts['id'], '_pronamic_donations_funding_goal', true ) && $atts['goal'] == 'true' ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( '&euro;' . number_format( get_post_meta( $atts['id'], '_pronamic_donations_funding_goal', true ), 2, ',', '.' ) ); ?></span> <span class="label"><?php _e( 'is our goal', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>

		<?php if ( get_post_meta( $atts['id'], '_pronamic_donations_raised', true ) && $atts['raised'] == 'true' ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( '&euro;' . number_format( get_post_meta( $atts['id'], '_pronamic_donations_raised', true ), 2, ',', '.' ) ); ?></span> <span class="label"><?php _e( 'raised so far', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>

		<?php if ( isset( $percentage ) && $atts['percentage'] == 'true' ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( $percentage . '%' ); ?></span> <span class="label"><?php _e( 'funded', 'pronamic_donations' ); ?></span>
			</div>

			<div class="progress">
				<div class="bar" style="width: <?php echo esc_attr( $percentage . '%' ); ?>;"></div>
			</div>

		<?php endif; ?>

		<?php if ( get_option( 'pronamic_donations_gravity_forms_page_id' ) && $atts['form'] == 'true' ) : ?>

			<?php $url = add_query_arg( 'pid', get_the_ID(), get_permalink( get_option( 'pronamic_donations_gravity_forms_page_id' ) ) ); ?>

			<a class="button btn btn-primary alt large" href="<?php echo esc_url( $url ); ?>"><?php _e( 'Donate', 'pronamic_donations' ); ?></a>

		<?php endif; ?>
	</div>

	<?php

	$output = ob_get_contents();

	ob_end_clean();

	return $output;
}

add_shortcode( 'donation', 'pronamic_donations_donation_shortcode' );
