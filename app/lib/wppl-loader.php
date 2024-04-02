<?php

if(!class_exists('WPPL_Loader')) {
	class WPPL_Loader {
		public function __construct() {
			$this->help();
			$this->lib();
			$this->models();
			$this->controllers();
		}

		private function lib() {
			require 'wppl-view.php';
			require 'wppl-form.php';
		}

		private function help() {
			require WPPL_PATH . '/app/helpers/wppl-helper.php';
		}

		private function models() {
			// Add your models
		}

		private function controllers() {
			require WPPL_PATH . '/vendor/autoload.php';
			require WPPL_CONTROLLER . '/mptc-app-controller.php';
			require WPPL_CONTROLLER . '/mptc-tab-controller.php';
			require WPPL_CONTROLLER . '/actions/mptc-cancel-controller.php';
			require WPPL_CONTROLLER . '/actions/mptc-pause-controller.php';
			require WPPL_CONTROLLER . '/actions/mptc-resume-controller.php';
			require WPPL_CONTROLLER . '/mptc-webhook-controller.php';
			require WPPL_CONTROLLER . '/mptc-thrivecart-controller.php';
			require WPPL_CONTROLLER . '/mptc-mapping-controller.php';
		}
	}

	new WPPL_Loader();
}