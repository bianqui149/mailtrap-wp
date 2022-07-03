<?php

/**
 *  WP_Plugin_Mailtrap
 *
 * Initialize the plugin.
 */
class WP_Plugin_Mailtrap_Admin_Settings
{

	/**
	 * Instance of the class; False if not instantiated yet.
	 *
	 * @var boolean
	 */
	private static $instance = false;

	/**
	 * Instantiates the Singleton if not already done and return it.
	 *
	 * @return obj  Instance of this class; false on failure
	 */
	public static function get_instance()
	{
		if (!self::$instance) {
			self::$instance = new WP_Plugin_Mailtrap_Admin_Settings;
		}
		return self::$instance;
	}

	/**
	 * Environment (DEV|QA|PRD)
	 *
	 * @var string
	 */
	public $env_name = '';

	/**
	 * Holds the values to be used in the fields callbacks
	 * 
	 * @var strings
	 */
	private $options;

	/**
	 * Construct the class instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

		$this->blog_name = $this->get_blog_name();
		$this->env_name = $this->get_env_name();
		add_action('admin_menu', [$this, 'add_plugin_options_page']);
		add_action('admin_init', [$this, 'add_plugin_page_init']);
		add_action('phpmailer_init', [$this, 'setup_phpmailer_init_with_mailtrap']);
	}

	public function add_plugin_options_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'WP Mailtrap Admin',
			'WP Mailtrap Admin',
			'manage_options',
			'wp-mailtrap-admin',
			array($this, 'create_admin_wps_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_wps_page()
	{
		// Set class property
		$this->options = get_option('wps_option_name');
?>
		<div class="wrap">
			<h1>My Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('wps_option_group');
				do_settings_sections('my-setting-admin');
				submit_button();
				?>
			</form>
		</div>
<?php
	}

	/**
	 * Register and add settings
	 */
	public function add_plugin_page_init()
	{
		register_setting(
			'wps_option_group',
			'wps_option_name',
			[$this, 'sanitize_input_values']
		);

		add_settings_section(
			'setting_section_id',
			'Mailtrap Settings',
			array($this, 'print_section_info'),
			'my-setting-admin'
		);

		add_settings_field(
			'hostname_key_wps',
			'Hostname',
			[$this, 'hostname_wps_callback'],
			'my-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'user_wps',
			'User',
			[$this, 'users_wps_callback'],
			'my-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'password_wps',
			'Password',
			[$this, 'password_wps_callback'],
			'my-setting-admin',
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize_input_values($input)
	{
		$new_input = array();
		if (isset($input['hostname_wps']))
			$new_input['hostname_wps'] = sanitize_text_field($input['hostname_wps']);

		if (isset($input['user_wps']))
			$new_input['user_wps'] = sanitize_text_field($input['user_wps']);

		if (isset($input['password_wps']))
			$new_input['password_wps'] = sanitize_text_field($input['password_wps']);

		return $new_input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function hostname_wps_callback()
	{
		printf(
			'<input type="password" id="hostname_wps" class="regular-text" name="wps_option_name[hostname_wps]" value="%s" />',
			isset($this->options['hostname_wps']) ? esc_attr($this->options['hostname_wps']) : ''
		);
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function user_wps_callback()
	{
		printf(
			'<input type="password" id="user_wps" class="regular-text" name="wps_option_name[user_wps]" value="%s" />',
			isset($this->options['user_wps']) ? esc_attr($this->options['user_wps']) : ''
		);
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function password_wps_callback()
	{
		printf(
			'<input type="password" id="password_wps" class="regular-text" name="wps_option_name[password_wps]" value="%s" />',
			isset($this->options['password_wps']) ? esc_attr($this->options['password_wps']) : ''
		);
	}

	/**
	 * It sets up the PHPMailer object to use the Mailtrap SMTP server
	 * 
	 * @param phpmailer The phpmailer object that WordPress uses to send emails.
	 */
	public function setup_phpmailer_init_with_mailtrap($phpmailer)
	{
		$phpmailer->Host = $this->options['hostname_wps'];
		$phpmailer->Port = 587;
		$phpmailer->Username = $this->options['user_wps'];
		$phpmailer->Password = $this->options['password_wps'];
		$phpmailer->SMTPAuth = true;
		$phpmailer->SMTPSecure = 'tls';
		$phpmailer->IsSMTP();
	}
} // End class.
WP_Plugin_Mailtrap_Admin_Settings::get_instance();
