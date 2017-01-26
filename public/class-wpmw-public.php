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

    function submit_site_name($site_name)
    {
        if (isset($site_name)) {
            $new_blog_id = wpmu_create_blog($site_name . '.realmultisite.dev', '/', $site_name, 1);
            if (is_wp_error($new_blog_id)) {
                return 0;
            } else {
                switch_to_blog($new_blog_id);
                return $new_blog_id;
            }
        }

    }

    function submit_menu()
    {
        $this->create_menu();
    }

    function submit_home_page($site_name, $post_content, $banner)
    {
        $this->add_page($site_name, $post_content, $banner, 'home');
    }

    function submit_page($page_title, $post_content, $banner)
    {
        $this->add_page($page_title, $post_content, $banner, 'page');
    }

    function submit_contact_page($contact_title, $contact_email, $contact_text, $banner)
    {
        $this->add_page($contact_title, $contact_text, $banner, 'contact', $contact_email);
    }


    function add_page($title, $content, $banner, $type, $extra = NULL)
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
        add_post_meta($post_id, '_WPMW_TYPE', $type, false);
        $menulocation = 'header-menu';
        $locations = get_nav_menu_locations();
        $menu_id = $locations[$menulocation];
        if ('home' == $type) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __('Home'),
                'menu-item-classes' => 'home',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish'));
        }
        if ('page' == $type) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __($title),
                'menu-item-url' => home_url('/') . $title,
                'menu-item-status' => 'publish'));
        }
        if ('contact' == $type) {
            add_post_meta($post_id, '_WPMW_CONTACTEMAIL', $extra, false);
            update_post_meta($post_id, '_wp_page_template', 'WPMW-contact-template.php');
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __($title),
                'menu-item-url' => home_url('/') . $title,
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

    function myajax_load_jquery_form()
    {
        wp_enqueue_script('jquery-form');
    }

    function wpmw_ajax_vars()
    { ?>
        <script type="text/javascript">
            var ajaxurl = <?php echo json_encode(admin_url("admin-ajax.php")); ?>;
        </script><?php
    }

    /*
 * Handeling ajax request
 */

    function site_submission()
    {
        $stylesheet = 'WPMW-theme';
        // A default response holder, which will have data for sending back to our js file
        $response = array(
            'error' => false,
        );
        $banner_choice = $_POST['bannerChoice'];
        $page_text = $_POST['pageText'];
        $site_type = $_POST['siteType'];
        $site_name = $_POST['sitename'];
        $page_name1 = $_POST['pageName1'];
        $page_text1 = $_POST['pageText1'];
        $contact_email = $_POST['contactEmail'];
        $contact_text = $_POST['contactText'];


        // Check if each field is filled in
        if (trim('' == $banner_choice || '' == $page_text || '' == $site_type || '' == $site_name)) {
            $response['error'] = true;
            $response['error_message'] = 'Something went wrong';
            // Exit here, for not processing further because of the error
            exit(json_encode($response));
        }
        $this->submit_site_name($site_name);
        $this->submit_menu();
        $this->submit_home_page($site_name, $page_text, $banner_choice);
        $this->submit_page($page_name1, $page_text1, $banner_choice);
        $this->submit_contact_page('contact', $contact_email, $contact_text, $banner_choice);


        switch_theme($stylesheet);
        restore_current_blog();


        // Don't forget to exit at the end of processing
        exit(json_encode($response));
    }

    function email_submission()
    {
        //response messages
        $not_human       = "Human verification incorrect.";
        $missing_content = "Please supply all information.";
        $email_invalid   = "Email Address Invalid.";
        $message_unsent  = "Message was not sent. Try Again.";
        $message_sent    = "Thanks! Your message has been sent.";

        //user posted variables
        $email = $_POST['email'];
        $message = $_POST['comment'];
        $human = $_POST['message_human'];
        $captcha= $_POST['g-recaptcha-response'];

        if($this->checkCaptcha($captcha)) {
            $response["captcha"] = TRUE;
            $headers = 'From: ' . 'nickmeiremans@hotmail.com' . "\r\n" .
                'Reply-To: ' . 'nickmeiremans@hotmail.com' . "\r\n";
            $sent = wp_mail('nickmeiremans@hotmail.com', 'test', strip_tags('blabla'), $headers);
            if ($sent) {
                $response["success"] = $message_sent; //message sent!
            } else {
                $response["error"] = $message_unsent; //message wasn't sent
            }
        }else{
            $response["captcha"] = FALSE;
        }
        exit(json_encode($response));
    }

    function checkCaptcha($captcha){
        if(!$captcha){
           return FALSE;
        }
        // calling google recaptcha api.
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeTUxMUAAAAANH1SAHcfBnuoEM3ilFJMrgmYwyZ&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        // validating result.
        if($response.success==false) {
            return FALSE;
        } else {
           return TRUE;
        }
    }

}
