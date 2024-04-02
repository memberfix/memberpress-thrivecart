<?php

class MPTC_ThriveCart_Controller{
	static function cancelSub($user){
		$tc = self::generate_client();
		$mepr_options = MeprOptions::fetch();
		$account_page = get_permalink(get_post($mepr_options->get_attr('account_page_id')));

		try {
			$tc->cancelSubscription(array(
				'order_id' => get_user_meta($user->ID, 'mptc-order-id', true),
				'subscription_id' => get_user_meta($user->ID, 'mptc-sub-id', true),
			));
		}catch (\ThriveCart\Exception $e){
			WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'error', 'Something went wrong. Please contact customer support');
			die();
		}

		MPTC_App_Controller::maybe_add_user_meta('mptc-sub-id', '', $user->ID);
		MPTC_App_Controller::maybe_add_user_meta('mptc-subscribed', 'false', $user->ID);

		WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'success', 'Your subscription was cancelled');
		die();
	}

	static function pauseSub($user){
		$tc = self::generate_client();
		$mepr_options = MeprOptions::fetch();
		$account_page = get_permalink(get_post($mepr_options->get_attr('account_page_id')));

		try {
			$tc->pauseSubscription(array(
				'order_id' => get_user_meta($user->ID, 'mptc-order-id', true),
				'subscription_id' => get_user_meta($user->ID, 'mptc-sub-id', true),
			));
		}catch (\ThriveCart\Exception $e){
			WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'error', 'Something went wrong. Please contact customer support');
			die();
		}

		MPTC_App_Controller::maybe_add_user_meta('mptc-subscribed', 'paused', $user->ID);

		WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'success', 'Your subscription was paused');
		die();
	}

	static function resumeSub($user){
		$tc = self::generate_client();
		$mepr_options = MeprOptions::fetch();
		$account_page = get_permalink(get_post($mepr_options->get_attr('account_page_id')));

		try {
			$tc->resumeSubscription(array(
				'order_id' => get_user_meta($user->ID, 'mptc-order-id', true),
				'subscription_id' => get_user_meta($user->ID, 'mptc-sub-id', true),
			));
		}catch (\ThriveCart\Exception $e){
			WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'error', 'Something went wrong. Please contact customer support');
			die();
		}

		MPTC_App_Controller::maybe_add_user_meta('mptc-subscribed', 'true', $user->ID);

		WPPL_Helper::redirect($account_page . '?action=tc-sub-management', 'success', 'Your subscription was resumed');
		die();
	}

	static function is_subscribed($id){
		if($meta = get_user_meta($id, 'mptc-subscribed', true)){
			if($meta == 'true' || $meta = 'paused'){
				return true;
			}
		}

		return false;
	}

	static function is_paused($id){
		if($meta = get_user_meta($id, 'mptc-subscribed', true)){
			if($meta == 'paused'){
				return true;
			}
		}

		return false;
	}

	static function product_exists($id){
		$tc = self::generate_client();

		try {
			$product = $tc->getProduct($id);
		}catch (\ThriveCart\Exception $e){
			return false;
		}

		return true;
	}

	static function ping($key = false){
		if(!$key){
			$key = get_option('mptc-api-key');
		}

		$req = wp_remote_get('https://thrivecart.com/api/external/ping', array(
			'headers' => array(
				'Authorization' => 'Bearer '. $key
			)
		));

		$response = json_decode($req['body']);

		if(!isset($response->account_name)){
			return false;
		}

		return true;
	}

	static function generate_client(){
		if(!self::ping()){
			wp_die('Your ThriveCart API key is invalid');
		}

		$client = new \ThriveCart\Api(get_option('mptc-api-key'));
		$client->setMode(get_option('mptc-mode'));
		return $client;
	}

	static function tc_api_valid(){
		if(!get_option('mptc-api-key') || !get_option('mptc-webhook-secret') || !get_option('mptc-mode')){
			return false;
		}

		if(!self::ping()){
			return false;
		}

		return true;
	}
}