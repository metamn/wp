<?php get_header(); ?>

<section id="content"> 
  <header><h3>Content</h3></header>
  <?php if ( have_posts() ) { ?>          
    <?php while ( have_posts() ) : the_post(); ?>		    
        
	  <?php endwhile; ?>
    <?php } else { ?>
    	<article class="not-found">
				<header>
					<h1>Not Found</h1>
				</header>
				<div class="entry">
					<p>No posts found.</p>					
				</div>
			</article>
	  <?php } ?>
</section>

<?php get_footer(); ?>
