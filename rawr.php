<?php

/**
 * RAWR for WordPress
 *
 * @link              https://rawr.at
 * @since             0.0.1
 * @package           Rawr
 *
 * @wordpress-plugin
 * Plugin Name:       RAWR for WordPress
 * Plugin URI:        https://rawr.at
 * Description:       RAWR. You got information - we got conversation! Rawr widgets sit right within the story. They grab the reader’s attention at the point of highest emotional activation.
 * Version:           0.1.0
 * Author:            RAWR
 * Author URI:        https://rawr.at
 * Text Domain:       rawr
 * Domain Path:       /languages
 */
if ( ! defined( 'WPINC' ) ) {
  die;
}

if (!class_exists('RawrPluginUpgrader')) {
  /**
   * Handles Activation and Upgrading
   * https://gist.github.com/zaus/c08288c68b7f487193d1
   */
  class RawrPluginUpgrader {

    function __construct() {
      add_action( 'admin_init', array(&$this, 'load_plugin') );
    }

    /**
     * Namespace the given key
     * @param string $key the key to namespace
     * @return the namespaced key
     */
    private function N($key = false) {
      if( ! $key || empty($key) ) { return get_class($this); }
      return sprintf('%s_%s', get_class($this), $key);
    }

    public function register($original_file) {
      register_activation_hook( $original_file, array( &$this, 'rawrActivate' ) );
      register_deactivation_hook( $original_file, array( &$this, 'rawrDeactivate') );
    }

    /**
     * The code that runs during plugin deactivation.
     */
    public function rawrDeactivate() {
      require_once plugin_dir_path( __FILE__ ) . 'includes/class-rawr-deactivator.php';
      Rawr_Deactivator::deactivate();
    }

    /**
     * The code that runs during plugin activation.
     */
    public function rawrActivate() {
      add_option( $this->N(), $this->N() );

      require_once plugin_dir_path( __FILE__ ) . 'includes/class-rawr-activator.php';
      Rawr_Activator::activate();
    }

    public function load_plugin() {
      if ( is_admin() && get_option( $this->N() ) == $this->N() ) {
        delete_option( $this->N() );

        /* do stuff once right after activation */
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-rawr-upgrader.php';
        Rawr_Upgrader::upgrade();
      }
    }
  } // class
} // class_exists


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rawr.php';

/**
 * Begins execution of the plugin, defines constants and loads required resources
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_rawr() {
  if ( ! defined( 'rawrPluginName' ) ) {
    define("rawrPluginName", 'rawr');
  }
  if ( ! defined( 'rawrPluginVersion' ) ) {
    define("rawrPluginVersion", '0.1.0');
  }
  if ( ! defined( 'rawrPluginUrl' ) ) {
    define("rawrPluginUrl", plugin_dir_url( __FILE__ ));
  }
  if ( ! defined( 'rawrPluginPath' ) ) {
    define("rawrPluginPath", plugin_dir_path( __FILE__ ));
  }
  if ( ! defined( 'rawrHomeUrl' ) ) {
    define("rawrHomeUrl", 'https://rawr.at');
  }
  if ( ! defined( 'rawrAdminUrl' ) ) {
    define("rawrAdminUrl", 'https:/admin.rawr.at');
  }
  if ( ! defined( 'rawrTranslationSlug' ) ) {
    define("rawrTranslationSlug", 'rawr');
  }
  if ( ! defined( 'rawrOptionName' ) ) {
    define("rawrOptionName", 'rawr');
  }

  $RawrPluginUpgrader = new RawrPluginUpgrader();
  $RawrPluginUpgrader->register(__FILE__);

  $plugin = new Rawr();
  $plugin->run();

}
run_rawr();
