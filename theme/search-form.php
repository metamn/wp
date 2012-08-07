<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="field" name="s" id="s" placeholder="Peste 300 de cadouri" />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="Cautare" />
	<ul id="search-price-list">
    <li><input id="search-price" type="radio" name="price" value="0-100000" checked/>Cautare fara pret</li>
    <li><input id="search-price" type="radio" name="price" value="0-100"/>< 100 RON</li>
    <li><input id="search-price" type="radio" name="price" value="100-250" />100 - 250 RON</li>
    <li><input id="search-price" type="radio" name="price" value="250-350" />250 - 350 RON</li>
    <li><input id="search-price" type="radio" name="price" value="350" />Banii nu conteaza!</li>  
  </ul>
</form>
