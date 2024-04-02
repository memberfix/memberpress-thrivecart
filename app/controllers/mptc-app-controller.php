<?php

class MPTC_App_Controller {
	static function maybe_add_option($option, $value){
		if(get_option($option)){
			return update_option($option, $value);
		}else{
			return add_option($option, $value);
		}
	}

	static function maybe_add_user_meta($tag, $value, $user_id){
		if(!get_user_meta($user_id, $tag)){
			return add_user_meta($user_id, $tag, $value);
		}else{
			return update_user_meta($user_id, $tag, $value);
		}
	}

	static function validate_mode(){
		 switch($_POST['mptc-mode']){
			 case 'test':
			 case 'live':
				 return true;
				 break;
			 default:
				 return false;
		 }
	}

	public function __construct(){
		add_action('admin_menu', array($this, 'pages'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
		add_action('admin_post_mptc-settings-form', array($this, 'form_callback'));
	}

	public function pages() {
		add_management_page(__('MemberPress ThriveCart', WPPL_SLUG), __('MemberPress ThriveCart', WPPL_SLUG), 'activate_plugins', 'memberfix-thrivecart', array($this,'page_callback'));
	}

	public function page_callback(){
		return new WPPL_View('settings');
	}

	public function enqueue(){
		global $hook_suffix;

		if($hook_suffix == 'tools_page_memberfix-thrivecart'){
			wp_enqueue_script('mptc-settings-script', WPPL_JS . '/settings.js', array(), false, true);
			wp_enqueue_style('mptc-settings-style', WPPL_CSS . '/settings.css', array(), false);
		}
	}

	public function form_callback(){
		$this->validate_settings();

		self::maybe_add_option('mptc-api-key', $_POST['mptc-api-key']);
		self::maybe_add_option('mptc-webhook-secret', $_POST['mptc-webhook-secret']);
		self::maybe_add_option('mptc-mode', $_POST['mptc-mode']);

		return WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'success', 'Settings were saved');
	}

	private function validate_settings(){
		if(!WPPL_Form::check_nonce($_POST['mptc_nonce'], 'mptc-settings-form')){
			die();
		}

		if(!isset($_POST['mptc-api-key']) || empty($_POST['mptc-api-key'])){
			WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'error', 'The API key field cannot be empty');
			die();
		}

		if(!isset($_POST['mptc-webhook-secret']) || empty($_POST['mptc-webhook-secret'])){
			WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'error', 'The Webhook secret field cannot be empty');
			die();
		}

		if(!isset($_POST['mptc-mode']) || empty($_POST['mptc-mode'])){
			WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'error', 'Mode cannot be empty');
			die();
		}

		if(!self::validate_mode()){
			WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'error', 'There is no such mode');
			die();
		}

		if(!MPTC_ThriveCart_Controller::ping($_POST['mptc-api-key'])){
			WPPL_Helper::redirect('/wp-admin/tools.php?page=memberfix-thrivecart', 'error', 'The API key is invalid');
			die();
		}
	}
}

new MPTC_App_Controller();