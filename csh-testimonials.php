<?php

/*
 * Plugin Name:       Csh Testimonials
 * Plugin URI:        http://demo.cmssuperheroes.com/csh-plugins/csh-testimonials
 * Description:       Custom and embed testimonials into the posts.
 * Version:           1.0.0
 * Author:            Tony
 * Author URI:        https://codecanyon.net/user/cmssuperheroes
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Copyright 2017 CmsSuperHeroes 
 * Text Domain:       cshtesti
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CSHTM_PLUGIN_VERSION', '1.0.0' );

define( 'CSHTM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CSHTM_PLUGIN_URL', plugins_url("", __FILE__) );

define( 'CSHTM_PLUGIN_ADMIN_DIR', CSHTM_PLUGIN_DIR . "/admin/" );
define( 'CSHTM_PLUGIN_ADMIN_URL', CSHTM_PLUGIN_URL . "/admin/" );

define( 'CSHTM_PLUGIN_ASSETS_DIR', CSHTM_PLUGIN_DIR . "/assets/" );
define( 'CSHTM_PLUGIN_ASSETS_URL', CSHTM_PLUGIN_URL . "/assets/" );

define( 'CSHTM_PLUGIN_INCLUDES_DIR', CSHTM_PLUGIN_DIR . "/includes/" );
define( 'CSHTM_PLUGIN_INCLUDES_URL', CSHTM_PLUGIN_URL . "/includes/" );

define( 'CSHTM_PLUGIN_LAYOUTS_DIR', CSHTM_PLUGIN_DIR . "/layouts/" );
define( 'CSHTM_PLUGIN_LAYOUTS_URL', CSHTM_PLUGIN_URL . "/layouts/" );

define( 'CSHTM_PLUGIN_PUBLIC_DIR', CSHTM_PLUGIN_DIR . "/public/" );
define( 'CSHTM_PLUGIN_PUBLIC_URL', CSHTM_PLUGIN_URL . "/public/" );

require CSHTM_PLUGIN_INCLUDES_DIR . 'class-csh-testimonials.php';
require CSHTM_PLUGIN_INCLUDES_DIR . 'csh-testimonials-utils.php';











