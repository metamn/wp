jQuery(document).ready(function(){

  
  // Display the responsive image
  jQuery('noscript[data-large][data-small]').each(function(){
    var src = screen.width >= 500 ? jQuery(this).data('large') : jQuery(this).data('small');
    jQuery('<img src="' + src + '" alt="' + $(this).data('alt') + '" />').insertAfter($(this));
  });
  
  
  // Draw the logo
  function logo() {
    var matrix = new Array(7);
    for (y = 0; y < 7; y++) {
      matrix[y] = new Array(19);
      for (x = 0; x < 19; x++) {
        matrix[y][x] = '';
      }
    }
    
    matrix[6][0] = 'set';
    
    matrix[6][1] = 'set';
    
    matrix[0][2] = 'set';
    matrix[1][2] = 'set';
    matrix[2][2] = 'set';
    matrix[3][2] = 'set';
    matrix[6][2] = 'set';
    
    matrix[0][3] = 'set';
    matrix[3][3] = 'set';
    matrix[6][3] = 'set';

    matrix[0][4] = 'set';
    matrix[3][4] = 'set';
    matrix[4][4] = 'set';
    matrix[5][4] = 'set';
    matrix[6][4] = 'set';
    
    matrix[0][5] = 'set';
    
    matrix[0][6] = 'set';
    matrix[1][6] = 'set';
    matrix[2][6] = 'set';
    matrix[3][6] = 'set';
    
    matrix[0][7] = 'set';
    
    matrix[0][8] = 'set';
    matrix[1][8] = 'set';
    matrix[2][8] = 'set';
    matrix[3][8] = 'set';
    
    matrix[0][9] = 'set';
    
    matrix[0][10] = 'set';
    matrix[1][10] = 'set';
    matrix[2][10] = 'set';
    matrix[3][10] = 'set';
    
    matrix[3][11] = 'set';
    
    matrix[1][12] = 'set';
    matrix[2][12] = 'set';
    matrix[3][12] = 'set';
        
    matrix[0][13] = 'set';
    matrix[1][13] = 'set';
    matrix[2][13] = 'set';
    matrix[3][13] = 'set';
    matrix[4][13] = 'set';
    matrix[5][13] = 'set';
    matrix[6][13] = 'set';
    
    matrix[0][14] = 'set';
    matrix[4][14] = 'set';    
    
    matrix[0][16] = 'set';
    matrix[1][16] = 'set';
    matrix[2][16] = 'set';
    matrix[3][16] = 'set';
    matrix[4][16] = 'set';
    matrix[5][16] = 'set';
    matrix[6][16] = 'set';
    
    matrix[0][17] = 'set';
    matrix[4][17] = 'set';
        
    var ret = "";
    for (y = 0; y < 7; y++) {
      for (x = 0; x < 19; x++) {
        var small = ' ';
        if (x == 15) {
          small = 'small ';
        }
        ret += "<div id='cell-" + x + "-" + y + "' class='cell " + small + matrix[y][x] + "'></div>";
      }
    }
    
    return ret;  
  }  
  jQuery("#logo").html(logo());
  
  
  
  // General functions
  //
  
  // Toggle the next element
  jQuery(".j-toggle").click(function() {
    jQuery(this).next().slideToggle();
  });

  
});
