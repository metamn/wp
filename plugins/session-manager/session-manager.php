<?php
/*
Plugin Name: Smuff Session Manager
Plugin URI: http://www.smuff.ro
Description: For non logged in users
Version: 0.1
Author: cs
Author URI: http://www.smuff.ro
License: none
*/


// Admin menu
function session_manager_admin_menu() {  
  add_menu_page('Session Manager', 'Session Manager', 'delete_others_posts', 'session-manager-menu', 'session_manager_main_page' );
  add_action( 'admin_init', 'session_manager_tables' );
} 
add_action('admin_menu', 'session_manager_admin_menu');


// Create database tables
function session_manager_tables() {
  global $wpdb;
  
  // Main table
  $table = $wpdb->prefix . "session_manager";
  $sql = "CREATE TABLE $table (
      id INT(9) NOT NULL AUTO_INCREMENT,
      cookie VARCHAR(80) NOT NULL,      
      visits VARCHAR(1200),
      clicks VARCHAR(1200) NOT NULL,
      PRIMARY KEY(id),
      UNIQUE KEY cookie (cookie)
  );";
  
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}


// Dashboard
function session_manager_main_page() {
  if (!current_user_can('delete_others_posts'))  {
    wp_die( 'Nu aveti drepturi suficiente de acces.' );
  } 
  ?>
  
  <div id="session_manager">
    <h2>session_manager</h2>    
  </div>
  
  <?php
}


?>

