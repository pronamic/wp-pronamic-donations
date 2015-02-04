<?php 

/**
 * Show donations
 */
class Pronamic_Donations_Post_Box_Widget extends WP_Widget {
	function Pronamic_Donations_Post_Box_Widget() {
		parent::__construct( 'pronamic-donations-post-box-widget', __( 'Donations', 'pronamic_donations' ), array( 'description' => __( 'Displays a box with specific post donation information.', 'pronamic_donations' ) ) );
	}

	function widget( $args, $instance ) {
		global $post;

		extract( $args );

		$title                 = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_number_donations = empty( $instance['show_number_donations'] ) ? '' : esc_attr( $instance['show_number_donations'] );
		$show_funding_goal     = empty( $instance['show_funding_goal'] ) ? '' : esc_attr( $instance['show_funding_goal'] );
		$show_raised           = empty( $instance['show_raised'] ) ? '' : esc_attr( $instance['show_raised'] );
		$show_percentages      = empty( $instance['show_percentages'] ) ? '' : esc_attr( $instance['show_percentages'] );
		$donation_form         = empty( $instance['donation_form'] ) ? '' : esc_attr( $instance['donation_form'] );

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		if ( get_post_meta( $post->ID, '_pronamic_donations_funding_goal', true ) && get_post_meta( $post->ID, '_pronamic_donations_raised', true ) ) {
			$percentage = ( get_post_meta( $post->ID, '_pronamic_donations_raised', true ) * 100 ) / get_post_meta( $post->ID, '_pronamic_donations_funding_goal', true );
			$percentage = round( $percentage, 2 );
		}

		?>

		<div class="donations">
			<?php if ( $show_number_donations ) : ?>

				<?php if ( get_post_meta( $post->ID, '_pronamic_donations_number', true ) ) : ?>
	
					<div class="donate-section">
						<span class="value"><?php echo get_post_meta( $post->ID, '_pronamic_donations_number', true ); ?></span> <span class="label"><?php _e( 'donations', 'pronamic_donations' ); ?></span>
					</div>

				<?php else : ?>

					<p>
						<?php _e( 'There are no donations yet. Be the first!', 'pronamic_donations' ); ?>
					</p>

				<?php endif; ?>
			
			<?php endif; ?>
			
			<?php if ( $show_funding_goal && get_post_meta( $post->ID, '_pronamic_donations_funding_goal', true ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo '&euro;' . number_format( get_post_meta( $post->ID, '_pronamic_donations_funding_goal', true ), 2, ',', '.' ); ?></span> <span class="label"><?php _e( 'is our goal', 'pronamic_donations' ); ?></span>
				</div>
			
			<?php endif; ?>

			<?php if ( $show_raised && get_post_meta( $post->ID, '_pronamic_donations_raised', true ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo '&euro;' . number_format( get_post_meta( $post->ID, '_pronamic_donations_raised', true ), 2, ',', '.' ); ?></span> <span class="label"><?php _e( 'raised so far', 'pronamic_donations' ); ?></span>
				</div>
			
			<?php endif; ?>

			<?php if ( $show_percentages && isset ( $percentage ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo $percentage . '%'; ?></span> <span class="label"><?php _e( 'funded', 'pronamic_donations' ); ?></span>
				</div>

				<div class="progress">
					<div class="bar" style="width: <?php echo $percentage . '%'; ?>;"></div>
				</div>

			<?php endif; ?>

			<?php if ( $donation_form == 'button' && get_option( 'pronamic_donations_gravity_forms_page_id' ) ) : ?>

				<?php $url = add_query_arg( 'pid', get_the_ID(), get_permalink( get_option( 'pronamic_donations_gravity_forms_page_id' ) ) ); ?>

				<a class="button btn btn-primary alt large" href="<?php echo esc_url( $url ); ?>"><?php _e( 'Donate', 'pronamic_donations' ); ?></a>

			<?php endif; ?>
		</div>

		<?php

		if ( $donation_form == 'widget' && get_option( 'pronamic_donations_gravity_form_id' ) ) {
			gravity_form( get_option( 'pronamic_donations_gravity_form_id' ), false, false, false, array( 'pid' => get_the_ID() ) );
		}

		echo $after_widget; 
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']                 = $new_instance['title'];
		$instance['show_number_donations'] = $new_instance['show_number_donations'];
		$instance['show_funding_goal']     = $new_instance['show_funding_goal'];
		$instance['show_raised']           = $new_instance['show_raised'];
		$instance['show_percentages']      = $new_instance['show_percentages'];
		$instance['donation_form']         = $new_instance['donation_form'];

		return $instance;
	}

	function form( $instance ) {
		$title                 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$show_number_donations = isset( $instance['show_number_donations'] ) ? esc_attr( $instance['show_number_donations'] ) : '';
		$show_funding_goal     = isset( $instance['show_funding_goal'] ) ? esc_attr( $instance['show_funding_goal'] ) : '';
		$show_raised           = isset( $instance['show_raised'] ) ? esc_attr( $instance['show_raised'] ) : '';
		$show_percentages      = isset( $instance['show_percentages'] ) ? esc_attr( $instance['show_percentages'] ) : '';
		$donation_form         = isset( $instance['donation_form'] ) ? esc_attr( $instance['donation_form'] ) : '';

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title', 'pronamic_donations' ); ?>
			</label>

			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_number_donations' ); ?>"  name="<?php echo $this->get_field_name( 'show_number_donations' ); ?>" type="checkbox" value="1" <?php checked( $show_number_donations, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_number_donations' ); ?>">
				<?php _e( 'Show number donations', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_funding_goal' ); ?>"  name="<?php echo $this->get_field_name( 'show_funding_goal' ); ?>" type="checkbox" value="1" <?php checked( $show_funding_goal, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_funding_goal' ); ?>">
				<?php _e( 'Show funding goal', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_raised' ); ?>"  name="<?php echo $this->get_field_name( 'show_raised' ); ?>" type="checkbox" value="1" <?php checked( $show_raised, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_raised' ); ?>">
				<?php _e( 'Show raised', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_percentages' ); ?>"  name="<?php echo $this->get_field_name( 'show_percentages' ); ?>" type="checkbox" value="1" <?php checked( $show_percentages, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_percentages' ); ?>">
				<?php _e( 'Show percentages', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'donation_form' ) ); ?>">
				<?php _e( 'Donation form:', 'pronamic_donations' ); ?>
			</label>

			<select id="<?php echo esc_attr( $this->get_field_id( 'donation_form' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'donation_form' ) ); ?>">
				<option value="default" <?php selected( $donation_form, 'default' ); ?>><?php _e( 'Show on current page', 'pronamic_donations' ); ?></option>
				<option value="button" <?php selected( $donation_form, 'button' ); ?>><?php _e( 'Show on another page', 'pronamic_donations' ); ?></option>
				<option value="widget" <?php selected( $donation_form, 'widget' ); ?>><?php _e( 'Show in widget', 'pronamic_donations' ); ?></option>
			</select>
		</p>
		
		<?php
	}
}
