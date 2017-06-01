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

class AddressChecker_validator
{


    /**
     * AddressChecker_validator constructor.
     */
    public function __construct()
    {


    }


    /**
     * Get the zipcode from of the Address field
     *
     * @param $straat
     * @param $number
     * @param $plaats
     * @param $api
     * @return array|null
     */
    public static function getPostcode($straat, $number, $plaats, $api)
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
        if ($dump['status'] == 'REQUEST_DENIED') {
            return 'REQUEST_DENIED';
        } else {
            if (isset($dump['results'][0]['address_components'][6]['long_name'])) {
                array_push(
                    $stuff,
                    str_replace(' ', '', strtoupper($dump['results'][0]['address_components'][6]['long_name']))
                );
            }
            if (isset($dump['results'][0]['address_components'][3]['long_name'])) {
                array_push(
                    $stuff,
                    $dump['results'][0]['address_components'][3]['long_name']
                );

            }
            if (isset($dump['results'][0]['address_components'][4]['long_name'])) {
                array_push(
                    $stuff,
                    $dump['results'][0]['address_components'][4]['long_name']
                );
            }
        }

        return $stuff;
    }

}
