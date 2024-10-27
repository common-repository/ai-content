<?php
$modal_html_bulk_delete = '<div class="modal custom-modal fade" id="delete_bulk_fc_record" tabindex="-1" role="dialog" aria-labelledby="delete_bulk_fc_record" aria-hidden="true">
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-body">
			<img src="' . $this->fc_get_plugin_url() . '/assets/images/delete-icon.png" alt="delete-icon">
			<h4 class="modal-custom-heading">' . esc_html__( 'Confirmation', 'wp-google-map-plugin' ) . '</h4>
			<p class="modal_delete_msg">' . esc_html__( 'Do you really want to delete these selected records ? This process cannot be undone.', $this->pluginPrefix ) . '</p>
			 
		</div>
		<div class="modal-footer select-some">
		<button type="button" class="fc-btn select-some-btn" data-dismiss="modal">' . esc_html__( 'Ok', $this->pluginPrefix ) . '</button>
		</div>
		<div class="modal-footer delete">
			<button type="button" class="fc-btn cancel-btn" data-dismiss="modal">' . esc_html__( 'Close', $this->pluginPrefix ) . '</button>
			<a class="fc-btn fc-btn-submit fc-btn-big bulk-delete-btn" href="javascript:void(0);" type="button" class="fc-btn fc-btn-submit">' . esc_html__( 'Delete', $this->pluginPrefix ) . '</a>
	   </div>
	</div>
</div>
</div>';
echo wp_kses_post( $modal_html_bulk_delete );
