<?php
/*
Plugin Name: Smuff Recommendation Engine
Plugin URI: http://www.smuff.ro
Description: For non logged in users
Version: 0.1
Author: cs
Author URI: http://www.smuff.ro
License: none
*/


// Admin menu
function recommendation_engine_admin_menu() {  
  add_menu_page('Recommendation Engine', 'Recommendation Engine', 'delete_others_posts', 'session-manager-menu', 'recommendation_engine_main_page' );
  add_action( 'admin_init', 'recommendation_engine_tables' );
} 
add_action('admin_menu', 'recommendation_engine_admin_menu');


// Create database tables
// Table structure:
//  - id: unique id
//  - cookie: cookie id
//  - visits: a date array of visits
//  - clicks: an array of user clicks
//  - type: the current profile summary of the user [passive, interactive, contactable, ...]
function recommendation_engine_tables() {
  global $wpdb;
  
  // Main table
  $table = $wpdb->prefix . "recommendation_engine";
  $sql = "CREATE TABLE $table (
      id INT(9) NOT NULL AUTO_INCREMENT,
      cookie VARCHAR(80) NOT NULL,      
      visits VARCHAR(1200),
      clicks VARCHAR(1200),
      type VARCHAR(80),
      PRIMARY KEY(id),
      UNIQUE KEY cookie (cookie)
  );";
  
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}


// Dashboard
function recommendation_engine_main_page() {
  if (!current_user_can('delete_others_posts'))  {
    wp_die( 'Nu aveti drepturi suficiente de acces.' );
  } 
  ?>
  
  <div id="recommendation_engine">
    <h2>recommendation_engine</h2>    
  </div>
  
  <?php
}

?>

