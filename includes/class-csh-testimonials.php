<?php
class Csh_Testimonials {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * @var      class    $public_instance    instance of public Class.
	 */
	protected $public_instance;

	/**
	 * @var      class    $admin_instance    instance of admin Class.
	 */
	protected $admin_instance;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		/* Set plugin information */
		if ( defined( 'CSHTM_PLUGIN_VERSION' ) ) {
			$this->version = CSHTM_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'csh-testimonials';

		// Text domain.
		$this->load_textdomain();
		// Load assets for plugin.
		$this->load_dependencies();

		$this->do_public();
		$this->do_admin();
		$this->build_setting_fields();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function load_textdomain() {
		add_action( 'plugins_loaded', 'cshtm_load_textdomain' );
		function cshtm_load_textdomain() {
		    $language_folder = dirname( plugin_basename( __DIR__ ) ) . '/languages';
		    load_plugin_textdomain( 'cshtesti', false, $language_folder); 
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Csh_Testimonials_Loader. Orchestrates the hooks of the plugin.
	 * - Csh_Testimonials_i18n. Defines internationalization functionality.
	 * - Csh_Testimonials_Admin. Defines all hooks for the admin area.
	 * - Csh_Testimonials_Public. Defines all hooks for the public side of the site.
	 *
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once CSHTM_PLUGIN_PUBLIC_DIR . 'class-csh-testimonials-public.php';
		$this->public_instance = new Csh_Testimonials_Public( $this->get_plugin_name(), $this->get_version() );

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once CSHTM_PLUGIN_ADMIN_DIR . 'class-csh-testimonials-admin.php';
		$this->admin_instance = new Csh_Testimonials_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Front end handle.
	 */
	public function do_public() {

	}

	/**
	 * Build admin setting page.
	 */
	public function do_admin() {
		$admin_page = $this->admin_instance;
		$admin_page->cshtm_custom_column();
		$admin_page->add_meta_box();
	}

	/**
	 * add fields to page.
	 */
	public function build_setting_fields(){
		$admin_setting = $this->admin_instance;
		//Add Sections.
		$admin_setting->add_section('cshtm-setting', 'Shortcode Settings');

		$admin_setting->add_field_of_section('cshtm-setting','1','Show all testimonials','desc_default', array());

		$admin_setting->add_field_of_section('cshtm-setting','2', 'Select layout', 'desc_layout', array());

		$admin_setting->add_field_of_section('cshtm-setting','3', 'Show by category', 'desc_cat', array());

		$admin_setting->add_field_of_section('cshtm-setting','4', 'Number column', 'desc_column', array());

		$admin_setting->add_field_of_section('cshtm-setting','5', 'Dots each carousel', 'desc_dot', array());

		$admin_setting->add_field_of_section('cshtm-setting','6', 'Use loop', 'desc_loop', array());

		$admin_setting->add_field_of_section('cshtm-setting','7', 'Background', 'desc_background', array());

		$admin_setting->add_field_of_section('cshtm-setting','8', 'Text color', 'desc_text_color', array());

		$admin_setting->add_field_of_section('cshtm-setting','9', 'Name color', 'desc_name_color', array());

		
		// $admin_setting->add_field_of_section('cshtm-setting', 'select_layout', 'Select Layout', 'select', array(
		//     'options' => array(
		//         '0' => 'Layout 1',
		//         '1' => 'Layout 2'
		//     ),
		// ));

		// $admin_setting->add_field_of_section('cshtm-setting', 'background_color', 'Background', 'color', array(
		//     'desc' => 'Select background color of testimonials.',
		//     'default' => '#fff'
		// ));

		// $admin_setting->add_field_of_section('cshtm-setting', 'text_color', 'Text color', 'color', array(
		//     'desc' => 'Testimonial text color.',
		//     'default' => '#9b9b9b'
		// ));

		// $admin_setting->add_field_of_section('cshtm-setting', 'name_color', 'Name color', 'color', array(
		//     'desc' => 'Testimonial name color.',
		//     'default' => '#000'
		// ));
	}

}
$plugin = new Csh_Testimonials; // Control public and admin class.





