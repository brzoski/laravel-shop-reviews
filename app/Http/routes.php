<?php

// Route for Homepage - displays all products from the shop
/*Route::get('/', function()
{
	$products = Models\Product::all();
	return View::make('index', array('products'=>$products));
});
*/


Route::get('/', 'HomeController@index');

// Route that shows an individual product by its ID

Route::get('products/{id}', 'HomeController@products');



// Route that handles submission of review - rating/comment
Route::post('products/{id}', 'HomeController@products_post');




Route::auth();

Route::get('/home', 'HomeController@index');
