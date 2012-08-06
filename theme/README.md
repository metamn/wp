# How it works?

1. Every user is identified with a cookie (ujs_user)
2. Every click is saved into database (wp_recommendation_engine)
3. The database has the following structure:

  1. id: unique id
  2. cookie: cookie id
  3. visits: a date array of visits
  4. clicks: an array of user clicks, including shopping cart and other ajax clicks
  
4. Eshop is only used to create products. Cart, checkout, history etc are done by custom code

 
