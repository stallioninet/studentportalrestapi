<?php
/** @wordpress-plugin
 * Plugin Name:       Student Portal With Rest Api
 * Plugin URI:        http://stallioni.com
 * Description:       Add Custom Endpoints to the Wordpress REST API 
 * Version:           1.0.1
 * Author:            stallioni aruljothi
 * Text Domain:       studentwprest
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'WSPRA_PLUGIN_VERSION', '1.0.1' );
define( 'WSPRA_DB', 'wspa_' );
define( 'WSPRA_PLUGIN_SLUG', 'student-portal-rest-api' );
define( 'WSPRA_PLUGIN_TEXTDOMAIN', 'studentwprest' );

$plugin = plugin_basename( __FILE__ );

register_activation_hook( __FILE__, 'wspra_activate_plugin' );
register_deactivation_hook( __FILE__, 'wspra_deactivate_plugin' );

function wspra_activate_plugin()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-studentwprest-activator.php';
	WSPRA_Activator::activate();
}

function wspra_deactivate_plugin()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-studentwprest-deactivator.php';
	WSPRA_Deactivator::deactivate();
}
add_action( 'admin_menu', 'wspra_add_admin_menu' );
function wspra_add_admin_menu()
{
  $page_title = 'WSPRA';
  $menu_title = 'Student Portal API';
  $capability = 'manage_options';
  $menu_slug = 'wspra_api_endpoints';
  $function = 'wspra_menu_callback';
  $icon_url  = plugins_url(WSPRA_PLUGIN_SLUG.'/img/menu_icon.png');
  $position  = 5;
  add_menu_page(  $page_title,  $menu_title,  $capability,  $menu_slug,  $function  ,$icon_url ,$position );
  add_submenu_page( $menu_slug ,  'Log', 'Log',  $capability, 'wspra_api_log', 'wspra_menu_logs_callback' );
    add_submenu_page( $menu_slug ,  'New Api Secret', 'New Api Secret',  $capability, 'wspra_new_api', 'wspra_menu_new_api_callback' );
  // add_submenu_page( $menu_slug ,  'Secret List', 'Secret List',  $capability, 'wcra_api_list', 'wcra_menu_api_list_callback' );
  // add_submenu_page( $menu_slug ,  'Settings', 'Settings',  $capability, 'wcra_api_settings', 'wcra_menu_settings_callback' );
  // add_submenu_page( $menu_slug ,  'Log', 'Log',  $capability, 'wcra_api_log', 'wcra_menu_logs_callback' );
  // add_submenu_page( $menu_slug ,  'Recent Activity', 'Recent Activity',  $capability, 'wcra_api_recent_activity', 'wcra_menu_activity_callback' );
  // add_submenu_page( $menu_slug ,  'Walk Through', 'Walk Through',  $capability, 'wcra_api_walk_help', 'wcra_api_walk_help_callback' );
}
function wspra_menu_callback()
{
  require_once 'admin/wspr_api_endpoints.php';   
}
function wspra_menu_logs_callback()
{
  require_once 'admin/api_log_display.php';
  require_once 'admin/api_log.php';
}
function wspra_menu_new_api_callback()
{
  require_once 'admin/api_new.php';
} 
    

function wspra_get_settings($key=''){
	$wpr__settings = get_option('wspra_settings_meta');
	if($key){
		return $wpr__settings[$key];
	}else{
		return $wpr__settings;
	}
}

function wcra_api_key_gen(){
    $output = '';
      for($loop = 0; $loop <= 31; $loop++) {
          for($isRandomInRange = 0; $isRandomInRange === 0;){
              $isRandomInRange = wscra_isRandomInRange(wscra_findRandom());
          }
          $output .= html_entity_decode('&#' . $isRandomInRange . ';');
      }
      return $output;
}
 

function wcra_endpoints_data($format=''){
  global $wpdb;
  $tab = $wpdb->prefix.WCRA_DB.'api_endpoints';
  $q = "SELECT * FROM $tab ORDER BY id DESC";
  $get_endspts = $wpdb->get_results($q);
  if($format == 'array'){
    foreach ($get_endspts as $key => $value) {
      $callback = unserialize($value->basedata);
      $data[$value->base] = array('callback' => $callback);
    }
    return $data;
  }else{
    return $get_endspts;
  }
}

function wscra_findRandom() {
    $mRandom = rand(48, 122);
    return $mRandom;
}

function wscra_isRandomInRange($mRandom) {
    if(($mRandom >=58 && $mRandom <= 64) ||
            (($mRandom >=91 && $mRandom <= 96))) {
        return 0;
    } else {
        return $mRandom;
    }
}

function wspra_admin_page_tabs( $current = 'wcra_new_api' ) {
    $tabs = array(
        'wspra_api_endpoints'  => __( '<i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Endpoint URLs', 'plugin-textdomain' ),
          'wspra_new_api'   => __( '<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;New Api Secret', 'plugin-textdomain' ), 
       // 'wspra_api_list'  => __( '<i class="fa fa-user-secret" aria-hidden="true"></i>&nbsp;Secret List', 'plugin-textdomain' ),
        //'wcra_api_settings'  => __( '<i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Settings', 'plugin-textdomain' ),       
        'wspra_api_log'  => __( '<i class="fa fa-history" aria-hidden="true"></i>&nbsp;Log', 'plugin-textdomain' ),
        //'wcra_api_recent_activity'  => __( '<i class="fa fa-bell" aria-hidden="true"></i>&nbsp;Recent Activity', 'plugin-textdomain' ),
       // 'wcra_api_walk_help'  => __( '<i class="fa fa-question-circle" aria-hidden="true"></i>&nbsp;Walk Through', 'plugin-textdomain' ),
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab navmar ' . esc_attr($class) . '" href="?page=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}
/*****************************************************************************/
add_action( 'admin_enqueue_scripts','enqueue_styles');
function enqueue_styles()
{
    wp_enqueue_style( 'wcra_back_handle_css', plugin_dir_url( __FILE__ ) . 'admin/js/customwprest-admin.css', array(),1.0, 'all' );
    wp_enqueue_script( 'wcra_back_handle',  plugin_dir_url( __FILE__ ) . 'admin/js/customwprest-admin.js'  );
}
 require_once plugin_dir_path( __FILE__ ). 'includes/Rest.inc.php';

 
