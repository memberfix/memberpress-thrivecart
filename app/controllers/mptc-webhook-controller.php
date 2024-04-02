<?php

class MPTC_Webhook_Controller{
	private $event;
	private $mapped_products = array();

	public function __construct() {
		add_action('rest_api_init', array($this, 'endpoints'));
	}

	public function endpoints(){
		register_rest_route('memberfix/v1', '/thrivecart', array(
			'methods' => ['POST', 'HEAD'],
			'callback' => array($this, 'callback')
		));
	}

	public function callback(){
		$this->event = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);

		if(!isset($this->event->thrivecart_secret) || $this->event->thrivecart_secret != get_option('mptc-webhook-secret')){
			$this->default();
		}

		if(!isset($this->event->event)){
			$this->default();
		}

		switch ($this->event->event){
			case 'order.success':
				return $this->handle_payments();
			case 'order.subscription_payment':
				return $this->handle_payments();
			default:
				return $this->default();
		}
	}

	private function default(){
		echo "I'm not sure we've met before";
		die();
	}

	private function success(){
		return json_encode(array(
			'status' => 'success',
			'message' => 'Your request has been processed correctly'
		));
	}

	private function error(){
		return json_encode(array(
			'status' => 'error',
			'message' => 'Your request could not be processed correctly'
		));
	}

	private function get_or_create_user($email){
		if(!get_user_by('email', $email)){
			$user = new WP_User(wp_create_user($email, uniqid('pwtc', $email)));
			wp_update_user( array( 'ID' => $user->ID, 'user_email' => $email ) );
			wp_new_user_notification($user->ID, null, 'user');

			return $user;
		}

		return get_user_by('email', $email);
	}

	private function handle_payments(){
		if($this->is_product_mapped($this->event->purchase_map_flat)){
			$customer = $this->event->customer->id;
			$user = $this->get_or_create_user($this->event->customer->email);
			$member = new MeprUser($user->ID);
			$latest_txns = $member->recent_transactions(1);
       		$tc_product_id = $this->event->base_product; 
			$tc_product = 'product-' . $tc_product_id;  

			 //expiration date 
			 $future_charges = $this->event->order->future_charges; // Accessing future charges array
			 // Checking if there are future charges
			 if (!empty($future_charges)) {
				 // Assuming there's only one future charge in this example
				 $due_date = $future_charges[0]->due; // Extracting due date
			 } else {
				 // No future charges found
				 $due_date = null; // Assuming this product will never expire
			 }

			if(!empty($latest_txns)){
				$created = DateTime::createFromFormat('Y-m-d H:i:s', $latest_txns[0]->created_at);
				if($created->format('Y-m-d') == (new DateTime)->format('Y-m-d')) {
					echo $this->error();
					die(); 
				}
			}

			MPTC_App_Controller::maybe_add_user_meta('mptc-order-id', $this->event->order->id, $user->ID);
			$sub_id = isset(get_object_vars($this->event->subscription_ids)[$tc_product]) ? get_object_vars($this->event->subscription_ids)[$tc_product] : ''; //added check here if the key exists in the array before trying to access it
			MPTC_App_Controller::maybe_add_user_meta('mptc-sub-id', $sub_id, $user->ID); 
			MPTC_App_Controller::maybe_add_user_meta('mptc-customer-id', $this->event->customer->id, $user->ID);
			MPTC_App_Controller::maybe_add_user_meta('mptc-subscribed', 'true', $user->ID);


			foreach ($this->mapped_products as $product) {
				if ($product->tc === $tc_product_id) {
				//error_log('handle_payments PRODUCT: ' . print_r($product, true)); 
            $prd = new MeprProduct($product->mepr);
            // error_log('handle_payments prd: ' . print_r($prd, true));
            $txn = $this->add_transaction($prd, $user);
			$txn->expires_at = $due_date;
            $txn->save();
            // error_log('handle_payments txn: ' . print_r($txn, true)); 
            if ($txn) { 
                $success = true; // At least one transaction was successful 
            }
		}
		else {
			//error_log('handle_payments - no Product in Thrive cart txn matching mapped products');
		}
        }

        if ($success) { 
            echo $this->success();
        } else {
            echo $this->error();
        }
		}
	}





private function is_product_mapped($tc_map){
    $maps = json_decode(get_option('mptc-mappings'));
    // error_log('is_product_mapped maps: ' . print_r($maps, true)); 

    foreach($maps as $map){
        if(str_contains(trim($tc_map), 'product-'.$map->tc)){
            array_push($this->mapped_products, $map); 
        }
    }

    if(!empty($this->mapped_products)){
        // error_log('is_product_mapped Product mappings: ' . print_r($this->mapped_products, true)); // Debug statement to log mapped products
        return true;
    }

    return false; 
}
  
 

	private function add_transaction($prd, $user){
		$txn = new MeprTransaction();
		$txn->amount = $prd->price;
		$txn->user_id = $user->ID;
		$txn->product_id = $prd->ID;
		$txn->trans_num = 'tc-' . MeprTransaction::generate_trans_num();
		$txn->status = MeprTransaction::$complete_str;
		$txn->created_at = (new DateTime())->format('Y-m-d H:i:s');

		if($prd->period_type != 'lifetime'){
			$txn->expires_at = (new DateTime())->add(DateInterval::createFromDateString($prd->period . ' ' . $prd->period_type))->format('Y-m-d H:i:s');
		}

		$txn->save();

		return $txn;
	}




}

new MPTC_Webhook_Controller();