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




// Session functions
//

// Register every click into the session db (AJAX)
function session_click() {
  $url = strval( $_POST['id'] ); 
  $post = get_page_by_path($url);  
  
  /*
  if ($post) {
    $_SESSION['ujs_clicks'][] = $post->ID;    
    $ret = array(
      'success' => true,
      'message' => "$post->ID"
    );      
  } else {
    $ret = array(
      'success' => false,
      'message' => 'Cannot get page id'
    );
  }
  */
  
  $ret = array(
    'success' => true,
    'message' => "$post->ID"
  );
    
  $response = json_encode($ret);
  header( "Content-Type: application/json" );
  echo $response;
  exit;
}
add_action('wp_ajax_session_click', 'session_click');
add_action( 'wp_ajax_nopriv_session_click', 'session_click' );


// Initialize session
function init_session() {  
  print_r($_SESSION);
  
  $post = get_post_id();  
  echo "PID: $post";
  
  
  // create new session id if necessary
  if (!($_SESSION['ujs_user'])) {
    $_SESSION['ujs_user'] = generateRandomString();
  }
  // create new array to store browsing history
  if (empty($_SESSION['ujs_clicks'])) {
    $_SESSION['ujs_clicks'] = array();
  }
  
  return $_SESSION;
}






// Cart functions
//

// Get cart items from session
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


// Load post details on an index page (AJAX)
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

// Generate unique ID
function generateRandomString($length = 10) {
  $characters = ’0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ’;
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

// Get the current page, post or category ID
// - $url is the full url of the post/cat/page as is in the browser
function get_post_id($url = '') {
  if ($url == '') {
    $url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  }
  
  $url = remove_hostname($url);
  
  // homepage
  if ($url == "/") {
    $ret = 0;
  } else {  
    // page
    $page = get_page_by_path($url);
    if ($page) {
      $ret = "p-$page->ID";
    } else {
      // category
      $cat = remove_taxonomy($url, 'category');    
      $term = get_term_by('slug', $cat, 'category');
      if ($term) {
        $ret = "c-$term->term_id";
      } else {
        // tag
        $tag = remove_taxonomy($url, 'tag'); 
        $term = get_term_by('slug', $tag, 'post_tag');
        if ($term) {
          $ret = "t-$term->ID";
        } else {
          // post
          $slug = get_post_naked_slug($url);        
          $post = get_post_by_slug($slug);
          if ($post) {
            $ret = "p-$post->ID";
          } else {
            $ret = "-1";
          }
        }      
      }
    } 
  }
  
  return $ret;
}


// Remove hostname from url
// - $url is the full Wordpress url (http://smuff.ro/article-111-11)
function remove_hostname($url) {
  $u = explode(get_bloginfo('home'), $url);
  return $u[1];
}

// Remove taxonomy from slug
// - $slug is the trimmed Wordpress url (/category/uncategorized)
// - $type is either 'category' or 'tag'
// - only one taxonomy must be returned: 'produse' or 'gadget'; 'produse/gadget' won't work
function remove_taxonomy($slug, $type) {
  $u = explode($type, $slug);
  $r = explode("/", $u[1]);
  // return the last category name
  $c = count($r);
  return $r[$c-2];
}

// Remove date from post slug
function get_post_naked_slug($url) {
  // remove date
  $r = explode("/", $url);
  return $r[4];
}

// Get post by name
// - $slug is the naked slug: 'ali-baba'
function get_post_by_slug($slug) {
  $args = array(
    'name' => $slug,
    'post_type' => 'post',
    'post_status' => 'publish',
    'numberposts' => 1
  );
  $post = get_posts($args);
  if ($post) {
    return $post[0];
  };
}

?>
