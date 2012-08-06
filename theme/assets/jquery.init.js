jQuery(document).ready(function() {

  // Set up Ajax
  var ajaxurl = jQuery("#ajax-url").attr("data-url");
  var ajaxloading = jQuery("#ajax-url").attr("data-loading");
  var ajaxspinner = "<img src='" + ajaxloading + "' alt='Incarcare date ...' />";
  var ajaxerror = "Eroare incarcare date de pe server ... ";
  // When cart empty redirect to checkout page
  var checkout_url = jQuery("#checkout-url").attr("data-url");


  // Cart
  //
  
  // Add to cart
  jQuery("#add-to-cart #submit").click(function(event) {
    // Do not submit the form the classic way, only with AJAX
    event.preventDefault();
  
    // Display Ajax loading spinner
    jQuery(this).html(ajaxspinner);
    
    // Save this !!!
    var _this = jQuery(this);
    
    // Get query parameters
    var nonce = jQuery(this).parent().attr("data-nonce"); 
    var id = jQuery(this).parent().attr("data-id"); 
    
    // Do the ajax
    jQuery.post(
      ajaxurl, 
      {
        'action' : 'add_to_cart',
        'nonce' : nonce,
        'id' : id
      }, 
      function(response) {        
        //alert(response.message);
        _this.parent().next().html(response.message);       
      }
    );
    
  });
  
  
  // Remove cart item
  jQuery("#cart #items .remove-cart-item").click(function() {
    
    // Display Ajax loading spinner
    jQuery(this).html(ajaxspinner);
    
    // Save this !!!
    var _this = jQuery(this);
    
    // Get query parameters
    var nonce = jQuery(this).attr("data-nonce");    
    var id = jQuery(this).attr("data-id");
    
    // Do the ajax
    jQuery.post(
      ajaxurl, 
      {
        'action' : 'remove_cart_item',
        'nonce' : nonce,
        'id' : id
      }, 
      function(response) {        
        if (response.success) {     
          if (response.empty) {
            window.location.replace(checkout_url);
          } else {     
            _this.parent().parent().slideUp();
          }
        } else {
          _this.html(ajaxerror + response.message);
        } 
      }
    );
    
  });


  // Products
  //
  
  // Click on product image, index pages, to load product info
  jQuery(".home article .featured-image").click(function() {
  
    // Display Ajax loading spinner
    jQuery(".index article .entry .body").html(ajaxspinner);
    jQuery(".index article .entry .thumbs").html(ajaxspinner);
    
    // Get query parameters
    var nonce = jQuery(this).attr("data-nonce");    
    var id = jQuery(this).attr("data-id"); 
    
    // If this is icon view switch to mixed view
    if (jQuery(this).parent().parent().hasClass('icons')) {
      jQuery("#content article, #content .product-scroller, #product-info").removeClass('large mixed icons list');
      jQuery("#content article, #content .product-scroller, #product-info").addClass('mixed');
      // Remember to switch back on close
      jQuery("#product-info").attr("data-view", 'icons');
    } 
           
    
    // Do the ajax
    jQuery.post(
      ajaxurl, 
      {
        'action' : 'load_post_details',
        'nonce' : nonce,
        'post_id' : id
      }, 
      function(response) {
        if (response.success) {          
          jQuery("#product-info .body").html(response.body);
          jQuery("#product-info .thumbs").html(response.thumbs);
          
          jQuery("#sidebar").slideUp();
          jQuery("#product-info").slideDown();
        } else {
          jQuery("#product-info .body").html(ajaxerror + response.message);
          jQuery("#product-info .thumbs").html(ajaxerror + response.message);        
        } 
      }
    );
  });
  
  
  // Close product info on index page
  jQuery("#product-info .close").click(function() {
    // Switch back to icon view if necessary
    var view = jQuery(this).parent().attr("data-view");    
    if (view && (view != '')) {
      jQuery("#product-info").attr("data-view", '');
      jQuery("#content article, #content .product-scroller, #product-info").removeClass('large mixed icons list');
      jQuery("#content article, #content .product-scroller, #product-info").addClass(view);
    }
  
    jQuery("#product-info").slideUp();
    jQuery("#sidebar").slideDown();    
  });
  
  
  // Switch display type
  jQuery("#switch-view li").click(function() {
    if (jQuery(this).hasClass('active')) {
      jQuery("#switch-view li").slideDown();
    } else {
      jQuery("#switch-view li").removeClass('active');
      jQuery("#switch-view li").hide();
      jQuery(this).addClass('active');
      
      var neu = jQuery(this).attr("data-id");      
      jQuery("#content article, #content .product-scroller, #product-info").removeClass('large mixed icons list');
      jQuery("#content article, #content .product-scroller, #product-info").addClass(neu);
    }   
  });
 
  
  
  
  // Header
  //

  // Show price search in header
  jQuery("header #search #s").click(function() {
    jQuery(this).next().next().slideDown();
  });
  
  // Draw the logo
  function logo() {
    var matrix = new Array(7);
    for (y = 0; y < 7; y++) {
      matrix[y] = new Array(24);
      for (x = 0; x < 24; x++) {
        matrix[y][x] = '';
      }
    }
    
    matrix[6][0] = 'set';
    
    matrix[6][1] = 'set';
    
    matrix[6][2] = 'set';
    
    matrix[6][3] = 'set';
    
    matrix[6][4] = 'set';
    
    matrix[0][5] = 'set';
    matrix[1][5] = 'set';
    matrix[2][5] = 'set';
    matrix[3][5] = 'set';
    matrix[6][5] = 'set';
    
    matrix[0][6] = 'set';
    matrix[3][6] = 'set';
    matrix[6][6] = 'set';

    matrix[0][7] = 'set';
    matrix[3][7] = 'set';
    matrix[4][7] = 'set';
    matrix[5][7] = 'set';
    matrix[6][7] = 'set';
    
    matrix[0][8] = 'set';
    
    matrix[0][9] = 'set';
    
    matrix[0][10] = 'set';
    
    matrix[0][11] = 'set';
    matrix[1][11] = 'set';
    matrix[2][11] = 'set';
    matrix[3][11] = 'set';
    
    matrix[0][12] = 'set';
    
    matrix[0][13] = 'set';
    matrix[1][13] = 'set';
    matrix[2][13] = 'set';
    matrix[3][13] = 'set';
    
    matrix[0][14] = 'set';
    
    matrix[0][15] = 'set';
    matrix[1][15] = 'set';
    matrix[2][15] = 'set';
    matrix[3][15] = 'set';
    
    matrix[3][16] = 'set';
    
    matrix[1][17] = 'set';
    matrix[2][17] = 'set';
    matrix[3][17] = 'set';
        
    matrix[0][19] = 'set';
    matrix[1][19] = 'set';
    matrix[2][19] = 'set';
    matrix[3][19] = 'set';
    matrix[4][19] = 'set';
    matrix[5][19] = 'set';
    matrix[6][19] = 'set';
    
    matrix[0][20] = 'set';
    matrix[4][20] = 'set';    
    
    matrix[0][22] = 'set';
    matrix[1][22] = 'set';
    matrix[2][22] = 'set';
    matrix[3][22] = 'set';
    matrix[4][22] = 'set';
    matrix[5][22] = 'set';
    matrix[6][22] = 'set';
    
    matrix[0][23] = 'set';
    matrix[4][23] = 'set';
        
    var ret = "";
    var size = "";
    for (y = 0; y < 7; y++) {
      for (x = 0; x < 24; x++) {        
        switch(x) {
          case 18:
            size = 'small';
            break;          
          case 21:
            size = 'small';
            break;
          default:
            size = '';
        }      
        ret += "<div id='cell-" + x + "-" + y + "' class='cell " + size + matrix[y][x] + "'></div>";
      }
    }
    
    return ret;  
  }  
  jQuery("#logo").html(logo());
  
  
  
  // General functions
  //
  
   // Display the responsive image
  jQuery('noscript[data-large][data-small]').each(function(){
    var src = screen.width >= 500 ? jQuery(this).data('large') : jQuery(this).data('small');
    jQuery('<img src="' + src + '" alt="' + $(this).data('alt') + '" />').insertAfter($(this));
  });
  
  
  // Toggle the next element
  jQuery(".j-toggle").click(function() {
    jQuery(this).next().slideToggle();
  });

  
});
