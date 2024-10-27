<?php

$data         = get_option( 'wai_settings' );
$modelFactory = new WAI_Model();
$prompt_obj   = $modelFactory->create_object( 'prompt' );

$all_prompts = $prompt_obj->fetch();

$prompts = [];

foreach ( $all_prompts as $prompt ) {
	$prompts[ $prompt->id ] = $prompt->title;
}
$form             = new WAI_FORM();
$form->form_id    = 'wai_test_form';
$form->form_class = 'wai_test_form';
$form->set_header( esc_html__( 'OpenAI Testing', 'text-prompter' ), $response, $accordion = false );

$form->add_element(
	'group',
	'wai_testing',
	array(
		'value'  => esc_html__( 'OpenAI Testing', 'text-prompter' ),
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);

$description = esc_html__( 'After setting up your OpenAI API key and prompts, you can test the prompt output here.', 'text-prompter' );


$form->add_element(
	'html',
	'wai_notes',
	array(
		'html'   => $description,
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
		'class'  => 'fc-msg fc-msg-info',
	)
);

$form->add_element(
	'html',
	'wai_error',
	array(
		'html'   => '',
		'before' => '<div class="fc-8">',
		'after'  => '</div>',
		'class'  => ' wai_error fc-msg fc-danger',
		'show'   => 'false',
	)
);

$models = array(
	'gpt-4o'      => 'gpt-4o',
	'gpt-4-turbo'        => 'gpt-4-turbo',
	'gpt-4' => 'gpt-4',
	'gpt-3.5-turbo'      => 'gpt-3.5-turbo',
);

$desc = sprintf( esc_html__( 'Read %s to find which model is best suitable for your text prompt.', 'text-prompter' ), '<a href ="https://beta.openai.com/docs/models/overview" target="_blank" >' . esc_html__( 'Models Overview', 'text-prompter' ) . '</a>' );

$form->add_element(
	'select',
	'wai_model',
	array(
		'label'   => esc_html__( 'Choose Model', 'text-prompter' ),
		'current' => 'gpt-4o',
		'desc'    => $desc,
		'options' => $models,
		'before'  => '<div class="fc-4">',
		'after'   => '</div>',
		'id'      => 'wai_model',
	)
);

$form->add_element(
	'select',
	'wai_test_task',
	array(
		'label'   => esc_html__( 'Choose Prompt', 'text-prompter' ),
		'desc'    => esc_html__( 'The selected prompt will be used to perform task.', 'text-prompter' ),
		'options' => $prompts,
		'before'  => '<div class="fc-4">',
		'after'   => '</div>',
		'id'      => 'wai_test_task',
	)
);


$form->add_element(
	'textarea',
	'wai_test_text',
	array(
		'label'  => esc_html__( ' Enter Text', 'text-prompter' ),
		'value'  => isset( $data['wai_test_text'] ) ? $data['wai_test_text'] : esc_html__( 'How to Write Quality Contents', 'text-prompter' ),
		'desc'   => esc_html__( 'Enter few words to try selected prompt.', 'text-prompter' ),
		'id'     => 'wai_test_text',
		'class'  => 'form-control',
		'before' => '<div class="fc-8" >',
		'after'  => '</div>',
	)
);

$desc = sprintf( esc_html__( 'This text will be replaced with OpenAI output.' ) );

$form->add_element(
	'html',
	'wai_samples',
	array(
		'html'   => $desc,
		'before' => '<div class="fc-8">',
		'after'  => '</div>',
		'class'  => ' wai_samples fc-msg fc-success wpgmp_api_key_instructions',
	)
);

$form->add_element(
	'submit',
	'wai_tryapi_settings',
	array(
		'value' => esc_html__( 'Try Now', 'text-prompter' ),
	)
);
$form->add_element(
	'hidden',
	'operation',
	array(
		'value' => 'tryapi',
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
