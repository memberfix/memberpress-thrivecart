<?php

/**
 * Plugin Name:       MemberPress ThriveCart Integration
 * Plugin URI:        https://memberfix.rocks/thrivecart-memberpress-integration/
 * Description:       MemberPress integration with ThriveCart
 * Version:           1.2.1
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            MemberFix
 * Author URI:        https://memberfix.rocks
 */

define('WPPL_SLUG', 'memberpress-thrivecart');
define('WPPL_PATH', WP_PLUGIN_DIR . '/' . WPPL_SLUG);
define('WPPL_APP', WPPL_PATH . '/app');
define('WPPL_CONTROLLER', WPPL_APP . '/controllers');
define('WPPL_MODEL', WPPL_APP . '/models');
define('WPPL_URL', plugins_url('/'. WPPL_SLUG));
define('WPPL_ASSETS', WPPL_URL . '/assets');
define('WPPL_JS', WPPL_ASSETS . '/js');
define('WPPL_CSS', WPPL_ASSETS . '/css');

 class MemberPress_ThriveCart{
     public function __construct(){
         $this->check_php_version();
         $this->check_wp_version();
         require WPPL_PATH . '/app/lib/wppl-loader.php';
     }

     private function check_php_version(){
        if(phpversion() < 7.4){
            wp_die(__('PHP version cannot be lower than 7.4', WPPL_SLUG));
        }
     }

     private function check_wp_version(){
         global $wp_version;

         if($wp_version < 4.5){
            wp_die(__('WordPress version cannot be lower than 4.5', WPPL_SLUG));
         }
     }
 }

 new MemberPress_ThriveCart();