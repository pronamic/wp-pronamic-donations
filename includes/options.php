<?php

/**
 * Admin menu
 */
function pronamic_donations_admin_menu() {
	add_submenu_page(
		'options-general.php',
		__( 'Pronamic Donations', 'pronamic_donations' ),
		__( 'Donations', 'pronamic_donations' ),
		'edit_theme_options',
		'pronamic_donations_settings',
		'pronamic_donations_settings_page_render'
	);
}
add_action( 'admin_menu', 'pronamic_donations_admin_menu' );

/**
 * Admin initialize
 */
function pronamic_donations_admin_init() {
	register_setting( 'pronamic_funding_options', 'pronamic_donations_total_funding_goal' );
	register_setting( 'pronamic_funding_options', 'pronamic_donations_total_raised' );
	register_setting( 'pronamic_funding_options', 'pronamic_donations_total_number' );

	register_setting( 'pronamic_settings_options', 'pronamic_donations_post_types' );
	register_setting( 'pronamic_settings_options', 'pronamic_donations_gravity_forms_page_id' );
	register_setting( 'pronamic_settings_options', 'pronamic_donations_gravity_form_id' );
}
add_action( 'admin_init', 'pronamic_donations_admin_init' );

/**
 * Render
 */
function pronamic_donations_settings_page_render() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>

		<h2>
			<?php _e( 'Pronamic Donations', 'pronamic_donations' ); ?>
		</h2>

		<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'funding_options'; ?>  
		  
		<h2 class="nav-tab-wrapper">  
		    <a href="?page=pronamic_donations_settings&tab=funding_options" class="nav-tab <?php echo $active_tab == 'funding_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Fundings', 'pronamic_donations' ); ?></a>  
		    <a href="?page=pronamic_donations_settings&tab=settings_options" class="nav-tab <?php echo $active_tab == 'settings_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'pronamic_donations' ); ?></a>  
		</h2> 

		<form method="post" action="options.php">
			<?php if ( $active_tab == 'funding_options' ) : ?>
			
				<?php settings_fields( 'pronamic_funding_options' ); ?>

				<h3>
					<?php _e( 'Fundings', 'pronamic_donations' ); ?>
				</h3>
	
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="pronamic_donations_total_funding_goal"><?php _e( 'Total funding goal', 'pronamic_donations' ); ?></label>
						</th>
						<td>
							<input id="pronamic_donations_total_funding_goal" name="pronamic_donations_total_funding_goal" type="text" value="<?php echo get_option( 'pronamic_donations_total_funding_goal' ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="pronamic_donations_total_raised"><?php _e( 'Total raised', 'pronamic_donations' ); ?></label>
						</th>
						<td>
							<input id="pronamic_donations_total_raised" name="pronamic_donations_total_raised" type="text" value="<?php echo get_option( 'pronamic_donations_total_raised' ); ?>" class="regular-text" />

							<p class="description">
								<?php _e( 'This field is automatically updated.', 'pronamic_donations' ); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="pronamic_donations_total_number"><?php _e( 'Total number donations', 'pronamic_donations' ); ?></label>
						</th>
						<td>
							<input id="pronamic_donations_total_number" name="pronamic_donations_total_number" type="text" value="<?php echo get_option( 'pronamic_donations_total_number' ); ?>" class="regular-text" />

							<p class="description">
								<?php _e( 'This field is automatically updated.', 'pronamic_donations' ); ?>
							</p>
						</td>
					</tr>
				</table>

				<p>
					<span class="dashicons dashicons-info"></span>
					<?php _e( "Use the 'Total donations' widget to show totals to site visitors.", 'pronamic_donations' ); ?>
				</p>

			<?php else : ?>
				
				<?php settings_fields( 'pronamic_settings_options' ); ?>

				<h3>
					<?php _e( 'Post Types', 'pronamic_donations' ); ?>
				</h3>

				<?php

				$post_types = get_post_types( array(
						'public' => true
					),
					'objects'
				);

				?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="pronamic_donations_post_types"><?php _e( 'Post types', 'pronamic_donations' ); ?></label>
						</th>
						<td>
							<?php

							$active = get_option( 'pronamic_donations_post_types' );

							$active = is_array( $active ) ? $active : array();

							foreach ( $post_types as $key => $post_type ) : ?>
					
								<div>
									<input name="pronamic_donations_post_types[]" type="checkbox" value="<?php echo $key; ?>" <?php checked( in_array( $key, $active ) ); ?>  /> <?php echo $post_type->label; ?>
								</div>
							
							<?php endforeach; ?>

							<p class="description">
								<?php _e( 'Enable post types to be able to set a target amount per post.', 'pronamic_donations' ); ?>
							</p>
						</td>
					</tr>
				</table>
			
				<h3>
					<?php _e( 'Gravity Forms', 'pronamic_donations' ); ?>
				</h3>
	
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="pronamic_donations_gravity_forms_page_id"><?php _e( 'Gravity Forms Page', 'pronamic_donations' ); ?></label>
						</th>
						<td>
							<?php

							wp_dropdown_pages( array(
								'name'             => 'pronamic_donations_gravity_forms_page_id',
								'selected'         => get_option( 'pronamic_donations_gravity_forms_page_id' ),
								'show_option_none' => __( '&mdash; Select a page &mdash;', 'pronamic_donations' )
							) );

							?>
							
							<p class="description">
								<?php _e( 'Choose a page which contains the donation form to link to from widgets and shortcode.', 'pronamic_donations' ); ?>
							</p>
						</td>
					</tr>

					<?php

					if ( class_exists( 'RGFormsModel' ) ) :
						$forms = RGFormsModel::get_forms();

						?>

						<tr valign="top">
							<th scope="row">
								<label for="pronamic_donations_gravity_form_id"><?php _e( 'Gravity Forms Widget Form', 'pronamic_donations' ); ?></label>
							</th>
							<td>
								<select name="pronamic_donations_gravity_form_id" id="pronamic_donations_gravity_form_id">
									<option value=""><?php _e( '&mdash; Select a form &mdash;', 'pronamic_donations' ); ?></option>
		
									<?php foreach ( $forms as $form ) : ?>
		
										<option value="<?php echo $form->id; ?>" <?php selected( get_option( 'pronamic_donations_gravity_form_id' ), $form->id ); ?>>
											<?php echo $form->title; ?>
										</option>
										
									<?php endforeach; ?>
								</select>
							
								<p class="description">
									<?php _e( "Choose a form to embed in the 'Donations' widget, if set to 'Show in widget'.", 'pronamic_donations' ); ?>
								</p>
							</td>
						</tr>
					
					<?php else : ?>

						<?php _e( 'It looks like Gravity Forms is not active. Please activate it.', 'pronamic_donations' ); ?>

					<?php endif; ?>

					<?php

					if ( class_exists( 'RGFormsModel' ) ) :
						$forms = RGFormsModel::get_forms();

						?>

						<tr valign="top">
							<th scope="row">
								<label for="pronamic_donations_gravity_form_ids"><?php _e( 'Gravity Forms forms', 'pronamic_donations' ); ?></label>
							</th>
							<td>
								<?php

								$active = get_option( 'pronamic_donations_gravity_form_ids' );

								$active = is_array( $active ) ? $active : array();

								?>

								<?php foreach ( $forms as $form ) : ?>

									<div>
										<label for="pronamic_donations_gravity_form_ids_<?php echo esc_attr( $form->id ); ?>">
											<input type="checkbox" name="pronamic_donations_gravity_form_ids[]" id="pronamic_donations_gravity_form_ids_<?php echo esc_attr( $form->id ); ?>" value="<?php echo esc_attr( $form->id ); ?>" <?php checked( in_array( $form->id, $active ) ); ?>>
											<?php echo esc_html( $form->title ); ?>
										</label>
									</div>

								<?php endforeach; ?>

								<p>
									<?php _e( 'Enable forms to process payments for form entries as donations.', 'pronamic_donations' ); ?>
								</p>
							</td>
						</tr>
					
					<?php else : ?>

						<?php _e( 'It looks like Gravity Forms is not active. Please activate it.', 'pronamic_donations' ); ?>

					<?php endif; ?>
				</table>
			
			<?php endif; ?>

			<?php submit_button(); ?>
		</form>
	</div>

	<?php
}
