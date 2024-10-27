<?php

if ( isset( $_REQUEST['_wpnonce'] ) ) {

	$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

		die( 'Cheating...' );

	} else {
		$data = $_POST;
	}
}
global $wpdb;


$modelFactory = new WAI_Model();

$location_obj = $modelFactory->create_object( 'prompt' );

if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['id'] ) ) {
	$location_obj = $location_obj->fetch( array( array( 'id', '=', intval( wp_unslash( $_GET['id'] ) ) ) ) );
	$data         = (array) $location_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}

$form = new WAI_FORM();
$form->set_header( esc_html__( 'Prompt', 'text-prompter' ), $response, $accordion = true );
$form->add_element(
	'group',
	'wai_settings',
	array(
		'value'  => esc_html__( 'Prompt Settings', 'text-prompter' ),
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);

$form->add_element(
	'text',
	'title',
	array(
		'label'  => esc_html__( ' Title', 'text-prompter' ),
		'value'  => isset( $data['title'] ) ? $data['title'] : '',
		'class'  => 'form-control',
		'desc'   => esc_html__( 'Menu title to access prompt. This will be used to access the prompt.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);

$desc = sprintf( esc_html__( 'Write your text prompt here. The model will generate a text completion to match whatever context or pattern you write here. %s', 'text-prompter' ), '<a href ="https://beta.openai.com/docs/guides/completion/prompt-design" target="_blank" >' . esc_html__( 'Prompts Guide', 'text-prompter' ) . '</a>' );


$form->add_element(
	'textarea',
	'prompts',
	array(
		'label'         => esc_html__( 'Text Prompt', 'text-prompter' ),
		'value'         => ( isset( $data['prompts'] ) and ! empty( $data['prompts'] ) ) ? $data['prompts'] : '',
		'textarea_rows' => 10,
		'textarea_name' => 'prompts',
		'class'         => 'form-control',
		'desc'          => $desc,
		'placeholder'   => 'Correct this to standard English',
	)
);


$form->add_element(
	'text',
	'words',
	array(
		'label'  => esc_html__( ' Words', 'text-prompter' ),
		'value'  => isset( $data['words'] ) ? $data['words'] : 400,
		'class'  => 'form-control',
		'desc'   => esc_html__( 'Write here how many words to be generated. max 2000 words.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);

$form->add_element(
	'group',
	'wai_advance_settings',
	array(
		'value'  => esc_html__( 'Advanced Settings', 'text-prompter' ),
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
	)
);


$desc = sprintf( esc_html__( 'Read %1$s to setup below settings to get better result. Use %2$s tool to try with these settings. You may leave these settings unchanged as we used the best default settings.', 'text-prompter' ), '<a href ="https://towardsdatascience.com/gpt-3-parameters-and-prompt-design-1a595dc5b405" target="_blank" >' . esc_html__( 'Parameters Guide', 'text-prompter' ) . '</a>', '<a href ="https://beta.openai.com/playground" target="_blank" >' . esc_html__( 'OpenAI Playground', 'text-prompter' ) . '</a>' );

$form->add_element(
	'html',
	'wai_notes',
	array(
		'html'   => $desc,
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
		'class'  => 'fc-msg fc-msg-info',
	)
);

$models = array(
	'gpt-4o'      => 'gpt-4o',
	'gpt-4-turbo'        => 'gpt-4-turbo',
	'gpt-4' => 'gpt-4',
	'gpt-3.5-turbo'      => 'gpt-3.5-turbo',
);

$desc = sprintf( esc_html__( 'Read %s to find which model is best suitable for your text prompt. Default is gpt-4.', 'text-prompter' ), '<a href ="https://beta.openai.com/docs/models/overview" target="_blank" >' . esc_html__( 'Models Overview', 'text-prompter' ) . '</a>' );

$form->add_element(
	'select',
	'model',
	array(
		'label'   => esc_html__( 'Choose Model/Engine', 'text-prompter' ),
		'current' => isset( $data['model'] ) ? $data['model'] : 'gpt-4',
		'desc'    => $desc,
		'options' => $models,
		'before'  => '<div class="fc-4">',
		'after'   => '</div>',
		'id'      => 'wai_model',
	)
);

$form->add_element(
	'text',
	'temperature',
	array(
		'label'  => esc_html__( ' Temperature', 'text-prompter' ),
		'value'  => isset( $data['temperature'] ) ? $data['temperature'] : .7,
		'class'  => 'form-control',
		'desc'   => esc_html__( 'A number between 0 and 1. This controls the randomness and creativity of the model’s predictions.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);

$form->add_element(
	'text',
	'top_p',
	array(
		'label'  => esc_html__( ' Top p', 'text-prompter' ),
		'value'  => isset( $data['top_p'] ) ? $data['top_p'] : 1,
		'class'  => 'form-control',
		'desc'   => esc_html__( 'A number between 0 and 1. This controls the randomness and originality of the model.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);

$form->add_element(
	'text',
	'frequency_penalty',
	array(
		'label'  => esc_html__( ' Frequency penalty', 'text-prompter' ),
		'value'  => isset( $data['frequency_penalty'] ) ? $data['frequency_penalty'] : 0,
		'class'  => 'form-control',
		'desc'   => esc_html__( 'A number between 0 and 1. This controls the model’s tendency to repeat predictions.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);

$form->add_element(
	'text',
	'presence_penalty',
	array(
		'label'  => esc_html__( ' Presence penalty', 'text-prompter' ),
		'value'  => isset( $data['presence_penalty'] ) ? $data['presence_penalty'] : 0,
		'class'  => 'form-control',
		'desc'   => esc_html__( ' A number between 0 to 1. The presence penalty parameter encourages the model to make novel predictions.', 'text-prompter' ),
		'before' => '<div class="fc-4" >',
		'after'  => '</div>',
	)
);



$form->add_element(
	'submit',
	'wai_save_settings',
	array(
		'value' => esc_html__( 'Save', 'text-prompter' ),
	)
);
$form->add_element(
	'hidden',
	'operation',
	array(
		'value' => 'save',
	)
);

if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {

	$form->add_element(
		'hidden',
		'entityID',
		array(
			'value' => intval( wp_unslash( $_GET['id'] ) ),
		)
	);
}

$form->render();
