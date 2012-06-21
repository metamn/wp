<article <?php post_class(); ?>>
	<header>
		<h1>
		  <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		    <?php the_title(); ?>
		  </a>
		</h1>
	</header>
	<div class="entry">	    
    <div class="featured-image" data-id="<?php echo $post->ID ?>" data-nonce="<?php echo wp_create_nonce('load-post-details') ?>">
      <?php echo responsive_image($post->ID);?>      
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
