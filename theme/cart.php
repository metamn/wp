<?php

$cart = get_cart_items();

?>

<section id="cart">
  <?php if ($cart) { ?>   
    <ul id="header">
      <li class="c1"></li>      
      <li class="c2"></li>
      <li class="c3">Cantitate</li>
      <li class="c4">Pret</li>
      <li class="c5">Total</li>
      <li class="last c6"></li>
    </ul>     
    
    <?php foreach ($cart as $item) { ?>
      <ul id="items">
        <li class="c1"><?php echo $item->thumb ?></li>      
        <li class="c2"><?php echo $item->title ?></li>
        <li class="c3"><?php echo $item->qty ?></li>
        <li class="c4"><?php echo $item->price ?></li>
        <li class="c5"><?php echo get_cart_item_total($item->qty, $item->price) ?></li>
        <li class="last c6">
          <div class="remove-cart-item" data-id="<?php echo $item->id ?>" data-nonce="<?php echo wp_create_nonce('remove-cart-item') ?>" >
            <span>[x]</span> renunta
          </div>
        </li>
      </ul>
    <?php } ?>
    
    <ul id="coupon">
      <li class="c1"></li>      
      <li class="c2">Introduceti codul cuponului</li>
      <li class="c3">....</li>
      <li class="c4"></li>
      <li class="c5"></li>
      <li class="last c6">Actualizare</li>
    </ul>
    
    <ul id="delivery">
      <li class="c1"></li>      
      <li class="c2">Metoda de livrare</li>
      <li class="c3">
        <ul>
          <li>Posta Romana, cu plata la livrare 4-6 zile</li>
          <li>Fan Courier, cu plata la livrare 24 ore</li>
          <li>Fan Courier, cu plata prin transfer bancar in avans 1-2 zile</li>
          <li>Ridicare din sediul Tg. Mures</li>
        </ul>
      </li>
      <li class="c4">
        <ul>
          <li>8.00 RON</li>
          <li>19.00 RON</li>
          <li>17.00 RON</li>
          <li>0.00 RON</li>
        </ul>
      </li>
      <li class="c5"></li>
      <li class="last c6">
        <ul>
          <li>..</li>
          <li>..</li>
          <li>..</li>
          <li>..</li>
        </ul>
      </li>
    </ul>
    
    <ul id="total">
      <li class="c1"></li>      
      <li class="c2">Total cu TVA</li>
      <li class="c3"></li>
      <li class="c4"></li>
      <li class="c5">123 RON</li>
      <li class="last c6"></li>
    </ul>
    
  <?php } else { ?>
    Cosul Dvs. este gol.
  <?php } ?>
</section>
