<?php
/**
 * WP AIT KIT How To.
 *
 * @package aicontent
 * @author Flipper Code <flippercode>
 **/
$form = new WAI_FORM();
echo wp_kses_post( $form->show_header() );
?>
<div class="flippercode-ui">
	<div class="fc-main">
		<div class="fc-container">
			  <div class="fc-divider">
				  <div class="fc-back fc-how">
				  <div class="row flippercode-main wpgmp-docs">
	<div class="fc-12">
		   
			<h4 class="fc-title-blue"><?php esc_html_e( 'Generate OpenAI API Key', 'text-prompter' ); ?> </h4>
			<div class="fc-overview">

				<?php
				echo sprintf(
					esc_html__( 'To create a new OpenAI API Key, which is required for this plugin, navigate to the %1$s page. Once created, insert the key on the %2$s page.', 'text-prompter' ),
					'<a class="fc-link" href="https://beta.openai.com/account/api-keys" target="_blank">' . esc_html__( 'API Keys', 'text-prompter' ) . '</a>',
					'<a class="fc-link" href="' . admin_url( 'admin.php?page=wai_manage_settings' ) . '" target="_blank">' . esc_html__( 'Settings', 'text-prompter' ) . '</a>'
				);
				?>
					
			 

			</div>

			<h4 class="fc-title-blue"><?php esc_html_e( 'Add Text Prompt', 'text-prompter' ); ?></h4>
			<div class="fc-overview">

				<?php
				echo sprintf(
					esc_html__( 'Navigate to the %1$s page to create a new prompt. A text prompt specifies the task to be performed, and a well-defined prompt will result in better content. For guidance on crafting effective prompts, consult the %2$s.', 'text-prompter' ),
					'<a class="fc-link" href="' . admin_url( 'admin.php?page=wai_form_prompt' ) . '" target="_blank">' . esc_html__( 'Add Prompt', 'text-prompter' ) . '</a>',
					'<a class="fc-link" href="https://beta.openai.com/docs/guides/completion/prompt-design" target="_blank">' . esc_html__( 'Prompts Guide', 'text-prompter' ) . '</a>'
				);
				?>
				 
			</div>

			<h4 class="fc-title-blue"><?php esc_html_e( 'Text Prompts Example', 'text-prompter' ); ?></h4>
			<div class="fc-overview">
				<?php
				echo esc_html__( 'Below are some good references to understand prompt design.', 'text-prompter' );
				?>
			</br>
			<ol style="margin-top:15px;">
			<li>
			<?php
				echo sprintf(
					esc_html__( '%s', 'text-prompter' ),
					'<a class="fc-link" href="https://beta.openai.com/docs/guides/completion/introduction" target="_blank">' . esc_html__( 'how to generate or manipulate text', 'text-prompter' ) . '</a>'
				);
				?>
			</li>

			<li>
			<?php
				echo sprintf(
					esc_html__( '%s', 'text-prompter' ),
					'<a class="fc-link" href="https://towardsdatascience.com/gpt-3-parameters-and-prompt-design-1a595dc5b405" target="_blank">' . esc_html__( 'GPT-3 Parameters and Prompt Design', 'text-prompter' ) . '</a>'
				);
				?>
			</li>
			   
			</ol>
			</div>

			<h4 class="fc-title-blue"><?php esc_html_e( 'Prompt Testing', 'text-prompter' ); ?></h4>
			<div class="fc-overview">

					<?php
					echo sprintf(
						esc_html__( 'Navigate to the %1$s page to evaluate the output of your prompts. You can also use the %2$s tool for further testing.', 'text-prompter' ),
						'<a class="fc-link" href="' . admin_url( 'admin.php?page=wai_test_settings' ) . '" target="_blank">' . esc_html__( 'Testing', 'text-prompter' ) . '</a>',
						'<a href ="https://beta.openai.com/playground" target="_blank" >' . esc_html__( 'OpenAI Playground', 'text-prompter' ) . '</a>'
					);
					?>
			</div>

			<h4 class="fc-title-blue"><?php esc_html_e( 'How to Perform Task', 'text-prompter' ); ?></h4>
			<div class="fc-overview">
				<?php esc_html_e( 'When editing a text block on the Gutenberg editor, this plugin will display a Text Prompts panel. Select the desired AI task and click the Submit button. A new paragraph block will be added to the selected block, displaying the output.', 'text-prompter' ); ?>
				<img src="<?php echo esc_url( WAI_IMAGES . '/gutenberg-editor.png' ); ?>" />

				<?php esc_html_e( 'The Text Prompts Panel will be visible on following block types:', 'text-prompter' ); ?>

				<ol class="fc-list" style="margin-top: 15px;margin-left: 25px;font-weight: bold;">
					<li><?php esc_html_e( 'Paragraph', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'Heading', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'Quote', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'Preformatted', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'Pullquote', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'Verse', 'text-prompter' ); ?></li>
				</ol>
				
			</div>

			<h4 class="fc-title-blue"><?php esc_html_e( 'Shortcode', 'text-prompter' ); ?></h4>
			<div class="fc-overview">
				<p>
				<code>
				[text_prompter model="MODEL_NAME" temperature="TEMPERATURE_VALUE" max_tokens="MAX_TOKENS_VALUE" transist="TRANSIST_VALUE" attributes="ATTRIBUTE_VALUE"]PROMPT_CONTENT[/text_prompter]
				</code>
				</p>
				<?php esc_html_e( 'Shortcode Attributes:', 'text-prompter' ); ?>

				<ul class="fc-list" style="margin-top: 15px;margin-left: 25px;">
					<li><?php esc_html_e( 'model: (optional) Specifies the name or ID of the GPT model to use for text generation. If no model is specified, the default model will be used.', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'temperature: (optional) Controls the "creativity" of the generated text, with higher values producing more diverse and unpredictable responses. Default value is 0.7.', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'max_tokens: (optional) Limits the maximum number of tokens (words) in the generated text. Default value is 2048.', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'transist: (optional) Specifies whether to use transist models for generation. Default value is 86400 seconds.', 'text-prompter' ); ?></li>
					<li><?php esc_html_e( 'attributes: (optional) additional HTML attributes to add to the output <code>&lt;div&gt;</code> element that contains the generated text. This can be used for styling or other purposes.', 'text-prompter' ); ?></li>
				</ol>
				
			</div>

			  <h4 class="fc-title-blue">
				<?php esc_html_e( 'Create Support Ticket', 'text-prompter' ); ?>
			</h4>
		<div class="fc-overview">

			<?php
				echo sprintf(
					esc_html__( 'If you encounter any issues, please feel free to create a %s and our team will assist you as soon as possible.', 'text-prompter' ),
					'<a class="fc-link" href="https://www.flippercode.com/support/" target="_blank">' . esc_html__( 'Support Ticket', 'text-prompter' ) . '</a>'
				);
				?>
		</div>
</div>
</div>
</div>
</div>
</div>
</div>
