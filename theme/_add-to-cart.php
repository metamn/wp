<div class="add-to-cart">
  <?php 
    $session = load_session();
    if ($session->type == CONTACTABLE) {
      echo '1 click';
    } else {
      echo 'add to cart';
    }   
  ?>
</div>