/**********************************************************/
// add_action('wp_init','hook_rest_server_new') ;
// function hook_rest_server_new(){
//  echo  $_get_settings = wspra_get_settings('wpsr_set_enb_api');
//      if($_get_settings == 1 ){
//        add_action( 'rest_api_init',   'register_routes_arul'  );
//     }
// }
add_action( 'rest_api_init',   'register_routes_arul'  );
 function register_routes_arul()
 {
    $my_namespace = 'wpapi';
    $my_version   = '1';
     $_get_settings = wspra_get_settings();
      $my_namespace = esc_attr($_get_settings['wpsr_set_end_slug']);
     $my_version = esc_attr($_get_settings['wpsr_set_ver']);
     $namespace = $my_namespace . '/v'. $my_version;
         register_rest_route( $namespace, '/store_user/', array(
            'methods' => 'POST',
            'callback' => 'wcra_wcra_test_callback' 
            //  {
            //        $parameters = $request->get_params();
            //        $secret_key = sanitize_text_field($request->get_param( 'secret_key' ));
            //        $_authintication = wspra_authintication($secret_key);

            //         if($_authintication['act'] == 'error'){
            //           $error = wspra_response( wspra_http_response_code() , $_authintication['msg'] , $_authintication , $secret_key );
            //           return $error;
            //         }
            // return $response =  wcra_response( 200 ,'Connection OK' , $_authintication , $parameters);
            //     },
            //     'permission_callback'   => function(){
            //       return true;
            //     }
   ) );
 }
 function wcra_wcra_test_callback(WP_REST_Request $request)
 {
      $parameters = $request->get_params();
      $headers = $request->get_headers();
      $_authintication = wspra_authintication($secret_key);
      $First_Name = $request->get_param( 'First_Name' );
      $Last_Name = $request->get_param( 'Last_Name' );
      $Email = $request->get_param( 'Email' );
      $Username = $request->get_param( 'Username' );
      $Password = $request->get_param( 'Password' );
      $Enrolled_Courses = $request->get_param( 'Enrolled_Courses' );
      $secret_key = sanitize_text_field($request->get_headers( 'Api_key' )); 
      $_authintication = wspra_authintication($secret_key);
           if($_authintication['act'] == 'error'){
            $error = wspra_response( wspra_http_response_code() , $_authintication['msg'] , $_authintication , $secret_key );
               $error =1;
            }
            else{
                $error = 0;
            }
   //print_R($parameters);
   if($error == 1)
   {
         //error 
    return    wspra_response( 401 ,'Connection error' , $_authintication , $parameters);
   }else{

      $nameErr = $email_exists = $emailErr = $usernameErr = $coursesErr = "";$Errmessage ='';
     if (empty( $First_Name)) {
          $Errmessage = "First Name is required";
        } else {
          $name = test_input_validations($First_Name);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $Errmessage = "Only letters and white space allowed in First Name";
          }
        }

        if (empty( $Last_Name)) {
          $Errmessage = "Last Name is required";
        } else {
          $namelast = test_input_validations($Last_Name);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z-' ]*$/",$namelast)) {
            $Errmessage = "Only letters and white space allowed Last Name";
          }
        }

         
        if (empty($Email)) {
            $Errmessage = "Email is required";
          } else {
            $email = test_input_validations($Email);
             if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $Errmessage = "Invalid email format";
               $email_exists = email_exists( $Email ) ;
            }
          }

          if (empty($Username)) {
            $Errmessage = "Username is required";
            } else {
             $checkuser = validate_username( $Username );
           }
           if (empty($Password)) {
            $Errmessage = "Password is required";
            }
           if (empty($Enrolled_Courses)) {
            $Errmessage = "Courses is required";
            } 

           if( $Errmessage == '')
          {
            //inserted
            $exist_user_id = email_exists( $email );
            $enarr = $Enrolled_Courses;
        	if( $enarr !='')
        	{
        		$enarr = implode(',',$enarr);
        	}
       
           if($exist_user_id)
         	{
         	    //update course detaila only
         	    update_user_meta( $exist_user_id, 'enrolled_courses', $Enrolled_Courses);
         	    $successmessage = 'User Updated Successfully!';
         	}else{
         	    //insaerted the data 
         	    global $wpdb,$user;
         	  $data = array(
                         'user_login'           => $Username,  
                         'user_pass'            => $Password, 
                         'user_email'            => $email, 
                         'nickname'              => $name,    
                         'first_name'            => $name,  
                         'last_name'             => $namelast,
                    );
                      
                    $user_id = wp_insert_user( $data );
                    wp_update_user( array( 'ID' => $user_id, 'role' => 'student' ) );
                    update_user_meta( $user_id, 'enrolled_courses', $Enrolled_Courses);
                           $successmessage = 'User created Successfully!';
    
         	}
        	
        	
             //global $wpdb;
            // $wpdb->query("INSERT INTO  `usertable`(`first_name`,`last_name`,`password`,`email`,`courses`) VALUES('$First_Name','$Last_Name','$Password','$Email','$Enrolled_Courses') ");
            return    wspra_response( 200 , $successmessage , 'Connection Ok' , $parameters );
          }else{
            return    wspra_response( 406 ,$Errmessage , 'Connection not set' , $parameters );
          }
         
   }

  //  return    wspra_response( 200 ,'Connection OK' , $_authintication , $parameters);
         
       
 }
 /********************************************************/
 // function check_the_api_givenvalue($textinput,$nameval)
 // {
 //     if (empty( $textinput)) {
 //          $Errmessage = "First Name is required";
 //        } else {
 //          $name = test_input_validations($textinput);
 //          // check if name only contains letters and whitespace
 //          if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
 //            $Errmessage = "Only letters and white space allowed";
 //          }
 //        }
 //        if(empty($Errmessage))
 //        {

 //        }else{
 //             return    wspra_response( 401 , $nameval .'is Required!' , $_authintication , $parameters);
 //           }
 // }
