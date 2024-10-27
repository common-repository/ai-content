<?php


class WAI_Fresh_Settings {


	public static function get_fresh_settings( $context = '' ) {

		$fresh_settings = array();
		switch ( $context ) {
			case 'wai_settings':
				$all_question = array(
					'Write a paragraph',
					'Generate ideas',
					'Write an article',
					'Summarize',
					'Summarize into bullet points',
					'Paraphrase',
					'Find a matching quote',
				);

				$fresh_settings = $all_question;
				break;

			case 'wpr_security_ques_setting':
				$settings['allow_profile_page_update']                 = 'true';
				$settings['allow_sec_ques_login']                      = '';
				$settings['allow_sec_ques_register']                   = '';
				$settings['allow_sec_ques_forgot']                     = '';
				$settings['other_settings']['login_when']              = 'all';
				$settings['other_settings']['login_show_hint']         = 'no';
				$settings['other_settings']['register_field_type']     = 'text';
				$settings['other_settings']['register_field_required'] = 'all';
				$settings['other_settings']['login_when']              = 'all';

				$fresh_settings = $settings;

				break;
		}

		return $fresh_settings;
	}


}
