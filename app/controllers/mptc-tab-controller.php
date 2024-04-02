<?php

class MPTC_Tab_Controller{
	private $slug = 'tc-sub-management';

	public function __construct(){
		add_action('mepr_account_nav', array($this, 'tab'));
		add_action('mepr_account_nav_content', array($this, 'tab_content'));
	}

	public function tab($user){
        if(MPTC_ThriveCart_Controller::is_subscribed($user->ID)):
		?>
			<span class="mepr-nav-item prem-support <?php MeprAccountHelper::active_nav('premium-support'); ?>">
	            <a href="/account/?action=<?php echo $this->slug; ?>">Subscription Management</a>
	        </span>
        <?php
        endif;
	}

	public function tab_content($action){
		if($action == $this->slug) {
			new WPPL_View('tab');
		}
	}
}

new MPTC_Tab_Controller();