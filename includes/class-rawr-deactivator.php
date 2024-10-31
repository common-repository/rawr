<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.0.1
 * @package    Rawr
 * @subpackage Rawr/includes
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Deactivator {

  /**
   * Do things when the plugin was deactivated
   *
   * @since    0.0.1
   */
  public static function deactivate() {
    $options = (array) json_decode(get_option("rawr_options"));
    $options['plugin_showOnboarding'] = 0;
    $options['plugin_lastVersion'] = rawrPluginVersion;
    update_option('rawr_options', json_encode($options));
    Rawr_Helper::doRequest("wp_deactivate");
  }

}
