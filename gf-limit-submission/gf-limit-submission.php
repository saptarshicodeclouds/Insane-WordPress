<?php
/**
 * Plugin Name: GF Limit Submissions
 * Plugin URI: https://example.com/
 * Description: Limit the number of entries that can be submitted.
 * Version: 1.0.0
 * Author: SSDev
 * Author URI: http://example.com
 * License: GPL-3.0+
 * Text Domain: gf-limit-submissions
 * Domain Path: /languages 
*/

defined('ABSPATH') or die("Direct access to the script does not allowed");

define('GF_LIMIT_SUBMISSIONS_VERSION', '1.0.0');

add_action( 'gform_loaded', array( 'GF_Limit_Submissions_Bootstrap', 'load' ), 5 );

class GF_Limit_Submissions_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gf-limit-submissions.php' );

        GFAddOn::register( 'GFLimitSubmissions' );
    }

}

function gf_simple_addon() {
    return GFLimitSubmissions::get_instance();
}