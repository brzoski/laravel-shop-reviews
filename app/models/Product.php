<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function reviews()
	{
	    return $this->hasMany('App\Models\Review');
	}

	// The way average rating is calculated (and stored) is by getting an average of all ratings, 
	// storing the calculated value in the rating_cache column (so that we don't have to do calculations later)
	// and incrementing the rating_count column by 1

    public function recalculateRating()
    {
    	//$reviews = $this->reviews()->notSpam()->approved(); 
    	$reviews = $this->reviews(); 
	    $avgRating = $reviews->avg('rating');
		$this->rating_cache = round($avgRating, 1);
		$this->rating_count = $reviews->count();
    	$this->save();
    }
}
