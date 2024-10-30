<?php
if (!session_id()) {
    session_start();
}
/**
 * Class to handle all custom post type definitions for Restaurant Reservations
 */
if (!defined('ABSPATH'))
    exit;

class Csh_Testimonials_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		//--------------------Action------------------//
        add_action('wp_enqueue_scripts', array($this, 'cshtm_public_register_style'));
        add_action('wp_enqueue_scripts', array($this, 'cshtm_public_register_script'));

        //------------------Shortcode------------------//
        add_shortcode('cshtm_carousel', array($this, 'do_cshtm_carousel'));

	}

	public function cshtm_public_register_style(){
		// Load style template.
	    $layout1_css = get_stylesheet_directory().'/pl_templates/csh-testimonials/layouts/layout1/layout1.css';
		if (file_exists( $layout1_css )) {
			wp_register_style('cshtm_layout_1', get_stylesheet_directory_uri().'/pl_templates/csh-testimonials/layouts/layout1/layout1.css');
		}else {
			wp_register_style('cshtm_layout_1', CSHTM_PLUGIN_LAYOUTS_URL . 'layout1/layout1.css');
		}

		$layout2_css = get_stylesheet_directory().'/pl_templates/csh-testimonials/layouts/layout2/layout2.css';
		if (file_exists( $layout2_css )) {
			wp_register_style('cshtm_layout_2', get_stylesheet_directory_uri().'/pl_templates/csh-testimonials/layouts/layout2/layout2.css');
		}else {
			wp_register_style('cshtm_layout_2', CSHTM_PLUGIN_LAYOUTS_URL . 'layout2/layout2.css');
		}

        wp_register_style('cshtm-owl-carousel', CSHTM_PLUGIN_ASSETS_URL . 'css/owl.carousel.min.css');
    }

	public function cshtm_public_register_script(){
        wp_enqueue_script('jquery');
        wp_enqueue_media();

        wp_register_script('cshtm-owl-carousel', CSHTM_PLUGIN_ASSETS_URL . 'js/owl.carousel.min.js');
    }


	public function do_cshtm_carousel($atts, $content = null) {
		ob_start();
		global $l1_index, $l2_index, $l1var, $l2var;
		if (!isset($l1_index)) {
			$l1_index = 0;
		}

		if (!isset($l2_index)) {
			$l2_index = 0;
		}

		if (empty($l1var)) {
			$l1var = array();
		}

		if (empty($l2var)) {
			$l2var = array();
		}
		
		extract(shortcode_atts(array(
			'layout'  => "1",
			'category'  => "default",
			'column'    => "3",
			'dots_each' => "1",
			'loop'      => false,
			'background' => "#ffffff",
			'text_color' => "#9b9b9b",
			'name_color' => "#000000",
        ), $atts));

        if ($loop != true) {
        	$loop = false;
        }

		wp_enqueue_style('cshtm-owl-carousel');
		wp_enqueue_script('cshtm-owl-carousel');

		$all_posts = cshtm_get_testimonial_posts($category);

		if ($layout == '1') {
			$l1_index++;
			wp_enqueue_style('cshtm_layout_1');
			$item_id = ".cshtm-owl-carousel-l1"."[id='l1-".$l1_index."']";
			$dynamic_css_l1 = "";
			//dynamic css
			$dynamic_css_l1 .= $item_id."{ background-color: {$background}; }";
			$dynamic_css_l1 .= $item_id." .cshtm-text p,".$item_id." .cshtm-desc p,".$item_id." .cshtm-desc a{ color: {$text_color};}";

			$dynamic_css_l1 .= $item_id." .cshtm-text span{ background: {$text_color};}";
			$dynamic_css_l1 .= $item_id." .owl-carousel .owl-dot span{ border:2px solid {$text_color}; background-color: {$background};}";
			$dynamic_css_l1 .= $item_id." .owl-carousel .owl-dot.active span{ background-color: {$text_color}; }";

			$dynamic_css_l1 .= $item_id." .cshtm-desc h4,".$item_id." .owl-carousel .cshtm-quote{ color: {$name_color}; }";

			wp_add_inline_style('cshtm_layout_1', $dynamic_css_l1);
			//pass js var
			$l1var['number_item'.$l1_index] = $column;
			$l1var['dots_each'.$l1_index] = $dots_each;
			$l1var['carousel_loop'.$l1_index] = $loop;
		    wp_localize_script('cshtm-owl-carousel', 'l1var', $l1var);

		    // Load template.
		    $template_file = get_stylesheet_directory().'/pl_templates/csh-testimonials/layouts/layout1/layout1.php';
			if (file_exists( $template_file )) {
				require $template_file;
			}else {
				require CSHTM_PLUGIN_DIR . 'layouts/layout1/layout1.php';
			}
		}else {
			$l2_index++;
			wp_enqueue_style('cshtm_layout_2');
			$item_id = ".cshtm-owl-carousel-l2"."[id='l2-".$l2_index."']";
			$dynamic_css_l2 = "";
			//dynamic css
			$dynamic_css_l2 .= $item_id." .owl-carousel{ background-color: {$background}; }";
			$dynamic_css_l2 .= $item_id." .cshtm-text p,".$item_id." .cshtm-desc p," .$item_id." .cshtm-desc a{ color: {$text_color};}";

			$dynamic_css_l2 .= $item_id." .cshtm-text span{ background: {$text_color};}";
			$dynamic_css_l2 .= $item_id." .owl-carousel .owl-dot span{ border:2px solid {$text_color}; background-color: {$background};}";
			$dynamic_css_l2 .= $item_id." .owl-carousel .owl-dot.active span{ background-color: {$text_color}; }";

			$dynamic_css_l2 .= $item_id." .cshtm-desc h4,".$item_id." .owl-carousel .cshtm-quote{ color: {$name_color}; }";

			wp_add_inline_style('cshtm_layout_2', $dynamic_css_l2);
			//pass js var
			$l2var['number_item'.$l2_index] = $column;
			$l2var['dots_each'.$l2_index] = $dots_each;
			$l2var['carousel_loop'.$l2_index] = $loop;

		    wp_localize_script('cshtm-owl-carousel', 'l2var', $l2var);

		    // Load template.
		    $template_file = get_stylesheet_directory().'/pl_templates/csh-testimonials/layouts/layout2/layout2.php';
			if (file_exists( $template_file )) {
				require $template_file;
			}else {
				require CSHTM_PLUGIN_DIR . 'layouts/layout2/layout2.php';
			}
		}

		return ob_get_clean();
		
	}
}
