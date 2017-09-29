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

	$pid = $atts['id'];

	if ( 'total' === strtolower( $atts['id'] ) ) {
		$pid = get_the_ID();

		$number       = get_option( 'pronamic_donations_total_number' );
		$funding_goal = get_option( 'pronamic_donations_total_funding_goal' );
		$raised       = get_option( 'pronamic_donations_total_raised' );
	} else {
		$number       = get_post_meta( $atts['id'], '_pronamic_donations_number', true );
		$funding_goal = get_post_meta( $atts['id'], '_pronamic_donations_funding_goal', true );
		$raised       = get_post_meta( $atts['id'], '_pronamic_donations_raised', true );
	}

	if ( $funding_goal && $raised ) {
		$percentage = round( ( ( $raised * 100 ) / $funding_goal ), 2 );
	}

	ob_start(); ?>

	<div class="donations">
		<?php if ( 'true' == $atts['number'] && $number ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( $number ); ?></span> <span class="label"><?php _e( 'donations', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>

		<?php if ( 'true' == $atts['goal'] && $funding_goal ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( '&euro;' . number_format( $funding_goal, 2, ',', '.' ) ); ?></span> <span class="label"><?php _e( 'is our goal', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>

		<?php if ( 'true' == $atts['raised'] && $raised ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( '&euro;' . number_format( $raised, 2, ',', '.' ) ); ?></span> <span class="label"><?php _e( 'raised so far', 'pronamic_donations' ); ?></span>
			</div>

		<?php endif; ?>

		<?php if ( 'true' == $atts['percentage'] && isset( $percentage ) ) : ?>

			<div class="donate-section">
				<span class="value"><?php echo esc_html( $percentage . '%' ); ?></span> <span class="label"><?php _e( 'funded', 'pronamic_donations' ); ?></span>
			</div>

			<div class="progress">
				<div class="bar" style="width: <?php echo esc_attr( $percentage . '%' ); ?>;"></div>
			</div>

		<?php endif; ?>

		<?php if ( get_option( 'pronamic_donations_gravity_forms_page_id' ) && 'true' == $atts['form'] ) : ?>

			<?php $url = add_query_arg( 'pid', $pid, get_permalink( get_option( 'pronamic_donations_gravity_forms_page_id' ) ) ); ?>

			<a class="button btn btn-primary alt large" href="<?php echo esc_url( $url ); ?>"><?php _e( 'Donate', 'pronamic_donations' ); ?></a>

		<?php endif; ?>
	</div>

	<?php

	$output = ob_get_contents();

	ob_end_clean();

	return $output;
}

add_shortcode( 'donation', 'pronamic_donations_donation_shortcode' );
