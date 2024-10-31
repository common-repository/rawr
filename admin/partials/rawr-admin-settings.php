<?php

/**
 * Provide a settings area view for the plugin
 *
 * This file is used to markup the admin-facing settings of the plugin.
 *
 * @link       https://rawr.at
 * @since      0.0.1
 *
 * @package    Rawr
 * @subpackage Rawr/admin/partials
 *
 */

function verifySave() {
  return isset($_REQUEST['settings-updated']) && wp_verify_nonce($_POST['_wpnonce']);
}
$rawrOptions = (array) json_decode(get_option("rawr_options"));
if (verifySave()) {
  $rawrOptions["settings_clickTrackingFunction"] = $_POST['rawr-clickTrackingFunction'];
  $rawrOptions["settings_userAccessLevel"] = $_POST['rawr-userAccessLevel'];
  $rawrOptions["settings_defaultWidget"] = $_POST['rawr-defaultWidget'];
  update_option("rawr_options", json_encode($rawrOptions));
}

?>
<div class="bootstrap-wrapper">
  <div class="container-fluid">

  <div class="row">
    <div class="col-xs-12">
      <div class="page-header">
        <h1><img class="rawr-logo" src="<?php echo rawrPluginUrl. 'admin/assets/rawr-logo.png' ?>" /> <?php _e( 'RAWR', rawrTranslationSlug ); ?> <small>[v<?php echo rawrPluginVersion; ?>]</small></h1>
      </div>
    </div>
  </div>

  <?php if (verifySave()) { ?>
    <div id="message" class="alert alert-success" role="alert">
      <p><strong><?php _e('Settings saved.') ?></strong></p>
    </div>
  <?php } ?>

    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading"><?php _e( 'Plugin Settings', rawrTranslationSlug ); ?> <img class="rawr-icons" id="rawr-settings-info" src="<?php echo rawrPluginUrl . "/common/vendor/flat-color-icons/info.svg" ;?>" /></div>
          <div class="panel-body table-responsiv rawr-table-wrapper">
            <form action="" method="post">
              <?php wp_nonce_field(); ?>
              <p>
                <div class="form-group settingspage-option settingspage-tracking-function row">
                  <label class="col-xs-12 col-sm-4" for='rawr-clickTrackingFunction'><?php _e( 'Click Tracking function', rawrTranslationSlug ); ?></label>
                  <input class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0" id='rawr-clickTrackingFunction' type='text' name='rawr-clickTrackingFunction' placeholder='<?php _e( 'clickTracking', rawrTranslationSlug ); ?>' value="<?php echo $rawrOptions['settings_clickTrackingFunction']; ?>"/>
                </div>
              </p>
              <p>
                <div class="form-group settingspage-option settingspage-access-management row">
                  <label class="col-xs-12 col-sm-4" for='rawr-userAccessLevel'/><?php _e('Plugin Rights Management', $this->translation_slug) ?></label>
                  <select class='widget-type-dropdown col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0' name='rawr-userAccessLevel' id="rawr-userAccessLevel">
                    <option value='manage_options' <?php selected($rawrOptions["settings_userAccessLevel"], 'manage_options', true ); ?>><?php _e( 'min. "Manage Option" Right', rawrTranslationSlug ); ?></option>
                    <option value='publish_pages' <?php selected($rawrOptions["settings_userAccessLevel"], 'publish_pages', true ); ?>><?php _e( 'min. "Publish Pages" Right', rawrTranslationSlug ); ?></option>
                    <option value='publish_posts' <?php selected($rawrOptions["settings_userAccessLevel"], 'publish_posts', true ); ?>><?php _e( 'min. "Publish Posts" Right', rawrTranslationSlug ); ?></option>
                  </select>
                </div>
              </p>
              <p>
                <div class="form-group settingspage-option settingspage-access-management row">
                  <label class="col-xs-12 col-sm-4" for='rawr-defaultWidget'/><?php _e('Default Widget Design', $this->translation_slug) ?></label>
                  <select class='widget-type-dropdown col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0' name='rawr-defaultWidget' id="rawr-defaultWidget">
                    <option value='default-widget' <?php selected($rawrOptions["settings_defaultWidget"], 'default-widget', true ); ?>><?php _e( 'Default Widget', rawrTranslationSlug ); ?></option>
                    <option value='default-vote-widget' <?php selected($rawrOptions["settings_defaultWidget"], 'default-vote-widget', true ); ?>><?php _e( 'Default Vote Widget', rawrTranslationSlug ); ?></option>
                    <option value='rawr-block-widget' <?php selected($rawrOptions["settings_defaultWidget"], 'rawr-block-widget', true ); ?>><?php _e( 'Progressbar Widget', rawrTranslationSlug ); ?></option>
                  </select>
                </div>
              </p>
              <p>
                <input type='hidden' name='settings-updated' value='1'/>
                <button type='submit' class='btn btn-primary'><?php _e( 'Save' ); ?></button>
              </p>
            </form>
          </div>
        </div>
      </div>
      <div class="hidden-xs col-sm-4 col-md-3">
        <div class="panel panel-default settingspage-contact">
          <div class="panel-heading"><?php _e( 'Contact', rawrTranslationSlug ); ?></div>
          <div class="panel-body table-responsiv rawr-table-wrapper">
            <p><span class="fl-icon-twitter"/></span> <a href="https://twitter.com/rawr_at"><?php _e( 'Follow us on Twitter', rawrTranslationSlug ); ?></a></p>
            <p><span class="fl-icon-facebook"/></span> <a href="https://www.facebook.com/Rawr-1065727876794236/?ref=br_rs"><?php _e( 'Follow us on Facebook', rawrTranslationSlug ); ?></a></p>
            <p><span class="fl-icon-linkedin"/></span> <a href="https://de.linkedin.com/company/rawr.at"><?php _e( 'Follow us on LinkedIn', rawrTranslationSlug ); ?></a></p>
            <p><a class="settingspage-contact-link" href="http://newsroom.rawr.at/contact/<?php echo $this->currentUtm; ?>" target="_blank"><?php _e('Contact the RAWR Team', $this->translation_slug); ?></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
