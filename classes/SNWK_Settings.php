<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
/**
 * FAQ post type
 */

new SNWK_Settings();
class SNWK_Settings
{
    /**
     *
     * The settings page for the plugin.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'snillrik_faq_create_menu'));
        add_action('admin_init', array($this, 'register_snillrik_faq_settings'));
    }

    function snillrik_faq_create_menu()
    {
        add_menu_page(
            'Snillrik Wayke',
            'Snillrik Wayke',
            'administrator',
            __FILE__,
            [$this, 'snillrik_faq_page'],
            plugins_url('/images/snillrik_bulb.svg', __FILE__)
        );
        add_action('admin_init', [$this, 'register_snillrik_faq_settings']);
    }

    /**
     * Register the settings
     */
    function register_snillrik_faq_settings()
    {
        register_setting('snillrik-waykegroup', 'snillrik_wayke_token');
        register_setting('snillrik-waykegroup', 'snillrik_wayke_itempage');
    }

    /**
     * The settings page
     */
    function snillrik_faq_page()
    {

        $html_out = "";

        $html_out .= "<div class='wrap snillrik-waykefaq'>";
        $html_out .= "<h1>Snillrik Wayke</h1>";
        $html_out .= "<p>Some settings for the Snillrik Wayke plugin</p>";
        $html_out .= "<form method='post' action='options.php'>";
        echo $html_out;
        
        settings_fields('snillrik-waykegroup');
        do_settings_sections('snillrik-waykegroup');
        $wayke_token = get_option('snillrik_wayke_token', "");
        $itempage = get_option('snillrik_wayke_itempage', "");
        
        $html_out = "";
        $html_out .= "<div class='snillrik-waykemain'>";
        
        $html_out .= "<div class='snillrik-waykerow'>";
        $html_out .= "<div class='snillrik-waykeitem'>";
        $html_out .= "<div class='snillrik-waykeitem-inner'>";
        $html_out .= "<h3>" . esc_attr__("Token for Wayke", "snillrik-waykefaq") . "</h3>";
        $html_out .= "<p>" . esc_attr__("The token for communicating with Wayke", "snillrik-waykefaq") . "</p>";
        $html_out .= "<p>TODO: Hur man får fatt på ett sånt token. </p>";
        $html_out .= "<input type='password' id='snillrik_wayke_token' name='snillrik_wayke_token' value='" . $wayke_token . "' />";
        $html_out .= "</div>";
        $html_out .= "</div>";
        $html_out .= "</div>";

        $html_out .= "<div class='snillrik-waykeitem'>";
        $html_out .= "<div class='snillrik-waykeitem-inner'>";
        $html_out .= "<h3>" . esc_attr__("Select page for single car page", "snillrik-waykefaq") . "</h3>";
        $html_out .= "<p>" . esc_attr__("The page for the singe page for a car, the one that has the shortcode [wayke_item]", "snillrik-waykefaq") . "</p>";
        $html_out .= "<select  id='snillrik_wayke_itempage' name='snillrik_wayke_itempage'>";
        if ($pages = get_pages()) {
            foreach ($pages as $page) {
                $html_out .= "<option value='" . $page->ID . "' " . selected($page->ID, $itempage, false) . ">" . $page->post_title . "</option>";
            }
        }
        $html_out .= "</select>";
        $html_out .= "</div>";
        $html_out .= "</div>";
        $html_out .= "</div>";
        $html_out .= "<h3>" . esc_attr__("Shortcodes", "snillrik-waykefaq") . "</h3>";
        $html_out .= "<p>" . wp_kses_post("Info about shortcodes, <br />
            <strong>[wayke_list]</strong> for the list page<br />
            <strong>[wayke_item rightcol=true]</strong> for the singel car page set rightcol to false for no right column (most likly to use the price shortcode below. :)<br />
            <strong>[wayke_price]</strong> for price, intended for sidebar, widget etc. To be used on page where wayke_item shortcode is used.<br />
        ", "snillrik-waykefaq") . "</p>";
        $html_out .= "</div>";
        echo $html_out;

        submit_button();
        $html_out = "";
        $html_out .= "</form>";
        $html_out .= "</div>";
        echo $html_out;
    }
}
