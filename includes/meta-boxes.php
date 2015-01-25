<?php

/**
 * Add meta boxes
 */
function pronamic_donations_add_meta_boxes() {
	$post_types = get_option( 'pronamic_donations_post_types' );
	
	if ( $post_types ) { 
		foreach ( $post_types as $post_type ) {
			add_meta_box(  
				'pronamic_donations',
				__( 'Donation information', 'pronamic_donations' ),
				'pronamic_donations_box',
				$post_type,
				'normal',
				'high'
			);
		}
	}
}

add_action( 'add_meta_boxes', 'pronamic_donations_add_meta_boxes' );

/**
 * Print metabox
 */
function pronamic_donations_box( $post ) {
	wp_nonce_field( 'pronamic_save_donations_box_nonce', 'pronamic_donations_box_nonce' );

	?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="pronamic_donations_funding_goal">
						<?php _e( 'Funding goal', 'pronamic_donations' ); ?>
					</label>
				</th>
				<td>
					<input id="pronamic_donations_funding_goal" name="_pronamic_donations_funding_goal" value="<?php echo esc_attr( get_post_meta( $post->ID, '_pronamic_donations_funding_goal', true ) ); ?>" type="text" size="20" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pronamic_donations_raised">
						<?php _e( 'Raised', 'pronamic_donations' ); ?>
					</label>
				</th>
				<td>
					<input id="pronamic_donations_raised" name="_pronamic_donations_raised" value="<?php echo esc_attr( get_post_meta( $post->ID, '_pronamic_donations_raised', true ) ); ?>" type="text" size="20" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pronamic_donations_number">
						<?php _e( 'Number donations', 'pronamic_donations' ); ?>
					</label>
				</th>
				<td>
					<input id="pronamic_donations_number" name="_pronamic_donations_number" value="<?php echo esc_attr( get_post_meta( $post->ID, '_pronamic_donations_number', true ) ); ?>" type="text" size="20" class="regular-text" />
				</td>
			</tr>
		</tbody>
	</table>

	<?php
}

/**
 * Save metabox
 */
function pronamic_save_donations( $post_id ) {
	global $post;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return;
    }

	if ( ! isset( $_POST['pronamic_donations_box_nonce'] ) || ! wp_verify_nonce( $_POST['pronamic_donations_box_nonce'], 'pronamic_save_donations_box_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post' ) ) {
		return;
	}

	// Save data
	$data = filter_input_array( INPUT_POST, array( 
		'_pronamic_donations_funding_goal' => FILTER_SANITIZE_STRING,
		'_pronamic_donations_raised'       => FILTER_SANITIZE_STRING,
		'_pronamic_donations_number'       => FILTER_SANITIZE_STRING,
	) );

	foreach ( $data as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}
}

add_action( 'save_post', 'pronamic_save_donations' );
