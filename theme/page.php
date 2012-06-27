<?php get_header(); ?>

<?php if ( have_posts() ) { ?>          
  <?php while ( have_posts() ) : the_post(); ?>		    
    <article <?php post_class($klass); ?>>
	    <header>
		    <h1>
		      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		        <?php the_title(); ?>
		      </a>
		    </h1>
	    </header>
	    <div class="entry">	            
	      <div class="body">
	        <?php the_content(); ?>
	      </div>
	    </div>
    </article>     
  <?php endwhile; ?>
  
  <?php if (is_page($CART)) { include 'cart.php'; } ?>
	  
<?php } else { ?>
	<?php include '_not_found.php'?>
<?php } ?>

<?php get_footer(); ?>
