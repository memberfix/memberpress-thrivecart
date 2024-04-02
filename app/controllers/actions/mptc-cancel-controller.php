<?php

class MPTC_Cancel_Controller {
	public function __construct() {
		add_action( 'admin_post_mptc-cancel', array( $this, 'callback' ) );
		add_action( 'admin_post_nopriv_mptc-cancel', array( $this, 'callback' ) );
	}

	public function callback(){
		if(!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'mptc-cancel')){
			die('Something is not right here');
		}

		MPTC_ThriveCart_Controller::cancelSub(new WP_User(get_current_user_id()));
	}
}

new MPTC_Cancel_Controller();