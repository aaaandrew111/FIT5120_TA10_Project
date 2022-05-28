<?php
/*
	Plugin Name: Quiz Cat Free
	Plugin URI: https://fatcatapps.com/quiz-cat
	Description: Provides an easy way to create and administer quizzes
	Text Domain: quiz-cat
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 2.1.1
*/


// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



if ( !defined ('FCA_QC_PLUGIN_DIR') ) {

	// DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_QC_DEBUG', FALSE );
	if ( FCA_QC_DEBUG ) {
		define( 'FCA_QC_PLUGIN_VER', '2.1.' . time() );
	} else {
		define( 'FCA_QC_PLUGIN_VER', '2.1.1' );
	}
	define( 'FCA_QC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_QC_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_QC_PLUGINS_BASENAME', plugin_basename(__FILE__) );
	define( 'FCA_QC_PLUGIN_FILE', __FILE__ );
	define( 'FCA_QC_PLUGIN_PACKAGE', 'Free' ); //DONT CHANGE THIS, IT WONT ADD FEATURES, ONLY BREAKS UPDATER AND LICENSE

	include_once( FCA_QC_PLUGIN_DIR . '/includes/functions.php' );
	include_once( FCA_QC_PLUGIN_DIR . '/includes/post-type.php' );
	include_once( FCA_QC_PLUGIN_DIR . '/includes/quiz/quiz.php' );
	include_once( FCA_QC_PLUGIN_DIR . '/includes/editor/editor.php' );	
	include_once( FCA_QC_PLUGIN_DIR . '/includes/block.php' );

	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/editor/sidebar.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/editor/sidebar.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/premium/weighted.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/premium/weighted.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/premium/premium.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/premium/premium.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/premium/optins.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/premium/optins.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/premium/licensing.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/premium/licensing.php' );
	}	
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/premium/db.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/premium/db.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/stats/stats.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/stats/stats.php' );
	}
	if ( file_exists ( FCA_QC_PLUGIN_DIR . '/includes/upgrade.php' ) ) {
		include_once( FCA_QC_PLUGIN_DIR . '/includes/upgrade.php' );
	}	
	
	//FILTERABLE FRONT-END STRINGS
	$global_quiz_text_strings = array (
		'no_quiz_found' => esc_attr__('No Quiz found', 'quiz-cat'),
		'timedout' => esc_attr__('Timed out!', 'quiz-cat'),
		'time_taken' => esc_attr__('Total time taken:', 'quiz-cat'),
		'correct' => esc_attr__('Correct!', 'quiz-cat'),
		'wrong' => esc_attr__('Wrong!', 'quiz-cat'),
		'your_answer' => esc_attr__('Your answer:', 'quiz-cat'),
		'correct_answer' => esc_attr__('Correct answer:', 'quiz-cat'),
		'question' => esc_attr__('Question', 'quiz-cat'),
		'next' =>  esc_attr__('Next', 'quiz-cat'),
		'you_got' =>  esc_attr__('You got', 'quiz-cat'),
		'out_of' => esc_attr__('out of', 'quiz-cat'),
		'your_answers' =>  esc_attr__('Your Answers', 'quiz-cat'),
		'start_quiz' => esc_attr__('Start Quiz', 'quiz-cat'),
		'retake_quiz' => esc_attr__('Retake Quiz', 'quiz-cat'),
		'share_results' => esc_attr__('SHARE YOUR RESULTS', 'quiz-cat'),
		'i_got' => esc_attr__('I got', 'quiz-cat'),
		'skip_this_step' => esc_attr__('Skip this step', 'quiz-cat'),
		'your_name' => esc_attr__('Your Name', 'quiz-cat'),
		'your_email' => esc_attr__('Your Email', 'quiz-cat'),
		'share'  => esc_attr__('Share', 'quiz-cat'),
		'tweet'  =>  esc_attr__('Tweet', 'quiz-cat'),
		'pin'  =>  esc_attr__('Pin', 'quiz-cat'),
		'email'  =>  esc_attr__('Email', 'quiz-cat') 
	);
	
	//ACTIVATION HOOK
	function fca_qc_activation() {
	
		$meta_version = get_option ( 'fca_qc_meta_version' );
		
		if ( function_exists( 'fca_qc_convert_csv') && version_compare( $meta_version, '1.5.0', '<' ) ) {
			//convert CSV from old format to new
			fca_qc_convert_csv();
					
		}
	}
	register_activation_hook( FCA_QC_PLUGIN_FILE, 'fca_qc_activation' );

	function fca_qc_add_plugin_action_links( $links ) {
		
		$support_url = FCA_QC_PLUGIN_PACKAGE === 'Free' ? 'https://wordpress.org/support/plugin/quiz-cat' : 'https://fatcatapps.com/support';
		
		$new_links = array(
			'support' => "<a target='_blank' href='$support_url' >" . esc_attr__('Support', 'quiz-cat' ) . '</a>'
		);
		
		$links = array_merge( $new_links, $links );

		return $links;
		
	}
	add_filter( 'plugin_action_links_' . FCA_QC_PLUGINS_BASENAME, 'fca_qc_add_plugin_action_links' );

	/* Localization */
	function fca_qc_load_localization() {
		load_plugin_textdomain( 'quiz-cat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'fca_qc_load_localization' );
	
	//DEACTIVATION SURVEY
	function fca_qc_admin_deactivation_survey( $hook ) {
		if ( $hook === 'plugins.php' ) {
			
			ob_start(); ?>
			
			<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
				<p style="font-size: 14px; font-weight: bold; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php esc_attr_e( 'Sorry to see you go', 'quiz-cat' ) ?></p>
				<p><?php esc_attr_e( 'Hi, this is David, the creator of Quiz Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'quiz-cat' ) ?>
				</p>
				<p><?php esc_attr_e( 'I have a quick question that I hope you’ll answer to help us make Quiz Cat better: what made you deactivate?', 'quiz-cat' ) ?>
				</p>
				<p><?php esc_attr_e( 'You can leave me a message below. I’d really appreciate it.', 'quiz-cat' ) ?>
				</p>
				<p><b><?php esc_attr_e( 'If you\'re upgrading to Quiz Cat Premium and have questions or need help, click <a href=' . 'https://fatcatapps.com/article-categories/gen-getting-started/' . ' target="_blank">here</a></b>', 'quiz-cat' ) ?>
				</p>
				<p><textarea style='width: 100%;' id='fca-qc-deactivate-textarea' placeholder='<?php esc_attr_e( 'What made you deactivate?', 'quiz-cat' ) ?>'></textarea></p>
				<div style='float: right;' id='fca-deactivate-nav'>
					<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-qc-deactivate-skip'><?php esc_attr_e( 'Skip', 'quiz-cat' ) ?></button>
					<button type='button' class='button button-primary' id='fca-qc-deactivate-send'><?php esc_attr_e( 'Send Feedback', 'quiz-cat' ) ?></button>
				</div>
			
			</div>
			
			<?php
				
			$html = ob_get_clean();
			
			$data = array(
				'html' => $html,
				'nonce' => wp_create_nonce( 'fca_qc_uninstall_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);
						
			wp_enqueue_script('fca_qc_deactivation_js', FCA_QC_PLUGINS_URL . '/includes/deactivation.min.js', false, FCA_QC_PLUGIN_VER, true );
			wp_localize_script( 'fca_qc_deactivation_js', "fca_qc", $data );
		}
		
		
	}	
	add_action( 'admin_enqueue_scripts', 'fca_qc_admin_deactivation_survey' );

}