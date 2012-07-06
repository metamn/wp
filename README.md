# Design

* 'See more products' clicks are under 1% => pagination/infinite scrolling is obsolete
* Responsive design is done via Sass
* Responsive images are done via noscript


# Conversion Rate

* CR halves with every step until the final purchase 

  * Amazon like 1-step make purchase is a must: http://www.lukew.com/ff/entry.asp?1579
  * Session stores checkout data and changes Add to cart to Make Purchase
  * Checkout fields are: name, e-mail, address, tel, message
  * Since they gave us the e-mail ask for create account 
   
* Validate email address in real-time: http://blog.kicksend.com/how-we-decreased-sign-up-confirmation-email-bounces-by-50/
* Offer free shipping as a service for returning customers, 
or above a limit, like Amazon Prime
* Psychological motivators: 

  * See how many others already purchased this product
  
* For offline campaigns: buy with a QR code (adds to cart & goes to checkout)





# Content

* we are the content server, we distribute to all other channels
(Twitter, FB) through our blog: http://radar.oreilly.com/m/2012/07/federated-social-web-own-website.html


# Security

* Scan My Site: http://www.scanmysite.net/



# To care about:

* HTML5 outliner 
* YSlow (now Grade A, 98 score)
  * The only B is "Add Expires Header" but it is managed by W3 Total cache
  * Gzip is not done due to Apache/php.ini configuration is not always possible 
  on every servers  
* Google Page Speed is 85/100, the last 4 are not ok


# Plugins

* W3 Total Cache: cache, minify, expires header
* WP-DBManager: table optimisation, backups
* eShop
  * hard to activate (show two panels in wp-admin, create new admin user, empty browser cache)



# Links

* Email validation & verification: http://blog.kicksend.com/how-we-decreased-sign-up-confirmation-email-bounces-by-50/
* Checkout: http://www.lukew.com/ff/entry.asp?1579
* Colors: http://www.joehallock.com/edu/COM498/index.html, http://blog.kissmetrics.com/color-psychology/
* Wordpress jQuery Ajax: http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/
* Responsive Images: http://blog.cloudfour.com/responsive-imgs-part-2/
* Responsive Sass: http://thesassway.com/intermediate/responsive-web-design-in-sass-using-media-queries-in-sass-32
* HTML5 structure: http://www.456bereastreet.com/archive/201104/html5_document_outline_revisited/
* Optimize WP: http://wp.tutsplus.com/tutorials/the-ultimate-quickstart-guide-to-speeding-up-your-wordpress-site/



