<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Session\Session;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'clearance'])->except('index', 'show');
    }


    public function index() {
        $posts = Post::orderby('id', 'desc')->paginate(5);
        return view('posts.index', compact('posts'));
    }


    public function create() {
        return view('posts.create');
    }


    public function store(Request $request) {

        //Validating title and body field
        $this->validate($request, [
            'title'=>'required|max:100',
            'body' =>'required',
        ]);

        $title = $request['title'];
        $body = $request['body'];

        $post = Post::create($request->only('title', 'body'));

        //Display a successful message upon save
        return redirect()->route('posts.index')->with('flash_message', 'Article,'. $post->title.' created');
    }


    public function show($id) {
        $post = Post::findOrFail($id); //Find post of id = $id

        return view ('posts.show', compact('post'));
    }


    public function edit($id) {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }


    public function update(Request $request, $id) {
        $this->validate($request, [
            'title'=>'required|max:100',
            'body'=>'required',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect()->route('posts.show', $post->id)->with('flash_message', 'Article, '. $post->title.' updated');
    }


    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('flash_message', 'Article successfully deleted');
    }

}


