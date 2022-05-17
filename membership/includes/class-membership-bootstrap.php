<?php

defined('ABSPATH') or die("Direct access to the script does not allowed");

class Membership_Bootstrap{
    private $plugin_name;
    private $version;

    public function __construct(){
        $this->version      = '1.0.0';
		$this->plugin_name  = 'membership';


        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_files') );
        add_action( 'admin_menu', array( $this, 'mb_add_menu_page' ), 99 ); 
    }

    // Retrieve the version number of the plugin.
	public function get_version() {
		return $this->version;
	}

    // Get the name of the plugin
	public function get_plugin_name() {
		return $this->plugin_name;
	}

    // Files
    public function enqueue_files() {
        //  Style
        wp_enqueue_style( 'style-css',  plugin_dir_url(__FILE__) . 'css/style.css' );
    }
 
    // Add page to admin menu
    public function mb_add_menu_page() {
        add_menu_page(
            esc_html__( 'Membership', 'membership' ),
            esc_html__( 'Membership', 'membership'),
            'manage_options',
            'membership-plugin',
            null,
            'dashicons-admin-users',
            55.5
        );

        add_submenu_page(
            'membership-plugin',
            esc_html__( 'Membership', 'membership' ),
            esc_html__( 'Membership', 'membership' ),
            'manage_options',
            'membership-plugin',
            array( $this, 'wpdocs_add_menu_page_callback' )
        );

        add_submenu_page(
            'membership-plugin',
            esc_html__( 'Role', 'membership' ),
            esc_html__( 'Role', 'membership' ),
            'manage_options',
            'membership-plugin-role',
            array( $this, 'wpdocs_add_menu_page_callback' )
        );
    }

    // Add page to admin menu callback
    public function wpdocs_add_menu_page_callback() {
        
    }
}