<?php
/**
 * Creates a settingstab in Woocommerce
 *
 * @link       http://example.com
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
        add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);
        add_action('woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::settings_tab');
        add_action('woocommerce_update_options_settings_tab_demo', __CLASS__ . '::update_settings');

    }

    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['settings_tab_demo'] = __('Adress-checker', 'address-checker-tab');
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
        woocommerce_admin_fields(self::get_settings());
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings()
    {
        woocommerce_update_options(self::get_settings());
    }

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings()
    {
        $settings = array(
            'section_title' => array(
                'name' => __('Google API Code', 'woocommerce-settings-code'),
                'type' => 'title',
                'desc' => 'Om de adresgegevens te controlleren maken wij gebruik van een Google API.<br>
                                <a target="_blank" href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Hier</a> vind u uitleg om een Google API Code aan te vragen
                                <br>Deze plugin heeft toegang nodig tot Google Maps Geocoding.',
                'id' => 'wc_settings_tab_demo_section_title'
            ),
            'title' => array(
                'name' => __('Google api code', 'woocommerce-settings-tab-code'),
                'type' => 'text',
                'css' => 'min-width:350px;',
                'desc' => __('Voer hier u Google API code in', 'woocommerce-settings-tab-demo'),
                'id' => 'wcp_settings_api_code'
            ),

            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_demo_section_end'
            )
        );
        return apply_filters('wc_settings_tab_code_settings', $settings);
    }
}