<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WCRA_Rest_Controller extends WP_REST_Controller {
  
  var $my_namespace = 'wpapi';
  var $my_version   = '1';
   // Register our REST Server
  public function hook_rest_server(){
     $_get_settings = wspra_get_settings();
    //print_R($_get_settings);
        $_get_settings = wspra_get_settings('wpsr_set_enb_api');
     if($_get_settings == 1 ){
       add_action( 'rest_api_init',   'register_routes'  );
    }
    
  }
 
 //register endpoints with base
  public function register_routes() {
    echo 'asdasdasdas';
       $_get_settings = wspra_get_settings();
      $my_namespace = esc_attr($_get_settings['wpsr_set_end_slug']);
     $my_version = esc_attr($_get_settings['wpsr_set_ver']);
     $namespace = $my_namespace . '/v'. $my_version;
         register_rest_route( $namespace, '/store_user/', array(
    'methods' => 'GET',
    'callback' => 'wcra_wcra_test_callback',
     // $secret_key = sanitize_text_field('stallioni');
     // $_authintication = wspra_authintication($secret_key);
     //  if($_authintication['act'] == 'error'){
     //                   $error = wspra_response( wspra_http_response_code() , $_authintication['msg'] , $_authintication , $secret_key );
     //                return $error;
     //             }
  ) );

     // $get_points = wcra_endpoints_data('array');
    // if($get_points){
    //   foreach ($get_points as $registered_base => $basedata) {
    //       if($registered_base){
    //       register_rest_route( $namespace, '/' . $registered_base, array(   
    //         array(
    //             'methods'         => WP_REST_Server::ALLMETHODS,
    //             'callback'        => function($request){
    //                $parameters = $request->get_params();
    //                $secret_key = sanitize_text_field($request->get_param( 'secret_key' ));
    //                 $_authintication = wcra_authintication($secret_key);

    //                 if($_authintication['act'] == 'error'){
    //                   $error = wspra_response( wcra_http_response_code() , $_authintication['msg'] , $_authintication , $secret_key );
    //                   return $error;
    //                 }

    //                 $_get_base = wcra_get_base( wcra_request_url());
    //                 if(wcra_get_basedata($_get_base)){
    //                   $_get_basedata = wcra_get_basedata($_get_base);
    //                   $callback = unserialize($_get_basedata->basedata);
    //                   $callback = $callback['callback'];
    //                   if(!empty($callback)){
    //                     if(has_filter($callback)){
    //                       $parameters = $request->get_params();
    //                       $parameters['requested_url'] =  wcra_request_url();
    //                       return wspra_response( wcra_http_response_code(),'Response OK',$_authintication,apply_filters($callback, $parameters));                  
    //                     }else{
    //                        return wspra_response(200 , 'Connection OK', $_authintication,$parameters);
    //                     }
    //                   }
    //                 }
    //                 return $response =  wspra_response( 200 ,'Connection OK' , $_authintication , $parameters);
    //             },
    //             'permission_callback'   => function(){
    //               return true;
    //             }
    //           ),
    //       )  );

    //     }
    //   }
    // }

    
  }
function wcra_wcra_test_callback()
{
  echo 'call back retun';
}
}
 
