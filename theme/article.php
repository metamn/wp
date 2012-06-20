<article <?php post_class(); ?>>
	<header>
		<h1>
		  <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		    <?php the_title(); ?>
		  </a>
		</h1>
	</header>
	<div class="entry">
	  <?php if (is_single() || is_page()) { ?>
	    <?php include 'article-single.php'; ?>
	  <?php } else { ?>	  
	    <div class="featured-image">
	      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
	        <?php echo responsive_image($post->ID);?>
	      </a>
	    </div>	  
		  <div class="excerpt">
		    <?php the_excerpt(); ?>					
		  </div>
		<?php } ?>
		  <div class="shopping">
		    <?php print_r(get_eshop_product($post->ID)); ?>
		  </div>
	</div>
</article>
