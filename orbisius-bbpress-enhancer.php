<?php
/*
Plugin Name: Orbisius bbPress Enhancer
Plugin URI: http://club.orbisius.com/products/
Description: This plugin adds missing or not yet implemented functionality to bbPress.
Version: 1.0.0
Author: Svetoslav Marinov (Slavi)
Author URI: http://orbisius.com
*/

/*
Copyright 2013 Svetoslav Marinov (Slavi) <slavi@orbisius.com>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set up plugin
add_action( 'init', 'orbisius_bbpress_enhancer_init' );

/**
 * Setups loading of assets (css, js)
 * @return type
 */
function orbisius_bbpress_enhancer_init() {
    if (is_admin()) {
        add_action('admin_menu', 'orbisius_bbpress_enhancer_setup_admin');

        // when plugins are show add a settings link near my plugin for a quick access to the settings page.
        add_filter('plugin_action_links', 'orbisius_bbpress_enhancer_settings_link', 10, 2);

        /**
         * This adds author support for the forum posts.
         * @see bbpress.org/forums/topic/feature-request-change-post-author/
         */
        //add author change functionality to bbPress topics in wp-admin
        add_action('admin_init', 'orbisius_bbpress_enhancer_enable_topic_features');

        function orbisius_bbpress_enhancer_enable_topic_features() {
            add_post_type_support('topic', 'author');
        }

        //then allow subscribers to be authors...good for transfering to bbPress forum which uses custom post types
        add_filter('wp_dropdown_users', 'orbisius_bbpress_enhancer_enable_topic_features_add_user');
    }

	add_filter('bbp_get_form_topic_subscribed', 'orbisius_bbpress_enhancer_handle_checkbox', 10, 2);
    add_action('user_register', 'orbisius_bbpress_enhancer_auto_login');
	add_action('wp_footer', 'orbisius_bbpress_enhancer_add_plugin_credits', 1000); // be the last in the footer

    // todo add filter and append redirect_to .. if a forum post was accessed and after login or reg will go there.
    /*
     add_filter( 'login_url', 'another_login_url', 10, 2);
function another_login_url( $force_reauth, $redirect ){
    $login_url = 'your_chosen_login_url';

    if ( !empty($redirect) )
        $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );

    if ( $force_reauth )
        $login_url = add_query_arg( 'reauth', '1', $login_url ) ;

    return $login_url ;
}
     */
}

// Add the ? settings link in Plugins page very good
function orbisius_bbpress_enhancer_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $settings_link = '<a href="options-general.php?page='
                . dirname(plugin_basename(__FILE__)) . '/' . basename(__FILE__) . '">' . (__("Settings", "Orbisius bbPress Enhancer")) . '</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

/**
 * Makes the notify me on future replies always checked.
 *
 * @param bool $checked
 * @param int $topic_subscribed
 * @return string
 * @see http://bbpress.org/forums/topic/make-notification-of-new-replies-auto-checked/
 */
function orbisius_bbpress_enhancer_handle_checkbox( $checked, $topic_subscribed  ) {
    if ($topic_subscribed == 0) {
        $topic_subscribed = true;
    }

    return checked( $topic_subscribed, true, false );
}

/**
 * Set up administration
 *
 * @package Orbisius bbPress Enhancer
 * @since 0.1
 */
function orbisius_bbpress_enhancer_setup_admin() {
	add_options_page( 'Orbisius bbPress Enhancer', 'Orbisius bbPress Enhancer', 5, __FILE__, 'orbisius_bbpress_enhancer_options_page' );
}

/**
 * Options page
 *
 * @package Orbisius bbPress Enhancer
 * @since 1.0
 */
