<?php
/**
 * Creates a settingstab in Woocommerce
 *
 * @link       http://cherement.nl
 * @since      1.0.0
 *
 * @package    Address-Checker
 * @subpackage Address-Checker/includes
 */

if (!defined('ABSPATH')) exit;

class AdressCheckerWC_Settings_Tab_code
{




    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init()
    {

        add_action('woocommerce_settings_shipping', __CLASS__ . '::settings_tab');
        add_action('woocommerce_settings_save_shipping', __CLASS__ . '::update_settings');
        add_action( 'woocommerce_sections_shipping', __CLASS__ . '::add_sections'  );
        self::add_invalid_notification();






    }

    /**
     * Add a the notification if apikey is invalid
     */
    public static function add_invalid_notification(){

if(!isset($_POST['address_checker_settings_api_code'])){
    return;
}
        $key= $_POST['address_checker_settings_api_code'];
        AddressCheckerNotification::api_notice($key);
    }

    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['settings_tab_address_checker'] = __('Address-checker', 'address-checker-tab');

        return $settings_tabs;
    }

    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab()
    {

        global $current_section;
        woocommerce_admin_fields(self::get_settings($current_section));


    }

    public static function add_sections(){

        add_filter( 'woocommerce_get_sections_shipping', 'address_checker_add_section' );
        function address_checker_add_section( $sections ) {

            $sections['address_checker'] = __( 'Address Checker', 'address_checker' );
            return $sections;

        }

    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings()
    {

        global $current_section;
        woocommerce_update_options(self::get_settings($current_section));
        add_option('address_checker_settings_api_valid', 'false');

    }

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings($current_section, $settings='')
    {
        if($current_section == 'address_checker') {
            $settings = array(
                'section_title' => array(
                    'name' => __('Google maps API Code', 'woocommerce-settings-code'),
                    'type' => 'title',
                    'desc' => 'To check the address the plugin uses Google API.<br>
                                <a target="_blank" href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Here</a> is an explanation to request a Google API Code

                                <br>This plugin requires access to Google Maps Geocoding.',
                    'id' => 'wc_settings_tab_demo_section_title'
                ),
                'title' => array(
                    'name' => __('Google Api code', 'woocommerce-settings-tab-code'),
                    'type' => 'text',
                    'css' => 'min-width:350px;',
                    'desc' => __('Enter here your Google Api key', 'woocommerce-settings-tab-demo'),
                    'id' => 'address_checker_settings_api_code'
                ),

                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_settings_tab_demo_section_end'
                )
            );
            return apply_filters('woocommerce_get_settings_shipping', $settings, 'address_checker');
        }
        return null;


    }


}