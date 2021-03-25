<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
include_once('header.php');

$tab = $wpdb->prefix.WSPRA_DB.'api_endpoints';
?>
<h3>URL of API: <br><a href="<?php echo home_url();?>/wp-json/wspra/v1/store_user"><?php echo home_url().'/wp-json/wspra/v1/store_user';?></a>
</h3>
<br>
<h3>Parameters to pass:</h3>
<p>secret_key : 'required/given in the New API secret tab'</p>
<p>First_Name : 'required/only allwed character and space'</p>
<p>Last_Name : 'required/only allwed character and space'</p> 
<p>Email : 'required/only allwed character and space'</p>
<p>Username : 'required/allwed only character'</p>
<p>Password : 'required/All all character and space'</p>
<p>Enrolled_Courses : 'required/courses id with comma separater '</p>

<h3>Return values</h3>
<p>User created</p>
<p>User Updated</p> 
<p>If error display error message  </p>