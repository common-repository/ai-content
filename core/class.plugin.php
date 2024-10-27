<?php
/**
 * FlipperCode Plugin Framework Bootstrap File.
 *
 * @package Core
 */
/**
 * FlipperCode Plugin Framework Bootstrap File
 *
 * @package Core
 * @author Flipper Code <hello@flippercode.com>
 * @copyright 2022 flippercode
 *
 * @wordpress-plugin
 * FlipperCode Plugin Class
 * @author FlipperCode <hello@flippercode.com>
 * @package Core
 * Author URL : https://www.flippercode.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if ( ! class_exists( 'FC_Plugin_Base' ) ) {
	/**
	 * Main FC_Plugin_Base class
	 *
	 * @author Flipper Code <hello@flippercode.com>
	 * @package Core
	 */

	class FC_Plugin_Base {
		/**
		 * List of Plugin Base Class Vars.
		 */
		private $productData = array();
		protected $dboptions;
		private $pluginPrefix;
		private $childFileRefrence;
		private $controller;
		private $model;
		private $pluginLabel;
		private $pluginTextDomain;
		private $pluginURL;
		private $pluginDirectory;
		private $pluginClasses;
		private $pluginmodules;
		private $pluginmodulesprefix;
		private $pluginCssFilesFrontEnd;
		private $pluginCssFilesBackEnd;
		private $pluginJsFilesFrontEnd;
		private $pluginJsFilesBackEnd;

		private $fcpluginPage = false;

		private $isManagePage = false;


		protected $isPremium = true;


		protected $modules = array();


		protected $pluginDirectoryBaseName;

		protected $loadTemplateResources = true;
		protected $links_for_plugin;


		protected $dependencyMissing = false;


		protected $loadCustomizer = false;


		protected $pluginErrors = array();


		protected $initializePlugin = true;


		protected $mandatoryMethods = [ 'define_admin_menu' ];
		protected $settingsPageSlug = '';
		protected $plugin_row_links = [];
		/**
		 * Intialize Class Cariables, Register Common Hooks For Plugin.
		 */

		protected function __construct( $pluginData ) {
			$this->pluginPrefix = $pluginData['pluginPrefix'];
			$method_to_check    = $this->pluginPrefix . '_define_constants';
			if ( ! method_exists( $this, $method_to_check ) ) {
				wp_die( 'Please define method ' . $method_to_check . ' in your main class.' );
			}

			$this->fc_check_missing_methods();

			if ( $this->initializePlugin ) {

				$define_constant_method = $this->pluginPrefix . '_define_constants';
				$this->$define_constant_method();
				$this->productData = $pluginData;
				$this->fc_initialise_plugin();
				$this->fc_load_files();
				$this->fc_do_activation_work();
				if ( ! $this->dependencyMissing ) {
					$this->fc_register_default_hooks();
				}
			} else {

				if ( $this->fc_is_backend_plugin_page() ) {
					@wp_die( implode( '<br>', $this->pluginErrors ) );                }
			}

		}

		private function fc_check_missing_methods() {

			foreach ( $this->mandatoryMethods as $method ) {

				$method_to_check = $this->pluginPrefix . '_' . $method;
				if ( ! method_exists( $this, $method_to_check ) ) {

					$this->pluginErrors[] = sprintf( esc_html__( 'Please define method %s() in the main plugin class you are creating.', $this->pluginTextDomain ), $method_to_check );

				}
			}

			if ( ! empty( $this->pluginErrors ) && count( $this->pluginErrors ) > 0 ) {
				$this->initializePlugin = false;
			}

		}

		private function fc_do_activation_work() {

			register_activation_hook( $this->childFileRefrence, array( $this, 'fc_plugin_activation' ) );
			register_deactivation_hook( $this->childFileRefrence, array( $this, 'fc_plugin_deactivation' ) );
		}
		/**
		 * On plugin activation.
		 */
		public function fc_plugin_activation( $network_wide = null ) {
			if ( is_multisite() && $network_wide ) {
				global $wpdb;
				$currentblog = $wpdb->blogid;
				$activated   = array();
				$sql         = "SELECT blog_id FROM {$wpdb->blogs}";
				$blog_ids    = $wpdb->get_col( $wpdb->prepare( $sql, null ) );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					if ( method_exists( $this, 'fc_on_plugin_activation' ) ) {
						$this->fc_on_plugin_activation();
					}
				}
				switch_to_blog( $currentblog );
				update_site_option( $this->pluginPrefix . '_activated', $activated );
			} else {
				if ( method_exists( $this, 'fc_on_plugin_activation' ) ) {
					$this->fc_on_plugin_activation();
				}
			}
		}

		/**
		 * Install plugin tables.
		 */
		public function fc_on_plugin_activation() {
			global $wpdb;
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$modules   = $this->modules;
			$pagehooks = array();
			$tables    = array();
			if ( is_array( $modules ) ) {
				foreach ( $modules as $module ) {
					$object = new $module();
					if ( method_exists( $object, 'install' ) ) {
						$tables[] = $object->install();
					}
				}
			}

			$tables = array_filter( $tables );
			if ( is_array( $tables ) && ! empty( $tables ) ) {
				foreach ( $tables as $i => $sql ) {
					dbDelta( $sql );
				}
			}

			$plugin_activation_function = $this->pluginPrefix . '_plugin_activation_work';
			if ( method_exists( $this, $plugin_activation_function ) ) {
				 $this->$plugin_activation_function();
			}

		}

		/**
		 * On plugin initialization.
		 */
		private function fc_initialise_plugin() {
			$this->fc_set_up_plugin();
			$this->dboptions = get_option( $this->dboptions );
			if ( ! is_array( $this->dboptions ) ) {
				$this->dboptions = unserialize( $this->dboptions );
			}

			if ( $this->fc_is_backend_manage_page() ) {
				$this->isManagePage = true;
			} else {
				$this->isManagePage = false;
			}

		}
		/**
		 * Setup Plugin Definition
		 */
		private function fc_set_up_plugin() {
			foreach ( $this->productData as $property => $propertyValue ) {
				if ( property_exists( $this, $property ) ) {
					$this->$property = $propertyValue;
				}
			}
		}
		/**
		 * Call hook on plugin deactivation for both multi-site and single-site.
		 *
		 * @param  boolean $network_wide IS network activated?.
		 */
		public function fc_plugin_deactivation( $network_wide ) {
			if ( is_multisite() && $network_wide ) {
				global $wpdb;
				$currentblog = $wpdb->blogid;
				$activated   = array();
				$sql         = "SELECT blog_id FROM {$wpdb->blogs}";
				$blog_ids    = $wpdb->get_col( $wpdb->prepare( $sql, null ) );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					if ( method_exists( $this, 'fc_on_plugin_deactivation' ) ) {
						$this->fc_on_plugin_deactivation();
						$activated[] = $blog_id;
					}
				}
				switch_to_blog( $currentblog );
				update_site_option( $this->pluginPrefix . '_deactivated', $activated );
			} else {
				if ( method_exists( $this, 'fc_on_plugin_deactivation' ) ) {
					$this->fc_on_plugin_deactivation();
				}
			}
		}
		/**
		 * Perform tasks on plugin deactivation.
		 */
		public function fc_on_plugin_deactivation() {
			$plugin_deactivation_function = $this->pluginPrefix . 'plugin_deactivation_work';
			if ( method_exists( $this, $plugin_deactivation_function ) ) {
				 $this->$plugin_deactivation_function();
			}
		}

		/**
		 * Perform tasks on plugin deactivation.
		 */
		private function fc_register_default_hooks() {
			add_action( 'wp_enqueue_scripts', array( $this, 'fc_load_plugin_frontend_resources' ) );
			add_action( 'plugins_loaded', array( $this, 'fc_load_plugin_languages' ) );

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'fc_create_plugin_menu' ) );
				add_action( 'admin_init', array( $this, 'fc_process_backend_request' ) );
				add_action( 'admin_head', array( $this, 'fc_remove_unwanted_notifications' ) );
				add_action( 'admin_footer', array( $this, 'fc_add_modals_manage_pages' ) );

			}              if ( ! empty( $this->settingsPageSlug ) ) {
				add_filter( 'plugin_action_links_' . plugin_basename( $this->childFileRefrence ), array( $this, 'fc_add_settings_page_link' ) );
			}                  if ( ! empty( $this->plugin_row_links ) ) {
				add_filter( 'plugin_row_meta', array( $this, 'fc_add_plugin_row_custom_link' ), 10, 2 );
			}
		}
		public function fc_add_plugin_row_custom_link( $links, $file ) {
			if ( strpos( $file, basename( $this->childFileRefrence ) ) ) {
				foreach ( $this->plugin_row_links as $linkname => $link ) {
					$links[] = '<a href="' . $link . '" target="_blank" title="' . $linkname . '">' . $linkname . '</a>';                  }
			}
			return $links;
		}
		public function fc_add_settings_page_link( $links ) {
			 $url           = admin_url() . 'admin.php?page=' . $this->settingsPageSlug;
			 $settings_link = '<a target="_blank" href="' . esc_url( $url ) . '">' . esc_html( 'Settings' ) . '</a>';
			 $links[]       = $settings_link;
			 return $links;
		}
		public function fc_add_modals_manage_pages() {

			if ( $this->isManagePage ) {

				$delete_popup        = $this->productData['pluginDirectory'] . 'core/inc/modals/delete.php';
				$bulk_delete_popup   = $this->productData['pluginDirectory'] . 'core/inc/modals/delete-bulk.php';
				$custom_action_popup = $this->productData['pluginDirectory'] . 'core/inc/modals/custom-action.php';

				if ( file_exists( $delete_popup ) ) {
					require_once $delete_popup;                }
				if ( file_exists( $bulk_delete_popup ) ) {
					require_once $bulk_delete_popup;                }
				if ( file_exists( $custom_action_popup ) ) {
					require_once $custom_action_popup;                }
			}

		}

		protected function fc_is_backend_manage_page() {

			if ( is_admin() && isset( $_GET['page'] ) && ! empty( sanitize_text_field( $_GET['page'] ) ) && ( strpos( sanitize_text_field( $_GET['page'] ), $this->pluginPrefix . '_manage_' ) !== false ) ) {
				return true;
			} else {
				return false;
			}

		}
		protected function fc_is_backend_overview_page() {

			if ( is_admin() && isset( $_GET['page'] ) && ! empty( sanitize_text_field( $_GET['page'] ) ) && ( strpos( sanitize_text_field( $_GET['page'] ), '_overview' ) !== false ) ) {
				return true;
			} else {
				return false;
			}

		}

		protected function fc_is_backend_plugin_page() {

			if ( is_admin() && isset( $_GET['page'] ) && ! empty( sanitize_text_field( $_GET['page'] ) ) && ( strpos( sanitize_text_field( $_GET['page'] ), $this->pluginPrefix ) !== false ) ) {
				return true;
			} else {
				return false;
			}

		}

		protected function fc_get_current_page_capability() {
			$capability_to_check = '';
			if ( wp_doing_ajax() && isset( $_POST['page'] ) && ! empty( $_POST['page'] ) ) {
				$capability_to_check = sanitize_text_field( $_POST['page'] );

			} elseif ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) {
				$capability_to_check = sanitize_text_field( $_GET['page'] );

			} elseif ( isset( $_POST['page'] ) && ! empty( $_POST['page'] ) ) {
				$capability_to_check = sanitize_text_field( $_POST['page'] );
			} else {
			}

			if ( strpos( $capability_to_check, 'overview' ) !== false ) {
				$capability_to_check = str_replace( 'view', 'admin', $capability_to_check );            }
			return $capability_to_check;

		}

		public function fc_process_backend_request() {
			if ( $this->fc_is_backend_plugin_page() ) {
				$this->fcpluginPage = true;
			} else {
				$this->fcpluginPage = false;
			}
			if ( ! current_user_can( 'manage_options' ) && $this->fcpluginPage && ! empty( $_POST ) && isset( $_POST['operation'] ) && ! empty( $_POST['operation'] ) ) {
				$die                 = false;
				$capability_to_check = $this->fc_get_current_page_capability();
				if ( ! current_user_can( $capability_to_check ) ) {
					$die = true;                   }
				if ( $die ) {
					wp_die( 'You are not allowed to save any changes!!' );                }
			}
			if ( $this->fcpluginPage && $_GET['page'] == $this->pluginPrefix . '_form_permissions' && ! empty( $_POST ) && isset( $_POST['operation'] ) && ! empty( $_POST['operation'] ) && $_POST['operation'] == 'save' && current_user_can( 'manage_options' ) ) {
				$this->fc_update_plugin_permissions();
			}
		}

		private function fc_get_core_loading_plugin() {

			return $this->pluginPrefix;
		}


		protected function fc_get_plugin_url() {
			return $this->pluginURL; }

		public function fc_remove_unwanted_notifications() {
			if ( $this->fcpluginPage ) {
				?>	
				<style>
				.update-nag {display:none;}
				.no-js #loader{display: none;}.js #loader{display: block;position:absolute;left:100px;top:0;}
				</style>
				<?php
			}
			?>
						<style>
			.se-pre-con {display:none;position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 999999;
			background: url(<?php echo esc_url( $this->fc_get_plugin_url() . 'assets/images/Preloader_3.gif' ); ?>) center no-repeat #fff;}
			</style> 
			<?php
		}
		/*
		 * Function For Auto Loading Plugin's Current Template Resources @ Frontend.
		*/
		private function fc_load_current_template_style() {
			if ( ! isset( $this->dboptions['default_templates'] ) || empty( $this->dboptions['default_templates'] ) ) {
				return;            }
			$default_templates = $this->dboptions['default_templates'];
			if ( $default_templates ) {
				foreach ( $default_templates as $key => $template ) {
					$cssFile             = 'templates/' . $key . '/' . $template . '/' . $template . '.css';
					$templateCSSFilepath = $this->pluginDirectory . $cssFile;
					$templateCSSFile     = $this->pluginURL . $cssFile;
					if ( ! file_exists( $templateCSSFilepath ) ) {
						$uploads             = wp_upload_dir();
						$templateCSSFilepath = $uploads['basedir'] . '/' . $this->pluginPrefix . '/' . $key . '/' . $template . '/' . $template . '.css';
						$templateCSSFile     = $uploads['baseurl'] . '/' . $this->pluginPrefix . '/' . $key . '/' . $template . '/' . $template . '.css';
					}
					if ( file_exists( $templateCSSFilepath ) ) {
						wp_enqueue_style( $template . 'current-template-css', $templateCSSFile );
					}
					$jsFile             = 'templates/' . $key . '/' . $template . '/' . $template . '.js';
					$templateJsFilepath = $this->pluginDirectory . $jsFile;
					$templateJsFile     = $this->pluginURL . $jsFile;
					if ( ! file_exists( $templateJsFilepath ) ) {
						$uploads            = wp_upload_dir();
						$templateJsFilepath = $uploads['basedir'] . '/' . $this->pluginPrefix . '/' . $key . '/' . $template . '/' . $template . '.js';
						$templateCSSFile    = $uploads['baseurl'] . '/' . $this->pluginPrefix . '/' . $key . '/' . $template . '/' . $template . '.js';
					}
					if ( file_exists( $templateJsFilepath ) ) {
						wp_enqueue_script( $template . 'current-template-js', $templateJsFile );
					}
				}
			}
		}

		/**
		 * Enqueue scripts at frontend.
		 */
		public function fc_load_plugin_frontend_resources() {
			if ( $this->pluginCssFilesFrontEnd ) {
				foreach ( $this->pluginCssFilesFrontEnd as $frontendCSS ) {
					wp_enqueue_style( $frontendCSS, $this->pluginURL . 'assets/css/' . $frontendCSS, array(), date( 'H:i:s' ) );
				}
			}

			if ( $this->loadTemplateResources ) {
				$this->fc_load_current_template_style();            }
			$scripts = array();
			wp_enqueue_script( 'jquery' );
			if ( isset( $this->pluginJsFilesFrontEnd ) && ! empty( $this->pluginJsFilesFrontEnd ) ) {
				foreach ( $this->pluginJsFilesFrontEnd as $js ) {
					$scripts[] = array(
						'handle' => $js,
						'src'    => $this->pluginURL . 'assets/js/' . $js,
						'deps'   => array(),
					);
				}
			}
			$where = apply_filters( $this->pluginPrefix . '_script_position', true );
			if ( $scripts ) {
				foreach ( $scripts as $script ) {
					wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], date( 'H:i:s' ), $where );
				}
			}
			$localisation_method = $this->pluginPrefix . '_frontend_script_localisation';
			if ( method_exists( $this, $localisation_method ) ) {
				 $this->$localisation_method();
			}
		}
		/**
		 * Process slug and display view in the backend.
		 */
		public function fc_plugin_processor() {
			$return = '';
			if ( isset( $_GET['page'] ) ) {
				$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
			} else {
				$page = $this->pluginPrefix . '_view_overview';
			}
			$pageData = explode( '_', $page );
			if ( $this->pluginPrefix != strtolower( $pageData[0] ) ) {
				return;
			}
			$obj_type      = $pageData[2];
			$obj_operation = $pageData[1];
			if ( count( $pageData ) < 3 ) {
				die( 'Cheating!' );
			}
			try {

				if ( count( $pageData ) > 3 ) {
					$obj_type = $pageData[2] . '_' . $pageData[3];
				}
				if ( class_exists( $this->controller ) ) {
					$factoryObject = new $this->controller();
					$viewObject    = $factoryObject->create_object( $obj_type, $factoryObject );
					$viewObject->display( $obj_operation );
				}
			} catch ( Exception $e ) {
				echo FlipperCode_HTML_Markup::show_message( array( 'error' => $e->getMessage() ) );
			}
		}
		/**
		 * Create backend navigation.
		 */
		public function fc_create_plugin_menu() {
			global $navigations;
			$main_menu_method = $this->pluginPrefix . '_define_admin_menu';
			if ( method_exists( $this, $main_menu_method ) ) {
				$pluginBackendPageHook = $this->$main_menu_method();
			}
			if ( current_user_can( 'manage_options' ) ) {
				$role = get_role( 'administrator' );
				$role->add_cap( $this->pluginPrefix . '_admin_overview' );
				$role->add_cap( $this->pluginPrefix . '_form_permissions' );

			}
			$this->fc_load_modules_menu();
			add_action( 'load-' . $pluginBackendPageHook, array( $this, 'fc_load_plugin_backend_resources' ) );
		}
		/**
		 * Read models and create backend navigation.
		 */
		private function fc_load_modules_menu() {
			$modules   = $this->modules;
			$pagehooks = array();
			if ( is_array( $modules ) ) {
				foreach ( $modules as $module ) {
						$object = new $module();
					if ( method_exists( $object, 'navigation' ) ) {
						if ( ! is_array( $object->navigation() ) ) {
							continue;
						}
						foreach ( $object->navigation() as $nav => $title ) {
							$this->links_for_plugin[ $nav ] = $title;
							if ( current_user_can( 'manage_options' ) && is_admin() ) {
								$role = get_role( 'administrator' );
								$role->add_cap( $nav );
							}
							$pagehooks[] = add_submenu_page(
								$this->pluginPrefix . '_view_overview',
								$title,
								$title,
								$nav,
								$nav,
								array( $this, 'fc_plugin_processor' )
							);
						}
					}
				}
			}
			if ( is_array( $pagehooks ) ) {
				foreach ( $pagehooks as $key => $pagehook ) {
					add_action( 'load-' . $pagehooks [ $key ], array( $this, 'fc_load_plugin_backend_resources' ) );
				}
			}

		}
		/**
		 * Eneque scripts in the backend.
		 */
		public function fc_load_plugin_backend_resources() {

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'thickbox' );
			$wp_scripts = array( 'jQuery', 'thickbox', 'wp-color-picker', 'jquery-ui-datepicker', 'jquery-ui-slider' );
			if ( $wp_scripts ) {
				foreach ( $wp_scripts as $wp_script ) {
					wp_enqueue_script( $wp_script );
				}
			}
			if ( $this->isManagePage ) {

				$dep = [ 'jQuery' ];
				wp_enqueue_style( 'fc-manage-page-style', $this->pluginURL . 'assets/css/fc-modal.css' );
				wp_enqueue_script( 'fc-manage-page-script', $this->pluginURL . 'assets/js/fc-modal.js', array(), '', true );

			}
			wp_register_script( 'flippercode-ui.js', $this->pluginURL . 'assets/js/flippercode-ui.js', array(), '', true );
			$current_page = '';
			if ( is_admin() && isset( $_GET['page'] ) && $this->fcpluginPage ) {
				$current_page = sanitize_text_field( $_GET['page'] );
			}
			$core_script_args = apply_filters(
				'fc_ui_script_args',
				array(
					'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'language'   => 'en',
					'urlforajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'hide'       => 'Hide',
					'nonce'      => wp_create_nonce( 'fc_communication' ),
					'page'       => $current_page,
				)
			);
			wp_localize_script( 'flippercode-ui.js', 'fc_ui_obj', $core_script_args );
			wp_enqueue_script( 'flippercode-ui.js' );

			$scripts = array();
			foreach ( $this->pluginJsFilesBackEnd as $js ) {
				$scripts[] = array(
					'handle' => $js,
					'src'    => $this->pluginURL . 'assets/js/' . $js,
					'deps'   => array(),
				);
			}
			if ( $scripts ) {
				foreach ( $scripts as $script ) {
					wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], '', true );
				}
			}
			$backend_script_localisation = $this->pluginPrefix . '_backend_script_localisation';           if ( method_exists( $this, $backend_script_localisation ) ) {
				$this->$backend_script_localisation();
			}
			wp_enqueue_style( 'fc_ui-backend', $this->pluginURL . 'assets/css/flippercode-ui.css' );
			if ( $this->pluginCssFilesBackEnd ) {
				foreach ( $this->pluginCssFilesBackEnd as $backendCSS ) {
					wp_enqueue_style( $backendCSS . '-backend', $this->pluginURL . 'assets/css/' . $backendCSS, array(), date( 'H:i:s' ) );
				}
			}

		}
		/**
		 * Load plugin language file.
		 */
		public function fc_load_plugin_languages() {
			$this->modules = apply_filters( $this->pluginPrefix . '_extensions', $this->modules );
			load_plugin_textdomain( $this->pluginTextDomain, false, $this->pluginDirectoryBaseName . '/lang/' );
		}
		/**
		 * Call hook on plugin activation for both multi-site and single-site.
		 *
		 * @param  boolean $network_wide IS network activated?.
		 */
		private function fc_is_operation_allowed() {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'fc_communication' ) || ! current_user_can( $this->fc_get_current_page_capability() ) ) {
				return false;            }
			return true;
		}
		private function fc_set_default_template() {
			if ( ! $this->fc_is_operation_allowed() ) {
				return;            }
			$response   = array();
			$optionName = sanitize_text_field( $_POST['product'] );
			$data       = get_option( $optionName );
			if ( ! is_array( $data ) ) {
				$data = unserialize( $data );
			}
			$templates = $data['default_templates'];
			unset( $data['default_templates'] );
			$templates[ sanitize_text_field( $_POST['templatetype'] ) ] = sanitize_text_field( $_POST['template'] );
			$data['default_templates']                                  = $templates;

			update_option( $optionName, $data );
			return $data;
		}
		/**
		 * Delete custom template
		 */
		private function fc_delete_custom_template() {
			if ( ! $this->fc_is_operation_allowed() ) {
				return;            }
			// Recursively delete folders and files of user's custom template.
			$upload_dir    = wp_upload_dir();
			$base_dir      = $upload_dir['basedir'];
			$template_path = $base_dir . '/' . sanitize_text_field( $_POST['instance'] ) . '/' . sanitize_text_field( $_POST['templatetype'] ) . '/' . sanitize_text_field( $_POST['templateName'] );
			$this->fc_remove_directory( $template_path );
			$response = array( 'status' => 'Template was deleted successfully.' );
			return $response;
		}
		/**
		 * Physical remove custom templates if created.
		 */
		private function fc_remove_directory( $dir ) {
			if ( is_dir( $dir ) ) {
				$objects = scandir( $dir );
				foreach ( $objects as $object ) {
					if ( $object != '.' && $object != '..' ) {
						if ( is_dir( $dir . '/' . $object ) ) {
							$this->fc_remove_directory( $dir . '/' . $object );
						} else {
							unlink( $dir . '/' . $object );
						}
					}
				}
				rmdir( $dir );
			}
		}
		/**
		 * Load core classes.
		 */

		private function fc_load_core_classes() {

			if ( ! class_exists( 'FlipperCode_Initialise_Core' ) ) {
				$coreInitialisationFile = $this->pluginDirectory . 'core/class.initiate-core.php';
				if ( file_exists( $coreInitialisationFile ) ) {
					require_once $coreInitialisationFile;
				}
			}

		}

		/**
		 * Load plugin classes.
		 */

		private function fc_load_plugin_classes() {

			if ( is_array( $this->pluginClasses ) ) {
				foreach ( $this->pluginClasses as $file ) {

					$classFile = $this->pluginDirectory . '/classes/' . $file;
					if ( file_exists( $classFile ) ) {
						require_once $classFile;
					}
				}
			}

		}

		/**
		 * Load plugin modules.
		 */

		private function fc_load_plugin_modules() {

			$this->pluginmodules = apply_filters( $this->pluginPrefix . '_modules_to_load', $this->pluginmodules );

			if ( is_array( $this->pluginmodules ) ) {
				foreach ( $this->pluginmodules as $module ) {
					$file = $this->pluginDirectory . '/modules/' . $module . '/model.' . $module . '.php';
					$file = apply_filters( $this->pluginPrefix . '_backend_module_path_load', $file, $module );

					if ( file_exists( $file ) ) {
						include_once $file;
						$class_name = $this->pluginmodulesprefix . ucwords( $module );
						array_push( $this->modules, $class_name );
					}
				}
			}

		}

		/**
		 * Load all required classes for plugin.
		 */
		private function fc_load_files() {
			$this->fc_load_core_classes();
			$this->fc_load_plugin_classes();
			$this->fc_load_plugin_modules();
		}
	}
}
