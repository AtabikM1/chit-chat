<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\BaseService;
class PostController extends Controller
{
    protected $service;
    public function __construct(){
        $this->service = new BaseService(new Post);
    }
    public function index(){
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }
    public function store(Request $request){
        $validated = $request->validate([
            'content' => 'required',
        ]);
        $validated['user_id'] = auth()->id();
        $this->service->create($validated);
        return redirect()->route('posts.index');
    }
}
