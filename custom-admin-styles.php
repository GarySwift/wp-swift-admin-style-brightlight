<?php
/**
 * Plugin Name:       WP Swift: Admin Style BrightLight
 * Description:       A plugin that ads custom CSS to the dashboard to match the front end colour scheme of BrightLight.
 * Version:           1.0.0
 * Author:            Gary Swift
 * Author URI:        https://github.com/GarySwift
 * License:           MIT License
 * License URI:       http://www.opensource.org/licenses/mit-license.php
 * Text Domain:       wp-swift-admin-style-brightlight
 */
class Custom_Admin_Style_Plugin {

    private $color = "brightlight";
    private $title = "BrightLight";
    /**
     * Initializes the plugin.
     *
     */
    public function __construct() {
        add_action( 'admin_init' , array( $this, 'add_colors' ));
        add_action('after_setup_theme', array( $this, 'restrict_users_from_changeing_admin_theme' ));
        add_action('user_register', array( $this, 'set_default_admin_color_scheme' ));
    }

    /**
     * Register the new color scheme
     */
    function add_colors() {
        $suffix = is_rtl() ? '-rtl' : '';
        wp_admin_css_color(
            $this->color, __( $this->title, 'admin_schemes' ),
            plugins_url( "assets/stylesheets/colors$suffix.css", __FILE__ ),
            array( '#15163E', '#F7F7F7', '#21ACD3', '#D20056' ),
            array( 'base' => '#15163E', 'focus' => '#fff', 'current' => '#fff' )
        );    
    }

    /*
     * Disable color schemes selector for all users
     * Set this new color scheme for all users
     */
    function restrict_users_from_changeing_admin_theme () {
        $users = get_users();
        foreach ($users as $user) {
            remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
            update_user_meta($user->ID, 'admin_color', $this->color);
        }
        if (!current_user_can('manage_options')) {
            remove_action('admin_color_scheme_picker','admin_color_scheme_picker');
        }
    }

    /*
     * Set a Default Admin Color Scheme for All New Users in WordPress 
     */
    function set_default_admin_color_scheme($user_id) {
        $args = array(
            'ID' => $user_id,
            'admin_color' => $this->color
        );
        wp_update_user( $args );
    }
}
// global $acs_colors;
$acs_colors = new Custom_Admin_Style_Plugin;