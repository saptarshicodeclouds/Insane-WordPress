<?php

defined('ABSPATH') or die("Direct access to the script does not allowed");

GFForms::include_addon_framework();

class GFLimitSubmissions extends GFAddOn {
    
    // Addon Settings
    protected $_version = GF_LIMIT_SUBMISSIONS_VERSION;
	protected $_min_gravityforms_version = '2.3-beta-1';
	protected $_slug = 'gf-limit-submission';
	protected $_path = 'gf-limit-submission/gf-limit-submission.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Limit Submissions';
	protected $_short_title = 'GF Limit Submissions';

	private static $_instance = null;

    private $form_id = 0;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFLimitSubmissions
	*/
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFLimitSubmissions();
		}

		return self::$_instance;
	}

    public function init() {
		parent::init();
		add_filter( 'gform_submit_button',  array( $this, 'form_submit_button' ), 10, 2 );
        add_filter( 'gform_pre_render',     array( $this, 'pre_render' ), 10 , 2 );
	}

    public function minimum_requirements() {
		return array(
			'gravityforms' => array(
				'version' => '2.3-beta-1',
			),
			'wordpress' => array(
				'version' => '4.9'
			)
		);
	}

    public function form_settings_fields( $form ) {
		return array(
			array(
				'title'  => esc_html__( 'General Settings', 'gf-limit-submissions' ),
				'fields' => array(
					array(
						'label'             => esc_html__( 'Submission Limit', 'gf-limit-submissions' ),
						'type'              => 'text',
                        'input_type'        => 'number',
						'name'              => 'rule_submission_limit',
						'tooltip'           => esc_html__( 'Specify the number of entries that may be submitted if this limit feed applies.', 'gf-limit-submissions' ),
						'class'             => 'small',
					),
					array(
						'label'   => esc_html__( 'Limit Message', 'gf-limit-submissions' ),
						'type'    => 'textarea',
						'name'    => 'rule_limit_message',
						'tooltip' => esc_html__( 'Specify a message that will be displayed to users if their submission is limited or if the form\'s submission limit is reached.', 'gf-limit-submissions' ),
						'class'   => 'large merge-tag-support mt-prepopulate mt-position-right',
						'default_value' => __( 'The submission limit has been reached for this form.', 'gf-limit-submissions' )
					),
                    array(
						'label'   => esc_html__( 'Disable Type', 'gf-limit-submissions' ),
						'type'    => 'radio',
						'name'    => 'rule_limit_type',
						'tooltip' => esc_html__( 'Select the option which you want to disable limit has been reached for this form. ', 'gf-limit-submissions' ),
                        'default_value' => 'button',
						'choices' => array(
							array(
								'label' => esc_html__( 'Button', 'gf-limit-submissions' ),
								'value'  => 'button',
							),
							array(
								'label' => esc_html__( 'Form', 'gf-limit-submissions' ),
								'value'  => 'form',
							)
						),
					),
				),
			),
		);
	}

    // Pre Render Callback
    public function pre_render( $form ) {
        $settings = $this->get_form_settings( $form );

		// set form id
		$this->form_id      = $form['id'];
	    $search_criteria    = array();

        // Count Total Entries
	    $count_entries = GFAPI::count_entries( $this->form_id, $search_criteria );

		// Type of Limit
        if ( $settings['rule_limit_type'] != 'form') {
			return $form;
		}

        // 
        if ( empty($settings['rule_submission_limit'])) {
			return $form;
		}

        if ( isset($settings['rule_submission_limit']) && $settings['rule_submission_limit'] <= $count_entries) {
			$text   = $settings['rule_limit_message'];
			
            add_filter( 'gform_get_form_filter_' . $this->form_id, array( $this, 'get_limit_message' ), 10, 2 );
		}

		return $form;
	}

    public function get_limit_message( $form_string, $form ) {
        $settings = $this->get_form_settings( $form );

        $message = $settings['rule_limit_message'];

        if ( empty( $message ) ) {
			$message = __( 'The submission limit has been reached for this form.', 'gf-limit-submissions' );
		}

        return $message;
	}

    // Pre Render Callback
    function form_submit_button( $button, $form ) {
		$settings = $this->get_form_settings( $form );

        // set form id
		$this->form_id = $form['id'];
	    $search_criteria = array();

        // Count Total Entries
	    $count_entries = GFAPI::count_entries( $this->form_id, $search_criteria );

        // Type of Limit
        if ( $settings['rule_limit_type'] != 'button') {
			return $button;
		}

        if ( empty($settings['rule_submission_limit'])) {
			return $button;
		}
		
        if ( isset($settings['rule_submission_limit']) && $settings['rule_submission_limit'] <= $count_entries) {
			$text   = $settings['rule_limit_message'];
			$button = "<div>{$text}</div>";
		}

		return $button;
	}

}