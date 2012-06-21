<article <?php post_class(); ?>>
	<header>
		<h1>
		  <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		    <?php the_title(); ?>
		  </a>
		</h1>
	</header>
	<div class="entry">	    
    <div class="featured-image" data-id="<?php echo $post->ID ?>">
      <?php echo responsive_image($post->ID);?>      
    </div>	  
    <div class="thumbs">
      <?php
        if (is_single()) {
          $images = post_attachments($post->ID);
          foreach ($images as $img) {
            $thumb = wp_get_attachment_image_src($img->ID, 'thumbnail'); 
            $large = wp_get_attachment_image_src($img->ID, 'full'); ?>
            <div class="item">
              <img src="<?php echo $thumb[0]?>" rev="<?php echo $large[0]?>" title="<?php the_title() ?>" alt="<?php the_title() ?>"/>
            </div>
          <?php } 
        }
      ?>
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
