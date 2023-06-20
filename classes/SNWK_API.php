<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
/**
 * FAQ post type
 */
 
new SNWK_API();
class SNWK_API
{

    public function wayke_call($endpoint,$args=array()){
        
        /**
         * GET https://api.wayke.se/search/vehicles
         * hits och offset för pageinering
         */

        $wayke_token = get_option('snillrik_wayke_token', "");
        if(is_array($args)){ 

            //because php is dumb and replaces . in get variables with _
            $array_out = array();
            array_walk($args, function($val, $key) use (&$args, &$array_out){
                $key = str_replace("_",".",$key);
                $array_out[$key] = $val;
            });
            
            $args = array_filter($array_out, function($val) {
                return esc_attr($val);
            });

            $query_params = http_build_query($args);
            $query_str = $query_params=="" ? "" : "?".$query_params;
        }
        else if(is_string($args)){
            $args = esc_url_raw($args);
            $query_str = $args;
        }
        else{
            $query_str = "";
        }

        $urlen = "https://api.wayke.se/search/$endpoint"."/"."$query_str";
        
        $response = wp_remote_post(
            $urlen,
            array(
                'method' => 'GET',
                'headers' => array('x-api-key' => $wayke_token),
            )
        );
        $body_array = isset($response["body"]) ? json_decode($response["body"]) : false;

        return $body_array ? $body_array : $response;
    }
}
