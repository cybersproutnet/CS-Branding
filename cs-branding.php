<?php
/**
Plugin Name: CYBERsprout Branding
Plugin URI: http://cybersprout.net
Description: Brand the WP login page and add some security measures
Version: 1.0.0
Author: Tyler Golberg
Author URI: http://cybersprout.net
License: GPL2
GitHub Plugin URI: https://github.com/cybersproutnet/cs-branding
GitHub Branch: master
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;
	}

//Pulls the CSS file
wp_enqueue_style('cs-branding',plugins_url( 'cs-branding/cs-branding.css' ),array(),'1.0.0');

//Remove WP version
function my_footer_shh() {
    remove_filter( 'update_footer', 'core_update_footer' ); 
}

add_action( 'admin_menu', 'my_footer_shh' );
//function remove_version() {
//return '';}

//add_filter('the_generator', 'remove_version');

//Redirects user to CYBERsprout instead of Wordpress.org when clicking on the login logo
function my_login_logo_url() {
	return 'http://cybersprout.net';	}
	
add_filter( 'login_headerurl', 'my_login_logo_url' );

//Essentailly Alt text for the login logo
function my_login_logo_url_title() {
	return 'Website Login';	}
	
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

//Removes the feedback from WP when users enter incorrect login credentials
function login_error_override()	{
	return 'Incorrect login details.'; 	}
add_filter('login_errors', 'login_error_override');

//Remove WP Shake
function my_login_head() { remove_action('login_head', 'wp_shake_js', 12); }

add_action('login_head', 'my_login_head');

//Set "Remember Me" to automatically checked
function login_checked_remember_me() {
	add_filter( 'login_footer', 'rememberme_checked' );
	}
	function rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>"; }
add_action( 'init', 'login_checked_remember_me' );

//Remove WP logo from the admin bar
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
}

//Change Footer link in the WP Admin area
function remove_footer_admin () {
echo 'Managed by <a href="http://www.cybersprout.net" target="_blank">CYBERsprout</a> | <a href="mailto:info@cybersprout.net" target="_blank">Contact us</a> for support';}

add_filter('admin_footer_text', 'remove_footer_admin');

//Add a Dashboard Widget
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('custom_help_widget', 'Website Support', 'custom_dashboard_help');
wp_add_dashboard_widget('custom_resources_widget', 'Helpful Resources', 'custom_dashboard_resources');
}
function custom_dashboard_help() {
echo '<p>Please <a href="mailto:info@cybersprout.net">contact us</a> for the following:</p>
<ul>
<li><b>Technical Issues</b> - Updates to the platform may cause issues. Please let us know right away if you see something awry.</li>
<li><b>New User</b> - We\'ll setup a new user with the proper permissions and configuration.</li>
<li><b>Major Updates</b> - Some users can hangle minor updates but reconfiguring entire pages or design elements can be tricky.</li>
</ul>';
}
function custom_dashboard_resources() {
echo '<p>Check out the following resources for running your website:</p>
<ul>
<li><a href="http://cybersprout.net/wordpress-tutorial/basic-wordpress-terms/" target="_blank">Glossary: Basic Terms</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/posts-vs-pages/" target="_blank">Posts vs Pages</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/writing-a-blog-post/" target="_blank">Writing a blog post</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/formatting-a-blog-post/" target="_blank">Formatting a blog post</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/adding-media-to-a-post-or-page/" target="_blank">Adding media to a post or page</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/adding-links/ " target="_blank">Adding links</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/managing-comments/" target="_blank">Managing Comments</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/scheduling-and-publishing-blog-posts/" target="_blank">Scheduling and publishing blog posts</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/basics-of-wordpress-seo/ " target="_blank">Basics of Wordpress SEO</a></li>
<li><a href="http://cybersprout.net/wordpress-tutorial/free-online-photo-editing-tools/" target="_blank">Free photo editing tools</a></li>
<li><a href="" target="_blank"></a></li>
</li></ul>';
}

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

//Remove all other WP Dashboard widgets
function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_welcome', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}
add_action( 'admin_init', 'remove_dashboard_meta' );

//Clean Up Post and Page Options
function remove_custom_fields() {
	remove_meta_box( 'postcustom' , 'post' , 'normal' );
	remove_meta_box( 'postcustom' , 'page' , 'normal' );}
add_action( 'admin_menu' , 'remove_custom_fields' );

function remove_excerpt_field() {
	remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
	remove_meta_box( 'postexcerpt' , 'page' , 'normal' ); }
add_action( 'admin_menu' , 'remove_excerpt_field' );

//Custom Gravatar - Be sure to upload client image for this function to work properly
function newgravatar ($avatar_defaults) {
$myavatar = get_bloginfo('template_directory') . '/images/guest.png';
$avatar_defaults[$myavatar] = "WPBeginner";
return $avatar_defaults;
}

add_filter( 'avatar_defaults', 'newgravatar' );
