jQuery(document).ready(function(){

  
  // Display the responsive image
  jQuery('noscript[data-large][data-small]').each(function(){
    var src = screen.width >= 500 ? jQuery(this).data('large') : jQuery(this).data('small');
    jQuery('<img src="' + src + '" alt="' + $(this).data('alt') + '" />').insertAfter($(this));
  });
  
  
  // General functions
  //
  
  // Toggle the next element
  jQuery(".j-toggle").click(function() {
    jQuery(this).next().slideToggle();
  });

  
});
