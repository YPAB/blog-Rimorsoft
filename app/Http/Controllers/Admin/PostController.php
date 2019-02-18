<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Post;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostUpdateRequest;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Filesystem\disk;

class PostController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'DESC')
        ->where('user_id',auth()->user()->id)
        ->paginate();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id'); 
        $tags = Tag::orderBy('name', 'ASC')->get(); 
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        //salvamos el post   
         $post= Post::create($request->all());

         //Image
         if($request->file('file')){
            $path = Storage::disk('public')->put('image',$request->file('file'));
            $post->fill(['file' => asset($path)])->save(); //asset nos crea una ruta completa... que crea image/jpg-png
         }

         // ------- Relacion post y las etiquetas ------
         //con sync lo que digo es que se sincronice perfectamente la relacion entre post y etiquetas
         //attach crea el registro de la relacion- da igual usar attach y sync---
         //detach para eliminar la relacion---- sync es la combinacion perfecta entre attach y detach
         $post->tags()->attach($request->get('tags'));


        return redirect()->route('posts.edit',$post->id)
        ->with('info', 'Entrada creada con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('pass', $post); // si es mio elimina el post (Control de Acceso)
        
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $post = Post::findOrFail($id);
          $this->authorize('pass', $post); // si es mio elimina el post (Control de Acceso)

          $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id'); 
          $tags = Tag::orderBy('name', 'ASC')->get(); 
          

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, $id)
    {
         $post = Post::findOrFail($id);
         $this->authorize('pass', $post); // si es mio elimina el post (Control de Acceso)

         $post->fill($request->all())->save();

          //Image
         if($request->file('file')){
            $path = Storage::disk('public')->put('image',$request->file('file'));
            $post->fill(['file' => asset($path)])->save(); //asset nos crea una ruta completa... que crea image/jpg-png
         }

         // ------- Relacion post y las etiquetas ------
         //con sync lo que digo es que se sincronice perfectamente la relacion entre post y etiquetas
         //detach para eliminar la relacion---- sync es la combinacion perfecta entre attach y detach
         $post->tags()->sync($request->get('tags'));

          return redirect()->route('posts.edit',$post->id)
        ->with('info', 'Entrada actualizada con exito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $post = Post::findOrFail($id);
         $this->authorize('pass', $post); // si es mio elimina el post (Control de Acceso)
         $post->delete();

         return back()->with('info','Eliminado correctamente');

    }
}
