=== RAWR for WordPress ===

Contributors: rawrat, natterstefan, onedrop2000
Tags: marketing, survey, poll, analytics, opinion, statistics, ads, advertising, analysis, content, customer, engagement, filter bubble, free, interaction, leads, mobile, newsroom, polls, polling, rawr, recommendation, remarketing, responsive, retargeting, signups, support, user generated content, ugc, vote, voting
Requires at least: 3.3.0
Tested up to: 4.7.4
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

You got information - we got conversation! Rawr widgets sit right within the story and help your users to express and share their opinion with others.

== Description ==

Get to know what your readers think about a certain topic with RAWR. Our widgets increase time on site up to two minutes and generate leads for your website (eg. Signups). Our widgets are for free for smaller publishers, no subscription required. Check out our [pricing](http://newsroom.rawr.at/publishers/?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=wordpress-plugin-readme) for more details.

**WHAT YOU GET FROM RAWR**

1.  Embed polls with our shortcode or Inline Publishing
2.  Let users vote and share their opinion on your website
3.  Get new visitors on your site, when users share their argument with others
4.  Easily moderate arguments in the Admin-Backend
4.  Get deep insights from the questions you asked your readers

**PRICING**

Rawr is free for smaller websites, no strings attached. Check out our [pricing](http://newsroom.rawr.at/publishers/?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=wordpress-plugin-readme) for more details.

**DO YOU NEED MORE INFORMATION?**

Send us an email to [contact@rawr.at](mailto:contact@rawr.at).

== Frequently Asked Questions ==

= How can I sign up on RAWR? =

If you want to use RAWR you have to have a publisher account (free account available - see [pricing](http://newsroom.rawr.at/publishers/?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=wordpress-plugin-readme). [Signup for free](http://newsroom.rawr.at/try-it/?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=wordpress-plugin-readme) and we will send you your login details within 24 hours.

= How does this plugin work? =

Once you have activated the plugin, the RAWR snippet will place itself automatically at the bottom of each page and post right underneath your content. Optional you can use the shortcode to overwrite the default position. You can choose from currently three different design types of the widget (Default, Default Stats Widget and the Progress Bar Widget). To disable the RAWR widget for the current post or page, just disable it via the checkbox right next to the content editor.

We provided several tutorials, that set you going with the basic features of the RAWR plugin. You can find the tutorials within the rawr plugin sections next to the post or page content editor and the rawr plugin settings page.

= How can I change the design of a Widget? =

You can either change the default design in the Plugin-Settings page or choose one per post. All you have to do is to set the design in the Post-/Page-Metabox in the Post-Editor or overwrite it with the shortcode.

= How can I use the Shortcode? =

Embed a rawr with one of these shortcodes `[rawr id="auto" design="default-widget"]` or `[rawr]` wherever you want the rawr to appear.

Available shortcode parameters:

`
[author]       default: public name of the author
[categories]   default: post categories
[design]       default: default-widget; find other widgets on rawr.at
[id]           default: auto; provide the rawr-id you want to embed
[postid]       default: id of the post.
[tags]         default: tags of the post.
`

= Who can see the Plugin Settings-Page and the Rawr-Box in the Editor-Screen? =

By default the Rawr-Settings Page in the WP-Admin is only accessible for Admins (user has capability: `manage_options`). You can change this by modifying the "Plugin Rights Management" option. The Meta-Box in the Page- and Post-Editor is visible to all users (who can edit pages or posts).

= How can I install the Click Tracking function? =

On the RAWR plugin settings page you can define a callback that will be triggered everytime a user interacts with the RAWR widget. Just integrate the callback in your website and use it accordingly (eg. for Google Analytics tracking).
`
myClickTrackingFunction(event) {
  // RAWR widget has been clicked!
  // .. so let's do something :)
  console.log(event);
}
`

If you need more help from us, [contact us](http://newsroom.rawr.at/contact/?utm_campaign=wordpress-plugin&utm_medium=wordpress-plugin&utm_source=wordpress.org).

== Screenshots ==

1. Engage your Audience: Add rawrs to your content and engage your audience.
2. Embed Rawrs within Seconds: use the Shortcode and choose the Design you want.
3. Customize Rawr easily: With every release we add new features, which you can set up in the plugin settings.

== Installation ==

With WordPress Plugin Installer:

1. Go to Plugins->Add New and search for `RAWR for WordPress` and install the plugin
2. Activate it and start using RAWR

Via FTP:

1. Download it from wordpress.org
2. Upload all files to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

Install plugin using a .zip file:

1. Open the Plugins Menu in WordPress
2. Choose install plugin
3. Upload .zip file
4. Active plugin after upload

== Upgrade Notice ==

Currently there are no known issues when you upgrade the plugin.

== Changelog ==

= 2017/06/02 - 0.1.0 =

 * added Default-Widget Design option: admin can choose default design for rawrs in new posts/pages

= 2017/05/03 - 0.0.7 =

 * Plugin Rights Management added: define who can see the Settings-Page

= 2017/04/19 - 0.0.6 =

 * updated deactivation and activation hook: added info regarding the required signup

= 2017/03/28 - 0.0.5 =

 * RAWR widget design chooser and other post-/page-settings added
 * Onbhoarding tutorials added
 * Plugin settings page with Click Tracking support

= 2017/01/26 - 0.0.4.1 =

 * Release on wordpress.org

= 2017/01/23 - 0.0.4 =

 * Inline Publishing feature activated (for logged in Rawr users with editor rights of this tenant)

= 2017/08/10 - 0.0.1 =

 * Initial Release
 * Shortcode & LocationMetaData implemented
