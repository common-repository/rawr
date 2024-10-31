<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.1
 * @package    Rawr
 * @subpackage Rawr/includes
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_i18n {


  /**
   * Load the plugin text domain for translation.
   *
   * @since    0.0.1
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'rawr',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }



}
