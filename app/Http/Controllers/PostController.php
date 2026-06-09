<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\BaseService;
use App\Events\MessageSent; // <-- Import Event di sini

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

        // 1. Biarkan service membuat data
        $createdPost = $this->service->create($validated);

        // 2. Ambil fresh instance secara aman berdasarkan ID data yang baru dibuat
        $post = \App\Models\Post::with('user')->find($createdPost->id);

        if (!$post) {
            $post = \App\Models\Post::where('user_id', auth()->id())->latest()->first();
            $post->load('user');
        }

        // 3. Picu Broadcast
        broadcast(new MessageSent(auth()->user(), $post))->toOthers(); // .toOthers() agar pengirim tidak menerima double

        // 4. UBAH INI: Kembalikan JSON untuk JavaScript Anda
        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }
}
