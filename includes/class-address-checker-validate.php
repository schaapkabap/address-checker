<?php

/**
 * Validate the address in Woocommerce with the Google maps api
 *
 * @link       http://cherement.nl
 * @since      1.0.0
 *
 * @package    Address-Checker
 * @subpackage Address-Checker/includes
 */
if (!defined('ABSPATH')) {
    exit;
}

class AdressChecker_validate
{


    /**
     * AdressChecker_validate constructor.
     */
    public function __construct()
    {

        add_action('woocommerce_before_checkout_process', array($this, 'custom_validation_process'));

    }


    /**
     * Screams in Wordpress the
     */
    public function api_code_maps()
    {
        ?>
        <div class="error notice is-dismissible">

        <p><strong><?php _e('Add your Google API code in Woocommerce!', 'adress-checker'); ?></strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
        </div><?php
    }


    /* *
     *  Validate the adressfields of the billingadress and the shippingaddress
     */
    public function custom_validation_process()
    {

        global $woocommerce;

        if (isset($_POST['billing_address_1']) and $_POST['billing_address_1'] != '') {
            $this->validateAdres(
                $_POST['billing_address_1'],
                $_POST['billing_postcode'],
                $_POST['billing_city'],
                $_POST['billing_country']
            );
        }
        if (isset($_POST['ship_to_different_address'])) {
            $this->validateAdres(
                $_POST['shipping_address_1'],
                $_POST['shipping_postcode'],
                $_POST['shipping_city'],
                $_POST['billing_country']
            );
        }
    }


    /**
     * Scream the mediation in woocommerce
     * @param $scream
     */
    public function scream_wrong($scream)
    {
        global $woocommerce;
        if (function_exists('wc_add_notice')) {
            wc_add_notice($scream, 'error');
        }
        else {
            $woocommerce->add_error(print_r($_POST));
        }
    }


    /**
     * Asked to Google the zipcode of the Address field
     *
     * @param $straat
     * @param $number
     * @param $plaats
     * @param $api
     * @return array|null
     */
    public function getPostcode($straat, $number, $plaats, $api)
    {

        $url = $straat." ".$number." ".$plaats;
        $url = rawurlencode($url);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$url."&key=".$api;

        $ch = curl_init();
// Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
// Execute
        $result = curl_exec($ch);
// Closing
        curl_close($ch);

        $dump = (json_decode($result, true));
        $stuff = array();

        if ($dump['status'] == 'ZERO_RESULTS') {

            return null;
        }
        else {
            array_push(
                $stuff,
                str_replace(' ', '', strtoupper($dump['results'][0]['address_components'][6]['long_name']))
            );

            array_push($stuff, $dump['results'][0]['address_components'][4]['long_name']);
        }

        return $stuff;
    }


    /**
     *
     * Validates the address.
     * This can only be used in the Netherlands
     *
     * @param $adresField
     * @param $postalcodeField
     * @param $cityField
     */
    public function validateAdres($adresField, $postalcodeField, $cityField, $country)
    {


        if ($country != "NL") {
            $this->scream_wrong("This can only be used for The Netherlands");
        }

        $api_code = get_option('AdressChecker_settings_api_code');
        $postcode = $postalcodeField = str_replace(' ', '', strtoupper($postalcodeField));
        $city = $cityField;
        $adress = $adresField;
        $address = "";
        $number = "";
        $matches = array();

        /*
         * Validate the address field
         */
        if (preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $adress, $matches)) {
            $address = $matches['address'];
            $number = $matches['number'];
        } else { // no number found, it is only address
            $address = $adress;
        }


        if ($number == "") {
            $this->scream_wrong("Enter the Addressfield!");

        }
        /*
         * Get the zipcode of the adressfield
         */
        elseif (preg_match('~\A[1-9]\d{3} ?[a-zA-Z]{2}\z~', $postalcodeField)) {
            $api_postcode = $this->getPostcode($address, $number, $city, $api_code);

            if ($api_postcode[0] != $postcode) {
                $this->scream_wrong("Your address information has been entered incorrectly");
            }
        } else {
            $this->scream_wrong("Your address information has been entered incorrectly");

        }
    }
}
