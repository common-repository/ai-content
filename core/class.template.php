<?php

/**

 * Generate Bootstrap Form and it's Elements.
 *
 * @author Flipper Code <hello@flippercode.com>

 * @package Core
 */



if ( ! class_exists( 'FlipperCode_HTML_Markup' ) ) {



	/**

	 * Generate Bootstrap Form and it's Elements.
	 *
	 * @author Flipper Code <hello@flippercode.com>

	 * @package Core
	 */

	class FlipperCode_HTML_Markup {

		/**

		 * Form Title
		 *
		 * @var String
		 */

		protected $form_title = null;

		/**

		 * Form Name
		 *
		 * @var String
		 */

		public $form_name = null;

		/**

		 * Form ID
		 *
		 * @var String
		 */

		public $form_id = null;

		/**

		 * Form Action
		 *
		 * @var String
		 */

		public $form_class = null;

		/**

		 * Form Class
		 *
		 * @var String
		 */

		public $form_action = '';

		/**

		 * Form Orientation - Vertical or Horizontal
		 *
		 * @var String
		 */

		public $form_type = 'form-horizontal';

		/**

		 * Call to Action Slug
		 *
		 * @var String
		 */

		protected $manage_pagename = null;

		/**

		 * Call to Action Title
		 *
		 * @var String
		 */

		protected $manage_pagetitle = null;

		/**

		 * Success or Failure Form Response
		 *
		 * @var Array
		 */

		protected $form_response = null;



		private static $enable_accordian = false;



		/**

		 * Form Method - POST or GET
		 *
		 * @var string
		 */

		protected $form_method = 'post';

		/**

		 * Bootstrap Elements Supported
		 *
		 * @var Array
		 */

		private $form_elements = array( 'extensions', 'text', 'checkbox', 'multiple_checkbox', 'checkbox_toggle', 'radio', 'submit', 'button', 'select', 'hidden', 'wp_editor', 'html', 'datalist', 'textarea', 'file', 'div', 'blockquote', 'html', 'image', 'group', 'table', 'message', 'anchor', 'number', 'image_picker', 'radio_slider', 'tab', 'category_selector', 'templates', 'fc_modal', 'select2' );

		/**

		 * Attributes Allowed
		 *
		 * @var Array
		 */

		private $allowed_attributes;

		/**

		 * Hidden Fields
		 *
		 * @var Array
		 */

		private $form_hiddens = array();

		/**

		 * Form nonce key.
		 *
		 * @var string
		 */

		private $nonce_key = 'wpgmp-nonce';

		/**

		 * Array of Bootstrap Elements
		 *
		 * @var Array
		 */

		protected $elements = array();

		/**

		 * Array of Previously Stored Elements
		 *
		 * @var Array
		 */

		protected $backup_elements = array();

		/**

		 * Array of Rendered Elements
		 *
		 * @var Array
		 */

		protected $partially_rendered = false;

		/**

		 * Number of bootstrap columns
		 *
		 * @var Int
		 */

		/**

		 * Whether setting api enabled or not.
		 *
		 * @var boolean
		 */

		public $setting_api = false;

		/**

		 * Columns in row.
		 *
		 * @var integer
		 */

		protected $columns = 1;

		/**

		 * Divide Page in multiple parts.
		 *
		 * @var string
		 */

		public $spliter = '';

		/**

		 * Intialize form properties.
		 */

		public function __construct( $options = array() ) {

			$this->allowed_attributes = array_fill_keys( array( 'min', 'max', 'choose_button', 'remove_button', 'label', 'id', 'class', 'required', 'default_value', 'value', 'options', 'desc', 'before', 'after', 'radio-val-label', 'onclick', 'placeholder', 'textarea_rows', 'textarea_name', 'html', 'current', 'width', 'height', 'src', 'alt', 'heading', 'data', 'show', 'optgroup', 'selectable_optgroup', 'tabs', 'row_class', 'page', 'data_type', 'href', 'target', 'fpc', 'product', 'productTemplate', 'parentTemplate', 'template_list', 'instance', 'dboption', 'template_types', 'templatePath', 'templateURL', 'settingPage', 'customiser', 'attachment_id', 'parent_page_slug', 'data_type', 'fc_modal_header', 'fc_modal_content', 'fc_modal_footer', 'fc_modal_initiator', 'no-sticky', 'customiser', 'customiser_controls', 'data_placeholders', 'display_mode', 'multiple', 'select2', 'tempcol', 'parent_class', 'enable_slider', 'template_custom_html' ), '' );

			$this->allowed_attributes['style'] = array();

			$this->allowed_attributes['required'] = false;

			if ( isset( $options ) ) {

				$this->options = $options;

			}

		}

		/**

		 * Set Form's header
		 *
		 * @param String $form_title       Form Title.

		 * @param String $response         Success or Failure Message.

		 * @param string $manage_pagetitle Call to Action Title.

		 * @param string $manage_pagename  Call to Action Page Slug.
		 */

		public function set_header( $form_title, $response, $enable_accordian = '', $manage_pagetitle = '', $manage_pagename = '' ) {

			if ( isset( $form_title ) && ! empty( $form_title ) ) {

				$this->form_title = $form_title;
			}

			if ( isset( $response ) && ! empty( $response ) ) {

				$this->form_response = $response;
			}

			$this->manage_pagename = $manage_pagename;

			$this->manage_pagetitle = $manage_pagetitle;

			self::$enable_accordian = $enable_accordian;

		}



		/**

		 * Form Method
		 *
		 * @param string $method Form Method.
		 */

		public function set_form_method( $method ) {

			$this->form_method = $method;

		}

		/**

		 * Title Setter
		 *
		 * @param string $title Form Title.
		 */

		public function set_title( $title ) {

			$this->form_title = $title;

		}

		/**

		 * Action Setter
		 *
		 * @param String $action Form Action.
		 */

		public function set_form_action( $action ) {

			$this->form_action = $action;

		}

		/**

		 * Title Getter
		 *
		 * @return String Get Form Title.
		 */

		public function get_title() {

			if ( isset( $this->form_title ) && ! empty( $this->form_title ) ) {

				return $this->form_title; }

		}

		/**

		 * Call to Action Button
		 */



		public static function field_fc_modal( $name, $atts ) {

			extract( $atts );

			$value = isset( $value ) ? $value : $default_value;

			$id = isset( $id ) ? $id : $name;

			$fc_modal_header = $fc_modal_header ? $fc_modal_header : '';

			$fc_modal_content = $fc_modal_content ? $fc_modal_content : '';

			$fc_modal_initiator = $fc_modal_initiator ? $fc_modal_initiator : '';

			$modal = '<div data-initiator = "' . $fc_modal_initiator . '" name="' . $name . '" ' . self::get_element_attributes( $atts ) . ' id="' . $id . '" class="fc-modal">

                       <div class="fc-modal-content">

                        <div class="fc-modal-header">

                            <span class="fc-modal-close">x</span>

                            <h4>' . $fc_modal_header . '</h4>

                          </div>

                          <div class="fc-modal-body">' . $fc_modal_content . '</div>

                       </div>

                    </div>';

			return $modal;

		}



		public static function field_number( $name, $atts ) {

			$elem_value = ( isset( $atts['value'] ) ) ? $atts['value'] : $atts['default_value'];

			$min_value = $atts['min'] ? $atts['min'] : 0;

			$max_value = $atts['max'] ? $atts['max'] : 9999;

			$element = '<input type="number" min ="' . $min_value . '" max = "' . $max_value . '" name="' . $name . '" value="' . esc_attr( stripcslashes( $elem_value ) ) . '"' . self::get_element_attributes( $atts ) . ' />';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="description">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}



		/**

		 * Get form success or error message.
		 *
		 * @return HTML Success or error message html.
		 */

		public function get_form_messages() {

			if ( empty( $this->form_response ) && ! is_array( $this->form_response ) ) {

				return; }

			$response = $this->form_response;

			$output = '';

			if ( isset( $response['error'] ) ) {

				$output .= '<div class="fc-12 fc-msg fc-danger fade in">';

				$output .= '' . $response['error'] . '</div>';

			} else {

				$output .= '<div class="fc-12 fc-msg fc-success fade in">';

				$output .= '' . $response['success'] . '</div>';

			}

			return $output;

		}

		/**

		 * Form header getter.
		 *
		 * @return HTML  Generate form header html.
		 */



		public function show_header() {

			$output = '';

			$output .= '<div class="flippercode-ui">

						<div class="fc-main"><div class="fc-container"><div class="fc-divider"><div class="product_header">

			 		<div class="fc-10 col-sm-3 col-xs-3 product_header_desc">

                        <div class="product_name">' . $this->options['productName'] . ' <span class="fc-badge">' . $this->options['productVersion'] . '</span></div>

                    </div>

                    

                    <div class="fc-2 col-sm-3 col-xs-3 social_media_area">

                    <div class="social-media-links">

                           <a href="' . $this->options['docURL'] . '" target="_blank"><img src="' . plugin_dir_url( __DIR__ ) . 'assets/images/vector.png"></a>';

			if ( isset( $this->options['videoURL'] ) && ! empty( $this->options['videoURL'] ) ) {
				   $output .= '<a href="' . $this->options['videoURL'] . '" target="_blank"><img src="' . plugin_dir_url( __DIR__ ) . 'assets/images/video-icon.png"></a>';
			}

						   $output .= '<a href="https://www.flippercode.com" target="_blank"><img src="' . plugin_dir_url( __DIR__ ) . 'assets/images/flippdercode_logo.png"></a>

                         </div>      

                    </div></div></div></div></div></div>';

			return $output;

		}

		public function product_overview() {

			if ( ! isset( $this->options['no_header'] ) || $this->options['no_header'] !== true ) {

				echo wp_kses_post( $this->show_header() );

			}

			$productOverviewObj = new Flippercode_Product_Overview( $this->options );

		}

		public function get_header() {

			$output = '';

			if ( ! isset( $this->options['no_header'] ) || $this->options['no_header'] != true ) {

				$output = $this->show_header();

			}

			$output .= '<div class="flippercode-ui flippercode-ui-height">

						<div class="fc-main"><div class="fc-container">';

			$output .= '<div class="fc-divider   fc-item-shadow"><div class=" fc-back">

						<div class="fc-form-container">' .

						$this->get_form_messages();

			return apply_filters( 'wpgmp_form_header_html', $output );

		}

		/**

		 * Form footer getter.
		 *
		 * @return HTML Generate form footer html.
		 */

		public function get_footer() {

			$output = '</div>

						</div></div>

						</div>

						</div>

						</div>';

			return apply_filters( 'wpgmp_form_footer_html', $output );

		}

		/**

		 * Bootstrap columns setter.
		 *
		 * @param int $column Set columns occupied by element.
		 */

		public function set_col( $column ) {

			if ( $this->elements ) {

				$last_index = key( array_reverse( $this->elements ) );

				$this->elements[ $last_index ]['col_after'] = $column;

				return;

			}

			$this->columns = $column ? absint( $column ) : 2;

		}

		/**

		 * Bootstrap columns getter.
		 */

		public function get_col() {

			return $this->columns;

		}

		/**

		 * Add element in queue.
		 *
		 * @param string $type Element type.

		 * @param string $name Element name.

		 * @param array  $args Element Properties.
		 */

		public function add_element( $type, $name, $args = array() ) {

			if ( ! in_array( $type, $this->form_elements ) ) {

				return; }

			$this->elements[ $name ] = shortcode_atts( $this->allowed_attributes, $args );

			$this->elements[ $name ]['type'] = $type;

		}



		public static function apply_extensions( $filter, $value ) {

			$element_html = '';

			$element_html .= apply_filters( $filter, '', $value );

			$element_html .= self::field_hidden( 'fc_entity_type', array( 'value' => strtolower( trim( $filter ) ) ) );

			return $element_html;

		}



		public static function field_extensions( $name, $atts ) {

			return self::apply_extensions( $name, $atts['value'] );

		}



		/**

		 * Concat form elements together.
		 *
		 * @return html  Combined HTML of each elements.
		 */

		public function get_combined_markup() {

			$element_html = '';

			if ( $this->elements ) {

				$elements = $this->elements;

				$num = 0;

				$tmpl = false;

				$section_start = '';

				while ( $num < count( $elements ) ) {

					$col = $this->get_col();

					$elem_content = '';

					foreach ( array_slice( $elements, $num, $col ) as $name => $atts ) {

						if ( self::$enable_accordian == true ) {

							if ( $atts['type'] == 'group' ) {

								$before = apply_filters( 'wpgmp_element_before_start_row', '<dt><section class="fc-form-group {modifier} ' . $atts['parent_class'] . '">' );

								$after = apply_filters( 'wpgmp_element_after_end_row', '</section></dt>' );

								$group_section_start = '<dd>';

							} elseif ( $atts['type'] == 'templates' ) {

								$group_section_start = '';

								// Do Noting For Now
							} elseif ( $atts['type'] == 'hidden' ) {

								$before = '';

								$after = '';

								$group_section_start = '';

							} elseif ( $atts['type'] == 'submit' ) {

								$before = apply_filters( 'wpgmp_element_before_start_row', '</dd><section class="fc-form-group {modifier} ' . $atts['parent_class'] . '">' );

								$after = apply_filters( 'wpgmp_element_after_end_row', '</section>' );

								$group_section_start = '';

							} else {

								$before = apply_filters( 'wpgmp_element_before_start_row', '<section class="fc-form-group {modifier} ' . $atts['parent_class'] . '">' );

								$after = apply_filters( 'wpgmp_element_after_end_row', '</section>' );

								$group_section_start = '';

							}
						} else {

							if ( $atts['type'] == 'hidden' ) {

								$before = '';

								$after = '';

								$group_section_start = '';

							} else {

								$group_section_start = '';

								$before = apply_filters( 'wpgmp_element_before_start_row', '<div class="fc-form-group {modifier} ' . $atts['parent_class'] . '">' );

								$after = apply_filters( 'wpgmp_element_after_end_row', '</div>' );
							}
						}

						$row_extra = false;

						$temp = $before;

						if ( ! isset( $atts['type'] ) || ! is_string( $name ) ) {

							continue;

						}

						if ( 'hidden' == $atts['type'] ) {

							$before = '';

							$after         = '';
							$elem_content .= call_user_func( 'FlipperCode_HTML_Markup::field_' . $atts['type'], $name, $atts );

							continue;

						}

						if ( 'templates' == $atts['type'] ) {

							$before = '';

							$after = '';

							$elem_content .= call_user_func( 'FlipperCode_HTML_Markup::field_' . $atts['type'], $name, $atts );

							continue;

						}

						$elem_content .= $this->get_element_html( $name, $atts['type'], $atts );

						if ( isset( $atts['col_after'] ) ) {

							$this->columns = $atts['col_after'];

						}

						if ( isset( $atts['show'] ) and 'false' == $atts['show'] ) {

							$row_extra = true;

						}
					}

					if ( true == $row_extra ) {

						$temp = str_replace( '{modifier}', 'hiderow', $temp );

					} else {

						$temp = str_replace( '{modifier}', '', $temp );

					}

					if ( ! empty( $elem_content ) ) {

						$element_html .= $temp . $elem_content . $after . $group_section_start; }

					$num = $num + $col;

				}
			}

			return $element_html;

		}

		/**

		 * Form header getter.
		 *
		 * @return html Generate form header html.
		 */

		public function get_form_header() {

			$form_header = '<form enctype="multipart/form-data" method="' . $this->form_method . '" action="' . esc_url( $this->form_action ) . '" name="wpgmp_form" novalidate';

			if ( isset( $this->form_name ) && ! empty( $this->form_name ) ) {

				$form_header .= ' name="' . esc_attr( $this->form_name ) . '" '; }

			if ( isset( $this->form_id ) && ! empty( $this->form_id ) ) {

				$form_header .= ' id="' . esc_attr( $this->form_id ) . '" '; }

			if ( isset( $this->form_class ) && ! empty( $this->form_class ) ) {

				$form_header .= ' class="' . esc_attr( $this->form_class ) . '" '; }

			$form_header .= '>';

			$form_header .= '<div class="' . esc_attr( $this->form_type ) . '">';

			if ( self::$enable_accordian == true ) {

				$form_header .= '<dl class="custom-accordion">';

			}

			return $form_header;

		}

		/**

		 * Form nonce key setter.
		 *
		 * @param string $nonce_key Form nonce key.
		 */

		public function set_form_nonce( $nonce_key ) {

			$this->nonce_key = $nonce_key;

		}

		/**

		 * Form footer getter.
		 *
		 * @return html Generate form footer html.
		 */

		public function get_form_footer() {

			if ( self::$enable_accordian == true ) {

				$form_footer = '</dl></div>';

			} else {

				$form_footer = '</div>';

			}

			$form_footer .= wp_nonce_field( $this->nonce_key, '_wpnonce', true, false );

			$form_footer .= '</form>';

			return $form_footer;

		}

		/**

		 * Echo or return html elements.
		 *
		 * @param  boolean $echo  True to display.

		 * @return html    html Generate form html
		 */

		public function render( $echo = true ) {

			if ( ! $this->elements || ! is_array( $this->elements ) and $this->partially_rendered == false ) {

				echo '<div id="message" class="error"><p>Please add form element first.</p></div>';

				return;

			}

			$form_output = '';

			if ( empty( $this->backup_elements ) and ! isset( $this->options['ajax'] ) and ! isset( $this->options['elements_only'] ) ) {

				$form_output = $this->get_header();

			}

			if ( empty( $this->backup_elements ) and ! isset( $this->options['elements_only'] ) ) {

				$form_header = $this->get_form_header();

			}

			if ( isset( $this->options['elements_only'] ) ) {

				$form_html = $this->get_combined_markup();

			} else {

				$form_html = $form_header . $this->get_combined_markup() . $this->get_form_footer();

			}

			if ( isset( $this->spliter ) and $this->spliter != '' ) {

				$spliter = str_replace( '%form', $form_html, $this->spliter );

			} else {

				$spliter = $form_html; }

			$form_output .= $spliter;

			if ( ! isset( $this->options['ajax'] ) and ! isset( $this->options['elements_only'] ) ) {

				$form_output .= $this->get_footer();

			}

			if ( $echo ) {

				$form_output = balanceTags( $form_output );

				echo $form_output;

			} else {

				return $form_output; }

		}

		/**

		 * Element's html creater.
		 *
		 * @param  string $name Element Name.

		 * @param  string $type Element Type.

		 * @param  array  $atts  Element Options.

		 * @return html       Element's Html.
		 */

		public static function get_element_html( $name, $type, $atts ) {

			$element_output = '';

			if ( 'hidden' == $type ) {

				$element_output = call_user_func( 'FlipperCode_HTML_Markup::field_' . $type, $name, $atts );

				return $element_output;

			} elseif ( 'templates' == $type ) {

				$element_output = call_user_func( 'FlipperCode_HTML_Markup::field_' . $type, $name, $atts );

				return $element_output;

			} else {

				if ( ! empty( $atts['label'] ) ) {

					$element_output .= apply_filters( 'wpgmp_input_label_' . $name, '<div class="fc-3"><label for="' . $name . '">' . $atts['label'] . '&nbsp' . self::element_mandatory( isset( $atts['required'] ) ? $atts['required'] : '' ) . '</div>' ) . '</label>'; }

				$element_output .= ( isset( $atts['before'] ) && ! empty( $atts['before'] ) ) ? $atts['before'] : '<div class="fc-9">';

				$element_output .= call_user_func( 'FlipperCode_HTML_Markup::field_' . $type, $name, $atts );

				$element_output .= ( isset( $atts['after'] ) && ! empty( $atts['after'] ) ) ? $atts['after'] : '</div>';

				return $element_output;

			}

		}

		/**

		 * Display mandatory indicator on element.
		 *
		 * @param  boolean $required Whether field is required or not.

		 * @return html            Mandatory indicator.
		 */

		public static function element_mandatory( $required = false ) {

			if ( true == $required ) {

				return '<span style="color:#F00;">*</span>'; }

		}

		/**

		 * Attributes Generator for the element.
		 *
		 * @param  array $atts Attributes keys and values.

		 * @return string      Attributes section of the element.
		 */

		protected static function get_element_attributes( $atts ) {
			if ( ! is_array( $atts ) ) {
				return null; }

			$attributes = array();
			if ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) {
				$attributes[] = 'id="' . $atts['id'] . '"'; }

			$classes = ( ! empty( $atts['class'] ) ) ? $atts['class'] : 'form-control';

			if ( isset( $atts['type'] ) && $atts['type'] == 'submit' ) {
				$classes .= ' fc-btn fc-btn-submit fc-btn-big';
			}

			$attributes[] = 'class="' . $classes . '"';

			if ( isset( $atts['style'] ) && ! empty( $atts['style'] ) ) {
				$style = 'style="';
				foreach ( $atts['style'] as $key => $value ) {
					$style .= $key . ':' . $value . ';'; }
				$style .= '"';

				$attributes[2] = $style;
			}

			if ( isset( $atts['placeholder'] ) && ! empty( $atts['placeholder'] ) ) {
				$attributes[] = 'placeholder="' . esc_attr( $atts['placeholder'] ) . '"';
			}

			if ( isset( $atts['data'] ) && ! empty( $atts['data'] ) ) {
				foreach ( $atts['data'] as $key => $value ) {

					if ( ! is_array( $value ) ) {
						$attributes[] = 'data-' . $key . '="' . esc_attr( $value ) . '"';
					}
				}
			}

			if ( isset( $atts['src'] ) && ! empty( $atts['src'] ) ) {
				$attributes[] = 'src="' . esc_url( $atts['src'] ) . '"';
			}

			if ( isset( $atts['alt'] ) && ! empty( $atts['alt'] ) ) {
				$attributes[] = 'alt="' . esc_attr( $atts['alt'] ) . '"';
			}

			if ( isset( $atts['height'] ) && ! empty( $atts['height'] ) ) {
				$attributes[] = 'height="' . esc_attr( $atts['height'] ) . '"';
			}

			if ( isset( $atts['width'] ) && ! empty( $atts['width'] ) ) {
				$attributes[] = 'width="' . esc_attr( $atts['width'] ) . '"';
			}

			if ( isset( $atts['value'] ) && ! empty( $atts['value'] ) and ! is_array( $atts['value'] ) ) {
				$attributes[] = 'value="' . esc_attr( $atts['value'] ) . '"';
			}

			if ( isset( $atts['name'] ) && ! empty( $atts['name'] ) ) {
				$attributes[] = 'name="' . esc_attr( $atts['name'] ) . '"';
			}

			if ( ! $attributes ) {
				return null; }

			return implode( ' ', $attributes );

		}

		public static function get_all_templates_with_slider( $name, $atts, $accordian ) {

			$templateCategories = $atts['template_types'];
			$templatePath       = $atts['templatePath'];
			$templateURL        = $atts['templateURL'];
			$settingPage        = $atts['settingPage'];
			$customiser         = $atts['customiser'];
			$dbsetting          = get_option( $atts['dboption'] );
			if ( ! is_array( $dbsetting ) ) {
				$dbsetting = unserialize( $dbsetting );
			}

			if ( ! isset( $customiser ) ) {
				$custmiserDisabled = false;
			} elseif ( isset( $customiser ) and $customiser == 'false' ) {
				$custmiserDisabled = true;
			} else {
				$custmiserDisabled = false;
			}

			$html = '';

			foreach ( $templateCategories as $templateType ) {
				if ( $accordian == true ) {
					$html .= '</dd><dt>';

					$html .= self::get_element_html(
						$templateType . '_group',
						'group',
						array(
							'value'        => $atts['label'],
							'before'       => '<section class="fc-form-group ' . $atts['parent_class'] . ' "><div class="fc-12 ' . $templateType . '-template-type-listing">',
							'after'        => '</div></section>',
							'for_template' => 'yes',
						)
					);

					$html .= '</dt>';
				} else {
					$html .= self::get_element_html(
						$templateType . '_group',
						'group',
						array(
							'value'        => $atts['label'],
							'before'       => '<div class="' . $templateType . '-template-type-listing">',
							'after'        => '</div>',
							'for_template' => 'yes',
						)
					);
				}

				$directories = glob( $templatePath . $templateType . '/*', GLOB_ONLYDIR );

				if ( $accordian == true ) {
					$html .= '<dd>';
				}

				if ( ! empty( $atts['template_custom_html'] ) && is_array( $atts['template_custom_html'] ) && $atts['template_custom_html']['position'] == 'top' ) {
					$html .= isset( $atts['template_custom_html']['custom_html'] ) && ! empty( $atts['template_custom_html']['custom_html'] ) ? $atts['template_custom_html']['custom_html'] : '';
				}

				$html .= "<div class='fc_templates default_template_listing'><div class='fc_template_type'></div>";

				$uploads     = wp_upload_dir();
				$product     = $atts['instance'];
				$for         = $atts['template_types'][0];
				$path        = $uploads['basedir'] . '/' . $product . '/' . $for;
				$uploadsurl  = $uploads['baseurl'] . '/' . $product . '/' . $for;
				$fromUploads = glob( $path . '/*', GLOB_ONLYDIR );

				if ( is_array( $fromUploads ) ) {
					$directories = array_merge( $directories, $fromUploads );
				}

				foreach ( $directories as $key => $directory ) {

					$parentTemplate = $directory = basename( $directory );

					if ( file_exists( $templatePath . $templateType . '/' . $directory . '/' . $directory . '.png' ) ) {
						$preview = $templateURL . $templateType . '/' . $directory . '/' . $directory . '.png';
					} else {
						$preview = $uploadsurl . '/' . $directory . '/' . $directory . '.png';
					}

					$title                   = ucwords( $directory );
					$atts['productTemplate'] = $directory;
					$atts['templatetype']    = $templateType;
					$atts['settingPage']     = $settingPage;

					$customiserLink = self::get_product_customizer_link( $atts );

					$html .= '<div class="fc_template fc_template_' . $title . ' ">';

					if ( isset( $atts['current'] ) && ! empty( $atts['current'] ) ) {

						$currentTemplateCondition = ( $directory == $atts['current'] ) ? true : false;

					} else {

						$currentTemplateCondition = ( isset( $dbsetting['default_templates'][ $templateType ] ) && $dbsetting['default_templates'][ $templateType ] != $directory ) ? true : false;

					}
					// echo $directory.'Test -'.$atts['current'];
					$user_uploaded_template = array_filter(
						$fromUploads,
						function( $var ) use ( $directory ) {
							return strpos( $var, $directory ) !== false;
						}
					);
					$type                   = ( ! empty( $user_uploaded_template ) ) ? 'user-temp' : 'static-temp';

					$useThis = ( $currentTemplateCondition && ! empty( $atts['current'] ) ) ? "<a href='javascript:void(0)' class='current-temp-in-use set-default-template'><i class='fa fa-check' aria-hidden='true'></i></a>" : "<a class='set-default-template " . $type . "' href='javascript:void(0)' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "' ><i class='fa fa-check' aria-hidden='true'></i></a>";

					$deleteThis = '';
					if ( ! empty( $user_uploaded_template ) ) {

						$deleteThis = ( $currentTemplateCondition ) ? "<a class='delete-custom-template' href='javascript:void(0)' data-instance= '" . $atts['instance'] . "' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "' ><i class='fa fa-close' aria-hidden='true'></i></a>" : '';

					}

					if ( ! $custmiserDisabled ) {
						$fctools = "<div class='fc_tools'>" . $useThis . " | <a class='customise_this_template' href='" . $customiserLink . "' target='_blank'>Customize</a></div>";
					} else {
						$fctools = "<div class='fc_tools'><ul class='action_links'>" . '<li>' . $useThis . '</li><li>' . $deleteThis . '</li></ul></div>';
					}

					if ( isset( $atts['template_list'] ) && ! empty( $atts['template_list'] ) ) {
						$title = $atts['template_list'][ $directory ];
					}

					$html .= "
									<div class='fc_screenshot'><img src='" . $preview . "' /></div>
									<div class='fc_name'><span>" . ucwords( $title ) . '</span></div>
									' . $fctools . '
							 </div>';

				}

				$html .= '</div>';

				if ( ! $custmiserDisabled ) {

					$html .= "<div class='fc_templates customised-templates-listing'><div class='fc_template_type'></div><div class='row'>";
					$html .= self::get_userdefined_templates( $name, $atts, $templateType, $parentTemplate );
					$html .= '</div></div>';

				}

				if ( ! empty( $atts['template_custom_html'] ) && is_array( $atts['template_custom_html'] ) && $atts['template_custom_html']['position'] == 'bottom' ) {
					$html .= isset( $atts['template_custom_html']['custom_html'] ) && ! empty( $atts['template_custom_html']['custom_html'] ) ? $atts['template_custom_html']['custom_html'] : '';
				}

				if ( $accordian == true ) {
					$html .= '</dd>';
				}
			}

			return $html;
		}

		public static function get_all_templates( $name, $atts, $accordian ) {

			$templateCategories = $atts['template_types'];
			$templatePath       = $atts['templatePath'];
			$templateURL        = $atts['templateURL'];
			$settingPage        = $atts['settingPage'];
			$customiser         = $atts['customiser'];
			$dbsetting          = get_option( $atts['dboption'] );
			if ( ! is_array( $dbsetting ) ) {
				$dbsetting = unserialize( $dbsetting );
			}

			if ( ! isset( $customiser ) ) {
				$custmiserDisabled = false;
			} elseif ( isset( $customiser ) and $customiser == 'false' ) {
				$custmiserDisabled = true;
			} else {
				$custmiserDisabled = false;
			}

			$html = '';

			foreach ( $templateCategories as $templateType ) {
				if ( $accordian == true ) {
					$html .= '</dd><dt>';

					$html .= self::get_element_html(
						$templateType . '_group',
						'group',
						array(
							'value'        => $atts['label'],
							'before'       => '<section class="fc-form-group ' . $atts['parent_class'] . ' "><div class="fc-12 ' . $templateType . '-template-type-listing">',
							'after'        => '</div></section>',
							'for_template' => 'yes',
						)
					);

					$html .= '</dt>';
				} else {
					$html .= self::get_element_html(
						$templateType . '_group',
						'group',
						array(
							'value'        => $atts['label'],
							'before'       => '<div class="' . $templateType . '-template-type-listing">',
							'after'        => '</div>',
							'for_template' => 'yes',
						)
					);
				}

				$directories = glob( $templatePath . $templateType . '/*', GLOB_ONLYDIR );

				if ( $accordian == true ) {
					$html .= '<dd>';
				}

				if ( ! empty( $atts['template_custom_html'] ) && is_array( $atts['template_custom_html'] ) && $atts['template_custom_html']['position'] == 'top' ) {
					$html .= isset( $atts['template_custom_html']['custom_html'] ) && ! empty( $atts['template_custom_html']['custom_html'] ) ? $atts['template_custom_html']['custom_html'] : '';
				}
				$html .= "<div class='fc_templates default_template_listing'><div class='fc_template_type'></div><div class='fc-divider'>";

				$uploads     = wp_upload_dir();
				$product     = $atts['instance'];
				$for         = $atts['template_types'][0];
				$path        = $uploads['basedir'] . '/' . $product . '/' . $for;
				$uploadsurl  = $uploads['baseurl'] . '/' . $product . '/' . $for;
				$fromUploads = glob( $path . '/*', GLOB_ONLYDIR );

				if ( is_array( $fromUploads ) ) {
					$directories = array_merge( $directories, $fromUploads );
				}

				foreach ( $directories as $key => $directory ) {

					$parentTemplate = $directory = basename( $directory );

					if ( file_exists( $templatePath . $templateType . '/' . $directory . '/' . $directory . '.png' ) ) {
						$preview = $templateURL . $templateType . '/' . $directory . '/' . $directory . '.png';
					} else {
						$preview = $uploadsurl . '/' . $directory . '/' . $directory . '.png';
					}

					$title                   = ucwords( $directory );
					$atts['productTemplate'] = $directory;
					$atts['templatetype']    = $templateType;
					$atts['settingPage']     = $settingPage;

					$customiserLink = self::get_product_customizer_link( $atts );

					if ( ! isset( $atts['tempcol'] ) || empty( $atts['tempcol'] ) ) {
						if ( $key != 0 && $key % 3 == 0 ) {

							$html .= '</div><div class="fc-divider">';
						}
					} else {

						$break_on = 12 / $atts['tempcol'];
						if ( $key != 0 && $key % $break_on == 0 ) {

							$html .= '</div><div class="fc-divider">';

						}
					}

					$currentTemplateCondition = ( isset( $dbsetting['default_templates'][ $templateType ] ) && $dbsetting['default_templates'][ $templateType ] != $directory ) ? true : false;

					$user_uploaded_template = array_filter(
						$fromUploads,
						function( $var ) use ( $directory ) {
							return strpos( $var, $directory ) !== false;
						}
					);
					$type                   = ( ! empty( $user_uploaded_template ) ) ? 'user-temp' : 'static-temp';

					$useThis = ( $currentTemplateCondition ) ? "<a class='set-default-template " . $type . "' href='javascript:void(0)' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "' ><i class='fa fa-check' aria-hidden='true'></i></a>" : "<a href='javascript:void(0)' class='current-temp-in-use set-default-template'><i class='fa fa-check' aria-hidden='true'></i></a>";

					$deleteThis = '';
					if ( ! empty( $user_uploaded_template ) ) {

						$deleteThis = ( $currentTemplateCondition ) ? "<li><a class='delete-custom-template' href='javascript:void(0)' data-instance= '" . $atts['instance'] . "' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "' ><i class='fa fa-close' aria-hidden='true'></i></a></li>" : '';

					}

					if ( ! $custmiserDisabled ) {
						$fctools = "<div class='fc_tools'>" . $useThis . " | <a class='customise_this_template' href='" . $customiserLink . "' target='_blank'>Customize</a></div>";
					} else {
						$fctools = "<div class='fc_tools'><ul class='action_links'>" . '<li>' . $useThis . '</li>' . $deleteThis . '</ul></div>';
					}
					$tempcol = ( isset( $atts['tempcol'] ) && ! empty( $atts['tempcol'] ) ) ? $atts['tempcol'] : '4';

					if ( isset( $atts['template_list'] ) && ! empty( $atts['template_list'] ) ) {
						$title = $atts['template_list'][ $directory ];
					}

					$html .= "<div class='fc-" . $tempcol . "'><div class='fc_template fc_template_" . $title . "'>
									<div class='fc_screenshot'><img src='" . $preview . "' /></div>
									<div class='fc_name'><span>" . ucwords( $title ) . '</span></div>
									' . $fctools . '
							 </div></div>';

				}

				$html .= '</div></div>';

				if ( ! $custmiserDisabled ) {

					$html .= "<div class='fc_templates customised-templates-listing'><div class='fc_template_type'></div><div class='row'>";
					$html .= self::get_userdefined_templates( $name, $atts, $templateType, $parentTemplate );
					$html .= '</div></div>';

				}

				if ( ! empty( $atts['template_custom_html'] ) && is_array( $atts['template_custom_html'] ) && $atts['template_custom_html']['position'] == 'bottom' ) {
					$html .= isset( $atts['template_custom_html']['custom_html'] ) && ! empty( $atts['template_custom_html']['custom_html'] ) ? $atts['template_custom_html']['custom_html'] : '';
				}

				if ( $accordian == true ) {
					$html .= '</dd>';
				}
			}

			return $html;
		}

		/**

		 * Image picker element.
		 *
		 * @param  string $name  No use.

		 * @param  array  $atts  Attributes for custom html.

		 * @return html       Image Picker.
		 */



		function get_css_property_unit( $property ) {

			switch ( $property ) {

				case 'width':
					$unit = '%';

					break;

				case 'font-size':
					  $unit = 'px';

					break;

				case 'border-radius':
					  $unit = 'px';

				case 'padding':
					  $unit = 'px';

				case 'padding-top':
					  $unit = 'px';

				case 'padding-right':
					  $unit = 'px';

				case 'padding-bottom':
					  $unit = 'px';

				case 'padding-left':
					  $unit = 'px';

				case 'margin':
					  $unit = 'px';

				case 'margin-top':
					  $unit = 'px';

				case 'margin-right':
					  $unit = 'px';

				case 'margin-bottom':
					  $unit = 'px';

				case 'margin-left':
					  $unit = 'px';

					break;

				default:
					  $unit = '';

			}

				return $unit;

		}



		function generate_css( $prefix, $originalelements, $realformelements, $isImportant ) {

				$prefixspecificstyle = '';

				$final = array();

				$important = ( $isImportant ) ? '!important' : '';

			if ( is_array( $originalelements ) ) {

				foreach ( $originalelements as $element ) {

					foreach ( $realformelements as $key => $value ) {

						if ( strpos( $key, $element ) === 0 ) {

							$property = explode( '*', $key );

							$final[ $element ][ $property[1] ] = $value;

						}
					}
				}

				foreach ( $final as $selector => $cssInfo ) {

						$prefixspecificstyle .= $prefix . ' .' . $selector . '{ ';

					foreach ( $cssInfo as $cssproperty => $csspropertyvalue ) {

						$unit = self::get_css_property_unit( $cssproperty );

						$unit = '';

						$prefixspecificstyle .= $cssproperty . ' : ' . $csspropertyvalue . $unit . $important . '; ';

					}

						$prefixspecificstyle .= ' }';

				}
			}

				return $prefixspecificstyle;

		}



		function give_css_with_prefix( $prefix, $layoutid, $productcustomiserdata ) {

				$originalelements = $productcustomiserdata[ $layoutid ]['originalelements'];

				$realformelements = $productcustomiserdata[ $layoutid ]['formdata'];

				$backupStyle = self::generate_css( $prefix, $originalelements, $realformelements, false );

				return $backupStyle;

		}



		public static function get_userdefined_templates( $name, $atts, $templateType, $parentTemplate ) {

			$dbsetting = get_option( $atts['dboption'] );

			$userdefinetemplates = get_option( $atts['dboption'] . '-fc-styles' );

			$templatetype = $atts['templatetype'];

			foreach ( $userdefinetemplates as $key => $templates ) {

				if ( $templates['templateType'] != $templatetype ) {

					continue;
				}

					$directory = $key;

					$templatePreviewSpecificClass = '.current-templte-' . $directory;

					$css = self::give_css_with_prefix( $templatePreviewSpecificClass, $directory, $userdefinetemplates );

					$preview = html_entity_decode( $templates['templateMarkup'] );

					$title = ucwords( $directory );

					$atts['productTemplate'] = $directory;

					$atts['templatetype'] = $templates['templateType'];

					$atts['parentTemplate'] = $parentTemplate;

					$customiserLink = self::get_product_customizer_link( $atts );

				if ( $key % 3 == 0 and $key != 0 ) {

					$html .= '</div><div class="fc-divider">';

				}

					$html .= "<div class='fc-4 custom-temp-inside'><style>" . $css . "</style><div class='fc_template current-templte-" . $directory . "'>";

				$currentTemplateCondition = ( $dbsetting['default_templates'][ $templateType ] != $key ) ? true : false;

				$useThis = ( $currentTemplateCondition ) ? "<a class='set-default-template' href='javascript:void(0)' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "' >Use this</a>" : "<a href='javascript:void();' class='current-temp-in-use'>In Use</a>";

				$deleteTemplateLink = ( $currentTemplateCondition ) ? "<a class='default-custom-template' href='javascript:void(0)' data-templateName = '" . $directory . "' data-product= '" . $atts['dboption'] . "' data-templatetype='" . $templateType . "'>Delete This Template</a>" : '';

					$html .= "<div class='fc_screenshot'>" . $preview . "</div>

									<div class='fc_name'>" . $title . " - Custom Defined Template</div>

									<div class='fc_tools'>" . $useThis . " | <a href='" . $customiserLink . "' target='_blank'>Customize</a>

									 | " . $deleteTemplateLink . '

									</div>

							 </div></div>';

			}

			return $html;

		}



		public static function get_product_customizer_link( $linkInfo ) {

			$info = array(

				'fpc'             => 'true',

				'product'         => $linkInfo['product'],

				'productTemplate' => $linkInfo['productTemplate'],

				'instance'        => $linkInfo['instance'],

				'templatetype'    => $linkInfo['templatetype'],

				'settingPage'     => $linkInfo['settingPage'],

			);

			if ( ! empty( $linkInfo['parentTemplate'] ) ) {

				$info['parentTemplate'] = $linkInfo['parentTemplate'];

			}

			$productCustomizerLink = add_query_arg( $info, admin_url( '?page=flippercode-product-customiser' ) );

			return $productCustomizerLink;

		}



		public static function field_templates( $name, $atts ) {

			$accordian     = self::$enable_accordian;
			$templatesHtml = '';
			if ( isset( $atts['enable_slider'] ) && ! empty( $atts['enable_slider'] ) && $atts['enable_slider'] == true ) {
				$templatesHtml .= self::get_all_templates_with_slider( $name, $atts, $accordian );
			} else {
				$templatesHtml .= self::get_all_templates( $name, $atts, $accordian );
			}
			return $templatesHtml;

		}



		/**

		 * Image picker element.
		 *
		 * @param  string $name  No use.

		 * @param  array  $atts  Attributes for custom html.

		 * @return html       Image Picker.
		 */

		public static function field_image_picker( $name, $atts ) {

			$display = ( ! empty( $atts['src'] ) ) ? 'block' : 'none';

			$imageClass = ( empty( $atts['src'] ) ) ? 'noimage' : '';

			$html = self::field_image(

				'selected_image',
				array(

					'src'      => $atts['src'],

					'width'    => '100',

					'class'    => $imageClass . ' selected_image',

					'height'   => '100',

					'required' => $atts['required'],

					'style'    => array(

						'display' => $display,

						'float'   => 'right',

					),

				)
			);

			$html .= self::field_anchor(

				'choose_image',
				array(

					'value' => $atts['choose_button'],

					'href'  => 'javascript:void(0);',

					'class' => 'fc-btn fc-btn-blue btn-small choose_image fc-3 ' . $atts['class'] . '',

					'data'  => array( 'target' => $name ),

					'id'    => $atts['id'] . '_link',

				)
			);

			$html .= self::field_anchor(

				'remove_image',
				array(

					'value'  => $atts['remove_button'],

					'before' => '<div class="fc-3">',

					'after'  => '</div>',

					'href'   => 'javascript:void(0);',

					'class'  => 'fc-btn fc-btn-red btn-small remove_image fc-3 fc-offset-1',

					'data'   => array( 'target' => $name ),

					'style'  => array( 'display' => $display ),

				)
			);

			$html .= self::field_hidden(

				'group_marker',
				array(

					'value' => $atts['src'],

					'id'    => $name,

					'name'  => $name,

				)
			);

			$html .= self::field_hidden(

				$name . '_attachment_id',
				array(

					'value' => $atts['attachment_id'],

					'id'    => $name . '_attachment_id',

				)
			);

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$html .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return $html;

		}



		/**

		 * Custom HTML to display.
		 *
		 * @param  string $name  No use.

		 * @param  array  $atts  Attributes for custom html.

		 * @return html       Body of custom html.
		 */

		public static function field_html( $name, $atts ) {

			extract( $atts );

			return '<div ' . self::get_element_attributes( $atts ) . ' >' . $html . '</div>';

		}

		/**

		 * Radio Slider
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_radio_slider( $name, $atts ) {

			extract( $atts );

			$value = $value ? $value : $default_value;

			$min = $min ? $min : 1;

			$max = $max ? $max : 100;

			$html = '<div id="ui_' . $id . '" data-value="' . $value . '" data-min="' . $min . '" data-max="' . $max . '" class="ui-slider">

			<input type="hidden" id="' . $id . '" value="' . $value . '" name="' . $name . '"></div>';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$html .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return $html;

		}

		/**

		 * Hidden Field
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_hidden( $name, $atts ) {

			extract( $atts );

			if ( ! isset( $default_value ) ) {

				$default_value = '';

			}

			if ( ! isset( $id ) ) {

				$id = $name;

			}

			$value = $value ? $value : $default_value;

			return '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . $value . '" />';

		}

		/**

		 * Group Heading
		 *
		 * @param  string $name Group title.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_group( $name, $atts ) {

			extract( $atts );

			if ( ! isset( $atts['class'] ) ) {

				$atts['class'] = '';

			}

			$atts['class'] = 'fc-title-blue group-element' . $atts['class'];

			if ( isset( $atts['default_value'] ) ) {
				$default_value = $atts['default_value'];
			} else {
				$default_value = '';
			}

			$value = $value ? ucwords( $value ) : ucwords( $default_value );

			return '<h4 ' . self::get_element_attributes( $atts ) . ' >' . $value . '</h4>';

		}

		/**

		 * DIV node
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_div( $name, $atts ) {

			extract( $atts );

			$value = $value ? $value : $default_value;

			return '<div name="' . $name . '" ' . self::get_element_attributes( $atts ) . '>' . $value . '</div>';

		}

		/**

		 * Blockquote node
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_blockquote( $name, $atts ) {

			extract( $atts );

			$value = $value ? $value : $default_value;

			return '<blockquote>' . $atts['value'] . '</blockquote>';

		}

		/**

		 * Text Input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_text( $name, $atts ) {

			$elem_value = ( isset( $atts['value'] ) ) ? $atts['value'] : $atts['default_value'];

			if ( isset( $atts['class'] ) && strstr( $atts['class'], 'color' ) !== false ) {

				if ( $atts['value'] == '#' || empty( $atts['value'] ) ) {
					$elem_value = ( isset( $atts['default_value'] ) ) ? $atts['default_value'] : '';
				}

				$elem_value = str_replace( '#', '', $elem_value );

				$elem_value = '#' . $elem_value;

			}

			$element = '<input type="text" name="' . $name . '" value="' . esc_attr( stripcslashes( $elem_value ) ) . '"' . self::get_element_attributes( $atts ) . ' />';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Display Information message in <p> tag.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_infoarea( $name, $atts ) {

			return '<p>' . $atts['desc'] . '</p>'; }



		/**

		 * Image tag.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_image( $name, $atts ) {

			if ( isset( $atts['show'] ) and $atts['show'] == 'false' ) {

				$hide = 'display:none;';

			} else {

				$hide = '';

			}

			if ( ! isset( $atts['alt'] ) ) {

				$atts['alt'] = '';

			}

			$element = '<img style="' . $hide . '" src="' . $atts['src'] . '" alt="' . $atts['alt'] . '" height="' . $atts['height'] . '" width="' . $atts['width'] . '" ' . self::get_element_attributes( $atts ) . ' >';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Generate output using wp_editor.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_wp_editor( $name, $atts ) {

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			$args = array(

				'textarea_rows' => $atts['textarea_rows'],

				'textarea_name' => $atts['textarea_name'],

				'editor_class'  => $atts['class'],

			);

			$output = '';

			ob_start();

			wp_editor( esc_textarea( $value ), $name, $args );

			$output .= ob_get_contents();

			ob_clean();

			$output .= '<p class="help-block">' . $atts['desc'] . '</p>';

			return $output;

		}



		/**

		 * Textarea element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_textarea( $name, $atts ) {

			$default_value = isset( $atts['default_value'] ) ? $atts['default_value'] : '';

			$elem_value = $atts['value'] ? $atts['value'] : $default_value;

			$element = '<textarea  rows="5" name="' . $name . '" ' . self::get_element_attributes( $atts ) . ' >' . esc_textarea( wp_unslash( $elem_value ) ) . '</textarea>';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * File Input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_file( $name, $atts ) {

			$elem_value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			$element = '<div class="fc-field ext_btn"><input type="file" class="fc-file_input" name="' . $name . '" ' . self::get_element_attributes( $atts ) . ' /><label for="file"><span class="icon-upload2" ></span> &nbsp;Choose a file </label><label class="fc-file-details"></label></div>';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Select Input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_select( $name, $atts ) {

			if ( ! isset( $atts['options'] ) || empty( $atts['options'] ) ) {

				return; }

			$options = '';

			$defaul_val = isset( $atts['default_value'] ) ? $atts['default_value'] : '';

			$elem_value = isset( $atts['current'] ) ? $atts['current'] : $defaul_val;

			$optgroup = isset( $atts['optgroup'] ) ? $atts['optgroup'] : 'false';

			$selectable_optgroup = isset( $atts['selectable_optgroup'] ) ? $atts['selectable_optgroup'] : 'false';

			$placeholder = isset( $atts['placeholder'] ) ? $atts['placeholder'] : '';

			$id = ( isset( $atts['id'] ) && ( $atts['id'] != '' ) ) ? $atts['id'] : $name;

			$main_class = ( isset( $atts['class'] ) ) ? $atts['class'] : '';

			if ( 'true' == $optgroup ) {

				if ( 'true' == $selectable_optgroup ) {

					foreach ( $atts['options'] as $opt_name => $values ) {

						foreach ( $values as $key => $value ) {

							if ( $value == $opt_name ) {

								$class = 'optionParent';

							} else {

								$class = 'optionChild';

							}

							$options .= '<option class = "' . $class . '" value="' . esc_attr( $key ) . '" ' . selected( $elem_value, $key, false ) . '>' . $value . '</option>';

						}
					}
				} else {

					foreach ( $atts['options'] as $opt_name => $values ) {

						$options .= '<optgroup label="' . $opt_name . '">';

						foreach ( $values as $key => $value ) {

							$options .= '<option value="' . esc_attr( $key ) . '" ' . selected( $elem_value, $key, false ) . '>' . $value . '</option>';

						}

						$options .= '</optgroup>';

					}
				}
			} else {

				foreach ( $atts['options'] as $key => $value ) {

					$options .= '<option value="' . esc_attr( $key ) . '" ' . selected( $elem_value, $key, false ) . '>' . $value . '</option>';

				}
			}

			$main_class = ( isset( $atts['select2'] ) && ( $atts['select2'] == 'false' ) ) ? 'class="form-control ' . $main_class . '"' : 'class="fc_select2 form-control ' . $main_class . '"';

			$element = '<select id="' . $id . '" ' . $main_class . ' name="' . $name . '" ' . self::get_element_attributes( $atts ) . '>' . $options . '</select>';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_select_field_' . $name, $element, $name, $atts );

		}



		/**

		 * Submit button element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_submit( $name, $atts ) {

			if ( isset( $atts['no-sticky'] ) and $atts['no-sticky'] == 'true' ) {

				$no_sticky = 'fc-no-sticky';

			} else {

				$no_sticky = '';

			}

			$element = '<div class="fc-divider fc-footer ' . $no_sticky . '">

						<div class="fc-12">

						<input type="submit"  name="' . $name . '" ' . self::get_element_attributes( $atts ) . '/>

						</div>

						</div>';

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Button element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_button( $name, $atts ) {

			$eventstr = '';

			if ( isset( $atts['onclick'] ) and ! empty( $atts['onclick'] ) ) {

				$eventstr .= 'onclick =' . stripcslashes( $atts['onclick'] );

			}

			$element = '<input type="button" value="' . $atts['value'] . '"  name="' . $name . '" ' . self::get_element_attributes( $atts ) . ' />';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Checkbox input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_checkbox( $name, $atts ) {

			$id = ( ! empty( $atts['id'] ) ) ? $atts['id'] : $name;

			$display_mode = ( ! empty( $atts['display_mode'] ) ) ? $atts['display_mode'] : '';

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			$desc = '';

			if ( isset( $atts['desc'] ) ) {
				$desc = $atts['desc'];
			}

			$element = '<span class="checkbox ' . $display_mode . '"><input type="checkbox"  id="' . $id . '" name="' . $name . '" value="' . esc_attr( stripcslashes( $value ) ) . '"' . self::get_element_attributes( $atts ) . ' ' . checked( $value, $atts['current'], false ) . '/><label>' . $desc . '</label></span> ';

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}



		/**

		 * Switch input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_checkbox_toggle( $name, $atts ) {

			$atts['id'] = ( ! empty( $atts['id'] ) ) ? $atts['id'] : '';

			$display_mode = ( ! empty( $atts['display_mode'] ) ) ? $atts['display_mode'] : '';

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			$element = '<label class="switch ' . $display_mode . '"><input type="checkbox"  name="' . $name . '" value="' . esc_attr( stripcslashes( $value ) ) . '" ' . self::get_element_attributes( $atts ) . ' ' . checked( $value, $atts['current'], false ) . '/><div class="round slider"></div></label> ';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Multiple Checkbox input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_multiple_checkbox( $name, $atts ) {

			$id = ( ! empty( $atts['id'] ) ) ? $atts['id'] : $name;

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			$element = '';

			$display_mode = ( ! empty( $atts['display_mode'] ) ) ? $atts['display_mode'] : '';

			if ( is_array( $value ) ) {

				foreach ( $value as $key => $val ) {

					if ( is_array( $atts['current'] ) and in_array( $key, $atts['current'] ) ) {

						$element .= '<span class="checkbox ' . $display_mode . '"><input type="checkbox"  name="' . $name . '" value="' . esc_attr( stripcslashes( $key ) ) . '"' . self::get_element_attributes( $atts ) . ' checked="checked" /><label>' . $val . '</label></span> ';

					} else {

						$element .= '<span class="checkbox ' . $display_mode . '"><input type="checkbox"  name="' . $name . '" value="' . esc_attr( stripcslashes( $key ) ) . '" ' . self::get_element_attributes( $atts ) . ' /><label>' . $val . '</label></span> '; }
				}
			}

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Anchor tag element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_anchor( $name, $atts ) {

			$style = ( isset( $atts['show'] ) and $atts['show'] == 'false' ) ? "style='display:none;'" : '';

			$id = ( ! empty( $atts['id'] ) ) ? $atts['id'] : $name;

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];

			if ( ! isset( $atts['target'] ) ) {

				$atts['target'] = '';

			}

			if ( ! isset( $atts['id'] ) ) {

				$atts['id'] = $name;

			}

			$element = '<a ' . $style . '  href="' . $atts['href'] . '"  target="' . $atts['target'] . '" id="' . $atts['id'] . '" name="' . $name . '" ' . self::get_element_attributes( $atts ) . '/>' . $value . '</a>';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Radio input element.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_radio( $name, $atts ) {

			if ( ! isset( $atts['default_value'] ) ) {
				$atts['default_value'] = '';
			}

			$elem_value = $atts['current'] ? $atts['current'] : $atts['default_value'];

			$element = '';

			$radio_options = $atts['radio-val-label'];

			if ( is_array( $atts['radio-val-label'] ) ) {

				 $display_mode = ( ! empty( $atts['display_mode'] ) ) ? $atts['display_mode'] : '';

				foreach ( $radio_options as $radio_val => $radio_label ) {

					$element .= '<span class="radio ' . $display_mode . '"><input type="radio" name="' . $name . '" value="' . esc_attr( stripcslashes( $radio_val ) ) . '"' . self::get_element_attributes( $atts ) . ' ' . checked( $radio_val, $elem_value, false ) . '><label>' . $radio_label . '</label></span>';

				}
			}

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$element .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 * Message boxes.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_message( $name, $atts ) {

			$id = isset( $atts['id'] ) ? $atts['id'] : '';

			$element = '<div ' . self::get_element_attributes( $atts ) . '>' . $atts['value'] . '</div>';

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}

		/**

		 *  Sub heading
		 *
		 * @param  string $heading heading.

		 * @return html   blockquote html wrapper.
		 */

		public static function sub_heading( $heading ) {

			return '<div class="fc-12">

					<blockquote>

					' . $heading . '

					</blockquote>

					</div>';

		}



		public static function field_tab( $name, $atts ) {

			$tabs = $atts['tabs'];

			$current = $atts['current'];

			$page = $atts['page'];

			$parent_page_slug = $atts['parent_page_slug'];

			if ( ! empty( $parent_page_slug ) and $parent_page_slug = 'page' ) {

				$pageslug = 'edit.php?post_type=page&page=';

			} else {

				$pageslug = '?page='; }

			$element = '<h2 class="nav-tab-wrapper">';

			foreach ( $tabs as $tab => $name ) {

				$class = ( $tab == $current ) ? ' nav-tab-active' : '';

				$element .= "<a class='nav-tab$class' href='$pageslug$page&tab=$tab'>$name</a>";

			}

			return apply_filters( 'wpgmp_input_field_' . $name, $element, $name, $atts );

		}



		/**

		 * Table generator.
		 *
		 * @param  string $name Element name.

		 * @param  array  $atts Attributes.

		 * @return html       Element Html.
		 */

		public static function field_table( $name, $atts ) {

			$heads = $atts['heading'];

			$data = $atts['data'];

			$current = $atts['current'];

			$id = ( isset( $atts['id'] ) ) ? $atts['id'] : $name;

			if ( ! isset( $atts['class'] ) or '' == $atts['class'] ) {

				$atts['class'] = 'fc-table fc-table-layout3 dataTable';

			}

			$output = '<div class="fc-table-responsive"><table ' . self::get_element_attributes( $atts ) . ' id="' . $id . '"><thead><tr>';

			if ( is_array( $heads ) ) {

				foreach ( $heads as $head ) {

					$output .= '<th><strong>' . $head . '</strong></th>';

				}
			}

			$output .= '</tr></thead><tbody>';

			if ( ! empty( $data ) ) {

				foreach ( $data as $row => $columns ) {

					$output .= '<tr>';

					foreach ( $columns as $key => $col ) {

						$output .= '<td>' . ( $col ) . '</td>'; }

					$output .= '</tr>';

				}
			}

			$output .= '</tbody></table></div>';

			return apply_filters( 'wpgmp_input_field_' . $name, $output, $name, $atts );

		}

		/**

		 * Show success or error message.
		 *
		 * @param  array $response Success or Error message.

		 * @return html          Success or error message wrapper.
		 */

		public static function show_message( $response ) {

			if ( empty( $response ) ) {

				return; }

			$output = '';

			$output .= '<div id="message" class="' . $response['type'] . '">';

			$output .= '<p><strong>' . $response['message'] . '</strong></p></div>';

			return $output;

		}

		/**

		 * Button Wrapper
		 *
		 * @param  string $title Button title.

		 * @param  url    $link  Link url.

		 * @return html       Button wrapper.
		 */

		public static function button( $title, $link ) {

			return '<span class="glyphicon glyphicon-add wpgmp_new_add button action"><a href="' . esc_html( $link ) . '">' . $title . '</a></span>';

		}



		public static function field_category_selector( $name, $atts ) {

			$data = ( isset( $atts['data'] ) && ! empty( $atts['data'] ) ) ? $atts['data'] : array();

			$placeholder = $atts['placeholder'];

			$id = ( isset( $atts['id'] ) && ( $atts['id'] != '' ) ) ? $atts['id'] : $name;

			$class = $atts['class'];

			$options = '';

			if ( isset( $atts['data_type'] ) && ! empty( $atts['data_type'] ) && $atts['data_type'] != '' ) {

				$data_type = explode( '=', $atts['data_type'] );

				switch ( $data_type[0] ) {

					case 'all_terms':
						$post_types = get_post_types();

						foreach ( $post_types as $post_type ) {

								$taxonomy_names = get_object_taxonomies( $post_type );

								$terms = get_terms( $taxonomy_names, array( 'hide_empty' => false ) );

							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

								foreach ( $terms as $term ) {

									$selected = in_array( $term->term_id, $atts['current'] ) ? 'selected="selected"' : '';

									$options .= "<option value='{$term->term_id}' {$selected}>{$term->name}</option>";

								}
							}
						}

						break;

					case 'cpt':
						$all_post_type = array(

							'_builtin' => false,

							'public'   => true,

						);

						$types = get_post_types( $all_post_type );

						$post = ( is_array( $atts['current'] ) && in_array( 'post', $atts['current'] ) ) ? 'selected="selected"' : '';

						$page = ( is_array( $atts['current'] ) && in_array( 'page', $atts['current'] ) ) ? 'selected="selected"' : '';

						$options .= "<option value='post' {$post}>Post</option>";

						$options .= "<option value='page' {$page}>Pages</option>";

						foreach ( $types as $type ) {

							$all_post_type[] = $type;

							$selected = ( is_array( $atts['current'] ) && in_array( $type, $atts['current'] ) ) ? 'selected="selected"' : '';

							$options .= "<option value='{$type}' {$selected}>" . ucwords( $type ) . '</option>';

						}

						break;

					case 'taxonomy':
						$terms = get_terms( $data_type[1], array( 'hide_empty' => 0 ) );

						if ( ! is_wp_error( $terms ) ) {

							foreach ( $terms as $term ) {

								$selected = ( is_array( $atts['current'] ) && in_array( $term->term_id, $atts['current'] ) ) ? 'selected="selected"' : '';

								$options .= "<option value='{$term->term_id}' {$selected}>{$term->name}</option>";

							}
						}

						break;

					case 'dpt':
						$post_types = array( 'post', 'page' );

						foreach ( $post_types as $post_type ) {

							  $selected = ( is_array( $atts['current'] ) && in_array( $post_type, $atts['current'] ) ) ? 'selected="selected"' : '';

							  $options .= "<option value='{$post_type}' {$selected} >{$post_type}</option>";

						}

						break;

					case 'post_type':
						$posts = get_posts(

							apply_filters(

								'fc_control_post_query_args',
								array(

									'post_type'      => ( isset( $data_type[1] ) ) ? $data_type[1] : 'post',

									'posts_per_page' => -1,

								),
								$name
							)
						);

						foreach ( $posts as $post ) {

								$selected = ( is_array( $atts['current'] ) && in_array( $post->ID, $atts['current'] ) ) ? 'selected="selected"' : '';

								$options .= "<option value='{$post->ID}' {$selected} >{$post->post_title}</option>";

						}

						break;

					case 'users':
						$users = isset( $data_type[1] ) ? get_users( apply_filters( 'fc_control_user_query', array( 'role' => $data_type[1] ), $name ) ) : get_users( array() );

						foreach ( $users as $user ) {

							  $selected = ( is_array( $atts['current'] ) && in_array( $user->data->ID, $atts['current'] ) ) ? 'selected="selected"' : '';

							  $options .= "<option value='{$user->data->ID}' {$selected} >{$user->data->user_login}</option>";

						}

						break;

				}
			} elseif ( ! empty( $data ) ) {

				foreach ( $data as $row ) {

					$selected = '';

					if ( is_array( $atts['current'] ) && ! empty( $atts['current'] ) ) {

						$selected = ( in_array( $row['text'], $atts['current'] ) ) ? 'selected' : '';
					}

					if ( is_array( $atts['current'] ) && ! empty( $atts['current'] ) ) {

						$selected = ( in_array( $row['id'], $atts['current'] ) ) ? 'selected' : '';
					}

					$options .= "<option value='{$row['id']}' {$selected}>{$row['text']}</option>";

				}
			}

			if ( $atts['multiple'] == 'false' ) {

				$multiple_select = '';

			} else {

				$multiple_select = 'multiple="multiple"';

			}

			$output = '

		   <select id="' . $id . '" class="fc_select2 form-control ' . $class . '" name="' . $name . '[]" data-tags="true" data-placeholder="' . $placeholder . '" data-allow-clear="true" ' . $multiple_select . '>

			 ' . $options . '

		   </select>

		   ';

			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {

				$output .= '<p class="help-block">' . $atts['desc'] . '</p>'; }

			return $output;

		}



	}

}



