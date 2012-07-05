<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
    <?php
	    /*
	     * Print the <title> tag based on what is being viewed.
	     */
	    global $page, $paged;

	    wp_title( '|', true, 'right' );

	    // Add the blog name.
	    bloginfo( 'name' );

	    // Add the blog description for the home/front page.
	    $site_description = get_bloginfo( 'description', 'display' );
	    if ( $site_description && ( is_home() || is_front_page() ) )
		    echo " | $site_description";

	    // Add a page number if necessary:
	    if ( $paged >= 2 || $page >= 2 )
		    echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	    ?>
    </title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <!-- Compass -->
    <link href="<?php bloginfo('stylesheet_directory')?>/assets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <link href="<?php bloginfo('stylesheet_directory')?>/assets/print.css" media="print" rel="stylesheet" type="text/css" />
    <!--[if IE]>
        <link href="<?php bloginfo('stylesheet_directory')?>/assets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <![endif]-->

            
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory')?>/assets/jquery.init.js"></script>
    
    <?php wp_head(); ?>
  </head>
  
  <body <?php body_class(); ?>>   
  
    <?php $session = manage_session(); ?>
    <?php 
      echo "Returning: $session->returning";
      echo "<br/>";
      echo "Timestamp: $session->timestamp";
      echo "<br/>";
      print_r($session->cliks);
    ?>
         
    <div class="container">
    
      <div id="ajax-url" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-loading="<?php bloginfo('stylesheet_directory')?>/assets/ajax-loader.gif" class="hidden"></div>
      <div id="checkout-url" data-url="<?php echo get_page_link($CART); ?>" class="hidden"></div>
      
      <header>
        <hgroup>
          <h1>
            <?php bloginfo( 'name' ); ?>
          </h1>
          <h2>
            <?php bloginfo( 'description' ); ?>
          </h2> 
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <div id="logo"></div>       
          </a>
        </hgroup>
      
        <nav> 
          <h3>Navigare principala si cautare</h3>    
          <div id="search">
            <?php include '_search-form.php' ?>            
          </div>
          <ul id="categories">
            <li><h4>Cadouri <span>></span></h4></li>
            <li><h4>Evenimente <span>></span></h4></li>
            <li><h4>Sarbatori <span>></span></h4></li>
            <li class="last"><h4>Servicii <span>></span></h4></li>
          </ul>   
        </nav>
        
        <aside>
          <h3>Cos cumparaturi si informatii</h3>
          <ul>
            <li>
              <div class="title j-toggle">Cos cumparaturi</div>
              <ul>
                <li><?php echo do_shortcode ('[eshop_cart_items showwhat="items"]');?></li>
                <li>Wishlist</li>
              </ul>
            </li>
            <li>
              <div class="title j-toggle">Contul meu</div>
              <ul>
                <li>Istoric comenzi</li>
                <li>Personalizare cadouri</li>
                <li>Planificare cadouri</li>
              </ul>
            </li>
            <li>
              <div class="title j-toggle">0740-456-127</div>
              <ul>
                <li>Politica de preturi</li>
                <li>Cum cumpar?</li>
                <li>Business si Media</li>
                <li>Despre noi</li>
                <li>Protectia consumatorilor</li>
                <li>Ajutor</li>
              </ul>
            </li>
          </ul>
        </aside>       
      </header>
    
  

