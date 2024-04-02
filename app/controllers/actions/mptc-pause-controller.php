<?php

class MPTC_Pause_Controller {
	public function __construct() {
		add_action( 'admin_post_mptc-pause', array( $this, 'callback' ) );
		add_action( 'admin_post_nopriv_mptc-pause', array( $this, 'callback' ) );
	}

	public function callback(){
		if(!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'mptc-pause')){
			die('Something is not right here');
		}

		MPTC_ThriveCart_Controller::pauseSub(new WP_User(get_current_user_id()));
	}
}

new MPTC_Pause_Controller();