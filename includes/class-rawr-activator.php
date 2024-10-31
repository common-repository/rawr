<?php
if ( ! defined( 'ABSPATH' ) )
  exit();

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.1
 * @package    Rawr
 * @subpackage Rawr/includes
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Activator {

  /**
   * The array of default options
   *
   * @since    0.0.7
   * @access   protected
   * @var      array    $defaultOptions    The default options of the plugin
   */
  protected static $defaultOptions;

  /**
   * Initialize the class
   *
   * @since    0.0.7
   */
   private function __construct() {
   }

  /**
   * Do things when the plugin was activated
   *
   * @since    0.0.1
   */
  public static function activate() {
    if (self::$defaultOptions == null) {
       self::$defaultOptions = new Rawr_Activator();
    }
    self::$defaultOptions = array();
    self::$defaultOptions = [
      'plugin_showOnboarding' => 1,
      'plugin_currentVersion' => rawrPluginVersion,
      'plugin_lastVersion' => rawrPluginVersion,

      'settings_clickTrackingFunction' => '',
      'settings_userAccessLevel' => 'manage_options',
      'settings_defaultWidget' => 'default-widget'
    ];
    $options = (array) json_decode(get_option("rawr_options"));

    if (!$options) {
      $options = array();
      $options = self::$defaultOptions;
    } else {
      $options = self::updateRawrOptions($options);
    }
    update_option('rawr_options', json_encode($options));
    Rawr_Helper::doRequest("wp_activate");
  }

  /**
   * Updates specifc settings or sets default values if they do not exist yet.
   * @author: natterstefan
   * @date   2017-04-27
   * @since  0.0.7
   * @todo:  may merge it with the similiar function in class-rawr-upgrader
   * @param  array     $options current options of the plugin
   * @return array $options contains the updated array of options
   */
  private static function updateRawrOptions($options) {
    if (!is_array($options)) {
      return $options;
    }
    foreach (self::$defaultOptions as $key => $value) {
      if (!array_key_exists($key, $options)) {
        $options[$key] = $value;
      }
    }

    return $options;
  }
}
