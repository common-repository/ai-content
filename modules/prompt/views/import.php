<?php
/**
 * Import Prompts(s) Tool.
 *
 * @package aicontent
 * @author Flipper Code <hello@flippercode.com>
 */

$form        = new WAI_FORM();
$current_csv = get_option( 'wai_current_csv' );
$step        = 'step-1';

if ( is_array( $current_csv ) && file_exists( $current_csv['file'] ) ) {
	$step = 'step-2';
}

if ( $step == 'step-1' ) {
	$form->set_header( esc_html__( 'Step 1 - Upload CSV', 'text-prompter' ), $response );
	$form->add_element(
		'group',
		'csv_upload_step_1',
		array(
			'value'  => esc_html__( 'Step 1 - Upload CSV', 'text-prompter' ),
			'before' => '<div class="fc-12">',
			'after'  => '</div>',
		)
	);

} elseif ( $step == 'step-2' ) {
	$form->set_header( esc_html__( 'Step 2 - Columns Mapping', 'text-prompter' ), $response );
	$form->add_element(
		'group',
		'csv_upload_step_2',
		array(
			'value'  => esc_html__( 'Step 2 - Columns Mapping', 'text-prompter' ),
			'before' => '<div class="fc-12">',
			'after'  => '</div>',
		)
	);
}



if ( $step == 'step-1' ) {


	$form->add_element(
		'file',
		'import_file',
		array(
			'label'     => esc_html__( 'Choose File', 'text-prompter' ),
			'file_text' => esc_html__( 'Choose a File', 'text-prompter' ),
			'class'     => 'file_input',
			'desc'      => esc_html__( 'Please upload a valid CSV file containing prompts. You can also download a sample CSV file using the link provided, fill in your own data, and then upload it here.', 'text-prompter' ),
		)
	);

	$download_link = wp_nonce_url( admin_url( 'admin.php?page=wai_import_prompt&do_action=sample_csv_download' ), 'sample_csv_download_action', 'sample_csv_download_nonce' );

	$form->add_element(
		'html',
		'download_sample_file',
		array(
			'label' => esc_html__( 'Download Sample CSV', 'text-prompter' ),
			'id'    => 'download_sample_file',
			'html'  => '<a href="' . $download_link . '">' . __( 'Download pre-built prompts.', 'text-prompter' ) . '</a>',
			'desc'  => esc_html__( 'Click here to download a CSV file containing pre-built prompts. Keep the file structure unchanged, enter your own data, and then upload it using the file upload control above.', 'text-prompter' ),
		)
	);

	$form->add_element(
		'submit',
		'import_loc',
		array(
			'value'     => esc_html__( 'Continue', 'text-prompter' ),
			'no-sticky' => true,

		)
	);

	$form->add_element(
		'html',
		'instruction_html',
		array(
			'html'   => '',
			'before' => '<div class="fc-11">',
			'after'  => '</div>',
		)
	);


	$form->add_element(
		'hidden',
		'operation',
		array(
			'value' => 'prompt_fields',
		)
	);
	$form->add_element(
		'hidden',
		'import',
		array(
			'value' => 'prompt_import',
		)
	);
	$form->render();


} elseif ( $step == 'step-2' ) {

	$importer  = new FlipperCode_Export_Import();
	$file_data = $importer->import( 'csv', $current_csv['file'] );

	$datas = array();

	$csv_columns = array_values( $file_data[0] );

	$extra_fields = array();
	$core_fields  = array(
		''                  => esc_html__( 'Select Field', 'text-prompter' ),
		'title'             => esc_html__( 'Title', 'text-prompter' ),
		'prompts'           => esc_html__( 'Prompt', 'text-prompter' ),
		'words'             => esc_html__( 'Words', 'text-prompter' ),
		'model'             => esc_html__( 'Model/Engine', 'text-prompter' ),
		'temperature'       => esc_html__( 'Temperature', 'text-prompter' ),
		'top_p'             => esc_html__( 'Top P', 'text-prompter' ),
		'frequency_penalty' => esc_html__( 'Frequency Penalty', 'text-prompter' ),
		'presence_penalty'  => esc_html__( 'Presence Penalty', 'text-prompter' ),
	);

	foreach ( $core_fields as $key => $value ) {
		$csv_options[ $key ] = $value;
	}



	$html = '<p class="fc-msg"><b>' . ( count( $file_data ) - 1 ) . '</b> ' . esc_html__( 'records are ready to upload. Please map csv columns below and click on Import button.', 'text-prompter' ) . '. Leave ID field empty if you\'re adding new records. ID field is used to update existing prompt.</p>';

	$html .= '<div class="fc-table-responsive">
 <table class="fc-table">
 <thead><tr><th>CSV Field</th><th>Assign</th></tr></thead>
 <tbody>';

	foreach ( $csv_columns as $key => $value ) {

		if ( isset( $_POST['csv_columns'][ $key ] ) ) {
			$selected = sanitize_text_field( $_POST['csv_columns'][ $key ] );
		} elseif ( array_key_exists( $value, $core_fields ) ) {
			$selected = $value;
		} else {
			$selected = '';
		}


		$html .= '<tr><td>' . $value . '</td><td>' . $form->field_select(
			'csv_columns[' . $key . ']',
			array(
				'options' => $csv_options,
				'current' => $selected,
			)
		) . '</td></tr>';
	}

	$html .= '</tbody></table>';
	$form->add_element(
		'html',
		'instruction_html',
		array(
			'html'   => $html,
			'before' => '<div class="fc-11">',
			'after'  => '</div>',
		)
	);
	$form->add_element(
		'hidden',
		'operation',
		array(
			'value' => 'import_prompt',
		)
	);
	$form->add_element(
		'hidden',
		'import',
		array(
			'value' => 'prompt_import',
		)
	);

	$submit_button = $form->field_submit(
		'import_loc',
		array(
			'value'     => esc_html__( 'Import Prompts', 'text-prompter' ),
			'no-sticky' => true,
			'class'     => 'form-control fc-btn fc-btn-submit fc-btn-big',
		)
	);

	$cancel_button = $form->field_button(
		'cancel_import',
		array(
			'value'     => esc_html__( 'Cancel', 'text-prompter' ),
			'no-sticky' => true,
			'class'     => 'fc-btn fc-danger fc-btn-big cancel_import',
		)
	);


	$html = "<div class='import_prompt'><div>" . $submit_button . '</div><div>' . $cancel_button . '</div></div>';

	$form->add_element(
		'html',
		'button_html',
		array(
			'html'   => $html,
			'before' => '<div class="fc-12">',
			'after'  => '</div>',
		)
	);


	$form->render();

}

