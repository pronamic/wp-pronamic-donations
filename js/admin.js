( function( $ ) {
	var PronamicDonationsSettings = function() {
		var elements     = {};
		elements.formId  = $( '#pronamic_donations_gravity_form_id' );
		elements.formIds = $( 'input[name="pronamic_donations_gravity_form_ids[]"]' );

		elements.formId.on( 'change', function() {
			elements.formIds.filter( ':disabled' ).removeAttr( 'disabled' );

			elements.formIds.filter( '[value="' + $( this ).val() + '"]' ).attr( 'disabled', 'disabled' ).attr( 'checked', 'checked' );
		} );

		elements.formId.trigger( 'change' );
	}

	$( document ).ready( function() {
		var donationsSettings = new PronamicDonationsSettings();
	} );
} )( jQuery );