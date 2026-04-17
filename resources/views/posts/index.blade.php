<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline - Private Board</title>
    <style>
        body { font-family: monospace; background-color: #fafafa; color: #111; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .compose-box { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background: #fff; }
        textarea { width: 100%; height: 80px; padding: 10px; border: 1px solid #999; box-sizing: border-box; font-family: monospace; resize: vertical; }
        button { padding: 8px 15px; background: #000; color: #fff; border: none; cursor: pointer; font-family: monospace; margin-top: 10px; }
        button.logout { background: #fff; color: #000; border: 1px solid #000; }
        .post { border: 1px solid #eee; padding: 15px; margin-bottom: 15px; background: #fff; }
        .post-author { font-weight: bold; margin-bottom: 5px; color: #555; }
        .post-time { font-size: 0.8em; color: #999; }
        .post-content { margin-top: 10px; white-space: pre-wrap; }
    </style>
</head>
<body>

<div class="header">
    <h2>[ TIMELINE ]</h2>
    <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="logout">LOGOUT [{{ auth()->user()->username }}]</button>
    </form>
</div>

<div class="compose-box">
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <textarea name="content" placeholder="Ketik sesuatu di sini... (maks 280 karakter)" maxlength="280" required></textarea>
        <button type="submit">POSTING</button>
    </form>
</div>

<div>
    @forelse($posts as $post)
        <div class="post">
            <div class="post-author">
                {{ '@' . $post->user->name }}
                <span class="post-time">- {{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="post-content">{{ $post->content }}</div>
        </div>
    @empty
        <div class="post" style="text-align: center; color: #888;">
            Belum ada data. Jadilah yang pertama.
        </div>
    @endforelse
</div>

</body>
</html>
