<?php
if ( ! defined( 'ABSPATH' ) )
  exit();

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rawr.at
 * @since      0.0.1
 *
 * @package    Rawr
 * @subpackage Rawr/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and includes the hooks to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rawr
 * @subpackage Rawr/admin
 * @author     Stefan Natter <stefan@rawr.at>
 */
class Rawr_Admin {

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
   * The options name to be used in this plugin
   *
   * @since  	0.0.1
   * @access 	private
   * @var     string 		$option_name 	Option name of this plugin
   */
  private $option_name;

  /**
   * The options name for page/post/custom-posttypes used in this plugin
   *
   * @since  	0.0.5
   * @access 	private
   * @var     string 		$post_option_prefix 	Page(Posts, Custom-Posttypes)-Option name of this plugin
   */
   private $post_option_prefix;

  /**
   * The translate slug of this plugin.
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $translation_slug    the translation-slug of this plugin.
   */
  private $translation_slug;

  /**
   * The RAWR Admin URL
   *
   * @since  	0.0.5
   * @access 	private
   * @var  	  string 		$adminUrl
   */
   private $adminUrl;

   /**
    * The current URL and utm paramters
    *
    * @since  	0.0.5
    * @access 	private
    * @var  	  string 		$currentUrl
    */
    private $currentUrl, $currentUtm;

    /**
     * The options of the plugin
     *
     * @since  	0.0.5
     * @access 	private
     * @var  	  string 		$options
     */
     private $options;

