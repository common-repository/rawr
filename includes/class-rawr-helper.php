<?php
if ( ! defined( 'ABSPATH' ) )
  exit();

/**
 * Provides static functions which are commonly used in some parts of the Plugin
 *
 * @since      0.0.3
 * @package    Rawr
 * @subpackage Rawr/includes
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Helper {

  /**
   * Checks if file does exist or not
   *
   * @param $filePath Path of the file we need to check
   * @return false if $url does not exist
   * @since    0.0.3
   */
   public static function doesFileExist($filePath) {
     return is_file($filePath) && file_exists($filePath);
   }

   /**
    * Checks the given url if it exists or returns a 404
    *
    * @param $url we need to check
    * @return true if $url does not return a 404
    * @since    0.0.3
    */
   public static function checkUrl($url) {
     if (!$url) { return FALSE; }
     $curl_resource = curl_init($url);
     curl_setopt($curl_resource, CURLOPT_RETURNTRANSFER, true);
     curl_exec($curl_resource);
     if(curl_getinfo($curl_resource, CURLINFO_HTTP_CODE) == 404) {
         curl_close($curl_resource);
         return FALSE;
     } else {
         curl_close($curl_resource);
         return TRUE;
     }
     return FALSE;
   }


   /**
   * Sends a request to a given URL and activates/deactives the plugin
   *
   * @return -
   * @since 0.0.6
   */
   public static function doRequest($type) {
     $url = 'http://newsroom.rawr.at/wordpresshook';
     $post_data = array(
       'wp_blog' => urlencode(get_bloginfo('url')),
       'wp_pluginversion' => urlencode(rawrPluginVersion),
       'type' => urlencode($type));
     $result = wp_remote_post( $url, array( 'body' => $post_data ) );
     return $result;
   }
}
