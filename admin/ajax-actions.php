<?php
/**
 * Handles AJAX requests from the Admin
 *
 * Author: Stefan Natter
 */
if (!defined('ABSPATH'))
  exit();

if (!class_exists('RAWR_ADMIN_AJAX')) {
  final class RAWR_ADMIN_AJAX {

    public function __construct() {
      if (current_user_can('manage_options')) {
        add_action('wp_ajax_rawr_handler', array($this, 'rawr_ajax_handler'));
      }
    }

    /**
     * Ajax handler for RAWR
     *
     * @return json|int
     */
    public function rawr_ajax_handler() {
      if (!isset( $_REQUEST['rawr_ajax_nonce']) || !wp_verify_nonce($_REQUEST['rawr_ajax_nonce'], 'rawr_ajax_nonce')) {
        wp_die();
      }
      if (!current_user_can( 'manage_options' )) {
        wp_die();
      }
      $options = (array) json_decode(get_option("rawr_options"));
      if (isset($_REQUEST['step']) && $_REQUEST['step'] == 'dismiss') {
        $options['plugin_showOnboarding'] = 0;
        update_option('rawr_options', json_encode($options));
        wp_send_json(true);
      }
    }
  }
}
