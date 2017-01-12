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
     * @return [type] [description]
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
        if (isset($_POST['sitename'])) {

            $sitename = $_POST['sitename'];
            echo($sitename);
            $new_blog_id = wpmu_create_blog( $sitename.'.realmultisite.dev', '/', $sitename, 1 );


        }

    }
}
