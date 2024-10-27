<?php
/**
 * Class: WAI_Model_Prompt
 *
 * @author Flipper Code <hello@flippercode.com>
 * @package Updates
 * @version 1.0.0
 */

if ( ! class_exists( 'WAI_Model_Prompt' ) ) {

	/**
	 * Notification model for CRUD operation.
	 *
	 * @package updates
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WAI_Model_Prompt extends FlipperCode_Model_Base {

		/**
		 * Validations on update properies.
		 *
		 * @var array
		 */
		protected $validations;
		/**
		 * Intialize rule object.
		 */
		public function __construct() {
			global $wpdb;
			$this->table       = $wpdb->prefix . 'wai_prompts';
			$this->unique      = 'id';
			$this->validations = array(
				'title'   => array( 'req' => esc_html__( 'Title is required', 'text-prompter' ), 'alphanum' => esc_html__( 'Title: Use only alphabets and numbers.', 'text-prompter' ) ),
				'prompts' => array( 'req' => esc_html__( 'Text Prompt is required.', 'text-prompter' )),
				'words' => array( 'num' => esc_html__( 'Words: Enter number only.', 'text-prompter' )),
				'temperature' => array( 'float' => esc_html__( 'Temperature: Enter number only between 0 to 1.', 'text-prompter' )),
				'top_p' => array( 'float' => esc_html__( 'Top P: Enter number only between 0 to 1.', 'text-prompter' )),
				'frequency_penalty' => array( 'float' => esc_html__( 'Frequency Penalty: Enter number only between 0 to 1.', 'text-prompter' )),
				'presence_penalty' => array( 'float' => esc_html__( 'Presence Penalty: Enter number only between 0 to 1.', 'text-prompter' ),
				 ),
			);
		}
		/**
		 * Admin menu for CRUD Operation
		 *
		 * @return array Admin meny navigation(s).
		 */
		public function navigation() {

			return array(
				'wai_form_prompt'   => __( 'New Prompt', 'text-prompter' ),
				'wai_manage_prompt' => __( 'Manage Prompts', 'text-prompter' ),
				'wai_import_prompt' => __( 'Import Prompts', 'text-prompter' ),
			);
		}
		/**
		 * Install table associated with Rule entity.
		 *
		 * @return string SQL query to install wup_updates table.
		 */
		public function install() {

			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();
			
			$update_tbl = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'wai_prompts (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `title` varchar(255) NOT NULL,
                                `prompts` longtext NOT NULL,
                                `words` int(11) NOT NULL,
                                `model` varchar(255) NOT NULL,
                                `temperature` varchar(255) NOT NULL,
                                `top_p` varchar(255) NOT NULL,
                                `frequency_penalty` varchar(255) NOT NULL,
                                `presence_penalty` varchar(255) NOT NULL,
                                PRIMARY KEY (`id`)
							   ) '.$charset_collate.';';

			return $update_tbl;
		}
		/**
		 * Get Rule(s)
		 *
		 * @param  array $where  Conditional statement.
		 * @return array         Array of Rule object(s).
		 */
		public function fetch( $where = array() ) {

			$objects = $this->get( $this->table, $where );

			if ( isset( $objects ) ) {

				return $objects;
			}
		}

		/**
		 * Add or Edit Operation.
		 */
		public function save() {

			global $_POST;
			$entityID = '';
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
				die( 'Cheating...' );
			}

			$this->verify( $_POST );

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}

			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
			}

			if ( isset( $_POST['prompts'] ) ) {
				$data['prompts'] = sanitize_textarea_field( wp_unslash( $_POST['prompts'] ) );
			}

			$data['title']             = sanitize_text_field( wp_unslash( $_POST['title'] ) );
			$data['words']             = sanitize_text_field( wp_unslash( $_POST['words'] ) );
			$data['temperature']       = sanitize_text_field( wp_unslash( $_POST['temperature'] ) );
			$data['model']             = sanitize_text_field( wp_unslash( $_POST['model'] ) );
			$data['top_p']             = sanitize_text_field( wp_unslash( $_POST['top_p'] ) );
			$data['frequency_penalty'] = sanitize_text_field( wp_unslash( $_POST['frequency_penalty'] ) );
			$data['presence_penalty']  = sanitize_text_field( wp_unslash( $_POST['presence_penalty'] ) );

			if ( $entityID > 0 ) {
				$where[ $this->unique ] = $entityID;
			} else {
				$where = '';
			}

			$result = FlipperCode_Database::insert_or_update( $this->table, $data, $where );

			if ( false === $result ) {
				$response['error'] = esc_html__( 'Something went wrong. Please try again.', 'text-prompter' );
			} elseif ( $entityID > 0 ) {
				$response['success'] = esc_html__( 'Prompt was updated successfully.', 'text-prompter' );
			} else {
				$response['success'] = esc_html__( 'Prompt was added successfully.', 'text-prompter' );
			}

			$response['last_db_id'] = $result;

			return $response;
		}


		/**
		 * Delete prompt object by id.
		 */
		public function delete() {
			if ( isset( $_GET['id'] ) ) {
				$id          = intval( wp_unslash( $_GET['id'] ) );
				$connection  = FlipperCode_Database::connect();
				$this->query = $connection->prepare( "DELETE FROM $this->table WHERE $this->unique='%d'", $id );
				return FlipperCode_Database::non_query( $this->query, $connection );
			}
		}


		/**
		 * Clone prompt by id.
		 */
		function copy( $update_id ) {
			if ( isset( $update_id ) ) {
				$id   = intval( wp_unslash( $update_id ) );
				$map  = $this->get( $this->table, array( array( 'id', '=', $id ) ) );
				$data = array();
				foreach ( $map[0] as $column => $value ) {

					if ( $column == 'id' ) {
						continue; } elseif ( $column == 'title' ) {
						$data[ $column ] = $value . ' ' . __( 'Copy', 'text-prompter' );
						} else {
							$data[ $column ] = $value; }
				}

				$result = FlipperCode_Database::insert_or_update( $this->table, $data );
			}
		}
		/**
		 * Export data into csv
		 *
		 * @param  string $type File Type.
		 */
		function export( $type = 'csv' ) {

			if ( isset( $_POST ) && ( $_POST['action'] == 'export_prompt_csv' ) && empty( $_POST['id'] ) ) {
				$response['error'] = esc_html__( 'Please select prompts to export.', 'text-prompter' );
				return $response;
			}

			$selected_prompts = array();
			if ( isset( $_POST['id'] ) and is_array( $_POST['id'] ) ) {
				$selected_prompts = array_map( 'sanitize_text_field', $_POST['id'] );
			}

			$all_prompts = $this->fetch();
			$file_name   = sanitize_file_name( 'prompt_' . $type . '_' . time() );

			foreach ( $all_prompts as $prompt ) {
				if ( ! empty( $selected_prompts ) && ! in_array( $prompt->id, $selected_prompts ) ) {
					continue;
				}

				$loc_data = array(
					'id'                => $prompt->id,
					'title'             => $prompt->title,
					'prompts'           => $prompt->prompts,
					'words'             => $prompt->words,
					'model'             => $prompt->model,
					'temperature'       => $prompt->temperature,
					'top_p'             => $prompt->top_p,
					'frequency_penalty' => $prompt->frequency_penalty,
					'presence_penalty'  => $prompt->presence_penalty,
				);

				$data_prompts[] = $loc_data;
			}

			$head_columns = array(
				'id',
				'title',
				'prompts',
				'words',
				'model',
				'temperature',
				'top_p',
				'frequency_penalty',
				'presence_penalty',
			);

			$exporter = new FlipperCode_Export_Import( $head_columns, $data_prompts );
			$exporter->export( $type, $file_name );
			die();
		}

		/**
		 * Import Prompt via CSV,JSON,XML and Excel.
		 *
		 * @return array Success or Failure error message.
		 */
		public function prompt_fields() {

			$response = false;
			$result   = false;
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

				die( 'Cheating...' );

			}
			if ( isset( $_POST['import_loc'] ) ) {
				if ( isset( $_FILES['import_file']['tmp_name'] ) and '' == sanitize_file_name( wp_unslash( $_FILES['import_file']['tmp_name'] ) ) ) {
					$response['error'] = esc_html__( 'Please select file to be imported.', 'text-prompter' );
				} elseif ( isset( $_FILES['import_file']['name'] ) and ! $this->validate_extension( sanitize_file_name( wp_unslash( $_FILES['import_file']['name'] ) ) ) ) {
					$response['error'] = esc_html__( 'Please upload a valid csv file.', 'text-prompter' );
				} else {

					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}

					$uploadedfile     = $_FILES['import_file'];
					$upload_overrides = array( 'test_form' => false );

					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

					if ( $movefile && ! isset( $movefile['error'] ) ) {
						update_option( 'wai_current_csv', $movefile );
					} else {
						$response['error'] = $movefile['error'];
					}
				}
				return $response;
			}
		}

		public function cancel_import() {
			$current_csv = get_option( 'wai_current_csv' );
			unlink( $current_csv['file'] );
			delete_option( 'wai_current_csv' );
		}

		public function import_prompt() {
			$result = false;

			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

				die( 'Cheating...' );

			}

			if ( isset( $_POST['import_loc'] ) ) {

					$current_csv = get_option( 'wai_current_csv' );
				if ( ! is_array( $current_csv ) or ! file_exists( $current_csv['file'] ) ) {
					$response['error'] = esc_html__( 'Something went wrong. Please start import process again.', 'text-prompter' );
					return $response;
				}

					$csv_columns       = array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['csv_columns'] ) );
					$colums_mapping    = array();
					$duplicate_columns = array();

					// Unset unasigned field
				foreach ( $csv_columns as $key => $value ) {

					if ( $value == '' ) {
						unset( $csv_columns[ $key ] );
					}
				}

					// Find duplicate fields
					$duplicate_columns = array_count_values( $csv_columns );

					$not_allowed = array();
				foreach ( $duplicate_columns as $name => $count ) {

					if ( $count > 1 ) {
						$not_allowed[] = $name;
					}
				}

				if ( count( $csv_columns ) == 0 ) {
					$response['error'] = _( 'Please map prompts fields to csv columns.', 'text-prompter' );

					return $response;
				}

				$is_update_process = false;

				if ( in_array( 'id', $csv_columns ) !== false ) {
					$is_update_process = true;
				}

				if ( count( $not_allowed ) > 0 ) {
					$wrongly_mapped    = implode( ',', $not_allowed );
					$response['error'] = esc_html__( 'Duplicate mapping is not allowed. Please check these fields : ', 'text-prompter' ) . $wrongly_mapped;
					return $response;
				}

					// Address and title is required if add process.
				if ( $is_update_process == false ) {

					if ( in_array( 'title', $csv_columns ) === false or in_array( 'prompts', $csv_columns ) === false ) {
						$response['error'] = esc_html__( 'Title & Prompt fields are required.', 'text-prompter' );
						return $response;
					}
				}


				if ( count( $csv_columns ) > 0 ) {
					$importer  = new FlipperCode_Export_Import();
					$file_data = $importer->import( 'csv', $current_csv['file'] );


					if ( ! empty( $file_data ) ) {

						unset( $file_data[0] );

						$modelFactory = new WAI_Model();

						foreach ( $file_data as $data ) {

							$all_data_in_string = implode( ' ', $data );

							if ( empty( trim( $all_data_in_string ) ) || trim( $all_data_in_string ) == '' ) {
								continue;
							}

							$datas             = array();
							$location_settings = array();
							foreach ( $data as $key => $value ) {

								if ( ! isset( $csv_columns[ $key ] ) || trim( $csv_columns[ $key ] ) == '' ) {
									continue;
								}

								if( $csv_columns[ $key ] == 'prompts'){
									$datas[ $csv_columns[ $key ] ] = sanitize_textarea_field(trim( $value ));	
								} else {
									$datas[ $csv_columns[ $key ] ] = sanitize_text_field(trim( $value ));
								}

							}

							$entityID = '';
							if ( isset( $datas['id'] ) ) {
								$entityID = intval( wp_unslash( $datas['id'] ) );
								unset( $datas['id'] );
							}

							// Rest Columns are extra fields.
							if ( $entityID > 0 ) {
								$where[ $this->unique ] = $entityID;
							} else {
								$where = '';
							}

							$datas = array_filter( $datas );

							if ( count( $datas ) == 0 ) {
								continue;
							}

							$datas = apply_filters( 'fc_import_location_data', $datas );

							$result = FlipperCode_Database::insert_or_update( $this->table, $datas, $where );

						}

						$response['success'] = count( $file_data ) . ' ' . esc_html__( 'records imported successfully.', 'text-prompter' );
						// Here remove the temp file.
						unlink( $current_csv['file'] );
						delete_option( 'wai_current_csv' );

					} else {
						$response['error'] = esc_html__( 'No records found in the csv file.', 'text-prompter' );
					}
				} else {
					$response['error'] = esc_html__( 'Please assign fields to the csv columns.', 'text-prompter' );
				}

				return $response;
			}
		}
	}
}