function test_input_validations($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function wcra_get_logged_user(){
  $id = get_current_user_id();
  $data = get_user_by('ID' , $id);
  return $data->display_name;
}
 function wspra_authintication($secret){
  global $wpdb;
  $get = array();
  $secret = esc_attr($secret);
  // $_get_root_secret = wspra_get_root_secret();
  // $_get_settings = wspra_get_settings();
  // $wpr_set_auth_check = esc_attr($_get_settings['wpr_set_auth_check']);
  // if($wpr_set_auth_check == 0 ){
  //   return array('act' => 'success' , 'msg' => 'Secret by-passed','secret' => $secret );
  // }

  // if(empty($secret)){
  //   return array('act' => 'error' , 'msg' => 'invalid Request, Secret Key Required','secret' => $secret );
  //    }
  if(empty($secret))
  {
     return array('act' => 'error' , 'msg' => 'Invalid Secret Key' ,'secret' => $secret);
  }else{
        $tab = $wpdb->prefix.WSPRA_DB.'api_base';
        $checkQ = "SELECT * FROM $tab WHERE ApiSecret = '$secret' ";
        //return $checkQ;
        $get = $wpdb->get_row($checkQ);
        if($get){
          if($get->Status == 0){
            return array('act' => 'success' , 'msg' => 'Secret Key matched!','secret' => $secret );
          }else{
            return array('act' => 'error' , 'msg' => 'Secret key has been blocked','secret' => $secret );
          }
          
        }else if($secret == $_get_root_secret){
          return array('act' => 'success' , 'msg' => 'Secret by passed by Root' ,'secret' => $secret);
        }else{
          return array('act' => 'error' , 'msg' => 'Invalid Secret Key' ,'secret' => $secret);
        }
    }
 }
/*********************************************************************/
function wcra_get_username($secret){
	global $wpdb;
 	$tab = $wpdb->prefix.WSPRA_DB.'api_base';
 	$checkQ = "SELECT * FROM $tab WHERE ApiSecret = '$secret' ";
 	$get = $wpdb->get_row($checkQ);
 	if($get){
 		return $get->Fullname;
 	}else{
 		return false;
 	}
}
 function wspra_get_root_secret(){
  $_get_settings = wspra_get_settings();
  $root_secret = $_get_settings['root_secret'];
  return $root_secret;
}

  if (!function_exists('wspra_http_response_code')) {
        function wspra_http_response_code($code = NULL) {

            if ($code !== NULL) {

                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }

                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

                header($protocol . ' ' . $code . ' ' . $text);

                $GLOBALS['wcra_http_response_code'] = $code;

            } else {

                $code = (isset($GLOBALS['wcra_http_response_code']) ? $GLOBALS['wcra_http_response_code'] : 200);

            }

            return $code;

        }
    }
function wspra_get_list_api_access(){
  global $wpdb;
  $tab = $wpdb->prefix.WSPRA_DB.'api_base';
  $checkQ = "SELECT * FROM $tab ORDER BY id DESC";
  $get = $wpdb->get_results($checkQ);
  if(count($get)){
    return $get;
  }else{
    return array();
  }
}