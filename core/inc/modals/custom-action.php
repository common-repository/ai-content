<?php
$modal_html_custom_action = '<div class="modal custom-modal fade modal_for_custom_action" id="modal_for_custom_action" tabindex="-1" role="dialog" aria-labelledby="modal_for_custom_action" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			 <div class="fc-backend-loader" style="display:none;"><img id="wpdf_loader_image" src="' . $this->fc_get_plugin_url() . '/assets/images/Preloader_3.gif"></div>
			<div class="modal-body">
				<img src="' . $this->fc_get_plugin_url() . '/assets/images/sync_icon.png" alt="delete-icon">
				<h4></h4>
				<p class="action-body"></p>
			</div>
			<div class="modal-footer">
			<a class="fc-btn fc-btn-submit fc-btn-big custom-action-yes" data-action="" id="custom-action-yes" data-item-id="" href="#" type="button" class="fc-btn fc-btn-submit"></a>
			<button type="button" class="fc-btn cancel-btn" data-dismiss="modal">' . esc_html__( 'Close', 'wp-display-files' ) . '</button>
			</div>
		</div>
	</div>
</div>';
echo wp_kses_post( $modal_html_custom_action );