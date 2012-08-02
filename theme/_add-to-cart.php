<div class="add-to-cart">
  <?php 
    $session = load_session();
    $product = product($post->ID);
    
    echo "<form id='add-to-cart' method='post' data-nonce='";
    echo wp_create_nonce('add-to-cart');
    echo "'>";
    
    echo "<select id='product-variations'>";      
    foreach ($product->variations as $key => $v) {
      echo "<option value=" . $key . ">" . $v->name . " &mdash; " . $v->price . " RON</option>";
    }      
    echo "</select>";    
        
    if ($session->type == CONTACTABLE) {
      echo "<input id='submit' type='submit' value='Cumpara printr-un click'>";
    } else {
      echo "<input id='submit' type='submit' value='Adauga la cos'>";
    }   
    echo "</form>";
  ?>
  <div class="message"></div>
</div>
