<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function store()
    {
        $data = request()->validate(['title' => 'required',
            'content' => 'required',]);
        $post = Post::create($data);
        return redirect('posts/' . $post->id);
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function update(Post $post)
    {
        $data = request()->validate([
            'title' => '',
            'content' => '',
        ]);
        $post->update($data);
        return redirect('posts/' . $post->id);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('posts');
    }
}
