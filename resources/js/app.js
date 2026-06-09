import './bootstrap';

window.Echo.channel('chat-room')
    // TAMBAHKAN TANDA TITIK di depan nama event sesuai log Reverb kamu
    .listen('.message.sent', (e) => {
        console.log('Pesan baru mendarat di browser:', e);

        const chatContainer = document.getElementById('chat-messages');
        const emptyChat = document.getElementById('empty-chat');

        if (chatContainer) {
            if (emptyChat) emptyChat.remove();

            // Sesuai log backend: "user": { "name": "biki" }
            const username = e.user.name;
            // Sesuai model Post kamu: $post->content dioper ke $this->post di event
            const content = e.post.content;

            const newMessageHtml = `
                <div class="post">
                    <div class="post-author">
                        @${username}
                        <span class="post-time">- baru saja</span>
                    </div>
                    <div class="post-content">${content}</div>
                </div>
            `;

            chatContainer.insertAdjacentHTML('afterbegin', newMessageHtml);
        }
    });

console.log('Laravel Echo siap di channel chat-room.');
