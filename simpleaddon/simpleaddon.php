<?php
/*
    Plugin Name: Gravity Forms Simple Add-On 1.CNH
    Plugin URI: http://www.gravityforms.com
    Description: A simple add-on to demonstrate the use of the Add-On Framework 2.CNH
    Version: 2.1
    Author: Rocketgenius
    Author URI: http://www.rocketgenius.com
*/

// CNH - Change Name Here

defined('ABSPATH') or die("Direct access to the script does not allowed");

define( 'GF_SIMPLE_ADDON_VERSION', '2.1' ); // 3. CNH

add_action( 'gform_loaded', array( 'GF_Simple_AddOn_Bootstrap', 'load' ), 5 );

class GF_Simple_AddOn_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gfsimpleaddon.php' );

        GFAddOn::register( 'GFSimpleAddOn' );
    }

}

function gf_simple_addon() {
    return GFSimpleAddOn::get_instance();
}