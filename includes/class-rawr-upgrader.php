<?php
if ( ! defined( 'ABSPATH' ) )
  exit();

/**
 * Handles plugin updates
 *
 * This class defines all code necessary to run during the plugin's upgrade/update.
 * It is based on the great solution from zaus (https://gist.github.com/zaus/c08288c68b7f487193d1)
 *
 * It is mainly used to cover FTP, ZIP or other uploads/updates of the plugin. As the 'register_activation_hook'
 * will not be triggered then. In "normal" update cases it is solved in the class-rawr-activator already (around line 98)
 *
 * @since      0.1.0
 * @package    Rawr
 * @subpackage Rawr/includes
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Upgrader {
  const VERSION_008 = '0.1.0';

  public static function upgrade() {
    $upgrades = array(
      self::VERSION_008
    );
    $options = (array) json_decode(get_option("rawr_options"));
    $currentVersion = rawrPluginVersion;
    $lastVersion = $options['plugin_lastVersion'];
    if (version_compare($lastVersion, $currentVersion) < 0) {
      Rawr_Helper::doRequest("wp_updated");
      foreach($upgrades as $nextVersion) {
        if(version_compare($lastVersion, $nextVersion) < 0) {
          $options = self::do_upgrade($lastVersion, $nextVersion, $options);
        }
        $lastVersion = $nextVersion;
      }
    }
    $options['plugin_currentVersion'] = $currentVersion;
    update_option('rawr_options', json_encode($options));
  }

  private static function do_upgrade($lastVersion, $nextVersion, $currentOptions) {
    ## error_log('upgrade from ' . $lastVersion . ' to ' . $next);
    switch($nextVersion) {
      case self::VERSION_008:
        $newOptions = array(
          'settings_defaultWidget' => 'default-widget'
        );
        return self::addOptionsProperty($currentOptions, $newOptions);
      break;
    }
  }
  private static function addOptionsProperty($currentOptions, $newOptions) {
   foreach ($newOptions as $key => $value) {
     if (!array_key_exists($key, $currentOptions)) {
       $currentOptions[$key] = $value;
     }
   }
   return $currentOptions;
  }
}
