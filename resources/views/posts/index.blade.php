<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TALKTIME - Private Board</title>
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="header">
    <h2>[ TALKTIME ]</h2>
    <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="logout">LOGOUT [{{ auth()->user()->username }}]</button>
    </form>
</div>

<div class="compose-box">
    <form id="postForm">
        <textarea id="postContent" placeholder="Ketik sesuatu di sini... (maks 280 karakter)" maxlength="280" required></textarea>
        <button type="submit">POSTING</button>
    </form>
</div>

<div id="postsContainer">
    @forelse($posts as $post)
        <div class="post">
            <div class="post-author">
                {{ '@' . $post->user->name }}
                <span class="post-time">- {{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="post-content">{{ $post->content }}</div>
        </div>
    @empty
        <div id="emptyState" class="post" style="text-align: center; color: #888;">
            Belum ada data. Jadilah yang pertama.
        </div>
    @endforelse
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const postForm = document.getElementById('postForm');
        const postContent = document.getElementById('postContent');

        // Fungsi Helper untuk menyisipkan HTML Post baru secara agresif ke baris paling atas
        function appendPostToUI(username, time, content) {
            if (!content) return;

            const container = document.querySelector('#postsContainer');
            const empty = document.querySelector('#emptyState');

            if (empty) {
                empty.remove();
            }

            if (container) {
                // Membuat element DOM asli untuk memotong isolasi cache rendering browser
                const newPost = document.createElement('div');
                newPost.className = 'post';
                newPost.innerHTML = `
                    <div class="post-author">
                        @${username}
                        <span class="post-time">- ${time}</span>
                    </div>
                    <div class="post-content">${content}</div>
                `;

                // Paksa masukkan ke posisi paling atas
                container.insertBefore(newPost, container.firstChild);
                console.log('DOM dipaksa render ulang secara fisik untuk:', username);
            } else {
                console.error('Target postsContainer tidak ditemukan di layar!');
            }
        }

        // ==========================================
        // 1. KIRIM DATA VIA AJAX (AXIOS) - TANPA RELOAD
        // ==========================================
        if (postForm) {
            postForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const contentValue = postContent.value;
                if (!contentValue.trim()) return;

                try {
                    const response = await axios.post("{{ route('posts.store') }}", {
                        content: contentValue
                    });

                    const savedPost = response.data.data;

                    // Langsung masukkan ke UI si pengirim secara instan
                    appendPostToUI(savedPost.user.name, 'just now', savedPost.content);

                    // Bersihkan text area
                    postContent.value = '';
                } catch (error) {
                    console.error('Gagal mengirim postingan:', error);
                }
            });
        }

        // ==========================================
        // 2. TERIMA DATA VIA WEBSOCKET (LARAVEL ECHO)
        // ==========================================
        if (window.Echo) {
            window.Echo.channel('chat-room')
                .listen('MessageSent', function (e) {
                    console.log('Pesan baru mendarat di browser:', e);

                    try {
                        // Membaca dari struktur objek data dump Anda yang flat
                        const username = e.user ? e.user.name : 'Anonymous';
                        const content = e.post ? e.post.content : '';

                        // Eksekusi cetak fisik ke layar browser sebelah
                        appendPostToUI(username, 'just now', content);
                    } catch (err) {
                        console.error('Gagal memproses data ke UI:', err);
                    }
                });
        } else {
            console.error('Laravel Echo tidak terdefinisi di objek window!');
        }
    });
</script>
</body>
</html>
