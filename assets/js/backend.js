jQuery( document ).ready(
	function($) {

		var wpp_image_id = '';

		$( 'span.delete a' ).click(
			function() {

				if (confirm( wpp_js_lang.confirm )) {
					return true;
				}

				return false;

			}
		);

		$( '.switch_onoff' ).change(
			function() {
				var target = $( this ).data( 'target' );
				if ($( this ).attr( 'type' ) == 'radio') {
					$( target ).closest( '.form-group' ).hide();
					target += '_' + $( this ).val();
				}
				if ($( this ).is( ":checked" )) {
					$( target ).closest( '.form-group' ).show();
				} else {
					$( target ).closest( '.form-group' ).hide();
					if ($( target ).hasClass( 'switch_onoff' )) {
						$( target ).attr( 'checked', false );
						$( target ).trigger( "change" );
					}
				}

			}
		);

		$.each(
			$( '.switch_onoff' ),
			function(index, element) {
				if (true == $( this ).is( ":checked" )) {
					$( this ).trigger( "change" );
				}

			}
		);

		let fc_test_form = $( '.wai_test_form' );

		$( ".cancel_import" ).click(
			function() {
				var wpgmp_bid = confirm( wpp_js_lang.cancel_msg );
				if (wpgmp_bid == true) {
					$( this ).closest( "form" ).find( "input[name='operation']" ).val( "cancel_import" );
					$( this ).closest( "form" ).submit();
					return true;
				} else {
					return false;
				}
			}
		);

		fc_test_form.on(
			'submit',
			function(e) {

				e.preventDefault();

				$( '.fc-error-msg' ).remove();

				let formerror = false;

				if ( ! formerror) {

					$( '.fc-backend-loader' ).show();

					let ajaxurl    = wai_js_lang.ajax_url;
					let rest_nonce = wai_js_lang.rest_nonce;

					jQuery.ajax(
						{
							type: 'post',
							dataType: 'json',
							url: wai_js_lang.site_url + '/wp-json/aicontent/v1/text',
							headers: {
								'X-WP-Nonce': rest_nonce,
							},
							data: { 'model': $( "#wai_model" ).val(), 'text': $( "#wai_test_text" ).val(), 'type': $( "#wai_test_task" ).val() },
						}
					)

						.done( function(data) { $( '.fc-backend-loader' ).hide(); } )

						.fail(
							function(reason) {

								if ( reason.responseJSON.code) {
									$( ".wai_samples" ).html( reason.responseJSON.message );
									$( ".wai_samples" ).removeClass( "fc-msg-info" ).addClass( 'fc-danger' );
								} else if (reason.responseJSON) {
									$( ".wai_samples" ).html( JSON.parse( reason.responseJSON.message ).message );
									$( ".wai_samples" ).removeClass( "fc-msg-info" ).addClass( 'fc-danger' );
								}

								$( '.fc-backend-loader' ).hide();
							}
						)

						.then(
							function(data) {

								if (data.error == true) {

									$( ".wai_samples" ).html( data.message );
									$( ".wai_samples" ).removeClass( "fc-msg-info" ).addClass( 'fc-danger' );

								} else {
									$( ".wai_samples" ).html( data.message );
									$( ".wai_samples" ).removeClass( "fc-danger" ).addClass( 'fc-msg-info' );
								}

							}
						);

				}

				return false;

			}
		);
	}
);
