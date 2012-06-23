<?php 
  // Decide if it is single page or index page
  if (is_single()) {
    $klass = '';
  } else {
    $klass = $view;
  } 
  
  // See if this is the first product in a list or not
  if ($count > 1) {
    $klass .= ' not-first';
    if ($count % 2 == 1 ) {
      $klass .= ' odd';
    }
  }  
  
  // Identify posts with a number / counter
  $klass .= " count-$count";
?>


<article <?php post_class($klass); ?>>
	<header>
		<h1>
		  <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		    <?php the_title(); ?>
		  </a>
		</h1>
	</header>
	<div class="entry">	    
    <div class="featured-image" data-id="<?php echo $post->ID ?>" data-nonce="<?php echo wp_create_nonce('load-post-details') ?>">
      <?php echo responsive_image($post->ID); ?>
    </div>	  
    <div class="thumbs">
      <?php if (is_single()) { echo post_thumbnails($post->ID, $post->post_title); } ?>
    </div>	  
	  <div class="shopping">
	    <?php echo do_shortcode('[eshop_addtocart id="'. $post->ID . '"]'); ?>	    
	  </div>
	  <div class="excerpt">
	    <?php the_excerpt(); ?>					
	  </div>
	  <div class="body">
	    <?php if (is_single()) { the_content(); } ?>
	  </div>
	</div>
</article>


