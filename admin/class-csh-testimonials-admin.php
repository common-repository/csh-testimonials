<?php

class Csh_Testimonials_Admin {
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
	protected $fields = array(); // attribute of all fields.
	protected $sections = array(); // attribute of all sections.

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_enqueue_scripts', array($this, 'cshtm_admin_register_style'));
        add_action('admin_enqueue_scripts', array($this, 'cshtm_admin_register_script'));

		add_filter( 'admin_init', array( $this, 'create_section_and_fields' ) );

		//Create some menus at admin dashboard.
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ) );

		add_action( 'init', array( $this, 'cshtm_register_posttye' ) );
		add_action( 'init', array( $this, 'cshtm_register_taxonomy' ), 0 );
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	
	public function cshtm_admin_register_style(){
        wp_register_style('cshtm_admin_style', CSHTM_PLUGIN_ASSETS_URL . 'css/csh-testimonials-admin.css');
        wp_enqueue_style('cshtm_admin_style');
    }

	public function cshtm_admin_register_script(){
        wp_enqueue_script('jquery');

        wp_register_script('cshtm_admin_script', CSHTM_PLUGIN_ASSETS_URL . 'js/csh-testimonials-admin.js');
        wp_enqueue_script('cshtm_admin_script'); 
    }

	/*-------------------------------------
	One page -> one Form -> one Submit Button -> one Group setting -> display some sections and it's fields:
		do_settings_sections($sectionId);
		settings_fields( 'groupSetting' ); 
		submit_button();
	-------------------------------------*/

	public function add_section($sectionId, $title) {
		$input = array( 'sectionId'  =>	$sectionId,
				   		'title'     =>	$title);
		array_push($this->sections, $input);
	}

	//Fields of Sections.
	public function add_field_of_section($sectionId, $fieldId, $title, $typeInput, $xData = array()) {
		$input = array('sectionId'	=>	$sectionId,
					   'fieldId'	=>	$fieldId,
					   'title'		=>	$title,
					   'typeInput'	=>	$typeInput,
					   'xData'		=>	$xData);
		array_push($this->fields, $input);
	}


	public function create_section_and_fields() {
		
		foreach ($this->sections as $key => $value) {
			add_settings_section(
			$this->sections[$key]['sectionId'], // ID
			$this->sections[$key]['title'], // Title
			'', // Section can no need callback function.
			$this->sections[$key]['sectionId'] // Let page same sectionId to unique.
			);
		}

		// Render fields loop.
		foreach ($this->fields as $key => $value) {
			$callback = array($this, 'fields_callback');
			add_settings_field(
			$this->fields[$key]['fieldId'], // ID
			$this->fields[$key]['title'], // Title 
			$callback, // Callback
			$this->fields[$key]['sectionId'], // Same Page
			$this->fields[$key]['sectionId'], // Belong to Section id
			array ('fieldId'   => $this->fields[$key]['fieldId'],
				 'typeInput' => $this->fields[$key]['typeInput'],
				)         
			);
		}
	}
	public function cshtm_register_posttye() {
		$labels = array(
			'name'                  => _x( 'Testimonials', 'post type general name', 'cshtesti' ),
			'singular_name'         => _x( 'Testimonial', 'post type singular name', 'cshtesti' ),
			'menu_name'             => _x( 'Testimonials', 'admin menu', 'cshtesti' ),
			'name_admin_bar'        => _x( 'Testimonial', 'add new on admin bar', 'cshtesti' ),
			'add_new'               => _x( 'Add New', 'Testimonial', 'cshtesti' ),
			'add_new_item'          => __( 'Add New Testimonial', 'cshtesti' ),
			'new_item'              => __( 'New Testimonial', 'cshtesti' ),
			'edit_item'             => __( 'Edit Testimonial', 'cshtesti' ),
			'view_item'             => __( 'View Testimonial', 'cshtesti' ),
			'all_items'             => __( 'All Testimonials', 'cshtesti' ),
			'search_items'          => __( 'Search Testimonial', 'cshtesti' ),
			'parent_item_colon'     => __( 'Parent Testimonial:', 'cshtesti' ),
			'not_found'             => __( 'No Testimonial found.', 'cshtesti' ),
			'not_found_in_trash'    => __( 'No Testimonial found in Trash.', 'cshtesti' ),
			'featured_image'        => 'Avatar image',
			'set_featured_image'    => 'Set avatar image',
			'remove_featured_image' => 'Remove avatar',
			'use_featured_image'    => 'Use as avatar'
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'cshtesti' ),
	        'supports'           => array( 
				'title', 
				'editor', 
				'thumbnail',
			),
			'menu_icon' => 'dashicons-testimonial',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'page',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null
		);

		register_post_type( 'csh_testimonial', $args );
	}

	public function cshtm_register_taxonomy() {
	    $labels = array(
			'name' => _x( 'Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search category', 'cshtesti'  ),
			'popular_items' => __( 'Popular category', 'cshtesti'  ),
			'all_items' => __( 'All Categories', 'cshtesti'  ),
			'parent_item' => __( 'Parent Category', 'cshtesti'  ),
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Category', 'cshtesti'  ),
			'update_item' => __( 'Update Category', 'cshtesti'  ),
			'add_new_item' => __( 'Add New Category', 'cshtesti'  ),
			'new_item_name' => __( 'New Category Name', 'cshtesti'  ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'cshtesti'  ),
			'add_or_remove_items' => __( 'Add or remove categories', 'cshtesti'  ),
			'choose_from_most_used' => __( 'Choose from the most used categories', 'cshtesti'  ),
		);
		register_taxonomy('cshtm_cat','csh_testimonial', array(
			'label' => __('category', 'cshtesti' ),
			'labels' => $labels,
			'hierarchical' => true,// False is like tag.
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'cshtm_category' ),
		));
	}

	public function cshtm_custom_column() {
		/* Custom colums */
		add_filter ("manage_edit-csh_testimonial_columns", 'cshtm_edit_csh_testimonial_column' );
		function cshtm_edit_csh_testimonial_column($columns) {
		 
			$columns = array(
				"cb" => "<input type=\"checkbox\" />",
				"title" => __("Title", "cshtesti"),
				"name" => __("Full Name", "cshtesti"),
				"avatar" => __("Avatar", "cshtesti"),
				"date" => __("Date", "cshtesti"),
				);
			return $columns;
		}

		add_action ('manage_posts_custom_column', 'cshtm_columns_show' );
		function cshtm_columns_show($column){
			global $post;
			$custom = get_post_custom();
			switch ($column)
			{
				case "title":
					the_title();
				break;
				case "name":
					echo (isset($custom['_cshtm_name'][0])) ? esc_html($custom['_cshtm_name'][0]) : "" ;
				break;
				case "avatar":
					$avatar_url = get_the_post_thumbnail_url( $post->ID);
					echo '<img width = "40px" height = "40px" src="'.$avatar_url.'">';
				break;
			}
		}
	}

	public function add_meta_box() {
		add_action( 'add_meta_boxes', 'cshtm_add_cshtms_metaboxes' );
		function cshtm_add_cshtms_metaboxes() {
			// Cause Information.
			add_meta_box('meta-cshtm-infomation', 'Name and Desc', 'cshtm_metabox_show_info', 'csh_testimonial', 'normal', 'low');
			function cshtm_metabox_show_info($post) {
				$cshtm_name            = get_post_meta( $post->ID, '_cshtm_name', true );
				$cshtm_desc            = get_post_meta( $post->ID, '_cshtm_desc', true );
				$cshtm_web             = get_post_meta( $post->ID, '_cshtm_web', true );	

				?>
				<div id="cshtm-meta-wrap">

					<label class="cshtm-meta-label" for="cshtm_name">Full Name: </label>
					<input type="text" id="cshtm_name" name="cshtm_name" value="<?php echo esc_attr( $cshtm_name ) ?>" /><br>

					<label class="cshtm-meta-label" for="cshtm_desc">Title-Company: </label>
					<input type="text" id="cshtm_desc" name="cshtm_desc" value="<?php echo esc_attr( $cshtm_desc ) ?>" /><br>

					<label class="cshtm-meta-label" for="cshtm_web">Website: </label>
					<input type="url" id="cshtm_web" name="cshtm_web" value="<?php echo esc_attr( $cshtm_web ) ?>" /><br>
					
				</div>
				
				<?php
			}

		}

		add_action( 'save_post', 'cshtm_metabox_save' );
		function cshtm_metabox_save( $post_id ){
			$post_type = get_post_type($post_id);
			// If this isn't a 'book' post, don't update it.
    		if ( "csh_testimonial" != $post_type ) return;
    		
    		if ($_POST) {
    			if (isset($_POST['cshtm_name'])) {
    				$cshtm_name        = sanitize_text_field( $_POST['cshtm_name'] );
    				update_post_meta( $post_id, '_cshtm_name', $cshtm_name );
    			}

    			if (isset($_POST['cshtm_desc'])) {
    				$cshtm_desc        = sanitize_text_field( $_POST['cshtm_desc'] );
    				update_post_meta( $post_id, '_cshtm_desc', $cshtm_desc );
    			}

    			if (isset($_POST['cshtm_web'])) {
    				$cshtm_web        = sanitize_text_field( $_POST['cshtm_web'] );
    				update_post_meta( $post_id, '_cshtm_web', $cshtm_web );
    			}
    			
    		}
		}

		// //change feature img label.
		// add_action( 'admin_head', 'cshtm_replace_default_featured_image_label', 100 );

		// function cshtm_replace_default_featured_image_label() {
		//     remove_meta_box( 'postimagediv', 'csh_testimonial', 'side' );
		//     add_meta_box('postimagediv', __('Avatar image'), 'post_thumbnail_meta_box', 'csh_testimonial', 'normal', 'low');
		// }
	}

	public function create_settings_menu() {
		//Main setting menu.
		register_setting(
			$this->plugin_name, //group of setting.
			$this->plugin_name //name of setting.
		);

		$menu_callback = array( $this, 'main_settings_menu_show');
		add_submenu_page ('edit.php?post_type=csh_testimonial',
						  'Testimonials-settings',
						  'Manual Shortcode',
						  'manage_options',
						  'csh-testimonials-setting',
						  $menu_callback
				 		 );

	}

	public function main_settings_menu_show() {

		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
		
		?>	
		<div id="cshtm-setting-wrap">
			<form method="post" action="options.php">
					<?php
					foreach ($this->sections as $key => $value) {
						do_settings_sections( $this->sections[$key]['sectionId'] );
						settings_fields( $this->plugin_name );
						submit_button();
					}
					?>
			</form>

		</div>
        <div class="cshlg_setting_premium">
            <h2><span style="color: red;">Get Csh Testimonials Premium</span></h2>
            <ul>
                <li>
                    <strong> Support for <span style="color: blue;">Visual Composer</span> </strong>
                    <br>
                    <span style="color: red; font-style: italic;">Add new element Csh Testimonials inside Visual Composer</span>
                </li>

                 <li>
                    <strong> 24/7 support</strong>
                    <br>
                </li>
               
            </ul>

            <div class="link_premium">
                <a id="cshlg-premium-button" class="button button-primary" href="https://cmssuperheroes.com/wordpress-plugins/csh-testimonials/" target="_blank">Get Csh Testimonials Premium now!</a>
                <br>
                <small style="color: red; font-style: italic;">Price is lower than 15$, Extend support to 12 months</small>
            </div>

        </div>
		<?php 
	}

	public function fields_callback( $args) {
		$arrGlobalData = get_option($this->plugin_name);
		switch ($args['typeInput']) {
			case 'desc_default':
				?>
					<p>[cshtm_carousel]</p>
				<?php
				break;
			case 'desc_layout':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">layout = "1 or 2"</span>], default = 1.</p>
				<?php
				break;
			case 'desc_cat':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">category = "name or slug"</span>]</p>
				<?php
				break;
			case 'desc_column':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">column = "number"</span>] , min = 1, max = 3, default = 3.</p>
				<?php
				break;
			case 'desc_dot':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">dots_each = "number"</span>] , default = 1.</p>
				<?php
				break;
			case 'desc_loop':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">loop = "false"</span>] , default = true.</p>
				<?php
				break;
			case 'desc_background':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">background = "color"</span>] , default = #ffffff.</p>
				<?php
				break;
			case 'desc_text_color':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">text_color = "color"</span>] , default = #9b9b9b.</p>
				<?php
				break;
			case 'desc_name_color':
				?>
					<p>[cshtm_carousel <span style="font-style: italic;">name_color = "color"</span>] , default = #000000.</p>
				<?php
				break;
			default:
		}
	}// end of call back.

	

}// End of AdminSettings.

?>