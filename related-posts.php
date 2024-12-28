<?php
/**
 * Plugin Name: Related Posts
 * Plugin URI: https://yourwebsite.com/related-posts
 * Description: This plugin displays related posts based on the category of the current post. It fetches posts from the   same category, shuffles them, and displays a maximum of 5 related posts with thumbnails.
 * Version: 1.0.0
 * Author: Pulak Wordpress iT
 * Author URI: https://yourwebsite.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: related-posts
 */

if ( !defined('ABSPATH') ) {
    return;
}

class Related_Posts{

private static $instance;

private function construct() {
    add_filter('the_content', array($this, 'display_related_posts'));

    $this->define_constants();
    $this->load_classes();
}

public static function get_instance() {
    if (null === self::$instance) {
        self::$instance = new self();
    }

return self::$instance;
}

private function define_constants() {
    define( 'RP_PLUGIN_PATH',plugin_dir_path( __FILE) );
}

private function load_classes() {
    require_once RP_PLUGIN_PATH . 'includes/class-related-posts.php';
   }
}

Related_Posts::get_instance();