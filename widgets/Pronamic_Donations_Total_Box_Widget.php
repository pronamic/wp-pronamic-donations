<?php 

/**
 * Show total donations
 */
class Pronamic_Donations_Total_Box_Widget extends WP_Widget {
	function Pronamic_Donations_Total_Box_Widget() {
		parent::__construct( 'pronamic-donations-total-box-widget', __( 'Total donations', 'pronamic_donations' ), array( 'description' => __( 'Displays a box with total donation information.', 'pronamic_donations' ) ) );
	}

	function widget( $args, $instance ) {
		global $post;

		extract( $args );

		$title                       = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_total_number_donations = empty( $instance['show_total_number_donations'] ) ? '' : esc_attr( $instance['show_total_number_donations'] );
		$show_total_funding_goal     = empty( $instance['show_total_funding_goal'] ) ? '' : esc_attr( $instance['show_total_funding_goal'] );
		$show_total_raised           = empty( $instance['show_total_raised'] ) ? '' : esc_attr( $instance['show_total_raised'] );
		$show_total_percentages      = empty( $instance['show_total_percentages'] ) ? '' : esc_attr( $instance['show_total_percentages'] );

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		if ( get_option( 'pronamic_donations_total_raised' ) && get_option( 'pronamic_donations_total_funding_goal' ) ) {
			$percentage = ( get_option( 'pronamic_donations_total_raised' ) * 100 ) / get_option( 'pronamic_donations_total_funding_goal' );
			$percentage = round( $percentage, 2 );
		}

		?>

		<div class="donations">
			<?php if ( $show_total_number_donations ) : ?>

				<?php if ( get_option( 'pronamic_donations_total_number' ) ) : ?>

					<div class="donate-section">
						<span class="value"><?php echo get_option( 'pronamic_donations_total_number' ); ?></span> <span class="label"><?php _e( 'donations', 'pronamic_donations' ); ?></span>
					</div>

				<?php else : ?>

					<p>
						<?php _e( 'There are no donations yet. Be the first!', 'pronamic_donations' ); ?>
					</p>

				<?php endif; ?>

			<?php endif; ?>
			
			<?php if ( $show_total_funding_goal && get_option( 'pronamic_donations_total_funding_goal' ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo '&euro;' . number_format( get_option( 'pronamic_donations_total_funding_goal' ), 2, ',', '.' ); ?></span> <span class="label"><?php _e( 'is our goal', 'pronamic_donations' ); ?></span>
				</div>
			
			<?php endif; ?>

			<?php if ( $show_total_raised && get_option( 'pronamic_donations_total_raised' ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo '&euro;' . number_format( get_option( 'pronamic_donations_total_raised' ), 2, ',', '.' ); ?></span> <span class="label"><?php _e( 'raised so far', 'pronamic_donations' ); ?></span>
				</div>

			<?php endif; ?>

			<?php if ( $show_total_percentages && isset ( $percentage ) ) : ?>

				<div class="donate-section">
					<span class="value"><?php echo $percentage . '%'; ?></span> <span class="label"><?php _e( 'funded', 'pronamic_donations' ); ?></span>
				</div>
				
				<div class="progress">
					<div class="bar" style="width: <?php echo $percentage . '%'; ?>;"></div>
				</div>
			
			<?php endif; ?>

			<?php if ( get_option( 'pronamic_donations_gravity_forms_page_id' ) ) : ?>

				<a class="button btn btn-primary alt large" href="<?php echo get_permalink( get_option( 'pronamic_donations_gravity_forms_page_id' ) ); ?>/?pid=<?php echo get_the_ID(); ?>"><?php _e( 'Donate', 'pronamic_donations' ); ?></a>
			
			<?php endif; ?>
		</div>

		<?php

		echo $after_widget; 
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']                       = $new_instance['title'];
		$instance['show_total_number_donations'] = $new_instance['show_total_number_donations'];
		$instance['show_total_funding_goal']     = $new_instance['show_total_funding_goal'];
		$instance['show_total_raised']           = $new_instance['show_total_raised'];
		$instance['show_total_percentages']      = $new_instance['show_total_percentages'];

		return $instance;
	}

	function form( $instance ) {
		$title                       = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$show_total_number_donations = isset( $instance['show_total_number_donations'] ) ? esc_attr( $instance['show_total_number_donations'] ) : '';
		$show_total_funding_goal     = isset( $instance['show_total_funding_goal'] ) ? esc_attr( $instance['show_total_funding_goal'] ) : '';
		$show_total_raised           = isset( $instance['show_total_raised'] ) ? esc_attr( $instance['show_total_raised'] ) : '';
		$show_total_percentages      = isset( $instance['show_total_percentages'] ) ? esc_attr( $instance['show_total_percentages'] ) : '';

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title', 'pronamic_donations' ); ?>
			</label>

			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_total_number_donations' ); ?>"  name="<?php echo $this->get_field_name( 'show_total_number_donations' ); ?>" type="checkbox" value="1" <?php checked( $show_total_number_donations, 1 ); ?> />
	
			<label for="<?php echo $this->get_field_id( 'show_total_number_donations' ); ?>">
				<?php _e( 'Show total number donations', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_total_funding_goal' ); ?>"  name="<?php echo $this->get_field_name( 'show_total_funding_goal' ); ?>" type="checkbox" value="1" <?php checked( $show_total_funding_goal, 1 ); ?> />
	
			<label for="<?php echo $this->get_field_id( 'show_total_funding_goal' ); ?>">
				<?php _e( 'Show total funding goal', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_total_raised' ); ?>"  name="<?php echo $this->get_field_name( 'show_total_raised' ); ?>" type="checkbox" value="1" <?php checked( $show_total_raised, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_total_raised' ); ?>">
				<?php _e( 'Show total raised', 'pronamic_donations' ); ?>
			</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'show_total_percentages' ); ?>"  name="<?php echo $this->get_field_name( 'show_total_percentages' ); ?>" type="checkbox" value="1" <?php checked( $show_total_percentages, 1 ); ?> />

			<label for="<?php echo $this->get_field_id( 'show_total_percentages' ); ?>">
				<?php _e( 'Show total percentages', 'pronamic_donations' ); ?>
			</label>
		</p>
		
		<?php
	}
}
