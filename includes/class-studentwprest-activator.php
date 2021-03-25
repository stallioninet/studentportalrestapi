<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WSPRA_Activator {
	public static function activate() {
 		global $wpdb;
		$wpr__settings = array();
   		$ApiSecret = wcra_api_key_gen();
  		$wpr_set_req_check = 0;
  		$wpr_set_auth_check = 1;
  		$wpr_set_ver = 1;
  		$wpr_set_enb_api = 1;
  		$wpr_set_end_slug = 'wspra';
  		$wpr_set_recent_activity_dur = 3;
  		$_get_settings = wspra_get_settings('wpsr_set_end_slug');
	    $wpr__settings['root_secret'] = $ApiSecret;
	    $wpr__settings['wpsr_set_req_check'] = $wpr_set_req_check;
	    $wpr__settings['wpsr_set_ver'] = $wpr_set_ver;
	    $wpr__settings['wpsr_set_end_slug'] = $wpr_set_end_slug;
	    $wpr__settings['wpsr_set_auth_check'] = $wpr_set_auth_check;
	    $wpr__settings['wpsr_set_enb_api'] = $wpr_set_enb_api;
	    $wpr__settings['wpsr_set_recent_activity_dur'] = $wpr_set_recent_activity_dur;
	    if(empty($_get_settings)){
	    	update_option('wspra_settings_meta' , $wpr__settings);
 	    }

      $prefix = $wpdb->prefix.WSPRA_DB;
      $query = array();
      $query['cus_api_base'] = "CREATE TABLE ".$prefix."api_base (
                                `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                `Fullname` text NOT NULL,
                                `Email` text NOT NULL,
                                `ApiSecret` text NOT NULL,
                                `Status` int(2) NOT NULL,
                                `CreatedAt` text NOT NULL
                              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      // $query['cus_api_endpoints'] = "CREATE TABLE ".$prefix."api_endpoints (
      //                           `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
      //                           `base` text NOT NULL,
      //                           `basedata` text NOT NULL,
      //                           `param` text  NULL,
      //                           `secret` text NOT NULL
      //                         ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      $query['cus_api_log'] = "CREATE TABLE ".$prefix."api_log (
                                `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                `secret` text NULL,
                                `requested_url` text NOT NULL,
                                `response_delivered` text NOT NULL,
                                `connectedAt` text NOT NULL,
                                `System_info` text,
                                `Ip` text NOT NULL
                              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      $query['notification_log'] = "CREATE TABLE ".$prefix."notification_log (
                                `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                `notification` text NULL,
                                `date_time` datetime NOT NULL
                              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

      foreach ($query as $q) {
            $wpdb->query($q); 
      }

   

	} //activate fun

}