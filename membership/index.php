<?php

/**
 * Plugin Name:       Membership
 * Plugin URI:        CONF_Plugin_Link
 * Description:       Membership Plugin
 * Version:           1.0.0
 * Author:            saptarshicodeclouds
 * Author URI:        https://github.com/saptarshicodeclouds
 * Text Domain:       membership
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
*/


defined('ABSPATH') or die("Direct access to the script does not allowed");

require('includes/class-membership-bootstrap.php');

$membership = new Membership_Bootstrap();