<?php

$form = new WAI_FORM();
echo $form->show_header();

if ( class_exists( 'FlipperCode_List_Table_Helper' ) and ! class_exists( 'wai_Prompt_Table' ) ) {

	class wai_Prompt_Table extends FlipperCode_List_Table_Helper {
		public function __construct( $tableinfo ) {
			parent::__construct( $tableinfo ); }  }

	// Minimal Configuration :)
	global $wpdb;
	$columns   = array(
		'title'       => esc_html__( 'Title', 'text-prompter' ),
		'words'       => esc_html__( 'Words', 'text-prompter' ),
		'model'       => esc_html__( 'Model', 'text-prompter' ),
		'temperature' => esc_html__( 'Temperature', 'text-prompter' ),
	);
	$sortable  = array( 'title' );
	$tableinfo = array(
		'table'                   => $wpdb->prefix . 'wai_prompts',
		'textdomain'              => 'text-prompter',
		'singular_label'          => esc_html__( 'Prompt', 'text-prompter' ),
		'plural_label'            => esc_html__( 'Prompts', 'text-prompter' ),
		'admin_listing_page_name' => 'wai_manage_prompt',
		'admin_add_page_name'     => 'wai_form_prompt',
		'primary_col'             => 'id',
		'columns'                 => $columns,
		'sortable'                => $sortable,
		'per_page'                => 200,
		'actions'                 => array( 'edit', 'delete' ),
		'bulk_actions'            => array(
			'delete'            => esc_html__( 'Delete', 'text-prompter' ),
			'export_prompt_csv' => esc_html__( 'Export CSV', 'text-prompter' ),
		),
		'col_showing_links'       => 'title',
		'translation'             => array(
			'manage_heading' => esc_html__( 'Manage Prompts', 'text-prompter' ),
			'add_button'     => esc_html__( 'Add Prompt', 'text-prompter' ),
			'delete_msg'     => esc_html__( 'Prompt was deleted successfully.', 'text-prompter' ),
			'insert_msg'     => esc_html__( 'Prompt was added successfully.', 'text-prompter' ),
			'update_msg'     => esc_html__( 'Prompt was updated successfully.', 'text-prompter' ),
			'search_text'    => esc_html__( 'Search', 'text-prompter' ),
		),
	);
	return new wai_Prompt_Table( $tableinfo );

}
