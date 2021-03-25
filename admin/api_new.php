<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$api_user = isset($_POST['api_user']) ? sanitize_text_field($_POST['api_user']) : '';
$api_email = isset($_POST['api_email']) ? sanitize_email($_POST['api_email']) : '';
include_once('header.php');
 ?>
  <div class="gsr_back_body">
<div class="wraparea">
<h2>Generate Api Secret</h2>
<form action="" method="post">
<table class="form-table">
  <tbody>

    <tr>
    <td> 
      <div class="form-group">
        <label for="api_user" class="control-label">Api Auth key Name</label><br>
        <input type="text" class="form-control" name="api_user" id="api_user" value="<?php echo esc_attr($api_user); ?>" placeholder="Full Name">
      </div>
    </td>
    </tr>

    <tr>
    <td> 
      <div class="form-group">
        <label for="api_email" class="control-label">Api Email</label><br>
        <input type="email" class="form-control" name="api_email" value="<?php echo esc_attr($api_email); ?>" placeholder="Email">
      </div>
    </td>
    </tr>

  </tbody>
</table>
<p class="submit"><input name="submit" id="submit_access" class="button button-primary" value="<?php _e('Save'); ?>" type="submit"></p>
</form>
</div>
</div>

<?php 
if(isset($_POST['submit'])){
  global $wpdb;
  $tab = $wpdb->prefix.WSPRA_DB.'api_base';
  unset($_POST['submit']);
  $api_email = sanitize_email($_POST['api_email']);
  $api_user = sanitize_text_field($_POST['api_user']);
  $checkQ = "SELECT * FROM $tab WHERE Email = '$api_email' ";
  //echo $checkQ;die;
  $get = $wpdb->get_row($checkQ);
  //print_r($get);die;
  if(empty($api_user)){
     echo '<script>alert("Full Name required!");</script>';
  }else if(empty($api_email)){
    echo '<script>alert("Email required!");</script>';
  }else if(count($get)){
    echo '<script>alert("Email already in use");</script>';
  }else{
    $ApiSecret = wcra_api_key_gen();
    $data = array('Fullname' => $api_user , 'Email' => $api_email , 'ApiSecret' => $ApiSecret , 'CreatedAt' => date('Y-m-d H:i:s'));
    $insert = $wpdb->insert($tab , $data );
    if($insert){
      $notification = "<strong>1</strong> Secret Key has been generated for <strong> $api_email</strong>";
        
      echo '<script>alert("Secret Generated Successfully");</script>';
      print('<script>window.location.href="admin.php?page=wspra_new_api"</script>');
    }
  }

}
 //echo $tab = $wpdb->prefix.WSPRA_DB.'api_base';
 $_get_list_api_access = wspra_get_list_api_access();
//print_R($_get_list_api_access);
global $wpdb;
if(isset($_GET['s']) && isset($_GET['id']) ){
  if( intval(sanitize_text_field($_GET['id'])) > 0 ){
    $data = array('Status' => intval(sanitize_text_field($_GET['s']) ));
    $where = array('id' => intval(sanitize_text_field($_GET['id']) ));
    $tab = $wpdb->prefix.WSPRA_DB.'api_base';
    $update = $wpdb->update( $tab , $data , $where);
    if($update){
      $st = intval(sanitize_text_field($_GET['s'])) == 0 ? 'Activated' : 'Deactivated';
      $_get_email_by_id = wcra_get_email_by_id(intval(sanitize_text_field($_GET['id'])));
      $notification = "A Secret has been $st - <strong>$_get_email_by_id</strong>";
      wcra_save_recent_activity(array('txt' => $notification ));
      print('<script>window.location.href="admin.php?page=wcra_api_list"</script>');
    }
    
  }
}
$prefix = $wpdb->prefix.WSPRA_DB;

 ?>
  <div class="gsr_back_body">
<div class="wraparea">
<h2 wp-heading-inline >Access Lists</h2>

<table class="wp-list-table widefat fixed striped posts">
<thead>
  <tr>
    <td>User Name</td>
    <td>Email</td>
    <td>Api Secret</td>  
    <td>Created At</td>
    <td>Status</td>
    <td>Action</td>
  </tr>
</thead>

  <tbody>

   <?php if(count($_get_list_api_access)){
      foreach ($_get_list_api_access as $key => $value) {
        $staus = $value->Status == 0 ? '<span class="fa fa-circle" style="color:green;">Active</span>' : '<span class="fa fa-circle" style="color:red;" >Not Active</span>';
        $action = $value->Status == 0 ? '<a href="?page=wcra_api_list&s=1&id='.esc_attr($value->id).'" class="btn btn-info actctiuser">Deactivate</a>' : '<a href="?page=wcra_api_list&s=0&id='.esc_attr($value->id).'" class="btn btn-info actctiuser">Activate</a>';
        ?>
        <tr>
          <td><?php echo $value->Fullname; ?></td>
          <td><?php echo $value->Email; ?></td>
           <td><?php  echo $value->ApiSecret; ?></td>  
          <td><?php echo $value->CreatedAt; ?></td>
          <td><?php echo $staus; ?></td>
          <td><?php echo $action; ?></td>
        </tr>
        <?php
      }

    }else{
      ?>
      <tr><td colspan="5" align="left">No Records</td></tr>
      <?php
      } ?>

  </tbody>
<tfoot>
  <tr>
    <td>User Name</td>
    <td>Email</td>
     <td>Api Secret</td> 
    <td>Created At</td>
    <td>Status</td>
    <td>Action</td>
  </tr>
</tfoot>
</table>
</div>
</div>
<script type="text/javascript">
   jQuery('.actctiuser').on('click' , function(){
    if(confirm("Confirm!")){
      return true;
    }else{
      return false;
    }
   });
</script>