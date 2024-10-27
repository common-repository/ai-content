<?php
/*
Plugin Name: Text Prompter
Plugin URI: https://www.flippercode.com/
Description:  Unlimited text prompts for OpenAI tasks.
Author: flippercode
Author URI: https://www.flippercode.com/
Version: 1.0.7
Text Domain: text-prompter
Domain Path: /lang/
*/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


if ( ! class_exists( 'FC_Plugin_Base' ) ) {

	$pluginClass = plugin_dir_path( __FILE__ ) . '/core/class.plugin.php';
	if ( file_exists( $pluginClass ) ) {
		include $pluginClass;
	}
}

if ( ! class_exists( 'WP_AI_KIT' ) && class_exists( 'FC_Plugin_Base' ) ) {

	/**
	 * Main plugin class
	 *
	 * @author Flipper Code <hello@flippercode.com>
	 * @package WP Security Question
	 */
	class WP_AI_KIT extends FC_Plugin_Base {

		var $pluginPrefix = '';

		/**
		 * Intialize variables, files and call actions.
		 *
		 * @var array
		 */
		public function __construct() {

			parent::__construct( $this->wai_plugin_definition() );
			$this->wai_register_hooks();

		}

		function wai_plugin_definition() {

			$this->pluginPrefix       = 'wai';
			$pluginClasses            = array( 'wai-form.php', 'wai-controller.php', 'wai-model.php', 'wai-fresh-settings.php' );
			$pluginModules            = array( 'overview', 'prompt', 'settings' );
			$pluginCssFilesFrontEnd   = array();
			$pluginCssFilesBackendEnd = array( 'flippercode-ui.css' );
			$pluginJsFilesFrontEnd    = array( 'frontend.js' );
			$pluginJsFilesBackEnd     = array( 'backend.js', 'select2.js', 'flippercode-ui.js' );
			$pluginData               = array(
				'childFileRefrence'       => __FILE__,
				'childClassRefrence'      => __CLASS__,
				'pluginPrefix'            => 'wai',
				'pluginDirectory'         => plugin_dir_path( __FILE__ ),
				'pluginTextDomain'        => 'text-prompter',
				'pluginURL'               => plugin_dir_url( __FILE__ ),
				'dboptions'               => 'aicontent_settings',
				'controller'              => 'WAI_Controller',
				'model'                   => 'WAI_Model',
				'pluginLabel'             => esc_html__( 'Text Prompter', 'text-prompter' ),
				'pluginClasses'           => $pluginClasses,
				'pluginmodules'           => $pluginModules,
				'pluginmodulesprefix'     => 'WAI_Model_',
				'pluginCssFilesFrontEnd'  => $pluginCssFilesFrontEnd,
				'pluginCssFilesBackEnd'   => $pluginCssFilesBackendEnd,
				'pluginJsFilesFrontEnd'   => $pluginJsFilesFrontEnd,
				'pluginJsFilesBackEnd'    => $pluginJsFilesBackEnd,
				'loadCustomizer'          => false,
				'pluginDirectoryBaseName' => basename( dirname( __FILE__ ) ),
				'settingsPageSlug'        => 'wai_manage_settings',
				'plugin_row_links'        => array( 'Docs' => 'http://guide.flippercode.com/securityquestions/' ),
			);

			return $pluginData;
		}

		function wai_register_hooks() {

			if ( is_admin() ) {
				add_action( 'fc_plugin_module_to_load', array( $this, 'wai_plugin_module_to_load' ) );

				add_action( 'wpgmp_form_header_html', [ $this, 'wai_add_custom_loader' ] );

				add_action( 'admin_init', [ $this, 'aicontent_export_data' ] );
				add_action( 'admin_init', [ $this, 'wai_sample_csv_download' ] );

			}
			// don't
			add_action(
				'rest_api_init',
				function () {
					register_rest_route(
						'aicontent/v1',
						'/text',
						array(
							'methods'             => WP_REST_Server::EDITABLE,
							'callback'            => array( $this, 'aicontent_rest_openai_autocomplete' ),
							'permission_callback' => function () {
									return current_user_can( 'edit_others_posts' );
							},
						)
					);
				}
			);
			// don't
			add_action( 'enqueue_block_editor_assets', array( $this, 'aicontent_block_assets' ) );
			add_action( 'plugins_loaded', array( $this, 'wai_load_plugin_languages' ) );

			add_shortcode( 'text_prompter', array($this,'wai_prompt_shortcode') );

			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

		}

		//dont
		function wai_prompt_shortcode($atts,$content) {
			$defaults = array(
			  'model' => 'text-davinci-002',
			  'temperature' => 0.5,
			  'max_tokens' => 50,
			  'transist' => 86400,
			  'attributes' => ''
			);
		  
			$args = shortcode_atts($defaults, $atts);
		  
			// Construct the request data
			$data = array(
			  'model' => $args['model'],
			  'prompt' => $content,
			  'temperature' => $args['temperature'],
			  'max_tokens' => $args['max_tokens'],
			  'transist' => $args['transist'],
			);
		  
			// Check if there is an existing cache for this prompt
			$transist = intval($data['transist']);
			$cache_key = md5(json_encode($data));
			$cache_value = get_transient($cache_key);
		  	// If there is no cache, make the API request and cache the result
			if (!$cache_value) {
				$options = [
					'model'             => $data['model'],
					'prompt'            => $data['prompt'],
					'temperature'       => floatVal( $data['temperature'] ) ?? .7,
					'max_tokens'        => intval($data['max_tokens']),
				];	

			  $response = $this->aicontent_call_openai( $options ); // replace with your function that generates the OpenAI response
			  $response = $response->data;
			  $cache_value = $response['message'];
			  if( $response['error'] === false) {
			  	set_transient($cache_key, $cache_value, $transist);	
			  }	
			}
		  
			// Add any additional attributes to the output
			$attributes = $args['attributes'];
		  
			// Return the result with any specified attributes
			return '<div ' . $attributes . '>' . $cache_value . '</div>';
		  }

		// dont
		function wai_sample_csv_download() {

			if ( ! empty( $_GET['do_action'] ) && $_GET['do_action'] == 'sample_csv_download' ) {

				if ( isset( $_GET['sample_csv_download_nonce'] ) && wp_verify_nonce( $_GET['sample_csv_download_nonce'], 'sample_csv_download_action' ) ) {

					$sample_zip = WAI_DIR . 'assets/sample.csv';
					header( 'Content-Type: application/csv' );
					header( 'Content-Disposition: attachment; filename=sample.csv' );
					header( 'Pragma: no-cache' );
					header( 'Expires: 0' );
					readfile( $sample_zip );
					exit();

				} else {

					die( __( 'Something went wrong with the requested action. Please refresh page and try again.', 'text-prompter' ) );

				}
			}

		}
		// dont
		/**
		 * Export data into csv,xml,json or excel file
		 */
		function aicontent_export_data() {

			if ( isset( $_POST['action'] ) && isset( $_REQUEST['_wpnonce'] ) && $_POST['action'] == 'export_prompt_csv' ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );
				if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
					die( 'Cheating...' );
				}

				if ( isset( $_POST['action'] ) and false != strstr( $_POST['action'], 'export_' ) ) {
					$export_action = explode( '_', sanitize_text_field( $_POST['action'] ) );
					if ( 3 == count( $export_action ) and 'export' == $export_action[0] ) {
						$model_class = 'WAI_Model_' . ucwords( $export_action[1] );
						$entity      = new $model_class();
						$entity->export( $export_action[2] );
					}
				}
			}

		}


		// dont
		function aicontent_block_assets( $hook ) {

			$settings = get_option( 'wai_settings' );
			$language = $settings['wai_language'] ?? 'en';

			// Create any data in PHP that we may need to use in our JS file
			$nonce        = wp_create_nonce( 'wp_rest' );
			$modelFactory = new WAI_Model();
			$prompt_obj   = $modelFactory->create_object( 'prompt' );

			$all_prompts = $prompt_obj->fetch();

			$models = [];

			foreach ( $all_prompts as $prompt ) {
				$models[ $prompt->id ] = $prompt->title;
			}

			$aiKitScriptVars = array(
				'nonce'            => $nonce,
				'rest_nonce'       => wp_create_nonce( 'wp_rest' ),
				'siteUrl'          => get_site_url(),
				'selectedLanguage' => $language,
				'prompts'          => $models,
			);

			wp_enqueue_script(
				'aicontent-block-script',
				plugins_url( 'build/index.js', __FILE__ ),
				[ 'wp-edit-post' ]
			);

			wp_add_inline_script( 'aicontent-block-script', 'var aicontent =' . json_encode( $aiKitScriptVars ) );

		}


		// dont
		function aicontent_rest_openai_autocomplete( $data ) {

			set_time_limit( 0 );
			if ( ! isset( $data['text'] ) ) {
				return new WP_Error( 'missing_param', 'Missing reference text.', array( 'status' => 400 ) );
			}

			if ( ! isset( $data['type'] ) ) {
				return new WP_Error( 'missing_param', 'Missing Text Prompt.', array( 'status' => 400 ) );
			}

			$modelFactory = new WAI_Model();

			$location_obj = $modelFactory->create_object( 'prompt' );

			if ( isset( $data['type'] ) ) {
				$location_obj  = $location_obj->fetch( array( array( 'id', '=', intval( wp_unslash( $data['type'] ) ) ) ) );
				$prompt_detail = $location_obj[0];
				$language      = $data['language'] ?? 'en';
				$settings      = get_option( 'wai_settings' );
				$model         = $data['model'] ?? $prompt_detail->model;
				#$prompt        = str_replace( '[text_placeholder]', $data['text'], $prompt_detail->prompts );
				$prompt        = $prompt_detail->prompts.":".$data['text'];
				$max_tokens    = intval( $prompt_detail->words * 1.33 ) ?? 100;

				$options = [
					'model'             => $model,
					'prompt'            => $prompt,
					'temperature'       => floatVal( $prompt_detail->temperature ) ?? .7,
					'max_tokens'        => $max_tokens,
					'top_p'             => floatVal( $prompt_detail->top_p ) ?? 1,
					'frequency_penalty' => floatVal( $prompt_detail->frequency_penalty ) ?? 0,
					'presence_penalty'  => floatVal( $prompt_detail->presence_penalty ) ?? 0,
				];

				return $this->aicontent_call_openai( $options );

			} else {
				return new WP_Error( 'missing_param', 'Missing Text Prompt.', array( 'status' => 400 ) );
			}

		}

		// dont
		function aicontent_call_openai( $options ) {

			$settings = get_option( 'wai_settings' );

			$options['messages'] = [
				[
					'role' => 'user',
					'content' => [
						array('type' => 'text',
						'text' => $options['prompt'])
					]
				]
					];
			
			unset($options['prompt']);

			try {

				$res = wp_remote_post(
					'https://api.openai.com/v1/chat/completions',
					array(
						'body'    => json_encode( $options ),
						'headers' => array(
							'Authorization' => 'Bearer ' . $settings['wai_openai_key'],
							'Content-Type'  => 'application/json',
						),
						'timeout' => 45,
					)
				);

				
				if ( is_wp_error( $res ) ) {

					$result = array(
						'error'   => true,
						'message' => $res->get_error_message(),
					);

				} else {

					$response = json_decode( $res['body'] );

					if ( isset($response->error) ) {

						$result = array(
							'error'   => true,
							'message' => $response->error->message,
						);

					} else {

						$choices = $response->choices;
						if ( count( $choices ) == 0 ) {
							$result = array(
								'error'   => true,
								'message' => 'No result. Please try again using different text or check the prompt.',
							);
						} else {

							$message = nl2br( trim( $choices[0]->message->content ) );
							$result  = array(
								'error'   => false,
								'message' => $message,
							);

						}
					}
				}
			} catch ( Exception $e ) {

				$result = array(
					'error'   => false,
					'message' => $e->getMessage(),
				);

			}

			return new WP_REST_Response( $result, 200 );

		}

		function wai_add_custom_loader( $form_container_html ) {

			if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'wai' ) !== false ) {
				$form_container_html = $form_container_html . '<div class="fc-backend-loader" style="display:none;"><img src="' . WAI_IMAGES . '\Preloader_3.gif"></div>';
			}

			return $form_container_html;

		}

		function wai_plugin_module_to_load( $module ) {

			if ( $this->fc_is_backend_plugin_page() ) {

				$data = maybe_unserialize( get_option( 'wpr_security_ques_setting' ) );
				if ( ! isset( $data ) || ! isset( $data['wai_enabled'] ) ) {
					$module = 'debug';
				}
			}
			return $module;
		}

		/**
		 * Create backend navigation.
		 */
		function wai_define_admin_menu() {

			$pagehook = add_menu_page(
				esc_html__( 'Text Prompter', 'text-prompter' ),
				esc_html__( 'Text Prompter', 'text-prompter' ),
				'wai_admin_overview',
				WAI_SLUG,
				array( $this, 'fc_plugin_processor' ),
				WAI_IMAGES . 'fc-small-logo.png'
			);

			return $pagehook;

		}


		/**
		 * Eneque scripts in the backend.
		 */
		function wai_backend_script_localisation() {

			$wai_js_lang               = array();
			$wai_js_lang['site_url']   = get_site_url();
			$wai_js_lang['ajax_url']   = admin_url( 'admin-ajax.php' );
			$wai_js_lang['nonce']      = wp_create_nonce( 'wpgmp-nonce' );
			$wai_js_lang['rest_nonce'] = wp_create_nonce( 'wp_rest' );
			$wai_js_lang['confirm']    = esc_html__(
				'Are you sure to delete item?',
				'text-prompter'
			);
			$wai_js_lang['cancel_msg'] = esc_html__(
				'Are you sure to cancel import process?',
				'text-prompter'
			);

			wp_localize_script( 'backend.js', 'wai_js_lang', $wai_js_lang );

			$core_script_args = apply_filters(
				'fc_ui_script_args',
				array(
					'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'language'   => 'en',
					'urlforajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'hide'       => esc_html__( 'Hide', 'text-prompter' ),
					'nonce'      => wp_create_nonce( 'fc_communication' ),
				)
			);
			wp_localize_script( 'flippercode-ui.js', 'fc_ui_obj', $core_script_args );

		}

		/**
		 * Load plugin language file.
		 */
		function wai_load_plugin_languages() {

			load_plugin_textdomain( 'text-prompter', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Perform tasks on plugin deactivation.
		 */
		function wai_plugin_activation_work() {

			if ( false == get_option( 'wai_settings' ) ) {

				// To Do
			}

		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function wai_define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Define all plugin constants.
		 */
		protected function wai_define_constants() {

			$this->wai_define( 'WAI_SLUG', 'wai_view_overview' );
			$this->wai_define( 'WAI_VERSION', '1.0.1' );
			$this->wai_define( 'WAI_TEXT_DOMAIN', 'text-prompter' );
			$this->wai_define( 'WAI_TBL_PROMPTS', 'wai_prompts' );
			$this->wai_define( 'WAI_FOLDER', basename( dirname( __FILE__ ) ) );
			$this->wai_define( 'WAI_DIR', plugin_dir_path( __FILE__ ) );
			$this->wai_define( 'WAI_CORE_CLASSES', WAI_DIR . 'core/' );
			$this->wai_define( 'WAI_PLUGIN_CLASSES', WAI_DIR . 'classes/' );
			$this->wai_define( 'WAI_CONTROLLER', WAI_CORE_CLASSES );
			$this->wai_define( 'WAI_Model', WAI_DIR . 'modules/' );
			$this->wai_define( 'WAI_URL', plugin_dir_url( WAI_FOLDER ) . WAI_FOLDER . '/' );
			$this->wai_define( 'WAI_CSS', WAI_URL . '/assets/css/' );
			$this->wai_define( 'WAI_JS', WAI_URL . '/assets/js/' );
			$this->wai_define( 'WAI_IMAGES', WAI_URL . '/assets/images/' );

		}

	}
}

new WP_AI_KIT();
