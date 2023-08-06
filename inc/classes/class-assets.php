<?php
/**
 * Enqueue theme assets
 *
 * @package FutureWordPressScratchProject
 */


namespace STOCK_PHOTO_DUPLICATOR\Inc;

use STOCK_PHOTO_DUPLICATOR\Inc\Traits\Singleton;

class Assets {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_denqueue_scripts' ], 99 );
		/**
		 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend,
		 * except when is_admin() is used to include them conditionally
		 */
		// add_action( 'enqueue_block_assets', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_denqueue_scripts' ], 99 );

		add_filter( 'futurewordpress/project/javascript/siteconfig', [ $this, 'siteConfig' ], 1, 1 );
	}

	public function register_styles() {
		$version = $this->filemtime(STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH.'/backend.css');
		wp_enqueue_style('stockphotoduplicator', STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI.'/backend.css?jsversion='.$version, [], $version, 'all');
	}

	public function register_scripts() {
		$version = $this->filemtime(STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH.'/backend.js');
		wp_enqueue_script('stockphotoduplicator', STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI.'/backend.js?jsversion='.$version, ['jquery'], $version, true);
		wp_enqueue_media();
		wp_localize_script('stockphotoduplicator', 'fwpSiteConfig', apply_filters('futurewordpress/project/javascript/siteconfig', []));
	}
	private function allow_enqueue() {
		return ( function_exists( 'is_checkout' ) && ( is_checkout() || is_order_received_page() || is_wc_endpoint_url( 'order-received' ) ) );
	}

	public function admin_enqueue_scripts( $curr_page ) {
		global $post;
		// if( ! in_array( $curr_page, [ 'edit.php', 'post.php' ] ) || 'shop_order' !== $post->post_type ) {return;}
		wp_register_style('stockphotoduplicator', STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI.'/backend.css', [], $this->filemtime(STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH.'/backend.css'), 'all');
		wp_register_script('stockphotoduplicator', STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI.'/backend.js', ['jquery'], $this->filemtime(STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH.'/backend.js'), true);

		// wp_register_style( 'FutureWordPressScratchProjectBackend', 'https://templates.iqonic.design/product/qompac-ui/html/dist/assets/css/qompac-ui.min.css?v=1.0.1', [], false, 'all' );
		wp_register_style( 'FutureWordPressScratchProjectBackend', STOCK_PHOTO_DUPLICATOR_BUILD_LIB_URI . '/css/backend-library.css', [], false, 'all' );
		wp_register_script( 'FutureWordPressScratchProjectBackend', STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI . '/backend-library.js', [ 'jquery' ], $this->filemtime( STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH . '/backend-library.js' ), true );
		
		wp_enqueue_style( 'stockphotoduplicator' );
		wp_enqueue_script( 'stockphotoduplicator' );
		wp_enqueue_media();

		wp_localize_script( 'stockphotoduplicator', 'fwpSiteConfig', apply_filters( 'futurewordpress/project/javascript/siteconfig', [] ) );
	}
	private function filemtime($file) {
		return time();
		// return (file_exists($file) && !is_dir($file))?filemtime($time):rand(0, 999999);
	}
	public function siteConfig( $args ) {
		return wp_parse_args( [
			'ajaxUrl'    		=> admin_url( 'admin-ajax.php' ),
			'ajax_nonce' 		=> wp_create_nonce( 'futurewordpress/project/verify/nonce' ),
			'is_admin' 			=> is_admin(),
			'buildPath'  		=> STOCK_PHOTO_DUPLICATOR_BUILD_URI,
			'videoClips'  		=> ( function_exists( 'WC' ) && WC()->session !== null ) ? (array) WC()->session->get( 'uploaded_files_to_archive' ) : [],
			'i18n'					=> [
				'sureToSubmit'							=> __( 'Want to submit it? You can retake.', 'stock-photo-duplicator' ),
				'uploading'									=> __( 'Uploading', 'stock-photo-duplicator' ),
				'click_here'								=> __( 'Click here', 'stock-photo-duplicator' ),
				'video_exceed_dur_limit'		=> __( 'Video exceed it\'s duration limit.', 'stock-photo-duplicator' ),
				'file_exceed_siz_limit'			=> __( 'Filesize exceed it maximum limit 30MB.', 'stock-photo-duplicator' ),
				'audio_exceed_dur_limit'		=> __( 'Audio exceed it\'s duration limit.', 'stock-photo-duplicator' ),
				'invalid_file_formate'			=> __( 'Invalid file formate.', 'stock-photo-duplicator' ),
				'device_error'							=> __( 'Device Error', 'stock-photo-duplicator' ),
				'confirm_cancel_subscribe'	=> __( 'Do you really want to cancel this Subscription?', 'stock-photo-duplicator' ),
				'i_confirm_it'							=> __( 'Yes I confirm it', 'stock-photo-duplicator' ),
				'confirming'								=> __( 'Confirming', 'stock-photo-duplicator' ),
				'successful'								=> __( 'Successful', 'stock-photo-duplicator' ),
				'request_failed'						=> __( 'Request failed', 'stock-photo-duplicator' ),
				'submit'										=> __( 'Submit', 'stock-photo-duplicator' ),
				'cancel'										=> __( 'Cancel', 'stock-photo-duplicator' ),
				'registration_link'					=> __( 'Registration link', 'stock-photo-duplicator' ),
				'password_reset'						=> __( 'Password reset', 'stock-photo-duplicator' ),
				'give_your_old_password'		=> __( 'Give here your old password', 'stock-photo-duplicator' ),
				'you_paused'								=> __( 'Subscription Paused', 'stock-photo-duplicator' ),
				'you_paused_msg'						=> __( 'Your retainer subscription has been successfully paused. We\'ll keep your account on hold until you\'re ready to resume. Thank you!', 'stock-photo-duplicator' ),
				'you_un_paused'							=> __( 'Subscription Resumed', 'stock-photo-duplicator' ),
				'you_un_paused_msg'					=> __( 'Welcome back! Your retainer subscription has been successfully resumed. We\'ll continue to provide you with our services as before. Thank you!', 'stock-photo-duplicator' ),
				'are_u_sure'								=> __( 'Are you sure?', 'stock-photo-duplicator' ),
				'sure_to_delete'						=> __( 'Are you sure about this deletation. Once you permit to delete, this user data will be removed from database forever. This can\'t be Undone', 'stock-photo-duplicator' ),
				'sent_reg_link'							=> __( 'Registration Link sent successfully!', 'stock-photo-duplicator' ),
				'sent_passreset'						=> __( 'Password reset link sent Successfully!', 'stock-photo-duplicator' ),
				'sometextfieldmissing'			=> __( 'Some required field you missed. Pleae fillup them first, then we can proceed.', 'stock-photo-duplicator' ),
				'retainer_zero'							=> __( 'Retainer Amount Zero', 'stock-photo-duplicator' ),
				'retainer_zerowarn'					=> __( 'You must set retainer amount before send a registration email.', 'stock-photo-duplicator' ),
				'selectcontract'						=> __( 'Select Contract', 'stock-photo-duplicator' ),
				'sure2logout'								=> __( 'Are you to Logout?', 'stock-photo-duplicator' ),
				'selectcontractwarn'				=> __( 'Please choose a contract to send the registration link. Once you have selected a contract and updated the form, you will be able to send the registration link.', 'stock-photo-duplicator' ),
				'subscription_toggled'			=> __( 'Thank you for submitting your request. We have reviewed and accepted it, and it is now pending for today. You will have the option to change your decision tomorrow. Thank you for your patience and cooperation.', 'stock-photo-duplicator' ),
				'rusure2unsubscribe'				=> __( 'You can only pause you retainer once every 60 days. Are you sure you want to pause your retainer?', 'stock-photo-duplicator' ),
				'rusure2subscribe'					=> __( 'We are super happy you want to resume your retainer. Are you sure you want to start now?', 'stock-photo-duplicator' ),
				'say2wait2pause'						=> __( 'You\'ve already paused your subscription this month. Please wait until 60 days over to pause again. If you need further assistance, please contact our administrative team.', 'stock-photo-duplicator' ),
			],
			'leadStatus'		=> apply_filters( 'futurewordpress/project/action/statuses', ['no-action' => __( 'No action fetched', 'stock-photo-duplicator' )], false )
		], (array) $args );
	}
	public function wp_denqueue_scripts() {}
	public function admin_denqueue_scripts() {
		if( ! isset( $_GET[ 'page' ] ) ||  $_GET[ 'page' ] !='crm_dashboard' ) {return;}
		wp_dequeue_script( 'qode-tax-js' );
	}

}
