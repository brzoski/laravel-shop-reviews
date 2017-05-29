<?php

namespace App\Http\Controllers;

use DB;
use Validator;

use App\Http\Requests;
use Illuminate\Http\Request;

use  App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['products'] = Product::All();
        return view('index', $data);
    }

    public function products($id)
    {
        $data['product'] = Product::find($id);

    // Get all reviews that are not spam for the product and paginate them
        /*$reviews = $product->reviews()->with('user')->approved()->notSpam()->orderBy('created_at','desc')->paginate(100);*/


        $data['reviews'] = $data['product']->reviews()
        ->where('approved', 1)
        ->where('spam', 0)
        ->where('product_id', $id)
        ->orderBy('created_at','desc')
        ->paginate(100);

        return view('products.single', $data);

    }

    public function products_post($id, Request $request){

        $validator = Validator::make($request->all(), [
            'comment' => 'required|max:255',
            'rating'  => 'required|numeric',
        ]);


            $request['product_id'] = $id;
            $request['user_id'] = 1;

        if (!$validator->fails()) {
            // Insere
            DB::table('reviews')->insert( $request->except('_token') );

            // Atualiza Produto 
            $product = Product::find($id);
            $product->recalculateRating();

        return redirect('products/'.$id.'#reviews-anchor')->with('review_posted',true);
            
        } else {
            return redirect('products/'.$id.'#reviews-anchor')
                        ->withErrors($validator)
                        ->withInput();
        }


        //$request

        // If input passes validation - store the review in DB, otherwise return to product page with error message 
      /*  if ($validator->passes()) {
            $review->storeReviewForProduct($id, $input['comment'], $input['rating']);
            return Redirect::to('products/'.$id.'#reviews-anchor')->with('review_posted',true);
        }*/

       /* return Redirect::to('products/'.$id.'#reviews-anchor')->withErrors($validator)->withInput();*/




    }

}
