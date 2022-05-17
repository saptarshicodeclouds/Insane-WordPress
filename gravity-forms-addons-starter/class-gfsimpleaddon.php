<?php

// CNH - Change Name Here

defined('ABSPATH') or die("Direct access to the script does not allowed");

// include the Add-On Framework
GFForms::include_addon_framework();

// Inherit the Add-On Framework by creating a new class which extends GFAddOn:
class GFSimpleAddOn extends GFAddOn {

    // The version of this add-on
    protected $_version = GF_SIMPLE_ADDON_VERSION; //4. CNH

    // The version of Gravity Forms required for this add-on.
	protected $_min_gravityforms_version = '1.9';

    /**
     * A short, lowercase, URL-safe unique identifier for the add-on. This will be used in option
     * keys, filter, actions, URLs, and text-domain localization. The maximum size allowed for the slug is 33 characters.
    */
	protected $_slug = 'gravity-forms-addons-starter'; // 5. CNH
	
    // Relative path to the plugin from the plugins folder.
    protected $_path = 'gravity-forms-addons-starter/init.php';
	
    // The physical path to the main plugin file. Set this to __FILE__
    protected $_full_path = __FILE__;
	
    // The complete title of the Add-On.
    protected $_title = 'Gravity Forms Simple Add-On'; // 6. CNH
	
    // The short title of the Add-On to be used in limited spaces.
    protected $_short_title = 'Simple Add-On'; // 7. CNH

	private static $_instance = null;

    /**
	 * Get an instance of this class.
	 *
	 * @return GFSimpleAddOn
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFSimpleAddOn();
		}

		return self::$_instance;
	}

	// Handles hooks and loading of language files.
	public function init() {
		parent::init();
		add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 10, 2 );
		add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );
	}

    // # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'my_script_js',
				'src'     => $this->get_base_url() . '/js/my_script.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'strings' => array(
					'first'  => esc_html__( 'First Choice', 'simpleaddon' ),
					'second' => esc_html__( 'Second Choice', 'simpleaddon' ),
					'third'  => esc_html__( 'Third Choice', 'simpleaddon' )
				),
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => 'simpleaddon'
					)
				)
			),

		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'my_styles_css',
				'src'     => $this->get_base_url() . '/css/my_styles.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'field_types' => array( 'poll' ) )
				)
			)
		);

		return array_merge( parent::styles(), $styles );
	}

    // --- FRONTEND FUNCTIONS  ---

	/**
	 * Add the text in the plugin settings to the bottom of the form if enabled for this form.
	 *
	 * @param string $button The string containing the input tag to be filtered.
	 * @param array $form The form currently being displayed.
	 *
	 * @return string
	*/

	function form_submit_button( $button, $form ) {
		$settings = $this->get_form_settings( $form );
		
        if ( isset( $settings['enabled'] ) && true == $settings['enabled'] ) {
			$text   = $this->get_plugin_setting( 'mytextbox' );
			$button = "<div>{$text}</div>" . $button;
		}

		return $button;
	}

    // --- ADMIN FUNCTIONS ---
	/**
	 * Creates a custom page for this add-on.
	 */
	public function plugin_page() {
		echo 'This page appears in the Forms menu';
	}

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	*/
	public function plugin_settings_fields() {
		return array(
			array(
				'title'  => esc_html__( 'Simple Add-On Settings', 'simpleaddon' ),
				'fields' => array(
					array(
						'name'              => 'mytextbox',
						'tooltip'           => esc_html__( 'This is the tooltip', 'simpleaddon' ),
						'label'             => esc_html__( 'This is the label',   'simpleaddon' ),
						'type'              => 'text',
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
					)
				)
			)
		);
	}

    // --- HELPERS ---

	/**
	 * The feedback callback for the 'mytextbox' setting on the plugin settings page and the 'mytext' setting on the form settings page.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	*/
	public function is_valid_setting( $value ) {
		return strlen( $value ) < 10;
	}

}