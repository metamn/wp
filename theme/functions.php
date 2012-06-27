<?php


// Init sessions
// - a hack to access $_SESSION in functions.php
// - needs to rewrite wp-includes/load.php too !!
// - http://www.myguysolutions.com/2010/04/14/how-to-enable-the-use-of-sessions-on-your-wordpress-blog/
// - http://www.kanasolution.com/2011/01/session-variable-in-wordpress/
if ( !session_id() )
add_action( 'init', 'session_start' );



// Global variables
//

define($CART, 'cos-cumparaturi');



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
      'success' => false,
      'message' => 'Nonce error'
    );
  }
    
  $response = json_encode($ret);
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}
add_action('wp_ajax_load_post_details', 'load_post_details');
add_action( 'wp_ajax_nopriv_load_post_details', 'load_post_details' );


// Cart functions
//

// Get cart items from session
// - $session : $_SESSION['eshopcart'.$blog_id]
// - returns an array of objects
function get_cart_items() {
  $ret = array();
  
  $session = $_SESSION['eshopcart1'];
  
  if (!(empty($session))) {
    foreach ($session as $product => $value) {
      $item = new stdClass();
      
      // from session
      $item->id = $product;
      $item->post_id = $value['postid'];
      $item->qty = $value['qty'];
      $item->name = $value['pid'];
      $item->variation_name = $value['item'];
      $item->variation_id = $value['option'];
      $item->price = $value['price'];
      
      // Add variation to name
      if ($item->variation_name != 'default') {
        $item->title = $item->name . " (" . $item->variation_name . ")";
      } else {
        $item->title = $item->name;
      }
      
      // from post
      $item->url = get_permalink($item->post_id);
      $item->thumb = post_thumbnails($item->post_id, $item->title, true);
      
      $ret[] = $item;
    }
  }
  
  return $ret;
}

// Get single cart item total (Quantity * Price)
function get_cart_item_total($qty, $price) {
  return intval($qty)*intval($price);
}


// Remove item from cart (AJAX)
// - functions.php cannot handle $_SESSION
// - therefore removed items are marked only to be removed
// - they will be really removed on the final checkout action
function remove_cart_item() {
  $nonce = $_POST['nonce'];  
  if ( wp_verify_nonce( $nonce, 'remove-cart-item' ) ) {
    
    $id = strval( $_POST['id'] ); 
    unset($_SESSION['eshopcart1'][$id]); 
    $items = get_cart_items();
    $empty = empty($items);
    
    $ret = array(
      'success' => true,
      'empty' => $empty
    );  
  
  } else {
    $ret = array(
      'success' => false,
      'message' => 'Nonce error'
    );
  }
    
  $response = json_encode($ret);
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}
add_action('wp_ajax_remove_cart_item', 'remove_cart_item');
add_action( 'wp_ajax_nopriv_remove_cart_item', 'remove_cart_item' );


// Product functions
//


// Get product data
// - returns a $post like object
function product($post_id){
  $ret = new stdClass();
  
  $p = get_eshop_product($post->ID);
  $ret->title = $p[sku];
  $ret->excerpt = $p[description];
  
  $l = get_post_meta($post_id, 'Livrare', true);
  if ($l) {
    $ret->livrare = $l;
  } else {
    $ret->livrare = "5-7";
  }
  
  return $ret;
}

// Display post thumbnails
function post_thumbnails($post_id, $title, $only_first = false) {
  $ret = "";
  
  $images = post_attachments($post_id);
  foreach ($images as $img) {
    $thumb = wp_get_attachment_image_src($img->ID, 'thumbnail'); 
    $large = wp_get_attachment_image_src($img->ID, 'full');
    
    $ret .= '<div class="item">';
    $ret .= "<img src='$thumb[0]' rev='$large[0]' title='$title' alt='$title'/>";
    $ret .= '</div>';
    if ($only_first) { break; }
  }
  
  return $ret;
}
// Adding featured image support for post
add_theme_support( 'post-thumbnails' ); 


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


// Other functions
//


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


// General functions
//

// Get the responsive image
function responsive_image($post_id) {
  $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
  $medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium');
  $small_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail');
  
  $ret = "<noscript data-large='$large_image_url[0]' data-medium='$medium_image_url[0]' data-small='$small_image_url[0]' data-alt='Koala'>";
  $ret .= "<img src='Koala.jpg' alt='Koala' />";
  $ret .= "</noscript>";
  
  return $ret;
}



?>
