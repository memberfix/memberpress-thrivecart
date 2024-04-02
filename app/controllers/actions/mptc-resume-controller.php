<?php

class MPTC_Resume_Controller {
	public function __construct() {
		add_action( 'admin_post_mptc-resume', array( $this, 'callback' ) );
		add_action( 'admin_post_nopriv_mptc-resume', array( $this, 'callback' ) );
	}

	public function callback(){
		if(!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'mptc-resume')){
			die('Something is not right here');
		}

		MPTC_ThriveCart_Controller::resumeSub(new WP_User(get_current_user_id()));
	}
}

new MPTC_Resume_Controller();