function orbisius_bbpress_enhancer_options_page() {
	?>
    <?php add_thickbox(); ?>

	<div class="wrap">
        <h2>Orbisius bbPress Enhancer</h2>

        <h2>What does the plugin do?</h2>
        <p>
            This plugin adds missing or not yet implemented functionality to bbPress.


            <ol>
                <li>This adds author support for the forum posts.
                    <a href="#TB_inline?width=600&height=350&inlineId=orb-bbpress-enhancer-sample-author-box" class="thickbox">View</a>

                    <div id="orb-bbpress-enhancer-sample-author-box" style="display:none;">
                        <img src="<?php echo plugins_url('/screenshot-1.png', __FILE__) ?>" width="600" alt="screenshot-1" />
                    </div>

                    <ul>
                        <li>You can change the author of a bbPress forum post.</li>
                    </ul>
                </li>
                <li>Makes sure that the checkbox <strong>Notify me of follow-up replies via email</strong>
                    below each forum reply is checked
                    <a href="#TB_inline?width=600&height=450&inlineId=orb-bbpress-enhancer-sample-follow-up" class="thickbox">View</a>

                    <div id="orb-bbpress-enhancer-sample-follow-up" style="display:none;">
                        <p><img src="<?php echo plugins_url('/screenshot-2.png', __FILE__) ?>" width="600" alt="screenshot-2" /></p>
                    </div>

                    <ul>
                        <li>When you have the checkbox checked this reduces chance of the user forgetting to click it and therefore won't be notified for future replies.</li>
                    </ul>
                </li>
                <li>Make the user autologin after registering
                    <ul>
                        <li>This makes it super easy for the users after they register to be autologged in.
                            Compatible with S2Member plugin.
                        </li>
                    </ul>
                </li>
            </ol>
        </p>

        <h2>Plugin Options</h2>
        <div class="updated">
            <p>Currently, the plugin does not require any configuration options.</p>
        </div>

        <h2>Join the Mailing List</h2>
        <p>
            Get the latest news and updates about this and future cool <a href="http://profiles.wordpress.org/lordspace/"
                                                                            target="_blank" title="Opens a page with the pugins we developed. [New Window/Tab]">plugins we develop</a>.
        </p>

        <p>
            <!-- // MAILCHIMP SUBSCRIBE CODE \\ -->
            1) <a href="http://eepurl.com/guNzr" target="_blank">Subscribe to our newsletter</a> (opens in a new window)
            <!-- \\ MAILCHIMP SUBSCRIBE CODE // -->
        </p>
        <p>OR</p>
        <p>
            2) Subscribe using our QR code. [Scan it with your mobile device].<br/>
            <img src="<?php echo plugin_dir_url(__FILE__); ?>/i/guNzr.qr.2.png" alt="" />
        </p>

        <h2>Support</h2>
        <p>
            <div class="updated">
				<strong>
				** NOTE: ** Support is handled on our site: <a href="http://club.orbisius.com/support/" target="_blank" title="[new window]">http://club.orbisius.com/support/</a>
				<br/>Please do NOT use the WordPress forums or other places to seek support.
				</strong>
			</div>
        </p>

        <?php
            $app_link = 'http://club.orbisius.com/products/wordpress-plugins/orbisius-bbpress-enhancer/';
            $app_title = 'Orbisius bbPress Enhancer';
            $app_descr = 'The plugin makes sure that the checkbox \'Notify me of follow-up replies via email\' below each forum reply is checked off so the user is notified for new replies.';
        ?>
        <h2>Share</h2>
        <p>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                <a class="addthis_button_facebook" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_twitter" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_google_plusone" g:plusone:count="false" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_linkedin" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_email" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_myspace" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_google" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_digg" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_delicious" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_stumbleupon" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_tumblr" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_favorites" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                <a class="addthis_button_compact"></a>
            </div>
            <!-- The JS code is in the footer -->

            <script type="text/javascript">
            var addthis_config = {"data_track_clickback":true};
            var addthis_share = {
              templates: { twitter: 'Check out {{title}} @ {{lurl}} (from @orbisius)' }
            }
            </script>
            <!-- AddThis Button START part2 -->
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
            <!-- AddThis Button END part2 -->
        </p>

	</div>
	<?php
}

/**
* adds some HTML comments in the page so people would know that this plugin powers their site.
*/
function orbisius_bbpress_enhancer_add_plugin_credits() {
    printf(PHP_EOL . PHP_EOL . '<!-- ' . PHP_EOL . ' Powered by Orbisius bbPress Enhancer Plugin | Author URL: http://orbisius.com ' . PHP_EOL . '-->' . PHP_EOL . PHP_EOL);
}

/**
 * Adds a dropdown in edit forum post.
 *
 * @global type $user
 * @global type $post
 * @param string $output
 * @return string
 */
function orbisius_bbpress_enhancer_enable_topic_features_add_user($output) {
    if (!current_user_can('edit_posts')) {
        return $output;
    }

    global $user, $post;

    //global $post is available here, hence you can check for the post type here
    $users = get_users();
    $output = '<select id="post_author_override" name="post_author_override" class="">';

    //Leave the admin in the list
    $output .= '<option value="1">Admin</option>';

    foreach ($users as $user) {
        $sel = ($post->post_author == $user->ID) ? "selected='selected'" : '';
        $output .= '<option value="' . $user->ID . '"' . $sel . '>' . $user->user_login . '</option>';
    }

    $output .= "</select>";

    return $output;
}

/**
 * Auto logs a user after he/she registers for the site.
 * after logging in the user will be redirected to the
    // 1) dashboard
    // or redirect_to url
 */
function orbisius_bbpress_enhancer_auto_login($user_id = 0) {
    $username = '';
    $user = array();

    if (!empty($_REQUEST['user_login'])) {
        $username = $_REQUEST['user_login'];
    }

    if (!empty($_REQUEST['user_pass'])) {
        $password = $_REQUEST['user_pass'];
    } elseif (!empty($_REQUEST['ws_plugin__s2member_custom_reg_field_user_pass1'])) { // s2member
        $password = $_REQUEST['ws_plugin__s2member_custom_reg_field_user_pass1'];
    }

	$creds = array();
	$creds['user_login'] = $username;
	$creds['user_password'] = $password;

    // after logging in the user will be redirected to the
    // 1) dashboard
    // or redirect_to url
    if (!is_user_logged_in()) {
        $user = wp_signon( $creds, false );
    }
}
