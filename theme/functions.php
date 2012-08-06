<?php
// Init sessions
// - it is used to access shopping cart data in functions.php
//
// - a hack to access $_SESSION in functions.php
// - needs to rewrite wp-includes/load.php too !!
// - http://www.myguysolutions.com/2010/04/14/how-to-enable-the-use-of-sessions-on-your-wordpress-blog/
// - http://www.kanasolution.com/2011/01/session-variable-in-wordpress/
if ( !session_id() ) 
  add_action( 'init', 'session_start' );



// Global variables
//

// the slug of the shopping cart page
define("CART", 'cos-cumparaturi');

// in how many hours a session expires
define("NEW_SESSION_HRS", 3);

// visitor types
define("PASSIVE", 1);
define("INTERACTIVE", 2);
define("CONTACTABLE", 3);




// Session & Cookie functions
//

// How it works
//
// - $_SESSION is stored in the DB/MySQL until the browser is closed
// - the session must be saved into a cookie / db for persistence
// - therefore instead of $_SESSION we use cookies/db
//
// - cookies are sent with every HTTP request => size must be minimal
// - recommended: max 20 cookies, 4KB each
//
// - our approach:
//
//  1. Identify visitor with a single simple cookie: ujs_id
//  2. Save all stuff into DB under this id
//  3. Connect cart, wishlist etc with this cookie



// Manage session
//
// - called in header at every page load
// - returns a standard class:
//  - returning: boolean, if a visitor is returning or not
//  - visits: array, all the visits of the visitor
//  - clicks: array, all the clicks of the visitor
//  - new_visit: this is a new visit
//  - type: [contactable, shopper, ...] 

// Arguments
// - $post_id = the id of an AJAX action
function manage_session($post_id = '') {  
  $session = new stdClass();
  
  $id = $_COOKIE['ujs_user'];
  if ($post_id == '') {
    $post_id = get_post_id();
  }
  $now = current_time('timestamp');
    
  // create new session id, if necessary
  if (!($id)) {
    $session->returning = false;
           
    setcookie('ujs_user', generateRandomString(), time()+60*60*24*500, '/');
    $id = $_COOKIE['ujs_user'];        
  } else {
    $session->returning = true;
  }    
      
  // save to db
  db_save_session($id, $post_id, $now); 
    
  // load info from DB
  $s = db_get_session($id);
  if ($s) {
    $session->visits = $s->visits;
    $session->clicks = $s->clicks;
    $session->type = 'aaa';
  }    
  
  return $session;
}

// Load session
function load_session() {
  $session = new stdClass();
  
  $id = $_COOKIE['ujs_user'];
  
  $s = db_get_session($id);
  if ($s) {
    $session->visits = $s->visits;
    $session->clicks = $s->clicks;
    $session->type = CONTACTABLE;
  }    
  
  return $session;
}


// Save or create session to DB
function db_save_session($id, $post_id, $timestamp) {  
  global $wpdb;
  $wpdb->show_errors();
  
  if ($id) {
    $existing = db_get_session($id);
    if ($existing) {
      $clicks = $existing->clicks . $post_id . ',';
      
      // check if this is a new visit
      $visits = $existing->visits;
      if (is_new_visit($visits, $timestamp)) {
        $visits .= $timestamp . ',';  
      }
    } else {
      $clicks = $post_id . ',';
      $visits = $timestamp . ',';
    }
    
    return $wpdb->query( 
      $wpdb->prepare( 
      "
	      INSERT INTO wp_recommendation_engine
	      (cookie, visits, clicks)
	      VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE visits=VALUES(visits), clicks=VALUES(clicks)
      ", 
      array($id, $visits, $clicks)
      )
    );  
  } else {
    return false;
  }
}


// Load an entry from the session DB
function db_get_session($id) {
  if (($id) && ($id != '')) {
    global $wpdb;
    $wpdb->show_errors();
    
    $ret = $wpdb->get_results( 
	    "SELECT * FROM `wp_recommendation_engine` WHERE `cookie`='" . $id ."'"
    );
    
    return $ret[0];
  } else {
    return false;
  }
}


// Check if this is a new browsing session or not
function is_new_visit($visits, $now) {
  $old = get_last_explode(explode(',', $visits));
  
  if ($old) {  
    return ($now - $old > 60*60*NEW_SESSION_HRS); 
  } else {
    return true;
  }
}




// Cart functions
//


// Add to cart (AJAX)
function add_to_cart() {
  $nonce = $_POST['nonce'];  
  if ( wp_verify_nonce( $nonce, 'add-to-cart' ) ) {
    
    // Create new cart item
    $item = array();
    
    $item['postid'] = strval( $_POST['id'] );
    $item['qty'] = strval( $_POST['qty'] );
    $item['item'] = strval( $_POST['variation-name'] );
    $item['option'] = strval( $_POST['variation-id'] ) + 1;
    $item['price'] = strval( $_POST['price'] );
    
    // Save item
    $items = $_SESSION['eshopcart1'];
    if ($items) {
      $counter = 1;
      foreach ($items as $product => $value) {
        if ( ($item['postid'] == $value['postid']) && 
             ($item['option'] == $value['option']) &&
             ($item['price'] == $value['price']) ) {
             
             $_SESSION['eshopcart1'][$counter]['qty'] += 1;
             $counter += 1;             
             }       
        
      }    
    } else {
      $_SESSION['eshopcart1'][] = $item;      
    }
    
    // Register action
    manage_session('cart-a-' . $id);
    
    
    $ret = array(
      'success' => true,
      'message' => 'Ok'
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
add_action('wp_ajax_add_to_cart', 'add_to_cart');
add_action( 'wp_ajax_nopriv_add_to_cart', 'add_to_cart' );


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
function remove_cart_item() {
  $nonce = $_POST['nonce'];  
  if ( wp_verify_nonce( $nonce, 'remove-cart-item' ) ) {
    
    $id = strval( $_POST['id'] ); 
    unset($_SESSION['eshopcart1'][$id]); 
    $items = get_cart_items();
    $empty = empty($items);
    
    // Register action
    manage_session('cart-r-' . $id);
    
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
  
  //print_r($p[products]);
  $ret->title = $p[sku];
  $ret->excerpt = $p[description];
  
  foreach ($p[products] as $key => $v) {
    $variation = new stdClass();
    
    $variation->name = $v[option];
    $variation->price = $v[price];
    $variation->sale = $v[saleprice];
    
    $ret->variations[] = $variation;
  }
  
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
  $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

// Get the current page, post or category ID
// - $url is the full url of the post/cat/page as is in the browser
// - returns an integer:
//  - homepage: 0
//  - search: 1
//  - page: p-XXX
//  - category: c-XXX
//  - tag: c-XXX
//  - post: x-XXX
//  - not found: -1
function get_post_id() {
  // This is necessary as is, unless not working
  $url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  $url = remove_hostname($url);
    
  // search
  if (strpos($url,'?s=') !== false) {
    $ret = 1;
  } else {
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
              $ret = "x-$post->ID";
            } else {
              $ret = "-1";
            }
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
  return get_last_explode($r);
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


// Return the last item of an exploded string
function get_last_explode($explode) {
  $c = count($explode);
  return $explode[$c-2];  
}

?>
