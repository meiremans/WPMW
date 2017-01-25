<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ab-it.io
 * @since      1.0.0
 *
 * @package    Wpmw
 * @subpackage Wpmw/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpmw
 * @subpackage Wpmw/public
 * @author     Nick Meiremans <nick@ab-it.io>
 */
class Wpmw_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpmw_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpmw_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpmw-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpmw_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpmw_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpmw-public.js', array('jquery'), $this->version, false);

    }

    /**
     * Registers all shortcodes at once
     *
     */
    public function register_shortcodes()
    {

        add_shortcode('wizard', array($this, 'start_wizard'));

    } // register_shortcodes()

    /**
     * Processes shortcode nowhiring
     *
     * @param   array $atts The attributes from the shortcode
     *
     * @uses    get_option
     * @uses    get_layout
     *
     * @return    mixed    $output        Output of the buffer
     */
    public function start_wizard($atts = array())
    {


        ob_start();

        $defaults['start-wizard-template'] = $this->plugin_name . '-start-wizard';
        $defaults['order'] = 'date';
        $defaults['quantity'] = 100;
        $args = shortcode_atts($defaults, $atts, 'start-wizard');


        include wpmw_get_template($args['start-wizard-template']);


        $output = ob_get_contents();

        ob_end_clean();

        return $output;

    } // list_openings()

    function submit_site_name()
    {
        if (isset($_GET['sitename'])) {

            $stylesheet = 'WPMW-theme';
            $site_name = $_GET['sitename'];
            $banner = $_GET['bannerChoice'];
            $post_content = $_GET['pageText'];

            $new_blog_id = wpmu_create_blog($site_name . '.realmultisite.dev', '/', $site_name, 1);
            switch_to_blog($new_blog_id);
            $this->create_menu();
            $this->add_page($site_name, $post_content, $banner, TRUE);

            switch_theme($stylesheet);
            restore_current_blog();
        }

    }

    function add_page($title, $content, $banner, $isHome = FALSE)
    {
        $new_post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s'),
            'post_author' => get_current_user_id(),
            'post_type' => 'page',
            'post_category' => array(0)
        );
        $post_id = wp_insert_post($new_post);
        add_post_meta($post_id, '_WPMW_banner', $banner, false);
        $menulocation = 'header-menu';
        $locations = get_nav_menu_locations();
        $menu_id = $locations[$menulocation];
        if ($isHome) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __('Home'),
                'menu-item-classes' => 'home',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish'));
        } else {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __($title),
                'menu-item-url' => home_url('/' . $title),
                'menu-item-status' => 'publish'));
        }


    }

    function create_menu()
    {
        // Check if the menu exists
        $menu_name = 'Menu For Site';
        $menulocation = 'header-menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

// If it doesn't exist, let's create it.
        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);

        }
        if (!has_nav_menu($menulocation)) {
            $locations = get_theme_mod('nav_menu_locations');
            $locations[$menulocation] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    function wpmw_ajax_vars()
    { ?>
        <script type="text/javascript">
            var ajaxurl = <?php echo json_encode(admin_url("admin-ajax.php")); ?>;
        </script><?php
    }
}
