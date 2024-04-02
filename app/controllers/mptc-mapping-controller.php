<?php

class MPTC_Mapping_Controller{
	static function is_mepr_product_in_sync($id){
		$products = self::get_mepr_products();

		if(in_array($id, $products)){
			return true;
		}

		return false;
	}

	static function get_mepr_products(){
		if(get_option('mptc-mappings')) {
			$maps       = json_decode( get_option( 'mptc-mappings' ) );
			$returnable = array();

			foreach ( $maps as $map ) {
				array_push( $returnable, $map->mepr );
			}

			return $returnable;
		}

		return array();
	}

	static function retrieve_tc_paired_product($mepr_id){
		$maps = json_decode(get_option('mptc-mappings'));

		foreach($maps as $map){
			if($map->mepr == $mepr_id){
				return $map->tc;
			}
		}

		return false;
	}

	public function __construct(){
		add_action('wp_ajax_wppl_products_mapping', array($this, 'mapping_callback'));
	}

	public function mapping_callback(){
		$req = json_decode(stripslashes($_REQUEST['data']));

		if(!isset($req->nonce) && !wp_verify_nonce($req->nonce, 'wppl_products_mapping')) {
			die(json_encode( [
				'status'  => 'error',
				'message' => 'You should not be here'
			]));
		}

		MPTC_App_Controller::maybe_add_option('mptc-mappings', json_encode($this->parse_maps($req)));

		die(json_encode([
			'status' => 'success',
			'message' => 'The mapping was saved'
		]));
	}

	private function parse_maps($req){
		$returnable = [];

		foreach($req as $key => $item) {
			if($key == 'action' || $key == 'nonce') {
				continue;
			}

			if(empty($item) || empty($key)){
				continue;
			}

			if(!MPTC_ThriveCart_Controller::product_exists($item)){
				die(json_encode([
					'status' => 'error',
					'message' => 'There is no product in ThriveCart with the id of ' . $item
				]));
			}

			array_push($returnable, [
				'mepr' => $key,
				'tc' => $item
			]);
		}

		return $returnable;
	}
}

new MPTC_Mapping_Controller();