<?php
//Shop Redirect to merchant
Map::get( '/', 'home#index' );

Map::get( '/categories' . FORMAT, 'categories#categories' );
Map::get( '/brands' . FORMAT, 'categories#brands' );
Map::get( '/categories/(.*)' . FORMAT, 'categories#categories' );
Map::get( '/brands/(.*)' . FORMAT, 'categories#brands' );

// Map::get( '/category-list' . FORMAT, 'categories#categories' );
// Map::get( '/brand-list' . FORMAT, 'categories#brands' );
// Map::get( '/category-list/(.*)' . FORMAT, 'categories#categories' );
// Map::get( '/brand-list/(.*)' . FORMAT, 'categories#brands' );

Map::get( '/category/(.*)' . FORMAT, 'category#category' );
Map::get( '/brand/(.*)' . FORMAT, 'category#brand' );

//Map::get( '/cat/(.*)' . FORMAT, 'category#category' );
//Map::get( '/bnd/(.*)' . FORMAT, 'category#brand' );

Map::get( '/about-us' . FORMAT, 'staticpage#about' );
Map::get( '/contact-us' . FORMAT, 'staticpage#contact' );
Map::get( '/privacy-policy' . FORMAT, 'staticpage#privacy' );

Map::get( '/sitemap' . FORMAT, 'htmlSitemap#index' );

Map::get( '/search/(.*)', 'search#index' );

Map::get( '/shop/(.*)', 'shop#index' );
Map::get( '/error', 'error#index' );
Map::get( '/(.*)', 'product#index' );
