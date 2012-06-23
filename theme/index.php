<?php get_header(); ?>

<section id="content"> 
  <header>
    <?php echo get_content_title(); ?>
  </header>
  <?php if ( have_posts() ) { $count = 1; ?>          
    <?php while ( have_posts() ) : the_post(); ?>		    
      <?php include 'article.php'; ?>                
	  <?php $count++; endwhile; ?>
	  
	  <div id="product-scroll-left" class="product-scroller">&lsaquo;</div>
    <div id="product-scroll-right" class="product-scroller">&rsaquo;</div>
	  
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

<aside id="product-info" class="with-icons">
  <h3>Product info</h3>
  <span class="close"> x </span>
  <div class="thumbs"></div>
  <div class="body"></div>  
</aside>

<aside id="shopping-info">
  <h3>Informatii shopping</h3>
</aside>


<?php get_footer(); ?>