  /**
   * Initialize the class and set its properties.
   *
   * @since    0.0.1
   * @param    string    $plugin_name       The name of this plugin.
   * @param    string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->translation_slug = rawrTranslationSlug;
    $this->option_name = rawrOptionName;
    $this->post_option_prefix = '_' . rawrOptionName . '_';
    $this->adminUrl = rawrAdminUrl;
    $this->currentUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $this->currentUtm = sprintf("?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=%s", parse_url($this->currentUrl, PHP_URL_HOST));
    $this->options = (array) json_decode(get_option("rawr_options"));
  }

  /**
   * Do things on init of the Admin
   *
   * @since    0.0.5
   */
  public function admin_init() {
    if (defined( 'DOING_AJAX' ) && DOING_AJAX) {
      /*
       * Load Backend ajax actions
       */
      include_once ( rawrPluginPath . 'admin/ajax-actions.php' );
      new RAWR_ADMIN_AJAX();
    }
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    0.0.1
   */
  public function enqueue_styles($hook) {

    if (in_array($hook, ['toplevel_page_rawr', 'post-new.php', 'post.php'])) {
      wp_enqueue_style( $this->plugin_name . '-edit', plugin_dir_url( __FILE__ ) . 'css/rawr-admin-edit.min.css', array(), $this->version, 'all');
      wp_enqueue_style( 'shepherd-css', rawrPluginUrl. 'common/vendor/tether-shepherd/shepherd-theme-arrows.css', array(), $this->version, 'all');
    }

    if (in_array($hook, ['toplevel_page_rawr', 'rawr_page_rawr-dashboard'])) {
      wp_enqueue_style( 'bootstrap-wp-css', rawrPluginUrl . 'common/css/bootstrap-wp.min.css', array(), $this->version, 'all');
      wp_enqueue_style( 'flat-social-icons', rawrPluginUrl . 'common/vendor/flat-social-icons/flat-icons.css', array(), $this->version, 'all');
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rawr-admin.min.css', array(), $this->version, 'all');
    }

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    0.0.1
   */
  public function enqueue_scripts($hook) {
    wp_enqueue_script( 'rawr-admin', plugins_url( '/js/rawr-admin.min.js', __FILE__ ), array( 'jquery' ), rawrPluginVersion, true );
    wp_localize_script( 'rawr-admin', 'rawrAjaxData', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('rawr_ajax_nonce'),
      )
    );

    if (in_array($hook, ['toplevel_page_rawr', 'post-new.php', 'post.php'])) {
      wp_enqueue_script( 'shepherd-js', rawrPluginUrl . 'common/vendor/tether-shepherd/shepherd.min.js', $this->version, false );
    }

  }

  /**
   * Returns a customized footer on the RAWR Admin page
   *
   * @since  0.0.1
   */
  public function adminfooter_text($hook) {
    $screen = get_current_screen();
    if ( 'toplevel_page_rawr' != $screen->base ) {
      return $hook;
    }
    echo '<a href="https://www.rawr.at?utm_campaign=wordpress-plugin&utm_source=wordpress&utm_medium=wordpress">Powered by RAWR (www.rawr.at)</a>';
  }

  /**
   * Add an options page under the Settings submenu
   *
   * @since  0.0.1
   */
  public function add_menu_page() {
    $this->plugin_screen_hook_suffix = add_menu_page(
      __( 'RAWR', $this->translation_slug ),
      __( 'RAWR', $this->translation_slug ),
      $this->options['settings_userAccessLevel'],
      $this->plugin_name,
      array( $this, 'display_settings_page'),
      plugin_dir_url( __FILE__ ) . 'assets/rawr-logo-menu.png'
    );

  }
  /**
   * Render the options page for plugin
   *
   * @since  0.0.1
   */
  public function display_settings_page() {
    include_once 'partials/rawr-admin-settings.php';
  }


  /** === START META BOX === **/
  /**
   * Add meta box
   *
   * @param post $post The post object
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
   */
  function admin_editor_metaboxes($post) {
    add_meta_box('rawr_config', __('RAWR Config', $this->translation_slug), array($this, 'admin_build_metabox'), 'post', 'side', 'low');
    add_meta_box('rawr_config', __('RAWR Config', $this->translation_slug), array($this, 'admin_build_metabox'), 'page', 'side', 'low');
  }

  /**
   * Build custom field meta box
   *
   * @param post $post The post object
   */
  function admin_build_metabox($post) {
    wp_nonce_field(basename( __FILE__ ), 'rawr_meta_box_nonce');
    $checkbox_name = $this->post_option_prefix . "disableRawr";
    $current_rawrDisabled = get_post_meta($post->ID, $this->post_option_prefix . "disableRawr", true);
    $widgetType_name = $this->post_option_prefix . "widgetType";
    $currentWidgetType = get_post_meta($post->ID, $this->post_option_prefix . "widgetType", true);
    $currentUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    function setDropdownSelection ($currentWidgetType, $dropdownValue) {
      if (!empty($currentWidgetType) && $currentWidgetType == $dropdownValue){
        return "selected";
      } else {
        return "";
      }
    }
    ?>
    <div class='rawr-metabox'>
      <div class='widget-type'>
        <label for='<?php echo $widgetType_name; ?>'/><?php _e('Select widget style:', $this->translation_slug) ?></label>
        <select class='widget-type-dropdown' name='<?php echo $widgetType_name; ?>'>
          <option value='' <?php echo $isSelected = setDropdownSelection($currentWidgetType, '');?>>-- Choose Design --</option>
          <option value='default-widget' <?php echo $isSelected = setDropdownSelection($currentWidgetType, 'default-widget');?>>Default Widget</option>
          <option value='argument-stats-widget' <?php echo $isSelected = setDropdownSelection($currentWidgetType, 'argument-stats-widget');?>>Default Stats Widget</option>
          <option value='rawr-block-widget' <?php echo $isSelected = setDropdownSelection($currentWidgetType, 'rawr-block-widget');?>>Progress Bar Widget</option>
        </select>
      </div>
      <div class='widget-disable'>
        <input type='checkbox' id='rawrDisabled' name='<?php echo $checkbox_name; ?>' value='1' <?php checked($current_rawrDisabled, 1); ?>/>
        <label for='rawrDisabled'><?php _e('Disable RAWR on this Page', $this->translation_slug) ?></label>
      </div>
        <p>
          <a class="rawr-metabox-info" href="#">Tutorial</a> |
          <a class="rawr-metabox-admin-link" href="<?php echo $this->adminUrl . $this->currentUtm; ?>" target="_blank"><?php _e('RAWR Admin', $this->translation_slug); ?></a> |
          <a class="rawr-metabox-contact-link" href="http://newsroom.rawr.at/contact/<?php echo $this->currentUtm; ?>" target="_blank"><?php _e('Contact', $this->translation_slug); ?></a>
        </p>
    </div>
  <?php
  }

  /**
   * Store custom field meta box data
   *
   * @param int $post_id The post ID.
   */
  function admin_save_metaboxes($post_id) {
    if ( !isset( $_POST['rawr_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['rawr_meta_box_nonce'], basename( __FILE__ ) ) ){
      return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
      return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ){
      return;
    }
    if ( isset( $_REQUEST[$this->post_option_prefix ."disableRawr"] ) ) {
      update_post_meta($post_id, $this->post_option_prefix ."disableRawr", sanitize_text_field($_POST[$this->post_option_prefix . "disableRawr"]));
    } else {
      delete_post_meta( $post_id, $this->post_option_prefix ."disableRawr");
    }
    if ( isset( $_REQUEST[$this->post_option_prefix ."widgetType"] ) ) {
      update_post_meta($post_id, $this->post_option_prefix ."widgetType", sanitize_text_field($_POST[$this->post_option_prefix . "widgetType"]));
    } else {
      delete_post_meta( $post_id, $this->post_option_prefix ."widgetType");
    }
  }


  /**
   * add a link to the WP Toolbar
   *
   * @param  string $position $_POST value
   * @since  0.0.5
   * @return string           Sanitized value
   */
  function rawr_toolbar_link($wp_admin_bar) {
    $args = array(
      'id' => 'rawr_toolbar',
      'title' => __( 'RAWR Admin', $this->translation_slug ),
      'href' => $this->adminUrl . $this->currentUtm,
      'meta' => array(
        'class' => 'rawr_toolbar',
        'title' => __( 'Open the RAWR Admin', $this->translation_slug )
        )
    );
    $wp_admin_bar->add_node($args);
  }


  /**
   * Display Plugin Activation Message
   *
   * @since  0.0.5
   * @return string           Sanitized value
   * https://codex.wordpress.org/I18n_for_WordPress_Developers
   * https://codex.wordpress.org/Function_Reference/wp_kses
   */
  function plugin_activation_message(){
    if (isset($this->options['plugin_showOnboarding']) && $this->options['plugin_showOnboarding'] == true) {
      $html = sprintf("
      <div id='rawr-notice' class='notice notice-success is-dismissible'>
        <h1>%s <small>[v%s]</small></h1>
        <h3>%s</h3>
        <p>%s</p>
        <h3>%s</h3>
        <p>%s %s<br/></br>%s<br/><br/>
        </p>
      </div>
      ",
      __('RAWR Plugin', $this->translation_slug),
      rawrPluginVersion,
      __('RAWR Setup', $this->translation_slug),
      sprintf(wp_kses(__('Before you can use all the features of the Plugin <a href=%s target="_blank">sign up and get your free account</a>.', $this->translation_slug), array('a' => array('href' => array(), 'target' => array()))), "http://newsroom.rawr.at/try-it/" . $this->currentUtm),
      __('RAWR Tutorial', $this->translation_slug),
      __('To get an overview of the RAWR Plugin, just follow the tutorial we have provided for you. You can find the tutorials on the relevant places, like in the post or page editor and the RAWR plugin settings page.', $this->translation_slug),
      sprintf(wp_kses(__('For more information and details about the feature of the plugin, checkout our <a href=%s target="_blank">documentation</a>.', $this->translation_slug), array('a' => array('href' => array(), 'target' => array()))), "https://wordpress.org/plugins/rawr/"),
      sprintf(wp_kses(__('If you need personal support just contact the <a href="%s" target="_blank">RAWR TEAM</a>.', $this->translation_slug), array('a' => array('href' => array(), 'target' => array()))), "http://newsroom.rawr.at/contact/" . $this->currentUtm))
      ;

    echo $html;
    }
  }
} // end class
