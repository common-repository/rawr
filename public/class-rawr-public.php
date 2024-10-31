<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rawr.at
 * @since      0.0.1
 *
 * @package    Rawr
 * @subpackage Rawr/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rawr
 * @subpackage Rawr/public
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Public {

  /**
   * The ID of this plugin.
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * The environment of the Widgets
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $env
   */
  private $env;

  /**
   * The options name for page/post/custom-posttypes used in this plugin
   *
   * @since  	0.0.5
   * @access 	private
   * @var  	string 		$post_option_prefix 	Page(Posts, Custom-Posttypes)-Option name of this plugin
   */
   private $post_option_prefix = '_rawr_';

  /**
   * Initialize the class and set its properties.
   *
   * @since    0.0.1
   * @param    string    $plugin_name       The name of the plugin.
   * @param    string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->env = 'https://cdn-rawr-production.global.ssl.fastly.net/api/v2/embed/rawr/';
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    0.0.1
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Rawr_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Rawr_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    0.0.1
   */
  public function enqueue_scripts() {
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Rawr_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Rawr_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
    */
  }

  public function initHook() {
    add_shortcode('rawr', array($this,'RawrShortcode'));
  }

  /**
   * Returns the content for the created Shortcode
   *
   * @since    0.0.1
   */
  public function RawrShortcode($atts){
    $rawrOptions = (array) json_decode(get_option("rawr_options"));
    $defaultDesign = (isset($rawrOptions["settings_defaultWidget"])) ? $rawrOptions["settings_defaultWidget"] : "default-widget";

    /* get the options of the shortcode */
    $shortCodeOptions = shortcode_atts( array(
      'author' => '',
      'categories' => '',
      'design' => $defaultDesign,
      'id' => 'auto',
      'postid' => '',
      'tags' => ''
     ), $atts );

    /**
      * The following rule applies when it comes to the design we apply
      * settings_defaultWidget < shortCode Design < MetaBox widgetType
    */
    $currentWidgetType =  get_post_meta($GLOBALS['post']->ID, $this->post_option_prefix . "widgetType", true);
    if ($shortCodeOptions['design'] == $defaultDesign && !empty($currentWidgetType)) {
      $shortCodeOptions['design'] = $currentWidgetType;
    }

    /* check if we support the page-type (yet) or if the id is not auto, otherwise return */
    if($shortCodeOptions['id'] === 'auto' && !$this->CheckSupportedPages()) {
      return;
    };

    /* if not build the widget and embed it */
    $rawrConfig = $this->createLocationMetaData($shortCodeOptions);
    return $rawrConfig . $this->createRawrEmbed($shortCodeOptions['id'], $shortCodeOptions['design']);
  }

  /**
   * Add rawr to the bottom of a post by default
   * @since    0.0.1
   */
  public function theContentHook($content) {

    /* check if we support the page-type (yet), otherwise return */
    if(!$this->CheckSupportedPages()) {
      return $content;
    }

    $isRawrDisabled = get_post_meta($GLOBALS['post']->ID, $this->post_option_prefix . "disableRawr", true);
    if($isRawrDisabled) {
      return $content;
    }

    /* check if the shortcode is part of the content */
    if( has_shortcode( $content, 'rawr' ) ) {
      return $content;
    }

    /* check if WidgetType is set, or else use default-widget */
    $currentWidgetType =  get_post_meta($GLOBALS['post']->ID, $this->post_option_prefix . "widgetType", true);
    if (!empty($currentWidgetType)){
      $design = $currentWidgetType;
    } else {
      $rawrOptions = (array) json_decode(get_option("rawr_options"));
      $design = (isset($rawrOptions["settings_defaultWidget"])) ? $rawrOptions["settings_defaultWidget"] : "default-widget";
    }

    $rawrConfig = $this->createLocationMetaData();
    $custom_content = $this->createRawrEmbed($id = 'auto', $design);
    $content = $rawrConfig . $content . $custom_content;
    return $content;
  }

  /**
  * Returns the rawr embed code
  * @since	0.0.1
  */
  private function createRawrEmbed($id = 'auto', $design = 'default-widget') {
    return sprintf('<div id="rawr-embed-%s"></div><script src="%s.js?sq=1&t=0&d=%s&st=0&vt=inl"></script>', $id, $this->env . $id, $design);
  }

  /**
  * Creates the locationMetaData object
  * @since	0.0.1
  */
  private function createLocationMetaData($options = []) {
    if(!$this->CheckSupportedPages()) {
      return;
    }
    $rawrOptions = (array) json_decode( get_option( "rawr_options") );

    $locationMetaData = "";
    try {
      if(!isset($options) || !isset($options['categories']) || $options['categories'] == "") {
        $options['categories'] = "";
        if (is_array(get_the_category())) {
          foreach (get_the_category() as $key) {
           $options['categories'] .= "'" . $key->name . "', ";
          }
          if(isset($options['categories']) && $options['categories'] !== "") {
            $options['categories'] = trim($options['categories'], ", ");
          }
        }
      } else {
        $options['categories'] = "'" . str_replace(',', "','", str_replace(', ', ',', $options['categories'])) . "'";
      }

      if(!isset($options['tags']) || $options['tags'] == "") {
        $options['tags'] = "";
        if (is_array(get_the_tags())) {
           foreach ( get_the_tags() as $key) {
            $options['tags'] .= "'" . $key->name . "',";
           }
           if(isset($options['tags']) && $options['tags'] !== "") {
             $options['tags'] = trim($options['tags'], ", ");
           }
        }
       } else {
         $options['tags'] = "'" . str_replace(',', "','", str_replace(', ', ',', $options['tags'])) . "'";
       }

      if(!isset($options['postid']) || $options['postid'] == "") {
        $options["postid"] = get_the_ID();
      }

      if(!isset($options['author']) || $options['author'] == "") {
        $options["author"] = get_the_author();
      }

      if(!isset($options['ident']) || $options['ident'] == "") {
        $options["ident"] = $options["postid"];
      }
      $eventListener = '';
      if (isset($rawrOptions['settings_clickTrackingFunction']) && $rawrOptions['settings_clickTrackingFunction'] != "") {
        $eventListener = sprintf("
          eventListener: function(event){
            %s(event);
          },", $rawrOptions['settings_clickTrackingFunction']);
      }
      $locationMetaData .= "ident: '" . $options['ident'] . "',";
      $locationMetaData .= ( $options["author"] != "" ) ? "author: ['" . $options['author'] . "'], " : "";
      $locationMetaData .= ( $options["categories"] != "" ) ? "categories: [" . $options['categories'] . "], " : "";
      $locationMetaData .= ( $options["postid"] != "" ) ? "postid: '" . $options['postid'] . "', " : "";
      $locationMetaData .= "url: '" . get_permalink() . "',";
      $locationMetaData .= ( $options["tags"] != "" ) ? "tags: [" . $options["tags"] . "], " : "";
      $locationMetaData = trim($locationMetaData, ", ");

      if ($locationMetaData != "" )  {
        $locationMetaData = sprintf("
        <script type= text/javascript>
          var rawrConfig = {
              %s
              locationMetaData: {
              %s
            }
          };
        </script>
        ", $eventListener, $locationMetaData);
      }
    } catch (Exception $e) {
      return "";
    }
    return $locationMetaData;
  }

  /**
  * Currently checks if we support the page the user is visiting
  *
  * @since 0.0.1
  *
  * Default Support: only pages and posts at the moment
  */
  private function CheckSupportedPages() {
    $display = true;
    if ( is_home() ) {
        $display = false;
        if ( is_front_page() ) {
            $display = false;
        }
    }else if ( is_date() ) {
        $display = false;
    } else if ( is_author() ) {
        $display = false;
    } else if ( is_category() ) {
        $display = false;
    } else if ( is_tag() ) {
        $display = false;
    } else if ( is_tax() ) {
        $display = false;
    } else if ( is_archive() ) {
        $display = false;
    } else if ( is_search() ) {
        $display = false;
    } else if ( is_404() ) {
        $display = false;
    } else if ( is_attachment() ) {
        $display = false;
    } else if ( is_single() ) {
        $display = true;
    } else if ( is_page() ) {
        $display = true;
    }
    return $display;
  }
}
