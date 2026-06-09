import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';
window.axios = axios; // <-- BARIS INI WAJIB ADA

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    // OTOMATIS: Jika di server mendeteksi skema 'https', forceTLS wajib bernilai true
    forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: null,
});
// Daftarkan listener langsung di file kompilasi utama Vite
// Daftarkan listener langsung di file kompilasi utama Vite dengan nama kustom yang BENAR
window.Echo.channel('chat-room')
    .listen('.message.sent', (e) => { // <-- TAMBAHKAN TITIK DAN SESUAIKAN NAMANYA
        console.log('Pesan mendarat dengan valid di app.js:', e);

        const container = document.getElementById('postsContainer');
        if (container) {
            const empty = document.getElementById('emptyState');
            if (empty) empty.remove();

            // Sesuai objek dump Anda yang valid
            const username = e.user ? e.user.name : 'Anonymous';
            const content = e.post ? e.post.content : '';

            const postHTML = `
                <div class="post">
                    <div class="post-author">
                        @${username}
                        <span class="post-time">- just now</span>
                    </div>
                    <div class="post-content">${content}</div>
                </div>
            `;
            container.insertAdjacentHTML('afterbegin', postHTML);
            console.log('DOM berhasil dirender secara fisik untuk:', username);
        }
    });
