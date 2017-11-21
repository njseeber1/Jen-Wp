<?php
/*
Plugin Name:NickPlugin
Description: A tool built by Nick Group LLC.
Version: 1.0
Author: Nick Seeber
Author URI: http://www.Nick.com
 */

$dir = NickPlugin_dir();

function NickPlugin_init() {
	include_once (ABSPATH . "/wp-content/plugins/Nick-WPPlugin/shortcodes/shortcode1.php");
	add_post_type_support( 'page', 'excerpt' );
}



function settings() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	include_once (ABSPATH . "/wp-content/plugins/Nick-WPPlugin/views/settings.php");
} 


// function NickPlugin_css(){
// 	include_once  (ABSPATH . "/wp-content/plugins/Nick-WPPlugin/views/Common/header.php");
// }

// function NickPlugin_js() {
// 	include_once (ABSPATH . "wp-content/plugins/Nick-WPPlugin/views/Common/footer.php");
// }

// function NickPlugin_admin_js(){
// 	include_once  (ABSPATH . "wp-content/plugins/Nick-WPPlugin/views/Common/admin-footer.php");
// }

function NickPlugin_activation() {
	
}

function NickPlugin_deactivation() {
	
}

function add_interface_menu() {
	$page_title = "Nick Plugin";
	$menu_title = "Nick Plugin";
	$capability = "10";
	$menu_slug = "NickPlugin";
	$mainPage = "settings";
	


	add_menu_page($page_title, $menu_title, $capability, $menu_slug, $mainPage);

}


function NickPlugin_dir() {
	return dirname(__FILE__);
} 

//Start of Test Short Code

//End of test Short Code. 
	

//Start of calendar Shortcode

















// Add initialization and activation hooks
add_action('init', 'NickPlugin_init');
add_action( 'admin_menu', 'add_interface_menu' );

add_action('wp_head', 'NickPlugin_css');
add_action('wp_footer', 'NickPlugin_js');

add_action('admin_print_scripts', 'NickPlugin_css');
add_action('admin_print_footer_scripts', 'NickPlugin_admin_js');

register_activation_hook((ABSPATH . "/wp-content/plugins/Nick-WPPlugin/NickPlugin.php"), 'NickPluginactivation');
register_deactivation_hook((ABSPATH . "/wp-content/plugins/Nick-WPPlugin/NickPlugin.php"), 'NickPlugindeactivation');


?>
