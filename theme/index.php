<?php get_header(); ?>

<section id="content" class="index"> 
  <header>
    <?php echo get_content_title(); ?>
  </header>
  <?php if ( have_posts() ) { ?>          
    <?php while ( have_posts() ) : the_post(); ?>		    
      <?php include 'article.php'; ?> 
      <?php //break; ?>   
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

<nav id="sidebar">
  <h3>Alte categorii</h3>  
  <ul id="switch-view">
    <li class="active">Imagini mari <span>></span></li>
    <li>Imagine mare cu icoane <span>></span></li>
    <li>Matrix <span>></span></li>
    <li>Icoane <span>></span></li>
    <li>Blog <span>></span></li>    
    <li>Tabela <span>></span></li>
  </ul>
  <ul id="categories">
    <li class="active">Cadouri noi</li>
    <li>Reduceri</li>
    <li>Cele mai vandute</li>
    <li>Livrare imediata</li>
    <li>Recomandari speciale pentru tine</li>
  </ul>
</nav>




<?php get_footer(); ?>
