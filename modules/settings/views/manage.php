<?php

$data = get_option( 'wai_settings' );

$form = new WAI_FORM();
$form->set_header( esc_html__( 'OpenAI Settings', 'text-prompter' ), $response, $accordion = true );
$form->add_element(
	'group',
	'wai_settings',
	array(
		'value'  => esc_html__( 'OpenAI Settings', 'text-prompter' ),
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);

$desc = sprintf( esc_html__( 'Authentication for the OpenAI API is done using API keys. To obtain an API key, visit the %s page.', 'text-prompter' ), '<a href ="https://beta.openai.com/account/api-keys" target="_blank" >' . esc_html__( 'API Keys', 'text-prompter' ) . '</a>' );

$form->add_element(
	'text',
	'wai_openai_key',
	array(
		'label'       => esc_html__( ' Enter OpenAI API Key', 'text-prompter' ),
		'value'       => isset( $data['wai_openai_key'] ) ? $data['wai_openai_key'] : '',
		'desc'        => $desc,
		'class'       => 'form-control',
		'placeholder' => esc_html__( 'Enter OpenAI API Key', 'text-prompter' ),
		'before'      => '<div class="fc-4" >',
		'after'       => '</div>',
	)
);


$form->add_element(
	'submit',
	'wai_save_settings',
	array(
		'value' => esc_html__( 'Save Setting', 'text-prompter' ),
	)
);
$form->add_element(
	'hidden',
	'operation',
	array(
		'value' => 'save',
	)
);
$form->add_element(
	'hidden',
	'page_options',
	array(
		'value' => 'wai_api_key,wai_scripts_place',
	)
);

$form->render();
