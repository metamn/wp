<?php

$cart = get_cart_items($_SESSION['eshopcart'.$blog_id]);

?>

<section id="cart">
  <?php if ($cart) { ?>   
    <ul>
      <li class="c1"></li>      
      <li class="c2"></li>
      <li class="c3">Cantitate</li>
      <li class="c4">Pret</li>
      <li class="c5">Total</li>
      <li class="last c6"></li>
    </ul>     
    <?php foreach ($cart as $item) { ?>
      <ul>
        <li class="c1"><?php echo $item->thumb ?></li>      
        <li class="c2"><?php echo $item->title ?></li>
        <li class="c3"><?php echo $item->qty ?></li>
        <li class="c4"><?php echo $item->price ?></li>
        <li class="c5"><?php echo get_cart_item_total($item->qty, $item->price) ?></li>
        <li class="last c6">[x] sterge</li>
      </ul>
    <?php } ?>
  <?php } else { ?>
    Cosul Dvs. este gol.
  <?php } ?>
</section>
