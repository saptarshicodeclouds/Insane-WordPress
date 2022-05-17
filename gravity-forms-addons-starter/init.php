<?php
/*
    Plugin Name: Gravity Forms Simple Add-On - 1.CNH
    Plugin URI: http://www.example.com
    Description: A simple add-on to demonstrate the use of the Add-On Framework 2.CNH
    Version: 2.1
    Author: SSDev
    Author URI: http://www.example.com
*/

// CNH - Change Name Here

defined('ABSPATH') or die("Direct access to the script does not allowed");

define( 'GF_SIMPLE_ADDON_VERSION', '2.1' ); // 3. CNH

// Using the "gform_loaded" Gravity Forms hook to properly initialize the add-on once Gravity Forms has been loaded.
add_action( 'gform_loaded', array( 'GF_Simple_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_Simple_AddOn_Bootstrap {

    public static function load() {
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
        
        // the add-on is initialized by including the class-gfsimpleaddon.php file when gform_loaded is fired.
        require_once( 'class-gfsimpleaddon.php' );
        
        // Register the Addon
        GFAddOn::register( 'GFSimpleAddOn' );
    }
 
}

// Adding a get_instance function also helps other developers integrate with your add-on.
function gf_simple_addon() {
    return GFSimpleAddOn::get_instance();
}