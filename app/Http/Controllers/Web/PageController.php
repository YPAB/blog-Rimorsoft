<?php

namespace App\Http\Controllers\Web;

use App\Post;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
   public function blog (){
   	$posts = Post::orderBy('id', 'DESC')->where('status', 'PUBLISHED')->paginate(3);
   	return view ('web.posts', compact('posts'));
   }


   public function category($slug){

   	$category = Category::where('slug', $slug)->pluck('id')->first(); // con pluck solamente me devuelve el id sin embargo con first me devuelve la lista completa
   		$posts = Post::where('category_id',$category)
   		->orderBy('id', 'DESC')->where('status', 'PUBLISHED')->paginate(3);

   		return view ('web.posts', compact('posts'));
   }

     public function tag($slug){

   		$posts = Post::whereHas('tags', function($query) use($slug){
   			$query->where('slug',$slug);

   		})
   		->orderBy('id', 'DESC')->where('status', 'PUBLISHED')->paginate(3);
   		
   		return view ('web.posts', compact('posts'));
   }

   public function post($slug){

   	$post =Post::where('slug', $slug)->first();
   	return view('web.post', compact('post'));
   }


}
