<?php


// Ajax functions
//

// Load post details on an index page
function load_post_details() {  
  $nonce = $_POST['nonce'];
  if ( wp_verify_nonce( $nonce, 'load-post-details' ) ) {
    
    $post_id = intval( $_POST['post_id'] );  
    $post = wp_get_single_post($post_id);
    $thumbs = post_thumbnails($post_id, $post->post_title);
    
    $ret = array(
      'success' => true,
      'body' => $post->post_content,
      'thumbs' => $thumbs
    );
  
  
  } else {
    $ret = array(
      'success' => false
    );
  }
    
  $response = json_encode($ret);
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}
add_action('wp_ajax_load_post_details', 'load_post_details');


// Display post thumbnails
function post_thumbnails($post_id, $title) {
  $ret = "";
  
  $images = post_attachments($post_id);
  foreach ($images as $img) {
    $thumb = wp_get_attachment_image_src($img->ID, 'thumbnail'); 
    $large = wp_get_attachment_image_src($img->ID, 'full');
    
    $ret .= '<div class="item">';
    $ret .= "<img src='$thumb[0]' rev='$large[0]' title='$title' alt='$title'/>";
    $ret .= '</div>';
  }
  
  return $ret;
}

// Get post attachments / images
function post_attachments($post_id) {  
  $args = array(
	  'post_type' => 'attachment',
	  'numberposts' => -1,
	  'post_status' => null,
	  'post_parent' => $post_id,
	  'orderby' => 'menu_order',
	  'order' => 'ASC'
  ); 
  $attachments = get_posts($args);
  return $attachments;
}


// Get the responsive image
function responsive_image($post_id) {
  $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
  $medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium');
  
  $ret = "<noscript data-large='$large_image_url[0]' data-small='$medium_image_url[0]' data-alt='Koala'>";
  $ret .= "<img src='Koala.jpg' alt='Koala' />";
  $ret .= "</noscript>";
  
  return $ret;
}


// Determine what kind of content is displayed
// - like search, archive etc ...
// - if necessary the title is displayed, else will stay hidden
// - this is first of all for the HTML5 Outliner
function get_content_title() {  
  // By default content title is not displayed
  $hidden = "class='hidden'";
  
  // By default title is derived from body_class
  $body_class = get_body_class();
  if ($body_class) {
    $title = ucfirst($body_class[0]);
  } else {
    $title = "Content";
  }
  
  if (is_category()) {
    $hidden = '';
    $title = single_cat_title('', false);
  }
  
  if (is_search()) {
    $hidden = '';
    $title = "Search for " . get_search_query();
  }
  
  return "<h3 $hidden>$title</h3>";
}


// Adding featured image support for post
add_theme_support( 'post-thumbnails' ); 


?>
